<div class="notice notice-<?php echo esc_attr($notice['type']) ?> is-dismissible" style="clear:both;">
  <?php echo wp_kses_post($notice['content']) ?>
  <?php if ('' != $ajax_link) : ?>
  <div class="screen-reader-text" role="status" aria-live="polite" id="tag_groups_clear_cache_status"></div>
  <?php endif; ?>
  <div style="clear:both;"></div>
</div>
<?php if ('' != $ajax_link) : ?>
  <script>
  jQuery(document).ready(function(){
    jQuery("#tag_groups_premium_clear_cache").on('click', function(){
      jQuery("#tag_groups_premium_clear_cache").attr("disabled", "disabled");
      jQuery.ajax({
        url: "<?php echo esc_url($ajax_link) ?>",
        data: {
          action: "tg_ajax_clear_object_cache",
          nonce: <?php echo wp_json_encode(wp_create_nonce('tag_groups_clear_object_cache')); ?>,
        },
        method: "POST",
        success: function( data ) {
          var clearedText = <?php echo wp_json_encode(__('The cache has been cleared.', 'tag-groups')); ?>;
          var $done = jQuery('<span tabindex="-1" class="tag-groups-cache-cleared"><span class="dashicons dashicons-yes" aria-hidden="true"></span><span class="screen-reader-text"></span></span>');
          $done.find('.screen-reader-text').text(clearedText);
          jQuery("#tag_groups_premium_clear_cache").replaceWith($done);
          $done.trigger('focus');
          jQuery("#tag_groups_clear_cache_status").text(clearedText);
        }
      });
    });
  });
</script>
<?php endif; ?>
