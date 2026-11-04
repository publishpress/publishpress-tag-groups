<?php defined('ABSPATH') || exit;
?>
<div id="tg_setting_help_search">
  <div class="tg-settings-search-area">
    <label for="tg_setting_help_search_field"></label><input id="tg_setting_help_search_field" placeholder="<?php _e('Search for settings', 'tag-groups') ?>" autocomplete="off"  title="<?php _e('Search for setting pages. Type a space to show all settings.', 'tag-groups') ?>">
  </div>
  <div id="tg_setting_help_search_results" style="display:none;">
    <h2><?php _e('Search Results', 'tag-groups') ?></h2>
    <div class="chatty-mango-settings-columns tg_setting_help_search_results_inner">
      <h4 id="tg_setting_help_nothing_found" style="display:none"><?php _e('Nothing found', 'tag-groups') ?></h4>
      <?php foreach ($topics as $tab => $atts) :
            $keywords = strtolower(implode(',', $atts['keywords'])) . ',' . strtolower($atts['title']) . ',' . $tab;
            ?>
        <div id="tg_topic_' . $tab . '" class="tg_settings_topic" data-keywords="<?php echo esc_attr($keywords) ?>">
          <h4><span class="dashicons dashicons-arrow-right-alt tg_no_underline"></span>&nbsp; <a href="<?php echo esc_url(admin_url('admin.php?page=' . esc_attr($atts['page']) . '&active-tab=' . esc_attr($tab))) ?>"><?php echo esc_html($atts['title']) ?></a></h4>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<script>
var searchText = "";
jQuery(document).ready(function(){
  jQuery("#tg_setting_help_search_field").on('keyup', function(){
    searchText = jQuery("#tg_setting_help_search_field").val().toLowerCase();
    if (searchText.length === 0) {
      jQuery("#tg_setting_help_search_results").slideUp();
    } else {
      if (jQuery("#tg_setting_help_search_results").css("display") === "none") {
        jQuery("#tg_setting_help_search_results").slideDown();
      }
      if (searchText==="' . __( 'all', 'tag-groups') . '" || searchText==="*" || searchText===" ") {
        jQuery(".tg_settings_topic").removeClass("tg_hide");
      } else {
        jQuery(".tg_settings_topic")
        .addClass("tg_hide")
        .filter(function(index){
          var keywords = jQuery(this).attr("data-keywords");
          return keywords.indexOf(searchText) > -1;
        })
        .removeClass("tg_hide");
      }
      if (jQuery(".tg_settings_topic:not(.tg_hide)").length === 0 ){
        jQuery("#tg_setting_help_nothing_found").slideDown();
      } else {
        jQuery("#tg_setting_help_nothing_found").slideUp();
      }
      jQuery(".tg_settings_topic.tg_hide").slideUp();
      jQuery(".tg_settings_topic:not(.tg_hide)").slideDown();
    }
  });
});
</script>
