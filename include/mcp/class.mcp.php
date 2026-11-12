<?php

/**
 * Private Abilities API integration for Tag Groups.
 * @package Tag Groups
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

class TagGroups_Mcp
{
    public static function boot()
    {
        add_action('wp_abilities_api_categories_init', static function () {
            if (function_exists('wp_has_ability_category') && !wp_has_ability_category('publishpress')) {
                wp_register_ability_category('publishpress', array('label' => __('PublishPress', 'tag-groups'), 'description' => __('PublishPress plugin actions.', 'tag-groups')));
            }
        });
        add_action('wp_abilities_api_init', array(__CLASS__, 'register'));
    }

    public static function pro()
    {
        return TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_Post');
    }

    public static function error($message = '', $status = 400)
    {
        return new WP_Error('tag-groups-mcp', $message ?: __('The operation could not be confirmed. Read the current values before retrying.', 'tag-groups'), array('status' => $status));
    }

    public static function allowed($scope, $write = false, $id = null)
    {
        if (!get_current_user_id() || ($write && defined('TAG_GROUPS_REST_API_EDITABLE') && !TAG_GROUPS_REST_API_EDITABLE)) {
            return false;
        }
        if ($scope === 'posts') {
            return self::pro() && $id && current_user_can('read_post', $id) && current_user_can('edit_post', $id) && (!$write || self::editable_post($id));
        }
        $cap = $scope === 'settings' ? 'manage_options' : 'edit_pages';
        if (self::pro() && in_array($scope, array('groups', 'tags'), true)) {
            $cap = TagGroups_Options::get_option('tag_group_role_edit_' . $scope, 'edit_pages');
        }
        return is_string($cap) && current_user_can($cap);
    }

    /** Native object permissions and WordPress editor lock, shared by MCP writes. */
    public static function editable_post($id)
    {
        if (!current_user_can('read_post', $id) || !current_user_can('edit_post', $id)) {
            return false;
        }
        if (!function_exists('wp_check_post_lock')) {
            require_once ABSPATH . 'wp-admin/includes/post.php';
        }
        return !wp_check_post_lock($id);
    }

    public static function hash($value)
    {
        $json = wp_json_encode($value);
        if (!is_string($json)) {
            throw new RuntimeException('Unencodable revision');
        }
        return hash_hmac('sha256', $json, wp_salt('auth'));
    }

    public static function stale($input, $state)
    {
        return !isset($input['expected_hash']) || !hash_equals(self::hash($state), $input['expected_hash']);
    }

    public static function object_schema($properties, $required = array())
    {
        return array('type' => 'object', 'properties' => $properties, 'required' => $required, 'additionalProperties' => false);
    }

    /** Reject scalar coercion and list/object ambiguity before native sanitization. */
    public static function validate($value, $schema)
    {
        $type = $schema['type'] ?? '';
        $list = is_array($value) && ($value === array() || array_keys($value) === range(0, count($value) - 1));
        $valid = ($type === 'object' && (is_object($value) || (is_array($value) && (!$list || !$value))))
            || ($type === 'array' && $list)
            || ($type === 'integer' && is_int($value))
            || ($type === 'boolean' && is_bool($value))
            || ($type === 'string' && is_string($value));
        if (!$valid) {
            return self::error(__('Incorrect JSON type.', 'tag-groups'));
        }
        if ($type === 'object') {
            foreach ((array) $value as $key => $child) {
                if (isset($schema['properties'][$key])) {
                    $result = self::validate($child, $schema['properties'][$key]);
                    if (is_wp_error($result)) {
                        return $result;
                    }
                }
            }
        } elseif ($type === 'array') {
            foreach ($value as $child) {
                $result = self::validate($child, $schema['items']);
                if (is_wp_error($result)) {
                    return $result;
                }
            }
        }
        return rest_validate_value_from_schema($value, $schema);
    }

    /** Action-specific result envelopes; native discovery dictionaries remain extensible. */
    private static function output_schema($name)
    {
        $array = array('type' => 'array', 'items' => array('type' => 'object'));
        $ids = array('type' => 'array', 'items' => array('type' => 'integer'));
        $object = array('type' => 'object');
        $common = array('complete' => array('type' => 'boolean'), 'dry_run' => array('type' => 'boolean'), 'expected_hash' => array('type' => 'string'));
        if ($name === 'list-features') {
            return self::object_schema(array('pro' => array('type' => 'boolean'), 'taxonomies' => array('type' => 'array'), 'features' => array('type' => 'array'), 'pro_features' => array('type' => 'array'), 'native_only' => array('type' => 'array')), array('pro', 'features'));
        }
        if ($name === 'list-displays') {
            return self::object_schema(array('displays' => $array, 'blocks' => $array), array('displays', 'blocks'));
        }
        if (strpos($name, 'plugin-settings') !== false) {
            return self::object_schema($common + array('settings' => $object, 'fields' => $object, 'pro' => array('type' => 'boolean'), 'changes' => $object, 'applied' => array('type' => 'array'), 'failed' => array('type' => 'string'), 'locked_fields' => array('type' => 'array'), 'effective_values' => $object));
        }
        if ($name === 'list-terms') {
            return self::object_schema(array('terms' => $array, 'page' => array('type' => 'integer'), 'per_page' => array('type' => 'integer')), array('terms'));
        }
        if (strpos($name, 'term-groups') !== false || $name === 'create-terms') {
            return self::object_schema($common + array('items' => $array, 'applied_ids' => $ids, 'failed_id' => array('type' => 'integer'), 'failed_name' => array('type' => 'string'), 'names' => array('type' => 'array'), 'groups' => $ids));
        }
        if (strpos($name, 'post-groups') !== false) {
            return self::object_schema($common + array('id' => array('type' => 'integer'), 'taxonomy' => array('type' => 'string'), 'groups' => $array), array('id', 'groups'));
        }
        return self::object_schema($common + array('groups' => $array, 'id' => array('type' => 'integer'), 'ids' => $ids, 'group' => $object, 'affected_terms' => array('type' => 'integer'), 'affected_posts' => array('type' => 'integer'), 'failed_post_id' => array('type' => 'integer'), 'name' => array('type' => 'string')));
    }

    private static function labels()
    {
        return array(
            'list-features' => __('Discover Tag Groups features', 'tag-groups'),
            'list-displays' => __('Discover tag displays', 'tag-groups'),
            'get-plugin-settings' => __('Get Tag Groups settings', 'tag-groups'),
            'update-plugin-settings' => __('Update Tag Groups settings', 'tag-groups'),
            'list-groups' => __('List tag groups', 'tag-groups'),
            'get-group' => __('Get tag group deletion scope', 'tag-groups'),
            'save-group' => __('Save tag group', 'tag-groups'),
            'delete-group' => __('Delete tag group', 'tag-groups'),
            'set-group-order' => __('Set tag group order', 'tag-groups'),
            'list-terms' => __('Find taxonomy terms', 'tag-groups'),
            'create-terms' => __('Create grouped terms', 'tag-groups'),
            'set-term-groups' => __('Set term groups', 'tag-groups'),
            'bulk-set-term-groups' => __('Set groups for multiple terms', 'tag-groups'),
            'get-post-groups' => __('Get post grouped terms', 'tag-groups'),
            'set-post-groups' => __('Set post grouped terms', 'tag-groups'),
        );
    }

    /** Keep native diagnostics outside the structured tool result. */
    private static function execute_native($name, $input)
    {
        global $wpdb;
        $previous = $wpdb->suppress_errors();
        $level = ob_get_level();
        ob_start();
        try {
            return TagGroups_Mcp_Actions::execute($name, $input);
        } finally {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            $wpdb->suppress_errors($previous);
        }
    }

    public static function register()
    {
        if (!function_exists('wp_register_ability')) {
            return;
        }
        $id = array('type' => 'integer', 'minimum' => 1);
        $ids = array('type' => 'array', 'items' => array('type' => 'integer', 'minimum' => 0), 'uniqueItems' => true, 'maxItems' => 200);
        $write = array('expected_hash' => array('type' => 'string', 'minLength' => 64, 'maxLength' => 64), 'dry_run' => array('type' => 'boolean'));
        $definitions = array(
            'list-features' => array('settings', false, array(), array(), __('Discover Tag Groups features, native identifiers, edition requirements and limitations.', 'tag-groups')),
            'list-displays' => array('settings', false, array(), array(), __('Discover active native shortcodes and Gutenberg attribute schemas for clouds, lists, tabs, accordions and Pro filters.', 'tag-groups')),
            'get-plugin-settings' => array('settings', false, array(), array(), __('Read native Tag Groups settings and exact field schemas, choices and defaults.', 'tag-groups')),
            'update-plugin-settings' => array('settings', true, $write + array('settings' => TagGroups_Mcp_Settings::schema()), array('expected_hash', 'settings'), __('Update selected Tag Groups settings. Omitted settings are preserved; preview first.', 'tag-groups')),
            'list-groups' => array('groups', false, array(), array(), __('List group labels, positions and Pro parent headings in the current native language.', 'tag-groups')),
            'get-group' => array('groups', false, array('id' => $id), array('id'), __('Read a group deletion revision covering its current term and post memberships.', 'tag-groups')),
            'save-group' => array('groups', true, $write + array('id' => array('type' => 'integer', 'minimum' => 0), 'label' => array('type' => 'string', 'minLength' => 1, 'maxLength' => 200), 'position' => $id, 'is_parent' => array('type' => 'boolean')), array('expected_hash', 'id'), __('Create a group using id 0, or rename, move or change a Pro parent heading. Parents are determined by ordered headings.', 'tag-groups')),
            'delete-group' => array('groups', true, $write + array('id' => $id), array('expected_hash', 'id'), __('Delete one group and remove its memberships from all affected terms. Terms and posts are not deleted.', 'tag-groups')),
            'set-group-order' => array('groups', true, $write + array('ids' => $ids), array('expected_hash', 'ids'), __('Reorder all nonzero groups using each identifier exactly once. Pro parent headings determine hierarchy.', 'tag-groups')),
            'list-terms' => array('tags', false, array('taxonomy' => array('type' => 'string'), 'page' => $id, 'per_page' => array('type' => 'integer', 'minimum' => 1, 'maximum' => 100), 'search' => array('type' => 'string', 'maxLength' => 200)), array('taxonomy'), __('Find terms and their global group memberships in an enabled taxonomy.', 'tag-groups')),
            'create-terms' => array('tags', true, $write + array('taxonomy' => array('type' => 'string'), 'names' => array('type' => 'array', 'minItems' => 1, 'maxItems' => 20, 'uniqueItems' => true, 'items' => array('type' => 'string', 'minLength' => 1, 'maxLength' => 200)), 'groups' => $ids), array('expected_hash', 'taxonomy', 'names', 'groups'), __('Create named terms in an enabled taxonomy with global group memberships. Use the current list-groups revision and preview first.', 'tag-groups')),
            'set-term-groups' => array('tags', true, $write + array('id' => $id, 'groups' => $ids), array('expected_hash', 'id', 'groups'), __('Replace global group membership for one term. Free allows one group; Pro allows multiple. Group 0 means unassigned.', 'tag-groups')),
            'bulk-set-term-groups' => array('tags', true, $write + array('items' => array('type' => 'array', 'minItems' => 1, 'maxItems' => 20, 'items' => self::object_schema(array('id' => $id, 'groups' => $ids, 'expected_hash' => $write['expected_hash']), array('id', 'groups', 'expected_hash')))), array('items'), __('Validate all term memberships before saving; report applied identifiers on partial failure.', 'tag-groups')),
        );
        if (self::pro()) {
            $rows = array('type' => 'array', 'maxItems' => 200, 'items' => self::object_schema(array('group_id' => array('type' => 'integer', 'minimum' => 0), 'term_ids' => array('type' => 'array', 'items' => $id, 'uniqueItems' => true, 'maxItems' => 200)), array('group_id', 'term_ids')));
            $definitions['get-post-groups'] = array('posts', false, array('id' => $id), array('id'), __('Read Pro post-specific grouped tags and their revision. These differ from global term membership.', 'tag-groups'));
            $definitions['set-post-groups'] = array('posts', true, $write + array('id' => $id, 'taxonomy' => array('type' => 'string'), 'groups' => $rows), array('expected_hash', 'id', 'taxonomy', 'groups'), __('Replace post-specific grouped terms for one enabled metabox taxonomy, preserving other taxonomies. Existing global term memberships are required.', 'tag-groups'));
        }
        foreach ($definitions as $name => $definition) {
            list($scope, $is_write, $properties, $required, $description) = $definition;
            $schema = self::object_schema($properties, $required);
            wp_register_ability('publishpress-tag-groups/' . $name, array(
                'label' => self::labels()[$name],
                'description' => $description,
                'category' => 'publishpress',
                'input_schema' => $schema,
                'output_schema' => self::output_schema($name),
                'permission_callback' => static function ($input) use ($scope, $is_write, $schema) {
                    return !is_wp_error(self::validate($input, $schema)) && self::allowed($scope, $is_write, ((array) $input)['id'] ?? null);
                },
                'execute_callback' => static function ($input) use ($name, $schema, $scope, $is_write) {
                    try {
                        $valid = self::validate($input, $schema);
                        if (is_wp_error($valid) || !self::allowed($scope, $is_write, ((array) $input)['id'] ?? null)) {
                            return self::error(__('Invalid input or insufficient permission.', 'tag-groups'), 403);
                        }
                        return self::execute_native($name, json_decode(wp_json_encode($input), true));
                    } catch (Throwable $error) {
                        return self::error();
                    }
                },
                'meta' => array('public' => false, 'show_in_rest' => false, 'mcp' => array('public' => false), 'annotations' => array('readonly' => !$is_write, 'destructive' => $is_write, 'idempotent' => !in_array($name, array('save-group', 'create-terms'), true))),
            ));
        }
    }
}
