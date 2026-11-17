---
title: "Tags Not Visible in the WordPress Admin After Installing Tag Groups"
order: 110
doc_category: publishpress-tag-groups
---

# Tags Not Visible in the WordPress Admin After Installing Tag Groups

**Table of Contents**

- [Option #1: Check the filter menu](#option-1-check-the-filter-menu)
- [Option #2: Disable the filter menus](#option-2-disable-the-filter-menus)
- [Option #3: Reset the filters](#option-3-reset-the-filters)
- [Option #4: Test for plugin conflicts](#option-4-test-for-plugin-conflicts)

If you activate the [Tag Groups](https://publishpress.com/links/tag-groups) plugin and don’t see your tags in the WordPress admin area, you don’t need to worry. The tags are still there, they are just not visible. Follow these four steps to make your tags appear again.

## Option #1: Check the filter menu

This problem is normally caused by the filter menu on top of the “Tags” screen. Try selecting “Filter Off” and click “Apply”.

![Filters on the Tags screen](https://docs-media.publishpress.com/publishpress-tag-groups/tags-filter-51b11956de.webp)

## Option #2: Disable the filter menus

You can also try going to “[Tag Groups](https://publishpress.com/links/tag-groups)“, then “Back End” tab and disabling the filters.

![disabling the filters in Tag Groups](https://docs-media.publishpress.com/publishpress-tag-groups/back-end-filters-459bda40ad.webp)

## Option #3: Reset the filters

You can also go to “Tag Groups”, then “Tools”, then the “First Aid” tab. Click the “Reset Tag Filter” button. That may bring the tags back.

![](https://docs-media.publishpress.com/publishpress-tag-groups/reset-tag-filter-feb6349fbf.webp)

## Option #4: Test for plugin conflicts

Finally, test for plugin conflicts. Deactivate all other plugin one-by-one and ccheck if now the filter appeared on top of the tag lists. The filter might fail to show because another plugin is causing an error on that page.

To get more details on the plugin causing issues, open the console in your browser, as described [here](https://documentation.concrete5.org/tutorials/how-open-browser-console-view-errors). Then reload the tags page and check for error messages in the console. The messages usually point to the plugin that is causing the error.
