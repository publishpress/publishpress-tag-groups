---
title: "Toggle Post Filter, Slider Button – Shortcode Parameters"
order: 104
doc_category: publishpress-tag-groups
---

# Toggle Post Filter, Slider Button – Shortcode Parameters

**Table of Contents**

- [Shortcode](#shortcode)
- [Example](#example)
- [Data Types](#data-types)
- [Parameters](#parameters)
  - [button_text](#button_text)
  - [theme](#theme)
  - [button_class](#button_class)

This button opens the menu of the Toggle Post Filter, if you you selected the left or right slider as menu layout. Alternatively you can also use your own button and give it the class “tg_dpf_slider_toggle_button”.

## Shortcode

```
[tag_groups_tpf_slider_button]
```

## Example

```
[tag_groups_tpf_slider_button theme="light"]
```

## Data Types

- **string:** A text. For identifiers that won’t be visible to visitors you often have to replace spaces with _ or -. If the string contains spaces, you need to wrap it in quotes.
- **integer:** A number without decimal places.
- **0 or 1**: 0 turns the feature off and 1 on.

## Parameters

This overview assumes that you know how to use shortcodes. Shortcodes are the traditional WordPress way how to add features into posts, pages and widgets.

### button_text

Text for the button

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| “Filter” | string | `button_text="Refine your search"` |

### theme

Select a theme. Currently we have “dark” and “light”.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| “light” | string | `theme="dark"` |

### button_class

Optional CSS identifier for the button. It will be added to the internal CSS class name “tg_dpf_slider_toggle_button”.

| **Default** | **Possible Values** | **Example** |
| --- | --- | --- |
| empty | string | `button_class="my-button"` |
