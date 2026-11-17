---
title: "Create a Custom Color Scheme for Shuffle Box"
order: 24
doc_category: publishpress-tag-groups
---

# Create a Custom Color Scheme for Shuffle Box

**Table of Contents**

- [The Default Themes for Shuffle Boxes](#the-default-themes-for-shuffle-boxes)
- [Custom Themes for Shuffle Boxes](#custom-themes-for-shuffle-boxes)

This guide will show you how to create your own color scheme for the Shuffle Box feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin. First, we’ll introduce the default options, and then we’ll see how to create your own custom themes.

## The Default Themes for Shuffle Boxes

This screenshot below shows the default design for the shuffle box.

![](https://docs-media.publishpress.com/publishpress-tag-groups/default-shuffle-box-de94d7c3a2.webp)

You can also customize this one of [the default themes in Tag Groups](https://taxopress.com/docs/theming/). And there are additional default themes that are unique to Shuffle Boxes:

```
cm-shuffle-box-theme-default 
cm-shuffle-box-theme-blue
```

## Custom Themes for Shuffle Boxes

- In your WordPress admin area, go to “[Tag Groups](https://publishpress.com/links/tag-groups)” then “Tag Colors”.
- Enter the CSS class name. The default is “my-own-class”.

![](https://docs-media.publishpress.com/publishpress-tag-groups/my-class-name-c4e9a534ba.webp)

- Choose the colors you want to use for Shuffle Box.
- Copy the CSS and add it to your theme. Add the CSS into the style.css of a child theme or you can use the Customizer.

![](https://docs-media.publishpress.com/publishpress-tag-groups/css-class-ac83513d0f.webp)

- Take the CSS class name from the bottom of the screen. In this example, the class is “cm-shuffle-box-theme-default my-own-class”.

![](https://docs-media.publishpress.com/publishpress-tag-groups/css-class-name-247b87f9a3.webp)

- Go to your post and find the Gutenberg block for your shuffle box.
- Under “Advanced Styling”, enter your class into the “div class” field.
- Save the post and your custom color scheme will appear.

![](https://docs-media.publishpress.com/publishpress-tag-groups/add-class-875e31bf27.webp)

If you’re using the shortcode for Shuffle Box, add this to your shortcode: `div_class="cm-shuffle-box-theme-default my-own-class"`
