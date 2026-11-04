<?php defined('ABSPATH') || exit;
?>
<div class="tg_settings_tabs_content">

  <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
    <?php echo wp_nonce_field('tag-groups-backend', 'tag-groups-backend-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <p>
      <input type="checkbox" id="tg_filter_posts" name="filter_posts" autocomplete="off" value="1"<?php if ($show_filter_posts) :
            ?> checked<?php 
                                                                                                  endif; ?>/>&nbsp;
      <label for="tg_filter_posts"><?php _e('Display filter on the "Posts" screen.', 'tag-groups') ?></label>
      <span class="dashicons dashicons-editor-help chatty-mango-help-icon" title="<?php _e('Add a pull-down menu to the filters above the list of posts. If you filter posts by tag groups, then only items will be shown that have tags (terms) in that particular group. This feature can be turned off so that the menu won\'t obstruct your screen if you use a high number of groups. May not work with all taxonomies.', 'tag-groups') ?>"></span>
    </p>

    <p>
      <input type="checkbox" id="tg_filter_tags" name="filter_tags" autocomplete="off" value="1"<?php if ($show_filter_tags) :
            ?> checked<?php 
                                                                                                endif; ?>/>&nbsp;
      <label for="tg_filter_tags"><?php _e('Display filter on the "Tags" screen.', 'tag-groups') ?></label>
      <span class="dashicons dashicons-editor-help chatty-mango-help-icon" title="<?php _e('Add a filter to the list of tags. Disable it here if it conflicts with other plugins or themes.', 'tag-groups') ?>"></span>
    </p>

    <p>&nbsp;</p>
    <input type="hidden" name="tg_action" value="backend">
    <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Settings', 'tag-groups') ?>" id="submitbutton" />
  </form>

</div>
