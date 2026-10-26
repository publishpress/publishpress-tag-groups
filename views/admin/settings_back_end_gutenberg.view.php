<div class="tg_settings_tabs_content">

  <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
    <?php echo wp_nonce_field('tag-groups-gutenberg', 'tag-groups-gutenberg-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <p>
      <input type="checkbox" id="tg_server_side_render" name="tag_group_server_side_render" autocomplete="off" value="1" <?php if ($tag_group_server_side_render) :
            ?> checked<?php 
                                                                                                                         endif; ?> />&nbsp;
      <label for="tg_server_side_render"><?php _e('Gutenberg block preview', 'tag-groups') ?></label>
      <button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="gutenberg-preview" aria-expanded="false" aria-controls="tg-help-gutenberg-preview"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Gutenberg block preview', 'tag-groups'))); ?></span></button><span id="tg-help-gutenberg-preview" class="chatty-mango-help-container chatty-mango-help-container-gutenberg-preview" style="display:none;"><?php _e('Some blocks can show an approximate preview in the Gutenberg editor. If you deactivate this option, then all blocks will show a static icon with text.', 'tag-groups') ?></span>
    </p>

    <p>&nbsp;</p>
    <input type="hidden" name="tg_action" value="gutenberg">
    <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Settings', 'tag-groups') ?>" id="submitbutton" />
  </form>

</div>
