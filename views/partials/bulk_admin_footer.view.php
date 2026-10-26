<?php
/*
*   constructing the action menu
*
*   Using .html() instead of .text() to avoid ampersands displaying
*/
?>
<script>
jQuery(document).ready(function () {
  jQuery('<option>').val('assign').html('<?php _e('Assign to', 'tag-groups') ?>').appendTo("select[name='action']");
  jQuery('<option>').val('assign').html('<?php _e('Assign to', 'tag-groups') ?>').appendTo("select[name='action2']");
  var tgAssignLabel = <?php echo wp_json_encode(__('Tag group to assign', 'tag-groups')); ?>;
  var sel_top = jQuery("<select name='term-group-top' id='term-group-top'>").attr('aria-label', tgAssignLabel).insertAfter("select[name='action']");
  var sel_bottom = jQuery("<select name='term-group-bottom' id='term-group-bottom'>").attr('aria-label', tgAssignLabel).insertAfter("select[name='action2']");
  <?php foreach ($term_groups as $term_group) : ?>
  sel_top.append(jQuery("<option>").attr("value", "<?php echo esc_attr($term_group['term_group']) ?>").html("<?php echo esc_html($term_group['label']) ?>"));
  sel_bottom.append(jQuery("<option>").attr("value", "<?php echo esc_attr($term_group['term_group']) ?>").html("<?php echo esc_html($term_group['label']) ?>")); 
  <?php endforeach; ?>

  <?php if (isset($_GET['orderby']) && $_GET['orderby'] == 'term_group') : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
  jQuery('th#term_group').addClass('sorted');
        <?php if (isset($_GET['order']) && $_GET['order'] == 'asc') : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
  jQuery('th#term_group').addClass('asc');
        <?php else : ?>
  jQuery('th#term_group').addClass('desc');
        <?php endif; ?>
  <?php else : ?>
  jQuery('th#term_group').addClass('sortable');
  <?php endif; ?>

  jQuery('#bulk-action-selector-top').on('change', function(){
    var selectedOption = jQuery('#bulk-action-selector-top').children("option:selected").val();
    if ('assign' === selectedOption || '-1' === selectedOption ) {
      jQuery("[name='term-group-top']").prop('disabled', false);
    } else {
      jQuery("[name='term-group-top']").prop('disabled', 'disabled');
    }
  });
  jQuery('#bulk-action-selector-bottom').on('change', function(){
    var selectedOption = jQuery('#bulk-action-selector-bottom').children("option:selected").val();
    if ('assign' === selectedOption || '-1' === selectedOption ) {
      jQuery("[name='term-group-bottom']").prop('disabled', false);
    } else {
      jQuery("[name='term-group-bottom']").prop('disabled', 'disabled');
    }
  });

  jQuery('[name="term-group-top"]').on('change', function () {
    jQuery('[name="action"]').val('assign');
    jQuery('[name="action2"]').val('assign');
    var selected = jQuery(this).val();
    jQuery('[name="term-group-bottom"]').val(selected).prop('disabled', false);
  });
  jQuery('[name="term-group-bottom"]').on('change', function () {
    jQuery('[name="action"]').val('assign');
    jQuery('[name="action2"]').val('assign');
    var selected = jQuery(this).val();
    jQuery('[name="term-group-top"]').val(selected).prop('disabled', false);
  });
});
</script>
