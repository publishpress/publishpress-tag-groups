---
title: "Using Tag Groups in Theme Files"
order: 93
doc_category: publishpress-tag-groups
---

# Using Tag Groups in Theme Files

**Table of Contents**

- [Using shortcodes](#using-shortcodes)
- [Retrieving groups and tags with PHP](#retrieving-groups-and-tags-with-php)
- [Other Functions](#other-functions)
- [REST API](#rest-api)

This guide has advice on how to use the [Tag Groups](https://publishpress.com/links/tag-groups) plugin inside the PHP templates files of your theme.

## Using shortcodes

You can use the native WordPress function [do_shortcode](https://developer.wordpress.org/reference/functions/do_shortcode/) to execute a shortcode in your plugin or theme. Please note that this doesn’t automatically load the scripts that are needed for some features. Make sure that you activate “Always load scripts” in the [Tag Groups](https://publishpress.com/links/tag-groups) settings.

```
echo do_shortcode('[tag_groups_cloud include="6"]');
```

## Retrieving groups and tags with PHP

By default the function **tag_groups_cloud** returns the html for a tabbed tag cloud. If you want to add the tag cloud directly into your theme, you could do it like this:

```
if ( function_exists( 'tag_groups_cloud' ) ) echo tag_groups_cloud( array( 'amount' => 10 ) );
```

The array can, of course, contain also more parameters:

```
array( 'include' => '1,2,5,6', 'amount' => 10 )
```

If the optional second parameter is set to ‘true’, the function will return a multidimensional array containing tag groups and tags:

```
 if ( function_exists( 'tag_groups_cloud' ) ) print_r( tag_groups_cloud( array( 'orderby' => 'count', 'order' => 'DESC' ) , true ) );
```

[Click here for more info about the parameters](https://taxopress.com/docs/tabbed-tag-cloud-shortcode-parameters/).

## Other Functions

```
post_in_tag_group( $post_id, $tag_group_id )
```

The function **post_in_tag_group** checks whether the post with the given `$post_id` has at least one tag that is part of the tag group with the given `$tag_group_id`. The return value is true or false.

## REST API

You can request data through custom endpoints of the REST API. [Click here for more on Tag Groups and the REST API](https://taxopress.com/docs/rest-api/).
