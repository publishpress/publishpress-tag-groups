<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Group_Tools')) {
/**
   *
   */
    class TagGroups_Group_Tools
    {
      /**
       * Checks groups and fixes problems
       *
       * @param  boolean   $count_only
       * @param  integer   $offset
       * @param  integer   $length
       * @return integer
       */
        public static function check_fix_groups($count_only = false, $offset = null, $length = null)
        {

            global $tag_group_groups;
            $count = 0;
            $group_ids = $tag_group_groups->get_group_ids();
            if ($count_only) {
                if (is_array($group_ids)) {
                    return count($group_ids);
                } else {
                    return 0;
                }
            }

            if (isset($offset) && isset($length)) {
                $group_ids = array_slice($group_ids, $offset, $length);
            }

          // remove actions, we'll process anyway later
            remove_all_actions('term_group_saved');
            $need_to_save = false;
            if (0 == $offset) {
                  // test that runs only once

                  $group_ids_all = $tag_group_groups->get_group_ids();
                  // unsliced

                  $tag_group_groups->reindex_positions()->save();
                  $positions = $tag_group_groups->get_positions();
                  /**
                             * make sure all keys are group ids
                             *
                             */

                foreach ($positions as $id => $position) {
                    if (! in_array($id, $group_ids_all)) {
                        TagGroups_Error::verbose_log('[Tag Groups Pro] Removing orphaned group position for ID %d.', $id);
                        unset($positions[$id]);
                        $need_to_save = true;
                        $count++;
                    }
                }

                    $tag_group_groups->set_positions($positions);
                  $labels = $tag_group_groups->get_labels();
                  /**
                             * make sure all keys are group ids
                             *
                             */

                foreach ($labels as $id => $label) {
                    if (! in_array($id, $group_ids_all)) {
                        TagGroups_Error::verbose_log('[Tag Groups Pro] Removing orphaned group label for ID %d.', $id);
                        unset($labels[$id]);
                        $need_to_save = true;
                        $count++;
                    }
                }

                    $tag_group_groups->set_labels($labels);
            }

            foreach ($group_ids as $group_id) {
                $fixed_this_one = false;
    // fixed this group

                $tg_group = new TagGroups_Group($group_id);
                if (0 == $group_id) {
                /**
                             * position of group 0 must be 0
                             *
                             */

                    if (0 != $tg_group->get_position()) {
                        $tg_group->set_position(0);
                        TagGroups_Error::verbose_log('[Tag Groups Pro] Fixing position for group ID 0.');
                        $need_to_save = true;
                        $fixed_this_one = true;
                    }

                /**
                 * label cannot be empty
                 *
                 */

                    if ('' == trim($tg_group->get_label())) {
                                TagGroups_Error::verbose_log('[Tag Groups Pro] Fixing label for group ID 0.');
                                $tg_group->set_label('unassigned');
                                $need_to_save = true;
                                $fixed_this_one = true;
                    }
                } else {
                /**
                             * position of group must not be 0
                             *
                             */

                    if (0 == $tg_group->get_position()) {
                        TagGroups_Error::verbose_log('[Tag Groups Pro] Fixing position for group ID %d.', $group_id);
                        $tg_group->set_position($tag_group_groups->get_max_position() + 1);
                        $need_to_save = true;
                        $fixed_this_one = true;
                    }

                /**
                 * label cannot be empty
                 *
                 */

                    if ('' == trim($tg_group->get_label())) {
                                TagGroups_Error::verbose_log('[Tag Groups Pro] Fixing label for group ID %d.', $group_id);
                                $tg_group->set_label('empty!');
                                $need_to_save = true;
                                $fixed_this_one = true;
                    }
                }

              /**
               * prevent duplicate positions
               *
               */
                $all_positions = $tag_group_groups->get_positions();
                foreach ($all_positions as $term_group => $position) {
                    if ($tg_group->get_position() == $position && $group_id != $term_group) {
                        TagGroups_Error::verbose_log('[Tag Groups Pro] Fixing duplicate position for group ID %d.', $group_id);
            // move to the end
                          $tg_group->set_position($tag_group_groups->get_max_position() + 1);
                        $need_to_save = true;
                        $fixed_this_one = true;
                        break;
            // We are already behind the end
                    }
                }

                if ($fixed_this_one) {
                    $count++;
                }
            }

            if ($need_to_save) {
                $tg_group->save();
            }

            return $count;
        }
    }


}
