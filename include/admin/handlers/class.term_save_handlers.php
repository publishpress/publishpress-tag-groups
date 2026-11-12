<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps, WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Term_Save_Handlers')) {
/**
   *
   */
    class TagGroups_Term_Save_Handlers
    {
      /**
       * Saves the term group without the standard tag information
       *
       * @global int $tg_update_edit_term_group_called
       * @param  int    $term_id
       * @return void
       */
        public static function save_term_group_without_tag_info($term_id)
        {

          // next lines to prevent infinite loops when the hook edit_term is called again from the function wp_update_term
            global $tg_update_edit_term_group_called;
            if ($tg_update_edit_term_group_called) {
                    return;
            }

            self::save_term_group($term_id);
        }

      /**
       * Saves the term group and the standard tag information
       *
       * Called when editing an existing tag
       *
       * @global int $tg_update_edit_term_group_called
       * @param  int    $term_id
       * @return void
       */
        public static function save_term_group_with_tag_info($term_id)
        {

          // next lines to prevent infinite loops when the hook edit_term is called again from the function wp_update_term
            global $tg_update_edit_term_group_called;
            if ($tg_update_edit_term_group_called) {
                    return;
            }

            self::save_term_group($term_id);
  /**
           *   If necessary we also save default WP term properties.
           *   Make sure we have a taxonomy
           */

            if (! isset($_POST['tag-groups-taxonomy'])) {
                return;
            }

            $taxonomy = sanitize_title($_POST['tag-groups-taxonomy']);
            $args = array();
  /**
           * Save the tag name
           */

            if (isset($_POST['name']) && ( '' != $_POST['name'] )) {
    // allow zeros

                $args['name'] = stripslashes(sanitize_text_field($_POST['name']));
            }

          /**
           * For consistency with default WP behavior, we check if the name already exists (case-insensitiv match)
           */
            $terms_by_name = get_terms(array( 'name' => $args['name'], 'taxonomy' => $taxonomy, 'hide_empty' => false ));
            foreach ($terms_by_name as $term_by_name) {
                if ($term_id != $term_by_name->term_id) {
                    TagGroups_Error::verbose_log(sprintf('[Tag Groups] Duplicate names (case-insensitiv match) are not supported by WordPress: "%s"', $args['name']));
                }
            }

        /**
         * Save the tag slug
         */

            if (isset($_POST['slug'])) {
    // allow empty values

                $args['slug'] = sanitize_title($_POST['slug']);
            }

        /**
         * Save the tag description
         */

            if (isset($_POST['description'])) {
    /**
               * allow empty values
               */

                if (defined('TAG_GROUPS_ALLOW_ALL_HTML_IN_TERM_DESCRIPTION') && TAG_GROUPS_ALLOW_ALL_HTML_IN_TERM_DESCRIPTION) {
      /*
                 * Use this constant with caution! You have to trust all users who are are allowed to edit terms (tags)
                   */

                    $args['description'] = $_POST['description'];
                } else {
                /*
                       * Keep HTML that is allowed for posts - just in case a 3rd party plugin needs it
                         */
                        $args['description'] = wp_kses_post($_POST['description']);
                }
            }

        /**
         * Save the parent
         */

            if (isset($_POST['parent']) && ( '' != $_POST['parent'] )) {
                $args['parent'] = (int) $_POST['parent'];
            }

        /**
         * Some plugins save also additonal fields. We therefore allow to whitelist further arguments.
         * example for array usage:
         * define( 'TAG_GROUPS_ADDITIONAL_TERM_ARGS', array(
         *  'tag-image' => 'sanitize_text_field'
         * ));
         * (min. PHP 5.6)
         */

            if (defined('TAG_GROUPS_ADDITIONAL_TERM_ARGS')) {
                if (is_bool(TAG_GROUPS_ADDITIONAL_TERM_ARGS)) {
                    $exclude = array(
                          'action',
                          '_wp_original_http_referer',
                          '_wpnonce',
                          '_wp_http_referer',
                          'term-group',
                          'tag-groups-nonce',
                          'tag-groups-taxonomy',
                    );
                    foreach ($_POST as $posted_key => $posted_value) {
                        if (! in_array($posted_key, $exclude) && ! isset($args[$posted_key])) {
                                $args[$posted_key] = $posted_value;
                        }
                    }
                } elseif (is_array(TAG_GROUPS_ADDITIONAL_TERM_ARGS)) {
                    $permitted_sanitation_functions = array(
                          'intval',
                          'sanitize_email',
                          'sanitize_file_name',
                          'sanitize_html_class',
                          'sanitize_key',
                          'sanitize_meta',
                          'sanitize_mime_type',
                          'sanitize_option',
                          'sanitize_sql_orderby',
                          'sanitize_text_field',
                          'sanitize_textarea_field',
                          'sanitize_title',
                          'sanitize_title_for_query',
                          'sanitize_title_with_dashes',
                          'sanitize_user',
                    );
                    foreach (TAG_GROUPS_ADDITIONAL_TERM_ARGS as $additonal_arg => $sanitization) {
                        if (function_exists($sanitization) && in_array($sanitization, $permitted_sanitation_functions)) {
                                $args[$additonal_arg] = call_user_func($sanitization, $_POST[$additonal_arg]);
                        }
                    }
                }
            }

            wp_update_term($term_id, $taxonomy, $args);
        }

      /**
       * Get the $_POSTed value after saving a tag/term and save it in the database
       *
       * Called when creating a new tag or editing an existing tag
       *
       * @param  int    $term_id
       * @return void
       */
        public static function save_term_group($term_id)
        {

            global $tg_update_edit_term_group_called;
            $screen = get_current_screen();
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $tg_update_edit_term_group_called = true;
            if (is_object($screen) && ( ! in_array($screen->taxonomy, $enabled_taxonomies) ) && ( ! isset($_POST['new-tag-created']) )) {
                    return;
            }

            if (empty($_POST['tag-groups-nonce']) || ! wp_verify_nonce($_POST['tag-groups-nonce'], 'tag-groups-nonce')) {
                return;
            }

            $term = new TagGroups_Term((int) $term_id);
            if (! empty($_POST['term-group'])) {
                if (is_array($_POST['term-group'])) {
                    $term_group = array_map('intval', $_POST['term-group']);
                    if ($term->get_groups() != $term_group) {
                          $term->set_group($term_group)->save();
                    }
                } else {
                    $term_group = (int) $_POST['term-group'];
                    if (! in_array($term_group, $term->get_groups())) {
                        $term->set_group($term_group)->save();
                    }
                }
            } else {
                if ($term->get_groups() != array( 0 )) {
                    $term->remove_all_groups()->save();
                }
            }
        }

      /**
       * WPML: Check if we need to copy group info to the translation
       *
       * Copy the groups of an original term to its translation if a translation is saved
       *
       * @param  type   $term_id
       * @return type
       */
        public static function maybe_copy_term_group_to_translation($term_id)
        {

          /**
           * Check if WPML is available
           */
            $default_language_code = apply_filters('wpml_default_language', null);
            if (! isset($default_language_code)) {
                    return;
            }

          /**
           * Check if the new tag has no group set or groups set to unassigned
           */
            $term = new TagGroups_Term($term_id);
            $translated_term_groups = $term->get_groups();
            if (! empty($translated_term_groups) && array( 0 ) != $translated_term_groups) {
                  return;
            }

        /**
         *   edit-tags.php form
         */

            if (
                isset($_POST['icl_tax_post_tag_language'])
                && $_POST['icl_tax_post_tag_language'] != $default_language_code
            ) {
                if (! empty($_POST['icl_translation_of'])) {
                // translated from the default language

                        $original_term_id = $_POST['icl_translation_of'];
                } elseif (! empty($_POST['icl_trid'])) {
                // translated from another translated language

                        $translations = apply_filters('wpml_get_element_translations', null, $_POST['icl_trid']);
                    if (isset($translations[$default_language_code]->element_id)) {
                        $original_term_id = $translations[$default_language_code]->element_id;
                    }
                }
            } elseif (
                isset($_POST['term_language_code'])
                && $_POST['term_language_code'] != $default_language_code
                && ! empty($_POST['trid'])
            ) {
                /**
                 *   taxonomy-translation.php form
                 */
                $translations = apply_filters('wpml_get_element_translations', null, $_POST['trid']);
                if (isset($translations[$default_language_code]->element_id)) {
                    $original_term_id = $translations[$default_language_code]->element_id;
                }
            }

            if (isset($original_term_id)) {
                $tg_original_term = new TagGroups_Term($original_term_id);
                $original_term_groups = $tg_original_term->get_groups();
                if (! empty($original_term_groups)) {
                    $term->set_group($original_term_groups)->save();
                }
            }
        }
    }


}
