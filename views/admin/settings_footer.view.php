<?php

defined('ABSPATH') || exit;
?>
</div>
<script>
jQuery(document).ready(function(){
  jQuery(".chatty-mango-help-icon").on('click', function(){
    jQuery(".chatty-mango-help-container-" + jQuery(this).attr("data-topic")).slideToggle();
  });
});
</script>
