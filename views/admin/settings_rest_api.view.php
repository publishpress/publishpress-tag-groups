<div class="tg_settings_tabs_content">

  <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
    <?php echo wp_nonce_field('tag-groups-rest-api', 'tag-groups-rest-api-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <p>
      <input type="checkbox" id="group_public_api_access" name="group_public_api_access" autocomplete="off" value="1"<?php if ($group_public_api_access) :
            ?> checked<?php 
                                                                                                                     endif; ?>/>&nbsp;
      <label for="group_public_api_access"><?php _e('Enable Tag Groups Public API Access', 'tag-groups') ?></label>
      <button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="group-api" aria-expanded="false" aria-controls="tg-help-group-api"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Enable Tag Groups Public API Access', 'tag-groups'))); ?></span></button><span id="tg-help-group-api" class="chatty-mango-help-container chatty-mango-help-container-group-api" style="display:none;"><?php _e('Enable public access to tag groups endpoint /wp-json/tag-groups/v1/groups/.', 'tag-groups') ?></span>
    </p>

    <p>
      <input type="checkbox" id="terms_public_api_access" name="terms_public_api_access" autocomplete="off" value="1"<?php if ($terms_public_api_access) :
            ?> checked<?php 
                                                                                                                     endif; ?>/>&nbsp;
      <label for="terms_public_api_access"><?php _e('Enable Terms Public API Access', 'tag-groups') ?></label>
      <button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="terms-api" aria-expanded="false" aria-controls="tg-help-terms-api"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Enable Terms Public API Access', 'tag-groups'))); ?></span></button><span id="tg-help-terms-api" class="chatty-mango-help-container chatty-mango-help-container-terms-api" style="display:none;"><?php _e('Enable public access to terms endpoint /wp-json/tag-groups/v1/terms/.', 'tag-groups') ?></span>
    </p>

    <p>&nbsp;</p>
    <input type="hidden" name="tg_action" value="rest_api">
    <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Settings', 'tag-groups') ?>" id="submitbutton" />
  </form>
</div>
