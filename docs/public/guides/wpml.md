---
title: "Translating Tag Group Names With WPML"
order: 111
doc_category: publishpress-tag-groups
---

# Translating Tag Group Names With WPML

Table of Contents

- [Where The Translations Show Up](#0-toc-title)
- [Some More Details](#1-toc-title)

Translating tag group names in the [Tag Groups](https://publishpress.com/links/tag-groups) plugin is possible with the [WordPress Multilingual (WPML) plugin](https://wpml.org).

Before you start, you need to set up WPML with all required languages.

Then you open the [Tag Groups](https://publishpress.com/links/tag-groups) admin page where you create and edit tag groups. This page will not look much different from what you are used to. But now you can edit the names of the groups for each language independently.

All you need to do is to switch to another language in the admin bar and edit the names of the tag groups. If you now change the language, you will notice that the group names will change accordingly.

![](https://docs-media.publishpress.com/publishpress-tag-groups/translating-tag-group-names-with-wpml-b9b3f6adfa.webp)

## Where The Translations Show Up [#](#0-toc-title)

- On all **admin pages**, you see the group names that correspond to the language that you see in the admin bar.
- On the **front end**, the group names match the language of the page.

## Some More Details [#](#1-toc-title)

- If you create a new group while you use a language that is not your default language, the default language will first receive its group name from that translated language so that it won’t remain empty.
- The order of the groups is the same across all languages.
- The label “not assigned” remains untranslated on your back end. On the front end you can choose the translation in the shortcode or Gutenberg block.
- Each language version of a tag can belong to a different group.((Because WPML internally treats them as different tags.))
- If you create a translation of a tag, the translated tag will receive the groups of the original tag, unless you set different groups during the translation. You can change this behavior in the settings.

This feature is available both with the Free and the Pro version of Tag Groups.
