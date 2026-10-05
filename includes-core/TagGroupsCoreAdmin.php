<?php

namespace PublishPress\TagGroups;

/**
 * Class TagGroupsCoreAdmin
 *
 * @package PublishPress\TagGroups
 */
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- WordPress naming conventions for hook callbacks
class TagGroupsCoreAdmin
{
    public function __construct()
    {
        if (! self::should_show_free_upsells()) {
            return;
        }

        if (is_admin()) {
            if (class_exists('PublishPress\WordpressVersionNotices\Module\TopNotice\Module')) {
                add_filter(
                    \PublishPress\WordpressVersionNotices\Module\TopNotice\Module::SETTINGS_FILTER,
                    [$this, 'configure_top_notice']
                );
            }

            if (class_exists('PublishPress\WordpressVersionNotices\Module\MenuLink\Module')) {
                add_filter(
                    \PublishPress\WordpressVersionNotices\Module\MenuLink\Module::SETTINGS_FILTER,
                    [$this, 'configure_menu_link']
                );
            }
        }

        add_action('tag_groups_settings_right_sidebar', [$this, 'tag_groups_admin_advertising_sidebar_banner']);
    }

    /**
     * Whether free-only upgrade UI should be registered.
     *
     * @return bool
     */
    private static function should_show_free_upsells()
    {
        if (defined('TAG_GROUPS_SKIP_VERSION_NOTICES') && TAG_GROUPS_SKIP_VERSION_NOTICES) {
            return false;
        }

        if (defined('TAG_GROUPS_PLUGIN_IS_FREE') && ! TAG_GROUPS_PLUGIN_IS_FREE) {
            return false;
        }

        if (class_exists('TagGroups_Utilities') && \TagGroups_Utilities::is_premium_plan()) {
            return false;
        }

        return true;
    }

    /**
     * Configure the top notice banner settings
     *
     * @param array $settings Existing settings
     * @return array Modified settings
     */
    public function configure_top_notice($settings)
    {
        $settings['publishpress-tag-groups'] = [
            /* translators: %1$s and %2$s are opening and closing anchor tags for the upgrade link */
            'message' => esc_html__('You\'re using PublishPress Tag Groups Free. The Pro version has more features and support. %1$sUpgrade to Pro%2$s', 'tag-groups'),
            'link'    => 'https://publishpress.com/links/tag-groups-banner',
            'screens' => [
                ['base' => 'toplevel_page_tag-groups-settings', 'id' => 'toplevel_page_tag-groups-settings'],
                ['base' => 'tag-groups_page_tag-groups-settings-taxonomies', 'id' => 'tag-groups_page_tag-groups-settings-taxonomies'],
                ['base' => 'tag-groups_page_tag-groups-settings-front-end', 'id' => 'tag-groups_page_tag-groups-settings-front-end'],
                ['base' => 'tag-groups_page_tag-groups-settings-back-end', 'id' => 'tag-groups_page_tag-groups-settings-back-end'],
                ['base' => 'tag-groups_page_tag-groups-settings-tools', 'id' => 'tag-groups_page_tag-groups-settings-tools'],
                ['base' => 'tag-groups_page_tag-groups-settings-about', 'id' => 'tag-groups_page_tag-groups-settings-about'],
                ['base' => 'posts_page_tag-groups_post', 'id' => 'posts_page_tag-groups_post'],
                ['base' => 'admin_page_tag-groups-settings-first-steps', 'id' => 'admin_page_tag-groups-settings-first-steps'],
                ['base' => 'admin_page_tag-groups-settings-setup-wizard', 'id' => 'admin_page_tag-groups-settings-setup-wizard'],
            ]
        ];

        return $settings;
    }

    /**
     * Configure the menu link settings
     *
     * @param array $settings Existing settings
     * @return array Modified settings
     */
    public function configure_menu_link($settings)
    {
        $settings['publishpress-tag-groups'] = [
            'parent' => 'tag-groups-settings',
            'label'  => __('Upgrade to Pro', 'tag-groups'),
            'link'   => 'https://publishpress.com/links/tag-groups-sidebar',
        ];

        return $settings;
    }

    public function tag_groups_admin_advertising_sidebar_banner()
    {
        ?>

        <div class="tag-groups-advertisement-right-sidebar">
            <div id="postbox-container-1" class="postbox-container">
            <div class="meta-box-sortables">
                <div class="advertisement-box-content postbox">
                    <div class="postbox-header">
                        <h3 class="advertisement-box-header hndle is-non-sortable">
                            <span><?php echo esc_html__('Upgrade to PublishPress Tag Groups Pro', 'tag-groups'); ?></span>
                        </h3>
                    </div>

                    <div class="inside">
                        <p><?php echo esc_html__('Enhance the power of PublishPress Tag Groups with the Pro version:', 'tag-groups'); ?>
                        </p>
                        <ul>
                            <li><?php echo esc_html__('21 Shortcodes', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('18 Gutenberg Blocks', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Table Tag Cloud', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Simple Tag Cloud', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Shuffle Box', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Caching', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Multiple Groups Per Tag', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Tag Cloud Search', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Toggle Post Filter', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Dynamic Post Filter', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Post List', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Tag Meta Box', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Post Tags in Groups', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Permissions', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Default Groups', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('WooCommerce Attributes', 'tag-groups'); ?></li>
                            <li><?php echo esc_html__('Parent Group Level', 'tag-groups'); ?></li>
                        </ul>
                        <div class="upgrade-btn">
                            <a href="https://publishpress.com/tag-groups/" target="_blank" rel="noopener noreferrer"><?php echo esc_html__('Upgrade to Pro', 'tag-groups'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="advertisement-box-content postbox">
                    <div class="postbox-header">
                        <h3 class="advertisement-box-header hndle is-non-sortable">
                            <span><?php echo esc_html__('Need PublishPress Tag Groups Support?', 'tag-groups'); ?></span>
                        </h3>
                    </div>

                    <div class="inside">
                        <p><?php echo esc_html__('If you need help or have a new feature request, let us know.', 'tag-groups'); ?>
                            <a class="advert-link" href="https://wordpress.org/support/plugin/tag-groups/" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html__('Request Support', 'tag-groups'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="linkIcon">
                                    <path
                                        d="M18.2 17c0 .7-.6 1.2-1.2 1.2H7c-.7 0-1.2-.6-1.2-1.2V7c0-.7.6-1.2 1.2-1.2h3.2V4.2H7C5.5 4.2 4.2 5.5 4.2 7v10c0 1.5 1.2 2.8 2.8 2.8h10c1.5 0 2.8-1.2 2.8-2.8v-3.6h-1.5V17zM14.9 3v1.5h3.7l-6.4 6.4 1.1 1.1 6.4-6.4v3.7h1.5V3h-6.3z"
                                    ></path>
                                </svg>
                            </a>
                        </p>
                        <p>
                        <?php echo esc_html__('Detailed documentation is also available on the plugin website.', 'tag-groups'); ?>
                            <a class="advert-link" href="https://publishpress.com/tag-groups/" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html__('View Knowledge Base', 'tag-groups'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="linkIcon">
                                    <path
                                        d="M18.2 17c0 .7-.6 1.2-1.2 1.2H7c-.7 0-1.2-.6-1.2-1.2V7c0-.7.6-1.2 1.2-1.2h3.2V4.2H7C5.5 4.2 4.2 5.5 4.2 7v10c0 1.5 1.2 2.8 2.8 2.8h10c1.5 0 2.8-1.2 2.8-2.8v-3.6h-1.5V17zM14.9 3v1.5h3.7l-6.4 6.4 1.1 1.1 6.4-6.4v3.7h1.5V3h-6.3z"
                                    ></path>
                                </svg>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <?php
    }
}
