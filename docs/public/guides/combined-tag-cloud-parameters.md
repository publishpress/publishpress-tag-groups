---
title: "Simple Tag Cloud – Shortcode Parameters"
order: 19
doc_category: publishpress-tag-groups
---

# Simple Tag Cloud – Shortcode Parameters

**Table of Contents**

- [Shortcode](#shortcode)
- [Example](#example)
- [Data Types](#data-types)
- [Parameters](#parameters)
  - [Tags or Terms](#tags-or-terms)
  - [taxonomy](#taxonomy)
  - [smallest](#smallest)
  - [largest](#largest)
  - [orderby](#orderby)
  - [order](#order)
  - [amount](#amount)
  - [hide_empty](#hide_empty)
  - [threshold](#threshold)
  - [tags_post_id](#tags_post_id)
  - [assigned_class](#assigned_class)
  - [separator](#separator)
  - [adjust_separator_size](#adjust_separator_size)
  - [separator_size](#separator_size)
  - [prepend](#prepend)
  - [append](#append)
  - [link_target](#link_target)
  - [link_append](#link_append)
  - [show_tag_count](#show_tag_count)
  - [custom_title](#custom_title)
  - [custom_title_zero](#custom_title_zero)
  - [custom_title_plural](#custom_title_plural)
  - [Groups](#groups)
  - [include](#include)
  - [exclude](#exclude)
  - [groups_post_id](#groups_post_id)
- [Advanced Styling](#advanced-styling)
  - [div_id](#div_id)
  - [div_class](#div_class)

This guide shows you how to use and customize the shortcode for the Simple Tag Cloud feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode

```
[tag_groups_simple_cloud]
```

## Example

```
[tag_groups_simple_cloud include=3,5,6 smallest=16 largest=16]
```

## Data Types

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal place.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters

### Tags or Terms

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

Since WordPress doesn’t natively offer natural sort order, we have to first order them by name and then by natural sort order. If you limit the number of tags with amount, the amount of tags is therefore determined by cutting off with “name” sorting and then sorting them with “natural”.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | name | `orderby=natural` |

#### order

Whether to sort the tags in ascending or descending order. Ascending order also means from oldest to newest.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | ASC | `order=desc` |

#### amount

Maximum amount of tags in one cloud (per group).

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

#### tags_post_id

Display only tags that are assigned to the post (or page) with a specified ID. If the value is set to 0, the plugin will try to retrieve the current post ID. Turn it off with -1.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | -1 (all tags displayed) | `tags_post_id=0` |

#### assigned_class

A modification of the tags_post_id parameter: Rather than hiding tags that are not assigned to the post (or page), they can be styled differently. Tags will receive this class name with appended _1 or _0. (If you output the tags as an array, a new element with the key “assigned” will be true or false.)

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (off) | `assigned_class="color-for-assigned-tag"` |

#### separator

A separator between the tags.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `separator="•"` |

#### adjust_separator_size

Whether to adjust the separator’s size to the size of the following tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 | `adjust_separator_size=1` |

#### separator_size

The size of the separator, if not automatically adjusted to the size of the following tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 12 | `separator_size=12` |

#### prepend

Prepend to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `prepend="#"` |

#### append

Append to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `append="{count}"` |

#### link_target

Set the “target” attribute for the links of the tags. Possible values are: _blank, _self, _parent or _top. In the shortcode you can also set the name of a frame.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string: “_blank”, “_self”, “_parent”, “_top”, or the name of a frame | empty (= opens in the same window, same as using _self) | `link_target="_blank"` |

#### link_append

Append something to each tag’s link, for example a query.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `link_append="track=this"` |

Available only in the shortcode!

#### show_tag_count

Whether to show the number of posts as tooltip (behind the tag description) when hovering the mouse over the tag.

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

### Groups

#### include

[Tag groups](https://publishpress.com/links/tag-groups) that will be considered in the tag cloud. In the short code you use the IDs, which you find in the left column in the list of groups. Empty or not used means that all tag groups will be used.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of integers | all | `include="1,5,9"` |

#### exclude

groups to exclude

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| none | comma-separated list of group IDs | `exclude=2,3,5` |

#### groups_post_id

Display only groups of which at least one assigned tag is also assigned to the post (or page) with the ID x. If set to 0, it will try to retrieve the current post ID. Matching groups will be added to the list specified by the parameter include

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | -1 (all groups displayed). | `groups_post_id=14` |

## Advanced Styling

You can set here IDs and classes to be referred to in CSS. You need some knowledge of CSS to use these options.

### div_id

Define an ID for the enclosing `<div>`.

1. Avoid words that are used by WordPress such as “content” or “main”. Each ID must be unique for the entire page.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `div_id="sidebar_01"` |

### div_class

Define a class for the enclosing `<div>`.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | tag-groups-cloud-combined | `div_class="make-it-dark"` |
