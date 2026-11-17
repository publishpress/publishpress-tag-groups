---
title: "Create Your Own Accordion and Tab Themes"
order: 23
doc_category: publishpress-tag-groups
---

# Create Your Own Accordion and Tab Themes

The [Tag Groups](https://publishpress.com/links/tag-groups) plugin comes bundled with several themes provided by jQuery UI. These apply to Alphabetical Tag Cloud, Tabbed Tag Cloud and the Accordion Tag Cloud features.

Alternatively, you can also use your own theme. The easiest way to achieve this is to create one using the [jQuery UI ThemeRoller](http://jqueryui.com/themeroller/). In this guide we’ll show the six steps to create a new theme.

- Go to [jQuery UI ThemeRoller](http://jqueryui.com/themeroller/).
- You can click “Roll Your Own”, or pick a ready-made theme from the “Gallery.”
- Under the “Roll Your Own” tab, you can modify the Font Settings, Corner Radius, Header/Toolbar, Content, and more. In the center part of the page you can see a preview of your theme.

![](https://docs-media.publishpress.com/publishpress-tag-groups/theme-roller-1024x427-1-9f83c0d3d3.webp)

- When you’re finished, click the yellow “Download theme button”.
- On the next “Download Builder” page, select the version 1.12.x.

![The JQuery Download builder](https://docs-media.publishpress.com/publishpress-tag-groups/download-12-1-jquery-49fd6fcdce.webp)

- Go to the settings at the bottom and enter as “CSS Scope” the class .tag-groups-cloud (including the leading dot).
- Download the file.

![Download your JQuery UI theme](https://docs-media.publishpress.com/publishpress-tag-groups/tag-groups-cloud-59738ad8bf.webp)

- Unpack the downloaded zip file. You will need the “images” folder and the “jquery-ui.theme.min.css” file.

![Unpack the downloaded zip file for the theme](https://docs-media.publishpress.com/publishpress-tag-groups/theme-file-downloads-0bfdc6c523.webp)

- Create a new folder inside your*/wp-content/uploads* folder. For example, the folder can be “my-theme”.
- Add your two items into the folder.

![](https://docs-media.publishpress.com/publishpress-tag-groups/uploaded-theme-files-0c8cb13a6b.webp)

- Go to “[Tag Groups](https://publishpress.com/links/tag-groups)” in your WordPress admin area, then “Front End”, then “Themes and Appearance”.
- Enter the name of this new folder in the “own theme” field.
- Save the changes and check your new design with a Alphabetical Tag Cloud, Tabbed Tag Cloud, or Accordion Tag Cloud display.

![Creating a new theme from JQuery YI](https://docs-media.publishpress.com/publishpress-tag-groups/my-theme-load-d76ef86add.webp)

You can also leave the field for your custom theme empty. You may want to do this for example if the theme of your blog already provides tabs and you like to blend the tag cloud into this design.

If the styling doesn’t appear, check if your uploaded files have the correct permissions. Try to load the CSS and images directly in your browser (like http://www.example.com/wp-content/uploads/my-theme/jquery-ui.theme.min.css).
