---
title: "How to Choose a Custom Order in the Post Filter"
order: 36
doc_category: publishpress-tag-groups
---

# How to Choose a Custom Order in the Post Filter

If you are using the Post Filter option in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin, it is possible to customize the order of the posts.

Install the plugin [Simple Custom Post Order](https://wordpress.org/plugins/simple-custom-post-order/) and use it sort your posts.

You will need to use the shortcode of the body (posts) part of the Toggle Post Filter which is [tag_groups_tpf_body].

If you are using a Gutenberg block, simply [transform it to a shortcode](https://taxopress.com/docs/how-can-i-customize-a-gutenberg-block-beyond-the-given-options/).

Then make sure that the shortcode contains the parameters:

- orderby=menu_order
- order=asc

Here’s an example of this shortcode:

```
[tag_groups_tpf_body orderby=menu_order order=asc]
```

Note: If you are using the *Masonry* post layout, the post order might change again because posts are arranged in the browser depending on each element’s height.
