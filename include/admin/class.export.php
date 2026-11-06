<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2020 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

if (!class_exists('TagGroups_Export')) {
    /**
     *
     * @since 1.38.0
     */
    // phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps -- Legacy class structure
    class TagGroups_Export
    {
        /**
         * Options to be exported
         *
         * @var array
         */
        private $options ;
        /**
         * Terms to be exported
         *
         * @var array
         */
        private $terms ;
        /**
         * pseudo-random hash to cloak the exported files
         *
         * @var string
         */
        private $hash ;
        /**
         * whether an error occured
         *
         * @var integer
         */
        private $error ;
        // phpcs:ignore Squiz.Scope.MethodScope.Missing -- Legacy code
        function __construct()
        {
            $this->error = false;
        }

        /**
         * Create an array of all options that should be exported
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function process_options_for_export()
        {
            $this->options = array(
                'name'    => 'tag_groups_options',
                'version' => TAG_GROUPS_VERSION,
                'date'    => current_time('mysql'),
            );
            $available_options = TagGroups_Options::get_available_options();
            foreach ($available_options as $key => $value) {
                if ($available_options[$key]['export']) {
                    if (TagGroups_Utilities::is_premium_plan()) {
                        $this->options[$key] = TagGroups_Options::get_option($key);
                        continue;
                    }

                    if (TagGroups_Options::TAG_GROUPS_PLUGIN == $available_options[$key]['origin']) {
                        $this->options[$key] = TagGroups_Options::get_option($key);
                    }
                }
            }
        }

        /**
         * Create an array of all terms that should be exported
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function process_terms_for_export()
        {
            // generate array of all terms
            $wp_terms = get_terms(array(
                'hide_empty' => false,
                'taxonomy'   => TagGroups_Taxonomy::get_enabled_taxonomies(),
            ));
            $this->terms = array(
                'name'    => 'tag_groups_terms',
                'version' => TAG_GROUPS_VERSION,
                'date'    => current_time('mysql'),
            );
            $this->terms['terms'] = array();
            foreach ($wp_terms as $term) {
                $tg_term = new TagGroups_Term($term->term_id);
                // We export only fields that later can be updated with wp_update_term()
                $this->terms['terms'][] = array(
                    'term_id'     => $term->term_id,
                    'name'        => $term->name,
                    'slug'        => $term->slug,
                    'term_group'  => $tg_term->get_groups(),
                    'taxonomy'    => $term->taxonomy,
                    'description' => $term->description,
                    'parent'      => $term->parent,
                );
            }
        }

        /**
         * Writes options and terms into files
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function write_files()
        {
            try {
                // misusing the password generator to get a hash
                $this->hash = wp_generate_password(10, false);
                $export_data = array(
                    'settings' => wp_json_encode($this->options),
                    'terms'    => wp_json_encode($this->terms),
                );

                if (!set_transient('tag_groups_export_' . $this->hash, $export_data, 15 * MINUTE_IN_SECONDS)) {
                    $this->error = true;
                }
            } catch (Exception $e) {
                $this->error = true;
            }
        }

        /**
         * Displays the links to download the exported files, or an error message
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.Scope.MethodScope.Missing -- Legacy method naming
        function show_download_links()
        {

            if (!$this->error) {
                $settings_url = self::get_download_url($this->hash, 'settings');
                $terms_url = self::get_download_url($this->hash, 'terms');
                TagGroups_Admin_Notice::add('success', __('Your settings/groups and your terms have been exported. Please download the resulting files with right-click or ctrl-click:', 'tag-groups') . '  <p>
        <a href="' . esc_url($settings_url) . '" target="_blank" rel="noopener noreferrer">tag_groups_settings-' . esc_html($this->hash) . '.json<span class="screen-reader-text"> ' . esc_html__('(opens in a new tab)', 'tag-groups') . '</span></a>
        </p>' . '  <p>
        <a href="' . esc_url($terms_url) . '" target="_blank" rel="noopener noreferrer">tag_groups_terms-' . esc_html($this->hash) . '.json<span class="screen-reader-text"> ' . esc_html__('(opens in a new tab)', 'tag-groups') . '</span></a>
        </p>');
            } else {
                TagGroups_Error::log('[Tag Groups] Error writing files');
                TagGroups_Admin_Notice::add('error', __('Writing of the exported settings failed.', 'tag-groups'));
            }
        }

        /**
         * Builds an authenticated URL for an export download.
         *
         * @param string $hash
         * @param string $type
         * @return string
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        private static function get_download_url($hash, $type)
        {
            return add_query_arg(
                array(
                    'action'                 => 'tag_groups_download_export',
                    'tag_groups_export'      => $hash,
                    'tag_groups_export_type' => $type,
                    '_wpnonce'               => wp_create_nonce('tag-groups-download-export-' . $hash . '-' . $type),
                ),
                admin_url('admin-post.php')
            );
        }

        /**
         * Serves an exported JSON file to authorized administrators.
         *
         * @return void
         */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function download_file()
        {
            if (!current_user_can('manage_options')) {
                wp_die(esc_html__('You do not have permission to download this export file.', 'tag-groups'));
            }

            $hash = isset($_GET['tag_groups_export']) ? sanitize_text_field(wp_unslash($_GET['tag_groups_export'])) : '';
            $type = isset($_GET['tag_groups_export_type']) ? sanitize_key(wp_unslash($_GET['tag_groups_export_type'])) : '';
            $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

            if (empty($hash) || !in_array($type, array( 'settings', 'terms' ), true) || !wp_verify_nonce($nonce, 'tag-groups-download-export-' . $hash . '-' . $type)) {
                wp_die(esc_html__('The export download link is invalid or has expired. Please run the export again.', 'tag-groups'));
            }

            $export_data = get_transient('tag_groups_export_' . $hash);

            if (empty($export_data[$type])) {
                wp_die(esc_html__('The requested export file has expired. Please run the export again.', 'tag-groups'));
            }

            $filename = sanitize_file_name('tag_groups_' . $type . '-' . $hash . '.json');

            nocache_headers();
            header('Content-Type: application/json; charset=' . get_option('blog_charset'));
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('X-Content-Type-Options: nosniff');

            echo $export_data[$type]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON file download.
            exit;
        }
    }
}
