---
title: "Using the Tag Groups Metabox on the Post Edit Screen"
order: 107
doc_category: publishpress-tag-groups
---

# Using the Tag Groups Metabox on the Post Edit Screen

Table of Contents

- [Introducing the Tag Groups Metabox](#0-toc-title)
- [Additional notes and restrictions](#1-toc-title)

This is a feature of [Tag Groups](https://publishpress.com/links/tag-groups) Pro.

The default WordPress “Tags” box does not use [tag groups](https://publishpress.com/links/tag-groups). Therefore, the Tag Groups plugin offers a “Tag Meta Box” that helps you edit post tags separated by groups. The default box is disabled when using our plugin.

## Introducing the Tag Groups Metabox [#](#0-toc-title)

This screenshot below shows the metabox provided by the Tag Groups plugin:

![](https://docs-media.publishpress.com/publishpress-tag-groups/tgp-meta-box-colored-tags-e4a083f321.webp)

On the right of the tag group name you find two buttons: The left one adds all tags of this group, the right one closes this group in the editor.

Depending on the settings, you may also **add new tags** or add a tag that is currently in another group, in order to **move it to this group**. Added and moved tags will be marked green and yellow. If the settings allow you to move tags, you can also move them by drag and drop.

Tags appear in their respective groups, as set on the tags page. If a tag is assigned to more than one group, then all of these groups are available in the meta box, but you can choose to use this tag only with one of these groups in this post. For example, the tag “Leonardo da Vinci” may be assigned to the groups “painters” and “scientists” on the tags page. In a post about his paintings, however, you may want to use it only in the group “painters.”

Nothing is final until you save that post. Until then, no new tags will be created, no tags moved to other groups and no post tags changed.

Only when you save the post, it will receive the new tags and keep the old tags that it had before and that were hidden in groups that you kept close.

Please note that other plugins and themes don’t use tag groups. For them, all tags form just one unsorted group. If you want to display them in groups to your visitors, you need to use the **Gutenberg blocks** or **shortcodes** of this plugin or **modify your theme**. You can also [display post tags under the posts](https://taxopress.com/docs/post-tags/) sorted by groups.

## Additional notes and restrictions [#](#1-toc-title)

**Note 1:**Since tags are separated in the input field by comma (“,”), **tag names cannot contain this character** in order to work with the meta box.

**Note 2:**The meta box works only correctly if tag names are **unique**. It is not sufficient if tags with identical names are differentiated by their “slugs” (piece of the link). Please note that WordPress considers also two tags that differ only by capitalization as duplicates and refuses to accept more than one of each variation.

**Note 3:**If you display both the default tag box and the Tag Meta Box, you should avoid any contradictory or double actions, like adding the same tag to both boxes or renaming a tag in one box and removing the same tag in the second tag box. It is strongly recommended to use only one of the two tag boxes.

**Note 4:**If you add a tag to a post while the **Tag metabox is turned off in the settings**, or if you add it with Quick Edit in the list of posts, then this tag will be added for **all of the groups** that the tag is assigned to. That means that without the Tag Meta Box you won’t be able to fine-tune the groups of a tag for a particular post. If you apply all tags to posts with the same groups that they have on the tags page, then it is perfectly fine to disable the Tag Meta Box.
