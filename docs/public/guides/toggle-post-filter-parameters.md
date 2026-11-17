---
title: "Toggle Post Filter – Shortcodes Overview"
order: 100
doc_category: publishpress-tag-groups
---

# Toggle Post Filter – Shortcodes Overview

Table of Contents

- [Shortcodes](#0-toc-title)
- [Example](#1-toc-title)

This guide shows you how to use and customize all the shortcodes for the Toggle Post Filter feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

## Shortcodes [#](#0-toc-title)

This feature consists of several shortcodes that you can place at different locations of the page, including widgets. The main parts create the menu and the body (for the posts). [Click here for more on the menu shortcode](https://taxopress.com/docs/toggle-post-filter-menu-shortcode-parameters/) and [click here for more on the posts shortcode](https://taxopress.com/docs/toggle-post-filter-posts-body-shortcode-parameters/).

```
[tag_groups_tpf_menu]
```

```
[tag_groups_tpf_body]
```

There are also optional shortcodes:

1. A field for messages that tell you how many posts were found. You can omit it and use only the notes that appear for some seconds in the top right corner.

```
[tag_groups_tpf_messages]
```

This shortcode doesn’t have any parameters.

2. [A reset button](https://taxopress.com/docs/toggle-post-filter-reset-button-shortcode-parameters/):

```
[tag_groups_tpf_reset]
```

3. [A menu to select the sort order](https://taxopress.com/docs/toggle-post-filter-order-menu-shortcode-parameters/):

```
[tag_groups_tpf_order_menu]
```

4. [A button to open the slider menu](https://taxopress.com/docs/toggle-post-filter-slider-button-shortcode-parameters/):

```
[tag_groups_tpf_slider_button]
```

5. [An additional text search field](https://taxopress.com/docs/toggle-post-filter-text-search-shortcode-parameters/).

```
[tag_groups_tpf_text_search]
```

Note: Old shortcodes with the pattern tg_dpf_toggle_… still remain functional.

## Example [#](#1-toc-title)

```
Here comes the filter part:

[tag_groups_tpf_menu caching_time=60 display_amount=2 pager=1 persistent_filter=60 theme="dark" transition=fade operator="IN AND" layout="wide_tags"]

How many posts we found:

[tag_groups_tpf_messages]

And the posts go to a place that is sufficiently large:

[tag_groups_tpf_body theme="dark" layout="masonry"]
```
