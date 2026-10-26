
<div class='wrap tag-groups-tooltips-enabled' id="tag_group_administration">
  <h1 class="tg-admin-page-title"><?php esc_html_e('Tag Group Administration', 'tag-groups') ?></h1>

  <div id="tg_message_container" tabindex="-1">
  </div>
  <div id="tg_a11y_status" class="screen-reader-text" role="status" aria-live="polite"></div>

  <p>
    <div style="float:left; margin: 10px 50px 10px 0;">
      <label for="filter_label" class="screen-reader-text"><?php esc_html_e('Filter by label', 'tag-groups') ?></label>
      <input type="text" id="filter_label" placeholder="<?php esc_attr_e('Filter by label', 'tag-groups') ?>"/>
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
          <img src="<?php echo esc_url(admin_url('images/spinner.gif')) ?>" alt="<?php esc_attr_e('Loading', 'tag-groups') ?>" />
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
  var tagGroupsLabels = <?php echo wp_json_encode(array(
      'edit'                => __('Edit', 'tag-groups'),
      'create'              => __('Create', 'tag-groups'),
      'newgroup'            => __('new', 'tag-groups'),
      'placeholder_new'     => __('label', 'tag-groups'),
      'tooltip_delete'      => __('Delete this group.', 'tag-groups'),
      'tooltip_newbelow'    => __('Create a new group below.', 'tag-groups'),
      'tooltip_addnew'      => __('Add New Tag Group', 'tag-groups'),
      'tooltip_move_up'     => __('move up', 'tag-groups'),
      'tooltip_move_down'   => __('move down', 'tag-groups'),
      'tooltip_reload'      => __('reload', 'tag-groups'),
      'tooltip_next'        => __('next page', 'tag-groups'),
      'tooltip_previous'    => __('previous page', 'tag-groups'),
      'tooltip_go_to_page'  => __('go to page:', 'tag-groups'),
      'tooltip_showposts'   => __('Show posts', 'tag-groups'),
      'tooltip_showtags'    => __('Show tags', 'tag-groups'),
      /* translators: %s: tag group name */
      'rename_group'        => __('Rename group %s', 'tag-groups'),
      /* translators: %s: tag group name */
      'delete_group'        => __('Delete group %s', 'tag-groups'),
      /* translators: %s: tag group name */
      'move_up_group'       => __('Move %s up', 'tag-groups'),
      /* translators: %s: tag group name */
      'move_down_group'     => __('Move %s down', 'tag-groups'),
      /* translators: %s: tag group name */
      'show_tags_group'     => __('Show tags in group %s', 'tag-groups'),
      /* translators: %s: tag group name */
      'show_posts_group'    => __('Show posts in group %s', 'tag-groups'),
      'group_name'          => __('Group name', 'tag-groups'),
      'new_group_name'      => __('New group name', 'tag-groups'),
      'save_group_name'     => __('Save group name', 'tag-groups'),
      'create_group'        => __('Create group', 'tag-groups'),
      /* translators: %s: page number */
      'current_page_reload' => __('Page %s, current page. Reload', 'tag-groups'),
      /* translators: %s: number of groups */
      'filter_results'      => __('Groups found: %s', 'tag-groups'),
      'new_tab'             => __('(opens in a new tab)', 'tag-groups'),
  )); ?>;

  var tagGroupsAjaxParameters = {"ajaxurl": "<?php echo esc_url($admin_url) ?>", "postsurl": "<?php echo esc_url($post_url) ?>", "tagsurl": "<?php echo esc_url($tags_url) ?>", "items_per_page": "<?php echo esc_attr($items_per_page) ?>", "show_parents": false, "isPremium": false};
  var tagGroupsData = {
    tag_groups_taxonomy: <?php echo wp_json_encode($taxonomies); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>,
  };

  jQuery(document).ready(function () {
    tagGroupsData.tag_groups_task = "refresh";
    tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);

    var tagGroupsFilterTimer;
    jQuery('#filter_label').on('keyup', function(e){
      // ignore keys that do not change the value
      if (e.key === 'Tab' || e.key === 'Shift' || e.key === 'Escape') {
        return;
      }
      clearTimeout(tagGroupsFilterTimer);
      tagGroupsFilterTimer = setTimeout(function () {
        tagGroupsData.tag_groups_task = "refresh";
        tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);
      }, 300);
    });

    jQuery('#tag_group_administration').on('click', '.tg_edit_label', function () {
      tg_close_all_textfields();
      var element = jQuery(this);
      var position = element.attr("data-position");
      var label = escape_html(element.attr("data-label"));
      var isChild = element.attr("data-is_child") ? ' data-is_child="1"' : '';
      var active = jQuery('<span class="tg_edit_label_active"' + isChild + '><input data-position="' + position + '" data-label="' + label + '" value="' + label + '" aria-label="' + escape_html(tagGroupsLabels.group_name) + '"> <button type="button" class="button-link tg_edit_label_yes tg_icon_button tg_pointer" aria-label="' + escape_html(tagGroupsLabels.save_group_name) + '"><span class="dashicons dashicons-yes" aria-hidden="true" style="font-size: 30px; width: 30px; height: 30px; line-height: 1;"></span></button> </span>');
      element.replaceWith(active);
      active.children('input').trigger('focus').trigger('select');
    });

    // Escape cancels renaming and returns focus to the group name
    jQuery('#tag_group_administration').on('keydown', '.tg_edit_label_active input', function (e) {
      if (e.key === 'Escape') {
        e.preventDefault();
        tg_close_textfield(jQuery(this).parent(), false, tagGroupsLabels).trigger('focus');
      }
    });

    jQuery('#tag_group_administration').on('keypress', '.tg_edit_label_active', function (e) {
      if (e.keyCode == 13 && jQuery(e.target).is('input')) {
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
      if (e.keyCode == 13 && jQuery(e.target).is('input')) {
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
        var answer = confirm(<?php echo wp_json_encode(__('Do you really want to delete this tag group?', 'tag-groups')); ?>);
        if (answer) {
          var tagGroupsData = {
            tag_groups_task: 'delete',
            tag_groups_position: position,
            tag_groups_taxonomy: <?php echo wp_json_encode($taxonomies); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>,
          };
          tg_do_ajax(tagGroupsAjaxParameters, tagGroupsData, tagGroupsLabels);

        } else {
          jQuery('.tg_sort_tr[data-position='+position+'] td').removeClass('tg_ask_delete');
          jQuery('.tg_delete[data-position="'+position+'"]').trigger('focus');
        }
      }, 500);
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
      // Let rows be dragged by the group name button; only the icon buttons and inputs cancel a drag.
      cancel: "input,textarea,select,option,.tg_icon_button",
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
