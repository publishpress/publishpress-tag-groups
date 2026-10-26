<div class="tg_settings_tabs_content">

  <p>&nbsp;</p>
  <form method="POST" action="">
    <input type="hidden" name="task-set-name" value="Migration" />
    <input type="hidden" name="process-tasks" value="<?php echo esc_attr($tasks_migration) ?>" />
    <?php wp_nonce_field('tag-groups-first-aid-nonce', 'nonce') ?>
    <p><?php _e('Migrate tags to the new format of Tag Groups.', 'tag-groups') ?><button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="migrate" aria-expanded="false" aria-controls="tg-help-migrate"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Migrate', 'tag-groups'))); ?></span></button><span id="tg-help-migrate" class="chatty-mango-help-container chatty-mango-help-container-migrate" style="display:none;"><?php _e('The plugin automatically migrates tags to the new format and tries to keep track of changes. There may, however, be cases where other components made unnoticed changes. You might also need to manually migrate tags after you enabled a taxonomy that already contained untracked tags.', 'tag-groups') ?></span></p>
    <input class="button-primary" type="submit" name="update" value="<?php _e('Migrate', 'tag-groups') ?>" id="submitbutton_migrate" />
  </form>

  <p>&nbsp;</p>
  <form method="POST" action="">
    <input type="hidden" name="task-set-name" value="Maintenance" />
    <input type="hidden" name="process-tasks" value="<?php echo esc_attr($tasks_maintenance) ?>" />
    <?php wp_nonce_field('tag-groups-first-aid-nonce', 'nonce') ?>
    <p><?php _e('Running the maintenance can solve issues that you encounter during filtering by tag groups.', 'tag-groups') ?></p>
    <input class="button-primary" type="submit" name="maintenance" value="<?php _e('Maintenance', 'tag-groups') ?>" id="submitbutton_maintenance" />
  </form>

  <?php if ($tag_group_show_filter_tags) : ?>
    <p>&nbsp;</p>
    <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
      <p><?php _e("Reset the filter on the tags page to show all tags.", 'tag-groups') ?><button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="reset-tag-filter" aria-expanded="false" aria-controls="tg-help-reset-tag-filter"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Reset Tag Filter', 'tag-groups'))); ?></span></button><span id="tg-help-reset-tag-filter" class="chatty-mango-help-container chatty-mango-help-container-reset-tag-filter" style="display:none;"><?php _e('Use it if a JavaScript error disables the filter menu. Note that it is still recommended to find out the cause of the error.', 'tag-groups') ?></span></p>
        <?php echo wp_nonce_field('tag-groups-reset-tag-filter', 'tag-groups-reset-tag-filter-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
      <input type="hidden" name="tg_action" value="reset-tag-filter">
      <input class="button-primary" type="submit" name="reset" value="<?php _e('Reset Tag Filter', 'tag-groups') ?>" id="submitbutton_reset_tag_filter" />
    </form>
  <?php endif; ?>
</div>
