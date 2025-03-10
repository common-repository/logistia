<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       hello@logistia.app
 * @since      1.1.0
 *
 * @package    Logistia
 * @subpackage Logistia/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Logistia
 * @subpackage Logistia/admin
 * @author     Fespore <contact@logistia.app>
 */
class Logistia_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.1.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.1.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.1.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.1.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Logistia_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Logistia_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/logistia-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.1.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Logistia_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Logistia_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/logistia-admin.js', array('jquery'), $this->version, false);

    }

    public function add_menus()
    {
        add_menu_page(
            'Logistia Route planner',
            'Logistia Route planner',
            'manage_options',
            'logistia',
            [$this, 'render'],
            plugins_url('logistia/assets/logo-icon.svg')
        );
    }

    public function render()
    {
        $authToken = get_option("logistia_auth_token");

        if ($authToken != null) {
            echo '<iframe id="logistiaFrame" class="logistiaFrame" src="https://portal.logistia.app/token?redirect=delivery&token=' . $authToken . '" frameborder="0" height="100%" width="100%"></iframe>';
        } else {
            echo '<iframe id="logistiaFrame" class="logistiaFrame" src="https://portal.logistia.app" frameborder="0" height="100%" width="100%"></iframe>';
        }

    }

}
