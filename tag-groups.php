<?php

/**
 * Plugin Name: PublishPress Tag Groups Free
 * Plugin URI: https://wordpress.org/plugins/tag-groups/
 * Description: PublishPress Tag Groups allows you to organize your WordPress taxonomy terms and show them in clouds, tabs, accordions, tables, lists and much more.
 * Author: PublishPress
 * Author URI: https://publishpress.com
 * Version: 3.0.0
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: tag-groups
 * Domain Path: /languages
 * Requires at least: 5.5
 * Requires PHP: 7.2.5
 *
 * @package     PublishPress\TagGroups
 * @author      PublishPress
 * @copyright   Copyright (c) 2024, PublishPress
 * @license     GPL-3.0-or-later
 */

// keep the following line for automatic processing
// define( "CM_TGP_KERNL_UUID", '' );

defined('ABSPATH') || exit;

$includeFileRelativePath = '/publishpress/instance-protection/include.php';
if (file_exists(__DIR__ . '/lib/vendor' . $includeFileRelativePath)) {
    require_once __DIR__ . '/lib/vendor' . $includeFileRelativePath;
} elseif (file_exists(__DIR__ . '/vendor' . $includeFileRelativePath)) {
    require_once __DIR__ . '/vendor' . $includeFileRelativePath;
}

if (class_exists('PublishPressInstanceProtection\Config')) {
    $pluginCheckerConfig = new PublishPressInstanceProtection\Config();
    $pluginCheckerConfig->pluginSlug = 'tag-groups';
    $pluginCheckerConfig->pluginName = 'PublishPress Tag Groups';
    $pluginCheckerConfig->pluginFolder = 'tag-groups';
    $pluginChecker = new PublishPressInstanceProtection\InstanceChecker($pluginCheckerConfig);
}

$bundledTranslationsPath = '/publishpress/bundled-translations/core/include.php';
if (file_exists(__DIR__ . '/lib/vendor' . $bundledTranslationsPath)) {
    require_once __DIR__ . '/lib/vendor' . $bundledTranslationsPath;
} elseif (file_exists(__DIR__ . '/vendor' . $bundledTranslationsPath)) {
    require_once __DIR__ . '/vendor' . $bundledTranslationsPath;
}

add_action('plugins_loaded', function () {

    if (class_exists('PublishPress\BundledTranslations\BundledTranslations')) {
        $bundledTranslations = new PublishPress\BundledTranslations\BundledTranslations('tag-groups', __DIR__ . '/languages', __FILE__);
        $bundledTranslations->init();
    }
}, 5);
$wordpressVersionNoticesPath = '/publishpress/wordpress-version-notices/src/include.php';
if (file_exists(__DIR__ . '/lib/vendor' . $wordpressVersionNoticesPath)) {
    require_once __DIR__ . '/lib/vendor' . $wordpressVersionNoticesPath;
} elseif (file_exists(__DIR__ . '/vendor' . $wordpressVersionNoticesPath)) {
    require_once __DIR__ . '/vendor' . $wordpressVersionNoticesPath;
}

if (!function_exists('tag_groups_free_plugin_basenames')) {
    /**
     * Return the supported free-plugin entry paths.
     *
     * The WordPress.org folder remains the canonical installation path. The
     * branded folder is accepted for compatibility with pre-release packages.
     *
     * @return string[]
     */
    function tag_groups_free_plugin_basenames()
    {
        return [
            'publishpress-tag-groups/tag-groups.php',
            'tag-groups/tag-groups.php',
        ];
    }
}

if (!defined('TAG_GROUPS_PLUGIN_IS_FREE')) {
    if (in_array(plugin_basename(__FILE__), tag_groups_free_plugin_basenames(), true)) {
        define('TAG_GROUPS_PLUGIN_IS_FREE', true);
    } else {
    // Don't define the constant! If the premium plugin runs earlier, the free plugin still needs to define it.
    }
}
if (!defined('TAG_GROUPS_PLUGIN_IS_KERNL')) {
    if (defined('CM_TGP_KERNL_UUID') && CM_TGP_KERNL_UUID != '' || defined('CM_TGP_BETA_PLUGIN_UUID') && CM_TGP_BETA_PLUGIN_UUID != '') {
        define('TAG_GROUPS_PLUGIN_IS_KERNL', true);
    } else {
        define('TAG_GROUPS_PLUGIN_IS_KERNL', false);
    }
}

if (!defined('TAG_GROUPS_PLUGIN_BASENAME')) {
    define('TAG_GROUPS_FILE', __FILE__);
/**
     * The plugin's relative path (starting below the plugin directory), including the name of this file.
     */
    define("TAG_GROUPS_PLUGIN_BASENAME", plugin_basename(__FILE__));
/**
     * The required minimum version of WordPress.
     */
    define("TAG_GROUPS_MINIMUM_VERSION_WP", "4.9");
/**
     * Comma-separated list of default themes that come bundled with this plugin.
     */
    define("TAG_GROUPS_BUILT_IN_THEMES", "delta,base,ui-gray,ui-lightness,ui-darkness,blitzer,aristo");
/**
     * The theme that is selected by default. Must be among TAG_GROUPS_BUILT_IN_THEMES.
     */
    define("TAG_GROUPS_STANDARD_THEME", "delta");
/**
     * The default number of groups on one page on the edit group screen.
     */
    define("TAG_GROUPS_ITEMS_PER_PAGE", 20);
/**
     * This plugin's last piece of the path, i.e. basically the plugin's name
     */
    define("TAG_GROUPS_PLUGIN_RELATIVE_PATH", basename(dirname(__FILE__)));
/**
     * This plugin's absolute path on this server - starting from root.
     */
    define("TAG_GROUPS_PLUGIN_ABSOLUTE_PATH", dirname(__FILE__));
    if (!defined('TAG_GROUPS_LIB_VENDOR_PATH')) {
        define('TAG_GROUPS_LIB_VENDOR_PATH', __DIR__ . '/lib/vendor');
    }
}

if (defined('TAG_GROUPS_LOADED_BY_PRO') && TAG_GROUPS_LOADED_BY_PRO && defined('TAG_GROUPS_PRO_ABSPATH')) {
    if (!defined('TAG_GROUPS_PREMIUM_PLUGIN_ABSOLUTE_PATH')) {
        define('TAG_GROUPS_PREMIUM_PLUGIN_ABSOLUTE_PATH', TAG_GROUPS_PRO_ABSPATH . '/premium');
    }
    if (!defined('TAG_GROUPS_PREMIUM_PLUGIN_RELATIVE_PATH')) {
        define('TAG_GROUPS_PREMIUM_PLUGIN_RELATIVE_PATH', 'premium');
    }
    if (!defined('TAG_GROUPS_PREMIUM_CACHE_LIFETIME')) {
        define('TAG_GROUPS_PREMIUM_CACHE_LIFETIME', 600);
    }
    if (!defined('TAG_GROUPS_PREMIUM_MB_RELOAD_TAGS')) {
        define('TAG_GROUPS_PREMIUM_MB_RELOAD_TAGS', 300000);
    }
    if (!defined('TAG_GROUPS_PREMIUM_PLUGIN_URL') && defined('TAG_GROUPS_PRO_URL')) {
        define('TAG_GROUPS_PREMIUM_PLUGIN_URL', TAG_GROUPS_PRO_URL . '/premium');
    }
}

$libAutoloadPath = TAG_GROUPS_LIB_VENDOR_PATH . '/autoload.php';
if (file_exists($libAutoloadPath)) {
    require_once $libAutoloadPath;
}

$autoloadPath = TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

/**
 * Make scope of $tag_groups_loader global for wp-cli
 */
global  $tag_groups_loader ;
require_once dirname(__FILE__) . '/include/class.loader.php';
$tag_groups_loader = new TagGroups_Loader(TAG_GROUPS_PLUGIN_ABSOLUTE_PATH);
$tag_groups_loader->require_classes();
if (!function_exists('tag_groups_init')) {
/**
     * Do all initial stuff: register hooks, check dependencies
     *
     *
     * @param  void
     * @return void
     */
    function tag_groups_init()
    {
        global $tag_groups_loader ;
        if (!in_array(plugin_basename(__FILE__), tag_groups_free_plugin_basenames(), true)) {
        /**
                     *  TGP-Codester or TGP-Freemius
                     */

            if (defined('TAG_GROUPS_PLUGIN_IS_FREE') && TAG_GROUPS_PLUGIN_IS_FREE) {
/**
                 * The free version is also active.
                 */
                /**
                 * Make sure we don't delete data by removing the base plugin by returning data removal to opt-in:
                 * Set the option to OFF and keep, because removing the plugin might only happen later.
                 */
                update_option('tag_group_reset_when_uninstall', 0);
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
                deactivate_plugins(tag_groups_free_plugin_basenames(), true);
// add the hook directly
                add_action('admin_notices', function () {

                    echo  '<div class="notice notice-info is-dismissible"><p>' . esc_html__('The free PublishPress Tag Groups plugin cannot be active together with PublishPress Tag Groups Pro.', 'tag-groups') . ' <a href="https://publishpress.com/tag-groups/" target="_blank" style="text-decoration: none;" title="' . esc_attr__('more information', 'tag-groups') . '"><span class="dashicons dashicons-editor-help"></span></a></p></div><div clear="all" /></div>' ;
                });
/**
                 * Remove the misleading "Plugin activated" messaage
                 */
                unset($_GET['activate']);
            }
        }
        // URL must be defined after WP has finished loading its settings

        if (!defined('TAG_GROUPS_PLUGIN_URL')) {
            define("TAG_GROUPS_PLUGIN_URL", plugins_url('', __FILE__));
// start all initializations, registration of hooks, housekeeping, menus, ...
            $tag_groups_loader->set_version();
            $tag_groups_loader->check_preconditions();
            $tag_groups_loader->provide_globals();
            $tag_groups_loader->add_hooks();
            $tag_groups_loader->register_shortcodes_and_blocks();
            $tag_groups_loader->register_REST_API();
            $tag_groups_loader->register_CRON();
            if (is_admin()) {
                // Only load free-only admin features if not running inside Pro
                if (!defined('TAG_GROUPS_SKIP_VERSION_NOTICES') || !TAG_GROUPS_SKIP_VERSION_NOTICES) {
                    require_once(TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/includes-core/TagGroupsCoreAdmin.php');

                    // Keep the former namespace available for third-party integrations during the rebrand.
                    if (!class_exists('TaxoPress\\TagGroups\\TagGroupsCoreAdmin', false)) {
                        class_alias(
                            \PublishPress\TagGroups\TagGroupsCoreAdmin::class,
                            'TaxoPress\\TagGroups\\TagGroupsCoreAdmin'
                        );
                    }

                    new \PublishPress\TagGroups\TagGroupsCoreAdmin();
                    require_once(TAG_GROUPS_PLUGIN_ABSOLUTE_PATH . '/includes-core/TagGroupsReviews.php');
                }
            }
        }
    }

    add_action('plugins_loaded', 'tag_groups_init');
    register_activation_hook(__FILE__, array( 'TagGroups_Activation_Deactivation', 'on_activation' ));
}

/**
 * aliases for common functions, for backwards compatibility
 */
require_once 'aliases.php';
/**
 * guess what - the end
 */
