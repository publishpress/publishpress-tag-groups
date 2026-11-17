---
title: "Toggle Post Filter, Slider Button – Shortcode Parameters"
order: 104
doc_category: publishpress-tag-groups
---

# Toggle Post Filter, Slider Button – Shortcode Parameters

Table of Contents

- [Shortcode](#0-toc-title)
- [Example](#1-toc-title)
- [Data Types](#data-types)
- [Parameters](#3-toc-title)[button_text](#button_text)[theme](#theme)[button_class](#button_class)

This button opens the menu of the Toggle Post Filter, if you you selected the left or right slider as menu layout. Alternatively you can also use your own button and give it the class “tg_dpf_slider_toggle_button”.

## Shortcode [#](#0-toc-title)

```
[tag_groups_tpf_slider_button]
```

## Example [#](#1-toc-title)

```
[tag_groups_tpf_slider_button theme="light"]
```

## Data Types [#](#data-types)

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters [#](#3-toc-title)

This overview assumes that you know how to use shortcodes. Shortcodes are the traditional WordPress way how to add features into posts, pages and widgets.

#### button_text [#](#button_text)

Text for the button

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| “Filter” | string | `button_text="Refine your search"` |

#### theme [#](#theme)

Select a theme. Currently we have “dark” and “light”.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| “light” | string | `theme="dark"` |

#### button_class [#](#button_class)

Optional CSS identifier for the button. It will be added to the internal CSS class name “tg_dpf_slider_toggle_button”.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `button_class="my-button"` |
