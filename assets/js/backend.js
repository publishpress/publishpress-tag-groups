/*!
 * Last modified: 2021/09/03 13:50:24
 * Part of the WordPress plugin PublishPress Tag Groups
 * Plugin URI: https://publishpress.com/tag-groups/
 * Author: Christoph Amthor
 * License: GNU GENERAL PUBLIC LICENSE, Version 3
 */

/*
 * Makes the actual Ajax request and populates the table, the pager and the message box
 */
function tg_do_ajax(tg_params, send_data, labels) {
  var nonce = jQuery('#tg_nonce').val();
  var data = {
    action: 'tg_ajax_manage_groups',
    nonce: nonce,
  };
  jQuery.extend(send_data, data);

  send_data.tag_groups_start_position = jQuery('#tg_start_position').val();

  send_data.tag_groups_filter_label = jQuery('#filter_label').val();

  var message_output = '';

  jQuery('#tg_groups_container').fadeTo(200, 0.7);

  /*
   * Send request and parse response
   */
  jQuery.ajax({
    url: tg_params.ajaxurl,
    data: send_data,
    dataType: 'text',
    method: 'post',
    success: function (data) {
      try {
        // strip anything before the first opening {
        const dataOld = data;
        data = data.replace(/[^{]*{/, '{');
        // workaround for strange layout issue
        if (data !== dataOld) {
          jQuery('#wpfooter').hide();
        }
        var dataParsed = JSON.parse(data);
      } catch (e) {
        tg_display_error(
          e.message + '\n\n' + data.toString(),
          tg_params.isPremium
        );
        return false;
      }
      var status = dataParsed.data;
      var message = dataParsed.supplemental.message;
      var nonce = dataParsed.supplemental.nonce;
      var showParents = tg_params.show_parents || false;

      // write new nonce
      if (nonce !== '') {
        jQuery('#tg_nonce').val(nonce);
      }
      if (status === 'success') {
        var groups = dataParsed.supplemental.groups;
        var start_position = dataParsed.supplemental.start_position;
        if (start_position !== '') {
          jQuery('#tg_start_position').val(start_position);
        } else {
          start_position = send_data.start_position;
        }

        var max_number = dataParsed.supplemental.max_number;
        var output = '';

        var newGroupRow =
        '<tr class="tg_new_group_row" style="display:none;" id="tg_new_group">\n' +
        '<td style="display:none;">' + labels.newgroup + '</td>\n' +
        '<td colspan="5"><input placeholder="' + labels.placeholder_new + '">' +
        '<span class="tg_new_yes dashicons dashicons-yes tg_pointer" style="font-size: 30px; line-height: 1;"></span>' +
        '</td>\n' +
        '</tr>\n';

        output += newGroupRow;

        // disable sorting by drag and drop if the list is not complete
        if (dataParsed.supplemental.is_filtered) {
          jQuery('#tg_groups_container')
          .removeClass('ui-sortable')
          .sortable('disable');
        } else {
          jQuery('#tg_groups_container')
          .addClass('ui-sortable')
          .sortable('enable');
        }

        if (max_number > 0 || dataParsed.supplemental.is_filtered) {
          if (dataParsed.supplemental.parents_available) {
            jQuery('.tg_group_admin_parent').css('display', 'block');
          } else {
            jQuery('.tg_group_admin_parent').css('display', 'none');
          }
          if ((dataParsed.supplemental.only_parents && !dataParsed.supplemental.is_filtered) || max_number === 0) {
            output += tg_html_first_group(labels);
          }

          for (var key in groups) {
            var data_set = groups[key];
            if (data_set.id != null) {
              if (showParents && data_set.is_parent) {
                output +=
                  '<tr class="tg_sort_tr tg_is_parent" data-position="' +
                  data_set.position +
                  '">\n';
                output +=
                  '<td class="tg_label_column">';
                output += '<span class="dashicons dashicons-networking" title="' + labels.tooltip_parent + '"></span> ';
                output += '<div style="display:inline-block" class="tg_edit_label tg_text tg_truncate" data-position="' +
                  data_set.position +
                  '" data-label="' +
                  escape_html(data_set.label) +
                  '">';
                output +=
                  escape_html(data_set.label) +
                  '<span class="dashicons dashicons-edit tg_pointer" style="display:none;"></span></div></td>\n';
                output += '<td colspan="2"></td>';
              } else {
                output +=
                  '<tr class="tg_sort_tr" data-position="' + data_set.position + '">\n';

                output +=
                  '<td class="tg_label_column"><div class="tg_edit_label tg_text tg_truncate tg_child_label" data-position="' +
                  data_set.position +
                  '" data-label="' +
                  escape_html(data_set.label) +
                  '" data-is_child="1">';
                output +=
                  escape_html(data_set.label) +
                  '<span class="dashicons dashicons-edit tg_pointer" style="display:none;"></span></div></td>\n';
                if (dataParsed.supplemental.parents_available) {
                  output +=
                    '<td class="tg_hide_when_drag"><div class="tg_truncate">' +
                    data_set.parent_label +
                    '</div></td>\n';
                }
                output +=
                  '<td class="tg_hide_when_drag"><div class="tg_term_amounts">';
                if (tg_params.tagsurl !== '') {
                  output +=
                    '<a href="' +
                    tg_params.tagsurl +
                    '&term-filter=' +
                    data_set.id +
                    '">' +
                    data_set.amount +
                    '</a>';
                } else {
                  output += data_set.amount + '</span>';
                }
                output += '</div>';
                output += '</td>\n';
              }
              let outputFilters = '';
              if (tg_params.tagsurl !== '') {
                outputFilters +=
                  '<a href="' +
                  tg_params.tagsurl +
                  '&term-filter=' +
                  data_set.id +
                  '"><span class="tg_pointer dashicons dashicons-tag"></span></a> ';
              }
              if (tg_params.postsurl !== '') {
                outputFilters +=
                  '<a href="' +
                  tg_params.postsurl +
                  '&post_status=all&tg_filter_posts_value=' +
                  data_set.id +
                  '" title="' +
                  labels.tooltip_showposts +
                  '"><span class="tg_pointer dashicons dashicons-admin-page"></span></a>';
              }
              output += outputFilters
                ? '<td class="tg_hide_when_drag">' + outputFilters + '</td>\n'
                : '';
              output += '<td class="tg_hide_when_drag">';
              output +=
                '<span class="tg_delete tg_pointer dashicons dashicons-trash" data-position="' +
                data_set.position +
                '"></span>';
              output += '</td>\n';
              output += '<td class="tg_hide_when_drag">';

              output +=
                '<div style="overflow:hidden; position:relative; height:20px; clear:both;">';
              if (!dataParsed.supplemental.is_filtered && data_set.position > 1) {
                output +=
                  '<span class="tg_up tg_pointer dashicons dashicons-arrow-up" data-position="' +
                  data_set.position +
                  '"></span>';
              }
              output += '</div>';

              output +=
                '<div style="overflow:hidden; position:relative; height:20px; clear:both;">';
              if (!dataParsed.supplemental.is_filtered && data_set.position < max_number) {
                output +=
                  '<span class="tg_down tg_pointer dashicons dashicons-arrow-down" data-position="' +
                  data_set.position +
                  '"></span>';
              }
              output += '</div>';
              output += '<td>' + data_set.id + '</td>\n';

              output += '</td>\n';
              output += '</tr>\n';

              // hidden row for adding a new group
              var newGroupRow =
              '<tr class="tg_new_group_row" style="display:none;" id="tg_new_group">\n' +
              '<td style="display:none;">' + labels.newgroup + '</td>\n' +
              '<td colspan="5"><input placeholder="' + labels.placeholder_new + '">' +
              '<span class="tg_new_yes dashicons dashicons-yes tg_pointer" style="font-size: 30px; line-height: 1;"></span>' +
              '</td>\n' +
              '</tr>\n';

              // Prepend new group row before existing groups
              output = newGroupRow + output;
            }
          }
        } else {
          // no tag groups yet
          output += tg_html_first_group(labels);
        }

        // move add new group button outside the table
        const $tagGroupContainer = jQuery('#new_group');

        if ($tagGroupContainer.length){
          const $tagExistingButton = $tagGroupContainer.find('.new_group_pointer.button');

          if (!$tagExistingButton.length){
            const $addTagGroupButton = jQuery('<span>', {
              class: 'new_group_pointer button',
              text: labels.tooltip_addnew,
            });

            $addTagGroupButton.on('click', function () {
              var $row = jQuery('#tg_new_group');
                    
                // Ensure the row is fully removed and re-added
                if (!$row.length) {
                  jQuery('#tg_groups_container table').prepend(newGroupRow);
                  $row = jQuery('#tg_new_group'); // Re-select the newly added row
                }
              
                // Append a fresh new row
                jQuery('#tg_new_group').fadeIn();
                
                jQuery('#tg_groups_container table').prepend(newGroupRow);
                jQuery('#tg_new_group input').val('').show().trigger('focus');
            });

            $tagGroupContainer.append($addTagGroupButton);
          }
        }

        // write table of groups
        jQuery('#tg_groups_container').html(output);

        // pager
        var pager_output = '';
        var page, current_page;
        var items_per_page = Number(tg_params.items_per_page);
        if (items_per_page < 1) {
          items_per_page = 1;
        }
        current_page = Math.floor(start_position / items_per_page) + 1;
        max_page = Math.floor((max_number - 1) / items_per_page) + 1;

        if (current_page > 1) {
          pager_output +=
            '<button class="button-secondary tg_pager_button" data-page="' +
            (current_page - 1) +
            '" title="' +
            labels.tooltip_previous +
            '"><span class="dashicons dashicons-arrow-left-alt2"></span></button>';
        } else {
          pager_output +=
            '<button class="button-secondary tg_pager_button" disabled><span class="dashicons dashicons-arrow-left-alt2"></span></button>';
        }

        for (i = 1; i <= max_number; i += items_per_page) {
          page = Math.floor(i / items_per_page) + 1;
          if (page == current_page) {
            pager_output +=
              '<button class="tg_reload_button tg_pointer button-secondary" id="tg_groups_reload"><span class="dashicons dashicons-update"></span></button>';
          } else {
            pager_output +=
              '<button class="button-secondary tg_pager_button" data-page="' +
              page +
              '" title="' +
              labels.tooltip_go_to_page + ' ' + page +
              '"><span>' +
              page +
              '</span></button>';
          }
        }

        if (current_page < max_page) {
          pager_output +=
            '<button class="button-secondary tg_pager_button" data-page="' +
            (current_page + 1) +
            '" title="' +
            labels.tooltip_next +
            '"><span class="dashicons dashicons-arrow-right-alt2"></span></button>';
        } else {
          pager_output +=
            '<button class="button-secondary tg_pager_button" disabled><span class="dashicons dashicons-arrow-right-alt2"></span></button>';
        }

        jQuery('#tg_pager_container').fadeOut(200, function () {
          jQuery(this)
            .html(pager_output)
            .fadeIn(400, function () {
              jQuery('#tg_pager_container_adjuster').css({
                height: Number(jQuery('#tg_pager_container').height()) + 10,
              });
            });
        });

        if (message != '') {
          message_output +=
            '<div class="notice notice-success"><p>' +
            message +
            '</p></div>';
        } else {
          message_output += '';
        }
        jQuery('#tg_message_container').fadeTo(200, 0, function () {
          jQuery(this).html(message_output).fadeTo(400, 1);
        });
        jQuery('#new_parent').val('');
      } else {
        if (message == '') {
          message = 'Error loading data.';
          console.log(data);
        }
        message_output +=
          '<div class="notice notice-error"><p>' +
          message +
          '</p></div>';

        jQuery('#tg_message_container').fadeTo(200, 0, function () {
          jQuery(this).html(message_output).fadeTo(400, 1);
        });
      }
      jQuery('#tg_groups_container').fadeTo(200, 1);
    },
    error: function (xhr, textStatus, errorThrown) {
      tg_display_error(xhr.responseText, tg_params.isPremium);
    },
  });
}

function tg_html_first_group(labels) {
  let output = '<tr id="tg_new_1">\n';
  output +=
    '<td colspan="2" style="text-align: left;">' + 
    '<span style="display: flex; align-items: center; gap: 10px;">' + 
    '<input data-position="0" placeholder="' + labels.newgroup + '">' +
    '<span class="tg_new_yes dashicons dashicons-yes tg_pointer" data-id="1" style="font-size: 30px; line-height: 1;"></span>' +
    '</span>' +
    '</td>\n';
  output += '</tr>\n';
  return output;
}

function tg_display_error(message, isPremium) {
  console.log('[Tag Groups] ' + message);
  let url;
  if (isPremium) {
    url =
      'https://publishpress.com/tag-groups/';
  } else {
    url =
      'https://publishpress.com/tag-groups/';
  }

  message = message.replace(/\n/g, '<br>');
  message_output =
    '<tr><td colspan="6"><div class="notice notice-error"><p>Error loading groups. Please check the error description below and read the <a href="' +
    url +
    '" target="_blank">troublshooting page</a>.<br><br>' +
    message +
    '</p><div clear="both"></div></div></td></tr>';

  jQuery('#tg_groups_container')
    .fadeTo(200, 0, function () {
      jQuery(this).html(message_output).fadeTo(400, 1);
    })
    .removeClass('ui-sortable')
    .sortable('disable');
}

/*
 * Turn an editable label field back into normal text
 */
function tg_close_textfield(element, saved) {
  const position = element.children(':first').attr('data-position');
  var label;
  const isChild = element.attr('data-is_child');
  if (saved) {
    label = escape_html(element.children(':first').attr('value'));
  } else {
    label = escape_html(element.children(':first').attr('data-label'));
  }
  if (isChild) {
    element.replaceWith(
      '<span class="tg_edit_label tg_text tg_child_label" data-position="' +
        position +
        '" data-label="' +
        label +
        '" data-is_child=1>' +
        label +
        ' <span class="dashicons dashicons-edit tg_pointer" style="display:none;"></span></span>'
    );
  } else {
    element.replaceWith(
      '<span class="tg_edit_label tg_text" data-position="' +
        position +
        '" data-label="' +
        label +
        '">' +
        label +
        ' <span class="dashicons dashicons-edit tg_pointer" style="display:none;"></span></span>'
    );
  }
}

/*
 * Toggling the "new group" boxes
 */
function tg_toggle_clear() {
  var row = jQuery('#tg_new_');
  if (row.is(':visible')) {
    row.find('input').val('');
    row.children().fadeOut(300, function () {
      row.slideUp(600);
    });
  } else {
    jQuery('[id^="tg_new_"]:visible')
      .children()
      .fadeOut(300, function () {
        jQuery('[id^="tg_new_"]:visible').slideUp(400);
      });
    row.delay(200).slideDown(400, function () {
      row.children().fadeIn(300);
      row.find('input').val('').show().trigger('focus');
    });
  }
}

/*
 * Parse all editable label fields in order to turn them into normal text
 */
function tg_close_all_textfields() {
  jQuery('.tg_edit_label_active').each(function () {
    tg_close_textfield(jQuery(this), false);
  });
}

/*
 * Prevent HTML from breaking
 */
function escape_html(text) {
  return text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}
