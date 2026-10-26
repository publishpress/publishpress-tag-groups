<label for="tg_filter_posts_value" class="screen-reader-text"><?php esc_html_e('Filter by tag group', 'tag-groups'); ?></label>
<select name="tg_filter_posts_value" id="tg_filter_posts_value" onchange="tagGroupsGroupFilterChanged()">
  <option value=""><?php
  _e('Filter by tag group', 'tag-groups'); ?></option>
  <?php
    foreach ($term_groups as $term_group => $label) {
        $style = in_array($term_group, $parents) ? 'style="background-color:#dfdfdf"' : '';
        $prefix = empty($parents) || in_array($term_group, $parents) ? '' : '&nbsp;&nbsp;';
        printf('<option value="%s"%s %s>%s</option>', esc_attr($term_group), ( '' != $current_term_group && $term_group == $current_term_group ) ? ' selected="selected"' : '', esc_attr($style), esc_html($prefix . htmlentities($label, ENT_QUOTES, "UTF-8")));
    }
    ?>
</select>
<script>

function tagGroupsGroupFilterChanged() {

  var groupFilters = document.getElementsByName("tg_filter_posts_value");

  if ( typeof groupFilters == 'undefined' || groupFilters === null ) {
    return false;
  }

  var catFilter = document.getElementById("cat");

  if ( typeof catFilter === 'undefined' || catFilter === null ) {
    return true;
  }

  var selectedGroup = groupFilters[0].value;

  if (selectedGroup == "") {
    document.getElementById("cat").removeAttribute("disabled");
  } else {
    document.getElementById("cat").setAttribute("disabled","");
  }
  return true;
}

function tagGroupsCategoryFilterChanged() {

  var selectedGroup = document.getElementById("cat").value;
  var groupFilters = document.getElementsByName("tg_filter_posts_value");

  if ( typeof groupFilters === 'undefined' ) {
    return false;
  }

  if (selectedGroup == "0") {
    groupFilters[0].removeAttribute("disabled");
  } else {
    groupFilters[0].setAttribute("disabled","");
  }
  return true;
}

function tagGroupsPostFilterInit() {

  var catFilter = document.getElementById("cat");

  if ( typeof catFilter === 'undefined' || catFilter === null ) {
    return;
  }

  catFilter.setAttribute("onchange","tagGroupsCategoryFilterChanged()");
  
  // initial settings
  
  tagGroupsGroupFilterChanged();
  
  tagGroupsCategoryFilterChanged();
}

tagGroupsPostFilterInit();

</script>
