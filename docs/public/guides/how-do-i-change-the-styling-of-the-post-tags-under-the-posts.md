---
title: "Change the Icons on Tags Under Posts"
order: 37
doc_category: publishpress-tag-groups
---

# Change the Icons on Tags Under Posts

This guide will show you how to change the style of [the Tag Groups that appear underneath posts](https://taxopress.com/docs/post-tags/).

Use the following CSS in your Customizer -> CSS or your style.css to add a tag icon in front of each tag. The Dashicon used here is “f323”. You can find a new icon and the related number by [visiting the Dashicons guide on WordPress.org](https://developer.wordpress.org/resource/dashicons/).

For example, a “site” icon is “f319” and a “comments” icon is “f101”.

```
.tag-groups-premium-post-tags-table td a {
  text-decoration: none;
}

.tag-groups-premium-post-tags-table td a:before {
  content: "\f323";
  font-family: Dashicons;
  margin: 0 .2em 0 .4em;
  padding-right: .1em;
  padding-left: .5em;
}
```

The output using “f323” as a Dashicon will be like this screenshot below:

![](https://docs-media.publishpress.com/publishpress-tag-groups/post-tags-example-2be86847d5.webp)
