---
title: "Shuffle Box – Shortcode Parameters"
order: 67
doc_category: publishpress-tag-groups
---

# Shuffle Box – Shortcode Parameters

**Table of Contents**

- [Shortcode](#shortcode)
- [Example](#example)
- [Data Types](#data-types)
- [Parameters](#parameters)
  - [Tags or Terms](#tags-or-terms)
  - [layout_mode](#layout_mode)
  - [taxonomy](#taxonomy)
  - [smallest](#smallest)
  - [largest](#largest)
  - [orderby](#orderby)
  - [order](#order)
  - [amount](#amount)
  - [hide_empty](#hide_empty)
  - [threshold](#threshold)
  - [prepend](#prepend)
  - [append](#append)
  - [link_append](#link_append)
  - [link_target](#link_target)
  - [show_tag_count](#show_tag_count)
  - [custom_title](#custom_title)
  - [custom_title_zero](#custom_title_zero)
  - [custom_title_plural](#custom_title_plural)
  - [add_premium_filter](#add_premium_filter)
  - [tags_post_id](#tags_post_id)
  - [Groups](#groups)
  - [include](#include)
  - [exclude](#exclude)
  - [groups_post_id](#groups_post_id)
  - [Filters](#filters)
  - [show_group_filter](#show_group_filter)
  - [show_text_filter](#show_text_filter)
  - [show_filter_all_groups](#show_filter_all_groups)
  - [initial_group](#initial_group)
  - [Labels and Messages](#labels-and-messages)
  - [placeholder](#placeholder)
  - [not_assigned_name](#not_assigned_name)
  - [show_all_name](#show_all_name)
- [Advanced Styling](#advanced-styling)
  - [div_id](#div_id)
  - [div_class](#div_class)

This guide shows you how to use and customize the shortcode for the Shuffle Box feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode

```
[tag_groups_shuffle_box]
```

## Example

```
[tag_groups_shuffle_box taxonomy=product_tag smallest=16 largest=16 placeholder="type here"]
```

## Data Types

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.

- **integer:** A number without decimal places.

- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters

### Tags or Terms

#### layout_mode

Determines how the tags are arranged.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | fitRows | `layout_mode=vertical` |

| option | description |
| --- | --- |
| fitRows | in horizontal rows |
| masonry | forming columns |
| vertical | a vertical list |

#### taxonomy

Restrict the tags only to these taxonomies. Available are only taxonomies that have been activated in the Settings.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of taxonomy names | Taxonomies selected in the settings under “Basics”. | `taxonomy=post_tag,product_tag` |

#### smallest

Font-size in pt of the smallest tags.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 12 | `smallest=12` |

#### largest

Font-size in pt of the largest tags.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 22 | `largest=20` |

#### orderby

Which field to use for sorting.

Since WordPress doesn’t natively offer natural sort order, we have to first order them by name and then by natural sort order. If you limit the number of tags with [amount](#amount), the amount of tags is therefore determined by cutting off with “name” sorting and then sorting them with “natural”.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | name | `orderby=natural` |

#### order

Whether to sort the tags in ascending or descending order. Ascending order also means from oldest to newest.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | ASC | `order=desc` |

#### amount

Maximum amount of tags. This is the total number for the case when all filters are off (“show all”). Buttons and text search reduce this number.

Note: High numbers of tags will affect performance.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 0 (unlimited) | `amount=30` |

#### hide_empty

Whether to hide or show tags that are not assigned to any post.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 (hide empty) | `hide_empty=1` |

#### threshold

Set a minimum count of published posts that use a tag for this tag to appear. Posts are counted across all groups. Please also pay attention to the value of hide_empty.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 | integer | `threshold=2` |

#### prepend

Prepend to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag. Please note that {count} is the total number – not per selected group.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `prepend="#"` |

#### append

Append to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag. Please note that {count} is the total number – not per selected group.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `append="{count}"` |

#### link_append

Append something to each tag’s link, for example a query.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `link_append="track=this"` |

Available only in the shortcode!

#### link_target

Set the “target” attribute for the links of the tags. Possible values are: _blank, _self, _parent or _top. In the shortcode you can also set the name of a frame.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string: “_blank”, “_self”, “_parent”, “_top”, or the name of a frame | empty (= opens in the same window, same as using _self) | `link_target="_blank"` |

#### show_tag_count

Whether to show the number of posts as tooltip (behind the tag description) when hovering the mouse over the tag. Please note that the value is the total number – not per selected group.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 (show) | `show_tag_count=0` |

#### custom_title

tooltip when hovering the mouse over a tag; placeholders: {count} for post count, {description} for tag description, {name} for tag name

If you also use custom_title_plural then the text of custom_title will be used for a post count of 1 and you can use here the singular form

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| depends on show_tag_count | string | `custom_title="One post uses {name}"` |

#### custom_title_zero

tooltip when hovering the mouse over a tag; placeholders: {count} for post count, {description} for tag description, {name} for tag name

This text will be used for tags that have a post count of 0 (i.e. they are not used with any published post).

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| not used | string | `custom_title_zero="No post uses {name}"` |

#### custom_title_plural

tooltip when hovering the mouse over a tag; placeholders: {count} for post count, {description} for tag description, {name} for tag name

This text will be used for tags that have a post count of >1. You can use here plural forms.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| not used | string | `custom_title_plural="{count} posts use {name}"` |

#### add_premium_filter

When you use the premium plugin, additional query parameters in the links make sure that we can distinguish between the same tag that is used in different groups.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 0 (no filter) | `add_premium_filter=1` |

#### tags_post_id

Display only tags that are assigned to the post (or page) with a specified ID. If the value is set to 0, the plugin will try to retrieve the current post ID. Turn it off with -1.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | -1 (all tags displayed) | `tags_post_id=0` |

### Groups

#### include

[Tag groups](https://publishpress.com/links/tag-groups) that will be considered in the tag cloud. In the shortcode you use the IDs, which you find in the left column in the list of groups. Empty or not used means that all tag groups will be used, but groups may be hidden according to other settings if they contain no tags.

With the “Premium” plan you can use here the IDs of parent groups, so that we will include all contained tag groups.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of group IDs | empty (use all) | `include="1,5,9"` |

#### exclude

Tag groups that should be excluded in the tag cloud. Empty or not used means that no tag groups will be excluded, but groups may be hidden according to other settings if they contain no tags.

With the “Premium” plan you can use here the IDs of parent groups, so that we will exclude all contained tag groups.

| **accepted values** | **default** | **example shortcode** |
| --- | --- | --- |
| comma-separated list of group IDs | none (use all) | `exclude="2,3,5"` |

#### groups_post_id

Display only groups of which at least one assigned tag is also assigned to the post (or page) with the ID x. If set to 0, it will try to retrieve the current post ID. Matching groups will be added to the list specified by the parameter include

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | -1 (all groups displayed). | `groups_post_id=14` |

### Filters

#### show_group_filter

Whether to show the names of the groups (included with “include”) as filter buttons.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 | `show_group_filter=0` |

#### show_text_filter

Whether to show a text input field where visitors can filter tags by their names.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 | `show_text_filter=0` |

#### show_filter_all_groups

Whether to a button where visitors can reset all filters by groups and show all tags.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 | `show_filter_all_groups=0` |

#### initial_group

Which group to use for filtering when the Shuffle Box is loaded. By default it shows all available tags of all groups. Use the ID of a group that you don’t display to start without showing any tags.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | -1 (all groups) | `initial_group=4` |

### Labels and Messages

#### placeholder

Placeholder for the input field of the text filter.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “search” | `placeholder="type here"` |

#### not_assigned_name

Label of the button for the “not assigned” group.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “not assigned” | `not_assigned_name="all the rest"` |

#### show_all_name

Label of the button to show all groups (turn off the filter by groups).

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “show all” | `show_all_name="no filter"` |

## Advanced Styling

You can set here IDs and classes to be referred to in CSS. You need some knowledge of CSS to use these options.

### div_id

Define an ID for the enclosing `<div>`.

Avoid words that are used by WordPress such as “content” or “main”. Each ID must be unique for the entire page.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (random value) | `div_id="sidebar_01"` |

### div_class

Define a class for the enclosing `<div>`. You can use here themes.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | cm-shufflebox-theme-default | `div_class="make-it-dark"` |
