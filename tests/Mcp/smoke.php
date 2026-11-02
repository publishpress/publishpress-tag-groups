<?php
/** Isolated native workflows through released MCP Adapter 0.7.1. */
define('REST_REQUEST', true);
require $argv[1];
if (strpos(DB_NAME, 'taggroups_mcp_') !== 0) {
    throw new RuntimeException('Disposable taggroups_mcp_* database required');
}
require $argv[2] . '/vendor/autoload.php';
wp_set_current_user(1);
register_taxonomy('mcp_topic', array('post'), array('public' => true, 'hierarchical' => false));
$assertions = 0;
function check($condition, $message)
{
    global $assertions;
    ++$assertions;
    if (!$condition) {
        throw new RuntimeException($message);
    }
}
function callTG($name, $input = array())
{
    $ability = wp_get_ability('publishpress-tag-groups/' . $name);
    check($ability !== null, 'Missing ability ' . $name);
    $tool = \WP\MCP\Domain\Tools\McpTool::fromAbility($ability);
    check(!is_wp_error($tool), 'Invalid Adapter tool ' . $name);
    $result = $tool->execute($input);
    if (!is_wp_error($result)) {
        check(!is_wp_error(rest_validate_value_from_schema($result, $ability->get_output_schema())), 'Output schema mismatch ' . $name);
    }
    return $result;
}
function success($result, $message)
{
    check(!is_wp_error($result), $message . (is_wp_error($result) ? ': ' . $result->get_error_message() : ''));
    return $result;
}
$features = success(callTG('list-features'), 'Feature discovery');
$displays = success(callTG('list-displays'), 'Native frontend displays');
check(count($displays['displays']) >= 6, 'Native displays missing');
check(count($displays['blocks']) >= 5 && in_array('chatty-mango/tag-groups-cloud-tabs', array_column($displays['blocks'], 'name'), true), 'Native Gutenberg identifiers missing from discovery');
$settings = success(callTG('get-plugin-settings'), 'Settings discovery');
check($settings['pro'] === (getenv('TAGGROUPS_MCP_MODE') === 'pro'), 'Edition detection');
update_option('tag_groups_license_key', 'synthetic-secret');
check(strpos(wp_json_encode($settings), 'synthetic-secret') === false, 'License data exposed');
add_filter('excerpt_length', '__return_false', PHP_INT_MAX);
add_filter('excerpt_more', '__return_false', PHP_INT_MAX);
foreach ((array) $settings['fields'] as $name => $field) {
    $value = $settings['settings']->$name;
    check(!is_wp_error(rest_validate_value_from_schema($value, $field)), 'Discovery type mismatch ' . $name);
    $before = get_option($name, null);
    success(callTG('update-plugin-settings', array('settings' => (object) array($name => $value), 'expected_hash' => $settings['expected_hash'], 'dry_run' => true)), 'Preview ' . $name);
    check(get_option($name, null) === $before, 'Preview wrote ' . $name);
    $settings = success(callTG('update-plugin-settings', array('settings' => (object) array($name => $value), 'expected_hash' => $settings['expected_hash'])), 'Save ' . $name);
    check($settings['complete'], 'Native readback mismatch ' . $name);
    check(is_wp_error(callTG('update-plugin-settings', array('settings' => (object) array($name => null), 'expected_hash' => $settings['expected_hash']))), 'Null accepted ' . $name);
    if ($field['type'] === 'boolean') {
        check(is_wp_error(callTG('update-plugin-settings', array('settings' => (object) array($name => 'false'), 'expected_hash' => $settings['expected_hash']))), 'String false accepted ' . $name);
        foreach (array(true, false, $value) as $flag) {
            $settings = success(callTG('update-plugin-settings', array('settings' => (object) array($name => $flag), 'expected_hash' => $settings['expected_hash'])), 'Boolean save ' . $name);
            check($settings['complete'] && $settings['settings']->$name === $flag, 'Boolean roundtrip ' . $name);
        }
    }
}
remove_filter('excerpt_length', '__return_false', PHP_INT_MAX);
remove_filter('excerpt_more', '__return_false', PHP_INT_MAX);
check(get_option('tag_groups_license_key') === 'synthetic-secret', 'Unrelated secret overwritten');
$themeEvents = 0;
$watchThemeEvent = static function () use (&$themeEvents) { ++$themeEvents; };
add_action('tag_groups_theme_saved', $watchThemeEvent);
$settings = success(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_verbose_debug' => false))), 'Native diagnostics setting');
remove_action('tag_groups_theme_saved', $watchThemeEvent);
check($settings['complete'] && $themeEvents === 0, 'Unrelated diagnostics patch fired a theme lifecycle event');
$savedNativeSwitch = get_option('tag_group_mouseover', 0);
update_option('tag_group_mouseover', 2);
$truthyNativeSwitch = success(callTG('get-plugin-settings'), 'Native integer switch truthiness');
check($truthyNativeSwitch['settings']->tag_group_mouseover === true, 'Discovery disagreed with a truthy native integer switch');
update_option('tag_group_mouseover', $savedNativeSwitch);
$securitySettings = success(callTG('get-plugin-settings'), 'Security settings token');
$settingsBeforeInvalidPath = TagGroups_Mcp_Settings::state();
foreach (array('../plugins/private', '/absolute', 'C:\\private', 'safe/../outside', 'https://example.test/theme', 'safe%2f..') as $path) {
    check(is_wp_error(callTG('update-plugin-settings', array('expected_hash' => $securitySettings['expected_hash'], 'settings' => (object) array('tag_group_theme' => $path, 'tag_group_mouseover' => true)))), 'Unsafe theme path accepted');
    check(TagGroups_Mcp_Settings::state() === $settingsBeforeInvalidPath, 'Invalid theme patch wrote a valid sibling');
}
$denyUnfiltered = static function ($caps, $cap) { return $cap === 'unfiltered_html' ? array('do_not_allow') : $caps; };
add_filter('map_meta_cap', $denyUnfiltered, PHP_INT_MAX, 2);
try {
    check(is_wp_error(callTG('update-plugin-settings', array('expected_hash' => $securitySettings['expected_hash'], 'settings' => (object) array('tag_group_html_description' => 1, 'tag_group_mouseover' => true)))), 'Restricted Administrator enabled unfiltered public HTML');
} finally {
    remove_filter('map_meta_cap', $denyUnfiltered, PHP_INT_MAX);
}
check(TagGroups_Mcp_Settings::state() === $settingsBeforeInvalidPath, 'Denied unfiltered-HTML mixed patch wrote data');
$settings = success(callTG('get-plugin-settings'), 'Settings after security denial');
if (current_user_can('unfiltered_html')) {
    $settings = success(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_html_description' => 1), 'dry_run' => true)), 'Authorized raw HTML preview') + $settings;
    $settings = success(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_html_description' => 1))), 'Authorized raw HTML setting');
    check($settings['complete'] && $settings['settings']->tag_group_html_description === 1, 'Authorized native HTML mode did not persist');
    $settings = success(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_html_description' => 0))), 'Restore safe HTML mode');
}

$raw = TagGroups_Mcp_Settings::state();
check(is_wp_error(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_mouseover' => true, 'unknown' => 1)))), 'Unknown field accepted');
check(TagGroups_Mcp_Settings::state() === $raw, 'Mixed invalid patch wrote');
check(is_wp_error(callTG('update-plugin-settings', array('expected_hash' => str_repeat('0', 64), 'settings' => (object) array('tag_group_mouseover' => true)))), 'Stale settings accepted');
$settings = success(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_html_description' => 2))), 'Three-way HTML control');
check(get_option('tag_group_html_description') == 2 && $settings['settings']->tag_group_html_description === 2, 'HTML mode lost its native integer choice');
$settings = success(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_html_description' => 0))), 'Restore HTML control');
$groups = success(callTG('list-groups'), 'Groups');
$before = get_option('term_groups');
success(callTG('save-group', array('id' => 0, 'label' => 'Review', 'expected_hash' => $groups['expected_hash'], 'dry_run' => true)), 'Group create preview');
check(get_option('term_groups') === $before, 'Group preview wrote');
$groups = success(callTG('save-group', array('id' => 0, 'label' => 'Review', 'expected_hash' => $groups['expected_hash'])), 'Group create');
$group1 = $groups['id'];
check($groups['complete'], 'Group create readback');
$groups = success(callTG('save-group', array('id' => 0, 'label' => 'Second', 'expected_hash' => $groups['expected_hash'])), 'Second group');
$group2 = $groups['id'];
$allIds = array_values(array_filter(array_column($groups['groups'], 'id')));
$order = array_reverse($allIds);
$groups = success(callTG('set-group-order', array('ids' => $order, 'expected_hash' => $groups['expected_hash'])), 'Native group ordering');
check($groups['complete'], 'Group order readback: ' . wp_json_encode(array($groups, $order)));
$groups = success(callTG('save-group', array('id' => $group1, 'label' => 'Renamed review', 'expected_hash' => $groups['expected_hash'])), 'Native rename');
check($groups['complete'] && (new TagGroups_Group($group1))->get_label() === 'Renamed review', 'Rename readback');
$groups = success(callTG('list-groups'), 'Term creation revision');
$beforeTerms = wp_count_terms(array('taxonomy' => 'post_tag', 'hide_empty' => false));
success(callTG('create-terms', array('expected_hash' => $groups['expected_hash'], 'taxonomy' => 'post_tag', 'names' => array('Created by native API'), 'groups' => array($group1), 'dry_run' => true)), 'Term creation preview');
check(wp_count_terms(array('taxonomy' => 'post_tag', 'hide_empty' => false)) === $beforeTerms, 'Term creation preview wrote');
$createdTerms = success(callTG('create-terms', array('expected_hash' => $groups['expected_hash'], 'taxonomy' => 'post_tag', 'names' => array('Created by native API'), 'groups' => array($group1))), 'Native term creation');
check($createdTerms['complete'] && (new TagGroups_Term($createdTerms['applied_ids'][0]))->has_group($group1), 'Created term group readback');
$beforeLateLabel = (new TagGroups_Group($group1))->get_label();
$lateGroupToken = success(callTG('list-groups'), 'Late native group save token');
$denyGroupSave = static function ($caps, $cap) { return $cap === 'edit_pages' ? array('do_not_allow') : $caps; };
$denyOnGroupFilter = static function ($ids) use ($denyGroupSave) { add_filter('map_meta_cap', $denyGroupSave, PHP_INT_MAX, 2); return $ids; };
add_filter('tag_groups_save_group_ids', $denyOnGroupFilter);
try {
    $lateGroupResult = callTG('save-group', array('id' => $group1, 'label' => 'Must not persist after denial', 'expected_hash' => $lateGroupToken['expected_hash']));
} finally {
    remove_filter('tag_groups_save_group_ids', $denyOnGroupFilter);
    remove_filter('map_meta_cap', $denyGroupSave, PHP_INT_MAX);
}
check(is_wp_error($lateGroupResult), 'Native group save continued after permission revocation');
global $tag_group_groups;
$tag_group_groups->load();
check((new TagGroups_Group($group1))->get_label() === $beforeLateLabel, 'Denied native group label was persisted');
$diagnosticToken = success(callTG('list-groups'), 'Native diagnostic boundary token');
$emitNativeDiagnostic = static function ($labels) { echo 'synthetic-native-private-diagnostic'; return $labels; };
add_filter('tag_groups_save_group_labels', $emitNativeDiagnostic);
ob_start();
try {
    $diagnosticResult = callTG('save-group', array('id' => $group1, 'label' => $beforeLateLabel, 'expected_hash' => $diagnosticToken['expected_hash']));
} finally {
    $escapedDiagnostics = ob_get_clean();
    remove_filter('tag_groups_save_group_labels', $emitNativeDiagnostic);
}
check(!is_wp_error($diagnosticResult) && $escapedDiagnostics === '', 'Native output escaped the MCP result boundary');
$escapedCreationToken = success(callTG('list-groups'), 'Escaped term-name token');
$escapedName = 'MCP path \\ Review';
$escapedCreation = success(callTG('create-terms', array('expected_hash' => $escapedCreationToken['expected_hash'], 'taxonomy' => 'post_tag', 'names' => array($escapedName), 'groups' => array($group1))), 'Native escaped term-name creation');
check($escapedCreation['complete'] && get_term($escapedCreation['applied_ids'][0])->name === $escapedName, 'WordPress term creation stripped a literal backslash');
check(is_wp_error(callTG('create-terms', array('expected_hash' => $escapedCreationToken['expected_hash'], 'taxonomy' => 'post_tag', 'names' => array('Must not create a prefix', $escapedName), 'groups' => array($group1)))), 'Mixed duplicate term creation was not prevalidated');
check(!term_exists('Must not create a prefix', 'post_tag'), 'Invalid bulk creation wrote its valid prefix');

$term1 = wp_insert_term('Fixture one', 'post_tag')['term_id'];
$term2 = wp_insert_term('Fixture two', 'post_tag')['term_id'];
$terms = success(callTG('list-terms', array('taxonomy' => 'post_tag')), 'Term discovery');
$byId = array_column($terms['terms'], null, 'id');
check(is_wp_error(callTG('set-term-groups', array('id' => (string) $term1, 'groups' => array($group1), 'expected_hash' => $byId[$term1]['expected_hash']))), 'Numeric string accepted');
check(is_wp_error(callTG('bulk-set-term-groups', array('items' => array(array('id' => $term1, 'groups' => array($group1), 'expected_hash' => $byId[$term1]['expected_hash']), array('id' => $term2, 'groups' => array(99999), 'expected_hash' => $byId[$term2]['expected_hash']))))), 'Invalid bulk accepted');
check((new TagGroups_Term($term1))->get_groups() === array(0), 'Invalid bulk partially wrote');
$result = success(callTG('set-term-groups', array('id' => $term1, 'groups' => array($group1), 'expected_hash' => $byId[$term1]['expected_hash'])), 'Term membership');
check($result['complete'] && (new TagGroups_Term($term1))->has_group($group1), 'Term native persistence');
check(is_wp_error(callTG('set-term-groups', array('id' => $term1, 'groups' => array($group2), 'expected_hash' => $byId[$term1]['expected_hash']))), 'Stale term accepted');
$terms = success(callTG('list-terms', array('taxonomy' => 'post_tag')), 'Bulk partial fixture');
$byId = array_column($terms['terms'], null, 'id');
$denySecond = static function ($caps, $cap, $user, $args) use ($term2) { return $cap === 'edit_term' && (int) ($args[0] ?? 0) === (int) $term2 ? array('do_not_allow') : $caps; };
$revokeSecond = static function ($metaId, $objectId) use ($term1, $denySecond) { if ((int) $objectId === (int) $term1) { add_filter('map_meta_cap', $denySecond, PHP_INT_MAX, 4); } };
add_action('updated_term_meta', $revokeSecond, 10, 2);
try {
    $partial = success(callTG('bulk-set-term-groups', array('items' => array(array('id' => $term1, 'groups' => array($group2), 'expected_hash' => $byId[$term1]['expected_hash']), array('id' => $term2, 'groups' => array($group2), 'expected_hash' => $byId[$term2]['expected_hash'])))), 'Late permission bulk result');
} finally {
    remove_action('updated_term_meta', $revokeSecond);
    remove_filter('map_meta_cap', $denySecond, PHP_INT_MAX);
}
check(!$partial['complete'] && $partial['applied_ids'] === array((int) $term1) && $partial['failed_id'] === (int) $term2, 'Bulk hid successful prefix after authority changed');
check((new TagGroups_Term($term2))->get_groups() === array(0), 'Bulk wrote denied second object');
(new TagGroups_Term($term1))->set_group(array($group1))->save();
$deny = static function ($caps, $cap) { return in_array($cap, array('manage_options', 'edit_pages', 'edit_term'), true) ? array('do_not_allow') : $caps; };
add_filter('map_meta_cap', $deny, PHP_INT_MAX, 2);
check(is_wp_error(callTG('list-groups')), 'Restricted Administrator bypassed group capability');
check(is_wp_error(callTG('get-plugin-settings')), 'Restricted Administrator bypassed settings capability');
remove_filter('map_meta_cap', $deny, PHP_INT_MAX);
$subscriber = wp_insert_user(array('user_login' => 'mcp_subscriber', 'user_pass' => 'fixture-only', 'role' => 'subscriber'));
wp_set_current_user($subscriber);
check(is_wp_error(callTG('get-plugin-settings')), 'Subscriber got settings');
wp_set_current_user(1);
if ($settings['pro']) {
    $externalExcerpt = static function () { return 17; };
    add_filter('excerpt_length', $externalExcerpt, PHP_INT_MAX);
    $lockedSettings = success(callTG('get-plugin-settings'), 'Excerpt override discovery');
    check(in_array('tag_group_excerpt_length', $lockedSettings['locked_fields'], true) && $lockedSettings['effective_values']->tag_group_excerpt_length === 17, 'Native excerpt lock not discovered');
    $beforeExcerpt = get_option('tag_group_excerpt_length');
    check(is_wp_error(callTG('update-plugin-settings', array('expected_hash' => $lockedSettings['expected_hash'], 'settings' => (object) array('tag_group_excerpt_length' => 30, 'tag_group_mouseover' => true)))), 'Native excerpt lock bypassed');
    check(get_option('tag_group_excerpt_length') === $beforeExcerpt, 'Locked setting was written');
    remove_filter('excerpt_length', $externalExcerpt, PHP_INT_MAX);
    $savedGroupCapability = get_option('tag_group_role_edit_groups', 'edit_pages');
    TagGroups_Options::update_option('tag_group_role_edit_groups', 'publish_posts');
    $author = wp_insert_user(array('user_login' => 'group_author', 'user_pass' => 'fixture-only', 'role' => 'author'));
    wp_set_current_user($author);
    $authorGroups = success(callTG('list-groups'), 'Configured Author group access');
    $authorGroups = success(callTG('save-group', array('id' => 0, 'label' => 'Author-created group', 'expected_hash' => $authorGroups['expected_hash'])), 'Configured Author native group save');
    check($authorGroups['complete'], 'Native service ignored configured group capability');
    wp_set_current_user(1);
    TagGroups_Options::update_option('tag_group_role_edit_groups', $savedGroupCapability);
    $savedDisplay = get_option('tag_group_display_groups_under_posts', null);
    delete_option('tag_group_display_groups_under_posts');
    $nativeDefaults = success(callTG('get-plugin-settings'), 'Sparse native display defaults');
    global $tag_group_groups;
    check($nativeDefaults['settings']->tag_group_display_groups_under_posts === array_values(array_map('intval', $tag_group_groups->get_group_ids())), 'MCP invented a group in the native display default');
    if ($savedDisplay !== null) { update_option('tag_group_display_groups_under_posts', $savedDisplay); }
    $groups = success(callTG('list-groups'), 'Pro groups');
    $groups = success(callTG('save-group', array('id' => 0, 'label' => 'Heading', 'is_parent' => true, 'expected_hash' => $groups['expected_hash'])), 'Parent heading');
    check($groups['complete'] && (new TagGroups_Group($groups['id']))->is_parent, 'Parent heading readback');
    $terms = success(callTG('list-terms', array('taxonomy' => 'post_tag')), 'Pro term discovery');
    $byId = array_column($terms['terms'], null, 'id');
    $result = success(callTG('set-term-groups', array('id' => $term1, 'groups' => array($group1, $group2), 'expected_hash' => $byId[$term1]['expected_hash'])), 'Multiple membership');
    check($result['complete'], 'Pro multiple groups');
    $settings = success(callTG('get-plugin-settings'), 'Metabox discovery');
    $settings = success(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_meta_box_taxonomy' => array('post_tag')))), 'Enable metabox');
    check($settings['complete'], 'Metabox enable');
    $postId = wp_insert_post(array('post_title' => 'Grouped fixture', 'post_status' => 'draft'));
    $post = success(callTG('get-post-groups', array('id' => $postId)), 'Post group read');
    $patch = array('id' => $postId, 'taxonomy' => 'post_tag', 'groups' => array(array('group_id' => $group1, 'term_ids' => array((int) $term1))), 'expected_hash' => $post['expected_hash']);
    success(callTG('set-post-groups', $patch + array('dry_run' => true)), 'Post group preview');
    check(wp_get_post_terms($postId, 'post_tag', array('fields' => 'ids')) === array(), 'Post preview wrote');
    $postNotifications = 0;
    $observePostNotification = static function () use (&$postNotifications) { ++$postNotifications; };
    add_action('tag_groups_post_tags_saved', $observePostNotification);
    $post = success(callTG('set-post-groups', $patch), 'Post group save');
    remove_action('tag_groups_post_tags_saved', $observePostNotification);
    check($postNotifications === 1, 'Native grouped-post save notification missing or duplicated');
    check($post['complete'], 'Post native readback');
    check(in_array((int) $term1, wp_get_post_terms($postId, 'post_tag', array('fields' => 'ids')), true), 'Core term relationship not saved');
    $lockUser = wp_insert_user(array('user_login' => 'native_editor_lock', 'user_pass' => 'fixture-only', 'role' => 'editor'));
    update_post_meta($postId, '_edit_lock', time() . ':' . $lockUser);
    $lockedPost = success(callTG('get-post-groups', array('id' => $postId)), 'Locked post discovery');
    check(is_wp_error(callTG('set-post-groups', array('id' => $postId, 'taxonomy' => 'post_tag', 'groups' => array(), 'expected_hash' => $lockedPost['expected_hash'], 'dry_run' => true))), 'WordPress editor lock was bypassed');
    check(in_array((int) $term1, wp_get_post_terms($postId, 'post_tag', array('fields' => 'ids')), true), 'Locked preview changed relationships');
    delete_post_meta($postId, '_edit_lock');
    $secondPost = wp_insert_post(array('post_title' => 'Second propagation fixture', 'post_status' => 'draft'));
    wp_set_post_terms($secondPost, array($term1));
    update_post_meta($secondPost, '_cm_post_terms_' . $group1, ',' . $term1 . ',');
    $terms = success(callTG('list-terms', array('taxonomy' => 'post_tag')), 'Late propagation token');
    $lateTerms = array_column($terms['terms'], null, 'id');
    $propagationIds = TagGroups_Premium_Post_Meta_Tools::get_post_ids_matching_term($term1);
    check(count($propagationIds) === 2, 'Native propagation scope fixture');
    $firstPropagationPost = (int) $propagationIds[0];
    $laterPropagationPost = (int) $propagationIds[1];
    $denyLaterPost = static function ($caps, $cap, $user, $args) use ($laterPropagationPost) { return $cap === 'edit_post' && (int) ($args[0] ?? 0) === $laterPropagationPost ? array('do_not_allow') : $caps; };
    $revokeAfterPost = static function ($metaId, $objectId) use ($firstPropagationPost, $denyLaterPost) { if ((int) $objectId === $firstPropagationPost) { add_filter('map_meta_cap', $denyLaterPost, PHP_INT_MAX, 4); } };
    add_action('added_post_meta', $revokeAfterPost, 10, 2);
    add_action('updated_post_meta', $revokeAfterPost, 10, 2);
    add_action('deleted_post_meta', $revokeAfterPost, 10, 2);
    try {
        $latePropagation = success(callTG('set-term-groups', array('id' => $term1, 'groups' => array(0), 'expected_hash' => $lateTerms[$term1]['expected_hash'])), 'Late propagation result');
    } finally {
        remove_action('added_post_meta', $revokeAfterPost);
        remove_action('updated_post_meta', $revokeAfterPost);
        remove_action('deleted_post_meta', $revokeAfterPost);
        remove_filter('map_meta_cap', $denyLaterPost, PHP_INT_MAX);
    }
    check(!$latePropagation['complete'], 'Late propagation revocation reported success');
    check(get_post_meta($laterPropagationPost, '_cm_post_terms_' . $group1, true) === ',' . $term1 . ',', 'Propagation wrote a post after its capability was revoked');
    (new TagGroups_Term($term1))->set_group(array($group1, $group2))->save();
    wp_delete_post($secondPost, true);
    $terms = success(callTG('list-terms', array('taxonomy' => 'post_tag')), 'Post revocation term fixture');
    $revocationTerms = array_column($terms['terms'], null, 'id');
    success(callTG('set-term-groups', array('id' => $term2, 'groups' => array($group2), 'expected_hash' => $revocationTerms[$term2]['expected_hash'])), 'Second group term fixture');
    $beforePostRevocation = success(callTG('get-post-groups', array('id' => $postId)), 'Post revocation token');
    $denyPostSave = static function ($caps, $cap, $user, $args) use ($postId) { return $cap === 'edit_post' && (int) ($args[0] ?? 0) === (int) $postId ? array('do_not_allow') : $caps; };
    $revokePostSave = static function ($metaId, $objectId) use ($postId, $denyPostSave) { if ((int) $objectId === (int) $postId) { add_filter('map_meta_cap', $denyPostSave, PHP_INT_MAX, 4); } };
    add_action('added_post_meta', $revokePostSave, 10, 2);
    add_action('updated_post_meta', $revokePostSave, 10, 2);
    try {
        $revokedPostSave = callTG('set-post-groups', array('id' => $postId, 'taxonomy' => 'post_tag', 'groups' => array(array('group_id' => $group1, 'term_ids' => array((int) $term1)), array('group_id' => $group2, 'term_ids' => array((int) $term2))), 'expected_hash' => $beforePostRevocation['expected_hash']));
    } finally {
        remove_action('added_post_meta', $revokePostSave);
        remove_action('updated_post_meta', $revokePostSave);
        remove_filter('map_meta_cap', $denyPostSave, PHP_INT_MAX);
    }
    check(is_wp_error($revokedPostSave), 'Post save ignored authority revoked by native metadata hook');
    check(!in_array((int) $term2, wp_get_post_terms($postId, 'post_tag', array('fields' => 'ids')), true), 'Revoked post save changed core relationships');
    delete_post_meta($postId, '_cm_post_terms_' . $group2);
    TagGroups_Transients::delete_transient('tag_groups_post_terms');
    $savedMovePreference = get_option('tag_group_meta_box_change_group', 1);
    TagGroups_Options::update_option('tag_group_meta_box_change_group', 0);
    wp_set_current_user($author);
    $authorPostId = wp_insert_post(array('post_title' => 'Author assignment fixture', 'post_author' => $author, 'post_status' => 'draft'));
    check(!current_user_can('edit_term', $term1) && !current_user_can('edit_pages'), 'Author fixture unexpectedly has taxonomy-management capability');
    $authorPostToken = success(callTG('get-post-groups', array('id' => $authorPostId)), 'Author post discovery');
    $authorPostResult = success(callTG('set-post-groups', array('id' => $authorPostId, 'taxonomy' => 'post_tag', 'expected_hash' => $authorPostToken['expected_hash'], 'groups' => array(array('group_id' => $group1, 'term_ids' => array((int) $term1))))), 'Author native existing-term selection');
    check($authorPostResult['complete'], 'Post assignment incorrectly required global taxonomy management');
    check(is_wp_error(callTG('list-terms', array('taxonomy' => 'post_tag'))), 'Author gained global term management');
    wp_set_current_user(1);
    wp_delete_post($authorPostId, true);
    TagGroups_Options::update_option('tag_group_meta_box_change_group', $savedMovePreference);
    $silentToken = success(callTG('get-post-groups', array('id' => $postId)), 'Silent metadata failure token');
    $failGroupMetadata = static function ($check, $objectId, $key) use ($postId, $group2) { return (int) $objectId === (int) $postId && $key === '_cm_post_terms_' . $group2 ? false : $check; };
    add_filter('update_post_metadata', $failGroupMetadata, PHP_INT_MAX, 3);
    add_filter('add_post_metadata', $failGroupMetadata, PHP_INT_MAX, 3);
    try {
        $silentResult = success(callTG('set-post-groups', array('id' => $postId, 'taxonomy' => 'post_tag', 'expected_hash' => $silentToken['expected_hash'], 'groups' => array(array('group_id' => $group1, 'term_ids' => array((int) $term1)), array('group_id' => $group2, 'term_ids' => array((int) $term2))))), 'Silent native metadata failure result');
    } finally {
        remove_filter('update_post_metadata', $failGroupMetadata, PHP_INT_MAX);
        remove_filter('add_post_metadata', $failGroupMetadata, PHP_INT_MAX);
    }
    check(!$silentResult['complete'], 'Intended cached memberships masked a failed native metadata write');
    wp_set_post_terms($postId, array($term1));
    TagGroups_Premium_Post_Save_Handlers::clear_tag_groups_post_terms();
    $denyPost = static function ($caps, $cap, $user, $args) use ($postId) { return $cap === 'edit_post' && (int) ($args[0] ?? 0) === $postId ? array('do_not_allow') : $caps; };
    $terms = success(callTG('list-terms', array('taxonomy' => 'post_tag')), 'Propagation token');
    $byId = array_column($terms['terms'], null, 'id');
    add_filter('map_meta_cap', $denyPost, PHP_INT_MAX, 4);
    check(is_wp_error(callTG('set-term-groups', array('id' => $term1, 'groups' => array($group2), 'expected_hash' => $byId[$term1]['expected_hash']))), 'Global membership bypassed affected-post denial');
    check((new TagGroups_Term($term1))->has_group($group1), 'Denied propagation changed membership');
    check(is_wp_error(callTG('get-post-groups', array('id' => $postId))), 'Restricted post data exposed');
    remove_filter('map_meta_cap', $denyPost, PHP_INT_MAX);
    $settings = success(callTG('get-plugin-settings'), 'Custom taxonomy settings');
    $settings = success(callTG('update-plugin-settings', array('expected_hash' => $settings['expected_hash'], 'settings' => (object) array('tag_group_taxonomy' => array('post_tag', 'mcp_topic'), 'tag_group_meta_box_taxonomy' => array('post_tag', 'mcp_topic')))), 'Enable custom taxonomy');
    check($settings['complete'], 'Custom taxonomy settings readback');
    $topic = wp_insert_term('Scoped topic', 'mcp_topic')['term_id'];
    (new TagGroups_Term($topic))->set_group(array($group2))->save();
    $post = success(callTG('get-post-groups', array('id' => $postId)), 'Custom taxonomy post token');
    $post = success(callTG('set-post-groups', array('id' => $postId, 'taxonomy' => 'mcp_topic', 'expected_hash' => $post['expected_hash'], 'groups' => array(array('group_id' => $group2, 'term_ids' => array((int) $topic))))), 'Custom taxonomy assignment');
    check($post['complete'] && in_array((int) $term1, wp_get_post_terms($postId, 'post_tag', array('fields' => 'ids')), true), 'Custom taxonomy assignment lost original tags');
    check(in_array((int) $topic, wp_get_post_terms($postId, 'mcp_topic', array('fields' => 'ids')), true), 'Custom taxonomy relationship missing');
    $post = success(callTG('get-post-groups', array('id' => $postId)), 'Hidden-group fixture');
    TagGroups_Options::update_option('tag_group_meta_box_include', array($group2));
    $post = success(callTG('get-post-groups', array('id' => $postId)), 'Restricted metabox token');
    $post = success(callTG('set-post-groups', array('id' => $postId, 'taxonomy' => 'post_tag', 'expected_hash' => $post['expected_hash'], 'groups' => array())), 'Preserve excluded metabox group');
    check($post['complete'] && in_array((int) $term1, wp_get_post_terms($postId, 'post_tag', array('fields' => 'ids')), true), 'Hidden metabox membership was removed');
    TagGroups_Options::update_option('tag_group_meta_box_include', array());
    $savedLanguages = get_option('tag_group_group_languages', array());
    update_option('tag_group_group_languages', array('mcp_fixture'));
    update_option('term_group_labels_mcp_fixture', array($group1 => 'Translated fixture'));
    $translatedDelete = success(callTG('get-group', array('id' => $group1)), 'Translation-aware deletion token');
    update_option('term_group_labels_mcp_fixture', array($group1 => 'Changed translated fixture'));
    check(is_wp_error(callTG('delete-group', array('id' => $group1, 'expected_hash' => $translatedDelete['expected_hash'], 'dry_run' => true))), 'Deletion ignored a changed translated label');
    update_option('tag_group_group_languages', $savedLanguages);
    delete_option('term_group_labels_mcp_fixture');
    $groups = success(callTG('get-group', array('id' => $group1)), 'Pro deletion token');
    add_filter('map_meta_cap', $denyPost, PHP_INT_MAX, 4);
    check(is_wp_error(callTG('delete-group', array('id' => $group1, 'expected_hash' => $groups['expected_hash']))), 'Group deletion bypassed affected-post capability');
    remove_filter('map_meta_cap', $denyPost, PHP_INT_MAX);
    update_post_meta($postId, '_cm_post_terms_' . $group1, ',123456,');
    check(is_wp_error(callTG('delete-group', array('id' => $group1, 'expected_hash' => $groups['expected_hash'], 'dry_run' => true))), 'Deletion ignored stale affected memberships');
    update_post_meta($postId, '_cm_post_terms_' . $group1, ',' . $term1 . ',');
    $groups = success(callTG('get-group', array('id' => $group1)), 'Fresh deletion membership token');
    success(callTG('delete-group', array('id' => $group1, 'expected_hash' => $groups['expected_hash'], 'dry_run' => true)), 'Pro deletion preview');
    check((new TagGroups_Group($group1))->exists(), 'Deletion preview removed group');
    $deleted = success(callTG('delete-group', array('id' => $group1, 'expected_hash' => $groups['expected_hash'])), 'Scoped native Pro deletion');
    check($deleted['complete'] && !(new TagGroups_Group($group1))->exists(), 'Pro group deletion incomplete');
    check(get_post_meta($postId, '_cm_post_terms_' . $group1, true) === '', 'Deleted group post metadata remains');
    check(in_array((int) $topic, wp_get_post_terms($postId, 'mcp_topic', array('fields' => 'ids')), true), 'Deletion lost unrelated taxonomy');

} else {
    check(wp_get_ability('publishpress-tag-groups/get-post-groups') === null, 'Pro ability registered in Free');
}
$scopeFile = getenv('TAGGROUPS_MCP_CAPABILITIES_FILE');
if ($scopeFile && is_readable($scopeFile)) {
    require_once $scopeFile;
    update_option('cme_capabilities_application_password_capabilities', true);
    PP_Capabilities_Application_Password_Capabilities::instance();
    $scopeUser = is_multisite() ? wp_insert_user(['user_login' => 'mcp_scope_admin', 'user_pass' => 'synthetic-only', 'role' => 'administrator']) : 1;
    wp_set_current_user($scopeUser);
    $password = WP_Application_Passwords::create_new_application_password($scopeUser, ['name' => 'Tag Groups isolated scope']);
    check(!is_wp_error($password), 'Scoped password creation failed');
    $uuid = $password[1]['uuid'];
    $key = pp_capabilities_application_password_storage_key($scopeUser, $uuid);
    update_option(pp_capabilities_application_password_option_name(), [$key => ['user_id' => $scopeUser, 'uuid' => $uuid, 'denied' => ['manage_options' => false, 'edit_pages' => false]]]);
    do_action('application_password_did_authenticate', get_userdata($scopeUser), $password[1]);
    check(is_wp_error(callTG('get-plugin-settings')), 'Application-password settings scope bypassed');
    check(is_wp_error(callTG('list-groups')), 'Application-password management scope bypassed');
    update_option(pp_capabilities_application_password_option_name(), []);
    update_option('cme_capabilities_application_password_capabilities', false);
    WP_Application_Passwords::delete_application_password($scopeUser, $uuid);
    unset($password);
    wp_set_current_user(1);
}
foreach (wp_get_abilities() as $ability) {
    if (strpos($ability->get_name(), 'publishpress-tag-groups/') === 0) {
        $meta = $ability->get_meta();
        check(empty($meta['public']) && empty($meta['show_in_rest']) && empty($meta['mcp']['public']), 'Ability exposed through public/default discovery');
    }
}
define('TAG_GROUPS_REST_API_EDITABLE', false);
$lockedGroups = success(callTG('list-groups'), 'Explicit lock read access');
check(is_wp_error(callTG('save-group', array('id' => 0, 'label' => 'Blocked by configuration', 'expected_hash' => $lockedGroups['expected_hash']))), 'Explicit native configuration lock bypassed');
echo 'Tag Groups ' . ($settings['pro'] ? 'pro' : 'free') . ': ' . $assertions . " assertions passed.\n";
