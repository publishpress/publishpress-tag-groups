<?php

/** Native settings contracts for Tag Groups MCP. @package Tag Groups */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

class TagGroups_Mcp_Settings
{
    public static function fields()
    {
        $fields = array();
        $flags = array('mouseover' => false, 'collapsible' => false, 'enqueue_jquery' => true, 'shortcode_widget' => false, 'shortcode_enqueue_always' => true, 'show_filter' => false, 'show_filter_tags' => false, 'enable_group_public_api_access' => false, 'enable_terms_public_api_access' => false, 'server_side_render' => true, 'multilingual_sync_groups' => true, 'verbose_debug' => false, 'reset_when_uninstall' => false);
        foreach ($flags as $name => $default) {
            $fields['tag_group_' . $name] = array('type' => 'boolean', 'default' => $default);
        }
        $fields['tag_group_html_description'] = array('type' => 'integer', 'enum' => array(0, 1, 2), 'default' => 0, 'description' => __('HTML in tag descriptions: 0 removes HTML, 1 keeps HTML (requires unfiltered_html to enable), 2 checks the viewer unfiltered_html capability.', 'tag-groups'));
        $fields['tag_group_theme'] = array('type' => 'string', 'default' => TAG_GROUPS_STANDARD_THEME, 'maxLength' => 100);
        $fields['tag_group_taxonomy'] = array('type' => 'array', 'items' => array('type' => 'string', 'enum' => array_values(TagGroups_Taxonomy::get_public_taxonomies())), 'uniqueItems' => true, 'default' => array('post_tag'));
        $fields['tag_group_object_cache'] = array('type' => 'integer', 'enum' => array(0, 1, 2, 3, 9), 'default' => 1);
        if (TagGroups_Mcp::pro()) {
            $flags = array('meta_box_change_group' => true, 'meta_box_add_term' => true, 'meta_box_open_all' => false, 'hide_tagsdiv' => true, 'open_all_with_terms' => true, 'meta_box_add_all' => true, 'meta_box_remove_all' => true, 'meta_box_staged_loading' => true, 'auto_clear_cache' => true, 'display_groups_under_posts_single' => false, 'display_groups_under_posts_home' => false, 'display_groups_under_posts_archive' => false, 'display_groups_under_posts_feed' => false, 'remove_the_post_terms' => false, 'post_terms_with_parents' => false);
            if (defined('WC_VERSION') && class_exists('WooCommerce')) {
                $flags += array('add_product_tags_to_attributes' => false, 'remove_the_product_tags' => false);
                $fields['tag_group_display_groups_under_products_separator'] = array('type' => 'string', 'default' => ', ', 'maxLength' => 2000);
            }
            foreach ($flags as $name => $default) {
                $fields['tag_group_' . $name] = array('type' => 'boolean', 'default' => $default);
            }
            $fields['tag_group_meta_box_taxonomy'] = $fields['tag_group_taxonomy'];
            $fields['tag_group_meta_box_taxonomy']['default'] = array();
            global $tag_group_groups;
            foreach (array('meta_box_include', 'new_tag_default_groups', 'display_groups_under_posts') as $name) {
                $fields['tag_group_' . $name] = array('type' => 'array', 'items' => array('type' => 'integer', 'enum' => array_values(array_unique(array_merge(array(0), array_map('intval', $tag_group_groups->get_group_ids()))))), 'uniqueItems' => true, 'default' => $name === 'display_groups_under_posts' ? array_values(array_map('intval', $tag_group_groups->get_group_ids())) : array());
            }
            $leaf_ids = array_values(array_map('intval', $tag_group_groups->get_group_ids_by_position()));
            $fields['tag_group_meta_box_include']['items']['enum'] = array_values(array_unique(array_merge(array(0), $leaf_ids)));
            $fields['tag_group_new_tag_default_groups']['items']['enum'] = array_values(array_filter($leaf_ids));
            // JSON Schema requires a nonempty enum even when no assignable group exists.
            if (!$fields['tag_group_new_tag_default_groups']['items']['enum']) {
                unset($fields['tag_group_new_tag_default_groups']['items']['enum']);
                $fields['tag_group_new_tag_default_groups']['maxItems'] = 0;
            }
            $fields['tag_group_role_edit_groups'] = array('type' => 'string', 'enum' => array('manage_options', 'edit_pages', 'publish_posts'), 'default' => 'edit_pages');
            $fields['tag_group_role_edit_tags'] = array('type' => 'string', 'enum' => array('manage_options', 'edit_pages', 'publish_posts', 'edit_posts'), 'default' => 'edit_pages');
            $fields['tag_group_role_mb_override'] = array('type' => 'string', 'enum' => array('no_one', 'manage_options', 'edit_pages', 'publish_posts'), 'default' => 'no_one');
            $fields['tag_group_excerpt_length'] = array('type' => 'integer', 'minimum' => 0, 'maximum' => 10000, 'default' => 50);
            $fields['tag_group_display_groups_under_posts_priority'] = array('type' => 'integer', 'default' => 10);
            $strings = array('display_groups_under_posts_title' => __('Tags', 'publishpress-tag-groups-pro'), 'display_groups_under_posts_separator' => '&nbsp;|&nbsp;', 'excerpt_ellipsis' => ' [...]', 'dpf_template' => (new TagGroups_Premium_Templates())->get_html_of_default());
            foreach ($strings as $name => $default) {
                $fields['tag_group_' . $name] = array('type' => 'string', 'default' => $default, 'maxLength' => 50000);
            }
        }
        foreach ($fields as $name => &$field) {
            $field['description'] = $field['description'] ?? ucwords(str_replace('_', ' ', substr($name, 10)));
        }
        return $fields;
    }

    public static function schema()
    {
        $fields = self::fields();
        foreach (array('tag_group_meta_box_include', 'tag_group_new_tag_default_groups', 'tag_group_display_groups_under_posts') as $name) {
            if (isset($fields[$name])) {
                unset($fields[$name]['items']['enum'], $fields[$name]['maxItems']);
                $fields[$name]['items']['minimum'] = 0;
            }
        }
        return TagGroups_Mcp::object_schema($fields);
    }

    public static function state()
    {
        $state = array();
        foreach (self::fields() as $name => $field) {
            $state[$name] = get_option($name, null);
        }
        return $state;
    }

    public static function overrides()
    {
        $values = array();
        if (TagGroups_Mcp::pro()) {
            $native = TagGroups_Premium_Settings::get_excerpt_overrides();
            if ($native['length']) {
                $values['tag_group_excerpt_length'] = $native['length'];
            }
            if ($native['more']) {
                $values['tag_group_excerpt_ellipsis'] = $native['more'];
            }
        }
        return $values;
    }

    public static function read()
    {
        $values = array();
        $fields = self::fields();
        foreach ($fields as $name => $field) {
            $value = TagGroups_Options::get_option($name, $field['default']);
            if ($field['type'] === 'boolean') {
                $value = (bool) $value;
            } elseif ($field['type'] === 'integer') {
                $value = (int) $value;
            } elseif ($field['type'] === 'array') {
                $value = array_values((array) $value);
                if (($field['items']['type'] ?? '') === 'integer') {
                    $value = array_map('intval', $value);
                }
            }
            $values[$name] = $value;
        }
        return array('settings' => (object) $values, 'fields' => (object) $fields, 'expected_hash' => TagGroups_Mcp::hash(self::state()), 'pro' => TagGroups_Mcp::pro(), 'locked_fields' => array_keys(self::overrides()), 'effective_values' => (object) self::overrides());
    }

    public static function save($input)
    {
        if (TagGroups_Mcp::stale($input, self::state())) {
            return TagGroups_Mcp::error(__('Settings changed. Read them again.', 'tag-groups'), 409);
        }
        $valid = rest_validate_value_from_schema((object) $input['settings'], TagGroups_Mcp::object_schema(self::fields()));
        if (is_wp_error($valid)) {
            return TagGroups_Mcp::error(__('Invalid settings patch.', 'tag-groups'));
        }
        $patch = $input['settings'];
        if (
            isset($patch['tag_group_html_description']) && $patch['tag_group_html_description'] === 1
            && (int) TagGroups_Options::get_option('tag_group_html_description', 0) !== 1
            && !current_user_can('unfiltered_html')
        ) {
            return TagGroups_Mcp::error(__('Keeping unfiltered description HTML requires unfiltered_html.', 'tag-groups'), 403);
        }
        if (isset($patch['tag_group_theme'])) {
            $theme = $patch['tag_group_theme'];
            $segments = explode('/', $theme);
            if (
                preg_match('~[\\\\:\x00-\x1f?#%]~', $theme) || ($theme !== '' && ($theme[0] === '/' || in_array('', $segments, true)))
                || in_array('.', $segments, true) || in_array('..', $segments, true)
            ) {
                return TagGroups_Mcp::error(__('Use a relative theme folder within uploads.', 'tag-groups'));
            }
        }

        if (array_intersect(array_keys($patch), array_keys(self::overrides()))) {
            return TagGroups_Mcp::error(__('An excerpt setting is controlled by the active theme or another plugin.', 'tag-groups'), 403);
        }
        $native = TagGroups_Options::get_available_options();
        foreach ($patch as $name => &$value) {
            if (($native[$name]['type'] ?? '') === TagGroups_Options::STRING_HTML) {
                $value = TagGroups_Options::wp_kses($value);
            } elseif (is_string($value)) {
                $value = sanitize_text_field($value);
            }
        }
        unset($value);
        $merged = array_merge((array) self::read()['settings'], $patch);
        if (TagGroups_Mcp::pro() && array_intersect(array_keys($patch), array('tag_group_meta_box_taxonomy', 'tag_group_taxonomy')) && array_diff($merged['tag_group_meta_box_taxonomy'], $merged['tag_group_taxonomy'])) {
            return TagGroups_Mcp::error(__('Metabox taxonomies must also be enabled for Tag Groups.', 'tag-groups'));
        }
        if ($input['dry_run'] ?? false) {
            return array('dry_run' => true, 'changes' => (object) $patch);
        }
        if (!$patch) {
            return self::read() + array('complete' => true, 'applied' => array());
        }
        $applied = array();
        foreach ($patch as $name => $value) {
            if (!TagGroups_Mcp::allowed('settings', true)) {
                return array('complete' => false, 'applied' => $applied, 'failed' => $name);
            }
            if ($name === 'tag_group_object_cache' && TagGroups_Mcp::pro() && (int) TagGroups_Options::get_option($name, 1) !== $value) {
                TagGroups_Premium_Object_Cache::purge();
            }
            if ($name === 'tag_group_taxonomy') {
                TagGroups_Taxonomy::update_enabled($value);
            } else {
                TagGroups_Options::update_option($name, is_bool($value) ? (int) $value : $value);
            }
            if (!TagGroups_Mcp::allowed('settings', true)) {
                return array('complete' => false, 'applied' => $applied, 'failed' => $name);
            }
            $actual = (array) self::read()['settings'];
            if ($actual[$name] !== $value) {
                return array('complete' => false, 'applied' => $applied, 'failed' => $name);
            }
            $applied[] = $name;
        }
        $events = array();
        foreach (array_keys($patch) as $name) {
            if (in_array($name, array('tag_group_theme', 'tag_group_mouseover', 'tag_group_collapsible', 'tag_group_html_description', 'tag_group_enqueue_jquery'), true)) {
                $events['tag_groups_theme_saved'] = true;
            } elseif (strpos($name, 'tag_group_role_') === 0) {
                $events['tag_groups_permissions_saved'] = true;
            } elseif (strpos($name, 'tag_group_meta_box_') === 0 || in_array($name, array('tag_group_hide_tagsdiv', 'tag_group_open_all_with_terms'), true)) {
                $events['tag_groups_metabox_saved'] = true;
            } elseif ($name === 'tag_group_new_tag_default_groups') {
                $events['tag_groups_default_groups_saved'] = true;
            } elseif (in_array($name, array('tag_group_object_cache', 'tag_group_auto_clear_cache'), true)) {
                $events['tag_groups_object_cache_saved'] = true;
            } elseif (strpos($name, 'tag_group_excerpt_') === 0 || $name === 'tag_group_dpf_template') {
                $events['tag_groups_dpf_saved'] = true;
            } elseif (strpos($name, 'tag_group_display_groups_under_products') === 0 || in_array($name, array('tag_group_add_product_tags_to_attributes', 'tag_group_remove_the_product_tags'), true)) {
                $events['tag_groups_woocommerce_saved'] = true;
            } elseif (strpos($name, 'tag_group_display_groups_under_posts') === 0 || in_array($name, array('tag_group_remove_the_post_terms', 'tag_group_post_terms_with_parents'), true)) {
                $events['tag_groups_post_tags_saved'] = true;
            }
        }
        foreach (array_keys($events) as $event) {
            do_action($event);
        }
        return self::read() + array('complete' => true, 'applied' => $applied);
    }
}
