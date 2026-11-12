<?php defined('ABSPATH') || exit;
?>
<div class="tg_settings_tabs_content">

  <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
    <?php echo wp_nonce_field('tag-groups-multilingual', 'tag-groups-multilingual-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <p>
      <input type="checkbox" id="tag_group_multilingual_sync_groups" name="tag_group_multilingual_sync_groups" autocomplete="off" value="1" <?php if ($tag_group_multilingual_sync_groups) :
            ?> checked<?php 
                                                                                                                                            endif; ?> />&nbsp;
      <label for="tg_server_side_render"><?php _e('Keep groups of translations in sync', 'tag-groups') ?></label>
      <span class="dashicons dashicons-editor-help chatty-mango-help-icon" title="<?php _e('If you activate this option, we try to sync the groups across all language versions of a tag when you save it. Deactivate it to set groups independently.', 'tag-groups') ?>"></span>
    </p>

    <p>&nbsp;</p>
    <input type="hidden" name="tg_action" value="multilingual">
    <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Settings', 'tag-groups') ?>" id="submitbutton" />
  </form>

</div>
