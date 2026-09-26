<?php

/**
 * @package Tag Groups
 *
 * @author    Christoph Amthor
 * @copyright 2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license   GPL-3.0+
 */

if (!class_exists('TagGroups_Admin')) {
    // phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps -- Legacy class structure
    class TagGroups_Admin
    {
        /**
         * Adds the submenus and the settings page to the admin backend
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function register_menus()
        {
            // Add the main menu
            add_menu_page(__('Home', 'tag-groups'), 'Tag Groups', 'manage_options', 'tag-groups-settings', array( 'TagGroups_Settings', 'settings_page_home' ), 'dashicons-tag', '99.01');
// Define the menu structure
            $tag_groups_admin_structure = array(
                0  => array(
                'title'    => __('Taxonomies', 'tag-groups'),
                'slug'     => 'tag-groups-settings',
                'parent'   => 'tag-groups-settings',
                'user_can' => 'manage_options',
                'function' => array( 'TagGroups_Settings', 'settings_page_home' ),
            ),
                3  => array(
                'title'    => __('Front End', 'tag-groups'),
                'slug'     => 'tag-groups-settings-front-end',
                'parent'   => 'tag-groups-settings',
                'user_can' => 'manage_options',
                'function' => array( 'TagGroups_Settings', 'settings_page_front_end' ),
            ),
                4  => array(
                'title'    => __('Back End', 'tag-groups'),
                'slug'     => 'tag-groups-settings-back-end',
                'parent'   => 'tag-groups-settings',
                'user_can' => 'manage_options',
                'function' => array( 'TagGroups_Settings', 'settings_page_back_end' ),
            ),
                9  => array(
                'title'    => __('First Steps', 'tag-groups'),
                'slug'     => 'tag-groups-settings-first-steps',
                'parent'   => null,
                'user_can' => 'manage_options',
                'function' => array( 'TagGroups_Setup_Wizard', 'settings_page_onboarding' ),
            ),
                10 => array(
                'title'    => __('Setup Wizard', 'tag-groups'),
                'slug'     => 'tag-groups-settings-setup-wizard',
                'parent'   => null,
                'user_can' => 'manage_options',
                'function' => array( 'TagGroups_Setup_Wizard', 'settings_page_setup_wizard' ),
            ),
                11  => array(
                    'title'    => __('Settings', 'tag-groups'),
                    'slug'     => 'tag-groups-settings-general',
                    'parent'   => 'tag-groups-settings',
                    'user_can' => 'manage_options',
                    'function' => array( 'TagGroups_Settings', 'settings_page_general' ),
            ),
            );
// hook for premium plugin to modify the menu
            $tag_groups_admin_structure = apply_filters('tag_groups_admin_structure', $tag_groups_admin_structure);
// make sure they all have the right order
            ksort($tag_groups_admin_structure);
// register the menus and pages
            foreach ($tag_groups_admin_structure as $tag_groups_admin_page) {
                add_submenu_page(!empty($tag_groups_admin_page['parent']) ? $tag_groups_admin_page['parent'] : '', $tag_groups_admin_page['title'], $tag_groups_admin_page['title'], $tag_groups_admin_page['user_can'], $tag_groups_admin_page['slug'], $tag_groups_admin_page['function']);
            }
            // for each registered taxonomy a tag group admin page
            $tag_group_taxonomies = TagGroups_Options::get_option('tag_group_taxonomy', array( 'post_tag' ));
            $tag_group_role_edit_groups = 'edit_pages';
            if (TagGroups_Utilities::is_premium_plan()) {
                $tag_group_role_edit_groups = TagGroups_Options::get_option('tag_group_role_edit_groups', 'edit_pages');
            }

            if (TagGroups_Options::get_option('tag_group_enable_groups', 1)) {
                $tag_group_post_types = TagGroups_Taxonomy::post_types_from_taxonomies($tag_group_taxonomies);
                foreach ($tag_group_post_types as $post_type) {
                    if ('post' == $post_type) {
                        $post_type_query = '';
                    } else {
                        $post_type_query = '?post_type=' . $post_type;
                    }

                    $submenu_page = add_submenu_page('edit.php' . $post_type_query, __('Tag Group Admin', 'tag-groups'), __('Tag Group Admin', 'tag-groups'), $tag_group_role_edit_groups, 'tag-groups_' . $post_type, array( 'TagGroups_Group_Admin', 'render_group_administration' ));
                    if (
                        TagGroups_Utilities::is_premium_plan()
                        && class_exists('TagGroups_Premium_Admin')
                        && method_exists('TagGroups_Premium_Admin', 'add_screen_option')
                    ) {
                        add_action("load-$submenu_page", array( 'TagGroups_Premium_Admin', 'add_screen_option' ));
                    }
                }
            }
        }

        /**
         * Remove one of the Freemius submenus
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function remove_submenus()
        {

            if (TagGroups_Utilities::is_free_plan()) {
                remove_submenu_page('tag-groups-settings', 'tag-groups-settings-contact');
            } else {
                remove_submenu_page('tag-groups-settings', 'tag-groups-settings-wp-support-forum');
            }
        }

        /**
         * Create the html to add tags to tag groups on single tag view (after clicking tag for editing)
         *
         * @param type $tag
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function render_edit_tag_menu($tag)
        {
            global $tag_group_groups ;
            $screen = get_current_screen();
            if ('post' == $screen->post_type) {
                $url_post_type = '';
            } else {
                $url_post_type = '&post_type=' . $screen->post_type;
            }

            $tag_group_admin_url = admin_url('edit.php?page=tag-groups_' . $screen->post_type . $url_post_type);
            $term_groups = $tag_group_groups->get_all_with_position_as_key();
            unset($term_groups[0]);
            $tg_term = new TagGroups_Term($tag);
            $view = new TagGroups_View('admin/edit_tag_main');
            if (TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_View')) {
                $view = new TagGroups_Premium_View('admin/edit_tag_main');
            }

            $view->set(array(
                'term_groups'         => $term_groups,
                'screen'              => $screen,
                'tg_term'             => $tg_term,
                'tag_group_admin_url' => $tag_group_admin_url,
                ));
            $view->render();
        }

        /**
         * Create the html to assign tags to tag groups upon new tag creation (left of the table)
         *
         * @param type $tag
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function render_new_tag_menu($tag)
        {
            global $tag_group_groups ;
            $screen = get_current_screen();
            $term_groups = $tag_group_groups->get_all_with_position_as_key();
            unset($term_groups[0]);
            $new_tag_initial_groups = array();
            if (TagGroups_Utilities::is_premium_plan()) {
                $new_tag_initial_groups = TagGroups_Options::get_option('tag_group_new_tag_default_groups', array());
            }

            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only operation for WPML translation context
            if (empty($new_tag_initial_groups) && TagGroups_WPML::is_multilingual() && isset($_GET['trid']) && !empty($_GET['taxonomy'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only operation for WPML translation context
                $trid = (int) $_GET['trid'];
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only operation for WPML translation context
                $taxonomy = sanitize_title(wp_unslash($_GET['taxonomy']));
                $translations = apply_filters('wpml_get_element_translations', null, $trid, "tax_{$taxonomy}");
                $default_lang = apply_filters('wpml_default_language', null);
                if (!empty($default_lang) && is_array($translations) && isset($translations[$default_lang])) {
                    $original_translation = $translations[$default_lang];
                } else {
                    $original_translation = TagGroups_Utilities::get_first_element($translations);
                }


                if (!empty($original_translation)) {
                    $tg_original_term = new TagGroups_Term($original_translation->element_id);
                    $new_tag_initial_groups = $tg_original_term->get_groups();
                }
            }

            $view = new TagGroups_View('admin/new_tag_from_list');
            if (TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_View')) {
                $view = new TagGroups_Premium_View('admin/new_tag_from_list');
            }

            $view->set(array(
                'term_groups'            => $term_groups,
                'screen'                 => $screen,
                'new_tag_initial_groups' => $new_tag_initial_groups,
                ));
            $view->render();
        }

        /**
         * adds a custom column to the table of tags/terms
         * thanks to http://coderrr.com/add-columns-to-a-taxonomy-terms-table/
         *
         * @global object $wp
         * @param  array $columns
         * @return string
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_taxonomy_columns($columns)
        {
            global  $wp ;
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only operation for column sorting
            $new_order = (isset($_GET['order']) && 'asc' == $_GET['order'] && isset($_GET['orderby']) && 'term_group' == $_GET['orderby'] ? 'desc' : 'asc');
            $screen = get_current_screen();
            if (!empty($screen)) {
                $taxonomy = $screen->taxonomy;
                $link = add_query_arg(
                    array(
                    'orderby'  => 'term_group',
                    'order'    => $new_order,
                    'taxonomy' => $taxonomy,
                    ),
                    admin_url("edit-tags.php" . $wp->request)
                );
                $link_escaped = esc_url($link);
                $columns['term_group'] = '<a href="' . $link_escaped . '"><span>' . __('Tag Groups', 'tag-groups') . '</span><span class="sorting-indicator"></span></a>';
            } else {
                $columns['term_group'] = '';
            }

            return $columns;
        }

        /**
         * adds data into custom column of the table for each row
         * thanks to http://coderrr.com/add-columns-to-a-taxonomy-terms-table/
         *
         * @param  type $a
         * @param  type $b
         * @param  type $term_id
         * @return string
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_taxonomy_column_content($content = '', $column_name = '', $term_id = 0)
        {
            global  $tag_group_groups ;
            if ('term_group' != $column_name) {
                return $content;
            }

            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only operation for taxonomy display
            if (!empty($_REQUEST['taxonomy'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only operation for taxonomy display
                $taxonomy = sanitize_title(wp_unslash($_REQUEST['taxonomy']));
            } else {
                return '';
            }

            $term = get_term($term_id, $taxonomy);
            if (isset($term)) {
                $term_o = new TagGroups_Term($term);
                return implode(', ', $tag_group_groups->get_labels_by_position($term_o->get_groups()));
            } else {
                return '';
            }
        }

        /**
         * Modify the term query so that we can sort by the term meta
         *
         * @param  [type] $pieces
         * @param  [type] $taxonomies
         * @param  [type] $args
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function sort_taxonomy_columns($pieces, $taxonomies, $args)
        {
            global  $wpdb ;
            $screen = get_current_screen();
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            if (empty($screen) || !in_array($screen->taxonomy, $enabled_taxonomies)) {
                return $pieces;
            }
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only operation for column sorting
            if (empty($_GET['orderby']) || 'term_group' != $_GET['orderby']) {
                return $pieces;
            }

            // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Read-only operation for column sorting
            if (isset($_GET['order']) && strtoupper(sanitize_key(wp_unslash($_GET['order']))) == 'DESC') {
                $order = "DESC";
            } else {
                $order = 'ASC';
            }

            $pieces['join'] .= ' INNER JOIN ' . $wpdb->termmeta . ' AS tm ON t.term_id = tm.term_id ';
            $pieces['where'] .= ' AND tm.meta_key = "_cm_term_group_array"';
            $pieces['orderby'] = ' ORDER BY tm.meta_value ';
            $pieces['order'] = $order;
            return $pieces;
        }

        /**
         * processing actions defined in bulk_admin_footer()
         * credits http://www.foxrunsoftware.net
         *
         * @global int $tg_update_edit_term_group_called
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function do_bulk_action()
        {
            global  $tg_update_edit_term_group_called ;
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $screen = get_current_screen();
            $taxonomy = $screen->taxonomy;
            if (is_object($screen) && !in_array($taxonomy, $enabled_taxonomies)) {
                return;
            }
            $show_filter_tags = TagGroups_Options::get_option('tag_group_show_filter_tags', 0);
            if ($show_filter_tags) {
                $tag_group_tags_filter = TagGroups_Options::get_option('tag_group_tags_filter', array());
            /**
                             * Processing the filter
                             * Values come as POST (via menu, precedence) or GET (via link from group admin)
                             */

                // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Filter operation
                if (isset($_POST['term-filter'])) {
                    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Filter operation
                    $term_filter = (int) $_POST['term-filter'];
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter operation
                } elseif (isset($_GET['term-filter'])) {
                    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter operation
                    $term_filter = (int) $_GET['term-filter'];
        // We need to remove the term-filter piece, or it will stay forever
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- URL manipulation
                    $sendback = remove_query_arg(array( 'term-filter' ), wp_unslash($_SERVER['REQUEST_URI']));
                }


                if (isset($term_filter)) {
                    if ('-1' == $term_filter) {
                        unset($tag_group_tags_filter[$taxonomy]);
                        TagGroups_Options::update_option('tag_group_tags_filter', $tag_group_tags_filter);
                    } else {
                        $tag_group_tags_filter[$taxonomy] = $term_filter;
                        TagGroups_Options::update_option('tag_group_tags_filter', $tag_group_tags_filter);
                    /*
                         * Modify the query
                         */
                        add_action('terms_clauses', array( 'TagGroups_Admin', 'modify_terms_query' ), 10, 3);
                    }


                    if (isset($sendback)) {
            // remove filter that destroys WPML's "&lang="
                        remove_all_filters('wp_redirect');
            // escaping $sendback
                        wp_redirect(esc_url_raw($sendback));
                        exit;
                    }
                } else {
    /**
                         * If filter is set, make sure to modify the query
                         */
                    if (isset($tag_group_tags_filter[$taxonomy])) {
                        add_action('terms_clauses', array( 'TagGroups_Admin', 'modify_terms_query' ), 10, 3);
                    }
                }
            }

            $wp_list_table = _get_list_table('WP_Terms_List_Table');
            $action = $wp_list_table->current_action();
            $allowed_actions = array( 'assign' );
            if (!in_array($action, $allowed_actions)) {
                return;
            }

            check_admin_referer('bulk-tags');

            if (isset($_REQUEST['delete_tags'])) {
                $term_ids = array_map('intval', (array) wp_unslash($_REQUEST['delete_tags']));
            }

            if (isset($_REQUEST['term-group-top'])) {
                $term_group = (int) sanitize_text_field(wp_unslash($_REQUEST['term-group-top']));
            } else {
                return;
            }

            $sendback = remove_query_arg(array( 'assigned', 'deleted' ), wp_get_referer());
            if (!$sendback) {
                $sendback = admin_url('edit-tags.php?taxonomy=' . $taxonomy);
            }

            if (empty($term_ids)) {
                $sendback = add_query_arg(
                    array(
                    'number_assigned' => 0,
                    'group_id'        => $term_group,
                    ),
                    $sendback
                );
                $sendback = remove_query_arg(
                    array(
                    'action',
                    'action2',
                    'tags_input',
                    'post_author',
                    'comment_status',
                    'ping_status',
                    '_status',
                    'post',
                    'bulk_edit',
                    'post_view'
                    ),
                    $sendback
                );
// escaping $sendback
                wp_redirect(esc_url_raw($sendback));
                exit;
            }

            $pagenum = $wp_list_table->get_pagenum();
            $sendback = add_query_arg('paged', $pagenum, $sendback);
            $tg_update_edit_term_group_called = true;
/**
             *  skip update_edit_term_group()
             */
            switch ($action) {
                case 'assign':
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    $assigned = 0;
                    foreach ($term_ids as $term_id) {
                        $term = new TagGroups_Term($term_id);
                        if (false !== $term) {
                            if (0 == $term_group) {
                                if ($term->get_groups() != array( 0 )) {
                                                $term->remove_all_groups()->save();
                                }
                            } else {
                                if (!in_array($term_group, $term->get_groups())) {
                                    $term->add_group($term_group)->save();
                                }
                            }

                            $assigned++;
                        }
                    }

                    if (0 == $term_group) {
                        $message = _n('The term has been removed from all groups.', sprintf('%d terms have been removed from all groups.', number_format_i18n((int) $assigned)), (int) $assigned, 'tag-groups');
                    } else {
                        $tg_group = new TagGroups_Group($term_group);
                        $message = _n(sprintf('The term has been assigned to the group %s.', '<i>' . $tg_group->get_label() . '</i>'), sprintf('%d terms have been assigned to the group %s.', number_format_i18n((int) $assigned), '<i>' . $tg_group->get_label() . '</i>'), (int) $assigned, 'tag-groups');
                    }


                    break;
                default:
                    // Need to show a message?
                 
                
                    exit;
                break;
            }
            TagGroups_Admin_Notice::add('success', $message);
            $sendback = remove_query_arg(
                array(
                'action',
                'action2',
                'tags_input',
                'post_author',
                'comment_status',
                'ping_status',
                '_status',
                'post',
                'bulk_edit',
                'post_view'
                ),
                $sendback
            );
            wp_redirect(esc_url_raw($sendback));
            exit;
        }

        /**
         * Filter the tags on the tag page
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function do_filter_tags()
        {
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $screen = get_current_screen();
            $taxonomy = $screen->taxonomy;
            if (is_object($screen) && !in_array($taxonomy, $enabled_taxonomies)) {
                return;
            }
            $show_filter_tags = TagGroups_Options::get_option('tag_group_show_filter_tags', 0);
            if (!$show_filter_tags) {
                return;
            }
            $tag_group_tags_filter = TagGroups_Options::get_option('tag_group_tags_filter', array());
/**
             * Processing the filter
             * Values come as POST (via menu, precedence) or GET (via link from group admin)
             */

            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Filter operation
            if (isset($_POST['term-filter'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Filter operation
                $term_filter = (int) $_POST['term-filter'];
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter operation
            } elseif (isset($_GET['term-filter'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Filter operation
                $term_filter = (int) $_GET['term-filter'];
            }


            if (isset($term_filter)) {
                if ('-1' == $term_filter) {
                    unset($tag_group_tags_filter[$taxonomy]);
                    TagGroups_Options::update_option('tag_group_tags_filter', $tag_group_tags_filter);
                } else {
                    $tag_group_tags_filter[$taxonomy] = $term_filter;
                    TagGroups_Options::update_option('tag_group_tags_filter', $tag_group_tags_filter);
                /*
                     * Modify the query
                     */
                    add_action('terms_clauses', array( 'TagGroups_Admin', 'modify_terms_query' ), 10, 3);
                }


                if (isset($sendback)) {
/**
                     * We need to remove the term-filter piece, or it will stay forever
                     *
                     * Also return to first page, trying to solve error "A variable mismatch has been detected."
                     */
                    $sendback = remove_query_arg(array( 'term-filter', 'paged' ));
/**
                     *  let WP use $_SERVER['REQUEST_URI'] and apply whitelisting etc. if desired
                     *
                     * remove filter that destroys WPML's "&lang="
                     */
                    remove_all_filters('wp_redirect');
// escaping $sendback
                    wp_redirect(esc_url_raw($sendback));
                    exit;
                }
            } else {
            /**
                             * If filter is set, make sure to modify the query
                             */
                if (isset($tag_group_tags_filter[$taxonomy])) {
                    add_action('terms_clauses', array( 'TagGroups_Admin', 'modify_terms_query' ), 10, 3);
                }
            }
        }

        /**
         * modifies Quick Edit link to call JS when clicked
         * thanks to http://shibashake.com/WordPress-theme/expand-the-WordPress-quick-edit-menu
         *
         * @param  array  $actions
         * @param  object $tag
         * @return array
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function expand_quick_edit_link($actions, $tag)
        {
            $screen = get_current_screen();
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            if (is_object($screen) && !in_array($screen->taxonomy, $enabled_taxonomies)) {
                return $actions;
            }
            $term_o = new TagGroups_Term($tag);
            $groups = htmlspecialchars(wp_json_encode($term_o->get_groups()));
            $nonce = wp_create_nonce('tag-groups-nonce');
            $actions['inline hide-if-no-js'] = '<a href="javascript:void(0)" class="editinline" title="';
            $actions['inline hide-if-no-js'] .= esc_attr(__('Edit this item inline', 'tag-groups')) . '" ';
            $actions['inline hide-if-no-js'] .= " onclick=\"set_inline_tag_group_selected('{$groups}', '{$nonce}')\">";
            $actions['inline hide-if-no-js'] .= __('Quick&nbsp;Edit', 'tag-groups');
            $actions['inline hide-if-no-js'] .= '</a>';
            return $actions;
        }

        /**
         * adds JS function that sets the saved tag group for a given element when it's opened in quick edit
         * thanks to http://shibashake.com/WordPress-theme/expand-the-WordPress-quick-edit-menu
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function render_quick_edit_javascript()
        {
            $screen = get_current_screen();
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            if (!in_array($screen->taxonomy, $enabled_taxonomies)) {
                return;
            }
            $view = new TagGroups_View('partials/quick_edit_javascript');
            if (TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_View')) {
                $view = new TagGroups_Premium_View('partials/quick_edit_javascript');
            }

            $view->render();
        }

        /**
         * Create the html to assign tags to tag groups directly in tag table ('quick edit')
         *
         * @return type
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function quick_edit_tag()
        {
            global  $tg_quick_edit_tag_called, $tag_group_groups ;
            if ($tg_quick_edit_tag_called) {
                return;
            }
            $tg_quick_edit_tag_called = true;
            $screen = get_current_screen();
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            if (!in_array($screen->taxonomy, $enabled_taxonomies)) {
                return;
            }
            $term_groups = $tag_group_groups->get_all_with_position_as_key();
            unset($term_groups[0]);
            $view = new TagGroups_View('partials/quick_edit_tag');
            if (TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_View')) {
                $view = new TagGroups_Premium_View('partials/quick_edit_tag');
            }

            $view->set(array(
                'term_groups' => $term_groups,
                'screen'      => $screen,
                ));
            $view->render();
        }

        /**
         * Adds a bulk action menu to a term list page
         * credits http://www.foxrunsoftware.net
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function bulk_admin_footer()
        {
            global  $tag_group_groups ;
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $screen = get_current_screen();
            if (is_object($screen) && !in_array($screen->taxonomy, $enabled_taxonomies)) {
                return;
            }
            $term_groups = $tag_group_groups->get_all_with_position_as_key();
            $view = new TagGroups_View('partials/bulk_admin_footer');
            $view->set(array(
                'term_groups' => $term_groups,
                ));
            $view->render();
        }

        /**
         * Adds a filter menu to a term list page
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function filter_admin_footer()
        {
            global  $tag_group_groups ;
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $screen = get_current_screen();
            if (is_object($screen) && !in_array($screen->taxonomy, $enabled_taxonomies)) {
                return;
            }
            if (!TagGroups_Options::get_option('tag_group_show_filter_tags', 0)) {
                return;
            }
            $term_groups = $tag_group_groups->get_all_with_position_as_key(true);
            $tag_group_tags_filter = TagGroups_Options::get_option('tag_group_tags_filter', array());
            if (isset($tag_group_tags_filter[$screen->taxonomy])) {
                $tag_filter = $tag_group_tags_filter[$screen->taxonomy];
                if ($tag_filter > 0) {
        // check if group exists (could be deleted since last time the filter was set)
                    $tg_group = new TagGroups_Group($tag_filter);
                    if (!$tg_group->exists()) {
                            $tag_filter = -1;
                    }
                }
            } else {
                $tag_filter = -1;
            }

            $view = new TagGroups_View('partials/filter_admin_footer');
            $view->set(array(
                'parents'     => $tag_group_groups->get_parents(),
                'term_groups' => $term_groups,
                'tag_filter'  => $tag_filter,
                ));
            $view->render();
        }

        /**
         * Adds a button to reset the filter on the tags page, in case JavaScript breaks
         *
         * @since 1.25.0
         *
         * @param  void
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_admin_footer_text($text)
        {
            $screen = get_current_screen();
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            if (!empty($screen) && 'edit-tags' == $screen->base && TagGroups_Options::get_option('tag_group_show_filter_tags', 0) && in_array($screen->taxonomy, $enabled_taxonomies)) {
                $view = new TagGroups_View('partials/admin_footer');
                $view->set('reset_url', esc_url(add_query_arg('term-filter', -1)));
                return $view->return_html() . $text;
            }

            return $text;
        }

        /**
         * Adds a button to reset the filter on the tags page, in case JavaScript breaks
         *
         * @since 1.25.0
         *
         * @param  string $text
         * @return string
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_admin_footer_rating_text($text)
        {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Read-only check for admin page
            if (empty($_GET['page']) || strpos(sanitize_key(wp_unslash($_GET['page'])), 'tag-groups') !== 0) {
                return $text;
            }

            $view = new TagGroups_View('partials/admin_footer_rating');
            $text = $view->return_html() . $text;
            return $text;
        }

        /**
         * Adds JS to the footer for nicer tooltips in the backend
         *
         * @since 1.43.5
         *
         * @param  string $text
         * @return string
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_admin_footer_tooltip_script($text)
        {
            $screen = get_current_screen();
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Read-only check for admin page
            if (empty($_GET['page']) || strpos(sanitize_key(wp_unslash($_GET['page'])), 'tag-groups') !== 0) {
            /* Tooltip doesn't work well in Gutenberg sidebar */
                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                // if ( ( empty( $_GET['page'] ) || strpos( $_GET['page'], 'tag-groups' ) !== 0 ) &&
                // ( ! is_object( $screen ) || ! property_exists( $screen, 'base' ) || 'post' != $screen->base ) ) {
                return $text;
            }
            $view = new TagGroups_View('partials/admin_footer_tooltip');
            return $text . $view->return_html();
        }

        /**
         * Adds a pull-down menu to the filters above the posts.
         * Based on the code by Ohad Raz, http://wordpress.stackexchange.com/q/45436/2487
         * License: Creative Commons Share Alike
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_post_filter()
        {
            global  $tag_group_groups ;
            if (!TagGroups_Options::get_option('tag_group_show_filter', 1)) {
                return;
            }
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filter display
            $post_type = (isset($_GET['post_type']) ? sanitize_title(wp_unslash($_GET['post_type'])) : 'post');
            if (count(array_intersect($enabled_taxonomies, get_object_taxonomies($post_type)))) {
                $term_groups = $tag_group_groups->get_all_term_group_label();
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filter display
                $current_term_group = (isset($_GET['tg_filter_posts_value']) ? sanitize_text_field(wp_unslash($_GET['tg_filter_posts_value'])) : '');
                $view = new TagGroups_View('admin/post_filter');
                $view->set(array(
                    'current_term_group' => $current_term_group,
                    'parents'            => $tag_group_groups->get_parents(),
                    'term_groups'        => $term_groups,
                    ));
                $view->render();
            }
        }

        /**
         * Applies the filter, if used.
         * Based on the code by Ohad Raz, http://wordpress.stackexchange.com/q/45436/2487
         * License: Creative Commons Share Alike
         *
         * @global type $pagenow
         * @param  type $query
         * @return type
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function apply_post_filter($query)
        {
            global  $pagenow, $tag_group_groups ;
            if ('edit.php' != $pagenow) {
                return $query;
            }
            $show_filter_posts = TagGroups_Options::get_option('tag_group_show_filter', 0);
            if (!$show_filter_posts) {
                return $query;
            }

            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filter application
            if (isset($_GET['post_type'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filter application
                $post_type = sanitize_title(wp_unslash($_GET['post_type']));
            } else {
                $post_type = 'post';
            }

            /**
             * Losing here the filter by language from Polylang, but currently no other way to show any posts when combining tax_query and meta_query
             */
            unset($query->query_vars['tax_query']);
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
// note: removed restriction count( $tg_taxonomy ) <= 1 - rather let user figure out if the result works
            $taxonomy_intersect = array_intersect($enabled_taxonomies, get_object_taxonomies($post_type));
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filter application
            if (count($taxonomy_intersect) && isset($_GET['tg_filter_posts_value']) && '' !== $_GET['tg_filter_posts_value']) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filter application
                $group_id = (int) $_GET['tg_filter_posts_value'];
                if (TagGroups_Utilities::is_premium_plan()) {
                    $group_ids = $tag_group_groups->expand_parents(array( $group_id ));

                    if (count($group_ids) == 0) {
                        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Intentional meta_query for post filtering by tag groups
                        $query->query_vars['meta_query'] = array(
                            array(
                                'key'     => '_cm_post_terms_dummy',
                                'compare' => 'EXISTS',
                            ),
                        );
                    } elseif (count($group_ids) == 1) {
                        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Intentional meta_query for post filtering by tag groups
                        $query->query_vars['meta_query'] = array(
                            array(
                                'key'     => '_cm_post_terms_' . $group_ids[0],
                                'compare' => 'EXISTS',
                            ),
                        );
                    } else {
                        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Intentional meta_query for post filtering by tag groups
                        $query->query_vars['meta_query'] = array(
                            'relation' => 'OR',
                        );

                        foreach ($group_ids as $group_id) {
                            $query->query_vars['meta_query'][] = array(
                                'key'     => '_cm_post_terms_' . $group_id,
                                'compare' => 'EXISTS',
                            );
                        }
                    }

                    return $query;
                }

                $tg_group = new TagGroups_Group($group_id);
                $tags = $tg_group->get_group_terms($taxonomy_intersect, true, 'ids');
                if (empty($tags)) {
        /**
                             * We use a workaround to render an empty list
                             */
                    $query->query_vars['tag__in'] = array( 0 );
                } else {
                    $query->query_vars['tag__in'] = $tags;
                }
            }

            return $query;
        }

        /**
         * AJAX handler to get a feed
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function ajax_get_feed()
        {
            if (!current_user_can('manage_options')) {
                wp_die(-1, 403);
            }

            check_ajax_referer('tg_get_feed_nonce', 'nonce');

            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- AJAX handler
            if (isset($_REQUEST['url'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- AJAX handler
                $url = esc_url_raw(wp_unslash($_REQUEST['url']));
            } else {
                $url = '';
            }


            if (strpos($url, 'https://chattymango.com/') !== 0) {
                TagGroups_Error::log('[Tag Groups] Wrong feed URL: ' . $url);
                TagGroups_Utilities::die();
            }


            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- AJAX handler
            if (isset($_REQUEST['amount'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- AJAX handler
                $amount = (int) $_REQUEST['amount'];
            } else {
                $amount = 5;
            }
            $amount = max(1, min(20, $amount));

            /**
             * Assuming that the posts URL is the $url minus the trailing /feed
             */
            $posts_url = preg_replace('/(.+)feed\\/?/i', '$1', $url);
            $rss = new TagGroups_Feed();
            if (defined('WP_DEBUG')) {
                $rss->set_debug(WP_DEBUG);
            }
            $rss->set_url($url)->set_posts_url($posts_url)->set_amount($amount);
            wp_send_json($rss->get_html());
        }

        /**
         * Modifies the query to retrieve tags for filtering in the backend.
         *
         * @param  array $pieces
         * @param  array $taxonomies
         * @param  array $args
         * @return array
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function modify_terms_query($pieces, $taxonomies, $args)
        {
            $taxonomy = TagGroups_Utilities::get_first_element($taxonomies);
            if (empty($taxonomy) || is_array($taxonomy)) {
                $taxonomy = 'post_tag';
            }
            $show_filter_tags = TagGroups_Options::get_option('tag_group_show_filter_tags', 0);
            if (!$show_filter_tags) {
                return $pieces;
            }
            $tag_group_tags_filter = TagGroups_Options::get_option('tag_group_tags_filter', array());
            if (isset($tag_group_tags_filter[$taxonomy])) {
                $group_id = $tag_group_tags_filter[$taxonomy];
                if ($group_id > 0) {
        // check if group exists (could be deleted since last time the filter was set)
                    $tg_group = new TagGroups_Group($group_id);
                    if (!$tg_group->exists()) {
                            $group_id = -1;
                    }
                }
            } else {
                $group_id = -1;
            }


            if ($group_id > -1) {
                $tg_group = new TagGroups_Group($group_id);
                $mq_sql = $tg_group->terms_clauses();
                if (!empty($pieces['join'])) {
                    $pieces['join'] .= $mq_sql['join'];
                } else {
                    $pieces['join'] = $mq_sql['join'];
                }


                if (!empty($pieces['where'])) {
                    $pieces['where'] .= $mq_sql['where'];
                } else {
                    $pieces['where'] = $mq_sql['where'];
                }
            }

            return $pieces;
        }

        /**
         * Adds Settings link to plugin list
         *
         * @param  array $links
         * @return array
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_plugin_settings_link($links)
        {
            $settings_link = '<a href="' . admin_url('admin.php?page=tag-groups-settings') . '">' . __('Settings', 'tag-groups') . '</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        /**
         * Add a warning if the WPML/Polylang language switch is set to "all"
         *
         * @param  void
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_language_notice()
        {
            $screen = get_current_screen();
            if (!$screen || 'edit-tags' !== $screen->base && 'term' !== $screen->base) {
                return;
            }
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            if (!in_array($screen->taxonomy, $enabled_taxonomies)) {
                return;
            }

            if ('all' == TagGroups_WPML::get_current_language()) {
                $view = new TagGroups_View('partials/language_notice');
                $view->render();
            }
        }

        /**
         * Add inline styling to the tags page
         *
         * @param  void
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function add_tag_page_styling()
        {
            $view = new TagGroups_View('partials/tag_page_inline_style');
            $view->render();
        }

        /**
         * Recommend to run the migration
         *
         * @since 1.24.0
         *
         * @param  void
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function recommend_to_run_migration()
        {
            /* translators: %s is the href attribute with URL */
            TagGroups_Admin_Notice::add('info', sprintf(__('Please <a %s>click here to run the migration routines</a> to make sure we have migrated all tags.', 'tag-groups'), 'href="' . admin_url('admin.php?page=tag-groups-settings-general&process-tasks=migratetermmeta&task-set-name=Migration') . '"'));
        }
    }
    // class
    
}
