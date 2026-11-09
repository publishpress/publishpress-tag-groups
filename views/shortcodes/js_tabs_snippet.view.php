<?php defined('ABSPATH') || exit;
?>
<!-- begin Tag Groups plugin -->
<script>
  (function tagGroupsInitTabs(retries) {
    if (typeof jQuery !== 'undefined' && typeof jQuery.ui !== 'undefined' && typeof jQuery.ui.tabs !== 'undefined' && typeof jQuery.widget !== 'undefined' && typeof TagGroupsBase !== 'undefined') {
      TagGroupsBase.tabs('<?php echo esc_js($id) ?>', <?php echo $options_js_object; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already JSON encoded. ?>, <?php echo $delay ? 'true' : 'false' ?>);
      return;
    }

    if (retries > 0) {
      setTimeout(function() {
        tagGroupsInitTabs(retries - 1);
      }, 100);
      return;
    }

    var element = document.getElementById('<?php echo esc_js($id) ?>');
    if (element) {
      element.className = element.className.replace(/\btag-groups-cloud-hidden\b/g, '');
    }
    console.log('[Tag Groups] Error: jQuery UI Tabs is missing!');
  })(50);
</script>
<!-- end Tag Groups plugin -->
