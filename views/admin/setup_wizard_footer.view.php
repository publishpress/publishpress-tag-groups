</div>
<script>
jQuery(document).ready(function(){
  jQuery("button.chatty-mango-help-icon").off('click.tgHelp').on('click.tgHelp', function(){
    var button = jQuery(this);
    var expanded = button.attr("aria-expanded") === "true";
    button.attr("aria-expanded", expanded ? "false" : "true");
    jQuery(".chatty-mango-help-container-" + button.attr("data-topic")).stop(true, true).slideToggle();
  });
});
</script>
