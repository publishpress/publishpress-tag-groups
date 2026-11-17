---
title: "Tag Groups Themes: Design and Colors"
order: 94
doc_category: publishpress-tag-groups
---

# Tag Groups Themes: Design and Colors

Table of Contents

- [Default Themes](#0-toc-title)
- [Shuffle Box Themes](#1-toc-title)
- [Further CSS Customization](#2-toc-title)

This guide will show you how to select the colors of displays in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin. We’ll cover the default themes and also show how to make your own customizations.

## Default Themes [#](#0-toc-title)

If you want to change the style of the tabs or the accordion panels, you simply select one of the bundled themes that you find in the [Tag Groups](https://publishpress.com/links/tag-groups) settings, Front End, Themes and Appearance.

Tag Groups Pro comes with a few standard themes that you can use to give some shape and color to your tags. You apply a theme by using its class name for the enclosing element – “div class” or “table class”.

Example of a shortcode:

```
[tag_groups_cloud div_class="tag-groups-theme-green"]
```

The corresponding parameter is also available in the Gutenberg blocks.

The color is applied to the “prepend” and “append” elements.

```
tag-groups-theme-green
tag-groups-theme-blue
tag-groups-theme-red
tag-groups-theme-orange
tag-groups-theme-black
tag-groups-theme-darkred
tag-groups-theme-midnightblue
tag-groups-theme-slategray
```

This screenshot below is an example using the “green” theme:

![](https://docs-media.publishpress.com/publishpress-tag-groups/tag-groups-wordpress-plugin-tag-cloud-post-count-11d4752519.webp)

*tag-groups-theme-green with the parameter append=”{count}” to show the post counts.*

We also adapted styling created by [Dimox](http://dimox.net/beautiful-tags-for-a-website-using-only-css/) after a design by Orman Clark. You can use it with the class name

```
tag-groups-theme-tag-with-hole
```

Since the tag size is fixed, the maximum font size should not exceed 18px. The cloud looks best if all tag labels have the same height.

![](https://docs-media.publishpress.com/publishpress-tag-groups/tag-cloud-tags-with-holes-css-902dd9609c.webp)

## Shuffle Box Themes [#](#1-toc-title)

For the Shuffle Box feature, you can use the following class names. The color will also be applied to the buttons.

```
cm-shuffle-box-theme-default (Slategray)
cm-shuffle-box-theme-blue
```

Here’s an example of this theme with shortcode:

```
[tag_groups_shuffle_box div_class="cm-shuffle-box-theme-blue"]
```

[Click here for more details on Shuffle Box themes](https://taxopress.com/docs/custom-color-scheme-shuffle-box/).

## Further CSS Customization [#](#2-toc-title)

Advanced users with some knowledge of CSS can simply copy the corresponding lines from the file /css/frontend.css, change the class names and modify them to create their own variations. Save your customized styling to your child theme’s style.css file or in the WordPress customizer so that changes won’t be overwritten with the next plugin update.

You can find further help [in this article on modifying existing themes](https://taxopress.com/docs/how-can-i-change-the-colors-and-other-styling/).
