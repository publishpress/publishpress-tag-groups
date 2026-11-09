<?php defined('ABSPATH') || exit;
?>
<div style="margin: 50px 0 0;">

  <p><?php _e('Now you are ready to create your own tag groups. If you prefer to start out from a sample, we will create for you three groups and three tags. You can play around with them, rename or delete them.', 'tag-groups') ?></p>

  <form method="post" action="<?php echo esc_url($setup_wizard_next_link) ?>">

    <p>
      <input type="checkbox" autocomplete="off" value=1 id="tag-groups-create-sample-groups" name="tag-groups-create-sample-groups" >&nbsp;<label for="tag-groups-create-sample-groups"><?php _e('Create sample groups:', 'tag-groups') ?>
        <strong><?php
        echo esc_html(implode(', ', $group_names));
        ?></strong>
      </label>
    </p>

    <p>
      <input disabled type="checkbox" autocomplete="off" value=1 id="tag-groups-create-sample-tags" name="tag-groups-create-sample-tags" >&nbsp;<label for="tag-groups-create-sample-tags"><?php _e('Create sample tags in these groups:', 'tag-groups') ?>
        <strong><?php
        echo esc_html(implode(', ', $tag_names));
        ?></strong>
      </label>
    </p>

    <p>
      <input type="checkbox" autocomplete="off" value=1 id="tag-groups-create-sample-page" name="tag-groups-create-sample-page" checked>&nbsp;<label for="tag-groups-create-sample-page"><?php echo esc_html($create_sample_page_label) ?></label>
    </p>

    <?php foreach ($group_names as $group_name) : ?>
      <input type="hidden" name="tag_groups_group_names[]" value='<?php echo esc_attr($group_name) ?>'>
    <?php endforeach; ?>
    <?php foreach ($tag_names as $tag_name) : ?>
      <input type="hidden" name="tag_groups_tag_names[]" value='<?php echo esc_attr($tag_name) ?>'>
    <?php endforeach; ?>
    <input type="hidden" name="tg_action_wizard" value="sample-content">
    <input type="submit" value="<?php _e('Next Step', 'tag-groups') ?>" class="button button-primary tag-groups-wizard-submit">
    <input type="hidden" name="tag-groups-setup-wizard-nonce" id="tag-groups-setup-wizard-nonce" value="<?php echo esc_attr(wp_create_nonce('tag-groups-setup-wizard-nonce')) ?>" />
  </form>
</div>
<script>
  jQuery(document).ready(function(){
    jQuery('#tag-groups-create-sample-groups').on('change', function(){
      if ( jQuery('#tag-groups-create-sample-groups').prop('checked') ) {
        jQuery('#tag-groups-create-sample-tags').prop('disabled', false);
        // jQuery('#tag-groups-create-sample-page').prop('disabled', false);
      } else {
        jQuery('#tag-groups-create-sample-tags').prop('disabled', true);
        jQuery('#tag-groups-create-sample-tags').prop('checked', false);
        // jQuery('#tag-groups-create-sample-page').prop('disabled', true);
      }
    });
  });
</script>
