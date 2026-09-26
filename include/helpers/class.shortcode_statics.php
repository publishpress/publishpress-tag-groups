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
            add_filter('widget_text', 'do_shortcode');
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
    }


}
