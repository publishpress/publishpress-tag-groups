---
title: "Hide the “not assigned” Button in Shuffle Box"
order: 38
doc_category: publishpress-tag-groups
---

# Hide the “not assigned” Button in Shuffle Box

**Table of Contents**

- [Hide the button in the Gutenberg block](#hide-the-button-in-the-gutenberg-block)
- [Hide the button in the shortcode](#hide-the-button-in-the-shortcode)

You may sometimes see a “not assigned” tab when creating a Shuffle Box layout. You can see that tab in the screenshot below. The “not assigned” group is the default group for all tags and it has the ID 0 (zero).

![Shuffle box in Tag Groups plugin](https://docs-media.publishpress.com/publishpress-tag-groups/not-assigned-shuffle-box-1-316dc430c2.webp)

## Hide the button in the Gutenberg block

- Inside the Gutenberg block settings, find the “Groups” area.
- In the “Exclude groups” box, enter “not assigned”.

![Remove the not assigned group](https://docs-media.publishpress.com/publishpress-tag-groups/exclude-not-assigned-137d59c700.webp)

## Hide the button in the shortcode

- Add this parameter to the shortcode `exclude=0`.
- If you need to exclude more groups, you join the IDs into a comma-seperated list: `exclude=0,1,4`.
