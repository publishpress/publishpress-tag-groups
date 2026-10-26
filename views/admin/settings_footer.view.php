</div>
<script>
jQuery(document).ready(function(){
  // Namespaced so the setup wizard footer (also rendered on wizard pages) does not add a second toggle.
  jQuery(".chatty-mango-help-icon").off('click.tgHelp').on('click.tgHelp', function(){
    var button = jQuery(this);
    var container = jQuery(".chatty-mango-help-container-" + button.attr("data-topic"));
    var expanded = button.attr("aria-expanded") === "true";
    button.attr("aria-expanded", expanded ? "false" : "true");
    container.stop(true, true).slideToggle();
  });
});
</script>
