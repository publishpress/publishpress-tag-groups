<?php

/**
* Tag Groups
*
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
* @since       0.38
*
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (!class_exists('TagGroups_Taxonomy')) {
    class TagGroups_Taxonomy
    {
        /**
         * Constructor
         *
         *
         */
        public function __construct()
        {
        }
        
        /**
         * Removes taxonomy names that are not registered with this WP site as public
         *
         *
         * @param array $taxonomy_slugs
         * @return array
         */
        public static function remove_invalid($taxonomy_slugs)
        {
            if (!is_array($taxonomy_slugs)) {
                $taxonomy_slugs = array( $taxonomy_slugs );
            }
            $valid_taxonomy_slugs = self::get_public_taxonomies();
            return array_values(array_intersect($taxonomy_slugs, $valid_taxonomy_slugs));
        }
        
        /**
         * Returns taxonomy names that are enabled in the options
         *
         *
         * @param array|string $intersect_taxonomy_slugs Optional array of taxonomy names that needs to be intersected
         * @return array
         */
        public static function get_enabled_taxonomies($intersect_taxonomy_slugs = null)
        {
            if (!empty($intersect_taxonomy_slugs) && !is_array($intersect_taxonomy_slugs)) {
                $intersect_taxonomy_slugs = array( $intersect_taxonomy_slugs );
            }
            $tag_group_taxonomies = TagGroups_Options::get_option('tag_group_taxonomy', array( 'post_tag' ));
            $valid_taxonomy_slugs = self::get_public_taxonomies();
            
            if (empty($intersect_taxonomy_slugs)) {
                return array_values(array_intersect($tag_group_taxonomies, $valid_taxonomy_slugs));
            } else {
                return array_values(array_intersect($tag_group_taxonomies, $valid_taxonomy_slugs, $intersect_taxonomy_slugs));
            }
        }
        
        /**
         * Returns taxonomy names that are enabled in the options for the metabox
         *
         *
         * @param array $intersect_taxonomy_slugs Optional array of taxonomy names that needs to be intersected
         * @return array
         */
        public static function get_taxonomies_for_metabox($intersect_taxonomy_slugs = null)
        {
            if (TagGroups_Utilities::is_premium_plan()) {
                $tag_group_taxonomies = TagGroups_Options::get_option('tag_group_taxonomy', array( 'post_tag' ));
                $tag_group_meta_box_taxonomies = TagGroups_Options::get_option('tag_group_meta_box_taxonomy', array());
                $valid_taxonomy_slugs = self::get_public_taxonomies();

                if (empty($intersect_taxonomy_slugs)) {
                    return array_values(array_intersect($tag_group_taxonomies, $valid_taxonomy_slugs, $tag_group_meta_box_taxonomies));
                }

                return array_values(array_intersect($tag_group_taxonomies, $valid_taxonomy_slugs, $tag_group_meta_box_taxonomies, $intersect_taxonomy_slugs));
            }

            return array();
        }
        
        /**
         * Returns taxonomy names that are registered with this WP site
         *
         *
         * @param void
         * @return array
         */
        public static function get_public_taxonomies()
        {
            return get_taxonomies(array(
                'public' => true,
            ), 'names');
        }
        
        /**
         *   Retrieves post types from taxonomies
         *
         * @param array|string $taxonomy_slugs
         * @return array
         */
        static function post_types_from_taxonomies($taxonomy_slugs = array())
        {
            if (!is_array($taxonomy_slugs)) {
                $taxonomy_slugs = array( $taxonomy_slugs );
            }
            $taxonomy_slugs = array_values($taxonomy_slugs);
            $post_types = array();
            foreach ($taxonomy_slugs as $taxonomy) {
                $post_type_a = array();
                $taxonomy_o = get_taxonomy($taxonomy);
                /**
                 * The return value of get_taxonomy can be false
                 */
                if (!empty($taxonomy_o)) {
                    $post_type_a = $taxonomy_o->object_type;
                }
                if (!empty($post_type_a)) {
                    foreach ($post_type_a as $post_type) {
                        if (!in_array($post_type, $post_types)) {
                            $post_types[] = $post_type;
                        }
                    }
                }
            }
            return $post_types;
        }
        
        /**
         * Gets the taxonomy name for a given slug
         *
         *
         * @param string $taxonomy_slug
         * @return string name
         */
        public static function get_name_from_slug($taxonomy_slug)
        {
            $taxonomy = get_taxonomy($taxonomy_slug);
            
            if (is_object($taxonomy) && is_object($taxonomy->labels)) {
                return $taxonomy->labels->name;
            } else {
                return $taxonomy_slug;
            }
        }
        
        /**
         * Wrapper for backwards compatibility
         *
         * @deprecated
         * @param array $intersect_taxonomy_slugs Optional array of taxonomy names that needs to be intersected
         * @return array
         */
        public static function get_metabox($intersect_taxonomy_slugs = null)
        {
            TagGroups_Error::deprecated();
            return self::get_taxonomies_for_metabox($intersect_taxonomy_slugs);
        }
        
        /**
         * Returns the URL to a tag groups admin page
         *
         *
         * @param string $taxonomy
         * @return string
         */
        public static function get_tag_group_admin_url($taxonomy)
        {
            $post_type = TagGroups_Utilities::get_first_element(self::post_types_from_taxonomies($taxonomy));
            
            if ('post' == $post_type) {
                $rel_url = 'edit.php?page=tag-groups_' . $post_type;
            } else {
                $rel_url = 'edit.php?post_type=' . $post_type . '&page=tag-groups_' . $post_type;
            }
            
            return admin_url($rel_url);
        }
        
        /**
         * Update the list of enabled taxonomies and trigger required actions
         *
         * @param array $taxonomies
         * @return void
         */
        public static function update_enabled($taxonomies)
        {
            
            if (TagGroups_Options::update_option('tag_group_taxonomy', $taxonomies)) {
                // trigger actions
                do_action('tag_groups_taxonomies_saved', $taxonomies);
                if (
                    TagGroups_Utilities::is_premium_plan()
                    && (!defined('TAG_GROUPS_DISABLE_CACHE_REBUILD') || TAG_GROUPS_DISABLE_CACHE_REBUILD)
                ) {
                    TagGroups_Cron::schedule_in_secs(2, 'tag_groups_run_post_migration');
                }
            }
        }
    }
}
