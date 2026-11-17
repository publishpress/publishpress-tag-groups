---
title: "Limit the Size of the Menu in the Dynamic Post Filter"
order: 42
doc_category: publishpress-tag-groups
---

# Limit the Size of the Menu in the Dynamic Post Filter

It is possible to limit the size of the menu in the Dynamic Post Filter option in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin. This means that the menu will not grow too large, no matter how many terms you add.

Use the following CSS in your child theme’s style.css or in the CSS section of the theme customizer:

```
.ui-autocomplete {
   /* limit height to 100px and add vertical scrollbar */
   max-height: 100px;
   overflow-y: auto;
   /* prevent horizontal scrollbar */
   overflow-x: hidden;
}
```
