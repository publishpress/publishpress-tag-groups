<!-- wp:paragraph -->
<p>
  <?php printf(esc_html__('%s created this sample page in the Setup Wizard of the <b>PublishPress Tag Groups</b> plugin. You can safely edit and delete it or keep it for future reference.', 'tag-groups'), esc_html($author_display_name)) ?>
</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>
  <?php _e('The following blocks use a variety of parameters so that you can get an idea of the options. Feel free to generate a new sample page with more features after upgrading the plugin.', 'tag-groups') ?>
</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>
  <?php _e('This page was created while you had the Gutenberg editor enabled. Therefore the features only display while Gutenberg is active.', 'tag-groups') ?>
</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>
  <?php printf(esc_html__('Please find links to the documentation in the <a %s>Tag Groups settings</a>.', 'tag-groups'), 'href="' . esc_url($tag_groups_settings_link) . '"') ?>
</p>
<!-- /wp:paragraph -->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2><?php _e('Tabbed Tag Cloud', 'tag-groups') ?></h2>
<!-- /wp:heading -->

<!-- wp:chatty-mango/tag-groups-cloud-tabs {"source":"gutenberg","append":"{count}","custom_title":"We found {count} post(s) for this tag.","hide_empty":0,"hide_empty_tabs":1} /-->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2><?php _e('Accordion Tag Cloud', 'tag-groups') ?></h2>
<!-- /wp:heading -->

<!-- wp:chatty-mango/tag-groups-cloud-accordion {"source":"gutenberg","hide_empty":0,"hide_empty_content":1,"mouseover":1,"prepend":"#","separator":"|"} /-->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2><?php _e('Alphabetical Tag Cloud', 'tag-groups') ?></h2>
<!-- /wp:heading -->

<!-- wp:chatty-mango/tag-groups-alphabet-tabs {"source":"gutenberg","exclude_letters":"äöüß"} /-->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2><?php _e('Tag List', 'tag-groups') ?></h2>
<!-- /wp:heading -->

<!-- wp:chatty-mango/tag-groups-tag-list {"source":"gutenberg","append":") : ({count})","column_count":3,"hide_empty":0,"largest":18,"prepend":"(","smallest":14} /-->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2><?php _e('Alphabetical Tag Index', 'tag-groups') ?></h2>
<!-- /wp:heading -->

<!-- wp:chatty-mango/tag-groups-alphabetical-tag-index {"source":"gutenberg","exclude_letters":"0123456789","hide_empty":0,"largest":24,"smallest":16} /-->

<!-- wp:separator -->
<hr class="wp-block-separator"/>
<!-- /wp:separator -->

<!-- wp:paragraph -->
<p>Created by the <a href="https://publishpress.com/tag-groups/" target="_blank" rel="noopener noreferrer">PublishPress Tag Groups plugin</a></p>
<!-- /wp:paragraph -->
