---
title: "How to Make the Alphabetical Tag Index or the Tag List Responsive?"
order: 11
doc_category: publishpress-tag-groups
---

# How to Make the Alphabetical Tag Index or the Tag List Responsive?

The Alphabetical Tag Index and Tag List features in the Tag Group plugin will create lists in columns. You can use the shortcode parameter column_count or the corresponding block option to set the number of columns.

It is also possible to make these displays responsive, that means that the number of columns adjusts to the screen size of the viewer.

You can achieve this with a few steps.

1. In the shortcode set the column count to zero: column_count=0

2. Use div_class to set the class name to “responsive-tag-groups-list”. Now the shortcode looks like that:

```
[ ... column_count=0 div_class="responsive-tag-groups-list"]
```

3. Add to the CSS, for example in the WordPress customizer (We use the colored text only for the explanations below):

```
@media only screen and (max-width: 700px) {
  .responsive-tag-groups-list {
    column-count: 1;
    /** text-align: center; **/ /** optionally uncomment the code on the left to align tags to the center **/
  }
}
@media only screen and (min-width: 701px) and (max-width: 1000px) {
  .responsive-tag-groups-list {
    column-count: 2;
    column-gap: 10px;
  }
}
@media only screen and (min-width: 1001px) {
  .responsive-tag-groups-list {
    column-count: 3;
    column-gap: 10px;
  }
}
```

This CSS creates three different styles: one column for screen (viewport) sizes up to 700px, two columns for screen sizes from 701 to 1000px and three columns for screen sizes above 1000px.

Feel free to use your own values.
