<?php

/**
 * Fired during plugin activation
 *
 * @link       hello@logistia.app
 * @since      1.1.0
 *
 * @package    Logistia
 * @subpackage Logistia/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.0
 * @package    Logistia
 * @subpackage Logistia/includes
 * @author     Fespore <contact@logistia.app>
 */
class Logistia_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.1.0
     */
    public static function activate()
    {

        try {
            $currentIntegration = Logistia_Activator::create_keys("Logistia", get_current_user_id(), "read_write");
            if ($currentIntegration['consumer_key'] != null && $currentIntegration['consumer_secret'] != null) {
                Logistia_Activator::signInUser($currentIntegration['consumer_key'], $currentIntegration['consumer_secret']);
            } else {
                echo 'Could not generate keys';
            }
        } catch (Exception $e) {
            echo 'Logistia caught exception: ',  $e->getMessage(), "\n";
        }
    }

    static function signInUser($consumerKey, $consumerSecret)
    {
        $url = 'https://api.logistia.app/app/woocommerce/createIntegration';
        $current_user = wp_get_current_user();

        $data = array(
            'domain' => get_option("siteurl"),
            'consumerKey' => $consumerKey,
            'consumerSecret' => $consumerSecret,
            'email' => $current_user->user_email,
            'name' => $current_user->display_name,
            'storeName' => get_bloginfo('name'),
        );

        $options = array(
            'http' => array(
                'header' => "Content-type: application/json",
                'method' => 'POST',
                'content' => json_encode($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */

        } else {
            $token = json_decode($result)->token;
            delete_option("logistia_auth_token");
            if ($token != null) {
                add_option("logistia_auth_token", $token, null, 'no');
            }
        }
    }

    protected static function check_if_logistia_has_integration()
    {
        global $wpdb;
        $query = "SELECT * FROM `{$wpdb->base_prefix}woocommerce_api_keys` WHERE description LIKE %s'%'";

        $results = $wpdb->get_results($wpdb->prepare($query, "Logistia"));


        foreach ($results as $page) {
            return array(
                'consumer_key' => $page->consumer_key,
                'consumer_secret' => $page->consumer_secret
            );
        }

        return array(
            'consumer_key' => null,
            'consumer_secret' => null
        );
    }

    protected static function create_keys($app_name, $app_user_id, $scope)
    {
        global $wpdb;

        $description = sprintf(
        /* translators: 1: app name 2: scope 3: date 4: time */
            __('%1$s - API %2$s (created on %3$s at %4$s).', 'woocommerce'),
            wc_clean($app_name),
            "Read/Write",
            date_i18n(wc_date_format()),
            date_i18n(wc_time_format())
        );
        $user = wp_get_current_user();

        // Created API keys.
        $permissions = in_array($scope, array('read', 'write', 'read_write'), true) ? sanitize_text_field($scope) : 'read';
        $consumer_key = 'ck_' . wc_rand_hash();
        $consumer_secret = 'cs_' . wc_rand_hash();

        $wpdb->insert(
            $wpdb->prefix . 'woocommerce_api_keys',
            array(
                'user_id' => $user->ID,
                'description' => $description,
                'permissions' => $permissions,
                'consumer_key' => wc_api_hash($consumer_key),
                'consumer_secret' => $consumer_secret,
                'truncated_key' => substr($consumer_key, -7),
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );

        return array(
            'key_id' => $wpdb->insert_id,
            'user_id' => $app_user_id,
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret,
            'key_permissions' => $permissions,
        );
    }


}
