<?php

/**
*   Using .html() instead of .text() to avoid ampersands displaying
*/

?>
<script>
jQuery(document).ready(function () {
  var sel_filter;
  var container;

  // check if we have an bulk action menu
  if (jQuery("select[name='term-group-top']").length) { 
    // Create a container for the filter dropdown and button
    container = jQuery("<div>").attr("id", "filter_container").css({float: "right",marginLeft: "10px",}).insertAfter("select[name='term-group-top']");
  } else {
    container = jQuery("<div>").attr("id", "filter_container").css({float: "right",marginLeft: "10px",}).insertAfter("select[name='action']");
  }
  sel_filter = jQuery("<select>").attr("id", "tag_filter").attr("name", "term-filter").attr("aria-label", <?php echo wp_json_encode(__('Filter by tag group', 'tag-groups')); ?>).css("margin-right", "5px").appendTo(container);

  sel_filter.append(jQuery("<option>").attr("value", "-1").html("<?php
       _e('Filter off', 'tag-groups'); ?>")
  );

  <?php foreach ($term_groups as $term_group) :
        $bg_color = in_array($term_group['term_group'], $parents) ? '#dfdfdf' : 'transparent';
        $prefix = empty($parents) || in_array($term_group['term_group'], $parents) ? '' : '&nbsp;&nbsp;';
        ?>
  sel_filter.append(jQuery("<option>").attr("value", "<?php echo esc_attr($term_group['term_group']); ?>").css("background-color", "<?php echo esc_attr($bg_color); ?>").html("<?php echo esc_html($prefix . $term_group['label']); ?>")
    );
        <?php 
  endforeach; ?>

  var filterButton = jQuery("<button>").attr("id", "apply_filter_button").attr("class", "button button-primary").text(<?php echo wp_json_encode(__('Filter', 'tag-groups')); ?>).appendTo(container);

  jQuery("#tag_filter option[value=<?php echo esc_attr($tag_filter); ?>]").prop("selected", true);

  jQuery("#tg_reset_filter_button").show();
});
</script>
