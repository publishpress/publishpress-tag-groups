<script>
  jQuery(document).ready(function(){
    if ( typeof jQuery.ui.tooltip === 'function' ) {
      var tgTooltipHost = jQuery('.tag-groups-tooltips-enabled');
      tgTooltipHost.tooltip({
        // jQuery UI opens the tooltip on mouseover and on keyboard focus (focusin)
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
        tooltipClass: "tag-groups-tooltip",
        // keep the tooltip open while the pointer is over the tooltip itself
        close: function( event, ui ) {
          ui.tooltip.on( 'mouseenter.tgTooltip', function () {
            jQuery( this ).stop( true ).fadeTo( 200, 1 );
          }).on( 'mouseleave.tgTooltip', function () {
            jQuery( this ).fadeOut( 200, function () {
              jQuery( this ).remove();
            });
          });
        }
      });

      // Escape dismisses any open tooltip
      jQuery(document).on('keydown.tgTooltip', function (e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
          tgTooltipHost.tooltip('close');
          jQuery('.tag-groups-tooltip').stop(true).remove();
        }
      });
    }
  });
</script>
