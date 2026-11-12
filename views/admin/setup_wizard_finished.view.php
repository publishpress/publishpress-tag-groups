<?php defined('ABSPATH') || exit;
?>
<div style="margin: 50px 0 0;">

  <h3><?php _e('Well done!', 'tag-groups') ?></h3>

  <?php if (! empty($groups_admin_link)) : ?>
    <p><a  href="<?php echo esc_url($groups_admin_link) ?>" target="_blank"><?php _e('Now go the Tag Group Administration.', 'tag-groups') ?></a></p>
  <?php endif; ?>

  <?php if (! empty($tag_group_sample_page_id)) : ?>
    <p><a href="<?php echo esc_url(get_preview_post_link($tag_group_sample_page_id)) ?>" target="_blank"><?php _e('Or preview the sample page here.', 'tag-groups') ?></a></p>
  <?php endif; ?>

  <?php if (! empty($groups_admin_link)) : ?>
    <p><?php /* translators: %1$s and %2$s are href attributes with URLs */ echo wp_kses_post(sprintf(__('Or go to the <a %1$s>Tag Groups settings</a> or visit the <a %2$s>documentation</a>.', 'tag-groups'), 'href="' . esc_url($settings_home_link) . '" target="_blank"', 'href="' . esc_url($documentation_link) . '" target="_blank"')) ?></p>
  <?php else : ?>
    <p><?php /* translators: %1$s and %2$s are href attributes with URLs */ echo wp_kses_post(sprintf(__('Now go to the <a %1$s>Tag Groups settings</a>, or visit the <a %2$s>documentation</a>.', 'tag-groups'), 'href="' . esc_url($settings_home_link) . '" target="_blank"', 'href="' . esc_url($documentation_link) . '" target="_blank"')); ?></p>
  <?php endif; ?>

  <p><?php _e('If you need to launch the Setup Wizard again, you can find it in the settings. Just enter "wizard" in the search field.', 'tag-groups') ?></p>

  <p><?php _e('Happy tagging!', 'tag-groups') ?></p>
</div>
