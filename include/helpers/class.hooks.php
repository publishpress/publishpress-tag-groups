<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2020 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Hooks')) {
    /**
     *
     */
    class TagGroups_Hooks
    {
        /**
         * does all initialization
         *
         * @var object
         */
        private $loader ;
        /**
         * enqueues js and css
         *
         * @var object
         */
        private $enqueue ;
        /**
         * Array of registered hooks for temporary removal
         *
         * @var array
         */
        private $hooks ;
        /**
         * constructor
         *
         * @param object $loader
         */
        public function __construct($loader = null)
        {
            if (!empty($loader)) {
                $this->loader = $loader;
            }
            $this->enqueue = new TagGroups_Enqueue();
        }

        /**
         * Runs when plugin is launched
         *
         * @return void
         */
        public function root_all()
        {
            global  $tag_group_terms ;
            /**
             * enable internationalization
             */
            if (!empty($this->loader)) {
                add_action('init', array( $this->loader, 'register_textdomain' ));
            }
            /**
             * Filter that allows to modify the term query and search for terms that have tags in the specified tag groups
             *
             * @param  array            arguments for WP Term Query
             * @param  array|int|string Tag       Group IDs (array of integers or comma-separated list of integers)
             * @param  string           Logic     relation between the Tag Group IDs (AND|OR)
             * @return array            arguments for WP Term Query
             */
            add_filter(
                'tag_groups_modify_term_query_args',
                array( $tag_group_terms, 'modify_query_args' ),
                10,
                3
            );
        }

        /**
         * Runs when plugin is launched and is_admin()
         *
         * @return void
         */
        public function is_admin()
        {
            add_action('admin_init', array( $this, 'admin_init' ));
            add_action('wp_ajax_tg_ajax_benchmark', array( 'TagGroups_Settings_Ajax', 'ajax_benchmark' ));
            add_action('admin_menu', array( 'TagGroups_Admin', 'register_menus' ));
            add_action('admin_menu', array( 'TagGroups_Admin', 'remove_submenus' ), 1000000000);
            add_action('admin_enqueue_scripts', array( $this->enqueue, 'admin_enqueue_scripts' ));
            /**
             * Display any admin notices from the queue
             */
            add_action('admin_notices', array( 'TagGroups_Admin_Notice', 'display' ));
            add_action('admin_post_tag_groups_download_export', array( 'TagGroups_Export', 'download_file' ));
            /**
             * Add own category to Gutenberg
             */
            add_filter('block_categories_all', array( 'TagGroups_Gutenberg', 'block_categories' ));
            /**
             * Processing routines in chunks with process bar
             */
            add_action('wp_ajax_tg_free_ajax_process', array( 'TagGroups_Process', 'tg_ajax_process' ));
            /**
             * #### Terms
             */
            /**
             * After a term has changed its groups, we must update the array of terms per group.
             */
            add_action('tag_groups_groups_of_term_saved', array( 'TagGroups_Group_Save_Handlers', 'schedule_clear_tag_groups_group_terms' ), 11);
            /**
             * After a term has been deleted, we might have to update the object cache
             */
            add_action('delete_term', array( 'TagGroups_Group_Save_Handlers', 'schedule_clear_tag_groups_group_terms' ), 20);
            /**
             * After a term has been edited, we need to refresh some transients
             */
            add_action('edited_term', array( 'TagGroups_Transients', 'clear_transients_for_frontend_features' ), 20);
            if (TagGroups_Options::get_option('tag_group_multilingual_sync_groups', 1)) {
                /**
                 * After a term has beend edited, we may have to sync the groups of all translations
                 */
                add_action(
                    'edited_term',
                    array( 'TagGroups_WPML', 'sync_groups' ),
                    20,
                    3
                );
            }
            /**
             * Plugin: Simple Custom Post Order
             */
            /**
             * After the term order has been changed, we need to refresh some transients
             */
            add_action('scp_update_menu_order_tags', array( 'TagGroups_Transients', 'clear_transients_for_frontend_features' ), 20);
            /**
             * Plugin: Custom Taxonomy Order
             */
            /**
             * After the term order has been changed, we need to refresh some transients
             */
            add_action('customtaxorder_update_order', array( 'TagGroups_Transients', 'clear_transients_for_frontend_features' ), 20);
            /**
             * #### Groups
             */
            /**
             *  After a term group order has possibly been changed, we should sort the groups in the term meta
             */
            add_action('term_group_saved', array( 'TagGroups_Term_Meta_Tools', 'sort_groups' ));
            /**
             * After a term group has been deleted, we must update the tag meta with the groups.
             */
            add_action('tag_groups_term_group_deleted', array( 'TagGroups_Term_Meta_Tools', 'remove_missing_groups' ));
            /**
             * #### Taxonomies
             */
            /**
             * After the taxonomies have been changed, we check if we must migrate all newly enabled tags.
             */
            add_action('tag_groups_taxonomies_saved', array( 'TagGroups_Cron_Handlers', 'maybe_schedule_term_migration' ), 10);
            /**
             * Rendering a shortcode for the Gutenberg block editor
             */
            // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
            // add_action('wp_ajax_tg_render_shortcode', array('TagGroups_Shortcode_Common', 'render'));
        }

        /**
         * Runs when plugin is launched and ! is_admin()
         *
         * @return void
         */
        public function not_is_admin()
        {
            // frontend stuff
            add_action('wp_enqueue_scripts', array( $this->enqueue, 'wp_enqueue_scripts' ));
            add_action('init', array( 'TagGroups_Shortcode_Statics', 'maybe_do_shortcode_in_widgets' ));
        }

        /**
         * Runs if is_admin() && on admin_init
         *
         * @return void
         */
        public function admin_init()
        {
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $tag_group_role_edit_tags = 'edit_pages';
            $tag_group_role_edit_groups = 'edit_pages';
            if (TagGroups_Utilities::is_premium_plan()) {
                $tag_group_role_edit_tags = TagGroups_Options::get_option('tag_group_role_edit_tags', 'edit_pages');
                $tag_group_role_edit_groups = TagGroups_Options::get_option('tag_group_role_edit_groups', 'edit_pages');
            }

            if (current_user_can($tag_group_role_edit_tags)) {
                $this->user_can_tag_group_role_edit_tags($enabled_taxonomies);
            }
            /**
             * extra columns on tags page
             */
            foreach ($enabled_taxonomies as $taxonomy) {
                add_filter("manage_edit-{$taxonomy}_columns", array( 'TagGroups_Admin', 'add_taxonomy_columns' ));
                add_filter(
                    "manage_{$taxonomy}_custom_column",
                    array( 'TagGroups_Admin', 'add_taxonomy_column_content' ),
                    10,
                    3
                );
            }
            add_action('admin_head', array( 'TagGroups_Admin', 'add_tag_page_styling' ));
            /**
             * sorting on tags page
             */
            add_filter(
                'terms_clauses',
                array( 'TagGroups_Admin', 'sort_taxonomy_columns' ),
                10,
                3
            );
            /**
             * filtering on tags page
             */
            add_action('load-edit-tags.php', array( 'TagGroups_Admin', 'do_filter_tags' ));
            add_action('admin_footer-edit-tags.php', array( 'TagGroups_Admin', 'filter_admin_footer' ));
            /**
             * Process data submitted from settings forms
             */
            add_action('in_admin_header', array( 'TagGroups_Settings', 'settings_page_actions' ));
            /**
             * Process data submitted from setup wizard forms
             */
            add_action('in_admin_header', array( 'TagGroups_Setup_Wizard', 'settings_page_actions_wizard' ));
            if (current_user_can($tag_group_role_edit_groups)) {
                $this->user_can_tag_group_role_edit_groups();
            }
            /**
             * Add link on Plugins page
             */
            add_filter("plugin_action_links_" . TAG_GROUPS_PLUGIN_BASENAME, array( 'TagGroups_Admin', 'add_plugin_settings_link' ));
            /**
             * Add post filter
             */
            add_action('restrict_manage_posts', array( 'TagGroups_Admin', 'add_post_filter' ));
            add_filter('parse_query', array( 'TagGroups_Admin', 'apply_post_filter' ));
            /**
             * Register Ajax handler for development feed
             */
            add_action('wp_ajax_tg_ajax_get_feed', array( 'TagGroups_Admin', 'ajax_get_feed' ));
            /**
             * Maybe add message about language-related recommendation
             */
            add_action('admin_notices', array( 'TagGroups_Admin', 'add_language_notice' ));
            /**
             * Add fall-back button to reset the group filter for tags
             */
            add_filter('admin_footer_text', array( 'TagGroups_Admin', 'add_admin_footer_text' ), 100);

            /**
             * Add the PublishPress footer to Tag Groups admin pages.
             */
            add_action('in_admin_footer', array( 'TagGroups_Admin', 'render_admin_footer' ));

            /**
             * Add the script for the jQuery tooltip plugin
             */
            add_filter('admin_footer_text', array( 'TagGroups_Admin', 'add_admin_footer_tooltip_script' ), 102);
        }

        /**
         * Functions that require permission tag_group_role_edit_tags
         *
         * @param  array  $enabled_taxonomies
         * @return void
         */
        private function user_can_tag_group_role_edit_tags($enabled_taxonomies)
        {
            /**
             * Add group menus when creating and editing tags
             */
            foreach ($enabled_taxonomies as $taxonomy) {
                add_action("{$taxonomy}_edit_form_fields", array( 'TagGroups_Admin', 'render_edit_tag_menu' ));
                add_action("{$taxonomy}_add_form_fields", array( 'TagGroups_Admin', 'render_new_tag_menu' ));
                add_filter(
                    "{$taxonomy}_row_actions",
                    array( 'TagGroups_Admin', 'expand_quick_edit_link' ),
                    10,
                    2
                );
            }
            add_action(
                'quick_edit_custom_box',
                array( 'TagGroups_Admin', 'quick_edit_tag' ),
                10,
                3
            );
            /**
             * Adding tag group menu to quick edit on Tags page
             */
            add_action('admin_footer-edit-tags.php', array( 'TagGroups_Admin', 'render_quick_edit_javascript' ));
            /**
             * Footer of bulk admin
             */
            add_action('admin_footer-edit-tags.php', array( 'TagGroups_Admin', 'bulk_admin_footer' ));
            /**
             * Processing bulk actions for tags
             */
            add_action('load-edit-tags.php', array( 'TagGroups_Admin', 'do_bulk_action' ));
            /**
             * If using WPML and translation not available, copies tag name to translation
             */
            add_action('create_term', array( 'TagGroups_Term_Save_Handlers', 'maybe_copy_term_group_to_translation' ), 20);
            /**
             * Saves the tag group; other tag info saved anyway
             */
            add_action('create_term', array( 'TagGroups_Term_Save_Handlers', 'save_term_group_without_tag_info' ));
            /**
             * Saves the tag group; need to save also other tag info
             */
            add_action('edit_term', array( 'TagGroups_Term_Save_Handlers', 'save_term_group_with_tag_info' ));
        }

        /**
         * Functions that require permission tag_group_role_edit_groups
         *
         * @return void
         */
        private function user_can_tag_group_role_edit_groups()
        {
            /**
             * Registers Ajax handlers to manage groups on Tag Group Admin page
             */
            add_action('wp_ajax_tg_ajax_manage_groups', array( 'TagGroups_Group_Admin', 'ajax_manage_groups' ));
        }

        /**
         * Wrapper for remove_all_filters(), returning previously registered functions
         *
         * @param  array|string $hook_names (array of comma-separated list)
         * @return void
         */
        public function remove_all_filters($hook_names)
        {
            if (empty($hook_names)) {
                return array();
            }
            if (!is_array($hook_names)) {
                $hook_names = array_map('trim', explode(',', $hook_names));
            }
            $this->hooks = array();
            foreach ($hook_names as $hook_name) {
                $this->hooks[] = array(
                    'name'        => $hook_name,
                    'subscribers' => $this->get_all_hooks($hook_name),
                );
                remove_all_filters($hook_name);
            }
        }

        /**
         * Restores all hook from remove_all_filters()
         *
         * @param  array  $hook_array
         * @return void
         */
        public function restore_hooks()
        {
            if (empty($this->hooks)) {
                return;
            }
            foreach ($this->hooks as $hooks) {
                foreach ($hooks['subscribers'] as $subscriber) {
                    add_filter(
                        $hooks['name'],
                        $subscriber['function'],
                        $subscriber['priority'],
                        $subscriber['accepted_args']
                    );
                }
            }
        }

        /**
         * List all hooks
         *
         * https://stackoverflow.com/a/26680808
         *
         * @param  string  $hook
         * @return array
         */
        public function get_all_hooks($hook = '')
        {
            global  $wp_filter ;
            $hooks = array();

            if (isset($wp_filter[$hook]->callbacks)) {
                array_walk($wp_filter[$hook]->callbacks, function ($callbacks, $priority) use (&$hooks) {
                    foreach ($callbacks as $id => $callback) {
                        $hooks[] = array_merge([
                            'id'       => $id,
                            'priority' => $priority,
                        ], $callback);
                    }
                });
            } else {
                return [];
            }

            foreach ($hooks as &$item) {
                /**
                 * skip if callback does not exist
                 *
                 */
                if (!is_callable($item['function'])) {
                    continue;
                }
                /**
                 * function name as string or static class method eg. 'Foo::Bar'
                 *
                 */

                if (is_string($item['function'])) {
                    $ref = ( strpos($item['function'], '::') ? new ReflectionClass(strstr($item['function'], '::', true)) : new ReflectionFunction($item['function']) );
                    $item['file'] = $ref->getFileName();
                    $item['line'] = ( get_class($ref) == 'ReflectionFunction' ? $ref->getStartLine() : $ref->getMethod(substr($item['function'], strpos($item['function'], '::') + 2))->getStartLine() );
                } elseif (is_array($item['function'])) {
                    $ref = new ReflectionClass($item['function'][0]);
                    // $item['function'][0] is a reference to existing object
                    $item['function'] = array( $item['function'][0], $item['function'][1] );
                    $item['file'] = $ref->getFileName();
                    $item['line'] = ( strpos($item['function'][1], '::') ? $ref->getParentClass()->getMethod(substr($item['function'][1], strpos($item['function'][1], '::') + 2))->getStartLine() : $ref->getMethod($item['function'][1])->getStartLine() );
                    // closures
                } elseif (is_callable($item['function'])) {
                    $ref = new ReflectionFunction($item['function']);
                    $item['file'] = $ref->getFileName();
                    $item['line'] = $ref->getStartLine();
                }
            }
            return $hooks;
        }
    }
}
