<h2><?php _e('First Steps', 'tag-groups') ?></h2>
<div class="tg-onboarding-container">
  <h3><?php /* translators: %s is the plugin name */ printf(esc_html__('Welcome to %s!', 'tag-groups'), esc_html($title)) ?></h3>
  <p><?php _e('The plugin is ready for use. If you need some assistance, we can walk you through the basic steps.', 'tag-groups') ?></p>
  <div style="margin: 50px 0; max-width: 500px;">
    <a href="<?php echo esc_url($settings_setup_wizard_link) ?>" class="tg_premium_backend_call_to_action_button"><span class="dashicons dashicons-lightbulb"></span>&nbsp;<?php _e('Start the Setup Wizard', 'tag-groups') ?></a>
  </div>
  <div style="clear:both; height: 50px;"></div>
  <div id="tag_groups_onboarding_accordion">
    <h3><span class="dashicons dashicons-admin-settings"></span>&nbsp;<?php _e('Or click here if you prefer to do it on your own.', 'tag-groups') ?></h3>
    <div style="display:none;">
      <ol>
        <li><?php /* translators: %s is the href attribute with URL */ echo wp_kses_post(str_replace('</a>', '<span class="screen-reader-text"> ' . esc_html__('(opens in a new tab)', 'tag-groups') . '</span></a>', sprintf(__('Go to the <span class="dashicons dashicons-tag"></span>&nbsp;Tag Groups <a %s>taxonomy settings</a> and <b>select the taxonomy</b> (tag type) of your tags. In most cases just leave the default: Tags (post_tag).', 'tag-groups'), 'href="' . esc_url($settings_taxonomy_link) . '" target="_blank" rel="noopener noreferrer"'))) ?></li>
        <li><?php _e('Go to the tag groups admin page and <b>create some groups</b>.', 'tag-groups') ?> <?php /* translators: %s is the href attribute with URL */ echo wp_kses_post(str_replace('</a>', '<span class="screen-reader-text"> ' . esc_html__('(opens in a new tab)', 'tag-groups') . '</span></a>', sprintf(__('You can find these pages listed on your <a %s>Tag Groups home page</a>.', 'tag-groups'), 'href="' . esc_url($settings_home_link) . '" target="_blank" rel="noopener noreferrer"'))) ?></li>
        <li><?php _e('Go to your tags and <b>assign them to these groups</b>.', 'tag-groups') ?></li>
      </ol>
      </div>
    </div>
  </div>
  <script>
  jQuery(document).ready(function(){
    jQuery('#tag_groups_onboarding_accordion').accordion({collapsible:true, active:false});
  });
</script>
