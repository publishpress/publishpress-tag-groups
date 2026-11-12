const { __ } = wp.i18n;

export const optionsOrderby = [
  { value: 'name', label: __('Name') },
  { value: 'natural', label: __('Natural sorting') },
  { value: 'count', label: __('Post count') },
  { value: 'slug', label: __('Slug') },
  { value: 'term_id', label: __('Term ID') },
  { value: 'description', label: __('Description') },
  { value: 'random', label: __('Random') },
  { value: 'term_order', label: __('Term Order') },
];

export const optionsTagStyle = [
  { value: '', label: __('Theme default') },
  { value: 'label-maker', label: __('Label Maker') },
  { value: 'heat', label: __('Heat Cloud') },
  { value: 'count-pills', label: __('Count Pills') },
  { value: 'image-cards', label: __('Image Cards') },
];

export const optionsOrder = [
  { value: 'ASC', label: __('Ascending') },
  { value: 'DESC', label: __('Descending') },
];

export const optionsTarget = [
  { value: '_self', label: '_self' },
  { value: '_blank', label: '_blank' },
  { value: '_parent', label: '_parent' },
  { value: '_top', label: '_top' },
];

export function getIncludeOptions(_this) {
  let optionsInclude = [];

  if (_this.state.groups && _this.state.groups.length > 0) {
    _this.state.groups.forEach((group) => {
      optionsInclude.push({ value: group.term_group, label: group.label });
    });
  }
  return optionsInclude;
}

export function getExcludeOptions(_this) {
  let optionsExclude = [];

  if (_this.state.groups && _this.state.groups.length > 0) {
    _this.state.groups.forEach((group) => {
      if (_this.state.selectedInclude.indexOf(group.term_group) < 0) {
        optionsExclude.push({ value: group.term_group, label: group.label });
      }
    });
  }
  return optionsExclude;
}

export function getTaxonomyOptions(_this) {
  let optionsTaxonomies = [];

  if (_this.state.taxonomies && _this.state.taxonomies.length > 0) {
    _this.state.taxonomies.forEach((taxonomy) => {
      optionsTaxonomies.push({ value: taxonomy.slug, label: taxonomy.name });
    });
  }
  return optionsTaxonomies;
}

export function getActiveGroupsOptions(_this) {
  const { groups_post_id } = _this.props.attributes;
  let optionsActiveGroups = [];
  let i = 0;
  if (
    (typeof _this.state.selectedInclude !== 'undefined' &&
      _this.state.selectedInclude.length) ||
    (typeof _this.state.selectedExclude !== 'undefined' &&
      _this.state.selectedExclude.length)
  ) {
    let expandedInclude, expandedExclude;
    if (_this.state.selectedInclude.length > 0) {
      expandedInclude = expandIds(
        _this.state.selectedInclude,
        _this.state.groups
      );
    } else {
      let groupIds = [];
      for (let group of _this.state.groups) {
        if (0 === group.term_group) {
          continue; // unassigned group is only included if mentioned in 'include'
        }
        groupIds.push(group.term_group);
      }
      expandedInclude = expandIds(groupIds, _this.state.groups);
    }
    if (typeof _this.state.selectedExclude !== 'undefined') {
      expandedExclude = expandIds(
        _this.state.selectedExclude,
        _this.state.groups
      );
    } else {
      expandedExclude = [];
    }
    _this.state.groups.forEach((group) => {
      if (
        expandedInclude.indexOf(group.term_group) > -1 &&
        expandedExclude.indexOf(group.term_group) == -1
      ) {
        let label;
        if (groups_post_id > -1) {
          label = i;
        } else {
          label = group.label;
        }
        optionsActiveGroups.push({ value: i, label });
        i++;
      }
    });
  } else if (_this.state.groups && _this.state.groups.length) {
    _this.state.groups.forEach((group) => {
      if (!group.term_group) return; // skipping unassigned
      if (group.is_parent) return;
      let label;
      if (groups_post_id > -1) {
        label = i;
      } else {
        label = group.label;
      }
      optionsActiveGroups.push({ value: i, label });
      i++;
    });
  }
  return optionsActiveGroups;
}

function expandIds(groupIds, allGroups) {
  let expandedGroups = [];
  for (let groupId of groupIds) {
    const group = getGroupById(allGroups, groupId);
    if (typeof group.is_parent !== 'undefined' && group.is_parent) {
      if (typeof group.children === 'undefined') {
        continue;
      }
      group.children.forEach((childId) => {
        if (groupIds.indexOf(childId) === -1) {
          expandedGroups.push(childId);
        }
      });
    } else {
      expandedGroups.push(group.term_group);
    }
  }
  return expandedGroups;
}

function getGroupById(allGroups, id) {
  for (let group of allGroups) {
    if (group.term_group === id) {
      return group;
    }
  }
  return { termgroup: -1, label: 'error' };
}

export function renderTabs(_this) {
  const { active, collapsible, mouseover } = _this.props.attributes;

  let options = {
    active: active < 0 ? false : active,
    collapsible: collapsible == 1,
  };

  if (mouseover) {
    options.event = 'mouseover';
  }

  setTimeout(() => {
    jQuery('#' + _this.state.uniqueId).tabs(options);
  }, 1000);
}

export function renderAccordion(_this) {
  const {
    active,
    collapsible,
    mouseover,
    heightstyle,
  } = _this.props.attributes;

  let options = {
    active: active < 0 ? false : active,
    collapsible: collapsible == 1,
    heightStyle: heightstyle,
  };

  if (mouseover) {
    options.event = 'mouseover';
  }

  setTimeout(() => {
    jQuery('#' + _this.state.uniqueId).accordion(options);
  }, 1000);
}
