<div style="margin: 50px 0 0;">
  <p><?php _e('Choose the taxonomies for which you want to use tag groups.', 'tag-groups') ?> <?php _e("In most cases the default taxonomy <b>Tags (post_tag)</b> will do the job.", 'tag-groups') ?><button type="button" class="button-link dashicons dashicons-editor-help chatty-mango-help-icon" data-topic="taxonomies" aria-expanded="false" aria-controls="tg-help-taxonomies"><span class="screen-reader-text"><?php /* translators: %s: name of the setting */ echo esc_html(sprintf(__('More information: %s', 'tag-groups'), __('Taxonomies', 'tag-groups'))); ?></span></button></p>

  <div id="tg-help-taxonomies" class="chatty-mango-help-container chatty-mango-help-container-taxonomies" style="display:none;">
    <p><?php _e("The default texonomy is <b>Tags (post_tag)</b>. Please note that the tag clouds might not work with all taxonomies and that some taxonomies listed here may not be accessible in the admin backend. If you don't understand what is going on here, just leave the default.", 'tag-groups') ?></p>
  </div>
  <div class="chatty-mango-settings-container">
    <form method="POST" action="<?php echo esc_url($setup_wizard_next_link) ?>">
      <ul>
        <p>&nbsp;</p>
        <?php foreach ($public_taxonomies as $taxonomy) : ?>
            <?php if (is_taxonomy_hierarchical($taxonomy)) {
                continue; 
            } ?>
          <li class="tg_advanced_options_items">
            <input type="checkbox" name="taxonomies[]" autocomplete="off" id="<?php echo esc_attr($taxonomy) ?>" value="<?php echo esc_attr($taxonomy) ?>"<?php if (in_array($taxonomy, $enabled_taxonomies)) : ?>
              checked
                                                                              <?php endif; ?>
            />&nbsp;<span class="dashicons dashicons-index-card tg_no_underline<?php if (strpos($taxonomy, '_tag') === false) :
                ?> tg_faded<?php 
                                                                               endif; ?>"></span>
              <label for="<?php echo esc_attr($taxonomy) ?>" class="tg_unhide_trigger" title="<?php _e('post type', 'tag-groups') ?>: <?php echo esc_html(implode(', ', TagGroups_Taxonomy::post_types_from_taxonomies($taxonomy))) ?>">
              <?php echo esc_html(TagGroups_Taxonomy::get_name_from_slug($taxonomy)) ?> (<?php echo esc_html($taxonomy) ?>)
            </label>
          </li>
        <?php endforeach; ?>
      </ul>

      <p>&nbsp;</p>
      <h4><span class="dashicons dashicons-lightbulb"></span>&nbsp;<?php _e('You will find more options in the Tag Groups settings.', 'tag-groups') ?></h4>

      <input type="hidden" name="tg_action_wizard" value="taxonomy">
      <input type="submit" value="<?php _e('Next Step', 'tag-groups') ?>" class="button button-primary tag-groups-wizard-submit">
      <input type="hidden" name="tag-groups-setup-wizard-nonce" id="tag-groups-setup-wizard-nonce" value="<?php echo esc_attr(wp_create_nonce('tag-groups-setup-wizard-nonce')) ?>" />
    </form>
  </div>
</div>

<script>
jQuery(document).ready(function () {
  jQuery(".tg_unhide_trigger").on('mouseover', function () {
    jQuery(this).find("span").show();
  });
  jQuery(".tg_unhide_trigger").on('mouseout', function () {
    jQuery(this).find("span").hide();
  });
});
</script>
