<?php

/**
 * Tag Groups
 *
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (!class_exists('TagGroups_Terms')) {
    class TagGroups_Terms
    {
        /**
         * Deletes the transients that are relevant for terms and groups
         *
         * To be called only if general purging of cache is needed. Otherwise use hooks.
         *
         * @param  int    $rebuild_in_seconds Turned off if less than 0.
         * @return void
         */
        public function clear_term_cache($rebuild_in_seconds = 10)
        {
            $this->clear_post_count_transient($rebuild_in_seconds);
            $languages = apply_filters('wpml_active_languages', null, '');
            
            if (!empty($languages)) {
                foreach ($languages as $language_code => $language_info) {
                    TagGroups_Transients::delete_all_transients('tag_groups_group_terms-' . $language_code);
                }
            } else {
                TagGroups_Transients::delete_all_transients('tag_groups_group_terms');
            }
            
            TagGroups_Transients::delete_transient('tag_groups_post_terms');
            TagGroups_Transients::delete_transient('tag_groups_post_types');
            TagGroups_Transients::delete_transient('tag_groups_post_ids_groups');
        }
        
        /**
         * Clears the transient of post counts per group for terms
         *
         * @param  integer $rebuild_in_seconds
         * @return void
         */
        public function clear_post_count_transient($rebuild_in_seconds = 5)
        {
            if (!TagGroups_Utilities::is_premium_plan()) {
                return;
            }

            $languages = apply_filters('wpml_active_languages', null, '');

            if (!empty($languages)) {
                foreach ($languages as $language_code => $language_info) {
                    TagGroups_Transients::delete_transient('tag_groups_post_counts-' . $language_code);
                }
            } else {
                TagGroups_Transients::delete_transient('tag_groups_post_counts');
            }

            if ($rebuild_in_seconds < 0) {
                return;
            }

            if (
                (!defined('TAG_GROUPS_DISABLE_CACHE_REBUILD') || TAG_GROUPS_DISABLE_CACHE_REBUILD)
                && class_exists('TagGroups_Premium_Cron')
            ) {
                TagGroups_Premium_Cron::schedule_in_secs($rebuild_in_seconds, 'tag_groups_rebuild_post_counts');
            }
        }
        
        /**
         * Helper for natural sorting of names
         *
         * Inspired by _wp_object_name_sort_cb
         *
         * @param  array   $terms
         * @param  string  $order   asc or desc
         * @return array
         */
        public function natural_sorting($terms, $order)
        {
            $factor = ( 'desc' == strtolower($order) ? -1 : 1 );
            // "use" requires PHP 5.3+
            uasort($terms, function ($a, $b) use ($factor) {
                return $factor * strnatcasecmp($a->name, $b->name);
            });
            return array_values($terms);
        }
        
        /**
         * Modifies the query to show only terms that belong to particular tag group
         *
         * @param  array            $args
         * @param  array|int|string $group_ids Tag Group IDs (array of integers or comma-separated list of integers)
         * @param  string           $relation  Logic relation between the Tag Group IDs (and|or)
         * @return array
         */
        public function modify_query_args($args, $group_ids = null, $relation = 'OR')
        {
            global  $tag_group_groups ;
            if (empty($group_ids)) {
                return $args;
            }
            if (!is_array($group_ids)) {
                $group_ids = explode(',', $group_ids);
            }
            $group_ids = array_map('intval', $group_ids);
            // intval also trims spaces
            if (strtoupper($relation) != 'OR') {
                $relation = 'AND';
            }
            /**
             * searching for not-assigned terms
             */
            if (in_array(0, $group_ids)) {
                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                $args['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                    'key'     => '_cm_term_group_array',
                    'value'   => ',0,',
                    'compare' => 'LIKE',
                ),
                    array(
                    'key'     => '_cm_term_group_array',
                    'compare' => 'NOT EXISTS',
                ),
                );
                return $args;
            }
            
            $meta_query = array(
                'relation' => $relation,
            );
            $group_ids = array_intersect($group_ids, $tag_group_groups->get_group_ids());
            
            if (count($group_ids) == 0) {
                // never matches -> create dummy condition that never is true
                $meta_query[] = array(
                    'key'     => '_cm_term_group_array_dummy',
                    'compare' => 'EXISTS',
                );
            } else {
                foreach ($group_ids as $group_id) {
                    $meta_query[] = array(
                        'key'     => '_cm_term_group_array',
                        'value'   => ',' . $group_id . ',',
                        'compare' => 'LIKE',
                    );
                }
            }
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
            $args['meta_query'] = $meta_query;
            return $args;
        }
        
        /**
         * Filter to enable term_order for orderby
         *
         * @param  string  $orderby
         * @param  array   $query_vars
         * @return array
         */
        public function enable_terms_order($orderby, $query_vars)
        {
            return ( 'term_order' == $query_vars['orderby'] ? 'term_order' : $orderby );
        }
    }
}
