---
title: "I Don’t See Posts In the Dynamic Post Filter"
order: 51
doc_category: publishpress-tag-groups
---

# I Don’t See Posts In the Dynamic Post Filter

If you click on a tag in the Dynamic Post Filter from the [Tag Groups](https://publishpress.com/links/tag-groups) plugin and don’t see any or all posts, there can be various reasons:

1. Please make sure that these posts are published.
2. Check that the posts have the correct post type. In the [Tag Groups](https://publishpress.com/links/tag-groups) settings you will find the submenu “Taxonomies” where you can select the taxonomy for that post type. By default we use Tags. If you have selected anything else, for example Product tags (product_tag) , you will need to use that same taxonomy again in the shortcode or Gutenberg block. This is done with the parameter taxonomy followed by the identifier of that Taxonomy, for example: taxonomy=”product_tag”
3. Sometimes it happens that the plugin was not able to process all your posts in order to update the information about tags and tag groups. You can solve that problem by going to “Tag Groups”, then “Tools”, and the “First Aid” tab. Click all the buttons.
