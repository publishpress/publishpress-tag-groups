<?php defined('ABSPATH') || exit;
?>
<div class="form-field">
  <label for="term-group"><?php _e('Tag Groups', 'tag-groups') ?></label>

  <select id="term-group" name="term-group" autocomplete="off">
    <option value="0" selected><?php _e('not assigned', 'tag-groups') ?></option>
    <?php foreach ($term_groups as $term_group) : ?>
      <option value="<?php echo esc_attr($term_group['term_group']); ?>" <?php if (in_array($term_group['term_group'], $new_tag_initial_groups)) :
            ?> selected="selected"<?php 
                     endif; ?>><?php echo esc_html(htmlentities($term_group['label'], ENT_QUOTES, "UTF-8")); ?></option>
    <?php endforeach; ?>
  </select>
  <script>
    jQuery(document).ready(function() {
      jQuery('#term-group').SumoSelect({
        search: true,
        forceCustomRendering: true,
      });
    });
  </script>
  <input type="hidden" name="tag-groups-nonce" id="tag-groups-nonce" value="<?php echo esc_attr(wp_create_nonce('tag-groups-nonce'))  ?>" />
  <input type="hidden" name="new-tag-created" id="new-tag-created" value="1" />
  <input type="hidden" name="tag-groups-taxonomy" id="tag-groups-taxonomy" value="<?php echo esc_attr($screen->taxonomy); ?>" />
</div>
