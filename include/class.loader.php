<?php

/**
 * Tag Groups
 *
 * @package Tag Groups
 *
 * @author    Christoph Amthor
 * @copyright 2019 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license   GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Loader')) {
    class TagGroups_Loader
    {
        /**
         * absolute path to the plugin main file
         *
         * @var string
         */
        public $plugin_path ;
        public function __construct($plugin_path)
        {
            $this->plugin_path = $plugin_path;
        }
        
        /**
         * Provide objects that we'll need frequently
         *
         * @param  void
         * @return object $this
         */
        public function provide_globals()
        {
            global  $tag_group_groups, $tag_group_terms ;
            $tag_group_groups = new TagGroups_Groups();
            $tag_group_terms = new TagGroups_Terms();
            return $this;
        }
        
        /**
         * Makes all required classes available through an autoloader
         *
         * @param  void
         * @return object $this
         */
        public function require_classes()
        {
            spl_autoload_register(function ($class_name) {

                if (strpos($class_name, 'TagGroups_') !== 0) {
                    return;
                }
                    
                if (class_exists($class_name)) {
                    return;
                }
                /**
                 * Directories are ordered according to priority
                 */
                $dirs = array(
                '/include/entities/',
                '/include/helpers/',
                '/include/helpers/cache/',
                '/include/admin/',
                '/include/admin/runners/',
                '/include/admin/handlers/',
                '/include/shortcodes/'
                );
                $class_name = str_replace('TagGroups_', '', $class_name);
                foreach ($dirs as $dir) {
                /**
                                         * We need to make all strings lower-case for different filesystems
                                         */
                    
                    if (file_exists($this->plugin_path . $dir . 'class.' . strtolower($class_name) . '.php')) {
                        include_once $this->plugin_path . $dir . 'class.' . strtolower($class_name) . '.php';
                        return;
                    }
                }
            });
            return $this;
        }
        
        /**
         * whether the installed version is newer than the latest recorded in the database
         *
         * @return boolean
         */
        public function is_version_update()
        {
            return version_compare($this->get_version(), $this->get_saved_version(), '>');
        }
        
        /**
         * Gets the version number of the installed files
         *
         * @return string
         */
        public function get_version()
        {
            $this->set_version();
            return TAG_GROUPS_VERSION;
        }
        
        /**
         * Gets the latest recorded version number
         *
         * @return string
         */
        public function get_saved_version()
        {
            return TagGroups_Options::get_option('tag_group_base_version', '1.0');
        }
        
        /**
         * Sets the version from the plugin main file
         *
         * @param  void
         * @return object $this;
         */
        public function set_version()
        {
            if (defined('TAG_GROUPS_VERSION')) {
                return;
            }
            if (!function_exists('get_plugin_data')) {
                include_once ABSPATH . '/wp-admin/includes/plugin.php';
            }
            $plugin_header = get_plugin_data(WP_PLUGIN_DIR . '/' . TAG_GROUPS_PLUGIN_BASENAME, false, false);
            if (isset($plugin_header['Version'])) {
                $version = $plugin_header['Version'];
            } else {
                $version = '1.0';
            }
            
            define('TAG_GROUPS_VERSION', $version);
        }
        
        /**
         * Check if WordPress meets the minimum version
         *
         * @param  void
         * @return void
         */
        public function check_preconditions()
        {
            if (!defined('TAG_GROUPS_MINIMUM_VERSION_WP')) {
                return;
            }
            global  $wp_version ;
    /**
                 * Check the minimum WP version
                 */
            
            if (version_compare($wp_version, TAG_GROUPS_MINIMUM_VERSION_WP, '<')) {
                TagGroups_Error::log('[Tag Groups] Insufficient WordPress version for Tag Groups plugin.');
                /* translators: %1$s is the plugin name, %2$s is the minimum WordPress version */
                TagGroups_Admin_Notice::add('error', sprintf(__('The plugin %1$s requires WordPress %2$s to function properly.', 'tag-groups'), '<b>PublishPress Tag Groups</b>', TAG_GROUPS_MINIMUM_VERSION_WP) . __('Please upgrade WordPress and then try again.', 'tag-groups'));
                return;
            }
        }
        
        /**
         * adds all hooks
         *
         * @param  void
         * @return object $this
         */
        public function add_hooks()
        {
            global  $tag_groups_hooks ;
            $tag_groups_hooks = new TagGroups_Hooks($this);
            $tag_groups_hooks->root_all();
            if (is_admin()) {
                $tag_groups_hooks->is_admin();
            } else {
                $tag_groups_hooks->not_is_admin();
            }
            
            return $this;
        }
        
        /**
         * registers the shortcodes with Gutenberg blocks
         *
         * @param  void
         * @return object $this
         */
        public function register_shortcodes_and_blocks()
        {
            if (defined('CM_UNIT_TESTING') && CM_UNIT_TESTING) {
                return;
            }
            /**
             * add Gutenberg functionality
             */
            include_once $this->plugin_path . '/src/init.php';
    // Register shortcodes also for admin so that we can remove them with strip_shortcodes in Ajax call
            TagGroups_Shortcode_Statics::register();
            return $this;
        }
        
        /**
         * registers the REST API
         *
         * @param  void
         * @return object $this
         */
        public function register_REST_API()
        {
            TagGroups_REST_API::register_hook();
            return $this;
        }
        
        /**
         * Loads text domain for internationalization
         */
        public function register_textdomain()
        {
            $domain       = 'tag-groups';
            $mofile_custom = sprintf('%s-%s.mo', $domain, get_user_locale());
            $locations = [
            trailingslashit(WP_LANG_DIR . '/' . $domain),
            trailingslashit(WP_LANG_DIR . '/loco/plugins/'),
            trailingslashit(WP_LANG_DIR),
            trailingslashit(plugin_dir_path(TAG_GROUPS_FILE) . 'languages'),
            ];
    // Try custom locations in WP_LANG_DIR.
            foreach ($locations as $location) {
                if (load_textdomain($domain, $location . $mofile_custom)) {
                    return true;
                }
            }
        }
        
        /**
         * registers the CRON routines
         *
         * @param  void
         * @return object $this
         */
        public function register_CRON()
        {
            // CRON independent from admin or frontend
            TagGroups_Cron::register_cron_handlers();
            TagGroups_Cron::schedule_regular('hourly', 'tag_groups_check_tag_migration');
    // schedule purging of expired transients
            TagGroups_Cron::schedule_regular('daily', 'tag_groups_purge_expired_transients');
            return $this;
        }
    }

}
