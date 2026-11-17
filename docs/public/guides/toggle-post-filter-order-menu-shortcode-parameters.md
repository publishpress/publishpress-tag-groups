---
title: "Toggle Post Filter, Order Menu – Shortcode Parameters"
order: 99
doc_category: publishpress-tag-groups
---

# Toggle Post Filter, Order Menu – Shortcode Parameters

**Table of Contents**

- [Shortcode](#shortcode)
- [Example](#example)
- [Data Types](#data-types)
- [Parameters](#parameters)
  - [orderby_text](#orderby_text)
  - [order_text](#order_text)
  - [orderby_options](#orderby_options)
  - [order_options](#order_options)
  - [theme](#theme)
  - [sumoselect](#sumoselect)
  - [div_class](#div_class)

This menu allows your visitors to change the sort order of posts that are displayed in the Toggle Post Filter. It must be used together with the menu and the body (posts) part of the Toggle Post Filter.

**Note:** If you use masonry or boxed post layout, all displayed posts will be rearranged after sorting to fit best into the available space. The resulting sort order might therefore be different from what you set in the menu.

## Shortcode

```
[tag_groups_tpf_order_menu]
```

## Example

```
[tag_groups_tpf_order_menu orderby_text="Sort order" order_text="Up or down"]
```

## Data Types

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters

This overview assumes that you know how to use shortcodes. Shortcodes are the traditional WordPress way how to add features into posts, pages and widgets.

### orderby_text

Text for the menu to select the “order by” of posts.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| Order by | string | `orderby_text="sort posts by"` |

### order_text

Text for the menu to select the post order (ascending or descending).

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| Order | string | `order_text="up or down"` |

### orderby_options

Here you configure the options that appear in the “order by” menu. Options are separated by |. Each options consists of a value and a label that will be displayed in the menu, separated by :. Values must be [one of the internal orderby identifiers provided by WordPress](https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters) or a custom field (see below).

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| date:date\|author:author\|title:title | string | `orderby_options="date:date\|rand:random\|title:title"` |

If your posts have [custom fields](https://wordpress.org/support/article/custom-fields/) (post meta), you can use them as orderby value. This only works if these custom fields hold strings.

Note: If a custom field is used for sorting, only posts that have that custom field will appear in the list. If the total amount of posts differs depending on the selected sort order, you may have to add the custom field to the missing posts.

**Example:**

We use a custom field “subtitle” that will appear in the menu as “Subtitle”:

```
orderby_options="subtitle:Subtitle|relevance:relevance|title:title"
```

If the custom field saves numbers, you need to add “%num” to the field name. Otherwise the order would follow the rule for strings. (“1, 11, 2, 3” instead of “1, 2, 3, 11”.)

**Example:**

We use a custom field “view_count” that holds numeric values.

```
orderby_options="view_count%num:Views|relevance:relevance|title:title"
```

### order_options

Here you configure the options that appear in the “order” menu. Options are separated by |. Each options consists of a value and a label that will be displayed in the menu, separated by :. Values must be “asc” and “desc”.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| desc:↓\|asc:↑ | string | `order_options=desc:down\|asc:up` |

### theme

Select a theme. Currently we have “dark” and “light”.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| “light” | string | `theme="dark"` |

### sumoselect

Whether to show a flat menu.((If this doesn’t work, check if you use a plugin that processes JS scripts such as “Autoptimize” and add “sumoselect” to the excluded files.))

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| 1 (on) | integer | `sumoselect=0` |

### div_class

Optional CSS identifier for the enclosing div.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `div_class="my-class"` |
