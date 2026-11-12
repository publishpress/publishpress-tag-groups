<?php defined('ABSPATH') || exit;
?>
<div style="float:left;">
  <h2><?php _e('Active Taxonomies', 'tag-groups') ?></h2>
  <table class="widefat fixed striped">
    <?php foreach ($taxonomy_infos as $taxonomy_info) : ?>
      <tr>
        <td>
          <div class="tg_admin_accordion">
            <h4><span title="<?php _e('Click for more information', 'tag-groups') ?>"><?php echo esc_html($taxonomy_info['name']) ?> (<?php echo esc_html($taxonomy_info['slug']) ?>)</span></h4>
            <div style="display:none;">
              <?php if ($group_count < 100 && $taxonomy_info['term_count'] < 10000) : ?>
                <h4><?php _e('Group Statistics', 'tag-groups') ?>
                    <?php if (TagGroups_WPML::get_current_language()) : ?>
                  (<?php _e('for the selected language', 'tag-groups') ?>)
                    <?php endif; ?>
              </h4>
                    <?php echo wp_kses_post($taxonomy_info['info_html']) ?>
              <?php else : ?>
                  <?php echo wp_kses_post(sprintf('This taxonomy has %d tags in %d groups.', $taxonomy_info['term_count'], $group_count)); ?>
              <?php endif; ?>
          </div>
        </td>
        <td style="max-width:300px;">
          <span class="dashicons dashicons-arrow-right-alt tg_no_underline"></span>&nbsp;<a href="<?php echo esc_url($taxonomy_info['tag_group_admin']) ?>"><?php _e('go to tag group administration', 'tag-groups') ?></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>

<!-- begin Tag Groups plugin -->
<script>
jQuery(document).ready(function() {
  var icons = {
    header: "dashicons dashicons-arrow-right",
    activeHeader: "dashicons dashicons-arrow-down"
  };
  jQuery( ".tg_admin_accordion" ).accordion({
    icons:icons,
    collapsible: true,
    active: false,
    heightStyle: "content"
  });
});
</script>
<!-- end Tag Groups plugin -->
