---
title: "Which Tag Groups Feature Do I Need?"
order: 47
doc_category: publishpress-tag-groups
---

# Which Tag Groups Feature Do I Need?

**Table of Contents**

- [Tag Clouds](#tag-clouds)
  - [Displaying Tag Groups](#displaying-tag-groups)
  - [I need to display the tags under tabs (arranged horizontally). The tabs should represent tag groups.](#i-need-to-display-the-tags-under-tabs-arranged-horizontally-the-tabs-should-represent-tag-groups)
  - [I need to display the tags in an accordion (stacked vertically and sliding up and down). Each slider should represent a tag group.](#i-need-to-display-the-tags-in-an-accordion-stacked-vertically-and-sliding-up-and-down-each-slider-should-represent-a-tag-group)
  - [I need to display the tags in under tabs (arranged horizontally). The tags should be sorted alphabetically into groups by their first letters.](#i-need-to-display-the-tags-in-under-tabs-arranged-horizontally-the-tags-should-be-sorted-alphabetically-into-groups-by-their-first-letters)
  - [I need to display the tags in a table: I the first row are the groups and below in each column the tags of a group.](#i-need-to-display-the-tags-in-a-table-i-the-first-row-are-the-groups-and-below-in-each-column-the-tags-of-a-group)
  - [I need to display the tags in one tag cloud. It should be possible to filter the tags by group or by name.](#i-need-to-display-the-tags-in-one-tag-cloud-it-should-be-possible-to-filter-the-tags-by-group-or-by-name)
  - [I need to display a tag cloud but only those tags that belong to a particular post.](#i-need-to-display-a-tag-cloud-but-only-those-tags-that-belong-to-a-particular-post)
  - [I need to display a tag cloud but only tags of those groups that belong to a particular post.](#i-need-to-display-a-tag-cloud-but-only-tags-of-those-groups-that-belong-to-a-particular-post)
  - [Not Displaying Tag Groups](#not-displaying-tag-groups)
  - [I need to display the tags in a simple tag cloud. The tags of different groups should be mixed together and no group names should be displayed.](#i-need-to-display-the-tags-in-a-simple-tag-cloud-the-tags-of-different-groups-should-be-mixed-together-and-no-group-names-should-be-displayed)
  - [I need to display only a subset of particular groups in a simple tag cloud.](#i-need-to-display-only-a-subset-of-particular-groups-in-a-simple-tag-cloud)
- [Other Ways to Display Tags](#other-ways-to-display-tags)
  - [I need to automatically display under each post the tags that belong to this particular post. Tags should be sorted by groups.](#i-need-to-automatically-display-under-each-post-the-tags-that-belong-to-this-particular-post-tags-should-be-sorted-by-groups)
- [Lists of Posts](#lists-of-posts)
  - [I need a filter on my page where visitors first select a group and then some of the tags of this group. Every time they add or remove a tag, they should see a list of posts that match these tags.](#i-need-a-filter-on-my-page-where-visitors-first-select-a-group-and-then-some-of-the-tags-of-this-group-every-time-they-add-or-remove-a-tag-they-should-see-a-list-of-posts-that-match-these-tags)
- [Other Ways to use Groups](#other-ways-to-use-groups)
  - [I need to display on a page the name of one or more tag groups or the number of tags in them.](#i-need-to-display-on-a-page-the-name-of-one-or-more-tag-groups-or-the-number-of-tags-in-them)
  - [I need a menu where visitors can select a group. They will then be taken to a list of matching posts.](#i-need-a-menu-where-visitors-can-select-a-group-they-will-then-be-taken-to-a-list-of-matching-posts)

Below you find a list of common cases that users need to achieve. Some of these features are available only in the Pro version.

## Tag Clouds

Tag clouds show tags of selected groups. When your visitors click on a tag, they will be taken to a new page where they see a list with matching posts.

### Displaying Tag Groups

#### I need to display the tags under tabs (arranged horizontally). The tabs should represent tag groups.

→ Use the Tabbed Tag Cloud.

#### I need to display the tags in an accordion (stacked vertically and sliding up and down). Each slider should represent a tag group.

→ Use the Accordion Tag Cloud.

#### I need to display the tags in under tabs (arranged horizontally). The tags should be sorted alphabetically into groups by their first letters.

→ Use the Alphabetical Tag Cloud.

#### I need to display the tags in a table: I the first row are the groups and below in each column the tags of a group.

→ Use the Table Tag Cloud.

#### I need to display the tags in one tag cloud. It should be possible to filter the tags by group or by name.

→ Use the Shuffle Box.

#### I need to display a tag cloud but only those tags that belong to a particular post.

Use any of the tag clouds with the parameter tags_post_id. For the current post you use tags_post_id=0.

#### I need to display a tag cloud but only tags of those groups that belong to a particular post.

Use any of the tag clouds with the parameter groups_post_id. For the current post you use groups_post_id=0.

### Not Displaying Tag Groups

#### I need to display the tags in a simple tag cloud. The tags of different groups should be mixed together and no group names should be displayed.

→ Use the Simple Tag Cloud.

#### I need to display only a subset of particular groups in a simple tag cloud.

→ Create a tag group for the tags that you want to display and use the Simple Tag Cloud.

## Other Ways to Display Tags

### I need to automatically display under each post the tags that belong to this particular post. Tags should be sorted by groups.

→ Activate the Post Tag feature in the [Tag Groups](https://publishpress.com/links/tag-groups) settings.

## Lists of Posts

### I need a filter on my page where visitors first select a group and then some of the tags of this group. Every time they add or remove a tag, they should see a list of posts that match these tags.

→ Use the Dynamic Post Filter.

## Other Ways to use Groups

### I need to display on a page the name of one or more tag groups or the number of tags in them.

→ Use the Group Info shortcode.

### I need a menu where visitors can select a group. They will then be taken to a list of matching posts.

→ Use the [Tag Groups](https://publishpress.com/links/tag-groups) Menu shortcode.
