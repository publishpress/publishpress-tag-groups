---
title: "Alphabetical Tag Index – Shortcode Parameters"
order: 10
doc_category: publishpress-tag-groups
---

# Alphabetical Tag Index – Shortcode Parameters

Table of Contents

- [Shortcode](#0-toc-title)
- [Example](#1-toc-title)
- [Data Types](#data-types)
- [Parameters](#3-toc-title)[amount](#4-toc-title)[append](#5-toc-title)[column_count](#6-toc-title)[column_gap](#7-toc-title)[custom_title](#custom_title)[custom_title_zero](#custom_title_zero)[custom_title_plural](#custom_title_plural)[div_class](#11-toc-title)[div_id](#12-toc-title)[exclude_letters](#13-toc-title)[exclude_terms](#14-toc-title)[h_level](#15-toc-title)[/h3>, , ... of the headings with the group names Default Possible Values Example 3 integer h_level=2 header_class](#16-toc-title)[hide_empty](#17-toc-title)[threshold](#18-toc-title)[include](#19-toc-title)[exclude](#exclude)[include_letters](#21-toc-title)[include_terms](#22-toc-title)[keep_together](#23-toc-title)[largest](#24-toc-title)[link_target](#25-toc-title)[link_append](#26-toc-title)[locale](#locale)[ignore_accents](#ignore_accents)[order](#29-toc-title)[prepend](#30-toc-title)[show_tag_count](#31-toc-title)[smallest](#32-toc-title)[tags_div_class](#33-toc-title)[tags_post_id](#34-toc-title)[taxonomy](#35-toc-title)

This guide shows you how to use and customize the shortcode for the Alphabetical Tag Index feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode [#](#0-toc-title)

```
[tag_groups_alphabetical_index]
```

## Example [#](#1-toc-title)

```
[tag_groups_alphabetical_index column_count=2]
```

## Data Types [#](#data-types)

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters [#](#3-toc-title)

This overview assumes that you know how to use shortcodes. Shortcodes are the traditional WordPress way how to add features into posts, pages and widgets.

### amount [#](#4-toc-title)

the maximum number of tags per group to display

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 (no limit) | integer | `amount=10` |

### append [#](#5-toc-title)

adds something behind the tag name; placeholder: {count} for post count

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | append`=`” ({count})” |

### column_count [#](#6-toc-title)

number of columns

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 2 | integer | `column_count=3` |

### column_gap [#](#7-toc-title)

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

### div_class [#](#11-toc-title)

class of the enclosing div for own styling

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| tag-groups-tag-list | string | `div_class="my-class"` |

### div_id [#](#12-toc-title)

ID of the enclosing div

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| random | string (must be unique on the page) | `div_id="my-id"` |

### exclude_letters [#](#13-toc-title)

letters/characters that should not appear in the list

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `exclude_letters=0123456789` |

### exclude_terms [#](#14-toc-title)

a list of tags that should not appear

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | comma-separated list of tag IDs | `exclude_terms=` |

### h_level [#](#15-toc-title)

<h3>/h3>, <h4></h4>, … of the headings with the group names

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 3 | integer | `h_level=2` |

### header_class [#](#16-toc-title)

class for the headers

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `header_class="my-class"` |

### hide_empty [#](#17-toc-title)

hide tags with post count zero

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 1 | 0 or 1 | `hide_empty=0` |

### threshold [#](#18-toc-title)

Set a minimum count of published posts that use a tag for this tag to appear. Posts are counted across all groups. Please also pay attention to the value of hide_empty.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 | integer | `threshold=2` |

### include [#](#19-toc-title)

groups to include

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| all | comma-separated list of group IDs | `include=2,3,5` |

### exclude [#](#exclude)

groups to exclude

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| none | comma-separated list of group IDs | `exclude=2,3,5` |

### include_letters [#](#21-toc-title)

which first letters to use

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| all initials of the displayed tags | string | `include_letters=abcdz` |

### include_terms [#](#22-toc-title)

tags to include (see also “hide_empty”)

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| all | comma-separated list of tag IDs | `include_terms=12,34,255` |

### keep_together [#](#23-toc-title)

whether to keep headings and tag block together; otherwise tags lists may continue in a new column

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 1 (on) | 0 or 1 | `keep_together=1` |

### largest [#](#24-toc-title)

max. font size in pt

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 12 | integer | `largest=32` |

### link_target [#](#25-toc-title)

target attribute of the tag link (_blank, _top, _self, …)

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `link_target="_top"` |

### link_append [#](#26-toc-title)

append query to the link

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `link_append=`“source=cloud” |

### locale [#](#locale)

Customize the locale (language-specific setting) that is used for sorting the first letters (initials). By default we use the language setting of your site. This sort order affects only the *order of the first letters*, not the*tag order* (see “orderby” and “order”).

Accepted values depend on which locales PHP can use on your server. They can have formats like “fr”, “fr_FR” or “fr_FR.UTF8”. Please ask your provider if you cannot find it by guessing or googling.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (site locale) | `locale="fr_FR.UTF8"` |

### ignore_accents [#](#ignore_accents)

If you turn this on, then accented (or umlaut) letters will be treated like normal letters. For a list of changes see [remove_accents](https://developer.wordpress.org/reference/functions/remove_accents/).

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 0 | `ignore_accents=1` |

### order [#](#29-toc-title)

ascending or descending order; use with orderby

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| asc | string (asc or desc) | `order=desc` |

by what to order the tags

| option | description |
| --- | --- |
| name | display name of the tag |
| natural | natural sort order |
| count | post count |
| slug | tag slug |
| term_id | ID of the tag |
| description | tag description |
| random | random order that changes when all object + page caches refresh |
| term_order | A custom sort order is available with a 3rd-party plugin. |

use together with order

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| name | string | `orderby=natural` |

### prepend [#](#30-toc-title)

prepend to the tag; placeholder: {count} for post count

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `prepend="#"` |

### show_tag_count [#](#31-toc-title)

show the post count in the title (deprecated option, better use custom_title

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 1 (on) | 0 or 1 | `show_tag_count=0` |

### smallest [#](#32-toc-title)

min. font size in pt

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 12 | integer | `smallest=14` |

### tags_div_class [#](#33-toc-title)

class name for the divs around tags

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `tags_div_class="my-class"` |

### tags_post_id [#](#34-toc-title)

Use post ID or zero for current post.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| -1 (not used) | integer | `tags_post_id=0` |

### taxonomy [#](#35-toc-title)

taxonomies (tag types) to use

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| all selected in the settings | comma-separated list of taxonomy names/slugs | `taxonomy=product_tag` |
