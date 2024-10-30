(function ($) {
    'use strict';

    $(window).load(function () {
        console.log(window.location.search);
        if (window.location != undefined && window.location.search != undefined && window.location.search.includes("logistia")) {
            // fill iframe 100% of the container height
            let update = document.getElementsByClassName("update-nag");
            if (update != undefined && update != null && update.length > 0) {
                update[0].style.display = 'none';
            }
            var documentHeight = document.getElementById('wpwrap').offsetHeight;
            let iframe = document.getElementById("logistiaFrame");
            iframe.style.height = documentHeight + "px";
            iframe.contentWindow.postMessage({
                type: "iframeLoaded",
                source: {
                    type: "wordpress",
                    location: getHomeUrl()
                }
            }, '*');
        }

    });

    function getHomeUrl() {
        var href = window.location.href;
        var index = href.indexOf('/wp-admin');
        var homeUrl = href.substring(0, index);
        return homeUrl;
    }

})(jQuery);
