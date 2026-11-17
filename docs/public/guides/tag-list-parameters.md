---
title: "Tag List – Shortcode Parameters"
order: 87
doc_category: publishpress-tag-groups
---

# Tag List – Shortcode Parameters

Table of Contents

- [Shortcode](#0-toc-title)
- [Example](#1-toc-title)
- [Data Types](#data-types)
- [Parameters](#3-toc-title)[add_premium_filter](#4-toc-title)[amount](#5-toc-title)[append](#6-toc-title)[assigned_class](#7-toc-title)[column_count](#8-toc-title)[column_gap](#9-toc-title)[custom_title](#custom_title)[custom_title_zero](#custom_title_zero)[custom_title_plural](#custom_title_plural)[div_class](#13-toc-title)[div_id](#14-toc-title)[exclude_terms](#15-toc-title)[group_in_class](#16-toc-title)[groups_post_id](#17-toc-title)[h_level](#18-toc-title)[header_class](#19-toc-title)[hide_empty_content](#20-toc-title)[hide_empty](#21-toc-title)[threshold](#threshold)[include](#23-toc-title)[exclude](#exclude)[include_terms](#25-toc-title)[keep_together](#26-toc-title)[largest](#27-toc-title)[link_target](#28-toc-title)[link_append](#29-toc-title)[not_assigned_name](#30-toc-title)[order](#31-toc-title)[orderby](#32-toc-title)[prepend](#33-toc-title)[show_not_assigned](#34-toc-title)[show_all_groups](#35-toc-title)[show_tag_count](#36-toc-title)[smallest](#37-toc-title)[tags_div_class](#38-toc-title)[tags_post_id](#39-toc-title)[taxonomy](#40-toc-title)

This guide shows you how to use and customize the shortcode for the Tag List feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode [#](#0-toc-title)

```
[tag_groups_tag_list]
```

## Example [#](#1-toc-title)

```
[tag_groups_tag_list append="{count}"]
```

## Data Types [#](#data-types)

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters [#](#3-toc-title)

This overview assumes that you know how to use shortcodes. Shortcodes are the traditional WordPress way how to add features into posts, pages and widgets.

### add_premium_filter [#](#4-toc-title)

Modifies the URL – for the case that you use the same tag in various groups.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 (false) | 0 or 1 | `add_premium_filter=1` |

### amount [#](#5-toc-title)

the maximum number of tags per group to display

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 (no limit) | integer | `amount=10` |

### append [#](#6-toc-title)

adds something behind the tag name; placeholder: {count} for post count

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | append`=`” ({count})” |

### assigned_class [#](#7-toc-title)

adds class name to tags that shows whether the tag is assigned or not; assigned tags have “_1” appended, unassigned “_0” to the class name

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `assigned_class="colored-tags"` |

### column_count [#](#8-toc-title)

number of columns

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 2 | integer | `column_count=3` |

### column_gap [#](#9-toc-title)

width of the gap between columns

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 10px | string | `column_gap=20px` |

### custom_title [#](#custom_title)

tooltip when hovering the mouse over a tag; placeholders: {count} for post count, {description} for tag description, {name} for tag name

If you also use custom_title_plural then the text of custom_title will be used for a post count of 1 and you can use here the singular form

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| depends on show_tag_count | string | `custom_title="One post uses {name}"` |

### custom_title_zero [#](#custom_title_zero)

tooltip when hovering the mouse over a tag; placeholders: {count} for post count, {description} for tag description, {name} for tag name

This text will be used for tags that have a post count of 0 (i.e. they are not used with any published post).

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| not used | string | `custom_title_zero="No post uses {name}"` |

### custom_title_plural [#](#custom_title_plural)

tooltip when hovering the mouse over a tag; placeholders: {count} for post count, {description} for tag description, {name} for tag name

This text will be used for tags that have a post count of >1. You can use here plural forms.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| not used | string | `custom_title_plural="{count} posts use {name}"` |

### div_class [#](#13-toc-title)

class of the enclosing div for own styling

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| tag-groups-tag-list | string | `div_class="my-class"` |

### div_id [#](#14-toc-title)

ID of the enclosing div

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| random | string (must be unique on the page) | `div_id="my-id"` |

### exclude_terms [#](#15-toc-title)

a list of tags that should not appear

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | comma-separated list of tag IDs | `exclude_terms=` |

### group_in_class [#](#16-toc-title)

whether to add information about the groups into the class attribute; tg_tag_group_id_ + ID and tg_tag_group_label_ + tag name (spaces replaced with dashes)

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 (off) | 0 or 1 | `group_in_class=1` |

### groups_post_id [#](#17-toc-title)

ID of a post that the tags should be take from. 0 tries to get current post.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| -1 (off) | integer | `groups_post_id=5634` |

### h_level [#](#18-toc-title)

H3, then H4 etc.. Headings with the group names

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 3 | integer | `h_level=2` |

### header_class [#](#19-toc-title)

class for the headers

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `header_class="my-class"` |

### hide_empty_content [#](#20-toc-title)

hide empty groups

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 (off) | 0 or 1 | `hide_empty_content=1` |

### hide_empty [#](#21-toc-title)

hide tags with post count zero

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 1 | 0 or 1 | `hide_empty=0` |

### threshold [#](#threshold)

Set a minimum count of published posts that use a tag for this tag to appear. Posts are counted across all groups. Please also pay attention to the value of hide_empty.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 | integer | `hide_empty=0` |

### include [#](#23-toc-title)

groups to include

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| all | comma-separated list of group IDs | `include=2,3,5` |

### exclude [#](#exclude)

groups to exclude

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| none | comma-separated list of group IDs | `exclude=2,3,5` |

### include_terms [#](#25-toc-title)

tags to include (see also “hide_empty”)

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| all | comma-separated list of tag IDs | `include_terms=12,34,255` |

### keep_together [#](#26-toc-title)

whether to keep headings and tag block together; otherwise tags lists may continue in a new column

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 1 (on) | 0 or 1 | `keep_together=1` |

### largest [#](#27-toc-title)

max. font size in pt

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 12 | integer | `largest=32` |

### link_target [#](#28-toc-title)

target attribute of the tag link (_blank, _top, _self, …)

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `link_target="_top"` |

### link_append [#](#29-toc-title)

append query to the link

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `link_append=`“source=cloud” |

### not_assigned_name [#](#30-toc-title)

lets you choose a custom group name for not-assigned tags

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| not assigned | string | `not_assigned_name="unsorted tags"` |

### order [#](#31-toc-title)

ascending or descending order; use with orderby

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| asc | string (asc or desc) | `order=desc` |

### orderby [#](#32-toc-title)

by what to order the tags

use together with order

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| name | string | `orderby=natural` |

### prepend [#](#33-toc-title)

prepend to the tag; placeholder: {count} for post count

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `prepend="#"` |

### show_not_assigned [#](#34-toc-title)

whether to show tags that are not assigned to any group

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 | 0 or 1 | `show_not_assigned=1` |

### show_all_groups [#](#35-toc-title)

whether to show also empty groups

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 (off) | 0 or 1 | `show_all_groups=1` |

### show_tag_count [#](#36-toc-title)

show the post count in the title (deprecated option, better use custom_title

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 1 (on) | 0 or 1 | `show_tag_count=0` |

### smallest [#](#37-toc-title)

min. font size in pt

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 12 | integer | `smallest=14` |

### tags_div_class [#](#38-toc-title)

class name for the divs around tags

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `tags_div_class="my-class"` |

### tags_post_id [#](#39-toc-title)

Use post ID or zero for current post.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| -1 (not used) | integer | `tags_post_id=0` |

### taxonomy [#](#40-toc-title)

taxonomies (tag types) to use

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| all selected in the settings | comma-separated list of taxonomy names/slugs | `taxonomy=product_tag` |
