/*!
 * Last modified: 2021/10/27 17:52:13
 * Part of the WordPress plugin PublishPress Tag Groups
 * Plugin URI: https://publishpress.com/tag-groups/
 * Author: Christoph Amthor
 * License: GNU GENERAL PUBLIC LICENSE, Version 3
 */

var TagGroupsBase = {
  accordion: function (id, options, delay) {
    if (delay) {
      options['create'] = function (event) {
        jQuery(event.target).removeClass('tag-groups-cloud-hidden');
      };
    }
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
};

/**
 * Search panel above a tabbed or accordion tag cloud (search_panel="1")
 *
 * Reads the tags from the cloud it belongs to, then shows live matches, recently clicked tags
 * (kept in localStorage for this browser only) and the most popular tags (by data-count).
 */
var TagGroupsSearchPanel = {
  storageKey: 'tagGroupsSearchRecent',
  maxRecent: 5,
  maxMatches: 20,

  initAll: function () {
    var panels = document.querySelectorAll('.tag-groups-search-panel');
    for (var i = 0; i < panels.length; i++) {
      TagGroupsSearchPanel.init(panels[i]);
    }
  },

  init: function (panel) {
    if (panel.getAttribute('data-ready')) {
      return;
    }
    var cloud = document.getElementById(panel.getAttribute('data-target'));
    var input = panel.querySelector('.tag-groups-search-input');
    if (!cloud || !input) {
      return;
    }
    panel.setAttribute('data-ready', '1');

    var tags = TagGroupsSearchPanel.collectTags(cloud);
    var popular = parseInt(panel.getAttribute('data-popular'), 10) || 0;

    TagGroupsSearchPanel.renderPopular(panel, tags, popular);
    TagGroupsSearchPanel.renderRecent(panel, tags);

    input.addEventListener('input', function () {
      TagGroupsSearchPanel.renderMatches(panel, tags, input.value);
    });
    // browsers can restore the typed text when going back to the page
    if (input.value) {
      TagGroupsSearchPanel.renderMatches(panel, tags, input.value);
    }
    input.addEventListener('keydown', function (event) {
      if ('Escape' === event.key) {
        input.value = '';
        TagGroupsSearchPanel.renderMatches(panel, tags, '');
      }
    });

    var remember = function (event) {
      var link = event.target.closest ? event.target.closest('a') : null;
      if (link && link.href && tags.byHref[link.href]) {
        TagGroupsSearchPanel.remember(tags.byHref[link.href]);
      }
    };
    panel.addEventListener('click', remember);
    cloud.addEventListener('click', remember);
  },

  collectTags: function (cloud) {
    var list = [];
    var byHref = {};
    var spans = cloud.querySelectorAll('.tag-groups-tag');
    for (var i = 0; i < spans.length; i++) {
      var link = spans[i].querySelector('a');
      if (!link || !link.href) {
        continue;
      }
      var label = spans[i].querySelector('.tag-groups-label') || link;
      var count = parseInt(spans[i].getAttribute('data-count'), 10) || 0;
      // the same tag can be in several groups
      if (byHref[link.href]) {
        byHref[link.href].count = Math.max(byHref[link.href].count, count);
        continue;
      }
      var tag = {
        name: label.textContent.trim(),
        href: link.href,
        target: link.getAttribute('target') || '',
        count: count,
      };
      byHref[link.href] = tag;
      list.push(tag);
    }
    return { list: list, byHref: byHref };
  },

  chip: function (tag, query) {
    var link = document.createElement('a');
    link.className = 'tag-groups-search-chip';
    link.href = tag.href;
    // keep the cloud's link_target
    if (tag.target) {
      link.target = tag.target;
      if ('_blank' === tag.target) {
        link.rel = 'noopener';
      }
    }
    var position = query ? tag.name.toLowerCase().indexOf(query) : -1;
    if (position < 0) {
      link.textContent = tag.name;
      return link;
    }
    var mark = document.createElement('mark');
    mark.textContent = tag.name.substr(position, query.length);
    link.appendChild(document.createTextNode(tag.name.substr(0, position)));
    link.appendChild(mark);
    link.appendChild(document.createTextNode(tag.name.substr(position + query.length)));
    return link;
  },

  fill: function (section, tags, query) {
    var target = section.querySelector('.tag-groups-search-list');
    while (target.firstChild) {
      target.removeChild(target.firstChild);
    }
    for (var i = 0; i < tags.length; i++) {
      target.appendChild(TagGroupsSearchPanel.chip(tags[i], query));
    }
  },

  renderMatches: function (panel, tags, value) {
    var section = panel.querySelector('.tag-groups-search-matches');
    var popular = panel.querySelector('.tag-groups-search-popular');
    var query = value.trim().toLowerCase();
    if ('' === query) {
      section.hidden = true;
      popular.hidden = !popular.querySelector('.tag-groups-search-chip');
      return;
    }
    var found = [];
    for (var i = 0; i < tags.list.length && found.length < TagGroupsSearchPanel.maxMatches; i++) {
      if (tags.list[i].name.toLowerCase().indexOf(query) !== -1) {
        found.push(tags.list[i]);
      }
    }
    TagGroupsSearchPanel.fill(section, found, query);
    section.querySelector('.tag-groups-search-empty').hidden = found.length > 0;
    section.hidden = false;
    popular.hidden = true;
  },

  renderPopular: function (panel, tags, amount) {
    var section = panel.querySelector('.tag-groups-search-popular');
    if (!amount) {
      return;
    }
    var sorted = tags.list.slice().sort(function (a, b) {
      return b.count - a.count;
    });
    sorted = sorted.slice(0, amount);
    if (!sorted.length || !sorted[0].count) {
      return;
    }
    TagGroupsSearchPanel.fill(section, sorted, '');
    section.hidden = false;
  },

  renderRecent: function (panel, tags) {
    var section = panel.querySelector('.tag-groups-search-recent');
    var recent = [];
    var stored = TagGroupsSearchPanel.load();
    for (var i = 0; i < stored.length; i++) {
      // only show tags that are in this cloud
      if (stored[i] && tags.byHref[stored[i].href]) {
        recent.push(tags.byHref[stored[i].href]);
      }
    }
    if (!recent.length) {
      return;
    }
    TagGroupsSearchPanel.fill(section, recent, '');
    section.hidden = false;
  },

  load: function () {
    try {
      var stored = JSON.parse(window.localStorage.getItem(TagGroupsSearchPanel.storageKey) || '[]');
      return Array.isArray(stored) ? stored : [];
    } catch (error) {
      return [];
    }
  },

  remember: function (tag) {
    var stored = TagGroupsSearchPanel.load().filter(function (item) {
      return item && item.href !== tag.href;
    });
    stored.unshift({ name: tag.name, href: tag.href });
    try {
      window.localStorage.setItem(
        TagGroupsSearchPanel.storageKey,
        JSON.stringify(stored.slice(0, TagGroupsSearchPanel.maxRecent))
      );
    } catch (error) {
      // storage can be unavailable, e.g. in private windows
    }
  },
};

if ('loading' === document.readyState) {
  document.addEventListener('DOMContentLoaded', TagGroupsSearchPanel.initAll);
} else {
  TagGroupsSearchPanel.initAll();
}
