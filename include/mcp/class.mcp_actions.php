<?php

/** Native group, term and post operations for MCP. @package Tag Groups */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

class TagGroups_Mcp_Actions
{
    public static function execute($name, $input)
    {
        global $tag_group_groups;
        $tag_group_groups->load();
        switch ($name) {
            case 'list-features':
                return array('pro' => TagGroups_Mcp::pro(), 'taxonomies' => array_values(TagGroups_Taxonomy::get_enabled_taxonomies()), 'features' => array('settings', 'groups', 'ordering', 'term_memberships', 'bulk_term_memberships'), 'pro_features' => array('multiple_groups', 'parent_headings', 'post_grouped_terms', 'default_groups', 'metabox', 'post_display', 'filter_templates', 'woocommerce', 'scoped_group_deletion'), 'native_only' => array('import_export_files', 'reset', 'migration_repair', 'license', 'sample_pages', 'frontend_rendering'));
            case 'list-displays':
                global $shortcode_tags;
                $displays = array();
                foreach ($shortcode_tags as $tag => $callback) {
                    if (strpos($tag, 'tag_groups_') !== 0) {
                        continue;
                    }
                    $schemas = array();
                    if (is_array($callback) && is_object($callback[0])) {
                        foreach (get_class_vars(get_class($callback[0])) as $property => $value) {
                            if (strpos($property, 'serverside_render_attributes') === 0 && is_array($value)) {
                                $schemas[$property] = (object) $value;
                            }
                        }
                    }
                    $displays[] = array('shortcode' => $tag, 'attribute_schemas' => (object) $schemas);
                }
                $blocks = array();
                foreach (WP_Block_Type_Registry::get_instance()->get_all_registered() as $name => $block) {
                    if (strpos($name, 'chatty-mango/tag-groups-') === 0) {
                        $blocks[] = array('name' => $name, 'attributes' => (object) $block->get_attributes());
                    }
                }
                return array('displays' => $displays, 'blocks' => $blocks);
            case 'get-plugin-settings':
                return TagGroups_Mcp_Settings::read();
            case 'update-plugin-settings':
                return TagGroups_Mcp_Settings::save($input);
            case 'list-groups':
                return self::groups();
            case 'get-group':
                if (!(new TagGroups_Group($input['id']))->exists()) {
                    return TagGroups_Mcp::error(__('Unknown group identifier.', 'tag-groups'));
                }
                $scope = self::deletion_scope($input['id']);
                return array('id' => $input['id'], 'expected_hash' => TagGroups_Mcp::hash(array(self::group_state(), $scope)), 'affected_terms' => count($scope[0]), 'affected_posts' => count($scope[1]));
            case 'save-group':
            case 'delete-group':
            case 'set-group-order':
                return self::save_group($name, $input);
            case 'list-terms':
                return self::terms($input);
            case 'create-terms':
                return self::create_terms($input);
            case 'set-term-groups':
                return self::save_terms(array($input), !empty($input['dry_run']));
            case 'bulk-set-term-groups':
                return self::save_terms($input['items'], !empty($input['dry_run']));
            case 'get-post-groups':
                return self::post($input['id']);
            case 'set-post-groups':
                return self::save_post($input);
        }
        return TagGroups_Mcp::error();
    }

    private static function group_state()
    {
        global $tag_group_groups;
        return array($tag_group_groups->get_group_ids(), $tag_group_groups->get_positions(), $tag_group_groups->get_labels(), $tag_group_groups->get_parents(), $tag_group_groups->get_tag_group_label_option_name());
    }

    private static function groups()
    {
        global $tag_group_groups;
        $rows = array();
        foreach ($tag_group_groups->get_group_ids_by_position(true) as $id) {
            $group = new TagGroups_Group($id);
            $rows[] = array('id' => (int) $id, 'label' => $group->get_label(), 'position' => (int) $group->get_position(), 'is_parent' => (bool) $group->is_parent, 'parent_id' => (int) $group->get_parent());
        }
        return array('groups' => $rows, 'expected_hash' => TagGroups_Mcp::hash(self::group_state()));
    }

    private static function group_ids_valid($ids)
    {
        global $tag_group_groups;
        if (array_diff($ids, array_merge(array(0), array_map('intval', $tag_group_groups->get_group_ids()))) || (in_array(0, $ids, true) && count($ids) > 1)) {
            return false;
        }
        foreach ($ids as $id) {
            if ($id && (new TagGroups_Group($id))->is_parent) {
                return false;
            }
        }
        return true;
    }

    /** Fresh exact native metadata scope; no private content is returned to clients. */
    private static function deletion_scope($id)
    {
        global $wpdb;
        $previous = $wpdb->suppress_errors();
        try {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $terms = $wpdb->get_results($wpdb->prepare("SELECT term_id, meta_value FROM $wpdb->termmeta WHERE meta_key = '_cm_term_group_array' AND meta_value LIKE %s ORDER BY term_id LIMIT 501", '%,' . $wpdb->esc_like((string) $id) . ',%'), ARRAY_A);
            $failed = (bool) $wpdb->last_error;
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
            $posts = TagGroups_Mcp::pro() ? $wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY post_id LIMIT 501", '_cm_post_terms_' . $id), ARRAY_A) : array();
            $failed = $failed || (bool) $wpdb->last_error;
        } finally {
            $wpdb->suppress_errors($previous);
        }
        if ($failed || count($terms) > 500 || count($posts) > 500) {
            throw new RuntimeException('Native deletion scope unavailable');
        }
        $translations = array();
        foreach ((array) TagGroups_Options::get_option('tag_group_group_languages', array()) as $language) {
            $translations[$language] = TagGroups_Options::get_option('term_group_labels_' . $language, array());
        }
        return array($terms, $posts, $translations);
    }

    private static function save_group($operation, $input)
    {
        global $tag_group_groups;
        $scope = $operation === 'delete-group' ? self::deletion_scope($input['id']) : null;
        $revision = $scope === null ? self::group_state() : array(self::group_state(), $scope);
        if (TagGroups_Mcp::stale($input, $revision)) {
            return TagGroups_Mcp::error(__('Groups changed. Read them again.', 'tag-groups'), 409);
        }
        if ($operation === 'set-group-order') {
            $ids = array_values(array_filter(array_map('intval', $tag_group_groups->get_group_ids())));
            $sorted = $input['ids'];
            sort($ids);
            sort($sorted);
            if ($ids !== $sorted) {
                return TagGroups_Mcp::error(__('Supply every nonzero group exactly once.', 'tag-groups'));
            }
            if ($input['dry_run'] ?? false) {
                return array('dry_run' => true, 'ids' => $input['ids']);
            }
            $positions = $tag_group_groups->get_positions();
            foreach ($input['ids'] as $index => $id) {
                $positions[$id] = $index + 1;
            }
            $tag_group_groups->set_positions($positions);
            $tag_group_groups->save();
            $tag_group_groups->load();
            return self::groups() + array('complete' => array_values(array_filter(array_map('intval', $tag_group_groups->get_group_ids_by_position(true)))) === $input['ids']);
        }
        $group = new TagGroups_Group($input['id']);
        if (($input['id'] && !$group->exists()) || (!$input['id'] && empty($input['label'])) || (!empty($input['is_parent']) && !TagGroups_Mcp::pro())) {
            return TagGroups_Mcp::error(__('Invalid group or edition.', 'tag-groups'));
        }
        if (isset($input['position']) && $input['position'] > $tag_group_groups->get_max_position() + ($input['id'] ? 0 : 1)) {
            return TagGroups_Mcp::error(__('Invalid group position.', 'tag-groups'));
        }
        if (isset($input['label'])) {
            $input['label'] = sanitize_text_field($input['label']);
            if ($input['label'] === '') {
                return TagGroups_Mcp::error(__('A group needs a label.', 'tag-groups'));
            }
        }
        if (isset($input['label']) && (!TagGroups_Mcp::pro() || !$tag_group_groups->get_parents())) {
            foreach ($tag_group_groups->get_labels() as $other_id => $label) {
                if ((int) $other_id !== $input['id'] && $label === $input['label']) {
                    return TagGroups_Mcp::error(__('A group with this label already exists.', 'tag-groups'));
                }
            }
        }
        if (!empty($input['is_parent']) && !$group->is_parent && $input['id']) {
            $members = $group->get_group_terms(TagGroups_Taxonomy::get_enabled_taxonomies(), false, 'ids');
            if (!is_array($members) || $members) {
                return TagGroups_Mcp::error(__('Remove term memberships before converting this group to a parent heading.', 'tag-groups'));
            }
        }
        if ($operation === 'delete-group') {
            $terms = array_map('intval', array_column($scope[0], 'term_id'));
            if (!is_array($terms) || count($terms) > 500 || !TagGroups_Mcp::allowed('tags', true)) {
                return TagGroups_Mcp::error(__('Use native administration for this deletion.', 'tag-groups'), 403);
            }
            foreach ($terms as $term_id) {
                if (!self::editable_term((int) $term_id) || !self::term_posts_allowed((int) $term_id)) {
                    return TagGroups_Mcp::error(__('An affected term cannot be edited.', 'tag-groups'), 403);
                }
            }
            $post_ids = array_map('intval', array_column($scope[1], 'post_id'));
            foreach ($post_ids as $post_id) {
                if (!TagGroups_Mcp::editable_post($post_id)) {
                    return TagGroups_Mcp::error(__('An affected post cannot be edited.', 'tag-groups'), 403);
                }
            }
            if ($input['dry_run'] ?? false) {
                return array('dry_run' => true, 'affected_terms' => count($terms), 'affected_posts' => count($post_ids));
            }
            // Repair only authorized affected objects instead of scheduling site-wide maintenance.
            $propagation_allowed = self::guarded_write(static function () use ($group, $terms) {
                $group->delete(false, $terms);
            });
            foreach ($post_ids as $post_id) {
                if (!TagGroups_Mcp::editable_post($post_id)) {
                    return array('complete' => false, 'failed_post_id' => (int) $post_id);
                }
                (new TagGroups_Premium_Post($post_id))->fix_incorrect_post_terms(TagGroups_Taxonomy::get_enabled_taxonomies());
            }
            $tag_group_groups->load();
            $complete = $propagation_allowed && !(new TagGroups_Group($input['id']))->exists();
            foreach ($terms as $term_id) {
                $complete = !(new TagGroups_Term($term_id))->has_group($input['id']) && $complete;
            }
            return self::groups() + array('complete' => $complete);
        }
        if ($input['dry_run'] ?? false) {
            return array('dry_run' => true, 'group' => (object) array_diff_key($input, array_flip(array('expected_hash', 'dry_run'))));
        }
        if (!$input['id']) {
            $group->create($input['label'], $input['position'] ?? null, $input['is_parent'] ?? false);
        } else {
            if (isset($input['label'])) {
                $group->set_label($input['label']);
            }
            if (isset($input['position'])) {
                $group->move_to_position($input['position']);
            }
            if (isset($input['is_parent'])) {
                $parents = array_values(array_diff($tag_group_groups->get_parents(), array($input['id'])));
                if ($input['is_parent']) {
                    $parents[] = $input['id'];
                }
                $tag_group_groups->set_parents($parents);
                $group->is_parent = $input['is_parent'];
            }
            $group->save();
        }
        if (!TagGroups_Mcp::allowed('groups', true)) {
            return TagGroups_Mcp::error();
        }
        $id = (int) $group->get_group_id();
        $tag_group_groups->load();
        $actual = new TagGroups_Group($id);
        $complete = $actual->exists() && empty($group->error);
        foreach (array('label' => $actual->get_label(), 'position' => (int) $actual->get_position(), 'is_parent' => (bool) $actual->is_parent) as $key => $value) {
            $complete = (!array_key_exists($key, $input) || $input[$key] === $value) && $complete;
        }
        return self::groups() + array('id' => $id, 'complete' => $complete);
    }

    private static function create_terms($input)
    {
        $taxonomy = get_taxonomy($input['taxonomy']);
        if (!$taxonomy || !in_array($taxonomy->name, TagGroups_Taxonomy::get_enabled_taxonomies(), true) || !current_user_can($taxonomy->cap->edit_terms) || !self::group_ids_valid($input['groups']) || (!TagGroups_Mcp::pro() && count($input['groups']) > 1)) {
            return TagGroups_Mcp::error(__('Invalid term creation scope or permission.', 'tag-groups'), 403);
        }
        if (TagGroups_Mcp::stale($input, self::group_state())) {
            return TagGroups_Mcp::error(__('Groups changed. Read them again.', 'tag-groups'), 409);
        }
        $names = array_map('sanitize_text_field', $input['names']);
        $slugs = array_map('sanitize_title', $names);
        if (count(array_unique($slugs)) !== count($slugs)) {
            return TagGroups_Mcp::error(__('Term names must be distinct.', 'tag-groups'));
        }
        foreach ($names as $name) {
            if ($name === '' || term_exists(wp_slash($name), $taxonomy->name)) {
                return TagGroups_Mcp::error(__('A term name is empty or already exists.', 'tag-groups'));
            }
        }
        if ($input['dry_run'] ?? false) {
            return array('dry_run' => true, 'names' => $names, 'groups' => $input['groups']);
        }
        $applied = array();
        foreach ($names as $name) {
            if (!TagGroups_Mcp::allowed('tags', true) || !current_user_can($taxonomy->cap->edit_terms)) {
                return array('complete' => false, 'applied_ids' => $applied, 'failed_name' => $name);
            }
            $term = wp_insert_term(wp_slash($name), $taxonomy->name);
            if (is_wp_error($term)) {
                return array('complete' => false, 'applied_ids' => $applied, 'failed_name' => $name);
            }
            $id = (int) $term['term_id'];
            // Creation may trigger native hooks; recheck the actual new object's permission.
            if (!self::editable_term($id) || !TagGroups_Mcp::allowed('tags', true)) {
                return array('complete' => false, 'applied_ids' => $applied, 'failed_id' => $id);
            }
            $groups = $input['groups'] ?: array(0);
            $propagated = self::guarded_write(static function () use ($id, $groups) {
                (new TagGroups_Term($id))->set_group($groups)->save();
            });
            $actual = (new TagGroups_Term($id))->get_groups();
            sort($groups);
            sort($actual);
            if (!$propagated || $actual !== $groups) {
                return array('complete' => false, 'applied_ids' => $applied, 'failed_id' => $id);
            }
            $applied[] = $id;
        }
        return array('complete' => true, 'applied_ids' => $applied);
    }

    private static function editable_term($id)
    {
        $term = get_term($id);
        if (!$term || is_wp_error($term) || !in_array($term->taxonomy, TagGroups_Taxonomy::get_enabled_taxonomies(), true)) {
            return false;
        }
        $taxonomy = get_taxonomy($term->taxonomy);
        return current_user_can($taxonomy->cap->edit_terms) && current_user_can('edit_term', $id);
    }

    private static function term_row($term)
    {
        $groups = (new TagGroups_Term($term))->get_groups();
        return array('id' => (int) $term->term_id, 'name' => $term->name, 'slug' => $term->slug, 'taxonomy' => $term->taxonomy, 'groups' => $groups, 'expected_hash' => TagGroups_Mcp::hash(array($term, get_term_meta($term->term_id, '_cm_term_group_array', true), self::group_state())));
    }

    private static function terms($input)
    {
        $taxonomy = get_taxonomy($input['taxonomy']);
        if (!$taxonomy || !in_array($input['taxonomy'], TagGroups_Taxonomy::get_enabled_taxonomies(), true) || !current_user_can($taxonomy->cap->manage_terms)) {
            return TagGroups_Mcp::error(__('Taxonomy access denied.', 'tag-groups'), 403);
        }
        $page = $input['page'] ?? 1;
        $size = $input['per_page'] ?? 50;
        $terms = get_terms(array('taxonomy' => $input['taxonomy'], 'hide_empty' => false, 'number' => $size, 'offset' => ($page - 1) * $size, 'search' => $input['search'] ?? ''));
        if (is_wp_error($terms)) {
            return TagGroups_Mcp::error();
        }
        $rows = array();
        foreach ($terms as $term) {
            if (current_user_can('edit_term', $term->term_id)) {
                $rows[] = self::term_row($term);
            }
        }
        return array('terms' => $rows, 'page' => $page, 'per_page' => $size);
    }

    /** Keep native term propagation under the caller's post permissions. */
    private static function guarded_write($callback)
    {
        $allowed = true;
        $guard = static function ($native_allowed, $post_ids) use (&$allowed) {
            if (!$native_allowed || !is_array($post_ids) || count($post_ids) > 500) {
                $allowed = false;
            } else {
                foreach ($post_ids as $post_id) {
                    $allowed = TagGroups_Mcp::allowed('tags', true) && TagGroups_Mcp::editable_post($post_id) && $allowed;
                }
            }
            return $allowed;
        };
        add_filter('tag_groups_can_update_post_meta_for_term', $guard, PHP_INT_MAX, 2);
        try {
            $callback();
        } finally {
            remove_filter('tag_groups_can_update_post_meta_for_term', $guard, PHP_INT_MAX);
        }
        return $allowed;
    }

    /** Native global membership propagation may also update post metadata in Pro. */
    private static function term_posts_allowed($id)
    {
        if (!TagGroups_Mcp::pro()) {
            return true;
        }
        $ids = TagGroups_Premium_Post_Meta_Tools::get_post_ids_matching_term($id);
        if (!is_array($ids) || count($ids) > 500) {
            return false;
        }
        foreach ($ids as $post_id) {
            if (!TagGroups_Mcp::editable_post($post_id)) {
                return false;
            }
        }
        return true;
    }

    private static function save_terms($items, $preview)
    {
        $seen = array();
        foreach ($items as $item) {
            if (isset($seen[$item['id']]) || !self::editable_term($item['id']) || !self::term_posts_allowed($item['id']) || !self::group_ids_valid($item['groups']) || (!TagGroups_Mcp::pro() && count($item['groups']) > 1)) {
                return TagGroups_Mcp::error(__('Invalid term membership or insufficient permission.', 'tag-groups'), 403);
            }
            $seen[$item['id']] = true;
            if (!hash_equals(self::term_row(get_term($item['id']))['expected_hash'], $item['expected_hash'])) {
                return TagGroups_Mcp::error(__('Term membership changed. Read it again.', 'tag-groups'), 409);
            }
        }
        if ($preview) {
            return array('dry_run' => true, 'items' => $items);
        }
        $applied = array();
        foreach ($items as $item) {
            if (!TagGroups_Mcp::allowed('tags', true) || !self::editable_term($item['id']) || !hash_equals(self::term_row(get_term($item['id']))['expected_hash'], $item['expected_hash'])) {
                return array('complete' => false, 'applied_ids' => $applied, 'failed_id' => $item['id']);
            }
            $groups = $item['groups'] ?: array(0);
            $propagation_allowed = self::guarded_write(static function () use ($item, $groups) {
                (new TagGroups_Term($item['id']))->set_group($groups)->save();
            });
            $actual = (new TagGroups_Term($item['id']))->get_groups();
            sort($groups);
            sort($actual);
            if (!$propagation_allowed || $actual !== $groups) {
                return array('complete' => false, 'applied_ids' => $applied, 'failed_id' => $item['id']);
            }
            $applied[] = $item['id'];
        }
        return array('complete' => true, 'applied_ids' => $applied);
    }

    private static function post($id)
    {
        $post = get_post($id);
        if (!TagGroups_Mcp::pro() || !$post || wp_is_post_revision($id) || !current_user_can('read_post', $id) || !current_user_can('edit_post', $id)) {
            return TagGroups_Mcp::error(__('Post access denied.', 'tag-groups'), 403);
        }
        $rows = array();
        $native = new TagGroups_Premium_Post($id, true);
        foreach ($native->get_terms_by_group() as $group => $ids) {
            $rows[] = array('group_id' => (int) $group, 'term_ids' => array_map('intval', $ids));
        }
        return array('id' => $id, 'groups' => $rows, 'expected_hash' => TagGroups_Mcp::hash(array($post, get_post_meta($id), wp_get_object_terms($id, get_object_taxonomies($post->post_type), array('fields' => 'ids')), self::group_state(), TagGroups_Mcp_Settings::state())));
    }

    private static function save_post($input)
    {
        $before = self::post($input['id']);
        $tax = get_taxonomy($input['taxonomy']);
        $post = get_post($input['id']);
        if (is_wp_error($before) || !$tax || !is_object_in_taxonomy($post->post_type, $tax->name) || !in_array($tax->name, TagGroups_Taxonomy::get_taxonomies_for_metabox(), true) || !current_user_can($tax->cap->assign_terms)) {
            return TagGroups_Mcp::error(__('Metabox taxonomy or assignment is restricted.', 'tag-groups'), 403);
        }
        if (!TagGroups_Mcp::editable_post($post->ID)) {
            return TagGroups_Mcp::error(__('The post is locked or cannot be edited.', 'tag-groups'), 403);
        }
        if (!hash_equals($before['expected_hash'], $input['expected_hash'])) {
            return TagGroups_Mcp::error(__('Post terms changed. Read them again.', 'tag-groups'), 409);
        }
        $include = TagGroups_Options::get_option('tag_group_meta_box_include', array());
        $rows = array();
        $unassigned = array();
        $seen = array();
        foreach ($input['groups'] as $row) {
            $gid = $row['group_id'];
            if (isset($seen[$gid]) || !self::group_ids_valid(array($gid)) || ($include && !in_array($gid, $include))) {
                return TagGroups_Mcp::error(__('Invalid or restricted metabox group.', 'tag-groups'));
            }
            $seen[$gid] = true;
            foreach ($row['term_ids'] as $term_id) {
                $term = get_term($term_id, $tax->name);
                if (!$term || is_wp_error($term) || !(new TagGroups_Term($term))->has_group($gid)) {
                    return TagGroups_Mcp::error(__('Assign existing terms to their global groups first.', 'tag-groups'));
                }
                if (!$gid) {
                    $unassigned[] = $term_id;
                } else {
                    $rows[] = array('term_id' => $term_id, 'taxonomy' => $tax->name, 'term_group' => $gid);
                }
            }
        }
        // Preserve native memberships for every other taxonomy.
        foreach ($before['groups'] as $row) {
            foreach ($row['term_ids'] as $term_id) {
                $term = get_term($term_id);
                if ($term && !is_wp_error($term) && ($term->taxonomy !== $tax->name || ($include && !in_array($row['group_id'], $include)))) {
                    $rows[] = array('term_id' => $term_id, 'taxonomy' => $term->taxonomy, 'term_group' => $row['group_id']);
                }
            }
        }
        if ($input['dry_run'] ?? false) {
            return array('dry_run' => true, 'id' => $post->ID, 'taxonomy' => $tax->name, 'groups' => $input['groups']);
        }
        $authorize = static function () use ($post, $tax) {
            return TagGroups_Mcp::allowed('posts', true, $post->ID) && current_user_can($tax->cap->assign_terms);
        };
        $result = (new TagGroups_Premium_Post($post->ID))->set_terms($rows, $unassigned, 'term_id')->save($tax->name, $authorize);
        if (is_wp_error($result)) {
            return TagGroups_Mcp::error();
        }
        $after = self::post($post->ID);
        if (is_wp_error($after)) {
            return TagGroups_Mcp::error();
        }
        $expected = array();
        foreach ($rows as $row) {
            $expected[$row['term_group']][] = $row['term_id'];
        }
        $expected[0] = array_merge($expected[0] ?? array(), $unassigned);
        $complete = true;
        foreach ($after['groups'] as $row) {
            $ids = array_values(array_unique($expected[$row['group_id']] ?? array()));
            sort($ids);
            $actual = $row['term_ids'];
            sort($actual);
            $complete = $complete && $ids === $actual;
        }
        $expected_core = array();
        foreach (
            array_merge($rows, array_map(static function ($id) use ($tax) {
                return array('term_id' => $id, 'taxonomy' => $tax->name); 
            }, $unassigned)) as $row
        ) {
            if ($row['taxonomy'] === $tax->name) {
                $expected_core[] = $row['term_id'];
            }
        }
        $expected_core = array_values(array_unique($expected_core));
        sort($expected_core);
        $actual_core = wp_get_object_terms($post->ID, $tax->name, array('fields' => 'ids'));
        if (is_wp_error($actual_core)) {
            $complete = false;
        } else {
            $actual_core = array_map('intval', $actual_core);
            sort($actual_core);
            $complete = $complete && $actual_core === $expected_core;
        }
        return $after + array('complete' => $complete);
    }
}
