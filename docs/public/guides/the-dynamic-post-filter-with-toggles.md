---
title: "Toggle Post Filter – Overview"
order: 89
doc_category: publishpress-tag-groups
---

# Toggle Post Filter – Overview

**Table of Contents**

- [Instructions](#instructions)
- [Settings and Parameters](#settings-and-parameters)
  - [Common Settings](#common-settings)
- [Further Notes](#further-notes)
- [Performance](#performance)
- [Preset Tags](#preset-tags)
- [Alternatives](#alternatives)
- [Common Issues and Solutions](#common-issues-and-solutions)

The Toggle Post Filter is a frontend feature in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin. Visitors can select tags and see a list of posts that match their search criteria. You can use either shortcodes or Gutenberg blocks for this feature.

- [Click here to see demos of the Toggle Post Filter feature](https://demo.taxopress.com/tag-list/).
- [Click here for the Gutenberg block options](https://taxopress.com/docs/tag-list-gutenberg-block/).
- [Click here for the shortcode parameters](https://taxopress.com/docs/tag-list-parameters/).

Here’s a screenshot of the Toggle Post Filter feature from [our demo site](https://demo.taxopress.com). This display is showing two groups of tags. Results show if the photo is linked to at least one selected tag.

![Toggle Post Filter](https://docs-media.publishpress.com/publishpress-tag-groups/toggle-post-filter-0ee6d56459.webp)

## Instructions

To insert the Toggle Post Filter into a page, you can use either shortcodes or Gutenberg blocks. If you don’t feel comfortable with searching and configuring shortcode parameters, Gutenberg is the recommended path to follow.

For this tool you need to insert *at least two* blocks or shortcodes: One for the **filter menu** and one for the **post block** (body). Optionally you can also insert one for **messages** about how many posts were found, a pull-down menu to change the **sort order** and a **reset button**. If you use the slider layout, you also need a **button to open the slider**. Keeping these blocks separate makes it possible to freely arrange the components in your page layout.

The menu and the body parts of the vertical (“classic”) layout can be placed in columns side by side.

If groups become too long or have a different height in wide layout, try if the accordion option makes sense here.

Please make sure that you have **published posts** that use the tags that you see in the menu. If you still don’t see the posts, you may need to use the First Aid buttons in the Troubleshooting menu.

## Settings and Parameters

For all available options, see

- the settings of the Gutenberg block
- the list of shortcode parameters

### Common Settings

- **included taxonomies:** Most sites show default WordPress posts. If you want to show products or gallery items or other post types, you will need to select the corresponding taxonomy here: product_tag, xyz_tag
- **logic operator:** Select here how matching posts should be determined from the tags. A more fine-grained configuration per group is available in the shortcode.
- **included groups:** Narrow down the [tag groups](https://publishpress.com/links/tag-groups) that should be used.
- **select only one tag:** It is possible to have one or more groups where visitors can select only one tag.
- **text search:** Optionally show a text search field. The search term will be connected with the other search criteria using the logic operator AND (refine the results). Search is triggered on pressing enter, optionally also after you stopped typing. Use a dash in front of the search term to exclude it (AND NOT).
- **caching:** Speed up complex searches by caching the results on your server. You can also save the selected search criteria for each visitor with the “persistent filter” option. When they click through to a post and then return to the list, they continue where they left off.
- **initially show all posts:** This option lets you show all posts when no toggle is set, rather than showing an empty box with a placeholder message. This provides a “filter” experience, rather than searching.
- **pager:** Shows arrows to load more or go back or a pagination by page numbers. Without a pager the rest of the list will be cut off. You can also set the number of posts per page.
- **total amount:** Optionally show a message about the total amount. You can use the extra block or shortcode for the message or display it in a an overlay (popup). This text can be customized for singular and plural.
- **layout:** There are several layouts for the menu part and for the post part. The menu can also be configured to hide tags in an accordion under each group. Hidden toggle switches are also effective.
- **themes:** Two out-of-the-box themes are available, light and dark. You set them with the “theme” parameter.
- **post template:** Here you can configure what every post should element look like. You can also edit the HTML for the posts in the Tag Groups settings (go to Front End, Post Filter).
- **hide toggles in an accordion:** If you use many tags in a group, the list of toggles might become very long. The accordion option lets you show only group labels and hide all toggles in “drawers”.

## Further Notes

- Toggles (tags) can be sorted by name, slug, ID, description or post count.
- Use include tags (terms) and exclude tags (terms) to fine-tune the available tags.
- With knowledge of CSS you can create your own themes. Toggle switches are HTML checkboxes.
- This post filter can only be used *once* on each page (including widgets), and it cannot be used together with the shortcode or block of the Dynamic Post Filter.
- If you sort posts randomly, you should disable the pager since for each page the order of all posts across all pages is randomized again.

## Performance

Please note that it is unavoidable that complex queries take some time to process in the database. That is particularly the case if many tags are selected in multiple groups. The search, including tags and text, makes use of default WordPress post queries, which are already optimized for use on busy websites.

There are several ways how to improve performance:

1. **Utilize server caching:** With server caching we avoid executing the full database request multiple times for identical searches. In the Gutenberg block you set the caching time under *Caching and Performance*, in the shortcode with caching_time.
2. **Optimize the database:** The speed of database queries can greatly be improved by adding an index to the postmeta table. For this solution you might need the help of your system administrator who can run SQL queries on your database. [See here for more details](https://taxopress.com/docs/how-to-improve-the-performance-of-the-toggle-post-filter-dynamic-post-filter/).
3. **Reduce redundant queries:** Visitors often want to search for a combination of tags and text and we don’t need to kick off that search until they are done making their selection. We achieved this through a timeout (by default one second) of inactivity before we send the query to the server. We dynamically increase this timeout if we notice that a visitor selects many tags or doesn’t seem to be sure which options to choose.((since version 1.40.1)) If you still see too many premature server queries you can increase the value for the timeout (in the block under *Caching and Performance*, in the shortcode use timeout).((since version 1.40.1)) Try to find a good balance between a responsive search for a good user experience and a reduction of redundant requests to the server.
4. **Optimize images:** When posts are rendered, the browser also retrieves the images from the web. In the post templates try to use only image formats as large as it makes sense. Furthermore it is recommended to optimize image files for smaller size without compromising on quality.((There are free plugins that let you do that.))

## Preset Tags

Sometimes it comes in handy to preset tags for new visitors arriving at that page. Visitors will see the corresponding posts and can then change the filter.

There are two ways how to preset these initial tags: in the block/shortcode and via a URL parameter. In both cases you use the tag slug, which you find for each tag on the tags page. Multiple tags can be added in a comma-separated list.

In the Gutenberg block you select the tags in “Preset tags”.

In the shortcode for the menu part you enter:

```
[[tag_groups_dpf_toggle_menu preset_tags="tag-slug-1,tag-slug-2"]]
```

Or you add the URL-Parameter to the URL of the page where you show the Toggle Post Filter:

```
www.example.com/page-with-dynamic-post-filter/?presettags=tag-slug-1,tag-slug-2
```

If both the block/shortcode parameter and the URL parameter are set, we will use only the latter one.

Please note that preset tags are only effective when someone visits the page **for the first time**. When later returning to this page, some browsers keep the previous selection of toggles in the cache.

In the case that you also use the persistent filter (which is by default on – disable it with persistent_filter=0), the saved filter also takes priority over the preset tags. This means that visitors who made a selection and head over to a post will see their own selection when returning, rather than the preset tags. While you are testing the page, it might therefore help to temporarily disable the persistent filter or set it to a very low value.

If you preset a tag, this tag is effective for *all* groups where it appears. It is not possible to turn on the toggle only in one group while the same tag would be off in another group.

## Alternatives

If you don’t want your visitors to select tags with a toggle (on-off) switch but to enter them into a tag field, you could take a look at the Dynamic Post Filter.

If you don’t need the interactivity of a filter where posts respond live to search criteria, you may want to check out the Post List.

You can also use one of the tag clouds. They are highly customizable so that you can, for example, display all tags in the same size or prepend to each the same character. With the tag clouds, however, visitors don’t see immediately the list of matching posts. Instead, a click on the tag takes them to another page, where all posts are listed that use this tag.

## Common Issues and Solutions

If no tags or no posts appear, please make sure that

- you have selected the correct taxonomy. If you need something else than the default post_tag, use the *taxonomy* parameter;
- you have assigned your tags to groups;
- you use these tags in *published* posts.

If that still doesn’t help, you may want to try the buttons on the Troubleshooting page (particularly if you installed the plugin on a site with existing content and the meta data has not yet been generated).
