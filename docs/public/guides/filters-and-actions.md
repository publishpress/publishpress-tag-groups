---
title: "Filters and Actions in Tag Groups"
order: 31
doc_category: publishpress-tag-groups
---

# Filters and Actions in Tag Groups

**Table of Contents**

- [Actions](#actions)
- [Filters](#filters)
  - [View Filters](#view-filters)
  - [Term Filters](#term-filters)
  - [Group Filters](#group-filters)
  - [Filters for Tag Clouds](#filters-for-tag-clouds)
  - [Filters for Live Post Filters](#filters-for-live-post-filters)
  - [Post Tags (Under Posts)](#post-tags-under-posts)
  - [Admin](#admin)

This is a guide to the filter hooks and action hooks of the [Tag Groups](https://publishpress.com/links/tag-groups) plugin. If a feature is only in the Pro version of the plugin, the corresponding hooks will also be limited to the Pro version.

## Actions

Hook into an action with [add_action()](https://developer.wordpress.org/reference/functions/add_action/). Example:

```
function group_is_gone_message( $group_id ) {
 echo "Group {$group_id} is gone!";
}
add_action( 'term_group_deleted', 'group_is_gone_message' );
```

| Action name | Arguments of callback | Triggered when |
| --- | --- | --- |
| term_group_saved | void | after information about (one or more) term groups was saved |
| term_group_deleted | integer: group ID | after a group has been deleted |
| groups_of_term_saved | integer: term ID  
integer or array of integers: tag group IDs | after a term’s assignment to groups was saved |

## Filters

Hook into a filter with [add_filter()](https://developer.wordpress.org/reference/functions/add_filter/). Examples:

```
function wrap_as_gift( $html ) {
 return "" . $html . "";
}
add_filter( 'tag_groups_post_terms', 'wrap_as_gift' );
```

```
function add_my_icon_to_accordion_tag( $html, $id, $font_size, $post_count, $shortcode ) {
 if ('tag_groups_accordion' != $shortcode) {
  return $html;
 }
 return $html . '';
}
add_filter( 'tag_groups_cloud_tag_append', 'add_my_icon_to_accordion_tag', 10, 5 );
```

### View Filters

Filter the arguments or the final HTML of views (templates) that we use for pages or page fragments on the front and back end.

| Filter name | Arguments of callback | Return data type of callback | Filters what |
| --- | --- | --- | --- |
| tag_groups_view-{$view_slug} | string: content (HTML) | string | filters a string that contains the HTML of a view before output. Find possible view_slugs in the code (in the `views` folders, use the following folder name, a dash and the part before `.view.php`; example for a filter name: `tag_groups_view-partials-admin_notice`) |
| tag_groups_premium_view-{$view_slug} | string: content (HTML) | string | see tag_groups_view-{$view_slug}; These are the views int the `/premium/views` folder. |
| tag_groups_view_atts | array: attributes  
string: slug, see tag_groups_view-{$view_slug} | array, must contain same fields and data types as the attributes | filters the attributes before they are being sent to the view |
| tag_groups_premium_view_atts | array: attributes  
string: slug, see tag_groups_view-{$view_slug} | array, must contain same fields and data types as the attributes | filters the attributes before they are being sent to the view (premium part) |

### Term Filters

Filter the terms that were retrieved from the database.

| Filter name | Arguments of callback | Return data type of callback | Filters what |
| --- | --- | --- | --- |
| tag_groups_get_terms | WP_Term[]\|int[]\|string[]\|string\|WP_Error: the retrieved terms; The type depends on the fields parameter; your callback should also consider errors  
integer: group ID, if applicable  
string\|string[]: taxonomies  
bool\|int: Whether to hide terms with post count zero  
string: fields What to return. See WP’s get_terms()  
int: post_id: This parameter is only relevant if the tags depend on the language of a post  
string: orderby  
string: order  
string: include  
string exclude  
integer: threshold | same as the first parameter (terms) | filters the terms (tags); please note that not all parameters are in use, depending on the purpose that these tags are used for (tag clouds, backend) |

### Group Filters

Here you can filter data about [tag groups](https://publishpress.com/links/tag-groups) after loading from the database, or before saving it.

| Filter name | Arguments of callback | Return data type of callback | Filters what |
| --- | --- | --- | --- |
| tag_groups_load_group_ids | array of integers | array (same as argument) | filters the group IDs after loading from the database |
| tag_groups_load_group_labels | array; keys are group IDs, values are the labels (names) | array (same as argument) | filters the group labels after loading from the database |
| tag_groups_load_group_positions | array: keys are group IDs, values are the positions (determining the order) | array (same as argument) | filters the group positions after loading from the database |
| tag_groups_save_group_ids | array of integers | array (same as argument) | filters the group IDs before saving to the database |
| tag_groups_save_group_labels | array; keys are group IDs, values are the labels (names) | array (same as argument) | filters the group labels before saving to the database |
| tag_groups_save_group_positions | array: keys are group IDs, values are the positions (determining the order) | array (same as argument) | filters the group positions before saving to the database |

### Filters for Tag Clouds

| Filter name | Arguments of callback | Return data type of callback | Filters what |
| --- | --- | --- | --- |
| tag_groups_cloud_tag_inner | string: content  
integer: term ID  
string: shortcode identifier | string | filters all tag names in tag clouds |
| tag_groups_cloud_tag_outer | string: content (HTML)  
integer: term ID  
string: shortcode identifier | string | filters all tag names in tag clouds, including the wrapping  element |
| tag_groups_cloud_tag_prepend | string: content (HTML)  
integer: term ID  
integer: font size  
integer: post count of this tag  
string: shortcode identifier | string | filters all HTML prepended to tags in tag clouds, including the  element; if nothing is prepended, it filters an empty string so that you have the option to anyway prepend something |
| tag_groups_cloud_tag_append | string: content (HTML)  
integer: term ID  
integer: font size  
integer: post count of this tag  
string: shortcode identifier | string | filters all HTML appended to tags in tag clouds, including the element; if nothing is appended, it filters an empty string so that you have the option to anyway append something |
| tag_groups_cloud_html | string: content (HTML)  
string: shortcode identifier  
array: shortcode parameters | string | filters the complete HTML output of all tag clouds |
| tag_groups_tag_title | string: title  
string: shortcode name  
string: tag description  
integer: post count | string | filters the HTML title attribute of each tag that appears as tooltip on mouseover |
| tag_groups_custom_title | string: title  
string: shortcode name  
integer: post count | string | Filters the title attribute of a tag before replacing all placeholders |

### Filters for Live Post Filters

| Filter name | Arguments of callback | Return data type of callback | Filters what |
| --- | --- | --- | --- |
| tag_groups_excerpt | object: WP_Post object | string | Use a callback function to create a custom excerpt for the placeholder {post_excerpt} in a post template in the Toggle Post Filter, Dynamic Post Filter or Post List. The function needs to take care of proper encoding and sanitation and it must make sure that HTML is self-consistent and that no tags remain open. |
| excerpt_length | integer | integer | Length of the excerpt in post templates |
| excerpt_more | string | string | Ellipse characters (…) if the excerpt is truncated |
| tag_groups_tpf_before_query | array: WP_Query arguments when searching for posts | array (valid arguments for WP_Query) | This callback filters the post query of the Toggle Post Filter just before posts are retrieved. |
| tag_groups_dpf_before_query | array: WP_Query arguments when searching for posts | array (valid arguments for WP_Query) | This callback filters the post query of the Dynamic Post Filter just before posts are retrieved. |
| tag_groups_before_placeholders | string: The HTML template  
object: WP_Post object (the currently processed post) | HTML for a post with optional placeholders | This callback filters the post template before starting to process the placeholders for a post. It is run for each post that is in the queue for output. This filter can be used to create own placeholders or change the post HTML depending on the post. |

### Post Tags (Under Posts)

| Filter name | Arguments of callback | Return data type of callback | Filters what |
| --- | --- | --- | --- |
| tag_groups_post_terms | string: content (HTML) | string | filters a string containing the table of post tags, in TagGroups_Premium_Post_Terms::get_table() |

### Admin

| Filter name | Arguments of callback | Return data type of callback | Filters what |
| --- | --- | --- | --- |
| tag_groups_allow_duplicate_group_names | boolean | boolean | whether it is allowed to add two groups with identical names; default is false if no parent groups are in use, otherwise true |
