<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Settings')) {
    /**
     *
     */
    class TagGroups_Settings
    {
        /**
         * renders the top of all setting pages
         *
         * @param void
         * @return void
         */
        public static function add_header()
        {
            $view = new TagGroups_View('admin/settings_header');
            $view->set('admin_page_title', get_admin_page_title());
            $view->render();
        }

        /**
         * renders the bottom of all settings pages
         *
         * @param void
         * @return void
         */
        public static function add_footer()
        {
            $view = new TagGroups_View('admin/settings_footer');
            $view->render();
        }

        /**
         * gets the slug of the currently selected tab
         *
         * @param string $default
         * @return string
         */
        public static function get_active_tab($tabs)
        {

            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only tab selection
            if (isset($_GET['active-tab'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only tab selection
                return sanitize_title(wp_unslash($_GET['active-tab']));
            } else {
                $keys = array_keys($tabs);
                return reset($keys);
            }
        }

        /**
         * gets the HTML of the header of tabbed view
         *
         * @param string $default
         * @return string
         */
        public static function add_tabs($page, $tabs, $active_tab)
        {
            if (count($tabs) < 2) {
                return ( empty($label) ? '' : '<h2>' . $label . '</h2>' );
            }
            $view = new TagGroups_View('admin/settings_tabs');
            $view->set(array(
                'tabs'       => $tabs,
                'page'       => $page,
                'active_tab' => $active_tab,
            ));
            $view->render();
        }

        
        /**
         * renders a settings page: home
         *
         * @param void
         * @return void
         */
        public static function settings_page_home()
        {
            global  $tag_group_groups ;
            // Make very sure that only administrators can access this page
            if (!current_user_can('manage_options')) {
                wp_die("Capability check failed");
            }
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $public_taxonomies = TagGroups_Taxonomy::get_public_taxonomies();
            $tag_group_enable_groups = TagGroups_Options::get_option('tag_group_enable_groups', 1);
            self::add_header();
            $html = '';
            self::add_settings_help();
            $tabs = array();
            $tabs['taxonomies'] = '';
            $tabs = apply_filters('tag_groups_settings', $tabs);
            $active_tab = self::get_active_tab($tabs);
            ?>
            <div class="pp-columns-wrapper<?php echo (!TagGroups_Utilities::is_premium_plan()) ? ' pp-enable-sidebar' : '' ?>">
                <div class="pp-column-left">
                    <?php
                    self::add_tabs('tag-groups-settings', $tabs, $active_tab);
                    switch ($active_tab) {
                        case 'taxonomies':
                            $view = new TagGroups_View('admin/settings_taxonomies');
                            $view->set(array(
                                'public_taxonomies'  => $public_taxonomies,
                                'enabled_taxonomies' => $enabled_taxonomies,
                                'tag_group_enable_groups' => $tag_group_enable_groups,
                            ));
                            $view->render();
                            break;
                        default:
                            if (class_exists('TagGroups_Premium_Settings')) {
                                TagGroups_Premium_Settings::get_content($active_tab);
                            }
                            break;
                    }
                    ?>
                </div>
                <?php if (!TagGroups_Utilities::is_premium_plan()) : ?>
                    <div class="pp-column-right">
                        <?php do_action('tag_groups_settings_right_sidebar'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            self::add_footer();
        }
        /**
         * renders a settings page: back end
         *
         * @param void
         * @return void
         */
        public static function settings_page_back_end()
        {
            // Make very sure that only administrators can access this page
            if (!current_user_can('manage_options')) {
                wp_die("Capability check failed");
            }
            self::add_header();
            self::add_settings_help();
            $tabs = array();
            $tabs = apply_filters('tag_groups_settings_back_end_tabs', $tabs);
            $tabs['filters'] = __('Filters', 'tag-groups');
            if (TagGroups_Gutenberg::is_gutenberg_active()) {
                $tabs['gutenberg'] = __('Gutenberg', 'tag-groups');
            }
            if (TagGroups_WPML::is_multilingual()) {
                $tabs['multilingual'] = __('Multilingual', 'tag-groups');
            }
            $active_tab = self::get_active_tab($tabs);
            ?>
            <div class="pp-columns-wrapper<?php echo (!TagGroups_Utilities::is_premium_plan()) ? ' pp-enable-sidebar' : '' ?>">
                <div class="pp-column-left">
                    <?php
                    self::add_tabs('tag-groups-settings-back-end', $tabs, $active_tab);
                    switch ($active_tab) {
                        case 'filters':
                            $show_filter_posts = TagGroups_Options::get_option('tag_group_show_filter', 0);
                            $show_filter_tags = TagGroups_Options::get_option('tag_group_show_filter_tags', 0);
                            $view = new TagGroups_View('admin/settings_back_end_filters');
                            $view->set(array(
                                'show_filter_posts' => $show_filter_posts,
                                'show_filter_tags'  => $show_filter_tags,
                            ));
                            $view->render();
                            break;
                            // filters
                        // filters
                        case 'gutenberg':
                            $tag_group_server_side_render = TagGroups_Options::get_option('tag_group_server_side_render', 1);
                            $view = new TagGroups_View('admin/settings_back_end_gutenberg');
                            $view->set(array(
                                'tag_group_server_side_render' => $tag_group_server_side_render,
                            ));
                            $view->render();
                            break;
                            // gutenberg
                        // gutenberg
                        case 'multilingual':
                            $tag_group_multilingual_sync_groups = TagGroups_Options::get_option('tag_group_multilingual_sync_groups', 1);
                            $view = new TagGroups_View('admin/settings_back_end_multilingual');
                            $view->set(array(
                                'tag_group_multilingual_sync_groups' => $tag_group_multilingual_sync_groups,
                            ));
                            $view->render();
                            break;
                            // gutenberg
                        // gutenberg
                        default:
                            if (class_exists('TagGroups_Premium_Settings')) {
                                TagGroups_Premium_Settings::get_content($active_tab);
                            }
                            break;
                    }
                    ?>
                </div>
                <?php if (!TagGroups_Utilities::is_premium_plan()) : ?>
                    <div class="pp-column-right">
                        <?php do_action('tag_groups_settings_right_sidebar'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            self::add_footer();
        }

        /**
         * renders a settings page: front end
         *
         * @param void
         * @return void
         */
        public static function settings_page_front_end()
        {
            // Make very sure that only administrators can access this page
            if (!current_user_can('manage_options')) {
                wp_die("Capability check failed");
            }
            $default_themes = explode(',', TAG_GROUPS_BUILT_IN_THEMES);
            $tag_group_theme = TagGroups_Options::get_option('tag_group_theme', TAG_GROUPS_STANDARD_THEME);
            $tag_group_mouseover = TagGroups_Options::get_option('tag_group_mouseover', 0);
            $tag_group_collapsible = TagGroups_Options::get_option('tag_group_collapsible', 0);
            $tag_group_enqueue_jquery = TagGroups_Options::get_option('tag_group_enqueue_jquery', 1);
            $tag_group_html_description = TagGroups_Options::get_option('tag_group_html_description', 0);
            $tag_group_shortcode_widget = TagGroups_Options::get_option('tag_group_shortcode_widget');
            $tag_group_shortcode_enqueue_always = TagGroups_Options::get_option('tag_group_shortcode_enqueue_always', 1);
            self::add_header();
            self::add_settings_help();
            $tabs = array();
            $tabs['shortcodes'] = __('Shortcodes', 'tag-groups');
            $tabs['themes'] = __('Themes and Appearance', 'tag-groups');
            $tabs = apply_filters('tag_groups_settings_front_end_tabs', $tabs);
            $active_tab = self::get_active_tab($tabs);
            ?>
            <div class="pp-columns-wrapper<?php echo (!TagGroups_Utilities::is_premium_plan()) ? ' pp-enable-sidebar' : '' ?>">
                <div class="pp-column-left">
                    <?php
                    self::add_tabs('tag-groups-settings-front-end', $tabs, $active_tab);
                    switch ($active_tab) {
                        case 'shortcodes':
                            /**
                             * Let the premium plugin add own shortcode information.
                             */
                            $premium_shortcode_info = apply_filters('tag_groups_hook_shortcodes', '');
                            $view = new TagGroups_View('admin/settings_front_end_shortcodes');
                            $gutenberg_documentation_link = '';
                            $view->set(array(
                                'premium_shortcode_info'             => $premium_shortcode_info,
                                'tag_group_shortcode_enqueue_always' => $tag_group_shortcode_enqueue_always,
                                'tag_group_shortcode_widget'         => $tag_group_shortcode_widget,
                                'gutenberg_documentation_link'       => $gutenberg_documentation_link,
                            ));
                            $view->render();
                            break;
                        case 'themes':
                            $tag_group_html_description_options = array(
                                0 => __('remove', 'tag-groups') . ' ' . __('(recommended)', 'tag-groups'),
                                1 => __('keep', 'tag-groups'),
                                2 => __('use unfiltered_html filter', 'tag-groups'),
                            );
                            $view = new TagGroups_View('admin/settings_front_end_themes');
                            $view->set(array(
                                'default_themes'                     => $default_themes,
                                'tag_group_theme'                    => $tag_group_theme,
                                'tag_group_enqueue_jquery'           => $tag_group_enqueue_jquery,
                                'tag_group_mouseover'                => $tag_group_mouseover,
                                'tag_group_collapsible'              => $tag_group_collapsible,
                                'tag_group_html_description'         => $tag_group_html_description,
                                'tag_group_html_description_options' => $tag_group_html_description_options,
                            ));
                            $view->render();
                            break;
                        default:
                            if (class_exists('TagGroups_Premium_Settings')) {
                                TagGroups_Premium_Settings::get_content($active_tab);
                            }
                            break;
                    }
                    ?>
                </div>
                <?php if (!TagGroups_Utilities::is_premium_plan()) : ?>
                    <div class="pp-column-right">
                        <?php do_action('tag_groups_settings_right_sidebar'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            self::add_footer();
        }

        /**
         * renders a settings page: general
         *
         * @param void
         * @return void
         */
        public static function settings_page_general()
        {
            global  $tag_group_groups;

            // Make very sure that only administrators can access this page
            if (!current_user_can('manage_options')) {
                wp_die("Capability check failed");
            }
            $tag_group_reset_when_uninstall = TagGroups_Options::get_option('tag_group_reset_when_uninstall', 0);
            self::add_header();
            self::add_settings_help();
            $tabs = array();
            $tabs['first-aid'] = __('First Aid', 'tag-groups');
            $tabs['rest-api'] = __('REST API', 'tag-groups');
            $tabs['system'] = __('System Information', 'tag-groups');
            $tabs['debug'] = __('Debugging', 'tag-groups');
            $tabs['export_import'] = __('Export/Import', 'tag-groups');
            $tabs['reset'] = __('Reset', 'tag-groups');
            if (TagGroups_Utilities::is_premium_plan()) {
                $tabs['licences'] = __('Licences', 'tag-groups');
            }
            $tabs = apply_filters('tag_groups_settings_general_tabs', $tabs);
            $active_tab = self::get_active_tab($tabs);
            ?>
            <div class="pp-columns-wrapper<?php echo (!TagGroups_Utilities::is_premium_plan()) ? ' pp-enable-sidebar' : '' ?>">
                <div class="pp-column-left">
                    <?php
                    self::add_tabs('tag-groups-settings-general', $tabs, $active_tab);
                    switch ($active_tab) {
                        case 'first-aid':
                            if (
                                !empty($_POST['process-tasks']) &&
                                !empty($_POST['nonce']) && 
                                wp_verify_nonce(sanitize_key(wp_unslash($_POST['nonce'])), 'tag-groups-first-aid-nonce')
                            ) {
                                self::add_html_process();
                            } else {
                                $view = new TagGroups_View('admin/settings_troubleshooting_first_aid');
                                $view->set('tasks_migration', 'migratetermmeta');
                                $view->set('tasks_maintenance', 'fixgroups,fixmissinggroups,sortgroups');
                                $view->set('tag_group_show_filter_tags', TagGroups_Options::get_option('tag_group_show_filter_tags', 0));
                                $view->render();
                            }

                            break;
                
                        case 'licences':
                            if (class_exists('TagGroups_Premium_Settings') && method_exists('TagGroups_Premium_Settings', 'settings_page_licence')) {
                                TagGroups_Premium_Settings::settings_page_licence();
                            }
                
                
                            break;

                        case 'rest-api':
                            $view = new TagGroups_View('admin/settings_rest_api');
                            $view->set('group_public_api_access', TagGroups_Options::get_option('tag_group_enable_group_public_api_access', 0));
                            $view->set('terms_public_api_access', TagGroups_Options::get_option('tag_group_enable_terms_public_api_access', 0));
                            $view->render();

                            break;
                        case 'system':
                            $phpversion = phpversion();

                            if (version_compare($phpversion, '7.0.0', '<')) {
                                $php_upgrade_recommendation = true;
                            } else {
                                $php_upgrade_recommendation = false;
                            }

                            $active_theme = wp_get_theme();
                            $protocol = ( isset($_SERVER['HTTPS']) ? 'https://' : 'http://' );
                            $ajax_test_url = admin_url('admin-ajax.php', $protocol);
                            /* constants */
                            $wp_constants = array(
                                'WP_DEBUG',
                                'WP_DEBUG_DISPLAY',
                                'WP_DEBUG_LOG',
                                'ABSPATH',
                                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                                // 'WP_HOME',
                                'MULTISITE',
                                'WP_CACHE',
                                'COMPRESS_SCRIPTS',
                                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                                // 'FS_CHMOD_DIR',
                                // 'FS_CHMOD_FILE',
                                'FORCE_SSL_ADMIN',
                                'CM_UPDATE_CHECK',
                                'WP_MEMORY_LIMIT',
                                'WP_MAX_MEMORY_LIMIT',
                            );
                            sort($wp_constants);
                            $constants = get_defined_constants();
                            foreach ($constants as &$constant) {
                                if (isset($constant)) {
                                    $constant = self::echo_var($constant);
                                }
                            }
                            ksort($constants);
                            $benchmarks = array();
                            $benchmark['name'] = 'Database: tags (1000x read)';
                            $group_ids = $tag_group_groups->get_group_ids();
                            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
                            $start_time = microtime(true);
                            for ($i = 0; $i < 1000; $i++) {
                                $group_id = $group_ids[array_rand($group_ids)];
                                $tg_group = new TagGroups_Group($group_id);
                                $group_terms_ids_dummy = $tg_group->get_group_terms($enabled_taxonomies, false, 'ids');
                            }
                            $benchmark['value'] = sprintf('%d ms', 1000 * (microtime(true) - $start_time));
                            $benchmarks[] = $benchmark;
                            /**
                             * Prepare the cache here so that we can test if it persists beyond seesions
                             */
                            $tag_group_object_cache = TagGroups_Options::get_option('tag_group_object_cache', TagGroups_Object_Cache::WP_TRANSIENTS);
                            $object_cache_options = array(
                                1 => __('Transients', 'tag-groups'),
                                2 => __('Database', 'tag-groups'),
                                3 => __('Filesystem', 'tag-groups'),
                                9 => __('WP Object Cache', 'tag-groups'),
                            );
                            $cache_key = md5('benchmark');
                            foreach ($object_cache_options as $object_cache_option_id => $object_cache_option_name) {
                                TagGroups_Options::update_option('tag_group_object_cache', $object_cache_option_id);
                                do_action('tag_groups_hook_cache_set', $cache_key . '-efficacy-test', '');
                            }
                            TagGroups_Options::update_option('tag_group_object_cache', $tag_group_object_cache);
                            if (!defined('TAG_GROUPS_PLUGIN_IS_FREE') || !TAG_GROUPS_PLUGIN_IS_FREE) {
                            }
                            global  $wpdb ;
                            $db_info = $wpdb->db_server_info();
                            if (empty($db_info)) {
                                $db_info = 'unknown';
                            }
                            $view = new TagGroups_View('admin/settings_troubleshooting_system');
                            $view->set(array(
                                'phpversion'                 => $phpversion,
                                'php_upgrade_recommendation' => $php_upgrade_recommendation,
                                'db_info'                    => $db_info,
                                'wp_constants'               => $wp_constants,
                                'constants'                  => $constants,
                                'ajax_test_url'              => $ajax_test_url,
                                'active_theme'               => $active_theme,
                                'benchmarks'                 => $benchmarks
                            ));
                            $view->render();
                            break;
                        case 'debug':
                            $help_url = 'https://taxopress.com/docs/how-to-use-the-debug-log/';
                            $view = new TagGroups_View('admin/settings_troubleshooting_debug');
                            $verbose_is_on_hardcoded = defined('CM_DEBUG') && strtolower(CM_DEBUG) == 'verbose';
                            $verbose_is_on_option = (bool) TagGroups_Options::get_option('tag_group_verbose_debug', 0);
                            $view->set(array(
                                'debug_is_on'             => defined('WP_DEBUG') && WP_DEBUG,
                                'verbose_is_on'           => defined('CM_DEBUG') && $verbose_is_on_hardcoded || $verbose_is_on_option,
                                'help_url'                => $help_url,
                                'verbose_is_on_hardcoded' => $verbose_is_on_hardcoded,
                            ));
                            $view->render();
                            break;
                        case 'export_import':
                            $view = new TagGroups_View('admin/settings_tools_export_import');
                            $view->render();
                            break;
                        case 'reset':
                            $view = new TagGroups_View('admin/settings_tools_reset');
                            $view->set('tag_group_reset_when_uninstall', $tag_group_reset_when_uninstall);
                            $view->render();
                            break;
                        default:
                            if (class_exists('TagGroups_Premium_Settings')) {
                                TagGroups_Premium_Settings::get_content($active_tab);
                            }
                            break;
                    }
                    ?>
                </div>
                <?php if (!TagGroups_Utilities::is_premium_plan()) : ?>
                    <div class="pp-column-right">
                        <?php do_action('tag_groups_settings_right_sidebar'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            self::add_footer();
        }

        /**
         * Processes form submissions from the settings page
         *
         * @param void
         * @return void
         */
        public static function settings_page_actions()
        {
            global  $tag_group_groups ;

            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified in switch cases
            if (!empty($_REQUEST['tg_action'])) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended -- Sanitized in switch statement
                $tg_action = sanitize_key(wp_unslash($_REQUEST['tg_action']));
            } else {
                return;
            }

            // Make very sure that only administrators can do actions
            if (!current_user_can('manage_options')) {
                wp_die("Capability check failed");
            }

            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in switch cases
            if (isset($_POST['ok'])) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing -- Sanitized below
                $ok = sanitize_key(wp_unslash($_POST['ok']));
            } else {
                $ok = '';
            }

            switch ($tg_action) {
                case 'shortcode':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-shortcode-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-shortcode-nonce'])), 'tag-groups-shortcode')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }

                    if (isset($_POST['widget']) && $_POST['widget'] == '1') {
                        update_option('tag_group_shortcode_widget', 1);
                    } else {
                        update_option('tag_group_shortcode_widget', 0);
                    }


                    if (isset($_POST['enqueue']) && $_POST['enqueue'] == '1') {
                        update_option('tag_group_shortcode_enqueue_always', 1);
                    } else {
                        update_option('tag_group_shortcode_enqueue_always', 0);
                    }

                    TagGroups_Admin_Notice::add('success', __('Your settings have been saved.', 'tag-groups'));
                    break;
                case 'rest_api':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-rest-api-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-rest-api-nonce'])), 'tag-groups-rest-api')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    
                    $group_public_api_access = ( isset($_POST['group_public_api_access']) ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_enable_group_public_api_access', $group_public_api_access);

                    $terms_public_api_access = ( isset($_POST['terms_public_api_access']) ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_enable_terms_public_api_access', $terms_public_api_access);

                    TagGroups_Admin_Notice::add('success', __('Your settings has been saved.', 'tag-groups'));
                    break;
                case 'reset':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-reset-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-reset-nonce'])), 'tag-groups-reset')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }

                    if ($ok == 'yes') {
                        $tag_group_groups->reset_groups();
                        /**
                         * Remove filters
                         */
                        delete_option('tag_group_tags_filter');
                        TagGroups_Admin_Notice::add('success', __('All groups have been deleted and assignments reset.', 'tag-groups'));
                    }

                    break;
                case 'uninstall':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-uninstall-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-uninstall-nonce'])), 'tag-groups-uninstall')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }

                    if ($ok == 'yes') {
                        update_option('tag_group_reset_when_uninstall', 1);
                    } else {
                        update_option('tag_group_reset_when_uninstall', 0);
                    }

                    TagGroups_Admin_Notice::add('success', __('Your settings have been saved.', 'tag-groups'));
                    break;
                case 'theme':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-settings-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-settings-nonce'])), 'tag-groups-settings')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    $theme = '';
                    if (isset($_POST['theme'])) {
                        switch (sanitize_key(wp_unslash($_POST['theme']))) {
                            case 'own':
                                if (isset($_POST['theme-name'])) {
                                    $theme = sanitize_text_field(wp_unslash($_POST['theme-name']));
                                }
                                break;
                            case 'none':
                                $theme = '';
                                break;
                            default:
                                $theme = sanitize_text_field(wp_unslash($_POST['theme']));
                                break;
                        }
                    }
                    TagGroups_Options::update_option('tag_group_theme', $theme);
                    $mouseover = ( isset($_POST['mouseover']) && $_POST['mouseover'] == '1' ? 1 : 0 );
                    $collapsible = ( isset($_POST['collapsible']) && $_POST['collapsible'] == '1' ? 1 : 0 );
                    $html_description = ( isset($_POST['html_description']) ? (int) $_POST['html_description'] : 0 );
                    TagGroups_Options::update_option('tag_group_mouseover', $mouseover);
                    TagGroups_Options::update_option('tag_group_collapsible', $collapsible);
                    TagGroups_Options::update_option('tag_group_html_description', $html_description);
                    $tag_group_enqueue_jquery = ( isset($_POST['enqueue-jquery']) && $_POST['enqueue-jquery'] == '1' ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_enqueue_jquery', $tag_group_enqueue_jquery);
                    // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                    // TagGroups_Admin::clear_cache();
                    TagGroups_Admin_Notice::add('success', __('Your settings have been saved.', 'tag-groups'));
                    do_action('tag_groups_theme_saved');
                    break;
                case 'taxonomy':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-taxonomy-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-taxonomy-nonce'])), 'tag-groups-taxonomy')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }

                    if (isset($_POST['taxonomies'])) {
                        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized below
                        $taxonomies = wp_unslash($_POST['taxonomies']);

                        if (is_array($taxonomies)) {
                            $taxonomies = array_map('sanitize_text_field', $taxonomies);
                        } else {
                            $taxonomies = array( 'post_tag' );
                        }
                    } else {
                        $taxonomies = array( 'post_tag' );
                    }

                    $public_taxonomies = TagGroups_Taxonomy::get_public_taxonomies();
                    foreach ($taxonomies as $taxonomy_item) {
                        if (!in_array($taxonomy_item, $public_taxonomies)) {
                            die("Security check: taxonomies");
                        }
                    }
                    TagGroups_Taxonomy::update_enabled($taxonomies);
                    $tag_group_enable_groups = ( isset($_POST['tag_group_enable_groups']) ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_enable_groups', $tag_group_enable_groups);
                    TagGroups_Admin_Notice::add('success', __('Your settings have been saved.', 'tag-groups'));
                    break;
                case 'backend':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-backend-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-backend-nonce'])), 'tag-groups-backend')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    $show_filter_posts = ( isset($_POST['filter_posts']) ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_show_filter', $show_filter_posts);
                    $show_filter_tags = ( isset($_POST['filter_tags']) ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_show_filter_tags', $show_filter_tags);
                    TagGroups_Admin_Notice::add('success', __('Your settings have been saved.', 'tag-groups'));
                    break;
                case 'gutenberg':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-gutenberg-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-gutenberg-nonce'])), 'tag-groups-gutenberg')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    $tag_group_server_side_render = ( isset($_POST['tag_group_server_side_render']) ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_server_side_render', $tag_group_server_side_render);
                    TagGroups_Admin_Notice::add('success', __('Your settings have been saved.', 'tag-groups'));
                    break;
                case 'multilingual':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-multilingual-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-multilingual-nonce'])), 'tag-groups-multilingual')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    $tag_group_multilingual_sync_groups = ( isset($_POST['tag_group_multilingual_sync_groups']) ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_multilingual_sync_groups', $tag_group_multilingual_sync_groups);
                    TagGroups_Admin_Notice::add('success', __('Your settings have been saved.', 'tag-groups'));
                    break;
                case 'reset-tag-filter':
                    // check nonce
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-reset-tag-filter-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-reset-tag-filter-nonce'])), 'tag-groups-reset-tag-filter')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    /**
                     * Reset the group filter above the tags list
                     */
                    update_option('tag_group_tags_filter', array());
                    TagGroups_Admin_Notice::add('success', __('The filter on the tags page has been reset to show all tags.', 'tag-groups'));
                    break;
                case 'export':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-export-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-export-nonce'])), 'tag-groups-export')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    $export = new TagGroups_Export();
                    $export->process_options_for_export();
                    $export->process_terms_for_export();
                    $export->write_files();
                    $export->show_download_links();
                    break;
                case 'import':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-import-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-import-nonce'])), 'tag-groups-import')) {
                        die("Security check");
                    }
                    // Make very sure that only administrators can upload stuff
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    if (!function_exists('wp_handle_upload')) {
                        require_once ABSPATH . 'wp-admin/includes/file.php';
                    }
                    $import = new TagGroups_Import();
                    $import->determine_file_type();
                    $import->read_file();
                    $import->parse_and_save();
                    break;
                case 'debug':
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
                    if (!isset($_POST['tag-groups-debug-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-debug-nonce'])), 'tag-groups-debug')) {
                        die("Security check");
                    }
                    // Make sure that only administrators can save settings
                    if (!current_user_can('manage_options')) {
                        wp_die("Capability check failed");
                    }
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- Validated below
                    $verbose_debug = ( '1' == $_POST['verbose_debug'] ? 1 : 0 );
                    TagGroups_Options::update_option('tag_group_verbose_debug', $verbose_debug);

                    if ($verbose_debug) {
                        TagGroups_Error::log('[Tag Groups] Verbose logging has been turned on.');
                    } else {
                        TagGroups_Error::log('[Tag Groups] Verbose logging has been turned off.');
                    }

                    TagGroups_Admin_Notice::add('success', __('Your settings have been saved.', 'tag-groups'));
                    break;
                default:
                    // hook for premium plugin
                    do_action('tag_groups_hook_settings_action', $tg_action);
                    break;
            }
        }

        /**
         * Prepares variable for echoing as string
         *
         *
         * @param mixed $var Mixed type that needs to be echoed as string.
         * @return return string
         */
        private static function echo_var($var = null)
        {

            if (is_bool($var)) {
                return ( $var ? 'true' : 'false' );
            } elseif (is_array($var)) {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- Debug output for admin
                return print_r($var, true);
            } else {
                return (string) $var;
            }
        }

        /**
         * Returns an array that contains topics covered in the settings
         *
         * @param void
         * @return array
         */
        public static function get_setting_topics()
        {
            $public_taxonomies_slugs = TagGroups_Taxonomy::get_public_taxonomies();
            $public_taxonomies_names = array_map(array( 'TagGroups_Taxonomy', 'get_name_from_slug' ), $public_taxonomies_slugs);
            $topics = array(
                'taxonomies'      => array(
                'title'    => __('Taxonomies', 'tag-groups'),
                'page'     => 'tag-groups-settings',
                'keywords' => array_merge(array_keys($public_taxonomies_names), array_values($public_taxonomies_names), array( __('tag groups', 'tag-groups') )),
            ),
                'shortcodes'      => array(
                'title'    => __('Shortcodes', 'tag-groups'),
                'page'     => 'tag-groups-settings-front-end',
                'keywords' => array(
                __('tag cloud', 'tag-groups'),
                __('group info', 'tag-groups'),
                __('sidebar widget', 'tag-groups'),
                __('accordion', 'tag-groups'),
                __('tabs', 'tag-groups'),
                __('alphabetical', 'tag-groups'),
                __('post list', 'tag-groups'),
                'Gutenberg'
                ),
            ),
                'themes'          => array(
                'title'    => __('Themes and Appearance', 'tag-groups'),
                'page'     => 'tag-groups-settings-front-end',
                'keywords' => array(
                __('tag cloud', 'tag-groups'),
                'CSS',
                'style',
                'HTML',
                __('colors', 'tag-groups'),
                __('tag description', 'tag-groups')
                ),
            ),
                'filters'         => array(
                'title'    => __('Filters', 'tag-groups'),
                'page'     => 'tag-groups-settings-back-end',
                'keywords' => array( __('tag filter', 'tag-groups'), __('post filter', 'tag-groups') ),
            ),
                'export_import'   => array(
                'title'    => __('Export/Import', 'tag-groups'),
                'page'     => 'tag-groups-settings-general',
                'keywords' => array( __('backup', 'tag-groups') ),
            ),
                'reset'           => array(
                'title'    => __('Reset', 'tag-groups'),
                'page'     => 'tag-groups-settings-general',
                'keywords' => array( __('remove plugin', 'tag-groups'), __('remove data', 'tag-groups'), __('delete groups', 'tag-groups') ),
            ),
                'system'          => array(
                'title'    => __('System Information', 'tag-groups'),
                'page'     => 'tag-groups-settings-general',
                'keywords' => array(
                __('debugging', 'tag-groups'),
                __('PHP Version', 'tag-groups'),
                __('Ajax Test', 'tag-groups'),
                __('troubleshooting', 'tag-groups'),
                __('benchmarks', 'tag-groups'),
                __('speed test', 'tag-groups'),
                __('error', 'tag-groups'),
                __('testing', 'tag-groups')
                ),
            ),
                'debug'           => array(
                'title'    => __('Debugging', 'tag-groups'),
                'page'     => 'tag-groups-settings-general',
                'keywords' => array(
                __('debugging', 'tag-groups'),
                __('troubleshooting', 'tag-groups'),
                __('error', 'tag-groups'),
                __('testing', 'tag-groups'),
                __('help', 'tag-groups')
                ),
            ),
                'premium'         => array(
                'title'    => __('Tag Groups Pro', 'tag-groups'),
                'page'     => 'tag-groups-settings-premium',
                'keywords' => array(
                __('upgrade', 'tag-groups'),
                __('more groups', 'tag-groups'),
                __('posts', 'tag-groups'),
                __('tag cloud', 'tag-groups'),
                __('filter', 'tag-groups'),
                __('animated', 'tag-groups'),
                __('searchable', 'tag-groups'),
                'Shuffle Box',
                'Toggle Post Filter',
                'Dynamic Post Filter',
                'WooCommerce'
                ),
            ),
                'info'            => array(
                'title'    => __('Info', 'tag-groups'),
                'page'     => 'tag-groups-settings-about',
                'keywords' => array(
                __('author', 'tag-groups'),
                __('version', 'tag-groups'),
                __('contact', 'tag-groups'),
                __('about', 'tag-groups')
                ),
            ),
                'licenses'        => array(
                'title'    => __('Licenses', 'tag-groups'),
                'page'     => 'tag-groups-settings-about',
                'keywords' => array( __('Credits', 'tag-groups') ),
            ),
                'news'            => array(
                'title'    => __('Development News', 'tag-groups'),
                'page'     => 'tag-groups-settings-about',
                'keywords' => array( __('blog', 'tag-groups'), __('updates', 'tag-groups') ),
            ),
                'getting_started' => array(
                'title'    => __('First Steps', 'tag-groups'),
                'page'     => 'tag-groups-settings-first-steps',
                'keywords' => array( __('getting started', 'tag-groups'), __('introduction', 'tag-groups'), __('help', 'tag-groups') ),
            ),
                'setup_wizard'    => array(
                'title'    => __('Setup Wizard', 'tag-groups'),
                'page'     => 'tag-groups-settings-setup-wizard',
                'keywords' => array( __('getting started', 'tag-groups'), __('introduction', 'tag-groups'), __('sample', 'tag-groups') ),
            ),
                'first-aid'       => array(
                'title'    => __('First Aid', 'tag-groups'),
                'page'     => 'tag-groups-settings-general',
                'keywords' => array(
                __('troubleshooting', 'tag-groups'),
                __('migrate', 'tag-groups'),
                __('help', 'tag-groups'),
                __('problem', 'tag-groups'),
                __('tag filter', 'tag-groups')
                ),
            ),
            );
            if (TagGroups_Gutenberg::is_gutenberg_active()) {
                $topics['gutenberg'] = array(
                    'title'    => __('Gutenberg', 'tag-groups'),
                    'page'     => 'tag-groups-settings-back-end',
                    'keywords' => array( __('live block preview', 'tag-groups') ),
                );
            }
            if (TagGroups_WPML::is_multilingual()) {
                $topics['multilingual'] = array(
                    'title'    => __('Multilingual', 'tag-groups'),
                    'page'     => 'tag-groups-settings-back-end',
                    'keywords' => array( 'WPML', 'Polylang', __('translation', 'tag-groups') ),
                );
            }
            $topics = apply_filters('tag_groups_setting_topics', $topics);
            return $topics;
        }

        /**
         * Renders the widget where you can search for help
         *
         * @param void
         * @return void
         */
        public static function add_settings_help()
        {
            $topics = self::get_setting_topics();
            asort($topics);
            $view = new TagGroups_View('admin/settings_help');
            $view->set('topics', $topics);
            $view->render();
        }

        /**
         * adds the processing of tasks
         *
         * @param void
         * @return string
         */
        public static function add_html_process()
        {
            $tasks_whitelist = array(
                'migratetermmeta'  => __('Migrating the term meta', 'tag-groups'),
                'fixgroups'        => __('Fixing incorrect tag groups', 'tag-groups'),
                'fixmissinggroups' => __('Fixing incorrect groups in term meta', 'tag-groups'),
                'sortgroups'       => __('Sorting groups in term meta', 'tag-groups'),
                'checkbadterms'    => __('Checking the tag names', 'tag-groups'),
            );
            $totals = array();
            $languages = array();
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- Sanitized below
            $tasks = explode(',', wp_unslash($_POST['process-tasks']));
            $tasks = array_map('sanitize_title', $tasks);
            $tasks = array_intersect($tasks, array_keys($tasks_whitelist));

            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Admin context
            if (!empty($_POST['task-set-name'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Admin context
                $task_set_name = sanitize_text_field(wp_unslash($_POST['task-set-name']));
            } else {
                $task_set_name = '';
            }

            $task_html = '';
            foreach ($tasks as $key => $task) {
                $totals[$task] = TagGroups_Process::get_task_total($task);
                $view = new TagGroups_View('admin/settings_troubleshooting_process_task_no_language');
                $view->set(array(
                    'task'            => $task,
                    'tasks_whitelist' => $tasks_whitelist,
                ));
                $task_html .= $view->return_html();
            }
            /**
             * Add the Javascript part that loops through the tasks and through the chunks within each task
             *
             * Keep some timeout between the calls so that we don't block the browser
             * Parameters: chunk length and timeouts for chunk and task
             */
            $protocol = ( isset($_SERVER['HTTPS']) ? 'https://' : 'http://' );
            $ajax_link = admin_url('admin-ajax.php', $protocol);

            if (defined('TAG_GROUPS_CHUNK_SIZE')) {
                $chunk_length = (int) TAG_GROUPS_CHUNK_SIZE;
            } else {
                $chunk_length = 30;
            }


            if (defined('TAG_GROUPS_CHUNK_TIMEOUT')) {
                $timeout_chunk = (int) TAG_GROUPS_CHUNK_TIMEOUT;
            } else {
                $timeout_chunk = 10 * 1000;
                // 10 seconds - for really slow networks
            }


            if (defined('TAG_GROUPS_TASK_TIMEOUT')) {
                $timeout_task = (int) TAG_GROUPS_TASK_TIMEOUT;
            } else {
                $timeout_task = 5 * 60 * 1000;
                // 5 minutes - can be long, but user needs to keep window open
            }

            /**
             * The result messages will be revealed by the Javascript routine
             */
            $view = new TagGroups_View('admin/settings_troubleshooting_process');
            $view->set(array(
                'task_html'     => $task_html,
                'ajax_link'     => $ajax_link,
                'tasks'         => $tasks,
                'totals'        => $totals,
                'languages'     => $languages,
                'timeout_task'  => $timeout_task,
                'timeout_chunk' => $timeout_chunk,
                'chunk_length'  => $chunk_length,
                'task_set_name' => $task_set_name,
            ));
            $view->render();
        }
    }
}
