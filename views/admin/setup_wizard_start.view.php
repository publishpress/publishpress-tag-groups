<?php defined('ABSPATH') || exit;
?>
<div style="margin: 50px 0 0;">
  <ul style="list-style:disc; margin-left:20px;">
  <li><?php _e('On the following pages we will guide you through the basic settings that you need for the most common features.', 'tag-groups') ?></li>
    <li><?php _e('You can later make changes or fine-tune the details in the Tag Groups settings.', 'tag-groups') ?> <a class="tg_no_underline" href="#toplevel_page_tag-groups-settings"><span class="tag_groups_show_settings tg_pointer"><?php _e('(show me the menu)', 'tag-groups') ?></span></a></li>
    <li><?php _e('Feel free to abort the Setup Wizard any time and continue on your own path. You can also launch it again at a later time.', 'tag-groups') ?></li>
    <?php if (! $is_premium) : ?>
      <li><?php _e('The wizard will offer more options after upgrading to premium.', 'tag-groups') ?></li>
    <?php endif; ?>
  </ul>
  <div class="chatty-mango-settings-container">
    <form method="POST" action="<?php echo esc_url($setup_wizard_next_link) ?>">
      <input type="submit" value="<?php _e('Start', 'tag-groups') ?>" class="button button-primary tag-groups-wizard-submit">
    </form>
  </div>
</div>
<script>
jQuery(document).ready(function(){
  jQuery('.tag_groups_show_settings').on('mouseover click', function(){
    jQuery('#toplevel_page_tag-groups-settings').css('background-color', '#d46f15');
  });
  jQuery('.tag_groups_show_settings').on('mouseleave', function(){
    jQuery('#toplevel_page_tag-groups-settings').css('background-color', 'inherit');
  });
});
</script>
