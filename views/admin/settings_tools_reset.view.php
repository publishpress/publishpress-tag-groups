<?php defined('ABSPATH') || exit;
?>
<div class="tg_settings_tabs_content">
  <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
    <?php echo wp_nonce_field('tag-groups-reset', 'tag-groups-reset-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <p><?php _e('Use this button to delete all tag groups and assignments. Your tags will not be changed. Check the checkbox to confirm.', 'tag-groups') ?>
    <span class="dashicons dashicons-editor-help chatty-mango-help-icon" title="<?php _e('Please keep in mind that the tag assignments cannot be recovered by the export/import function.', 'tag-groups') ?>"></span></p>
    <input type="checkbox" id="reset_ok" name="ok" autocomplete="off" value="yes" />
    <label for="reset_ok"><?php _e('I know what I am doing.', 'tag-groups') ?></label>
    <input type="hidden" id="action" name="tg_action" value="reset">
    <p><input class="button-primary" type="submit" name="delete" value="<?php
    _e("Delete Groups", "tag-groups") ?>" id="submitbutton" />
  </form>
  <p>&nbsp;</p>
  <h2><?php _e('Delete Settings and Groups', 'tag-groups') ?></h2>
  <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
    <?php echo wp_nonce_field('tag-groups-uninstall', 'tag-groups-uninstall-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <p>
      <input type="checkbox" id="data_ok" name="ok" autocomplete="off" value="yes"
      <?php if ($tag_group_reset_when_uninstall) :
            ?> checked<?php 
      endif; ?> />
      <label for="data_ok"><?php _e("Delete all groups and settings when uninstalling the plugin.", "tag-groups") ?></label>
    </p>
    <input type="hidden" id="action" name="tg_action" value="uninstall">
    <input class="button-primary" type="submit" name="save" value="<?php _e("Save Settings", "tag-groups") ?>" id="submitbutton" />
  </form>
</div>
