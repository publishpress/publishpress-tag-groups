<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Setup_Wizard')) {
    /**
     *
     */
    class TagGroups_Setup_Wizard extends TagGroups_Settings
    {
        /**
         * renders a menu-less settings page: onboarding
         *
         * @param void
         * @return void
         */
        public static function settings_page_onboarding()
        {
            // Make very sure that only administrators can access this page
            if (!current_user_can('manage_options')) {
                return;
            }

            self::add_header();
            $settings_taxonomy_link = admin_url('admin.php?page=tag-groups-settings');
            $settings_home_link = admin_url('admin.php?page=tag-groups-settings');
            $settings_premium_link = admin_url('admin.php?page=tag-groups-settings-premium');
            $settings_setup_wizard_link = admin_url('admin.php?page=tag-groups-settings-setup-wizard');

            if (defined('TAG_GROUPS_PLUGIN_IS_FREE') && TAG_GROUPS_PLUGIN_IS_FREE) {
                $title = 'PublishPress Tag Groups';
                $documentation_link = 'https://publishpress.com/tag-groups/';
                $logo = '';
            } else {
                $title = 'PublishPress Tag Groups Pro';
                $documentation_link = 'https://publishpress.com/tag-groups/';
                $logo = '';
            }

            $view = new TagGroups_View('admin/onboarding');
            $view->set(array(
                'logo'                       => $logo,
                'title'                      => $title,
                'settings_taxonomy_link'     => $settings_taxonomy_link,
                'settings_home_link'         => $settings_home_link,
                'documentation_link'         => $documentation_link,
                'settings_premium_link'      => $settings_premium_link,
                'settings_setup_wizard_link' => $settings_setup_wizard_link,
            ));
            $view->render();
            self::add_footer();
        }

        /**
         * renders a menu-less settings page: onboarding
         *
         * @param void
         * @return void
         */
        public static function settings_page_setup_wizard()
        {
            // Make very sure that only administrators can access this page
            if (!current_user_can('manage_options')) {
                return;
            }
            global $tag_group_groups ;
            self::add_header();
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only wizard step navigation
            $step = ( isset($_GET['step']) && $_GET['step'] > 0 ? (int) $_GET['step'] : 1 );
            $setup_wizard_next_link = add_query_arg('step', $step + 1, admin_url('admin.php?page=tag-groups-settings-setup-wizard'));

            if (defined('TAG_GROUPS_PLUGIN_IS_FREE') && TAG_GROUPS_PLUGIN_IS_FREE) {
                $title = 'PublishPress Tag Groups';
                $is_premium = false;
                $documentation_link = 'https://publishpress.com/tag-groups/';
            } else {
                $title = 'PublishPress Tag Groups Pro';
                $is_premium = true;
                $documentation_link = 'https://publishpress.com/tag-groups/';
            }


            if ($is_premium && TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_View')) {
            } else {
                $steps = array(
                    1 => array(
                    'id'    => 'start',
                    'title' => __('Start', 'tag-groups'),
                ),
                    2 => array(
                    'id'    => 'taxonomies',
                    'title' => __('Taxonomies', 'tag-groups'),
                ),
                    3 => array(
                    'id'    => 'sample_content',
                    'title' => __('Sample Content', 'tag-groups'),
                ),
                    4 => array(
                    'id'    => 'finished',
                    'title' => null,
                ),
                );
            }

            $view = new TagGroups_View('admin/setup_wizard_header');
            $view->set(array(
                'title' => $title,
                'step'  => $step,
                'steps' => $steps,
            ));
            $view->render();
            switch ($steps[$step]['id']) {
                case 'sample_content':
                    $view = new TagGroups_View('admin/setup_wizard_sample_content');
                    $group_names = array( 
                        __('Sample Group A', 'tag-groups'), 
                        __('Sample Group B', 'tag-groups'), 
                        __('Sample Group C', 'tag-groups') 
                    );
                    /**
                     * Make sure they don't yet exist
                     */
                    $group_names = array_map(function ($original_name) {
                        $tg_group = new TagGroups_Group();
                        $name = $original_name;
                        $i = 0;
                        while ($tg_group->find_by_label($name) !== false) {
                            $i++;
                            $name = $original_name . ' - ' . $i;
                        }
                        return $name;
                    }, $group_names);
                    $tag_names = array( 
                        __('First Sample Tag', 'tag-groups'), 
                        __('Second Sample Tag', 'tag-groups'), 
                        __('Third Sample Tag', 'tag-groups') 
                    );
                    $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
                    $taxonomy = array_shift($enabled_taxonomies);
                    /**
                     * Make sure they don't yet exist
                     */
                    $tag_names = array_map(function ($original_name) use ($taxonomy) {
                        $name = $original_name;
                        $i = 0;
                        while (get_term_by('name', $name, $taxonomy) !== false) {
                            $i++;
                            $name = $original_name . ' - ' . $i;
                        }
                        return $name;
                    }, $tag_names);

                    if (TagGroups_Gutenberg::is_gutenberg_active()) {
                        $create_sample_page_label = __('Create a draft sample page with Gutenberg blocks.', 'tag-groups');
                    } else {
                        $create_sample_page_label = __('Create a draft sample page with shortcodes.', 'tag-groups');
                    }

                    $view->set(array(
                        'title'                    => $title,
                        'group_names'              => $group_names,
                        'tag_names'                => $tag_names,
                        'create_sample_page_label' => $create_sample_page_label,
                        'setup_wizard_next_link'   => $setup_wizard_next_link,
                    ));
                    break;
                case 'post_tags':
                    break;
                case 'meta_box':
                    break;
                case 'taxonomies':
                    $view = new TagGroups_View('admin/setup_wizard_taxonomies');
                    $view->set(array(
                        'title'                  => $title,
                        'public_taxonomies'      => TagGroups_Taxonomy::get_public_taxonomies(),
                        'enabled_taxonomies'     => TagGroups_Taxonomy::get_enabled_taxonomies(),
                        'setup_wizard_next_link' => $setup_wizard_next_link,
                    ));
                    break;
                case 'finished':
                    $view = new TagGroups_View('admin/setup_wizard_finished');
                    $documentation_link = 'https://publishpress.com/tag-groups/';
                    $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
                    $taxonomy = array_shift($enabled_taxonomies);
                    $view->set(array(
                        'groups_admin_link'        => TagGroups_Taxonomy::get_tag_group_admin_url($taxonomy),
                        'documentation_link'       => $documentation_link,
                        'settings_home_link'       => admin_url('admin.php?page=tag-groups-settings'),
                        'tag_group_sample_page_id' => TagGroups_Options::get_option('tag_group_sample_page_id', 0),
                    ));
                    break;
                case 'start':
                default:
                    $view = new TagGroups_View('admin/setup_wizard_start');
                    $view->set(array(
                        'title'                  => $title,
                        'setup_wizard_next_link' => $setup_wizard_next_link,
                        'is_premium'             => $is_premium,
                    ));
                    break;
            }
            $view->render();
            $view = new TagGroups_View('admin/setup_wizard_footer');
            $view->render();
            self::add_footer();
        }

        /**
         * Processes form submissions from the settings page
         *
         * @param void
         * @return void
         */
        public static function settings_page_actions_wizard()
        {
            global  $tag_group_groups ;
            if (empty($_REQUEST['tg_action_wizard'])) {
                return;
            }
            // Make very sure that only administrators can do actions
            if (!current_user_can('manage_options')) {
                wp_die("Capability check failed");
            }
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Nonce verification
            if (!isset($_POST['tag-groups-setup-wizard-nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tag-groups-setup-wizard-nonce'])), 'tag-groups-setup-wizard-nonce')) {
                die("Security check failed");
            }
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $taxonomy = array_shift($enabled_taxonomies);
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized in switch statement
            switch (sanitize_key(wp_unslash($_REQUEST['tg_action_wizard']))) {
                case 'taxonomy':
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
                            return;
                        }
                    }
                    TagGroups_Taxonomy::update_enabled($taxonomies);
                    break;
                case 'sample-content':
                    $created_groups = array();
                    /**
                     * Create groups
                     */
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized below
                    if (isset($_POST['tag-groups-create-sample-groups']) && wp_unslash($_POST['tag-groups-create-sample-groups'])) {
                        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- Sanitized below
                        foreach (wp_unslash($_POST['tag_groups_group_names']) as $group_name) {
                            $tg_group = new TagGroups_Group();
                            $tg_group->create(sanitize_text_field($group_name));
                            $created_groups[] = $tg_group->get_group_id();
                        }
                    }
                    /**
                     * Create tags
                     */
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized below
                    if (isset($_POST['tag-groups-create-sample-tags']) && wp_unslash($_POST['tag-groups-create-sample-tags'])) {
                        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- Sanitized below
                        foreach (wp_unslash($_POST['tag_groups_tag_names']) as $tag_name) {
                            $tag_name = sanitize_text_field($tag_name);

                            if (!term_exists($tag_name, $taxonomy)) {
                                $term_array = wp_insert_term($tag_name, $taxonomy);
                                $tg_term = new TagGroups_Term($term_array['term_id']);

                                if (empty($created_groups)) {
                                    $group_ids = $tag_group_groups->get_group_ids();
                                    unset($group_ids[0]);
                                } else {
                                    $group_ids = $created_groups;
                                }

                                // add one group
                                $amount = 1;

                                if (1 == $amount) {
                                    $random_group_ids = $group_ids[array_rand($group_ids)];
                                } else {
                                    $random_group_ids = array_intersect_key($group_ids, array_rand($group_ids, $amount));
                                }

                                $tg_term->add_group($random_group_ids)->save();
                            }
                        }
                    }
                    $tpf_include = $tag_group_groups->get_group_ids();
                    unset($tpf_include[0]);

                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Checkbox validation
                    if (isset($_POST['tag-groups-create-sample-page']) && wp_unslash($_POST['tag-groups-create-sample-page'])) {
                        if (TagGroups_Gutenberg::is_gutenberg_active()) {
                            $view = new TagGroups_View('admin/sample_page_gutenberg');
                            $sample_page_title = 'PublishPress Tag Groups (Free) Sample Page - Gutenberg Editor';
                        } else {
                            $view = new TagGroups_View('admin/sample_page');
                            $sample_page_title = 'PublishPress Tag Groups (Free) Sample Page - Classic Editor';
                        }

                        $tag_groups_settings_link = admin_url('admin.php?page=tag-groups-settings');
                        $current_user = wp_get_current_user();
                        $view->set(array(
                            'enabled_taxonomies'        => $enabled_taxonomies,
                            'author_display_name'       => $current_user->display_name,
                            'tag_groups_settings_link'  => $tag_groups_settings_link,
                            'tpf_include_csv'           => implode(',', $tpf_include),
                        ));
                        $content = $view->return_html();
                        $post_data = array(
                            'post_title'   => wp_strip_all_tags($sample_page_title),
                            'post_content' => $content,
                            'post_status'  => 'draft',
                            'post_type'    => 'page',
                            'post_author'  => get_current_user_id(),
                        );
                        $post_id = wp_insert_post($post_data);
                        TagGroups_Options::update_option('tag_group_sample_page_id', $post_id);
                    } else {
                        delete_option('tag_group_sample_page_id');
                    }

                    break;
            }
        }
    }
}
