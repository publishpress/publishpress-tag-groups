---
title: "Tag Groups Menu – Shortcode Parameters"
order: 83
doc_category: publishpress-tag-groups
---

# Tag Groups Menu – Shortcode Parameters

Table of Contents

- [Shortcode](#0-toc-title)
- [Example](#1-toc-title)
- [Data Types](#data-types)
- [Parameters](#3-toc-title)[Tags or Terms](#tags_or_terms)[include](#include)[taxonomy](#taxonomy)[Menu](#smallest)[type](#type)[](#9-toc-title)[Target Page](#groups_post_id)[custom_url](#custom_url)[shop](#shop)[Labels and Messages](#13-toc-title)[submit_label](#submit_label)[not_assigned_name](#not_assigned_name)
- [Advanced Styling](#16-toc-title)[div_id](#div_id)[div_class](#div_class)

This guide shows you how to use and customize the shortcode for the [Tag Groups](https://publishpress.com/links/tag-groups) Menu feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcode [#](#0-toc-title)

```
[tag_groups_menu]
```

## Example [#](#1-toc-title)

```
[tag_groups_menu taxonomy=product_tag include=1,3,4 type=autoselect]
```

## Data Types [#](#data-types)

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters [#](#3-toc-title)

### Tags or Terms [#](#tags_or_terms)

#### include [#](#include)

Determines which groups to include.

| accepted values | default | example shortcode |
| --- | --- | --- |
| comma-separated list of group IDs | empty (all enabled) | `include=1,5` |

#### taxonomy [#](#taxonomy)

Determines the post page where the posts will be filtered. Available are only taxonomies that have been activated in the Settings.

| accepted values | default | example shortcode |
| --- | --- | --- |
| one taxonomy name | post_tag | `taxonomy=product_tag` |

### Menu [#](#smallest)

#### type [#](#type)

The type of menu.

- “select”: Drop-down menu with a submit button. The posts will only be displayed when the user clicks on the button.
- “autoselect”: Drop-down menu without a button. The posts will be displayed as soon as the user selects an option.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | select | `type=select` |

### [#](#9-toc-title)

### Target Page [#](#groups_post_id)

#### custom_url [#](#custom_url)

Optional custom URL where you want to display the posts.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | none | `custom_url='https://www.example.com/posts/'` |

#### shop [#](#shop)

If you supply this parameter, then the search will happen on the default product page. This page is determined according to the e-commerce plugin. Currently there is only “wc”, which stands for “WooCommerce”.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty | `shop=wc` |

### Labels and Messages [#](#13-toc-title)

#### submit_label [#](#submit_label)

Text on the submit button, if you use one.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “search” | `submit_label="submit"` |

#### not_assigned_name [#](#not_assigned_name)

Label of the button for the “not assigned” group.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | “not assigned” | `not_assigned_name="all the rest"` |

## Advanced Styling [#](#16-toc-title)

You can set here IDs and classes to be referred to in CSS. You need some knowledge of CSS to use these options.

#### div_id [#](#div_id)

Define an ID for the enclosing <div>.

Avoid words that are used by WordPress such as “content” or “main”. Each ID must be unique for the entire page.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | empty (random value) | `div_id="sidebar_01"` |

#### div_class [#](#div_class)

Define a class for the enclosing <div>.

| accepted values | default | example shortcode |
| --- | --- | --- |
| string | none | `div_class="make-it-dark"` |
