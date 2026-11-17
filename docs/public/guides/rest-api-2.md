---
title: "Tag Groups and the REST API"
order: 64
doc_category: publishpress-tag-groups
---

# Tag Groups and the REST API

Table of Contents

- [Authentication](#0-toc-title)
- [Enabling POST/PUT/DELETE](#1-toc-title)
- [Groups](#groups)[Retrieve one or more groups](#3-toc-title)[Routes](#routes)[Methods](#methods)[Arguments](#arguments)[Create or update a group](#7-toc-title)[Routes](#routes)[Methods](#methods)[Arguments](#arguments)[Delete a group](#11-toc-title)[Routes](#routes)[Methods](#methods)[Arguments](#arguments)
- [Terms (Tags)](#terms_tags)[Retrieve one or more terms (tags)](#16-toc-title)[Routes](#routes1)[Methods](#methods1)[Arguments](#arguments1)[Update the groups of a term (tag)](#20-toc-title)[Routes](#routes1)[Methods](#methods1)[Arguments](#arguments1)
- [Taxonomies](#taxonomies)[Retrieve enabled taxonomies](#25-toc-title)[Routes](#routes2)[Methods](#methods2)[Arguments](#arguments2)[Update enabled taxonomies](#29-toc-title)[Routes](#routes2)[Methods](#methods2)[Arguments](#arguments2)
- [Post Tags](#33-toc-title)[Retrieve post tags](#34-toc-title)[Routes](#routes2)[Methods](#methods2)[Arguments](#arguments2)[Update post tags](#38-toc-title)[Routes](#routes2)[Methods](#methods2)[Arguments](#arguments2)

The [Tag Groups](https://publishpress.com/links/tag-groups) plugin does integrate with the WordPress REST API, however the features below are still considered experimental and not officially supported.

Please be careful with all operations that change data. There will be no confirmation dialog. To be on the safe side, make a backup of your database before you start and test first with non-critical sample data.

POST and PUT requests are handled by WordPress interchangeably.

If you use a client like Postman or RESTED, send the attributes as URLencoded form data (Header: Content-Type application/x-www-form-urlencoded) and use Basic Auth with your application password, available in WP 5.6+ or via 3rd-party plugins (untested and unsupported).

Permissions to create or edit groups can be changed in the [Tag Groups](https://publishpress.com/links/tag-groups) Settings (Pro version only).

Add the routes after your site URL. In GET requests you can simply add the arguments as parameters. Any returned data will be JSON-encoded, if applicable.

## Authentication [#](#0-toc-title)

- POST/PUT/DELETE require authentication. You can not perform any of these action if you’re not logged in and have the require capability)
- READ(GET) doesn’t require authentication as data is for both guests and users consumption

## Enabling POST/PUT/DELETE [#](#1-toc-title)

While all GET (retrieve) operations are available by default, all PUT/POST/DELETE (create, update, delete) operations are opt-in. You enable them by adding to your wp-config.php:

```
define( 'TAG_GROUPS_REST_API_EDITABLE', true );
```

Additionally you may have to authenticate, see below.

## Groups [#](#groups)

### Retrieve one or more groups [#](#3-toc-title)

Returns all groups or one group with its ID, label, position and terms.

#### Routes [#](#routes)

/wp-json/tag-groups/v1/groups/

/wp-json/tag-groups/v1/groups/{id}/

#### Methods [#](#methods)

GET

#### Arguments [#](#arguments)

| Argument | Values | Default | Description |
| --- | --- | --- | --- |
| taxonomy | comma-separated list | all that are selected for tag groups |  |
| hide_empty | boolean | false |  |
| fields | ids, … | ids |  |
| orderby | name, … | name |  |
| order | ASC, DESC | ASC |  |
| type | all, metabox | all | If you choose ‘metabox’, you see all groups that appear in the Tag Meta Box. (Pro version only) |

### Create or update a group [#](#7-toc-title)

In order to use this endpoint you will need to authenticate as a user who has sufficient permissions.

For creating a new group you use 0 as ID.

#### Routes [#](#routes)

/wp-json/tag-groups/v1/groups/{id}/

#### Methods [#](#methods)

PUT, POST

#### Arguments [#](#arguments)

| Argument | Values | Default | Description |
| --- | --- | --- | --- |
| label | string |  | cannot be empty if you create a new group |
| position | integer > 0 | after last existing | The position among the groups. The new group will be added at the end if you omit this argument. |

### Delete a group [#](#11-toc-title)

In order to use this endpoint you will need to authenticate as a user who has sufficient permissions.

#### Routes [#](#routes)

/wp-json/tag-groups/v1/groups/{id}/

#### Methods [#](#methods)

DELETE

#### Arguments [#](#arguments)

not available

## Terms (Tags) [#](#terms_tags)

### Retrieve one or more terms (tags) [#](#16-toc-title)

Returns all terms or one term with its ID, label, position and terms.

#### Routes [#](#routes1)

/wp-json/tag-groups/v1/terms/

/wp-json/tag-groups/v1/terms/{id}/

#### Methods [#](#methods1)

GET

#### Arguments [#](#arguments1)

| Argument | Values | Default | Description |
| --- | --- | --- | --- |
| taxonomy | comma-separated list, or keyword “public” | all enabled | This endpoint returns only taxonomies that are enabled in the Tag Groups (Pro) settings. The keyword “public”, however, makes it return all public taxonomies, including those that are not-enabled. |
| hide_empty | boolean | false |  |
| fields |  | ids |  |
| group | ID |  | restrict terms to those belonging to a certain group |
| orderby |  | name |  |
| order |  | ASC |  |

### Update the groups of a term (tag) [#](#20-toc-title)

In order to use this endpoint you will need to authenticate as a user who has sufficient permissions.

#### Routes [#](#routes1)

/wp-json/tag-groups/v1/terms/{id}/

#### Methods [#](#methods1)

PUT, POST

#### Arguments [#](#arguments1)

| Argument | Values | Description |
| --- | --- | --- |
| groups | comma-separated list of group IDs | Please note that groups will not be added but overwrite previous data. |

## Taxonomies [#](#taxonomies)

### Retrieve enabled taxonomies [#](#25-toc-title)

Returns all taxonomies (slug and name) that are enabled for tag groups or for the Tag Meta Box (Pro only).

#### Routes [#](#routes2)

/wp-json/tag-groups/v1/taxonomies/

#### Methods [#](#methods2)

GET

#### Arguments [#](#arguments2)

| Argument | Values | Default | Description |
| --- | --- | --- | --- |
| type | metabox, enabled | enabled |  |

### Update enabled taxonomies [#](#29-toc-title)

Updates all taxonomies that are enabled for tag groups or for the Tag Meta Box (Pro only).

In order to use this endpoint you will need to authenticate as a user who has sufficient permissions.

#### Routes [#](#routes2)

/wp-json/tag-groups/v1/taxonomies/

#### Methods [#](#methods2)

PUT, POST

#### Arguments [#](#arguments2)

| Argument | Values | Description |
| --- | --- | --- |
| enabled | comma-separated list of taxonomy slugs | taxonomies that are enabled for tag groups |
| metabox | comma-separated list of taxonomy slugs | taxonomies that are enabled for the Tag Meta Box (Pro only) |

## Post Tags [#](#33-toc-title)

Only available in the Pro version.

These endpoints retrieve and update only tags in groups as used by this plugin. If you need to handle other post data you should use the built-in endpoints of WP.

### Retrieve post tags [#](#34-toc-title)

Only posts will appear that you are allowed to view.

The ID is a post ID. For each post the authenticated user’s (if applicable) permissions will be checked.

#### Routes [#](#routes2)

/wp-json/tag-groups/v1/post-tags/

/wp-json/tag-groups/v1/post-tags/{id}/

#### Methods [#](#methods2)

GET

#### Arguments [#](#arguments2)

| Argument | Values | Description |
| --- | --- | --- |
| post_type | string (slug) | usually “post_tag” |

### Update post tags [#](#38-toc-title)

In order to use this endpoint you will need to authenticate as a user who has sufficient permissions.

The ID is a post ID. For each post the authenticated user’s permissions will be checked.

If you add a tag to a post and this tag is not yet assigned to the submitted group, then this group will be assigned.

#### Routes [#](#routes2)

/wp-json/tag-groups/v1/post-tags/{id}/

#### Methods [#](#methods2)

PUT, POST

#### Arguments [#](#arguments2)

| Argument | Values | Description |
| --- | --- | --- |
| terms | JSON-encoded array | JSON-encoded array of group IDs as keys and an array of term IDs as each value. Use group ID 0 for unassigned tags. example: {“0″:[1235],”9”:[147]} |
| taxonomy | taxonomy slug |  |
