---
title: "How to Use Shortcodes With Tag Groups"
order: 45
doc_category: publishpress-tag-groups
---

# How to Use Shortcodes With Tag Groups

**Table of Contents**

- [What Tag Groups Shortcodes Look Like](#what-tag-groups-shortcodes-look-like)
- [Troubleshooting Tag Groups Shortcodes](#troubleshooting-tag-groups-shortcodes)
- [Gutenberg is Also Available](#gutenberg-is-also-available)

The [Tag Groups](https://publishpress.com/links/tag-groups) plugin provides a large number of shortcodes to help display your terms and posts.

Shortcodes are something like placeholders. You insert them into the body of a post, page or text widget and they will be replaced in the output with something different, depending on the code you use.

Shortcodes are mostly provided by plugins and themes. If you deactivate a plugin or theme, all of its shortcodes that you used will now appear unprocessed on the frontend, the way you see them in the editor.

## What Tag Groups Shortcodes Look Like

Shortcodes are enclosed by square bracket and they start with a shortcode’s specific name (or identifier), followed by a space and then optional parameter-value pairs. Parameters are predefined for each shortcode.

Together with their values, parameters look like this: `size=12 button=large title="Hello there!"`. All parameter-value pairs are separated from each other by spaces.

Here is a full example of a shortcode with parameters and values:

```
[tag_groups_alphabet_tabs exclude_letters="Z" smallest=16]
```

- “tag_groups_alphabet_tabs” is the identifier of the shortcode
- “exclude_letters” is a parameter and “Z” is its value
- “smallest” is second parameter and “16” is its value

It’s possible to add more details to the display created by the shortcode. You can use “prepend” to add details before the term. You can use “append” to add details after the term.

```
[tag_groups_alphabet_tabs prepend="Tag: " append="{count}"]
```

- “tag_groups_alphabet_tabs” is the identifier of the shortcode.
- “Tag: ” will be added before each term name.
- “{count}” will show the number of posts attached to each term. It will be shown after the term name.

## Troubleshooting Tag Groups Shortcodes

If a shortcode doesn’t work or one or more parameters don’t have any effect, double-check the following points:

1. Check for **typos** in the code or the parameters.
2. When you copy and paste shortcodes from a web site, make sure you **don’t copy over any formatting**.
3. Try to **re-type all double quotes** since curly or tilted quotes are not recognized by WordPress.
4. If the value of a parameter contains **spaces**, then you need to enclose it in double quotes.
5. If you need to use **quotes** inside the value, you can use single quotes or double quotes, what ever you haven’t used for the outer enclosure. Example: `parameter="here is 'some text'"`. Some shortcodes, however, cannot handle both types of quotes, if the text becomes part of the HTML. Just try which one works.
6. Avoid nesting or overlapping of shortcodes since WordPress is sometimes confused on how to handle them.
7. Avoid HTML code in parameter values because WordPress tries to “fix” it and thinks that the HTML outside the shortcode continues inside that value.

## Gutenberg is Also Available

If you don’t want to mess around with shortcodes and parameters, you can also get the same results with Gutenberg, which is the default WordPress editor. All tag cloud shortcodes exist as Gutenberg blocks and you simply configure them through a user interface.

![](https://docs-media.publishpress.com/publishpress-tag-groups/gutenberg-example-fb27d420b5.webp)
