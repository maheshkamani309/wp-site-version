<?php
/*
Plugin Name: TWC Version Change
Description: Append version string to URLs based on settings.
Version: 1.0
Author: The WebCoder
Description: This plugin is use to pass version after the page link.
Text Domain: twc_version_change
Author URI: https://the-webcoder.com/
*/

define('TWC_VERSION_STRING', 'twc_ver');

if (!class_exists('twcVersionChange')) {

    final class twcVersionChange
    {

        public static $instance = null;

        public function __construct()
        {
            add_action('admin_init', [$this, 'registerSettings']);
            add_action('admin_menu', [$this, 'registerOptionPage']);

            $enabled = get_option('twc_enable_version');
            if ($enabled) {
                add_filter('post_type_link',  [$this, 'addVersionToLink'], 10, 3);
                add_filter('post_link',  [$this, 'addVersionToLink'], 10, 3);
                add_filter('page_link',  [$this, 'addVersionToLink'], 10, 3);
                add_filter('template_redirect',  [$this, 'addVersionAndRedirect']);
                add_filter('script_loader_src',  [$this,'appendVersionToScripts'], 10, 1);
                add_filter('style_loader_src',  [$this,'appendVersionToStyles'], 10, 1);
            }
        }


        /**
         * To add version to the every link of the site
         */
        function addVersionAndRedirect()
        {
            $version = get_option('twc_version_string');
            $ip_addresses = explode(',', get_option('twc_ip_addresses'));
            $user_ip = $_SERVER['REMOTE_ADDR'];

            if ((!isset($_GET[TWC_VERSION_STRING]) &&  !empty($version)) && (in_array($user_ip, $ip_addresses) || empty($ip_addresses[0]))) {
                global $wp;
                $permalink = home_url(add_query_arg($_GET, $wp->request));
                $permalink = add_query_arg(TWC_VERSION_STRING, $version, $permalink);
                wp_redirect($permalink);
                exit;
            }
        }


        /**
         * To add version to the every link of the site
         */
        function addVersionToLink($permalink, $post, $leave_name = false)
        {
            $version = get_option('twc_version_string');
            $ip_addresses = explode(',', get_option('twc_ip_addresses'));
            $user_ip = $_SERVER['REMOTE_ADDR'];

            if (!empty($version) && (in_array($user_ip, $ip_addresses) || empty($ip_addresses[0]))) {
                $permalink = add_query_arg(TWC_VERSION_STRING, $version, $permalink);
            }
            return $permalink;
        }


        /**
         * To get the instance of class
         * 
         * @return Object of the class
         */
        public static function getInstance()
        {

            if (self::$instance == null) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         * Function to register settings
         */
        public function registerSettings()
        {
            register_setting('twc_options_group', 'twc_enable_version', '');
            register_setting('twc_options_group', 'twc_version_string', '');
            register_setting('twc_options_group', 'twc_ip_addresses', '');
            register_setting('twc_options_group', 'twc_enable_css_js_version', '');

            add_settings_section('twc_version_change_settings', __('Version Change Settings', 'twc_version_change'), array($this, 'settingsSectionCb'), 'twc_options_group');
            add_settings_field('twc_enable_version_checkbox', __('Enable Versioning', 'twc_version_change'), [$this, 'versionCheckbox'], 'twc_options_group', 'twc_version_change_settings');
            add_settings_field('twc_version_string_input', __('Version Number', 'twc_version_change'), [$this, 'versionStringInput'], 'twc_options_group', 'twc_version_change_settings');

            add_settings_section('twc_version_change_settings_ip_address', __('Add IP address', 'twc_version_change'), array($this, 'settingsSectionIpAddressCb'), 'twc_options_group');
            add_settings_field('twc_ip_addresses_input', __('IP Addresses', 'twc_version_change'), [$this, 'versionIpInput'], 'twc_options_group', 'twc_version_change_settings_ip_address');


            add_settings_section('twc_version_change_settings_js_css_address', __('Add version to css and js', 'twc_version_change'), array($this, 'settingsSectionCssJsCb'), 'twc_options_group');
            add_settings_field('twc_enable_version_css_js_checkbox', __('Versioning to CSS and JS?', 'twc_version_change'), [$this, 'versionCssJsCheckbox'], 'twc_options_group', 'twc_version_change_settings_js_css_address');
        }


        /**
         * Functionality for the IP string
         */
        public function versionIpInput($args)
        {
            $twc_ip_addresses = get_option('twc_ip_addresses');
?>
            <p class="description">
                <input class="regular-text" type="text" name="twc_ip_addresses" value="<?php echo $twc_ip_addresses; ?>">
            </p>
            <p><small> <?php esc_html_e('You IP Address:', 'twc_version_change'); ?> <?php echo $_SERVER['REMOTE_ADDR']; ?></small></p>
        <?php

        }

        /**
         * Functionality for the version string
         */
        public function versionStringInput($args)
        {
            $twc_version_string = get_option('twc_version_string');
        ?>
            <p class="description">
                <input placeholder="1.1.1" class="regular-text" pattern="[0-9].[0-9].[0-9]" type="text" name="twc_version_string" value="<?php echo $twc_version_string; ?>">
                <?php esc_html_e('Please enter values like 0.0.0, 1.2.3', 'twc_version_change'); ?>
            </p>
        <?php

        }

        /**
         * Functionality for the enable disable checkbox
         */
        public function versionCheckbox($args)
        {
            $twc_enable_version = get_option('twc_enable_version');
        ?>
            <p class="description">
                <input <?php echo (isset($twc_enable_version) && $twc_enable_version == 1) ? "checked" : ''; ?> class="regular-text" type="checkbox" name="twc_enable_version" value="1">
                <?php esc_html_e('If checkbox is checked then version will pass after each URL ', 'twc_version_change'); ?>
            </p>
        <?php
        }


        /**
         * Functionality for the enable disable checkbox
         */
        public function versionCssJsCheckbox($args)
        {
            $twc_enable_css_js_version = get_option('twc_enable_css_js_version');
        ?>
            <p class="description">
                <input <?php echo (isset($twc_enable_css_js_version) && $twc_enable_css_js_version == 1) ? "checked" : ''; ?> class="regular-text" type="checkbox" name="twc_enable_css_js_version" value="1">
                <?php esc_html_e('If checkbox is checked then version will pass after each css and js ', 'twc_version_change'); ?>
            </p>
        <?php
        }

        /**
         * Register option page to the admin menu
         */
        public function registerOptionPage()
        {
            add_options_page('The WebCoder Version Change', 'TWC Version Change', 'manage_options', 'twc_version_change', [$this, 'optionsPage']);
        }


        /**
         * Display option page settings
         */
        public function optionsPage()
        {
            if (!current_user_can('manage_options')) {
                return;
            }

        ?>
            <div class="wrap">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                <form action="options.php" method="post">
                    <?php
                    do_settings_sections('twc_options_group');
                    settings_fields('twc_options_group');
                    submit_button('Save Settings');
                    ?>
                </form>
            </div>
<?php
        }

        /**
         * Function to show description for the section
         */
        public function settingsSectionCb()
        {
            return '';
        }

        /**
         * Function to show description for the section
         */
        public function settingsSectionIpAddressCb()
        {
            printf('<p>%s</p>', __('Please enter ip addresses comma(,) separeted if you want add version for specific ip addresses. If you leave blank then it will add version for all user(visitors).', 'twc_version_change'));
        }


        /**
         * Function to show description for the section
         */
        public function settingsSectionCssJsCb()
        {
            printf('<p>%s</p>', __('If checkbox is checked then the plugin will add version to the css and js links', 'twc_version_change'));
        }

        /**
         * Append version string to all CSS files
         *
         * @param string $src The source URL of the enqueued style.
         * @return string Modified source URL with version parameter.
         */
        function appendVersionToStyles($src)
        {
            // Check if versioning is enabled
            if (! get_option('twc_versioning_enabled')) {
                return $src;
            }
            $version = get_option('twc_version_string', '1.0.0');
            $src = remove_query_arg('ver', $src);
            $src = add_query_arg('ver', $version, $src);

            return $src;
        }

        /**
         * Append version string to all JS files
         *
         * @param string $src The source URL of the enqueued script.
         * @return string Modified source URL with version parameter.
         */
        function appendVersionToScripts($src)
        {
            if (! get_option('twc_enable_css_js_version')) {
                return $src;
            }
            $version = get_option('twc_version_string', '1.0.0');
            $src = remove_query_arg('ver', $src);
            $src = add_query_arg('ver', $version, $src);
            return $src;
        }
    }

    // Initialize the functionality
    add_action('init', ['twcVersionChange', 'getInstance']);
}
