---
title: "How to Modify a Theme in Tag Groups"
order: 34
doc_category: publishpress-tag-groups
---

# How to Modify a Theme in Tag Groups

This guide will show you how to make CSS changes to the displays in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin.

The Pro version of [Tag Groups](https://publishpress.com/links/tag-groups) has several themes available. You can find more information and examples [in this article](https://taxopress.com/docs/theming/).

For your own customization, you can copy and re-use one of these existing themes inside Tag Groups Pro. In this guide, we’ll show you how to find the CSS and safely copy it to your WordPress site to make changes.

- Download the main frontend CSS file from the Tag Groups Pro plugin. You will find that file here: /premium/assets/css/frontend.css
- Search for the CSS section that you need. For example, there are sections labeled, “Shufflebox Themes” or “Tag cloud themes”.
- Copy all the code for one definition. For example, copy everything that starts with “.tag-groups-theme-blue”. Here’s the code for that blue theme:

```
/* blue */
.tag-groups-theme-blue .tag-groups-tag {
  background-color: #ddd;
  margin: 2px 10px 2px 10px;
  padding: 0;
  display: inline-block;
  border-radius: 5px;
  border: none;
  white-space: nowrap;
}

.tag-groups-theme-blue .tag-groups-tag a {
  text-decoration: none;
  box-shadow: none;
  margin: 0;
  color: #333;
}

.tag-groups-theme-blue .tag-groups-tag a:visited,
.tag-groups-theme-blue .tag-groups-tag a:active,
.tag-groups-theme-blue .tag-groups-tag a:link {
  text-decoration: none;
  box-shadow: none;
  margin: 0;
  color: #333;
}

.tag-groups-theme-blue .tag-groups-label {
  margin: 0 0.3em;
}

.tag-groups-theme-blue .tag-groups-append {
  background-color: blue;
  color: white;
  margin-left: 0.2em;
  padding: 0.2em 0.4em;
  display: inline-block;
  border-radius: 0 5px 5px 0;
}

.tag-groups-theme-blue .tag-groups-prepend {
  background-color: blue;
  color: white;
  margin-right: 0.2em;
  padding: 0.2em 0.4em;
  display: inline-block;
  border-radius: 5px 0 0 5px;
}
```

- You can paste the CSS into the CSS editor of the WordPress Customizer, or you create a child theme and paste it into its style.css file.
- Rename all occurrences of the identifier (class name) to something unique, for example “.tag-groups-theme-blue-new”. You will also have to use the same new name in the shortcode or the Gutenberg block.
- Now you can edit the colors or other styling. If you are using the Customizer on the page where the shortcode is rendered, you can see the new styling live while you edit. If you use a child theme, you will need to reload the page after you save the file style.css.
