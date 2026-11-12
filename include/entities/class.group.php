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

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps, WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize, WordPress.DB.SlowDBQuery
defined('ABSPATH') || exit;

if (!class_exists('TagGroups_Group')) {
    class TagGroups_Group
    {
        /**
         * term_group id
         *
         * @var int
         */
        private $group_id ;
        /**
         * object of groups
         *
         * @var object
         */
        private $tg_groups ;
        /**
         * @var int
         */
        private $position ;
        /**
         *
         * @var string
         */
        private $label ;
        /**
         *
         * @var string
         */
        private $parent ;
        /**
         *
         * @var boolean
         */
        public $is_parent ;
        /**
         * instance of the premium part
         *
         * @var object
         */
        private $tg_group_premium ;
        /**
         * last error
         *
         * @var string
         */
        public $error ;
        public const  WRONG_ID = 'wrong ID' ;
        public const  WRONG_POSITION = 'wrong position' ;
        /**
         * Constructor
         *
         * @param  int    $group_id optional term_group
         * @return return type
         */
        public function __construct($group_id = null)
        {
            global  $tag_group_groups ;
            $this->tg_groups = $tag_group_groups;
            $this->is_parent = false;
            if (isset($group_id)) {
                $this->set_group_id($group_id);
            }
            $this->load();
            if (TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_Group')) {
                $this->tg_group_premium = new TagGroups_Premium_Group($this->group_id);
            }

            return $this;
        }

        /**
         * Load data from database
         *
         * @param  int    $group_id optional term_group
         * @return return type
         */
        public function load()
        {

            $labels = $this->tg_groups->get_labels();

            if (isset($labels[$this->group_id])) {
                $this->label = $labels[$this->group_id];
            } else {
                $this->label = '';
            }

            $positions = $this->tg_groups->get_positions();

            if (isset($positions[$this->group_id])) {
                $this->position = $positions[$this->group_id];
            } else {
                $this->position = 1;
            }

            if (TagGroups_Utilities::is_premium_plan()) {
                $parents = $this->tg_groups->get_parents();
                $this->is_parent = is_array($parents) && in_array($this->group_id, $parents);
            }

            return $this;
        }

        /**
         * checks whether this group exists, identified by its ID
         *
         *
         * @param  void
         * @return boolean
         */
        public function exists()
        {
            if (0 == $this->group_id) {
                return true;
            }
            return isset($this->group_id) && in_array($this->group_id, $this->tg_groups->get_group_ids());
        }

        /**
         * Saves this group to the database
         *
         *
         * @param  void
         * @return object $this
         */
        public function save()
        {


            if (empty($this->group_id)) {
                $this->error = TagGroups_Group::WRONG_ID;
                return $this;
            }

            $labels = $this->tg_groups->get_labels();
            $positions = $this->tg_groups->get_positions();
            $labels[$this->group_id] = $this->label;
            $positions[$this->group_id] = $this->position;
            $this->tg_groups->set_labels($labels);
            $this->tg_groups->set_positions($positions);
            if (TagGroups_Utilities::is_premium_plan() && $this->is_parent) {
                $parents = $this->tg_groups->get_parents();
                if (!in_array($this->group_id, $parents)) {
                    $parents[] = $this->group_id;
                    $this->tg_groups->set_parents($parents);
                }
            }

            $this->tg_groups->save();
            return $this;
        }

        /**
         * getter for the term_group value
         *
         *
         * @param  void
         * @return int    term_group
         */
        public function get_group_id()
        {
            if (!isset($this->group_id)) {
                return null;
            }
            return $this->group_id;
        }

        /**
         * setter for the term_group value
         *
         * @param  int    $group_id
         * @return object $this
         */
        public function set_group_id($group_id)
        {

            $this->group_id = (int) $group_id;
            if (TagGroups_Utilities::is_premium_plan() && isset($this->tg_group_premium)) {
                $this->tg_group_premium->set_group_id((int) $group_id);
            }

            return $this;
        }

        /**
         * adds a new group and saves it
         *
         * @param  string   $label    label of the new group
         * @param  int      $position position of the new group; null means after last
         * @return object
         */
        public function create($label, $position = null, $is_parent = false)
        {
            $this->set_group_id($this->tg_groups->get_max_term_group() + 1);
            $this->label = $label;
            $this->set_position($this->tg_groups->get_max_position() + 1);
            $this->tg_groups->reindex_positions()->add_group($this);
            if (!empty($position) && $position != $this->position) {
                $this->move_to_position($position);
            }
            $this->is_parent = $is_parent;
            $this->save();
            if (!empty($this->tg_groups->error)) {
                $this->error = $this->tg_groups->error;
            }
            return $this;
        }

        /**
         * returns all terms that are associated with this term group
         *
         * @param  string|array    $taxonomy   See get_terms
         * @param  string          $hide_empty See get_terms
         * @param  string          $fields     See get_terms
         * @param  string          $post_id    Possibly required to determine the language
         * @param  string          $orderby    See get_terms
         * @param  string          $order      See get_terms
         * @return array|integer
         */
        public function get_group_terms(
            $taxonomy = 'post_tag',
            $hide_empty = false,
            $fields = 'all',
            $post_id = 0,
            $orderby = 'name',
            $order = 'ASC'
        ) {
            global  $tag_group_terms ;
            if (!isset($this->group_id)) {
                return array();
            }
            $orderby = is_string($orderby) ? strtolower($orderby) : 'name';
            /**
             * Remove invalid taxonomies
             */
            $taxonomy = TagGroups_Taxonomy::remove_invalid($taxonomy);
            /**
             * In case we use the Polylang plugin: get the terms for the language of that post.
             * Polylang needs extra query parameter
             */

            if (function_exists('pll_get_post_language') && $post_id) {
                /**
                 * Better sanitize what we get from other plugins
                 */
                $pll_post_language = sanitize_text_field(pll_get_post_language($post_id, 'locale'));
            } else {
                $pll_post_language = '';
            }

            /**
             * In case we use the WPML plugin: consider the language
             */
            $current_language = TagGroups_WPML::get_current_language();

            if ($current_language) {
                $wpml_language = (string) ICL_LANGUAGE_CODE;
            } else {
                $wpml_language = '';
            }

            /**
             * try to get cached version
             *
             * We need to supply the language parameters
             */

            if ("count" == $fields) {
                $cache_key = md5('count-' . serialize($taxonomy) . '-' . serialize($hide_empty) . '-ids-' . $pll_post_language . '-' . $wpml_language);
                $count_transient_name = TagGroups_WPML::get_tag_groups_group_terms_transient_name() . '-' . $cache_key;
                $count_transient_value = TagGroups_Transients::get_transient($count_transient_name);
                if (false !== $count_transient_value && isset($count_transient_value[$this->group_id])) {
                    return $count_transient_value[$this->group_id];
                }
            } else {
                $cache_key = md5($this->group_id . '-' . serialize($taxonomy) . '-' . serialize($hide_empty) . '-' . (is_string($fields) ? strtolower($fields) : 'all') . '-' . $orderby . '-' . (is_string($order) ? strtolower($order) : 'asc') . '-' . $pll_post_language . '-' . $wpml_language);
                $transient_name = TagGroups_WPML::get_tag_groups_group_terms_transient_name() . '-' . $cache_key;
                $transient_value = TagGroups_Transients::get_transient($transient_name);
                if (false !== $transient_value) {
                    return $transient_value;
                }
            }

            $orderby_query = $orderby;
            if ('natural' == $orderby) {
                $orderby_query = 'name';
            }
            $default_orderby = array(
                'name',
                'slug',
                'term_group',
                'term_id',
                'id',
                'description',
                'parent',
                'term_order',
                'count'
            );

            if (!in_array($orderby_query, $default_orderby)) {
                // order by a custom meta field
                $additional_args = array(
                    'meta_key'     => $orderby_query,
                    'meta_compare' => 'NUMERIC',
                );
                $orderby_query = 'meta_value_num';
            }


            if (0 == $this->group_id) {
                $args = array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => $hide_empty,
                    'fields'     => $fields,
                    'orderby'    => $orderby_query,
                    'order'      => $order,
                    'meta_query' => array(
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
                ),
                );
            } else {
                $args = array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => $hide_empty,
                    'fields'     => $fields,
                    'orderby'    => $orderby_query,
                    'order'      => $order,
                    'meta_query' => array( array(
                    'key'     => '_cm_term_group_array',
                    'value'   => ',' . $this->group_id . ',',
                    'compare' => 'LIKE',
                    ) ),
                );
            }

            if (isset($additional_args)) {
                $args = array_merge($args, $additional_args);
            }
            /**
             * Add Polylang query parameter
             */
            if (!empty($pll_post_language)) {
                $args['lang'] = $pll_post_language;
            }
            /**
             * term_order requires special treatment
             */
            if ('term_order' == $orderby_query) {
                add_filter(
                    'get_terms_orderby',
                    array( $tag_group_terms, 'enable_terms_order' ),
                    10,
                    2
                );
            }
            $terms = get_terms($args);
            if (!empty($terms)) {
                if ('natural' == $orderby) {
                    $terms = $tag_group_terms->natural_sorting($terms, $order);
                }
            }
            /**
             * Filters the terms of a group before it's returned or saved to the transient
             *
             * @param WP_Term[]|int[]|string[]|string|WP_Error $terms Return type depends on $fields
             * @param int $this->group_id ID of this group
             * @param string|string[] $taxonomy
             * @param bool|int $hide_empty Whether to hide terms with post count zero
             * @param string $fields What to return. See WP's get_terms()
             * @param int $post_id This parameter is only relevant if the tags depend on the language of a post
             * @param string $orderby
             * @param string $order
             * @param string include
             * @param string exclude
             * @param int threshold
             *
             * @return mixed $terms Must be the same type as the input $terms
             */
            $terms = apply_filters(
                'tag_groups_get_terms',
                $terms,
                $this->group_id,
                $taxonomy,
                $hide_empty,
                $fields,
                $post_id,
                $orderby,
                $order,
                '',
                '',
                0
            );

            if ("count" == $fields) {
                if (!is_array($count_transient_value)) {
                    $count_transient_value = array();
                }
                $count_transient_value[$this->group_id] = $terms;
                TagGroups_Transients::set_transient($count_transient_name, $count_transient_value, 1 * HOUR_IN_SECONDS);
            } else {
                TagGroups_Transients::set_transient($transient_name, $terms, 1 * HOUR_IN_SECONDS);
            }

            return $terms;
        }

        /**
         * adds terms to this group
         *
         * @param  array  $term_ids one-dimensional array of term IDs or WP terms
         * @return object $this
         */
        public function add_terms($term_ids)
        {
            if (!is_int($this->group_id)) {
                return $this;
            }
            foreach ($term_ids as $term_id) {
                $tg_term = new TagGroups_Term($term_id);
                $tg_term->add_group($this->group_id);
                $tg_term->save();
            }
            return $this;
        }

        /**
         * removes terms from this group
         *
         * @param  array  $term_ids one-dimensional array of term IDs
         * @return object $this
         */
        public function remove_terms($term_ids = array())
        {
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            if (empty($term_ids)) {
                $term_ids = $this->get_group_terms($enabled_taxonomies, false, 'ids');
            }
            foreach ($term_ids as $term_id) {
                $tg_term = new TagGroups_Term($term_id);
                $tg_term->remove_group($this)->save();
            }
            return $this;
        }

        /**
         * deletes this group
         *
         * @param  int    $group_id ID of this group
         * @return object $this
         */
        public function delete()
        {

            $group_ids = $this->tg_groups->get_group_ids();

            if (($key = array_search($this->group_id, $group_ids)) === false) {
                $this->error = TagGroups_Group::WRONG_ID;
                return $this;
            }

            $labels = $this->tg_groups->get_labels();
            $positions = $this->tg_groups->get_positions();
            unset($group_ids[$key]);
            unset($labels[$this->group_id]);
            // remove labels of translations
            $tag_group_group_languages = TagGroups_Options::get_option('tag_group_group_languages', array());
            if (!empty($tag_group_group_languages)) {
                foreach ($tag_group_group_languages as $language) {
                    $translated_labels = TagGroups_Options::get_option('term_group_labels_' . $language, array());

                    if (is_array($translated_labels)) {
                        unset($translated_labels[$this->group_id]);
                        TagGroups_Options::update_option('term_group_labels_' . $language, $translated_labels);
                    }
                }
            }
            unset($positions[$this->group_id]);
            $this->tg_groups->set_labels($labels);
            $this->tg_groups->set_positions($positions);
            $this->tg_groups->set_group_ids($group_ids);
            $this->tg_groups->reindex_positions();
            $this->tg_groups->save();
            if (!empty($this->tg_groups->error)) {
                $this->error = $this->tg_groups->error;
            }
            $this->remove_terms();
            do_action('tag_groups_term_group_deleted', $this->group_id);
            unset($this->group_id);
            return $this;
        }

        /**
         * returns the position of this group
         *
         * @param  void
         * @return int|boolean
         */
        public function get_position()
        {
            return $this->position;
        }

        /**
         * sets the position of this group
         *
         * @param  int    $position position of this group
         * @return object $this
         */
        public function set_position($position)
        {

            if ($position < 1 || $position > $this->tg_groups->get_max_position() + 1) {
                $this->error = TagGroups_Group::WRONG_POSITION;
                return $this;
            }

            $this->position = $position;
            return $this;
        }

        /**
         * sets the position of this group
         *
         * @param  int    $position position of this group
         * @return object $this
         */
        public function move_to_position($new_position)
        {

            if (empty($this->group_id)) {
                $this->error = TagGroups_Group::WRONG_ID;
                return $this;
            }


            if ($new_position < 1 || $new_position > $this->tg_groups->get_max_position() + 1) {
                $this->error = TagGroups_Group::WRONG_POSITION;
                return $this;
            }

            $old_position = $this->get_position();
            $positions = $this->tg_groups->get_positions();
            /**
             * 1. move down on old position
             */
            foreach ($positions as $key => $value) {
                if ($value > $old_position) {
                    $positions[$key] = $value - 1;
                }
            }
            /**
             * 2. make space at new position
             */
            foreach ($positions as $key => $value) {
                if ($value >= $new_position) {
                    $positions[$key] = $value + 1;
                }
            }
            /**
             * 3. Insert
             */
            $positions[$this->group_id] = $new_position;
            $this->tg_groups->set_positions($positions);
            $this->position = $new_position;
            $this->tg_groups->reindex_positions();
            return $this;
        }

        /**
         * returns the label of this group
         *
         * @param  void
         * @return string|boolean
         */
        public function get_label()
        {
            if (!isset($this->group_id)) {
                // allow also "not assigned"
                return false;
            }
            return $this->label;
        }

        /**
         * sets the label of this group
         *
         * @param  string $label  label of this group
         * @return object $this
         */
        public function set_label($label)
        {

            if (empty($this->group_id)) {
                $this->error = TagGroups_Group::WRONG_ID;
                return $this;
            }

            $this->label = $label;
            return $this;
        }

        /**
         * returns the number of terms associated with this group
         *
         * @param  void
         * @return int
         */
        public function get_number_of_terms($taxonomies)
        {
            if (!isset($this->group_id)) {
                return false;
            }
            if (!is_array($taxonomies)) {
                $taxonomies = array( $taxonomies );
            }
            /**
             * Consider only taxonomies that
             * 1. are among $tag_group_taxonomies
             * 2. actually exist
             */
            $taxonomies = TagGroups_Taxonomy::remove_invalid($taxonomies);
            return $this->get_group_terms($taxonomies, false, 'count');
        }

        /**
         * sets $this->group_id by label
         *
         * @param  string        $label
         * @return boolean|int
         */
        public function find_by_label($label)
        {
            $labels = $this->tg_groups->get_labels();

            if (in_array($label, $labels)) {
                $this->set_group_id(array_search($label, $labels));
                $this->load();
                return $this;
            } else {
                return false;
            }
        }

        /**
         * sets $this->group_id by position
         *
         *
         * @param  int           $position
         * @return boolean|int
         */
        public function find_by_position($position)
        {
            $positions = $this->tg_groups->get_positions();

            if (in_array($position, $positions)) {
                $this->set_group_id(array_search($position, $positions));
                $this->load();
                return $this;
            } else {
                $this->set_group_id(0);
                return false;
            }
        }

        /**
         * returns an array of group properties as values
         *
         * @param  void
         * @return array
         */
        public function get_info(
            $taxonomy = null,
            $hide_empty = false,
            $fields = null,
            $orderby = 'name',
            $order = 'ASC'
        ) {
            // dealing with NULL values
            if (empty($fields)) {
                $fields = 'ids';
            }
            if (empty($taxonomy)) {
                $taxonomy = TagGroups_Taxonomy::get_enabled_taxonomies();
            }
            if (!isset($hide_empty) || empty($hide_empty)) {
                $hide_empty = false;
            }
            $terms = $this->get_group_terms(
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

            return array(
                'term_group' => (int) $this->group_id,
                'label'      => $this->label,
                'position'   => (int) $this->position,
                'terms'      => $terms,
            );
        }

        /**
         * Returns pieces for for terms_clauses(), enabled for multiple groups
         *
         * @param  int     $group_id
         * @return array
         */
        public function terms_clauses()
        {
            global $tag_group_groups ;

            if (0 == $this->group_id) {
                /**
                 * We are searching for unassigned terms.
                 */
                $meta_query_args = array(
                    'relation' => 'OR',
                    array(
                    'key'     => '_cm_term_group_array',
                    'value'   => ',0,',
                    'compare' => '=',
                ),
                    array(
                    'key'     => '_cm_term_group_array',
                    'compare' => 'NOT EXISTS',
                ),
                );
            } else {
                /**
                 * We are searching for terms assigned to a group with $this->group_id.
                 */
                $group_ids = array( $this->group_id );

                if (count($group_ids) > 1) {
                    $meta_query_args = array(
                        'relation' => 'OR',
                    );
                    foreach ($group_ids as $group_id) {
                        $meta_query_args[] = array(
                            'key'     => '_cm_term_group_array',
                            'value'   => ',' . $group_id . ',',
                            'compare' => 'LIKE',
                        );
                    }
                } else {
                    $meta_query_args = array(
                        // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                        // 'relation' => 'OR',
                        array(
                            'key'     => '_cm_term_group_array',
                            'value'   => ',' . $group_ids[0] . ',',
                            'compare' => 'LIKE',
                        ),
                    );
                }
            }

            /**
             * Convert the arguments to SQL pieces
             */
            $meta_query = new WP_Meta_Query($meta_query_args);
            return $meta_query->get_sql('term', 't', 'term_id');
        }

        /**
         * removes terms that have post count == 0
         *
         * @param  array        $terms
         * @param  array|string $taxonomy
         * @param  string       $fields
         * @return array
         */
        public function remove_empty_terms($terms, $taxonomy, $fields)
        {
            $fields = strtolower($fields);
            foreach ($terms as $key => $term) {
                if (is_array($taxonomy)) {
                    $taxonomy = TagGroups_Utilities::get_first_element($taxonomy);
                }
                switch ($fields) {
                    case 'all':
                        $wp_term = $term;
                        break;
                    case 'ids':
                        $wp_term = get_term_by('id', $term, $taxonomy);
                        break;
                    case 'slugs':
                        $wp_term = get_term_by('slug', $term, $taxonomy);
                        break;
                    case 'names':
                        $wp_term = get_term_by('name', $term, $taxonomy);
                        break;
                    default:
                        TagGroups_Error::log('[Tag Groups] Wrong parameter in remove_empty_terms');
                        return $terms;
                        break;
                }

                if (false === $wp_term || is_wp_error($wp_term)) {
                    TagGroups_Error::log('[Tag Groups] Cannot remove empty terms in remove_empty_terms');
                    return $terms;
                }

                $tg_term = new TagGroups_Term($wp_term);
                if (!$tg_term->get_post_count($this->group_id)) {
                    unset($terms[$key]);
                }
            }
            return $terms;
        }

        /**
         * Returns the ID of the parent group or 0 if no parent
         *
         * @return int
         */
        public function get_parent()
        {

            if ($this->is_parent) {
                return 0;
            }
            /**
             * This group's parent is the closest parent that is above
             */
            if ($this->position < 2) {
                return 0;
            }
            if (TagGroups_Utilities::is_premium_plan()) {
                $positions = $this->tg_groups->get_positions();
                $positions_flipped = array_flip($positions);
                $parents = $this->tg_groups->get_parents();

                for ($i = $this->position; $i > 0; $i--) {
                    if (isset($positions_flipped[$i]) && in_array($positions_flipped[$i], $parents)) {
                        return $positions_flipped[$i];
                    }
                }
            }

            return 0;
        }

        /**
         * Returns the label of the parent, if it exists, or a default string
         *
         * @return string
         */
        public function get_parent_label()
        {

            if (TagGroups_Utilities::is_premium_plan()) {
                $parent_id = $this->get_parent();

                if (0 == $parent_id) {
                    return '- none -';
                }

                /*
                 * Use the labels array because group IDs may already be filtered.
                 */
                $labels = $this->tg_groups->get_labels();

                if (isset($labels[$parent_id])) {
                    return $labels[$parent_id];
                }

                return '- none -';
            }

            return '';
        }
    }
}
