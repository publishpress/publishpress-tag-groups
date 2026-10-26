/*!
 * Last modified: 2021/10/27 17:52:13
 * Part of the WordPress plugin PublishPress Tag Groups
 * Plugin URI: https://publishpress.com/tag-groups/
 * Author: Christoph Amthor
 * License: GNU GENERAL PUBLIC LICENSE, Version 3
 */

var TagGroupsBase = {
  accordion: function (id, options, delay) {
    // Keep any callbacks the caller passed in.
    var userCreate = options['create'],
      userBeforeActivate = options['beforeActivate'],
      userActivate = options['activate'];
    options['create'] = function (event, ui) {
      if (delay) {
        jQuery(event.target).removeClass('tag-groups-cloud-hidden');
      }
      TagGroupsBase.accordionA11y(event.target);
      if (typeof userCreate === 'function') {
        return userCreate.apply(this, arguments);
      }
    };
    /**
     * jQuery UI sets role="tab", tabindex and aria-* on the headers synchronously in _toggle(),
     * right after beforeActivate. Re-apply our semantics once it has done so, and again on activate.
     */
    options['beforeActivate'] = function (event, ui) {
      var target = event.target;
      setTimeout(function () {
        TagGroupsBase.accordionA11y(target);
      }, 0);
      if (typeof userBeforeActivate === 'function') {
        return userBeforeActivate.apply(this, arguments);
      }
    };
    options['activate'] = function (event, ui) {
      TagGroupsBase.accordionA11y(event.target);
      if (typeof userActivate === 'function') {
        return userActivate.apply(this, arguments);
      }
    };
    jQuery('#' + id).accordion(options);
  },
  tabs: function (id, options, delay) {
    if (delay) {
      options['create'] = function (event) {
        jQuery(event.target).removeClass('tag-groups-cloud-hidden');
      };
    }
    jQuery('#' + id).tabs(options);
  },
  /**
   * Keeps the accordion headers as real headings (h3) and moves the interactive
   * semantics (role="button", aria-expanded, aria-controls, roving tabindex)
   * to a span inside each heading.
   *
   * The jQuery UI icon must stay a direct child of the header (jQuery UI toggles
   * it with .children()), so only the remaining content is wrapped.
   *
   * Clicks and Enter/Space still bubble to the header, where jQuery UI handles them.
   * Arrow/Home/End are handled here, because jQuery UI looks up the focused
   * element among the headers.
   */
  accordionA11y: function (element) {
    var $ = jQuery,
      $accordion = $(element),
      instance = $accordion.accordion('instance'),
      toggleClass = 'tag-groups-accordion-toggle',
      $active,
      $toggles,
      $current;

    if (!instance || !instance.headers) {
      return;
    }

    $active = instance.active || $();

    if ('tablist' === $accordion.attr('role')) {
      $accordion.removeAttr('role');
    }

    instance.headers.each(function () {
      var $header = $(this),
        $panel = $header.next(),
        $toggle = $header.children('.' + toggleClass),
        expanded = $active.length > 0 && $active[0] === this;

      if (!$toggle.length) {
        $header
          .contents()
          .not('.ui-accordion-header-icon')
          .wrapAll('<span class="' + toggleClass + '"></span>');
        $toggle = $header.children('.' + toggleClass);
        if (!$toggle.length) {
          $toggle = $('<span class="' + toggleClass + '"></span>').appendTo($header);
        }
        $toggle.on('keydown', TagGroupsBase.accordionKeydown);
        // A click on the header padding or icon used to focus the header; focus the control instead
        $header.on('click', function () {
          var toggle = $(this).children('.' + toggleClass)[0];
          if (toggle && document.activeElement !== toggle) {
            $(toggle).trigger('focus');
          }
        });
        $panel.on('keydown', function (event) {
          // Ctrl+Up: jQuery UI focuses the header, which is no longer focusable
          if (event.ctrlKey && 38 === event.keyCode) {
            $(this).prev().children('.' + toggleClass).trigger('focus');
          }
        });
      }

      $toggle.attr({
        role: 'button',
        'aria-expanded': expanded ? 'true' : 'false',
        'aria-controls': $panel.attr('id'),
      });

      if ($header.hasClass('ui-state-disabled')) {
        $toggle.attr('aria-disabled', 'true');
      } else {
        $toggle.removeAttr('aria-disabled');
      }

      $header.removeAttr('role aria-selected aria-expanded aria-controls tabindex');

      if ('tabpanel' === $panel.attr('role')) {
        $panel.removeAttr('role aria-labelledby');
      }
    });

    // Roving tabindex: the active header, else the one that had it, else the first one
    $toggles = instance.headers.children('.' + toggleClass);
    $current = $active.length ? $active.children('.' + toggleClass) : $toggles.filter('[tabindex="0"]').first();
    if (!$current.length) {
      $current = $toggles.first();
    }
    $toggles.attr('tabindex', '-1');
    $current.attr('tabindex', '0');
  },
  accordionKeydown: function (event) {
    var $ = jQuery,
      instance,
      $toggles,
      length,
      index,
      toFocus = null;

    if (event.altKey || event.ctrlKey) {
      return;
    }

    instance = $(this).parent().parent().accordion('instance');
    if (!instance || !instance.headers) {
      return;
    }

    $toggles = instance.headers.children('.tag-groups-accordion-toggle');
    length = $toggles.length;
    index = $toggles.index(this);

    switch (event.keyCode) {
      case 39: // right
      case 40: // down
        toFocus = $toggles[(index + 1) % length];
        break;
      case 37: // left
      case 38: // up
        toFocus = $toggles[(index - 1 + length) % length];
        break;
      case 36: // home
        toFocus = $toggles[0];
        break;
      case 35: // end
        toFocus = $toggles[length - 1];
        break;
      default:
        // Enter and Space bubble to the header, where jQuery UI activates the panel
        return;
    }

    event.preventDefault();
    event.stopPropagation();
    $toggles.attr('tabindex', '-1');
    $(toFocus).attr('tabindex', '0').trigger('focus');
  },
};
