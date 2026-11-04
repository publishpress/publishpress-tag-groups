<?php defined('ABSPATH') || exit;
?>
<!-- begin Tag Groups plugin -->
<script>
  (function tagGroupsInitAccordion(retries) {
    if (typeof jQuery !== 'undefined' && typeof jQuery.ui !== 'undefined' && typeof jQuery.ui.accordion !== 'undefined' && typeof jQuery.widget !== 'undefined' && typeof TagGroupsBase !== 'undefined') {
      TagGroupsBase.accordion('<?php echo esc_js($id) ?>', <?php echo $options_js_object; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already JSON encoded. ?>, <?php echo $delay ? 'true' : 'false' ?>);
      return;
    }

    if (retries > 0) {
      setTimeout(function() {
        tagGroupsInitAccordion(retries - 1);
      }, 100);
      return;
    }

    var element = document.getElementById('<?php echo esc_js($id) ?>');
    if (element) {
      element.className = element.className.replace(/\btag-groups-cloud-hidden\b/g, '');
    }
    console.log('[Tag Groups] Error: jQuery UI Accordion is missing!');
  })(50);
</script>
<!-- end Tag Groups plugin -->
