---
title: "Tag List – Gutenberg Block"
order: 86
doc_category: publishpress-tag-groups
---

# Tag List – Gutenberg Block

**Table of Contents**

- [Tags and Taxonomies](#tags-and-taxonomies)
  - [Include taxonomies](#include-taxonomies)
  - [Smallest font size](#smallest-font-size)
  - [Largest font size](#largest-font-size)
  - [Tags per group](#tags-per-group)
  - [Order tags by](#order-tags-by)
  - [Sort order](#sort-order)
  - [Hide unused tags](#hide-unused-tags)
  - [Minimum post count for tags to appear](#minimum-post-count-for-tags-to-appear)
  - [Prepend](#prepend)
  - [Append](#append)
  - [Show post count in the tooltip](#show-post-count-in-the-tooltip)
  - [Custom title for post count = 0](#custom-title-for-post-count--0)
  - [Custom title for post count =1](#custom-title-for-post-count-1)
  - [Custom title for post count >1](#custom-title-for-post-count-1-1)
  - [Link target](#link-target)
  - [Append to link](#append-to-link)
  - [Add filter to tags for multiple groups](#add-filter-to-tags-for-multiple-groups)
  - [Use tags of the following post](#use-tags-of-the-following-post)
- [Groups](#groups)
  - [Hide empty groups](#hide-empty-groups)
  - [Include groups](#include-groups)
  - [Use groups of the following post](#use-groups-of-the-following-post)
- [Columns](#columns)
  - [Column count](#column-count)
  - [Gap between columns](#gap-between-columns)
  - [Avoid breaking groups over columns](#avoid-breaking-groups-over-columns)

With the Tag List feature, terms are displayed in columns under their group names. You can choose which groups to show and you can customize how the tags layout.

This guide will show you how to use the Gutenberg block that is available with the Tag List feature.

![Tag List - Gutenberg Block](https://docs-media.publishpress.com/publishpress-tag-groups/tag-list-gutenberg-10e3b6558f.webp)

## Tags and Taxonomies

### Include taxonomies

Select the “tag type”. By default we use all that are enabled in the [Tag Groups](https://publishpress.com/links/tag-groups) settings.

### Smallest font size

Font size of the tags with the smallest post count.

### Largest font size

Font size of the tags with the largest post count.

### Tags per group

Maximum number of tags per group.

### Order tags by

Field that we should compare when ordering the tag. If you choose “natural sorting” with “Tags per group” then the number will be determined after sorting by name (not natural sorting).

Note on random order: The order only changes when all object and page caches refresh.

### Sort order

ascending or descending order

### Hide unused tags

Hide tags with a post count of zero.

### Minimum post count for tags to appear

Set a minimum count of published posts that use a tag for this tag to appear. Posts are counted across all groups.

### Prepend

Character or text to place before each tag. You can use the placeholder {count} to insert the post count.

### Append

Character or text to place after each tag. You can use the placeholder {count} to insert the post count.

### Show post count in the tooltip

Show the number of posts that use that tag when hovering the mouse over the tag.

### Custom title for post count = 0

The custom title can be configured separately for tags that have a post count of zero. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

### Custom title for post count =1

Tooltip text when hovering the mouse over the tag. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

### Custom title for post count >1

The custom title can be configured separately for tags that have a post count > 1 so that you can use here plural in your text. You can use the placeholders {count} to insert the post count, {description} for the tag description and {name} for the tag name.

### Link target

Set the “target” attribute for the links of the tags. Possible values are:

- **_blank:** open in a new tab/window
- **_self:** open in the same frame
- **_parent:** open in the parent frame
- **_top:** open in the full tab/window of this frame

In the shortcode you can also set the name of a frame.

### Append to link

Append something to each tag’s link, for example a URL query.

### Add filter to tags for multiple groups

When you use the premium plugin, additional query parameters in the links make sure that we can distinguish between the same tag that is used in different groups. If the resulting posts are not complete and troubleshooting doesn’t help, you can disable it here.

### Use tags of the following post

Display only tags that are assigned to the post (or page) with a specified ID. If you need this option, you mostly want to “use this post”. The menu offers also some recent posts by title.

## Groups

### Hide empty groups

Whether to hide groups without tags.

### Include groups

[Tag groups](https://publishpress.com/links/tag-groups) that will be considered in the tag cloud.

### Use groups of the following post

Display only groups of which at least one assigned tag is also assigned to the selectd post (or page). Matching groups will be added to the list specified by **Include groups**.

## Columns

### Column count

The number of columns.

### Gap between columns

Width of the gap between columns.

### Avoid breaking groups over columns

Try to keep the group header and its tags together in one block. That makes it easier to understand which tags belong to which group, but it might lead to empty spaces at the bottom of columns.
