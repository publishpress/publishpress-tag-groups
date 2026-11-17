---
title: "Alphabetical Tag Cloud – Shortcode Parameters"
order: 7
doc_category: publishpress-tag-groups
---

# Alphabetical Tag Cloud – Shortcode Parameters

Table of Contents

- [Shortcode](#0-toc-title)
- [Example](#1-toc-title)
- [Data Types](#data-types)
- [Parameters](#3-toc-title)[Tags or Terms](#tags_or_terms)[taxonomy](#taxonomy)[smallest](#smallest)[largest](#largest)[include_terms](#include_terms)[exclude_terms](#exclude_terms)[orderby](#orderby)[order](#order)[amount](#amount)[hide_empty](#hide_empty)[threshold](#14-toc-title)[tags_post_id](#tags_post_id)[assigned_class](#assigned_class)[separator](#separator)[adjust_separator_size](#adjust_separator_size)[separator_size](#separator_size)[prepend](#prepend)[append](#append)[link_append](#link_append)[link_target](#link_target)[show_tag_count](#show_tag_count)[custom_title](#custom_title)[custom_title_zero](#custom_title_zero)[custom_title_plural](#custom_title_plural)[Tabs and Alphabet](#groups_and_tabs)[include_letters](#include_letters)[exclude_letters](#exclude_letters)[collapsible](#collapsible)[mouseover](#mouseover)[active](#active)[locale](#locale)[ignore_accents](#ignore_accents)[Groups](#36-toc-title)[include](#include)[exclude](#exclude)[delay](#delay)[Advanced Styling](#advanced_styling)[div_id](#div_id)[div_class](#div_class)[ul_class](#ul_class)

This guide shows you how to use and customize the shortcode for the Alphabetical Tag Cloud feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode [#](#0-toc-title)

```
[tag_groups_alphabet_tabs]
```

## Example [#](#1-toc-title)

```
[tag_groups_alphabet_tabs exclude_letters="äöüß"]
```

## Data Types [#](#data-types)

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters [#](#3-toc-title)

### Tags or Terms [#](#tags_or_terms)

#### taxonomy [#](#taxonomy)

Restrict the tags only to these taxonomies. Available are only taxonomies that have been activated in the Settings.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of taxonomy names | Taxonomies selected in the settings under “Basics”. | `taxonomy=post_tag,product_tag` |

#### smallest [#](#smallest)

Font-size in pt of the smallest tags.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 12 | `smallest=12` |

#### largest [#](#largest)

Font-size in pt of the largest tags.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 22 | `largest=20` |

#### include_terms [#](#include_terms)

Here you can specify tags/terms by their IDs that alone should be included.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of integers | empty (= no restriction) | `include_terms=4,5,212` |

Available only in the shortcode!

#### exclude_terms [#](#exclude_terms)

Here you can specify a list of tag/term IDs that should be excluded. **exclude_terms** is ignored if you use include_terms.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of integers | empty (= no restriction) | `exclude_terms=3,2,11` |

Available only in the shortcode!

#### orderby [#](#orderby)

Which field to use for sorting.

Since WordPress doesn’t natively offer natural sort order, we have to first order them by name and then by natural sort order. If you limit the number of tags with amount, the amount of tags is therefore determined by cutting off with “name” sorting and then sorting them with “natural”.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | name | `orderby=natural` |

#### order [#](#order)

Whether to sort the tags in ascending or descending order. Ascending order also means from oldest to newest.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | ASC | `order=desc` |

#### amount [#](#amount)

Maximum amount of tags in one cloud (per group).

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 0 (unlimited) | `amount=30` |

#### hide_empty [#](#hide_empty)

Whether to hide or show tags that are not assigned to any post.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 (hide empty) | `hide_empty=1` |

### threshold [#](#14-toc-title)

Set a minimum count of published posts that use a tag for this tag to appear. Posts are counted across all groups. Please also pay attention to the value of hide_empty.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 0 | integer | `threshold=2` |

#### tags_post_id [#](#tags_post_id)

Display only tags that are assigned to the post (or page) with a specified ID. If the value is set to 0, the plugin will try to retrieve the current post ID. Turn it off with -1.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | -1 (all tags displayed) | `tags_post_id=0` |

#### assigned_class [#](#assigned_class)

A modification of the tags_post_id parameter: Rather than hiding tags that are not assigned to the post (or page), they can be styled differently. Tags will receive this class name with appended _1 or _0. (If you output the tags as an array, a new element with the key “assigned” will be true or false.)

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (off) | `assigned_class="color-for-assigned-tag"` |

#### separator [#](#separator)

A separator between the tags.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `separator="•"` |

#### adjust_separator_size [#](#adjust_separator_size)

Whether to adjust the separator’s size to the size of the following tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 | `adjust_separator_size=1` |

#### separator_size [#](#separator_size)

The size of the separator, if not automatically adjusted to the size of the following tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 12 | `separator_size=12` |

#### prepend [#](#prepend)

Prepend to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `prepend="#"` |

#### append [#](#append)

Append to each tag label. Can obtain the placeholder {count} that will be replaced with the post count for that tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `append="{count}"` |

#### link_append [#](#link_append)

Append something to each tag’s link, for example a query.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `link_append="track=this"` |

Available only in the shortcode!

#### link_target [#](#link_target)

Set the “target” attribute for the links of the tags. Possible values are: _blank, _self, _parent or _top. In the shortcode you can also set the name of a frame.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string: “_blank”, “_self”, “_parent”, “_top”, or the name of a frame | empty (= opens in the same window, same as using _self) | `link_target="_blank"` |

#### show_tag_count [#](#show_tag_count)

Whether to show the number of posts as tooltip (behind the tag description) when hovering the mouse over the tag.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 (show) | `show_tag_count=0` |

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

### Tabs and Alphabet [#](#groups_and_tabs)

#### include_letters [#](#include_letters)

Which letters should be used for the tabs.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (= all) | `include_letters="abcdefghi"` |

#### exclude_letters [#](#exclude_letters)

Which letters should be excluded from the tabs.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (= all) | `exclude_letters="äöüß"` |

#### collapsible [#](#collapsible)

Whether tabs are collapsible (toggle open/close).

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | Settings in the back end. | `collapsible=1` |

#### mouseover [#](#mouseover)

Whether tabs can be selected by hovering over with the mouse pointer (without clicking).

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | Settings in the back end. | `mouseover=1` |

#### active [#](#active)

Whether tabs are initially expanded or collapsed. Useful in connection with the parameter “collapsible”.

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 1 | `active=1` |

#### locale [#](#locale)

Customize the locale (language-specific setting) that is used for sorting the first letters (initials). By default we use the language setting of your site. This sort order affects only the *order of the first letters*, not the*tag order* (see “orderby” and “order”).

Accepted values depend on which locales PHP can use on your server. They can have formats like “fr”, “fr_FR” or “fr_FR.UTF8”. Please ask your provider if you cannot find it by guessing or googling.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (site locale) | `locale="fr_FR.UTF8"` |

#### ignore_accents [#](#ignore_accents)

If you turn this on, then accented (or umlaut) letters will be treated like normal letters. For a list of changes see [remove_accents](https://developer.wordpress.org/reference/functions/remove_accents/).

| accepted values | default | example shortcode |
| --- | --- | --- |
| 0 or 1 | 0 | `ignore_accents=1` |

### Groups [#](#36-toc-title)

#### include [#](#include)

Use only tags of specified groups.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of integers | empty (all groups) | `include=1,3,11` |

This parameter is only available in the shortcode.

#### exclude [#](#exclude)

groups to exclude

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| none | comma-separated list of group IDs | `exclude=2,3,5` |

#### delay [#](#delay)

Since version 1.29.0, we reveal the tag cloud only after the tabs have finished rendering. This prevents that visitors of your site first see tags in a list (often with bullets) before the software is able to kick in. If for any reason you need to restore the previous behavior, you can turn it off here.

| accepted values | default | example shortcode |
| --- | --- | --- |
| integer | 1 (on) | `delay=0` |

### Advanced Styling [#](#advanced_styling)

You can set here IDs and classes to be referred to in CSS. You need some knowledge of CSS to use these options.

#### div_id [#](#div_id)

Define an ID for the enclosing <div>.

1. If you display more than one tag cloud on the same page, each shortcode needs a different value. The safest solution is to leave it empty so that div_id automatically receives a random value.

1. Avoid words that are used by WordPress such as “content” or “main”. Each ID must be unique for the entire page.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (random value) | `div_id="sidebar_01"` |

#### div_class [#](#div_class)

Define a class for the enclosing <div>.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | tag-groups-cloud-tabs | `div_class="make-it-dark"` |

#### ul_class [#](#ul_class)

Define a class for the <ul> that generates the tabs with the group labels.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `ul_class="change-pointer"` |
