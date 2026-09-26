<?php

/**
 * @package     Tag Groups
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (! class_exists('TagGroups_Options')) {

  /**
   *  Provides all available options and methods to sanitize their values
   */
    class TagGroups_Options
    {
        public const TAG_GROUPS_PLUGIN = 1;
        public const TAG_GROUPS_PREMIUM_PLUGIN = 2;
        public const INTEGER = 'integer'; // also used for booleans
        public const STRING = 'string'; // strips whitespace
        public const STRING_HTML = 'string_html';
        public const ARRAY_OF_STRINGS = 'array_of_strings';
        public const ARRAY_OF_STRINGS_HTML = 'array_of_strings_html';
        public const ARRAY_OF_INTEGERS = 'array_of_integers';
        public const MIXED = 'MIXED'; // no sanitization available, use only for internally created content

      /**
       * allowed tags and attributes for sanitizing HTML
       * 
       * requires PHP 5.6.0
       */
        public const ALLOWED_HTML = array(
        'br' => array(),
        'b' => array(),
        'hr' => array(),
        'i' => array(
        'class' => array(),
        ),
        'strong' => array(),
        'small' => array(),
        'a' => array(
        'href' => array(),
        'class' => array(),
        'rel' => array(),
        'target' => array(),
        'id' => array(),
        'style' => array(),
        ),
        'header' => array(
        'class' => array(),
        'style' => array(),
        ),
        'div' => array(
        'class' => array(),
        'style' => array(),
        'id' => array(),
        ),
        'p' => array(
        'class' => array(),
        'style' => array(),
        ),
        'span' => array(
        'class' => array(),
        'style' => array(),
        ),
        'h1' => array(
        'class' => array(),
        'style' => array(),
        ),
        'h2' => array(
        'class' => array(),
        'style' => array(),
        ),
        'h3' => array(
        'class' => array(),
        'style' => array(),
        ),
        'h4' => array(
        'class' => array(),
        'style' => array(),
        ),
        'h5' => array(
        'class' => array(),
        'style' => array(),
        ),
        'h6' => array(
        'class' => array(),
        'style' => array(),
        ),
        'img' => array(
        'alt' => array(),
        'class' => array(),
        'src' => array(),
        'height' => array(),
        'width' => array(),
        'style' => array(),
        'id' => array()
        ),
        );

      /**
       * The list of all currently used options. Relevant for deleting, exporting, importing and sanitizing
       *
       * @param void
       * @return array
       */
        public static function get_available_options()
        {

            $available_options = array();

            $available_options['tag_group_taxonomy'] = array(
            'origin'  => self::TAG_GROUPS_PLUGIN,
            'export'  => true,
            'type'    => self::ARRAY_OF_STRINGS
            );
            $available_options['term_groups'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_INTEGERS
            );
            $available_options['term_group_positions'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_INTEGERS
            );
            $available_options['term_group_labels'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_STRINGS
            );
            $available_options['tag_group_theme'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::STRING
            );
            $available_options['tag_group_mouseover'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_collapsible'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_enqueue_jquery'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_shortcode_widget'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_show_filter'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_show_filter_tags'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_enable_groups'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_enable_group_public_api_access'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_enable_terms_public_api_access'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_html_description'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_admin_notice'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false,
            'type'    => self::ARRAY_OF_STRINGS_HTML
            );
            $available_options['tag_group_shortcode_enqueue_always'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_tags_filter'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_INTEGERS
            );
            $available_options['tag_group_base_version'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::STRING
            );
            $available_options['tag_group_base_first_activation_time'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_group_languages'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_STRINGS
            );
            $available_options['tag_group_sample_page_id'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_used_transient_names'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false,
            'type'    => self::ARRAY_OF_STRINGS
            );
            $available_options['tag_group_verbose_debug'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false, // false, because import could unintentionally turn it off
            'type'    => self::INTEGER
            );
            $available_options['tag_group_multilingual_sync_groups'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_run_term_migration_offset'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => false,
            'type'   => self::INTEGER
            );
      
          /**
           * Deprecated after 0.36 - don't export
           */
            $available_options['tag_group_labels'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false,
            'type'    => self::ARRAY_OF_STRINGS
            );
            $available_options['tag_group_ids'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false,
            'type'    => self::ARRAY_OF_INTEGERS
            );
            $available_options['max_tag_group_id'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_server_side_render'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
      
          /**
           * deprecated
           */
            $available_options['chatty_mango_cache'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false,
            'type'    => self::MIXED
            );
            $available_options['tag_group_onboarding'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => false,
            'type'    => self::INTEGER
            );

          /**
           * Options of the premium plugin; listed here so they survive uninstallation of premium plugin
           */
            $available_options['tag_group_meta_box_taxonomy'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_STRINGS
            );
            $available_options['tag_group_object_cache'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_meta_box_add_term'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_meta_box_change_group'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_role_edit_groups'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::STRING
            );
            $available_options['tag_group_open_all_with_terms'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_role_mb_override'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::STRING
            );
            $available_options['tag_group_hide_tagsdiv'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_display_groups_under_posts'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_INTEGERS
            );
            $available_options['tag_group_display_groups_under_posts_single'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_display_groups_under_posts_feed'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_display_groups_under_posts_home'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_display_groups_under_posts_archive'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_display_groups_under_posts_title'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::STRING_HTML
            );
            $available_options['tag_group_remove_the_post_terms'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_display_groups_under_posts_priority'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_display_groups_under_posts_separator'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::STRING_HTML
            );
            $available_options['tag_group_premium_version'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::STRING
            );
            $available_options['tag_group_premium_first_activation_time'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_dpf_template'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::STRING_HTML
            );
            $available_options['tag_group_add_product_tags_to_attributes'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_display_groups_under_products_separator'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::STRING_HTML
            );
            $available_options['tag_group_remove_the_product_tags'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_role_edit_tags'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::STRING
            );
            $available_options['tag_group_meta_box_include'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_INTEGERS
            );
            $available_options['tag_group_meta_box_open_all'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_group_new_tag_default_groups'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => false,
            'type'    => self::ARRAY_OF_INTEGERS
            );
            $available_options['tag_group_auto_clear_cache'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );
            $available_options['tag_groups_per_page'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'    => self::ARRAY_OF_INTEGERS
            );
            $available_options['tag_group_excerpt_length'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'   => self::INTEGER
            );
            $available_options['tag_group_excerpt_ellipsis'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'   => self::STRING_HTML
            );
            $available_options['term_group_parents'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'   => self::ARRAY_OF_INTEGERS
            );
            $available_options['tag_group_post_terms_with_parents'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'   => self::INTEGER
            );
            $available_options['tag_group_rebuild_post_counts_offset'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => false,
            'type'   => self::INTEGER
            );
            $available_options['tag_group_run_post_migration_offset'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => false,
            'type'   => self::INTEGER
            );
            $available_options['tag_group_run_fixing_post_meta_offset'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => false,
            'type'   => self::INTEGER
            );
            $available_options['tag_group_meta_box_add_all'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'   => self::INTEGER
            );
            $available_options['tag_group_meta_box_remove_all'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'   => self::INTEGER
            );
            $available_options['tag_group_meta_box_staged_loading'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => true,
            'type'   => self::INTEGER
            );

          /**
           * deprecated
           */
            $available_options['chatty_mango_packages'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => false,
            'type'    => self::ARRAY_OF_STRINGS
            );
            $available_options['tag_group_latest_version'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => false,
            'type'    => self::ARRAY_OF_STRINGS
            );
            $available_options['tag_group_latest_version_url'] = array(
            'origin' => self::TAG_GROUPS_PREMIUM_PLUGIN,
            'export' => false,
            'type'    => self::STRING
            );

          // should be last:
            $available_options['tag_group_reset_when_uninstall'] = array(
            'origin' => self::TAG_GROUPS_PLUGIN,
            'export' => true,
            'type'    => self::INTEGER
            );

            return $available_options;
        }


      /**
       * wrapper for WP's native get_option that checks if option is on whitelist and applies appropriate sanitization
       *
       * @param string $option_name
       * @param mixed $default_value
       * @return mixed
       */
        public static function get_option($option_name, $default_value = null)
        {

            $option_name = (string) $option_name;

            $available_options = self::get_available_options();

          /**
           * Check if whitelisted
           */
            if (! isset($available_options[ $option_name ]) && substr($option_name, 0, strlen('term_group_labels_')) != 'term_group_labels_') {
                TagGroups_Error::log('[Tag Groups] Unknown option: %s', $option_name);

                return false;
            }

          /**
           * retrieve the saved WP option value
           */
            $value = get_option($option_name, $default_value);

            if ($value === false) {
                return false;
            }

            if (substr($option_name, 0, strlen('term_group_labels_')) == 'term_group_labels_') {
                if (is_array($value)) {
                    $sanitized_value = $value;
              
                    array_walk_recursive($sanitized_value, function (&$value) {
                        $value = sanitize_text_field($value);
                    });
                } else {
                    $sanitized_value = sanitize_text_field($value);
                }
            } else {
                $sanitized_value = false;

              /**
               * sanitize
               */
                switch ($available_options[ $option_name ]['type']) {
                    case self::INTEGER:
                        if ($value === true) {
                            $sanitized_value = 1;
                        } else {
                            $sanitized_value = (int) $value;
                        }
                        break;

                    case self::STRING:
                        $sanitized_value = sanitize_text_field($value);
                        break;

                    case self::STRING_HTML:
                        $sanitized_value = self::wp_kses($value);
                        break;
          
                    case self::ARRAY_OF_INTEGERS:
                        if (! self::array_or_log($value, $option_name)) {
                            return false;
                        }

                        $sanitized_value = $value;

                        array_walk_recursive($sanitized_value, function (&$value) {
                            $value = (int) $value;
                        });

                        break;

                    case self::ARRAY_OF_STRINGS:
                        if (! self::array_or_log($value, $option_name)) {
                            return false;
                        }

                        $sanitized_value = $value;
            
                        array_walk_recursive($sanitized_value, function (&$value) {
                            $value = sanitize_text_field($value);
                        });


                        break;

                    case self::ARRAY_OF_STRINGS_HTML:
                        if (! self::array_or_log($value, $option_name)) {
                            return false;
                        }

                        $sanitized_value = $value;
            
                        array_walk_recursive($sanitized_value, function (&$value) {
                            $value = TagGroups_Options::wp_kses($value);
                        });
                        break;

                    case self::MIXED:
                      // We don't handle this case => not to be used to store user input
                        TagGroups_Error::log('[Tag Groups] MIXED type for %s', $option_name);

                        return $value;

                    break;
                }
            }

            if ($sanitized_value !== $value && ! ( $available_options[ $option_name ]['type'] == self::INTEGER && $value == $sanitized_value )) {
                TagGroups_Error::verbose_log('[Tag Groups] Sanitizing changed the value for %s: %s->%s', $option_name, $value, $sanitized_value);
            }
      
            return $sanitized_value;
        }


      /**
       * wrapper for WP's native update_option that checks if option is on whitelist and applies appropriate sanitation
       *
       * @param string $option_name
       * @param mixed $value
       * @return mixed
       */
        public static function update_option($option_name, $value, $autoload = true)
        {

            $option_name = (string) $option_name;
      
            if (substr($option_name, 0, strlen('term_group_labels_')) == 'term_group_labels_') {
                if (is_array($value)) {
                    $sanitized_value = $value;
              
                    array_walk_recursive($sanitized_value, function (&$value) {
                        $value = sanitize_text_field($value);
                    });
                } else {
                    $sanitized_value = sanitize_text_field($value);
                }
            } else {
                $available_options = self::get_available_options();

              /**
               * Check if whitelisted
               */
                if (! isset($available_options[ $option_name ])) {
                    TagGroups_Error::log('[Tag Groups] Unknown option: %s', $option_name);

                    return false;
                }

                $sanitized_value = false;

              /**
               * sanitize
               */
                switch ($available_options[ $option_name ]['type']) {
                    case self::INTEGER:
                        if ($value === true) {
                              $sanitized_value = 1;
                        } elseif ($value === false) {
                            $sanitized_value = 0;
                        } else {
                            $sanitized_value = (int) $value;
                        }
                        break;

                    case self::STRING:
                        $sanitized_value = sanitize_text_field($value);
                        break;

                    case self::STRING_HTML:
                        $sanitized_value = self::wp_kses($value);
                        break;
          
                    case self::ARRAY_OF_INTEGERS:
                        if (! self::array_or_log($value, $option_name)) {
                            return false;
                        }

                        $sanitized_value = $value;
            
                        array_walk_recursive($sanitized_value, function (&$value) {
                            $value = (int) $value;
                        });

                        break;

                    case self::ARRAY_OF_STRINGS:
                        if (! self::array_or_log($value, $option_name)) {
                            return false;
                        }

                        $sanitized_value = $value;
            
                        array_walk_recursive($sanitized_value, function (&$value) {
                            $value = sanitize_text_field($value);
                        });
                        break;

                    case self::ARRAY_OF_STRINGS_HTML:
                        if (! self::array_or_log($value, $option_name)) {
                            return false;
                        }

                        $sanitized_value = $value;
            
                        array_walk_recursive($sanitized_value, function (&$value) {
                            $value = TagGroups_Options::wp_kses($value);
                        });
                        break;

                    case self::MIXED:
                      // We don't handle this case => not to be used to store user input
                        TagGroups_Error::log('[Tag Groups] MIXED type for %s', $option_name);

                        $sanitized_value = $value;

                        break;
                }
            }

            if ($sanitized_value !== $value && ! ( $available_options[ $option_name ]['type'] == self::INTEGER && $value == $sanitized_value )) {
                TagGroups_Error::verbose_log('[Tag Groups] Sanitizing changed the value for %s', $option_name);
            }

          /**
           * save as WP option
           */
            return update_option($option_name, $sanitized_value, $autoload);
        }


      /**
       * Checks if $value is an array, log if not
       *
       * @param mixed $value
       * @param string $option_name
       * @return void
       */
        public static function array_or_log($value, $option_name)
        {

            if (! is_array($value)) {
                TagGroups_Error::verbose_log('[Tag Groups] Wrong data type: %s', $option_name);

                return false;
            }

            return true;
        }


      /**
       * intval to be used with array_walk_recursive
       * 
       * to avoid PHP Warning: intval() expects parameter 2 to be int, because array_walk_recursive sends the key as second parameter, but intval expects second parameter to be a integer
       *
       * @deprecated 1.40.2
       * @param mixed $value
       * @return integer
       */
        private static function intval_one_parameter($value)
        {

            return (int) $value;
        }


      /**
       * call wp_kses with extended parameters
       *
       * @param string $unsanitized_html
       * @return string
       */
        public static function wp_kses($unsanitized_html)
        {

            add_filter('safe_style_css', function ($styles) {
                $styles[] = 'position';
                $styles[] = 'bottom';
                $styles[] = 'top';
                $styles[] = 'left';
                $styles[] = 'right';
                return $styles;
            });

            return wp_kses($unsanitized_html, array_merge(wp_kses_allowed_html('post'), self::ALLOWED_HTML));
        }


      /**
       * Deletes all registered options
       *
       * @return void
       */
        public static function delete_all()
        {

            $option_count = 0;

            $available_options = TagGroups_Options::get_available_options();

            if (is_array(TagGroups_Options::get_option('tag_group_group_languages', false))) {
                foreach (TagGroups_Options::get_option('tag_group_group_languages') as $language) {
                    if (delete_option('term_group_labels_' . $language)) {
                        $option_count++;
                    }
                }
            }

            foreach ($available_options as $key => $value) {
                if (delete_option($key)) {
                    $option_count++;
                }
            }

            return $option_count;
        }
    }

}
