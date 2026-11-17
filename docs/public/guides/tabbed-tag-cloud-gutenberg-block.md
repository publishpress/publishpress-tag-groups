---
title: "Tabbed Tag Cloud – Gutenberg Block"
order: 69
doc_category: publishpress-tag-groups
---

# Tabbed Tag Cloud – Gutenberg Block

**Table of Contents**

- [Tags and Terms](#tags-and-terms)
  - [Included taxonomies](#included-taxonomies)
  - [Smallest font size](#smallest-font-size)
  - [Largest font size](#largest-font-size)
  - [Tags per group](#tags-per-group)
  - [Order tags by](#order-tags-by)
  - [Sort order](#sort-order)
  - [Hide unused tags](#hide-unused-tags)
  - [Minimum post count for tags to appear](#minimum-post-count-for-tags-to-appear)
  - [Separator](#separator)
  - [Adjust separator size to following tag](#adjust-separator-size-to-following-tag)
  - [Separator size](#separator-size)
  - [Prepend](#prepend)
  - [Append](#append)
  - [Show post count in the tooltip](#show-post-count-in-the-tooltip)
  - [Custom title for post count = 0](#custom-title-for-post-count--0)
  - [Custom title for post count =1](#custom-title-for-post-count-1)
  - [Custom title for post count >1](#custom-title-for-post-count-1-1)
  - [Append to the link](#append-to-the-link)
  - [Link Target](#link-target)
  - [Add filter to tags for multiple groups](#add-filter-to-tags-for-multiple-groups)
  - [Use tags of the following post](#use-tags-of-the-following-post)
- [Groups and Tabs](#groups-and-tabs)
  - [Delay the display of the tabs until they are fully rendered](#delay-the-display-of-the-tabs-until-they-are-fully-rendered)
  - [Show tabs](#show-tabs)
  - [Hide empty tabs](#hide-empty-tabs)
  - [Open tabs on mouseover](#open-tabs-on-mouseover)
  - [Make panels collapsible](#make-panels-collapsible)
  - [Start with expanded tabs](#start-with-expanded-tabs)
  - [Which tab should be initially open?](#which-tab-should-be-initially-open)
  - [Include groups](#include-groups)
  - [Label on tab for not-assigned tags](#label-on-tab-for-not-assigned-tags)
  - [Use groups of the following post](#use-groups-of-the-following-post)
- [Advanced Styling](#advanced-styling)
  - [div id](#div-id)
  - [div class](#div-class)
  - [ul class](#ul-class)

The Tabbed Tag Cloud feature allows you to display a tag cloud with your groups as tabs.

This guide will show you how to use the Gutenberg block that is available with the Tabbed Tag Cloud feature.

![](https://docs-media.publishpress.com/publishpress-tag-groups/tabbed-tag-cloud-block-8f1ff325d8.webp)

## Tags and Terms

### Included taxonomies

Restrict the tags only to these taxonomies (“tag types”). Available are only taxonomies that have been activated in the [Tag Groups](https://publishpress.com/links/tag-groups) Settings.

### Smallest font size

Font-size in pt of the smallest tags.

### Largest font size

Font-size in pt of the largest tags.

### Tags per group

Maximum amount of tags in one cloud (per group). Pull the slider to zero to show an unlimited number.

### Order tags by

Which field to use for sorting the tags.

Note on using “Natural Sorting”: Since WordPress doesn’t natively offer natural sort order, we have to first order them by name and then by natural sort order. If you limit the number of tags, the amount of tags is therefore determined by cutting off with “name” sorting and then sorting them with “natural”.

Note on random order: The order only changes when all object and page caches refresh.

### Sort order

Whether to sort the tags in ascending or descending order. Ascending order also means from oldest to newest.

### Hide unused tags

Whether to hide or show tags that are not used by any post.

### Minimum post count for tags to appear

Set a minimum count of published posts that use a tag for this tag to appear. Posts are counted across all groups.

### Separator

A separator between the tags.

### Adjust separator size to following tag

Disable if you want to set a fixed size.

### Separator size

The size of the separator, if not automatically adjusted to the size of the following tag.

### Prepend

Prepend to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag.

### Append

Append to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag.

### Show post count in the tooltip

Whether to show the number of posts as tooltip (behind the tag description) when hovering the mouse over the tag.

### Custom title for post count = 0

The custom title can be configured separately for tags that have a post count of zero. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

### Custom title for post count =1

Tooltip text when hovering the mouse over the tag. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

### Custom title for post count >1

The custom title can be configured separately for tags that have a post count > 1 so that you can use here plural in your text. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

### Append to the link

Append something to each tag’s link, for example a query.

### Link Target

Set the “target” attribute for the links of the tags. Possible values are:

- **_blank:** open in a new tab/window
- **_self:** open in the same frame
- **_parent:** open in the parent frame
- **_top:** open in the full tab/window of this frame

In the shortcode you can also set the name of a frame.

### Add filter to tags for multiple groups

When you use the premium plugin, additional query parameters in the links make sure that we can distinguish between the same tag that is used in different groups. If the resulting posts are not complete and troubleshooting doesn’t help, you can disable it here.

### Use tags of the following post

Display only tags that are assigned to the post (or page) with a specified ID. If you need this option, you mostly want to “use this post”. The menu offers also some recent posts by title.

## Groups and Tabs

### Delay the display of the tabs until they are fully rendered

By default the tabs are only revealed after they are fully rendered. That way your visitors don’t see the raw structure with all tags listed below each other before the software is able to kick in. If you use this feature in an unusual position or experience issues, you can disable it here. (available from 1.30.5)

### Show tabs

Whether to show the tabs. This option is useful if you want to show only one group.

### Hide empty tabs

Whether to hide tabs without tags.

### Open tabs on mouseover

Whether tabs can be selected by hovering over with the mouse pointer (without clicking).

### Make panels collapsible

Whether tabs are collapsible (toggle open/close).

### Start with expanded tabs

Whether tabs are initially expanded or collapsed. Useful when you make tabs collapsible.

### Which tab should be initially open?

(from version 1.31.0) Select a group that should be open when the tabs load.

### Include groups

[Tag groups](https://publishpress.com/links/tag-groups) that will be considered in the tag cloud.

### Label on tab for not-assigned tags

The name used in the tab of not-assigned tags.

### Use groups of the following post

Display only groups of which at least one assigned tag is also assigned to the selectd post (or page). Matching groups will be added to the list specified by **Include groups**.

## Advanced Styling

You can set here IDs and classes to be referred to in CSS. You need some knowledge of CSS to use these options.

```
 or 
```

Rather than hiding tags that are not assigned to the post (or page), they can be styled differently. Tags will receive this class name with appended _1 or _0. (If you output the tags as an array, a new element with the key “assigned” will be true or false.)

### div id

Define an ID for the enclosing `<div>`.

1. If you display more than one tag cloud on the same page, each shortcode needs a different value. The safest solution is to leave it empty so that div_id automatically receives a random value.
2. Avoid words that are used by WordPress such as “content” or “main”. Each ID must be unique for the entire page.

### div class

Define a class for the enclosing `<div>`. Here you can apply some themes.

### ul class

Define a class for the `<ul>` that generates the tabs with the group labels.
