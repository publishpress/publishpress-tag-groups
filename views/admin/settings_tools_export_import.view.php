<div class="tg_settings_tabs_content">
  <p>
    <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
      <h3><?php _e('Export', 'tag-groups') ?><button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="export" aria-expanded="false" aria-controls="tg-help-export"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Export', 'tag-groups'))); ?></span></button></h3>
      <span id="tg-help-export" class="chatty-mango-help-container chatty-mango-help-container-export" style="display:none;"><?php _e('Use this button to export all Tag Groups settings and groups and all terms that are assigned to a group into files.', 'tag-groups') ?> <?php _e("You can import both files separately. Category hierarchy won't be saved. When you restore terms that were deleted, they receive new IDs and you must assign them to posts again. Exporting cannot substitute a backup.", 'tag-groups') ?></span>
      <input type="hidden" name="tag-groups-export-nonce" id="tag-groups-export-nonce" value="<?php echo esc_attr(wp_create_nonce('tag-groups-export')) ?>" />
      <input type="hidden" id="tg_action_export" name="tg_action" value="export">
      <p><input class="button-primary" type="submit" name="export" value="<?php _e('Export Files', 'tag-groups') ?>" id="submitbutton_export" /></p>
    </form>
  </p>
  <p>&nbsp;</p>
  <p>
    <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>" enctype="multipart/form-data">
      <h3><?php _e('Import', 'tag-groups') ?><button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="import" aria-expanded="false" aria-controls="tg-help-import"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Import', 'tag-groups'))); ?></span></button></h3>
      <span id="tg-help-import" class="chatty-mango-help-container chatty-mango-help-container-import" style="display:none;"><?php _e('Below you can import previously exported settings/groups or terms from a file.', 'tag-groups') ?> <?php _e('It is recommended to back up the database of your site before proceeding.', 'tag-groups') ?> <?php _e('Please import the settings before the terms.', 'tag-groups') ?></span>
      <input type="hidden" name="tag-groups-import-nonce" id="tag-groups-import-nonce" value="<?php echo esc_attr(wp_create_nonce('tag-groups-import')) ?>" />
      <input type="hidden" id="tg_action_import" name="tg_action" value="import">
      <p><label for="settings_file" class="screen-reader-text"><?php esc_html_e('Import File', 'tag-groups') ?></label><input type="file" id="settings_file" name="settings_file"></p>
      <p><input class="button-primary" type="submit" name="import" value="<?php _e('Import File', 'tag-groups') ?>" id="submitbutton_import" /></p>
    </form>
  </p>
</div>
