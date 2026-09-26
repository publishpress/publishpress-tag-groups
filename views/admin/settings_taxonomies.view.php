<div class="tg_settings_tabs_content">
  <p><?php _e('Choose the taxonomies for which you want to use Tag Groups:', 'tag-groups') ?></p>

  <div class="chatty-mango-help-container chatty-mango-help-container-taxonomies" style="display:none;">
    <p><?php _e("The default texonomy is <b>Tags (post_tag)</b>. Please note that the tag clouds might not work with all taxonomies and that some taxonomies listed here may not be accessible in the admin backend. If you don't understand what is going on here, just leave the default.", 'tag-groups') ?></p>
    <p><?php _e("<b>Please deselect taxonomies that you don't use. Using several taxonomies for the same post type or hierarchical taxonomies (like categories) is experimental and not supported.</b>", 'tag-groups') ?></p>
    <p><?php _e('To see the post type, hover your mouse over the option.', 'tag-groups') ?></p>
    <p><?php _e('If you use a custom taxonomy, make sure that the attribute "Public" is set to "true" and "Hierarchical" to "false".', 'tag-groups') ?></p>
  </div>
  <div class="chatty-mango-settings-container">
    <form method="POST" action="<?php /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized */ echo isset($_SERVER['REQUEST_URI']) ? esc_url(wp_unslash($_SERVER['REQUEST_URI'])) : ''; ?>">
      <?php echo wp_nonce_field('tag-groups-taxonomy', 'tag-groups-taxonomy-nonce', true, false); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
      <p>
        <input id="tg_enable_groups" type="checkbox" name="tag_group_enable_groups" autocomplete="off" value="1" <?php checked($tag_group_enable_groups); ?> />
        <label for="tg_enable_groups"><?php _e('Enable Groups feature', 'tag-groups') ?></label>
      </p>
      <p><?php _e('Disable this option to hide the Tag Group Admin screens in the WordPress admin.', 'tag-groups') ?></p>
      <ul>
        <p><input id="tg_advanced_options_checkbox" type="checkbox" value=1 autocomplete="off" />
          <label for="tg_advanced_options_checkbox"><?php _e('Show hierarchical taxonomies. Not all Tag Groups features will work with these taxonomies.', 'tag-groups') ?></label></p>
        <script>
          jQuery(document).ready(function() {
            jQuery("#tg_advanced_options_checkbox").on('change', function() {
              if (jQuery("#tg_advanced_options_checkbox").is(":checked")) {
                jQuery(".tg_advanced_options_items").slideDown();
              } else {
                jQuery(".tg_advanced_options_items").slideUp();
              }
            });
          });
        </script>
        <p>&nbsp;</p>
        <?php foreach ($public_taxonomies as $taxonomy) : ?>
          <li<?php if (is_taxonomy_hierarchical($taxonomy)) :
                ?> class="tg_advanced_options_items" style="display:none;" <?php 
             endif; ?>>
            <input type="checkbox" name="taxonomies[]" autocomplete="off" id="<?php echo esc_attr($taxonomy) ?>" value="<?php echo esc_attr($taxonomy) ?>" <?php if (in_array($taxonomy, $enabled_taxonomies)) :
                ?> checked />
              <?php if ($tag_group_enable_groups) : ?>
                &nbsp;<a href="<?php echo esc_url(TagGroups_Taxonomy::get_tag_group_admin_url($taxonomy)) ?>" title="<?php _e('go to tag group administration', 'tag-groups') ?>"><span class="dashicons dashicons-index-card tg_no_underline"></span></a>
              <?php endif; ?>
                                                                              <?php else : ?>
            />&nbsp;<span class="dashicons dashicons-index-card tg_no_underline tg_faded"></span>
                                                                              <?php endif; ?>
          <label for="<?php echo esc_attr($taxonomy) ?>" class="tg_unhide_trigger">
              <?php echo esc_html(TagGroups_Taxonomy::get_name_from_slug($taxonomy)) ?> (<?php echo esc_html($taxonomy) ?>)
            </label>
          </li>
        <?php endforeach; ?>
      </ul>
      <script>
        jQuery(document).ready(function() {
          jQuery(".tg_unhide_trigger").on('mouseover', function() {
            jQuery(this).find("span").show();
          });
          jQuery(".tg_unhide_trigger").on('mouseout', function() {
            jQuery(this).find("span").hide();
          });
        });
      </script>
      <input type="hidden" name="tg_action" value="taxonomy">
      <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Taxonomies', 'tag-groups') ?>" id="submitbutton" />
    </form>
  </div>
</div>
