---
title: "Organizing Groups with a Parent Level"
order: 60
doc_category: publishpress-tag-groups
---

# Organizing Groups with a Parent Level

Table of Contents

- [Tag Group Administration](#0-toc-title)[Tag and Post Filters](#1-toc-title)[Tag Clouds and Lists](#2-toc-title)[Post Tags (under Posts)](#3-toc-title)[Tag Meta Box (on the Post Edit Screen)](#4-toc-title)

In [Tag Groups](https://publishpress.com/links/tag-groups) Pro is it possible to create a parent level for your [tag groups](https://publishpress.com/links/tag-groups).

Having a two–level hierarchy can make it easier to select a cluster of child groups by referring to their parent. It also helps your authors understand which groups belong together.

**Parent groups** can only hold (default) tag groups, but no tags. That means that we can have only a maximum of two group levels – parent groups and their child groups – and then the tags inside the child groups.

```
parent group: cars
  group: color
    tags: red, blue, green, ...
  group: configuration
    tags: air con, 4x4, autopilot, ...
parent group: bicycles
  group: color
    tags: red, blue, green ...
  group: manufacturer
    tags: company A, company B, ...
    
```

Parent groups don’t appear in frontend post filters (Toggle Post Filter, Dynamic Post Filter) and they have no effect on their logic operations.

## Tag Group Administration [#](#0-toc-title)

On the Tag Group Administration screen you find a text field where you can add a parent group by entering the name.

Just like default groups, you change the order of parent groups by drag and drop or by clicking on the up or down arrow at the end of the row. All default groups that are below a parent group are its children. You can also edit the name or delete a parent group.

It is also possible to use tag groups without a parent level.

![Tag Group Administration](https://docs-media.publishpress.com/publishpress-tag-groups/screenshot-tag-group-admin-parent-groups-wordpress-900x449-1-54e32b6cf7.webp)

Note: If you are using parent groups, all groups can have duplicate names. It is still recommended to avoid duplicates, however, because at some places you might confuse groups with identical names.

### Tag and Post Filters [#](#1-toc-title)

Once you’ve created parent groups, you will find them in the drop-down menus above the lists of tags and posts. If you select a parent group, all of its children will automatically be included in the filtering criteria.

Note: Styling of parent groups in menus depends on browser capabilities.

![](https://docs-media.publishpress.com/publishpress-tag-groups/screenshot-tag-filter-parents-e1620812462122-87db5555e9.webp)

### Tag Clouds and Lists [#](#2-toc-title)

Parent groups are not visible on your public web site, but they make your work easier on the back end. You can, for example, include or exclude all children by referring to their parent group. If you use shortcodes, you simply insert the parent group’s ID, exactly how you would use the list of group IDs.

### Post Tags (under Posts) [#](#3-toc-title)

In the Tag Group settings, under *Front End > Post Tags*, you find an option that lets you show also parent groups in the tags below the post content. Parent groups, default groups and tags are displayed in a table. The precise appearance depends on how your theme displays tables.

### Tag Meta Box (on the Post Edit Screen) [#](#4-toc-title)

Also the Tag Meta Box on the post edit screen shows your groups sorted under parent groups. This upper level acts only as a visual guidance that helps you organize your groups but it doesn’t change the way how we handle tags.

![](https://docs-media.publishpress.com/publishpress-tag-groups/screenshot-wordpress-tag-meta-box-250x250-1-acc1ea7868.webp)
