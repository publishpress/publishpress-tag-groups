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

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (!class_exists('TagGroups_Term')) {
    class TagGroups_Term
    {
        /**
         * identificator
         *
         * @var int
         */
        private $term_id ;
        /**
         * taxonomy, needed for updating
         *
         * @var int
         */
        private $taxonomy ;
        /**
         * name, needed for metabox and dynamic post filter
         *
         * @var string
         */
        private $name ;
        /**
         * array of groups that this term is a member of
         *
         * @var array
         */
        private $groups ;
        /**
         * slug of the term
         *
         * @var array
         */
        private $slug ;
        /**
         * count of the term
         *
         * @var array
         */
        private $count ;
        /**
         * last error
         *
         * @var string
         */
        public $error ;
        /**
         * Constructor
         *
         * @param  int|object $term         term
         * @return object     $this|boolean false if error occured during loading
         */
        public function __construct($term = null, $tg_terms = null)
        {
            
            if (isset($term)) {
                if (is_object($term)) {
                    /**
                     * We can fill the properties directly from the WP term object.
                     */
                    $this->term_id = $term->term_id;
                    $this->taxonomy = $term->taxonomy;
                    $this->name = $term->name;
                    $this->slug = $term->slug;
                    $this->count = $term->count;
                } else {
                    $this->term_id = $term;
                }
                
                $this->groups = array();
                return $this->load();
            }
            
            return $this;
        }
        
        /**
         * Loads relevant data from the database
         *
         * @param  void
         * @return object|boolean $this or false on error
         */
        public function load()
        {
            if (empty($this->term_id)) {
                return $this;
            }
            
            if (empty($this->groups) || empty($this->taxonomy) || empty($this->name) || empty($this->slug)) {
                /**
                 * We need to fill the properties from the WP term object.
                 */
                $tag_groups_hooks = new TagGroups_Hooks();
                /**
                 * Some plugins hook into get_term but forget to forward term_group
                 */
                
                if (!empty($this->taxonomy)) {
                    $tag_groups_hooks->remove_all_filters(array( 'get_term', 'get_' . $this->taxonomy ));
                } else {
                    $tag_groups_hooks->remove_all_filters(array( 'get_term' ));
                }
                
                $term = get_term($this->term_id);
                $tag_groups_hooks->restore_hooks();
                /**
                 * Check if term exists.
                 */
                
                if (is_object($term) && !is_wp_error($term)) {
                    $this->taxonomy = $term->taxonomy;
                    $this->name = $term->name;
                    $this->slug = $term->slug;
                    $this->count = $term->count;
                    // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                    // $this->groups = array( $term->term_group );
                } else {
                    TagGroups_Error::verbose_log('[Tag Groups] Error loading term (ID %d).', $this->term_id);
                }
            }
            
            $groups = get_term_meta($this->term_id, '_cm_term_group_array', true);
            
            if (false === $groups || '' === $groups) {
                // not found
                $this->groups = array( 0 );
            } else {
                $groups_a = explode(',', $groups);
                // remove empty values
                $groups_a = array_filter($groups_a, function ($v) {
                    return '' != $v;
                });
                // must be ints and no funny keys
                $groups_a = array_values(array_map('intval', $groups_a));
                // We return full array even for free plugin, because user might have downgraded after creating multiple groups
                $this->groups = $groups_a;
            }
            
            return $this;
        }
        
        /**
         * Save group-relevant data to the database (We are not saving the name)
         *
         * @param  boolean $override_permission_check Option to override the permission check if we are saving default groups
         * @return object  $this|boolean false in case of error
         */
        public function save($override_permission_check = false)
        {
            
            if (empty($this->term_id)) {
                return $this;
            }
            
            if (!$override_permission_check) {
                /**
                 * Check permissions
                 */
                $tag_group_role_edit_tags = 'edit_pages';
                if (TagGroups_Utilities::is_premium_plan()) {
                    $tag_group_role_edit_tags = TagGroups_Options::get_option('tag_group_role_edit_tags', 'edit_pages');
                }
                
                if (!current_user_can($tag_group_role_edit_tags)) {
                    TagGroups_Error::verbose_log('[Tag Groups] Insufficient permission to save terms');
                    return $this;
                }
            }
            
            /**
             * Remove the "not assigned" element - usually with term_group 0
             * Use a copy of $this->groups.
             * Bring groups in correct order
             */
            $term_groups = $this->get_sorted_groups();
            
            if (count($term_groups) > 1) {
                $index_not_assigned = array_search(0, $term_groups);
                if (false !== $index_not_assigned) {
                    unset($term_groups[$index_not_assigned]);
                }
            }
            
            if (TagGroups_Utilities::is_premium_plan()) {
                $result = update_term_meta($this->term_id, '_cm_term_group_array', ',' . implode(',', $term_groups) . ',');
            } else {
                $first_group = TagGroups_Utilities::get_first_element($term_groups);
                $result = update_term_meta($this->term_id, '_cm_term_group_array', ',' . $first_group . ',');
            }

            if ($result) {
                do_action('tag_groups_groups_of_term_saved', $term_groups, $this->term_id);
            }
            return $this;
        }
        
        /**
         * Checks if this term is assigned to at least one of these groups
         *
         * @param  int|object|array $group (int, object) or groups (array of int)
         * @return boolean
         */
        public function has_group($group)
        {
            
            if (0 === $group) {
                if (empty($this->groups) || array_values($this->groups) == array( 0 )) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $term_groups = $this->make_array($group);
                
                if (count(array_intersect($this->groups, $term_groups))) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        
        /**
         * Checks if this term is assigned to all of these groups
         *
         * @param  int|object|array $group (int, object) or groups (array of int)
         * @return boolean
         */
        public function has_all_groups($group)
        {
            
            if (0 === $group) {
                if (empty($this->groups) || array_values($this->groups) == array( 0 )) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $term_groups = $this->make_array($group);
                /**
                 *  find out which of the submitted groups are not among this term's groups
                 */
                
                if (count(array_diff($term_groups, $this->groups))) {
                    return false;
                } else {
                    return true;
                }
            }
        }
        
        /**
         * Checks if this term is assigned to exactly these groups
         *
         * @param  int|object|array $group (int, object) or groups (array of int)
         * @return boolean
         */
        public function has_exactly_groups($group)
        {
            
            if (0 === $group) {
                if (empty($this->groups) || array_values($this->groups) == array( 0 )) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $term_groups = $this->make_array($group);
                /**
                 * find out which of the submitted groups are not among this term's groups
                 */
                
                if (count(array_diff($term_groups, $this->groups)) || count(array_diff($this->groups, $term_groups))) {
                    return false;
                } else {
                    return true;
                }
            }
        }
        
        /**
         * Getter for $this->groups (values cast to integer)
         *
         * @param
         * @return
         */
        public function get_groups()
        {
            
            if (is_array($this->groups)) {
                return array_values(array_map('intval', $this->groups));
            } else {
                return (int) $this->groups;
            }
        }
        
        /**
         * Setter for $this->groups
         *
         * @param  int|object|array $group  (int, object) or groups (array of int)
         * @return object           $this
         */
        public function set_group($group)
        {
            $this->groups = $this->make_array($group);
            return $this;
        }
        
        /**
         * Adds one or more groups to $this->groups
         *
         * @param  int|object|array $group        (int, object) or groups (array of int)
         * @return object|boolean   $this|false
         */
        public function add_group($group)
        {
            if (!is_array($this->groups)) {
                return $this;
            }
            $group = $this->make_array($group);
            
            if (in_array(0, $group)) {
                $this->groups = array( 0 );
                return $this;
            }
            
            /**
             * Important: New group(s) must come first so that it will be saved in base plugin
             */
            $this->groups = array_merge($group, $this->groups);
            return $this;
        }
        
        /**
         * Remove a group from $this->groups
         *
         * @param  int|object|array $group        (int, object) or groups (array of int)
         * @return object|boolean   $this|false
         */
        public function remove_group($group)
        {
            $this->groups = array_diff($this->groups, $this->make_array($group));
            if (count($this->groups) == 0) {
                $this->groups = array( 0 );
            }
            return $this;
        }
        
        /**
         * Remove all groups from $this->groups
         *
         * @param
         * @return
         */
        public function remove_all_groups()
        {
            $this->groups = array( 0 );
            return $this;
        }
        
        /**
         * Setter for $term_id
         *
         * @param int $term_id
         */
        public function set_term_id($term_id)
        {
            $this->term_id = (int) $term_id;
        }
        
        /**
         * Getter for $term_id
         *
         * @return int
         */
        public function get_term_id()
        {
            return $this->term_id;
        }
        
        /**
         * returns the term's name
         *
         * @param  void
         * @return string
         */
        public function get_name()
        {
            return $this->name;
        }
        
        /**
         * returns the term's taxonomy
         *
         * @param  void
         * @return string
         */
        public function get_taxonomy()
        {
            return $this->taxonomy;
        }
        
        /**
         * returns the term's slug
         *
         * @param  void
         * @return string
         */
        public function get_slug()
        {
            return $this->slug;
        }
        
        /**
         * Makes an array of group ids from an object, an integer or an array
         * includes sanitation
         *
         * @param  object|array|integer
         * @return array                  one-dimensional array of integers (term_group values)
         */
        public function make_array($group)
        {
            
            if (is_object($group)) {
                return array( (int) $group->get_group_id() );
            } elseif (is_array($group)) {
                return array_map('intval', $group);
            } else {
                return array( (int) $group );
            }
        }
        
        /**
         * Returns the post count for the term, considering the group
         *
         * @param  integer   $group_id
         * @return integer
         */
        public function get_post_count($group_id = 0)
        {
            
            if (0 == $group_id) {
                return $this->count;
            }

            if (TagGroups_Utilities::is_premium_plan()) {
                global $tag_group_premium_terms;

                if (empty($tag_group_premium_terms)) {
                    return $this->count;
                }

                $post_counts = $tag_group_premium_terms->get_post_counts();

                if (!isset($post_counts[$this->term_id])) {
                    return $this->count;
                }

                if (!isset($post_counts[$this->term_id][$group_id])) {
                    return 0;
                }

                return $post_counts[$this->term_id][$group_id];
            }
        }
        
        /**
         * Checks whether the term exists as WP term
         *
         * @return boolean
         */
        public function exists()
        {
            if (empty($this->term_id)) {
                return false;
            }
            return (bool) term_exists($this->term_id);
        }
        
        /**
         * Sort the groups according to the positions
         *
         * @return array
         */
        public function get_sorted_groups()
        {
            global  $tag_group_groups ;
            $groups_sorted = array();
            foreach ($tag_group_groups->get_positions() as $group => $position) {
                if (in_array($group, $this->groups)) {
                    $groups_sorted[] = $group;
                }
            }
            return $groups_sorted;
        }
    }
}
