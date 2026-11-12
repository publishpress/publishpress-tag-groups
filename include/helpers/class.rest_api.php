<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 *
 * @since       0.37.0
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (!class_exists('TagGroups_REST_API')) {
    /**
     *   Adds endpoints to the WordPress REST API
     */
    class TagGroups_REST_API
    {
        public function __construct()
        {
        }

        /**
         * Register the REST API endpoints and schemata
         *
         *
         * @param  void
         * @return void
         */
        public static function register_hook()
        {
            if (defined('CM_DEBUG')) {
                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                // development
                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                // error_log( 'register_routes(), nonce: ' . wp_create_nonce( 'wp_rest' ) );
                add_filter('wp_is_application_passwords_available', '__return_true');
            }
            add_action('rest_api_init', array( 'TagGroups_REST_API', 'register_routes' ));
        }

        public static function register_routes()
        {
            global $tag_groups_current_user_id;
            /**
             * Make the current user ID that we retrieved from application password available in permission callbacks
             * (workaround for a suspected bug in 5.6-RC1
             */
            $tag_groups_current_user_id = get_current_user_id();
        
            register_rest_route(
                'tag-groups/v1',
                '/groups/(?P<id>\\d+)', 
                array(
                    array(
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => array('TagGroups_REST_API', 'get_groups'),
                        'args'                => array(
                            'id' => array(
                                'validate_callback' => function ($param, $request, $key) {
                                    return is_numeric($param);
                                },
                            ),
                        ),
                        'schema'              => array('TagGroups_REST_API', 'get_group_schema'),
                        'permission_callback' => function ($request) {
                            return TagGroups_REST_API::current_user_can_access_endoint('groups');
                        },
                    ),
                    array(
                        'methods'             => WP_REST_Server::EDITABLE,
                        'callback'            => array('TagGroups_REST_API', 'edit_group'),
                        'args'                => array(
                            'id' => array(
                                'validate_callback' => function ($param, $request, $key) {
                                    return is_numeric($param);
                                },
                            ),
                        ),
                        'permission_callback' => array('TagGroups_REST_API', 'current_user_can_edit_groups'),
                    ),
                    array(
                        'methods'             => WP_REST_Server::DELETABLE,
                        'callback'            => array('TagGroups_REST_API', 'delete_group'),
                        'args'                => array(
                            'id' => array(
                                'validate_callback' => function ($param, $request, $key) {
                                    return is_numeric($param);
                                },
                            ),
                        ),
                        'permission_callback' => array('TagGroups_REST_API', 'current_user_can_edit_groups'),
                    )
                )
            );
        
            register_rest_route('tag-groups/v1', '/groups/', array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array('TagGroups_REST_API', 'get_groups'),
                'schema'              => array('TagGroups_REST_API', 'get_group_schema'),
                'permission_callback' => function ($request) {
                    return TagGroups_REST_API::current_user_can_access_endoint('groups');
                },
            ));
        
            register_rest_route(
                'tag-groups/v1',
                '/terms/(?P<id>\\d+)', 
                array(
                    array(
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => array('TagGroups_REST_API', 'get_terms'),
                        'args'                => array(
                            'id' => array(
                                'validate_callback' => function ($param, $request, $key) {
                                    return is_numeric($param);
                                },
                            ),
                        ),
                        'schema'              => array('TagGroups_REST_API', 'get_term_schema'),
                        'permission_callback' => function ($request) {
                            return TagGroups_REST_API::current_user_can_access_endoint('terms');
                        },
                    ),
                    array(
                        'methods'             => WP_REST_Server::EDITABLE,
                        'callback'            => array('TagGroups_REST_API', 'edit_term'),
                        'args'                => array(
                            'id' => array(
                                'validate_callback' => function ($param, $request, $key) {
                                    return is_numeric($param);
                                },
                            ),
                        ),
                        'permission_callback' => array('TagGroups_REST_API', 'current_user_can_edit_tags'),
                    )
                )
            );
        
            register_rest_route('tag-groups/v1', '/terms/', array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array('TagGroups_REST_API', 'get_terms'),
                'schema'              => array('TagGroups_REST_API', 'get_term_schema'),
                'permission_callback' => function ($request) {
                    return TagGroups_REST_API::current_user_can_access_endoint('terms');
                },
            ));
        
            register_rest_route(
                'tag-groups/v1',
                '/taxonomies/', 
                array(
                    array(
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => array('TagGroups_REST_API', 'get_taxonomies'),
                        'schema'              => array('TagGroups_REST_API', 'get_taxonomy_schema'),
                        'permission_callback' => '__return_true',
                    ),
                    array(
                        'methods'             => WP_REST_Server::EDITABLE,
                        'callback'            => array('TagGroups_REST_API', 'edit_taxonomies'),
                        'permission_callback' => array('TagGroups_REST_API', 'current_user_can_manage_options'),
                    )
                )
            );

            if (TagGroups_Utilities::is_premium_plan()) {
                register_rest_route(
                    'tag-groups/v1',
                    '/post-tags/(?P<id>\\d+)',
                    array(
                        array(
                            'methods'             => WP_REST_Server::READABLE,
                            'callback'            => array('TagGroups_REST_API', 'get_posts'),
                            'args'                => array(
                                'id' => array(
                                    'validate_callback' => function ($param, $request, $key) {
                                        return is_numeric($param);
                                    },
                                ),
                            ),
                            'schema'              => array('TagGroups_REST_API', 'get_post_schema'),
                            'permission_callback' => '__return_true',
                        ),
                        array(
                            'methods'             => WP_REST_Server::EDITABLE,
                            'callback'            => array('TagGroups_REST_API', 'edit_post'),
                            'args'                => array(
                                'id' => array(
                                    'validate_callback' => function ($param, $request, $key) {
                                        return is_numeric($param);
                                    },
                                ),
                            ),
                            'permission_callback' => array('TagGroups_REST_API', 'current_user_can_manage_post_tags'),
                        ),
                    )
                );

                register_rest_route(
                    'tag-groups/v1',
                    '/post-tags/',
                    array(
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => array('TagGroups_REST_API', 'get_posts'),
                        'schema'              => array('TagGroups_REST_API', 'get_post_schema'),
                        'permission_callback' => '__return_true',
                    )
                );
            }
        }        


        /**
         * Get one or more groups
         *
         * Returns array consisting of items: tag group ID => tag group label or string of tag group label, if id was provided
         *
         * Arguments:
         *   taxonomy    default: post_tag
         *   hide_empty  default: true
         *   fields      for example: ids, all, names; default: all
         *
         *
         * @param  object          $request
         * @return array|object
         */
        public static function get_groups(WP_REST_Request $request)
        {
            global  $tag_group_groups;
            $id = $request->get_param('id');
            // don't sanitize here so that we can detect NULL
            $taxonomy = sanitize_title($request->get_param('taxonomy'));
            $hide_empty = ( $request->get_param('hide_empty') ? true : false );
            $fields = sanitize_title($request->get_param('fields'));
            $orderby = sanitize_title($request->get_param('orderby'));
            $order = sanitize_title($request->get_param('order'));

            if (TagGroups_Utilities::is_premium_plan()) {
                $expand = sanitize_title($request->get_param('expand'));

                if (!empty($expand)) {
                    $expand_a = explode(',', $expand);
                    $expand_a = array_map('intval', $expand_a);
                    $expand_a = $tag_group_groups->expand_parents($expand_a);

                    $groups = $tag_group_groups->get_info_of_all(
                        $taxonomy,
                        $hide_empty,
                        $fields,
                        $orderby,
                        $order
                    );

                    return array_intersect_key($groups, array_flip($expand_a));
                }
            }

            if (isset($id)) {
                $id = (int) $id;
                // particular group
                $tg_group = new TagGroups_Group($id);
                if (!$tg_group->exists()) {
                    return new WP_Error('no_group', 'Invalid group', array(
                        'status' => 404,
                    ));
                }
                return $tg_group->get_info(
                    $taxonomy,
                    $hide_empty,
                    $fields,
                    $orderby,
                    $order
                );
            }

            $groups = $tag_group_groups->get_info_of_all(
                $taxonomy,
                $hide_empty,
                $fields,
                $orderby,
                $order
            );

            if (TagGroups_Utilities::is_premium_plan()) {
                $type = $request->get_param('type');

                if ('metabox' == $type) {
                    $tag_group_meta_box_include = TagGroups_Options::get_option('tag_group_meta_box_include', array());

                    if (!empty($tag_group_meta_box_include)) {
                        foreach ($groups as $key => $group) {
                            if (!in_array($group['term_group'], $tag_group_meta_box_include)) {
                                unset($groups[$key]);
                            }
                        }

                        $groups = array_values($groups);
                    }
                }
            }

            return $groups;
        }

        /**
         * Delete a group
         *
         * @param  object $request
         * @return void
         */
        public static function delete_group(WP_REST_Request $request)
        {
            $id = (int) $request->get_param('id');

            if (0 == $id) {
                return new WP_Error('wrong_id', 'You cannot delete this groups', array(
                    'status' => 400,
                ));
            } else {
                $tg_group = new TagGroups_Group($id);
                if (!$tg_group->exists()) {
                    return new WP_Error('wrong_id', "A group with this ID doesn't exists", array(
                        'status' => 400,
                    ));
                }
                $tg_group->delete();
                if (!empty($tg_group->error)) {
                    return new WP_Error('error', $tg_group->error, array(
                        'status' => 400,
                    ));
                }
            }
        }

        /**
         * Edit a group or create a new group
         *
         * Sets the provided arguments, or creates a new group if ID is zero; in the letter case the label is mandatory
         *
         * Arguments:
         *   label      group name
         *   position   default for new groups: end of list
         *
         *
         * @param  object  $request
         * @return void
         */
        public static function edit_group(WP_REST_Request $request)
        {
            $id = (int) $request->get_param('id');
            $label = sanitize_text_field($request->get_param('label'));
            $position = (int) $request->get_param('position');

            if (0 == $id) {
                // create
                if ('' == $label) {
                    return new WP_Error('empty_label', 'A new group needs a label', array(
                        'status' => 400,
                    ));
                }
                $tg_group_check = new TagGroups_Group();
                if ($tg_group_check->find_by_label($label)) {
                    return new WP_Error('duplicate_label', 'A group with this label already exists', array(
                        'status' => 400,
                    ));
                }
                $tg_group = new TagGroups_Group();
                $tg_group->create($label, $position);
            } else {
                // update
                $tg_group = new TagGroups_Group($id);
                if (!$tg_group->exists()) {
                    return new WP_Error('no_group', 'Group with this ID not found', array(
                        'status' => 400,
                    ));
                }
                if (!empty($label)) {
                    $tg_group->set_label($label);
                }
                if (!empty($position)) {
                    $tg_group->set_position($position);
                }
                $tg_group->save();
            }

            if (!empty($tg_group->error)) {
                return new WP_Error('error', $tg_group->error, array(
                    'status' => 400,
                ));
            }
        }

        /**
         * Get one or more terms
         *
         * Arguments:
         *   taxonomy    default: post_tag
         *   hide_empty  default: true
         *
         *
         * @param  object         $request
         * @return array|object
         */
        public static function get_terms(WP_REST_Request $request)
        {
            global  $tag_group_premium_terms ;
            $term_o = new TagGroups_Term();
            $id = (int) $request->get_param('id');

            if (!empty($id)) {
                $term_o = new TagGroups_Term($id);
                return array(
                    'id'       => $id,
                    'name'     => $term_o->get_name(),
                    'slug'     => $term_o->get_slug(),
                    'taxonomy' => $term_o->get_taxonomy(),
                    'groups'   => $term_o->get_groups(),
                );
            } else {
                $orderby = sanitize_title($request->get_param('orderby'));
                if (empty($orderby)) {
                    $orderby = 'name';
                }
                $order = sanitize_title($request->get_param('order'));
                if (empty($order)) {
                    $order = 'ASC';
                }
                $taxonomy = sanitize_title($request->get_param('taxonomy'));

                if (empty($taxonomy)) {
                    $taxonomy = TagGroups_Taxonomy::get_enabled_taxonomies();
                } elseif ('public' == strtolower($taxonomy)) {
                    $taxonomy = array_values(get_taxonomies(array(
                        'public' => true,
                    ), 'names'));
                }

                $hide_empty = ( $request->get_param('hide_empty') ? true : false );
                $short = ( $request->get_param('short') ? true : false );
                $lang = sanitize_title($request->get_param('lang'));
                if (empty($lang)) {
                    $lang = null;
                }
                $args = array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => $hide_empty,
                    'orderby'    => $orderby,
                    'order'      => $order,
                );
                $group = $request->get_param('group');

                if (isset($group)) {
                    $group = (int) $group;
                    $tg_group = new TagGroups_Group($group);
                    if (!$tg_group->exists()) {
                        return new WP_Error('no_group', 'Invalid group', array(
                            'status' => 404,
                        ));
                    }
                    $group_terms = $tg_group->get_group_terms($taxonomy, $hide_empty, 'ids');
                    if (false === $group_terms) {
                        return new WP_Error('no_terms', 'Invalid terms', array(
                            'status' => 404,
                        ));
                    }
                    $args['include'] = $group_terms;
                }

                $terms = get_terms($args);
                if (class_exists('TagGroups_Premium_Term')) {
                    $post_counts = $tag_group_premium_terms->get_post_counts($lang);
                }
                $result = array();
                foreach ($terms as $term) {
                    if (is_object($term)) {
                        $term_o = new TagGroups_Term($term);
                        $info = array(
                            'id'          => $term->term_id,
                            'name'        => html_entity_decode($term->name),
                            'slug'        => $term->slug,
                            'taxonomy'    => $term->taxonomy,
                            'description' => $term->description,
                        );

                        if (!$short) {
                            $info['groups'] = $term_o->get_groups();

                            if (isset($post_counts) && isset($post_counts[$term->term_id])) {
                                $info['post_count'] = $post_counts[$term->term_id];
                            } else {
                                $info['post_count'] = $term->count;
                            }
                        }

                        $result[] = $info;
                    }
                }
                return $result;
            }
        }

        /**
         * Set the groups of a term
         *
         * Arguments:
         *      groups: comma-separated list of group IDs
         *
         * @param  object $request
         * @return void
         */
        public static function edit_term(WP_REST_Request $request)
        {
            $id = (int) $request->get_param('id');
            $groups = array_map('intval', explode(',', $request->get_param('groups')));

            if (0 == count($groups)) {
                return new WP_Error('no_groups', 'You have to supply a comma-separated list of groups', array(
                    'status' => 400,
                ));
            } else {
                $tg_term = new TagGroups_Term($id);
                if (!$tg_term->exists()) {
                    return new WP_Error('wrong_id', "A term with this ID doesn't exists", array(
                        'status' => 400,
                    ));
                }
                $tg_term->set_group($groups)->save();
                if (!empty($tg_term->error)) {
                    return new WP_Error('error', $tg_term->error, array(
                        'status' => 400,
                    ));
                }
            }
        }

        /**
         * Get taxonomies, enabled for tag groups or for the metabox
         *
         * Arguments:
         *   type    metabox, enabled
         *
         * @param  object         $request
         * @return array|object
         */
        public static function get_taxonomies(WP_REST_Request $request)
        {
            $type = $request->get_param('type');
            $result = array();
            switch ($type) {
                case 'metabox':
                    $taxonomy_slugs = TagGroups_Taxonomy::get_taxonomies_for_metabox();
                    break;
                case 'enabled':
                default:
                    $taxonomy_slugs = TagGroups_Taxonomy::get_enabled_taxonomies();
                    break;
            }
            foreach ($taxonomy_slugs as $taxonomy_slug) {
                $result[] = array(
                    'slug' => $taxonomy_slug,
                    'name' => TagGroups_Taxonomy::get_name_from_slug($taxonomy_slug),
                );
            }
            return $result;
        }

        /**
         * Set taxonomies
         *
         * Arguments:
         *   enabled    comma-separated list of taxonomy slugs
         *   metabox    comma-separated list of taxonomy slugs
         *
         * @param  object         $request
         * @return array|object
         */
        public static function edit_taxonomies(WP_REST_Request $request)
        {
            $enabled = array_map('sanitize_title', explode(',', $request->get_param('enabled')));
            $metabox = array_map('sanitize_title', explode(',', $request->get_param('metabox')));

            if (count($enabled) && $enabled[0]) {
                foreach ($enabled as $taxonomy) {
                    if (!taxonomy_exists($taxonomy)) {
                        return new WP_Error('wrong_taxonomy', sprintf("A taxonomy with the slug %s doesn't exist.", $taxonomy), array(
                            'status' => 400,
                        ));
                    }
                }
                TagGroups_Taxonomy::update_enabled($enabled);
            }


            if (count($metabox) && $metabox[0]) {
                $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
                foreach ($metabox as $taxonomy) {
                    if (!taxonomy_exists($taxonomy)) {
                        return new WP_Error('wrong_taxonomy', sprintf("A taxonomy with the slug %s doesn't exist.", $taxonomy), array(
                            'status' => 400,
                        ));
                    }
                    if (!in_array($taxonomy, $enabled_taxonomies)) {
                        return new WP_Error('wrong_taxonomy', sprintf("The taxonomy with the slug %s isn't enabled for tag groups.", $taxonomy), array(
                            'status' => 400,
                        ));
                    }
                }
                TagGroups_Options::update_option('tag_group_meta_box_taxonomy', $metabox);
            }
        }

        /**
         * Get one or more posts
         *
         * Arguments:
         *      post_type
         *
         * @param WP_REST_Request $request
         * @return array|object
         */
        public static function get_posts(WP_REST_Request $request)
        {
            $id = $request->get_param('id');
            // don't sanitize here so that we can detect NULL
            $post_type = sanitize_title($request->get_param('post_type'));

            if (!empty($id)) {
                $id = (int) $id;
                $post = get_post($id);
                if (!is_object($post)) {
                    return new WP_Error('wrong_post', sprintf("A post with the ID %d doesn't exist.", $id), array(
                        'status' => 400,
                    ));
                }
                /**
                 * check persmission
                 */

                if (get_current_user_id()) {
                    if (!current_user_can('read_post', $id)) {
                        return new WP_Error('permission', "You are not allowed to view this post.", array(
                            'status' => 403,
                        ));
                    }
                } else {
                    if ('' !== $post->post_password || 'publish' !== $post->post_status) {
                        return new WP_Error('permission', "You are not allowed to view this post.", array(
                            'status' => 400,
                        ));
                    }
                }

                $tg_post = new TagGroups_Premium_Post($id);
                return array(
                    'id'    => $id,
                    'terms' => $tg_post->get_terms_by_group(),
                );
            } else {
                $return_data = array();
                $args = array(
                    'numberposts' => -1,
                    'fields'      => 'ids',
                    'post_type'   => $post_type,
                    'post_status' => array(
                    'publish',
                    'pending',
                    'draft',
                    'future',
                    'private'
                ),
                );
                $post_ids = get_posts($args);
                foreach ($post_ids as $post_id) {
                    /**
                     * retrieving objects one by one so that we don't risk running into memory issues
                     */
                    $post = get_post($post_id);
                    /**
                     * check persmission
                     */

                    if (get_current_user_id()) {
                        if (!current_user_can('read_post', $post_id)) {
                            continue;
                        }
                    } else {
                        if ('' !== $post->post_password || 'publish' !== $post->post_status) {
                            continue;
                        }
                    }

                    $tg_post = new TagGroups_Premium_Post($post_id);
                    $return_data[] = array(
                        'id'    => $post_id,
                        'terms' => $tg_post->get_terms_by_group(),
                    );
                }
                return $return_data;
            }
        }

        /**
         * get taxonomies
         *
         * Arguments:
         *  terms   JSON-encoded array of group IDs as keys and an array of term IDs as each value
         *  taxonomy The taxonomy slug
         *
         * @param  object         $request
         * @return array|object
         */
        public static function edit_post(WP_REST_Request $request)
        {
            $id = (int) $request->get_param('id');
            $terms = json_decode($request->get_param('terms'), true);
            if (is_null($terms) || !is_array($terms)) {
                return new WP_Error('wrong_term_data', "The term data has a wrong format.", array(
                    'status' => 400,
                ));
            }
            $taxonomy = sanitize_title($request->get_param('taxonomy'));
            $enabled_taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            if (empty($taxonomy) || !in_array($taxonomy, $enabled_taxonomies)) {
                return new WP_Error('wrong_taxonomy', "Missing or wrong taxonomy.", array(
                    'status' => 400,
                ));
            }
            $post = get_post($id);
            if (!is_object($post)) {
                return new WP_Error('wrong_post', sprintf("A post with the ID %d doesn't exist.", $id), array(
                    'status' => 400,
                ));
            }
            /**
             * check persmission
             */
            $post_type_object = get_post_type_object($post->post_type);
            if (!is_object($post_type_object) || !is_object($post_type_object->cap) || !current_user_can($post_type_object->cap->edit_post, $id)) {
                return new WP_Error('permission', "You are not allowed to edit this post.", array(
                    'status' => 400,
                ));
            }
            $post_terms = array();
            $unassigned_post_terms = array();
            foreach ($terms as $group_id => $term_array) {
                if (!is_int($group_id)) {
                    return new WP_Error('wrong_group_id', sprintf("Wrong group ID %s.", $group_id), array(
                        'status' => 400,
                    ));
                }
                $tg_group = new TagGroups_Group($group_id);
                if (!$tg_group->exists()) {
                    return new WP_Error('wrong_group_id', sprintf("Wrong group ID %s.", $group_id), array(
                        'status' => 400,
                    ));
                }
                foreach ($term_array as $term_id) {
                    if (!is_int($term_id) || !get_term_by('id', $term_id, $taxonomy)) {
                        return new WP_Error('wrong_term_id', sprintf("Wrong term ID %s.", $term_id), array(
                            'status' => 400,
                        ));
                    }
                    $tg_term = new TagGroups_Term($term_id);

                    if (0 == $group_id) {
                        $unassigned_post_terms[] = $term_id;
                        if (!$tg_term->has_exactly_groups(0)) {
                            $tg_term->set_group(0)->save();
                        }
                    } else {
                        $post_terms[] = array(
                            'term_id'    => (int) $term_id,
                            'taxonomy'   => $taxonomy,
                            'term_group' => (int) $group_id,
                        );
                        if (!$tg_term->has_all_groups($group_id)) {
                            $tg_term->add_group($group_id)->save();
                        }
                    }
                }
            }
            $tg_post = new TagGroups_Premium_Post($id);
            $tg_post->set_terms($post_terms, $unassigned_post_terms, 'term_id')->save();
        }

        /**
         *
         */
        public static function get_group_schema()
        {
            $schema = array(
                '$schema'    => 'http://json-schema.org/draft-04/schema#',
                'title'      => 'group',
                'type'       => 'object',
                'properties' => array(
                'term_group' => array(
                'type'     => 'integer',
                'label'    => 'Object ID.',
                'readonly' => true,
                ),
                'label'      => array(
                'description' => 'The object name.',
                'type'        => 'string',
                ),
                'position'   => array(
                'description' => 'The position of the object in lists, menus and tag clouds.',
                'type'        => 'integer',
                ),
                'terms'      => array(
                'description' => 'The terms that are assigned to this group.',
                'type'        => 'array',
                ),
            ),
            );
            return $schema;
        }

        /**
         *
         */
        public static function get_term_schema()
        {
            $schema = array(
                '$schema'    => 'http://json-schema.org/draft-04/schema#',
                'title'      => 'term',
                'type'       => 'object',
                'properties' => array(
                'id'          => array(
                'type'     => 'integer',
                'label'    => 'Object ID.',
                'readonly' => true,
                ),
                'name'        => array(
                'description' => 'The object name.',
                'type'        => 'string',
                ),
                'slug'        => array(
                'description' => 'The term slug.',
                'type'        => 'string',
                ),
                'taxonomy'    => array(
                'description' => 'The term taxonomy.',
                'type'        => 'string',
                ),
                'description' => array(
                'description' => 'The term description.',
                'type'        => 'string',
                ),
                'groups'      => array(
                'description' => 'The groups that this term is assigned to.',
                'type'        => 'array',
                ),
                'post_count'  => array(
                'description' => 'The post count per group (published posts).',
                'type'        => 'array',
                ),
            ),
            );
            return $schema;
        }

        /**
         *
         */
        public static function get_taxonomy_schema()
        {
            $schema = array(
                '$schema'    => 'http://json-schema.org/draft-04/schema#',
                'title'      => 'taxonomy',
                'type'       => 'object',
                'properties' => array(
                'name' => array(
                'description' => 'The taxonomy name.',
                'type'        => 'string',
                ),
            ),
            );
            return $schema;
        }

        /**
         *
         */
        public static function get_post_schema()
        {
            $schema = array(
                '$schema'    => 'http://json-schema.org/draft-04/schema#',
                'title'      => 'post',
                'type'       => 'object',
                'properties' => array(
                'id'    => array(
                'type'     => 'integer',
                'label'    => 'Object ID.',
                'readonly' => true,
                ),
                'terms' => array(
                'description' => 'The post tags in their groups.',
                'type'        => 'array',
                ),
            ),
            );
            return $schema;
        }

        /**
         * Determines whether the current user is allowed to edit tag groups
         *
         * @return boolean
         */
        public static function current_user_can_edit_groups()
        {
            /**
             * editable REST API is opt-in
             */

            if (!defined('TAG_GROUPS_REST_API_EDITABLE') || !TAG_GROUPS_REST_API_EDITABLE) {
                TagGroups_Error::verbose_log('[Tag Groups] REST API is not editable. Add to wp-config.php: define( "TAG_GROUPS_REST_API_EDITABLE", true );');
                return false;
            }

            global $tag_groups_current_user_id ;
            $tag_group_role_edit_groups = 'edit_pages';
            if (TagGroups_Utilities::is_premium_plan()) {
                $tag_group_role_edit_groups = TagGroups_Options::get_option('tag_group_role_edit_groups', 'edit_pages');
            }

            if (!get_current_user_id() && $tag_groups_current_user_id) {
                wp_set_current_user($tag_groups_current_user_id);
            }
            return current_user_can($tag_group_role_edit_groups);
        }

        /**
         * Determines whether the current user is allowed to edit the groups of tags
         *
         * @return boolean
         */
        public static function current_user_can_edit_tags()
        {
            /**
             * editable REST API is opt-in
             */

            if (!defined('TAG_GROUPS_REST_API_EDITABLE') || !TAG_GROUPS_REST_API_EDITABLE) {
                TagGroups_Error::verbose_log('[Tag Groups] REST API is not editable. Add to wp-config.php: define( "TAG_GROUPS_REST_API_EDITABLE", true );');
                return false;
            }

            global $tag_groups_current_user_id ;
            $tag_group_role_edit_tags = 'edit_pages';
            if (TagGroups_Utilities::is_premium_plan()) {
                $tag_group_role_edit_tags = TagGroups_Options::get_option('tag_group_role_edit_tags', 'edit_pages');
            }

            if (!get_current_user_id() && $tag_groups_current_user_id) {
                wp_set_current_user($tag_groups_current_user_id);
            }
            return current_user_can($tag_group_role_edit_tags);
        }

        /**
         * Determines whether the current user is allowed to manage options
         *
         * @return boolean
         */
        public static function current_user_can_manage_options()
        {
            /**
             * editable REST API is opt-in
             */

            if (!defined('TAG_GROUPS_REST_API_EDITABLE') || !TAG_GROUPS_REST_API_EDITABLE) {
                TagGroups_Error::verbose_log('[Tag Groups] REST API is not editable. Add to wp-config.php: define( "TAG_GROUPS_REST_API_EDITABLE", true );');
                return false;
            }

            global  $tag_groups_current_user_id ;
            if (!get_current_user_id() && $tag_groups_current_user_id) {
                wp_set_current_user($tag_groups_current_user_id);
            }
            return current_user_can('manage_options');
        }

        /**
         * Determines whether the current user/guest is allowed to access endpoint
         *
         * @return boolean
         */
        public static function current_user_can_access_endoint($option = 'groups')
        {
            global  $tag_groups_current_user_id ;
            $can_access = false;

            if (!get_current_user_id() && $tag_groups_current_user_id) {
                wp_set_current_user($tag_groups_current_user_id);
            }
            
            //enable admin by default
            if (current_user_can('manage_options')) {
                $can_access = true;
            } elseif ($option == 'groups' && !empty(TagGroups_Options::get_option('tag_group_enable_group_public_api_access', 0))) {
                $can_access = true;
            } elseif ($option == 'terms' && !empty(TagGroups_Options::get_option('tag_group_enable_terms_public_api_access', 0))) {
                $can_access = true;
            }

            return $can_access;
        }

        /**
         * Determines whether the current user is allowed to manage post tags
         *
         * @return boolean
         */
        public static function current_user_can_manage_post_tags()
        {
            /**
             * editable REST API is opt-in
             */

            if (!defined('TAG_GROUPS_REST_API_EDITABLE') || !TAG_GROUPS_REST_API_EDITABLE) {
                TagGroups_Error::verbose_log('[Tag Groups] REST API is not editable. Add to wp-config.php: define( "TAG_GROUPS_REST_API_EDITABLE", true );');
                return false;
            }

            global $tag_groups_current_user_id ;
            if (TagGroups_Utilities::is_premium_plan()) {
                if (!get_current_user_id() && $tag_groups_current_user_id) {
                    wp_set_current_user($tag_groups_current_user_id);
                }

                return current_user_can('edit_posts');
            }

            return false;
        }
    }
}
