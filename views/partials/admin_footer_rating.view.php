<?php $rating_stars = '<span class="screen-reader-text">' . esc_html__('five-star', 'tag-groups') . '</span>' . str_repeat('<span class="dashicons dashicons-star-filled" aria-hidden="true"></span>', 5); ?>
<div class="pressshack-admin-wrapper tag-groups-admin-footer">
    <footer>
        <div class="tag-groups-rating">
            <a href="https://wordpress.org/support/plugin/tag-groups/reviews/#new-post" target="_blank" rel="noopener noreferrer">
                <?php
                printf(
                    /* translators: %1$s: plugin name, %2$s: five-star rating icons. */
                    esc_html__('If you like %1$s please leave us a %2$s rating. Thank you!', 'tag-groups'),
                    '<strong>' . esc_html__('PublishPress Tag Groups', 'tag-groups') . '</strong>',
                    $rating_stars // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted dashicon markup generated above.
                );
                ?><span class="screen-reader-text"> <?php esc_html_e('(opens in a new tab)', 'tag-groups'); ?></span>
            </a>
        </div>
        <hr>
        <nav aria-label="<?php echo esc_attr__('PublishPress Tag Groups resources', 'tag-groups'); ?>">
            <ul>
                <li><a href="https://publishpress.com/tag-groups/" target="_blank" rel="noopener noreferrer"><?php esc_html_e('About', 'tag-groups'); ?><span class="screen-reader-text"> <?php esc_html_e('(opens in a new tab)', 'tag-groups'); ?></span></a></li>
                <li><a href="https://publishpress.com/tag-groups/" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Documentation', 'tag-groups'); ?><span class="screen-reader-text"> <?php esc_html_e('(opens in a new tab)', 'tag-groups'); ?></span></a></li>
                <li><a href="https://publishpress.com/publishpress-support/" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Contact', 'tag-groups'); ?><span class="screen-reader-text"> <?php esc_html_e('(opens in a new tab)', 'tag-groups'); ?></span></a></li>
            </ul>
        </nav>
        <div class="tag-groups-publishpress-logo">
            <a href="https://publishpress.com/" target="_blank" rel="noopener noreferrer">
                <img src="<?php echo esc_url(TAG_GROUPS_PLUGIN_URL . '/assets/images/publishpress-logo.png'); ?>" alt="<?php echo esc_attr(__('PublishPress', 'tag-groups') . ' ' . __('(opens in a new tab)', 'tag-groups')); ?>">
            </a>
        </div>
    </footer>
</div>
