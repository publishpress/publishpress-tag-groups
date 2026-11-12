<?php defined('ABSPATH') || exit;
?>

<div class='wrap tag-groups-tooltips-enabled' id="tag_group_administration">
  <h2><?php _e('Tag Group Administration', 'tag-groups') ?></h2>

  <div id="tg_message_container">
  </div>

  <p>
    <div style="float:left; margin: 10px 50px 10px 0;">
      <input type="text" id="filter_label" placeholder="<?php _e('Filter by label', 'tag-groups') ?>"/>
    </div>

    <div style="float: left; margin: 5px 0;">
      <div id="new_group" style="margin:5px;" class="new_group_pointer">
      </div>
    </div>
  </p>

  <table class="widefat tg_groups_table">
    <thead>
      <tr>
        <th><?php _e('Tag Group name', 'tag-groups') ?></th>
        <th class="tg_group_admin_parent" style="display:none;"></th>
        <th><?php _e('Number of assigned tags', 'tag-groups') ?></th>
        <?php if ($tag_group_show_filter) : ?>
          <th><?php _e('Filters', 'tag-groups') ?></th>
        <?php endif; ?>
        <th><?php _e('Actions', 'tag-groups') ?></th>
        <th><?php _e('Change order', 'tag-groups') ?></th>
        <th style="min-width:30px;"><?php _e('Group ID', 'tag-groups') ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th><?php _e('Tag Group name', 'tag-groups') ?></th>
        <th class="tg_group_admin_parent" style="display:none;"></th>
        <th><?php _e('Number of assigned tags', 'tag-groups') ?></th>
        <?php if ($tag_group_show_filter) : ?>
          <th><?php _e('Filters', 'tag-groups') ?></th>
        <?php endif; ?>
        <th><?php _e('Actions', 'tag-groups') ?></th>
        <th><?php _e('Change order', 'tag-groups') ?></th>
        <th><?php _e('Group ID', 'tag-groups') ?></th>
      </tr>
    </tfoot>
    <tbody id="tg_groups_container">
      <tr>
        <td colspan="6" style="padding: 50px; text-align: center;">
          <img src="<?php echo esc_url(admin_url('images/spinner.gif')) ?>" />
        </td>
      </tr>
    </tbody>
  </table>

  <div id="tg_pager_container_adjuster">
    <div id="tg_pager_container"></div>
  </div>
  <input type="hidden" id="tg_nonce" value="<?php echo esc_attr(wp_create_nonce('tg_groups_management')); ?>">
  <input type="hidden" id="tg_start_position" value="1">

  <script>
  var tagGroupsLabels = {};
  tagGroupsLabels.edit = '<?php _e('Edit', 'tag-groups') ?>';
  tagGroupsLabels.create = '<?php _e('Create', 'tag-groups') ?>';
  tagGroupsLabels.newgroup = '<?php _e('new', 'tag-groups') ?>';
  tagGroupsLabels.placeholder_new = '<?php _e('label', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_delete = '<?php _e('Delete this group.', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_newbelow = '<?php _e('Create a new group below.', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_addnew = '<?php _e('Add New Tag Group', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_move_up = '<?php _e('move up', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_move_down = '<?php _e('move down', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_reload = '<?php _e('reload', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_next = '<?php _e('next page', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_previous = '<?php _e('previous page', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_go_to_page = '<?php _e('go to page:', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_showposts = '<?php _e('Show posts', 'tag-groups') ?>';
  tagGroupsLabels.tooltip_showtags = '<?php _e('Show tags', 'tag-groups') ?>';

  var tagGroupsAjaxParameters = {"ajaxurl": "<?php echo esc_url($admin_url) ?>", "postsurl": "<?php echo esc_url($post_url) ?>", "tagsurl": "<?php echo esc_url($tags_url) ?>", "items_per_page": "<?php echo esc_attr($items_per_page) ?>", "show_parents": false, "isPremium": false};
  var tagGroupsData = {
    tag_groups_taxonomy: <?php echo wp_json_encode($taxonomies); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>,
  };

  jQuery(document).ready(function () {
    tagGroupsData.tag_groups_task = "refresh";
    tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);

    jQuery('#filter_label').on('keyup', function(){
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });

    jQuery('#tag_group_administration').on('click', '.tg_edit_label', function () {
      tg_close_all_textfields();
      var element = jQuery(this);
      var position = element.attr("data-position");
      var label = escape_html(element.attr("data-label"));
      element.replaceWith('<span class="tg_edit_label_active"><input data-position="' + position + '" data-label="' + label + '" value="' + label + '"> <span class="tg_edit_label_yes dashicons dashicons-yes tg_pointer" ></span> </span>');
      jQuery('.tg_edit_label_yes').css('font-size', '30px');
    });

    jQuery('#tag_group_administration').on('keypress', '.tg_edit_label_active', function (e) {
      if (e.keyCode == 13) {
        e.preventDefault();
        var input = jQuery(this).children(":first");
        var tagGroupsData = {
          tag_groups_task: 'update',
          tag_groups_position: input.attr('data-position'),
          tag_groups_label: input.val(),
          tag_groups_taxonomy: <?php echo wp_json_encode($taxonomies); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>,
        };
        tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
        return false;
      }

      // if (e.keyCode == 65) {
      //   var input = jQuery(this).children(":first");
      //   input.select();
      //   return false;
      // }
      return true;
    });

    jQuery('#tag_group_administration').on('click', '.tg_edit_label_yes', function () {
      var input = jQuery(this).parent().children(":first");
      var tagGroupsData = {
        tag_groups_task: 'update',
        tag_groups_position: input.attr('data-position'),
        tag_groups_label: input.val(),
        tag_groups_taxonomy: <?php echo wp_json_encode($taxonomies); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>,
      };
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });

    jQuery('#tag_group_administration').on('click', '.tg_edit_label_no', function () {
      var input = jQuery(this).parent().children(":first");
      tg_close_textfield(jQuery(this).parent(), false);
    });

    jQuery('#tag_group_administration').on('keypress', '[id^="tg_new_"]:visible', function (e) {
      if (e.keyCode == 13) {
        var input = jQuery(this).find("input");
        var tagGroupsData = {
          tag_groups_task: 'new',
          tag_groups_position: input.attr('data-position'),
          tag_groups_label: input.val(),
          tag_groups_taxonomy: <?php echo wp_json_encode($taxonomies); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>,
        };
        tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
      }
    });

    jQuery('#tag_group_administration').on('click', '.tg_new_yes', function () {
      var input = jQuery(this).parent().children(":first");
      var tagGroupsData = {
        tag_groups_task: 'new',
        tag_groups_position: input.attr('data-position'),
        tag_groups_label: input.val(),
        tag_groups_taxonomy: <?php echo wp_json_encode($taxonomies); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>,
      };
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });

    jQuery('#tag_group_administration').on('click', '.tg_delete', function () {
      var position = jQuery(this).attr("data-position");
      jQuery('.tg_sort_tr[data-position='+position+'] td').addClass('tg_ask_delete');
      setTimeout(function () { // we need some time to effect the changes of the class
        var answer = confirm('<?php
        _e('Do you really want to delete this tag group?', 'tag-groups')
        ?> ');
        if (answer) {
          var tagGroupsData = {
            tag_groups_task: 'delete',
            tag_groups_position: position,
            tag_groups_taxonomy: <?php echo wp_json_encode($taxonomies); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>,
          };
          tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);

        } else {
          jQuery('.tg_sort_tr[data-position='+position+'] td').removeClass('tg_ask_delete')
        }
      }, 500);
    });

    jQuery('#tag_group_administration').on('mouseenter', '.tg_edit_label', function () {
      jQuery(this).children(".dashicons-edit").fadeIn();
    });

    jQuery('#tag_group_administration').on('mouseleave', '.tg_edit_label', function () {
      jQuery(this).children(".dashicons-edit").fadeOut();
    });

    jQuery('#tag_group_administration').on('click', '.tg_pager_button' ,function () {
      var page = jQuery(this).attr('data-page');
      jQuery("#tg_start_position").val((page - 1) * <?php echo intval($items_per_page) ?> + 1);
      tagGroupsData.tag_groups_task = "refresh";
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });

    jQuery('#tag_group_administration').on('click', '.tg_up', function () {
      tagGroupsData.tag_groups_position = jQuery(this).attr('data-position');
      tagGroupsData.tag_groups_task = "up";
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });

    jQuery('#tag_group_administration').on('click', '.tg_down', function () {
      tagGroupsData.tag_groups_position = jQuery(this).attr('data-position');
      tagGroupsData.tag_groups_task = "down";
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });

    var element, start_pos, end_pos;
    jQuery("#tg_groups_container").sortable({
      start: function (event, ui) {
        element = Number(ui.item.attr("data-position"));
        start_pos = ui.item.index(".tg_sort_tr") + 1;
      },
      update: function (event, ui) {
        end_pos = ui.item.index(".tg_sort_tr") + 1;
        tagGroupsData.tag_groups_position = element;
        tagGroupsData.tag_groups_task = "move";
        tagGroupsData.tag_groups_new_position = element + end_pos - start_pos;
        tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
      }
    });
    jQuery("#tg_groups_container").disableSelection();

    jQuery('#tag_group_administration').on('click', '#tg_groups_reload', function () {
      tagGroupsData.tag_groups_task = "refresh";
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });
    jQuery('#tag_group_administration').on('click', '#tg_groups_sort_up', function () {
      tagGroupsData.tag_groups_task = "sortup";
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });
    jQuery('#tag_group_administration').on('click', '#tg_groups_sort_down', function () {
      tagGroupsData.tag_groups_task = "sortdown";
      tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
    });

    jQuery( function() {
      jQuery( "#tg_tools_accordion" ).accordion({
        active: false,
        collapsible: true,
      });
    } );
  });
</script>
