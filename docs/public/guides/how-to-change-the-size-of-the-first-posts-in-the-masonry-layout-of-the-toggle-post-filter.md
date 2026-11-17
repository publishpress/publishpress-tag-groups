---
title: "Change the Size of the First Posts in the Masonry Layout"
order: 39
doc_category: publishpress-tag-groups
---

# Change the Size of the First Posts in the Masonry Layout

When using the Toggle Post Filter option in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin, you can customize items with CSS.

For example: If you want to change the width of the first two posts, you can use the following CSS:

```
@media screen and (min-width: 1200px) {
  #tg_filter_dpf_toggle_box_posts article:nth-child(1),
  #tg_filter_dpf_toggle_box_posts article:nth-child(2) {
    width: 49% !important;
  }
}
```

The width of 49% means that you show two items in a row. You can experiment with that number so that these posts are as wide as possible and still fit together into a row, including their margins.

The min-width: 1200px part makes sure that this styling only affects large screens. On mobile devices each row should have only *one* post. Here too you can try different numbers.

[Read more about @media.](https://developer.mozilla.org/en-US/docs/Web/CSS/@media)
