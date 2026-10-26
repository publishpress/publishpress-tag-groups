<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (! class_exists('TagGroups_Shortcode_Statics')) {
    class TagGroups_Shortcode_Statics
    {
          /**
           * Register the shortcodes with WordPress
           * 
           * @param void
           * @return void
           */
        static function register()
        {

        /**
         * Tabbed tag cloud
         */
            $object_TagGroups_Shortcode_Tabs = new TagGroups_Shortcode_Tabs();
            add_shortcode('tag_groups_cloud', array( $object_TagGroups_Shortcode_Tabs, 'tag_groups_cloud' ));
            if (function_exists('register_block_type')) {
                register_block_type('chatty-mango/tag-groups-cloud-tabs', array(
                          'attributes'      => TagGroups_Shortcode_Tabs::$serverside_render_attributes,
                          'render_callback' => array( $object_TagGroups_Shortcode_Tabs, 'tag_groups_cloud' ),
                ));
            }

        /**
         * Accordion tag cloud
         */
            $object_TagGroups_Shortcode_Accordion = new TagGroups_Shortcode_Accordion();
            add_shortcode('tag_groups_accordion', array( $object_TagGroups_Shortcode_Accordion, 'tag_groups_accordion' ));
            if (function_exists('register_block_type')) {
                register_block_type('chatty-mango/tag-groups-cloud-accordion', array(
                        'attributes'      => TagGroups_Shortcode_Accordion::$serverside_render_attributes,
                        'render_callback' => array( $object_TagGroups_Shortcode_Accordion, 'tag_groups_accordion' ),
                ));
            }

        /**
         * Tabbed tag cloud with first letters as tabs
         */
            $object_TagGroups_Shortcode_Alphabet_Tabs = new TagGroups_Shortcode_Alphabet_Tabs();
            add_shortcode('tag_groups_alphabet_tabs', array( $object_TagGroups_Shortcode_Alphabet_Tabs, 'tag_groups_alphabet_tabs' ));
            if (function_exists('register_block_type')) {
                register_block_type('chatty-mango/tag-groups-alphabet-tabs', array(
                        'attributes'      => TagGroups_Shortcode_Alphabet_Tabs::$serverside_render_attributes,
                        'render_callback' => array( $object_TagGroups_Shortcode_Alphabet_Tabs, 'tag_groups_alphabet_tabs' ),
                ));
            }

        /**
         * Group info
         */
            $object_TagGroups_Shortcode_Info = new TagGroups_Shortcode_Info();
            add_shortcode('tag_groups_info', array( $object_TagGroups_Shortcode_Info, 'tag_groups_info' ));
/**
         * Tags listed under group names
         */
            $object_TagGroups_Shortcode_Tag_List = new TagGroups_Shortcode_Tag_List();
            add_shortcode('tag_groups_tag_list', array( $object_TagGroups_Shortcode_Tag_List, 'tag_groups_tag_list' ));
            if (function_exists('register_block_type')) {
                register_block_type('chatty-mango/tag-groups-tag-list', array(
                        'attributes'      => TagGroups_Shortcode_Tag_List::$serverside_render_attributes,
                        'render_callback' => array( $object_TagGroups_Shortcode_Tag_List, 'tag_groups_tag_list' ),
                ));
            }

        /**
         * Tags listed under first letters
         */
            $object_TagGroups_Shortcode_Alphabetical_Index = new TagGroups_Shortcode_Alphabetical_Index();
            add_shortcode('tag_groups_alphabetical_index', array( $object_TagGroups_Shortcode_Alphabetical_Index, 'tag_groups_alphabetical_index' ));
            if (function_exists('register_block_type')) {
                register_block_type('chatty-mango/tag-groups-alphabetical-tag-index', array(
                        'attributes'      => TagGroups_Shortcode_Alphabetical_Index::$serverside_render_attributes,
                        'render_callback' => array( $object_TagGroups_Shortcode_Alphabetical_Index, 'tag_groups_alphabetical_index' ),
                ));
            }
        }

      /**
       * Makes sure that shortcodes work in text widgets.
       * 
       * @param void
       * @return void
       */
        static function maybe_do_shortcode_in_widgets()
        {

            $tag_group_shortcode_widget = TagGroups_Options::get_option('tag_group_shortcode_widget', 0);
            if ($tag_group_shortcode_widget) {
                add_filter('widget_text', 'do_shortcode');
            }
        }

      /**
       * decodes a string that has been encoded for Ajax transmission
       *
       * @param  string   $maybe_encoded_template
       * @return string
       */
        static function decode_string($maybe_encoded_template)
        {

            if ('' === $maybe_encoded_template) {
                return '';
            }

            $maybe_base64_decoded = base64_decode($maybe_encoded_template, true);
            if (false === $maybe_base64_decoded) {
                return html_entity_decode($maybe_encoded_template);
            }

            return urldecode($maybe_base64_decoded);
        }

      /**
       * modifies the term query to return only terms that have a minimum post count
       *
       * @param  array   $pieces
       * @param  array   $taxonomies
       * @param  array   $args
       * @return array
       */
        public static function terms_clauses_threshold($pieces, $taxonomies, $args)
        {

            if (empty($args['threshold'])) {
                return $pieces;
            }

            $one_less_than_threshold = (int) $args['threshold'] - 1;
    /**
             * We first try to find "AND tt.count > 0" and replace the number
             */
            $result = preg_replace('/(.*AND tt.count > )(\d+)(.*)/imu', '${1}' . $one_less_than_threshold . '$3', $pieces['where']);
            if ($result != $pieces['where']) {
            /**
                       * we found it
                       */

                  $pieces['where'] = $result;
            } else {
            /**
                       * we haven't found it amd simply attach our condition
                       */

                  $pieces['where'] = sprintf("%s AND tt.count > %d", $pieces['where'], $one_less_than_threshold);
            }

            return $pieces;
        }

      /**
       * sanitizes many classes separated by space
       *
       * @param  string   $classes
       * @return string
       */
        public static function sanitize_html_classes($classes)
        {

            // replace multiple spaces by one
            $classes = preg_replace('!\s+!', ' ', $classes);
    // turn into array
            $classes = explode(' ', $classes);
            if (! empty($classes)) {
                $classes = array_map('sanitize_html_class', $classes);
            }

            // turn back
            $classes = implode(' ', $classes);
            return $classes;
        }

        /**
         * Sanitizes the link target shortcode attribute.
         *
         * @param  string $target
         * @return string
         */
        public static function sanitize_link_target($target)
        {
            $target = strtolower(trim((string) $target));
            $allowed_targets = array( '_blank', '_self', '_parent', '_top' );

            if (!in_array($target, $allowed_targets, true)) {
                return '';
            }

            return $target;
        }

        /**
         * Per-request instance counters, keyed by ID pattern
         *
         * @var array
         */
        private static $instance_counters = array();

        /**
         * Inline fallback for the screen-reader-only class, in case the frontend CSS is disabled.
         */
        const SR_ONLY_STYLE = 'position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);clip-path:inset(50%);white-space:nowrap;border:0;';

        /**
         * Returns the next instance number (1-based) for a given ID pattern within this request.
         *
         * @param  string $group
         * @return int
         */
        public static function next_instance($group)
        {
            if (! isset(self::$instance_counters[ $group ])) {
                self::$instance_counters[ $group ] = 0;
            }

            return ++self::$instance_counters[ $group ];
        }

        /**
         * Makes the tab panel IDs ("tabs-1{$i}") unique per instance.
         *
         * The first instance on a page keeps its original IDs so that existing deep links keep working.
         * Applied on the final HTML so that cached output gets the same treatment.
         *
         * @param  string $html
         * @param  int    $instance
         * @return string
         */
        public static function prefix_tab_panel_ids($html, $instance)
        {
            if ($instance <= 1) {
                return $html;
            }

            $prefix = 'tg' . (int) $instance . '-';

            return preg_replace('/(\shref="#|\sid=")tabs-1(\d+)"/', '${1}' . $prefix . 'tabs-1${2}"', $html);
        }

        /**
         * Wraps text in a visually hidden span
         *
         * @param  string $text Already escaped text
         * @return string
         */
        public static function sr_only_html($text)
        {
            return '<span class="tag-groups-sr" style="' . self::SR_ONLY_STYLE . '">' . $text . '</span>';
        }

        /**
         * Returns a visually hidden post count for a tag link, if the count is otherwise only available in the title attribute.
         *
         * @param  object $attributes Shortcode attributes
         * @param  int    $post_count
         * @return string
         */
        public static function sr_count_html($attributes, $post_count)
        {
            $post_count = (int) $post_count;

            // The count is already visible in the link text.
            foreach (array( 'prepend', 'append' ) as $key) {
                if (! empty($attributes->{$key}) && false !== strpos((string) $attributes->{$key}, '{count}')) {
                    return '';
                }
            }

            $show_tag_count = ! empty($attributes->show_tag_count);
            $count_in_title = $show_tag_count;

            $custom_title_zero   = isset($attributes->custom_title_zero) ? $attributes->custom_title_zero : null;
            $custom_title_plural = isset($attributes->custom_title_plural) ? $attributes->custom_title_plural : null;
            $custom_title        = isset($attributes->custom_title) ? $attributes->custom_title : null;

            if (! is_null($custom_title) || ! is_null($custom_title_zero) || ! is_null($custom_title_plural)) {
                // Mirrors the template selection in TagGroups_Shortcode_Common::get_title()
                if (0 == $post_count && ! is_null($custom_title_zero)) {
                    $template = $custom_title_zero;
                } elseif ($post_count > 1 && ! is_null($custom_title_plural)) {
                    $template = $custom_title_plural;
                } else {
                    $template = $custom_title;
                }
                $template = (string) $template;

                if ('' === trim($template)) {
                    $count_in_title = $show_tag_count;
                } else {
                    $count_in_title = false !== strpos($template, '{count}');
                }
            }

            if (! $count_in_title) {
                return '';
            }

            /* translators: %s: number of posts */
            $text = sprintf(_n('(%s post)', '(%s posts)', $post_count, 'tag-groups'), number_format_i18n($post_count));

            return self::sr_only_html(' ' . esc_html($text));
        }

        /**
         * Returns the decorative separator, hidden from assistive technology
         *
         * @param  string     $separator
         * @param  int|string $font_size
         * @return string
         */
        public static function separator_html($separator, $font_size)
        {
            return '<span aria-hidden="true" style="font-size:' . $font_size . 'px">' . $separator . '</span> ';
        }

        /**
         * Clamps a heading level to 1-6
         *
         * @param  mixed $level
         * @return int
         */
        public static function clamp_heading_level($level)
        {
            $level = (int) $level;

            if ($level < 1) {
                return 1;
            }

            if ($level > 6) {
                return 6;
            }

            return $level;
        }

        /**
         * Keeps a delayed (initially transparent) widget visible when JavaScript is disabled
         *
         * @return string
         */
        public static function noscript_unhide_html()
        {
            return '<noscript><style>.tag-groups-cloud-hidden{opacity:1 !important}</style></noscript>';
        }

        /**
         * Adds the opt-in target size class to the wrapper classes
         *
         * @param  object $attributes Shortcode attributes
         * @param  string $classes
         * @return string
         */
        public static function maybe_add_target_size_class($attributes, $classes)
        {
            if (! empty($attributes->target_size)) {
                $classes .= ' tag-groups-target-size';
            }

            return $classes;
        }
    }


}
