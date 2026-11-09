<?php

/**
 * Tag Groups
 *
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 *
 * @since      1.8.0
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (!class_exists('TagGroups_Groups')) {
    class TagGroups_Groups
    {
        /**
         * array of all term_group values, including 0
         *
         * @var array
         */
        private $group_ids ;
        /**
         * array of positions[term_group]
         *
         * @var array
         */
        private $positions ;
        /**
         * array of labels[term_group]
         *
         * @var array
         */
        private $labels ;
        /**
         * array of IDs that are parents
         *
         * @var array
         */
        private $parents ;
        /**
         * whether we loaded the data
         *
         * @var boolean
         */
        private $loaded ;
        /**
         * last error
         *
         * @var string
         */
        public $error ;
        public const  PERMISSION = 'permission denied' ;
        /**
         * Constructor
         *
         *
         * @param  int    $term_group optional term_group
         * @return return type
         */
        public function __construct()
        {
            $this->loaded = false;
            $this->parents = array();
            return $this;
        }

        /**
         * Load data from database
         *
         * @param  int    $term_group optional term_group
         * @return return type
         */
        public function load()
        {

            /**
             * set flag early to avoid infinite loops
             */
            $this->loaded = true;
            /*
             * For historical reasons, term_groups and labels have been defined dependent of the position.
             * In future the way how it is saved in the database should be dependent on term_group.
             */
            $this->group_ids = TagGroups_Options::get_option('term_groups', array());

            if (empty($this->group_ids)) {
                $this->load_old_format();
            } else {
                $this->positions = TagGroups_Options::get_option('term_group_positions', array());
                if (TagGroups_Utilities::is_premium_plan()) {
                    $this->parents = array_values(array_intersect(
                        TagGroups_Options::get_option('term_group_parents', array()),
                        $this->group_ids
                    ));
                }

                $this->labels = TagGroups_Options::get_option($this->get_tag_group_label_option_name(), array());

                if (empty($this->labels)) {
                    /**
                     * This language has not yet been saved. We return the default language.
                     */
                    $this->labels = TagGroups_Options::get_option('term_group_labels', array());
                } elseif ($this->is_wpml_translated_language()) {
                    /**
                     * Check for untranslated names
                     */
                    $default_language_labels = TagGroups_Options::get_option('term_group_labels', array());
                    foreach ($default_language_labels as $group_id => $default_language_label) {
                        if (!isset($this->labels[$group_id])) {
                            $this->labels[$group_id] = $default_language_label;
                        }
                    }
                }

                /**
                 * sanity checks
                 */
                /**
                 * There should not be more elements for positions than IDs
                 */
                if (count($this->group_ids) != count($this->positions)) {
                    $this->recreate_ids_from_positions();
                }
                /**
                 * There should not be more elements for label that IDs
                 */
                if (count($this->group_ids) < count($this->labels)) {
                    $this->fix_labels();
                }
            }

            /**
             * Filters the group IDs after loading from the database
             *
             * @param int[] $this->group_ids
             * @return int[]
             */
            $this->group_ids = apply_filters('tag_groups_load_group_ids', $this->group_ids);
            /**
             * Filters the group labels after loading from the database
             *
             * @param string[] $this->labels keys are group IDs, values are the labels (names)
             * @return string[]
             */
            $this->labels = apply_filters('tag_groups_load_group_labels', $this->labels);
            /**
             * Filters the group positions after loading from the database
             *
             * @param int[] $this->positions keys are group IDs, values are the positions (determining the order)
             * @return int[]
             */
            $this->positions = apply_filters('tag_groups_load_group_positions', $this->positions);
            return $this;
        }

        /**
         * Loads only on-demand
         *
         * @return object $this
         */
        public function conditionally_load()
        {

            if (!$this->loaded) {
                $this->load();

                if (count($this->group_ids) == 0) {
                    $this->add_not_assigned();
                    $this->save();
                }
            }

            return $this;
        }

        /**
         * checks and, if needed, initialize values for first use
         *
         * @param  void
         * @return object $this
         */
        public function add_not_assigned()
        {
            array_unshift($this->group_ids, 0);
            array_unshift($this->labels, __('not assigned', 'tag-groups'));
            array_unshift($this->positions, 0);
            return $this;
        }

        /**
         * Saves tag group-relevant information to the database
         *
         * @param  type   var    Description
         * @return return type
         */
        public function save()
        {

            $tag_group_role_edit_groups = 'edit_pages';

            if (!current_user_can($tag_group_role_edit_groups)) {
                $this->error = TagGroups_Groups::PERMISSION;
                return $this;
            }

            /**
             * Filters the group IDs before saving to the database
             *
             * @param int[] $this->group_ids
             * @return int[]
             */
            $group_ids = apply_filters('tag_groups_save_group_ids', $this->group_ids);
            /**
             * Filters the group labels before saving to the database
             *
             * @param string[] $this->labels keys are group IDs, values are the labels (names)
             * @return string[]
             */
            $labels = apply_filters('tag_groups_save_group_labels', $this->labels);
            /**
             * Filters the group positions before saving to the database
             *
             * @param int[] $this->positions keys are group IDs, values are the positions (determining the order)
             * @return int[]
             */
            $positions = apply_filters('tag_groups_save_group_positions', $this->positions);
            TagGroups_Options::update_option('term_groups', $group_ids, true);
            TagGroups_Options::update_option('term_group_positions', $positions, true);
            if (TagGroups_Utilities::is_premium_plan()) {
                $parents = apply_filters('tag_groups_save_group_parents', $this->parents);
                TagGroups_Options::update_option('term_group_parents', $parents, true);
            }

            TagGroups_Options::update_option($this->get_tag_group_label_option_name(), $labels, true);
            /**
             * If we save translated groups, make sure we have untranslated ones. If not, give them the translations.
             */

            if ($this->is_wpml_translated_language()) {
                $default_language_labels = TagGroups_Options::get_option('term_group_labels', array());
                $changed = false;
                foreach ($this->labels as $group_id => $group_label) {
                    if (!isset($default_language_labels[$group_id])) {
                        $default_language_labels[$group_id] = $group_label;
                        $changed = true;
                    }
                }
                if ($changed) {
                    TagGroups_Options::update_option('term_group_labels', $default_language_labels);
                }
            }

            do_action('term_group_saved');
            return $this;
        }

        /**
         * returns the highest term_group in use
         *
         * @param  void
         * @return int
         */
        public function get_max_term_group()
        {
            $this->conditionally_load();

            if (count($this->group_ids) == 0) {
                return 0;
            } else {
                return max($this->group_ids);
            }
        }

        /**
         * returns the highest position in use
         *
         * @param  void
         * @return int
         */
        public function get_max_position()
        {
            $this->conditionally_load();

            if (count($this->positions) == 0) {
                return 0;
            } else {
                return max($this->positions);
            }
        }

        /**
         * returns the number of term groups_only
         *
         * @param void
         * @return int
         */
        public function get_number_of_term_groups()
        {
            $this->conditionally_load();
            $count = count($this->group_ids);
            if (isset($this->group_ids[0])) {
                $count--;
            }
            return $count;
        }

        /**
         * filter the list of tag groups by a substring
         *
         * @param string $substring
         * @return object $this
         */
        public function filter_by_substring($substring)
        {
            if (empty($substring)) {
                return $this;
            }
            $this->conditionally_load();
            foreach ($this->group_ids as $key => $group_id) {
                if (0 == $group_id) {
                    continue;
                }
                if (strpos(strtoupper($this->labels[$group_id]), strtoupper($substring)) === false) {
                    /**
                     * Don't unset labels or positions since we might need them with parent IDs
                     */
                    unset($this->group_ids[$key]);
                }
            }
            $this->group_ids = array_values($this->group_ids);
            return $this;
        }

        /**
         * adds a new group
         *
         * @param  object tag group
         * @return object $this
         */
        public function add_group($tg_group)
        {
            $this->conditionally_load();
            if (!is_numeric($tg_group->get_group_id()) || $tg_group->get_group_id() < 1 || in_array($tg_group->get_group_id(), $this->group_ids)) {
                return $this;
            }
            array_push($this->group_ids, $tg_group->get_group_id());
            $this->labels[$tg_group->get_group_id()] = $tg_group->get_label();
            $this->positions[$tg_group->get_group_id()] = $tg_group->get_position();
            return $this;
        }

        /**
         * removes all terms from all groups
         *
         * @param  void
         * @return object $this
         */
        public function unassign_all_terms()
        {
            $this->conditionally_load();
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            $terms = get_terms(array(
                'hide_empty' => false,
                'taxonomy'   => $enabled_taxonomies,
            ));
            foreach ($terms as $term) {
                $term_o = new TagGroups_Term($term);
                $term_o->remove_all_groups()->save();
            }
            return $this;
        }

        /**
         * getter for $group_ids
         *
         * @param  void
         * @return array
         */
        public function get_group_ids()
        {
            $this->conditionally_load();
            return $this->group_ids;
        }

        /**
         * setter for $group_ids
         *
         * @param  array  $group_ids
         * @return object $this
         */
        public function set_group_ids($group_ids)
        {
            $this->conditionally_load();
            $this->group_ids = $group_ids;
            return $this;
        }

        /**
         * returns the labels for an array of ids, sorted by position
         *
         * @param array $group_ids If we don't supply this parameter, we use all groups
         * @return array
         */
        public function get_labels_by_position($group_ids = array())
        {
            $this->conditionally_load();
            $result = array();
            if (empty($group_ids)) {
                $group_ids = $this->group_ids;
            }
            foreach ($group_ids as $group_id) {
                if (!empty($this->labels[$group_id]) && isset($this->positions[$group_id])) {
                    $result[$this->positions[$group_id]] = $this->labels[$group_id];
                }
            }
            ksort($result);
            return array_values($result);
        }

        /**
         * returns an array of group properties as values
         *
         * @param  void
         * @return array
         */
        public function get_info_of_all(
            $taxonomy = null,
            $hide_empty = false,
            $fields = null,
            $orderby = 'name',
            $order = 'ASC'
        ) {
            $this->conditionally_load();
            /**
             * dealing with NULL values
             */
            if (empty($fields)) {
                $fields = 'ids';
            }
            if (empty($taxonomy)) {
                $taxonomy = TagGroups_Taxonomy::get_enabled_taxonomies();
            }
            if (!isset($hide_empty) || empty($hide_empty)) {
                $hide_empty = false;
            }
            $result = array();
            foreach ($this->group_ids as $term_group) {
                if (isset($this->positions[$term_group]) && isset($this->labels[$term_group])) {
                    // allow unassigned
                    $tg_group = new TagGroups_Group($term_group);
                    $terms = $tg_group->get_group_terms(
                        $taxonomy,
                        $hide_empty,
                        $fields,
                        0,
                        $orderby,
                        $order
                    );

                    if (!is_array($terms)) {
                        $terms = array();
                        TagGroups_Error::log('[Tag Groups] Error retrieving terms in get_info().');
                    }

                    $result[$this->positions[$term_group]] = array(
                        'term_group' => (int) $term_group,
                        'label'      => $this->labels[$term_group],
                        'position'   => (int) $this->positions[$term_group],
                        'terms'      => $terms,
                        'is_parent'  => $tg_group->is_parent,
                    );
                    if ($tg_group->is_parent) {
                        $result[$this->positions[$term_group]]['children'] = $this->get_children($term_group);
                    }
                }
            }
            /**
             * The position should determine the order.
             */
            ksort($result);
            return $result;
        }

        /**
         * returns all tag groups with the position as keys and an array of group properties as values
         * including unassigned
         *
         * @param  void
         * @return array
         */
        public function get_all_with_position_as_key($include_parents = false)
        {

            $this->conditionally_load();
            $result = array();
            foreach ($this->group_ids as $group_id) {
                if (isset($this->positions[$group_id]) && isset($this->labels[$group_id])) {
                    if (TagGroups_Utilities::is_premium_plan() && !$include_parents && in_array($group_id, $this->parents)) {
                        continue;
                    }

                    $result[$this->positions[$group_id]] = array(
                        'term_group' => (int) $group_id,
                        'label'      => $this->labels[$group_id],
                        'position'   => (int) $this->positions[$group_id],
                    );
                }
            }
            /**
             * The position should determine the order.
             * Don't use ksort() because it reindexes the
             */
            uksort($result, function ($a, $b) {
                if ($a > $b) {
                    return 1;
                }
                if ($a < $b) {
                    return -1;
                }
                return 0;
            });
            return $result;
        }

        /**
         * returns all tag groups with the term_group as keys and labels as values
         * sorted by position
         *
         * @param  void
         * @return array
         */
        public function get_all_term_group_label()
        {
            $this->conditionally_load();
            $result = array();
            $positions_flipped = array_flip($this->positions);
            ksort($positions_flipped);
            foreach ($positions_flipped as $term_group) {
                $result[$term_group] = $this->labels[$term_group];
            }
            return $result;
        }

        /**
         * returns all tag group ids (including ID 0)
         * sorted by position
         *
         * @param  void
         * @return array
         */
        public function get_group_ids_by_position($include_parents = false)
        {
            $this->conditionally_load();
            $result = array();
            $position_flipped = array_flip($this->positions);
            ksort($position_flipped);
            foreach ($position_flipped as $group_id) {
                if (!$include_parents && in_array($group_id, $this->parents)) {
                    continue;
                }
                $result[] = $group_id;
            }
            return $result;
        }

        /**
         * returns all labels
         * sorted by position
         *
         * @param  void
         * @return array
         */
        public function get_all_labels_by_position()
        {
            $this->conditionally_load();
            $result = array();
            $positions = $this->positions;
            asort($positions);
            $positions_keys = array_keys($positions);
            foreach ($positions_keys as $term_group) {
                $result[] = $this->labels[$term_group];
            }
            return $result;
        }

        /**
         * getter for $labels
         *
         * @param  void
         * @return string[]
         */
        public function get_labels()
        {
            $this->conditionally_load();
            return $this->labels;
        }

        /**
         * setter for $labels
         *
         * @param  array  $labels
         * @return object $this
         */
        public function set_labels($labels)
        {
            $this->conditionally_load();
            $this->labels = $labels;
            return $this;
        }

        /**
         * getter for $positions
         *
         * @param  void
         * @return int[]
         */
        public function get_positions()
        {
            $this->conditionally_load();
            return $this->positions;
        }

        /**
         * setter for $positions
         *
         * @param  array  $positions
         * @return object $this
         */
        public function set_positions($positions)
        {
            $this->conditionally_load();
            $this->positions = $positions;
            return $this;
        }

        /**
         * getter for $parents
         *
         * @param  void
         * @return int[]
         */
        public function get_parents()
        {

            if (TagGroups_Utilities::is_premium_plan()) {
                $this->conditionally_load();
                return $this->parents;
            }

            return array();
        }

        /**
         * setter for $parents
         *
         * @param  array  $parents
         * @return object $this
         */
        public function set_parents($parents)
        {
            $this->conditionally_load();
            $this->parents = $parents;
            return $this;
        }

        /**
         * Returns all IDs of groups that are children of a given parent group
         *
         * @param int $parent
         * @return array
         */
        public function get_children($parent)
        {

            if (TagGroups_Utilities::is_premium_plan()) {
                $this->conditionally_load();
                if (
                    !in_array($parent, $this->group_ids)
                    || !in_array($parent, $this->parents)
                    || !isset($this->positions[$parent])
                ) {
                    return array();
                }

                $parent_position = $this->positions[$parent];
                $positions_flipped = array_flip($this->positions);
                $children = array();

                for ($i = $parent_position + 1; $i < count($positions_flipped); $i++) {
                    if (in_array($positions_flipped[$i], $this->parents)) {
                        break;
                    }

                    $children[] = $positions_flipped[$i];
                }

                return $children;
            }

            /**
             * otherwise simply remove the parent IDs
             */
            return array_diff($this->group_ids, $this->parents);
        }

        /**
         * Deletes all groups
         *
         * @param  void
         * @return void
         */
        public function reset_groups()
        {
            $this->conditionally_load();

            $tag_group_role_edit_groups = 'edit_pages';
            if (TagGroups_Utilities::is_premium_plan()) {
                $tag_group_role_edit_groups = TagGroups_Options::get_option('tag_group_role_edit_groups', 'edit_pages');
            }
            if (!current_user_can($tag_group_role_edit_groups)) {
                return false;
            }
            $this->group_ids = array();
            $this->positions = array();
            $this->labels = array();
            $this->delete_labels_languages();
            $this->unassign_all_terms();
            $this->add_not_assigned();
            $this->save();
            TagGroups_Cron::schedule_in_secs(5, 'tag_groups_clear_tag_groups_group_terms');
            TagGroups_Transients::delete_all_transients_and_log();
            return true;
        }

        /**
         * Deletes all labels for all languages
         *
         * @param  void
         * @return void
         */
        public function delete_labels_languages()
        {
            delete_option('term_group_labels');
            $tag_group_group_languages = TagGroups_Options::get_option('tag_group_group_languages', array());
            if (isset($tag_group_group_languages)) {
                foreach ($tag_group_group_languages as $language) {
                    delete_option('term_group_labels_' . $language);
                }
            }
            delete_option('tag_group_group_languages');
        }

        /**
         * Remove "holes" in position array
         *
         * This method assumes that element 0 for unassigned terms is set
         *
         * @param  void
         * @return object
         */
        public function reindex_positions()
        {
            $this->conditionally_load();
            $positions_flipped = array_flip($this->positions);
            // result: position => id
            ksort($positions_flipped);
            // re-index
            $positions_flipped = array_values($positions_flipped);
            $this->positions = array_flip($positions_flipped);
            return $this;
        }

        /**
         * Sorts the groups (positions) by alphabetical order, while keeping the parent-child relationship
         *
         * @param  void
         * @return void
         */
        public function sort($order = 'up')
        {

            $this->conditionally_load();

            if (empty($this->parents)) {
                $group_ids = $this->group_ids;
                // remove unassigned
                unset($group_ids[0]);
                usort($group_ids, array( $this, 'sort_by_label' ));
                if ('down' == $order) {
                    $group_ids = array_reverse($group_ids);
                }
                // add back unassigned
                array_unshift($group_ids, 0);
                $this->positions = array_flip($group_ids);
            } else {
                if (TagGroups_Utilities::is_premium_plan()) {
                    /**
                     * group by parents
                     */
                    $children_by_parents = $this->create_array_children_by_parents();
                    $sorted_ids = array();
                    foreach ($children_by_parents as $parent_id => $bunch) {
                        if (!in_array($parent_id, $sorted_ids) && 0 != $parent_id) {
                            $sorted_ids[] = $parent_id;
                        }
                        if (0 == $parent_id) {
                            // remove unassigned
                            unset($bunch[0]);
                        }
                        usort($bunch, array( $this, 'sort_by_label' ));
                        if ('down' == $order) {
                            $bunch = array_reverse($bunch);
                        }
                        if (0 == $parent_id) {
                            // add back unassigned
                            array_unshift($bunch, 0);
                        }
                        $sorted_ids = array_merge($sorted_ids, $bunch);
                    }
                    $this->positions = array_flip($sorted_ids);
                }
            }

            return $this;
        }

        /**
         * return a 2-dimensional-array with children groups sorted as arrays under their parents as keys
         *
         * @return array
         */
        public function create_array_children_by_parents()
        {

            $this->conditionally_load();
            if (TagGroups_Utilities::is_premium_plan()) {
                $groups_of_children = array();
                $current_parent = 0;
                $groups_by_position = array_flip($this->positions);

                foreach ($groups_by_position as $group_id) {
                    if (in_array($group_id, $this->parents)) {
                        $current_parent = $group_id;
                        $groups_of_children[$group_id] = array();
                        continue;
                    }

                    $groups_of_children[$current_parent][] = $group_id;
                }

                return $groups_of_children;
            }

            return array(
                0 => $this->group_ids,
            );
        }

        /**
         * Sorts by group label
         *
         * @param  int       $a
         * @param  int       $b
         * @return boolean
         */
        public function sort_by_label($a, $b)
        {
            $labels = TagGroups_Options::get_option($this->get_tag_group_label_option_name(), array());
            return strnatcmp($labels[$a], $labels[$b]);
        }

        /**
         * Check for WPML and use the correct option name
         *
         * @param  void
         * @return string
         */
        public function get_tag_group_label_option_name()
        {
            if (!TagGroups_WPML::get_current_language() || !$this->is_wpml_translated_language()) {
                return 'term_group_labels';
            }

            if ('all' == TagGroups_WPML::get_current_language()) {
                $language = (string) apply_filters('wpml_default_language', null);
            } else {
                $language = (string) TagGroups_WPML::get_current_language();
            }

            /**
             * Make sure we can delete this option during uninstallation
             */
            $tag_group_group_languages = TagGroups_Options::get_option('tag_group_group_languages', array());
            if (!is_array($tag_group_group_languages)) {
                // preventing value being a string, see ticket #1707, maybe an isolated case
                $tag_group_group_languages = array();
            }

            if (!in_array($language, $tag_group_group_languages)) {
                $tag_group_group_languages[] = $language;
                TagGroups_Options::update_option('tag_group_group_languages', $tag_group_group_languages);
            }

            return 'term_group_labels_' . $language;
        }

        /**
         * Returns true if WPML is installed and we are not using the default language.
         *
         * @param  void
         * @return boolean
         */
        public function is_wpml_translated_language()
        {
            $current_language = TagGroups_WPML::get_current_language();
            if (!$current_language) {
                return false;
            }
            $default_language = apply_filters('wpml_default_language', null);
            /**
             * workaround for Polylang
             */
            if (empty($default_language) && function_exists('pll_default_language')) {
                $default_language = pll_default_language();
            }
            if ($default_language === $current_language) {
                return false;
            }
            return true;
        }

        /**
         * Gets the current language, considers Polylang
         *
         * @deprecated
         * @param  void
         * @return string|boolean
         */
        public static function get_current_language()
        {
            TagGroups_Error::deprecated();
            return TagGroups_WPML::get_current_language();
        }

        /**
         * Add default groups to a new tag
         *
         * @since 1.26.0
         *
         * @param  int    $term_id     ID of the tag that has been created
         * @param  int    $taxonomy_id ID of the taxonomy of this tag
         * @return void
         */
        public function assign_default_groups($term_id)
        {
        }

        /**
         * Sort the groups according to the positions
         *
         * @deprecated 1.40.2
         * @return array
         */
        public function get_sorted_groups($groups)
        {
            $groups_sorted = array();
            foreach ($this->get_positions() as $group => $position) {
                if (array_search($group, $groups) !== false) {
                    $groups_sorted[] = $group;
                }
            }
            return $groups_sorted;
        }

        /**
         * Load format from old structure
         *
         * @return void
         */
        public function load_old_format()
        {
            $term_groups_position = TagGroups_Options::get_option('tag_group_ids', array());
            // position -> id
            $labels_position = TagGroups_Options::get_option('tag_group_labels', array());
            // position -> label
            $this->positions = array_flip($term_groups_position);
            $this->group_ids = array_keys($this->positions);
            /**
             * sort and use new keys
             */
            sort($this->group_ids);
            $this->labels = array();
            foreach ($term_groups_position as $position => $id) {
                $this->labels[$id] = $labels_position[$position];
            }
            ksort($this->positions);
            ksort($this->labels);
        }

        /**
         * Recreate group IDs from positions
         *
         * @return void
         */
        public function recreate_ids_from_positions()
        {
            $this->reindex_positions();
            // recreate $this->group_ids from positions
            $this->group_ids = array_keys($this->positions);
            sort($this->group_ids);
            TagGroups_Options::update_option('term_groups', $this->group_ids);
        }

        /**
         * Recreate the labels, considering translations
         *
         * @return void
         */
        public function fix_labels()
        {
            foreach ($this->labels as $group_id => $label) {
                if (!in_array($group_id, $this->group_ids)) {
                    unset($this->labels[$group_id]);
                }
            }
            TagGroups_Options::update_option('term_group_labels', $this->labels);
            $tag_group_group_languages = TagGroups_Options::get_option('tag_group_group_languages', array());
            if (!isset($tag_group_group_languages) || !is_array($tag_group_group_languages)) {
                return;
            }
            foreach ($tag_group_group_languages as $language) {
                $translated_labels = TagGroups_Options::get_option('term_group_labels_' . $language);
                if (count($this->group_ids) >= count($translated_labels)) {
                    continue;
                }
                foreach ($translated_labels as $group_id => $label) {
                    if (!in_array($group_id, $this->group_ids)) {
                        unset($translated_labels[$group_id]);
                    }
                }
                TagGroups_Options::update_option('term_group_labels_' . $language, $translated_labels);
            }
        }

        /**
         * Replace IDs of parent groups by IDs of their children, keeping IDs in the resulting array unique
         *
         * @param int[] $group_ids
         * @return int[]
         */
        public function expand_parents($group_ids)
        {

            $this->conditionally_load();
            if (empty($this->parents)) {
                return $group_ids;
            }
            if (TagGroups_Utilities::is_premium_plan()) {
                $expanded_group_ids = array();

                foreach ($group_ids as $group_id) {
                    if (in_array($group_id, $expanded_group_ids)) {
                        continue;
                    }

                    if (in_array($group_id, $this->parents)) {
                        $children = $this->get_children($group_id);
                        $expanded_group_ids = array_merge($expanded_group_ids, $children);
                    } else {
                        $expanded_group_ids[] = $group_id;
                    }
                }

                return $expanded_group_ids;
            }

            /**
             * otherwise simply remove the parent IDs
             */
            return array_diff($group_ids, $this->parents);
        }

        /**
         * Tests whether all available groups are parents
         *
         * @return boolean
         */
        public function is_only_parents()
        {

            $this->conditionally_load();
            if (TagGroups_Utilities::is_premium_plan()) {
                $groups_diff = array_diff($this->group_ids, $this->parents);
                return empty($groups_diff) || array( 0 ) == $groups_diff;
            }

            return false;
        }
    }
}
