<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps, WordPress.DB.SlowDBQuery, WordPressVIPMinimum.Performance.RegexpCompare
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Transients', false)) {
    require_once dirname(__DIR__, 2) . '/helpers/cache/class.transients.php';
}

if (! class_exists('TagGroups_Term_Meta_Tools')) {
/**
   *
   */
    class TagGroups_Term_Meta_Tools
    {
      /**
       * Removes non-existent groups from term meta
       *
       * @param  boolean   $count_only
       * @param  integer   $offset
       * @param  integer   $length
       * @return integer
       */
        public static function remove_missing_groups($count_only = false, $offset = null, $length = null)
        {

            global $tag_group_groups;
            $group_ids = $tag_group_groups->get_group_ids();
            $count = 0;
            if ($count_only) {
                    // In case we restarted the tasks, we need to get fresh data

                      $term_ids = false;
            } else {
                  $term_ids = TagGroups_Transients::get_transient('tag_groups_remove_missing_groups');
            }

            if (false === $term_ids) {
                $args = array(
                'hide_empty' => false,
                'taxonomy'   => TagGroups_Taxonomy::get_enabled_taxonomies(),
                'fields'     => 'ids',
                'meta_query' => array(
                array(
                  'key'     => '_cm_term_group_array',
                  'compare' => 'EXISTS',
                ),
                ),
                );
                $term_ids = get_terms($args);
    // Try to keep for 10 minutes so that our offset always starts with unprocessed items.
                TagGroups_Transients::set_transient('tag_groups_remove_missing_groups', $term_ids, 600);
            }

            if ($count_only) {
                if (is_array($term_ids)) {
                    return count($term_ids);
                } else {
                    return 0;
                }
            }

            if (isset($offset) && isset($length)) {
                $term_ids = array_slice($term_ids, $offset, $length);
            }

            if (is_array($term_ids)) {
                foreach ($term_ids as $term_id) {
                    $changed = false;
                    $groups = get_term_meta($term_id, '_cm_term_group_array', true);
                    if (',,' == $groups) {
            // fixing results of bug in version <= 1.23.0

                        $groups_a = array( 0 );
                        TagGroups_Error::verbose_log('[Tag Groups Pro] We remove an empty group from the term meta of term ID %d.', $term_id);
                        $changed = true;
                    } else {
                        $groups_a = explode(',', $groups);

              // remove empty values
                        $groups_a = array_filter($groups_a, function ($v) {
                              return '' != $v;
                        });
                        foreach ($groups_a as $key => $group) {
                            if (! in_array($group, $group_ids)) {
                // This group id doesn't exist -> we remove it from the term meta

                                TagGroups_Error::verbose_log('[Tag Groups Pro] We remove a non-existent group ID %d from the term meta of term ID %d.', $group, $term_id);
                                unset($groups_a[$key]);
                                $changed = true;
                            }
                        }
                    }

                    if ($changed) {
                        if (count($groups_a) == 0) {
                            $groups_a = array( 0 );
                        }

              // We need to update the term meta.

                        update_term_meta($term_id, '_cm_term_group_array', ',' . implode(',', $groups_a) . ',');
                        $count++;
                    }
                }
            }

            return $count;
        }

      /**
       * Converts term meta to new format that is faster to process
       *
       * @param  void
       * @return int    number of processed items
       */
        public static function term_meta_add_comma($count_only = false, $offset = null, $length = null)
        {

            if ($count_only) {
    // In case we restarted the tasks, we need to get fresh data

                $term_ids = false;
            } else {
                $term_ids = TagGroups_Transients::get_transient('tag_groups_term_meta_add_comma_ids');
            }

            if (false === $term_ids) {
    /**
               * Process only those that don't have any meta
               */
                $args_new = array(
                'hide_empty' => false,
                'fields'     => 'ids',
                'taxonomy'   => TagGroups_Taxonomy::get_enabled_taxonomies(),
                'meta_query' => array(
                'relation' => 'AND',
                array(
                  'key'     => '_cm_term_group_array',
                  'compare' => 'EXISTS',
                ),
                array(
                  'key'     => '_cm_term_group_array',
                  'value'   => '^,.+,$',
                  'compare' => 'REGEXP', // not possible to construct with LIKE and ',%,'
                ),
                ),
                );
                $args_all = array(
                'hide_empty' => false,
                'fields'     => 'ids',
                'taxonomy'   => TagGroups_Taxonomy::get_enabled_taxonomies(),
                'meta_query' => array(
                'relation' => 'AND',
                array(
                  'key'     => '_cm_term_group_array',
                  'compare' => 'EXISTS',
                ),
                ),
                );
                $term_ids_new = get_terms($args_new);
                $term_ids_all = get_terms($args_all);
                $term_ids = array_diff($term_ids_all, $term_ids_new);
    // Try to keep for 10 minutes so that our offset always starts with unprocessed items.
                TagGroups_Transients::set_transient('tag_groups_term_meta_add_comma_ids', $term_ids, 600);
            }

            if ($count_only) {
                if (is_array($term_ids)) {
                    return count($term_ids);
                } else {
                    return 0;
                }
            }

            if (isset($offset) && isset($length)) {
                $term_ids = array_slice($term_ids, $offset, $length);
            }

            $count = 0;
            if (is_array($term_ids)) {
                foreach ($term_ids as $term_id) {
                    $groups = get_term_meta($term_id, '_cm_term_group_array', true);
                /**
                             * Check if this is the old format without commas at beginning and end
                             * 
                             */
                    if (substr($groups, 0, 1) != ',' || substr($groups, -1, 1) != ',' || ',,' == $groups) {
                        $groups_a = explode(',', $groups);

                // remove empty values
                        $groups_a = array_filter($groups_a, function ($v) {
                            return '' != $v;
                        });
                        if (empty($groups_a)) {
                                $groups_a = array( 0 );
                        }

                /**
                 *  Fast way of saving: not necessary to use method save()
                 */

                        update_term_meta($term_id, '_cm_term_group_array', ',' . implode(',', $groups_a) . ',');
                        $count++;
                    }
                }
            }

            return $count;
        }

      /**
       * Converts all WP-native term_group attributes to the term meta format, if no term meta format was found.
       * Term meta must use _cm_term_group_array as key.
       *
       * @param  void
       * @return int|bool    number of processed items
       */
        public static function convert_to_term_meta($count_only = false, $offset = null, $length = null)
        {

            if ($count_only) {
    // In case we restarted the tasks, we need to get fresh data

                $terms = false;
            } else {
                $terms = TagGroups_Transients::get_transient('tag_groups_convert_to_term_meta_terms');
            }

            if (false === $terms) {
    /**
               * Process only those that don't have any meta
               */
                $args = array(
                'hide_empty' => false,
                'taxoomy'    => TagGroups_Taxonomy::get_enabled_taxonomies(),
                'meta_query' => array(
                array(
                  'key'     => '_cm_term_group_array',
                  'compare' => 'NOT EXISTS',
                ),
                ),
                );
                $terms = get_terms($args);
    // Try to keep for 10 minutes so that our offset always starts with unprocessed items.
                TagGroups_Transients::set_transient('tag_groups_convert_to_term_meta_terms', $terms, 600);
            }

            if ($count_only) {
                if (is_array($terms)) {
                    return count($terms);
                } else {
                    return 0;
                }
            }

            if (isset($offset) && isset($length)) {
                if (! is_array($terms) || $offset + $length > count($terms)) {
                    return false;
                }

                $terms = array_slice($terms, $offset, $length);
            }

            $count = 0;
            if (is_array($terms)) {
                foreach ($terms as $term) {
                    TagGroups_Error::verbose_log('[Tag Groups Pro] Migrating term meta for term ID %d.', $term->term_id);
                /**
                             *  Fast way of saving: not necessary to use method save()
                             */
                            $result = update_term_meta($term->term_id, '_cm_term_group_array', ',' . $term->term_group . ',');
                    if (is_int($result) || true === $result) {
                        $count++;
                    }
                }
            }

            return $count;
        }

      /**
       * Sort groups in the term meta - this is needed for sorting by term group on the tags admin page
       *
       * @param  boolean   $count_only
       * @param  integer   $offset
       * @param  integer   $length
       * @return integer
       */
        public static function sort_groups($force_rerun = true, $count_only = false, $offset = null, $length = null)
        {

            global $tag_group_groups;
            $count = 0;
            if ($force_rerun || $count_only) {
                    // In case we restarted the tasks, we need to get fresh data

                    $term_ids = false;
            } else {
                  $term_ids = TagGroups_Transients::get_transient('tag_groups_sort_groups');
            }

            if (false === $term_ids) {
                $args = array(
                'hide_empty' => false,
                'taxonomy'   => TagGroups_Taxonomy::get_enabled_taxonomies(),
                'fields'     => 'ids',
                'meta_query' => array(
                array(
                  'key'     => '_cm_term_group_array',
                  'compare' => 'EXISTS',
                ),
                ),
                );
                $term_ids = get_terms($args);
    // Try to keep for 10 minutes so that our offset always starts with unprocessed items.
                TagGroups_Transients::set_transient('tag_groups_sort_groups', $term_ids, 600);
            }

            if ($count_only) {
                if (is_array($term_ids)) {
                    return count($term_ids);
                } else {
                    return 0;
                }
            }

            if (isset($offset) && isset($length)) {
                $term_ids = array_slice($term_ids, $offset, $length);
            }

            if (is_array($term_ids)) {
                foreach ($term_ids as $term_id) {
                    $groups = get_term_meta($term_id, '_cm_term_group_array', true);
                    $groups_a = explode(',', $groups);

                        // remove empty values and zeros
                        $groups_a = array_filter($groups_a, function ($v) {
                            return '' != $v;
                        });
                    $groups_a = array_map('intval', $groups_a);
                    if (array_search(0, $groups_a)) {
                        $groups_sorted = array( 0 );
                    } else {
                        $groups_sorted = array();
                        foreach ($tag_group_groups->get_positions() as $group => $position) {
                            if (array_search($group, $groups_a) !== false) {
                                $groups_sorted[] = $group;
                            }
                        }
                    }

                    $groups_sorted = array_values($groups_sorted);
                    $groups_a = array_values($groups_a);
                    if ($groups_sorted !== $groups_a) {
        // comparing order

                        TagGroups_Error::verbose_log('[Tag Groups Pro] Group order changed in meta of term %d.', $term_id);
                        if (count($groups_sorted) == 0) {
                                $groups_sorted = array( 0 );
                        }

              // We need to update the term meta.

                        update_term_meta($term_id, '_cm_term_group_array', ',' . implode(',', $groups_sorted) . ',');
                        $count++;
                    }
                }
            }

            return $count;
        }
    }


}
