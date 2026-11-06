<div class="tg_settings_tabs_content">

  <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
    <?php echo wp_nonce_field('tag-groups-multilingual', 'tag-groups-multilingual-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <p>
      <input type="checkbox" id="tag_group_multilingual_sync_groups" name="tag_group_multilingual_sync_groups" autocomplete="off" value="1" <?php if ($tag_group_multilingual_sync_groups) :
            ?> checked<?php 
                                                                                                                                            endif; ?> />&nbsp;
      <label for="tag_group_multilingual_sync_groups"><?php _e('Keep groups of translations in sync', 'tag-groups') ?></label>
      <button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="multilingual-sync" aria-expanded="false" aria-controls="tg-help-multilingual-sync"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Keep groups of translations in sync', 'tag-groups'))); ?></span></button><span id="tg-help-multilingual-sync" class="chatty-mango-help-container chatty-mango-help-container-multilingual-sync" style="display:none;"><?php _e('If you activate this option, we try to sync the groups across all language versions of a tag when you save it. Deactivate it to set groups independently.', 'tag-groups') ?></span>
    </p>

    <p>&nbsp;</p>
    <input type="hidden" name="tg_action" value="multilingual">
    <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Settings', 'tag-groups') ?>" id="submitbutton" />
  </form>

</div>
