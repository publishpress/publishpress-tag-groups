<?php

defined('ABSPATH') || exit;
?>
<script>
  jQuery(document).ready(function(){
    if ( typeof jQuery.ui.tooltip === 'function' ) {
      jQuery('.tag-groups-tooltips-enabled').tooltip({
        position: {
          my: "center bottom-15",
          at: "center top",
          using: function( position, feedback ) {
            jQuery( this ).css( position );
            jQuery( "<div>" )
              .addClass( "arrow" )
              .addClass( feedback.vertical )
              .addClass( feedback.horizontal )
              .appendTo( this );
          }
        },
        tooltipClass: "tag-groups-tooltip"
      });
    }
  });
</script>
