---
title: "Shuffle Box – Gutenberg Block"
order: 66
doc_category: publishpress-tag-groups
---

# Shuffle Box – Gutenberg Block

**Table of Contents**

- [Tags and Taxonomies](#tags-and-taxonomies)
  - [Layout mode](#layout-mode)
  - [Included taxonomies](#included-taxonomies)
  - [Smallest font size](#smallest-font-size)
  - [Largest font size](#largest-font-size)
  - [Amount of tags](#amount-of-tags)
  - [Order tags by](#order-tags-by)
  - [Sort order](#sort-order)
  - [Hide unused tags](#hide-unused-tags)
  - [Prepend](#prepend)
  - [Append](#append)
  - [Show the post count in the tooltip](#show-the-post-count-in-the-tooltip)
  - [Custom title for post count = 0](#custom-title-for-post-count--0)
  - [Custom title for post count =1](#custom-title-for-post-count-1)
  - [Custom title for post count >1](#custom-title-for-post-count-1-1)
  - [Link target](#link-target)
  - [Add filter to tags for multiple groups](#add-filter-to-tags-for-multiple-groups)
  - [Append to the link](#append-to-the-link)
  - [Use tags of the following post](#use-tags-of-the-following-post)
- [Groups](#groups)
  - [Include groups](#include-groups)
  - [Use groups of the following post](#use-groups-of-the-following-post)
- [Filters](#filters)
  - [Show input field to filter by text](#show-input-field-to-filter-by-text)
  - [Show buttons to filter by group](#show-buttons-to-filter-by-group)
  - [Show Filter "All Groups"](#show-filter-all-groups)
  - [Initially selected filter](#initially-selected-filter)
- [Labels and Messages](#labels-and-messages)
  - [Placeholder for text field](#placeholder-for-text-field)
  - [Label for "not assigned" button](#label-for-not-assigned-button)
  - [Label for "show all" button](#label-for-show-all-button)
- [Advanced Styling](#advanced-styling)[div id](#div-id)[div class](#div-class)[](#)

The Shuffle Box is a tag cloud that allows you to filter the tags by their names or by their [tag groups](https://publishpress.com/links/tag-groups).

This guide will show you how to use the Gutenberg block that is available with the Shuffle Box feature.

![](https://docs-media.publishpress.com/publishpress-tag-groups/shuffle-box-block-4a1a50ceb8.webp)

## Tags and Taxonomies

### Layout mode

Determines how the tags are arranged. In most cases you want to keep the default.

- **fitRows:** Tags appear in rows.
- **masonry:** Tags appear in a masonry layout.
- **vertical:** Tags appear vertically stacked.

### Included taxonomies

Restrict the tags only to these taxonomies (“tag types”). Available are only taxonomies that have been activated in the [Tag Groups](https://publishpress.com/links/tag-groups) Settings.

### Smallest font size

Font-size in pt of the smallest tags.

### Largest font size

Font-size in pt of the largest tags.

### Amount of tags

Maximum amount of tags. This is the total number for the case when all filters are off (“show all”). Buttons and text search reduce this number.

Pull the slider to zero to show an unlimited number.

Note: A high number of tags will affect performance.

### Order tags by

Which field to use for sorting the tags.

Note on using “Natural Sorting”: Since WordPress doesn’t natively offer natural sort order, we have to first order them by name and then by natural sort order. If you limit the number of tags with [amount](#amount-of-tags), the amount of tags is therefore determined by cutting off with “name” sorting and then sorting them with “natural”.

Note on random order: The order only changes when all object and page caches refresh.

### Sort order

Whether to sort the tags in ascending or descending order. Ascending order also means from oldest to newest.

### Hide unused tags

Whether to hide or show tags that are not used by any post.

### Prepend

Prepend to each tag name. Can obtain the placeholder {count} that will be replaced with the post count for that tag. Please note that {count} is the total number – not per selected group.

### Append

Append to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag. Please note that {count} is the total number – not per selected group.

### Show the post count in the tooltip

Whether to show the number of posts as tooltip (behind the tag description) when hovering the mouse over the tag. Please note that the value is the total number – not per selected group.

If you enter a custom title, this option will be unavailable.

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

### Add filter to tags for multiple groups

When you use the premium plugin, additional query parameters in the links make sure that we can distinguish between the same tag that is used in different groups.

### Append to the link

Append something to each tag’s link, for example a query.

### Use tags of the following post

Display only tags that are assigned to the post (or page) with a specified ID. If you need this option, you mostly want to “use this post”. The menu offers also some recent posts by title.

## Groups

### Include groups

Tag groups that will be considered in the tag cloud.

### Use groups of the following post

Display only groups of which at least one assigned tag is also assigned to the selectd post (or page). Matching groups will be added to the list specified by **Include groups**.

## Filters

### Show input field to filter by text

Whether to show a text input field where visitors can filter tags by their names.

### Show buttons to filter by group

Whether to show the names of the groups (included with “include”) as filter buttons.

### Show Filter “All Groups”

Whether to a button where visitors can reset all filters by groups and show all tags.

### Initially selected filter

Which group to use for filtering when the Shuffle Box is loaded. By default it shows all available tags of all groups. Use the ID of a group that you don’t display to start without showing any tags.

## Labels and Messages

### Placeholder for text field

Placeholder for the input field of the text filter.

### Label for “not assigned” button

Label of the button for the “not assigned” group.

### Label for “show all” button

Label of the button to show all groups (turn off the filter by groups).

## Advanced Styling

You can set here IDs and classes to be referred to in CSS. You need some knowledge of CSS to use these options.

### div id

Define an ID for the enclosing `<div>`. Avoid identifiers that are used by WordPress such as “content” or “main”. Each ID must be unique for the entire page.

### div class

Define a class for the enclosing `<div>`. Here you can apply some themes.

### 
