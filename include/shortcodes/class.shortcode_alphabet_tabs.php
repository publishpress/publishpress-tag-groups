<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (! class_exists('TagGroups_Shortcode_Alphabet_Tabs')) {
    class TagGroups_Shortcode_Alphabet_Tabs extends TagGroups_Shortcode_Common
    {
          /**
           * attributes that we can use in the Gutenberg editor for server-side render
           *
           * @var array
           */
        public static $serverside_render_attributes = array(
        'source' => array(
        'type' => 'string',
        'default' => '',
        ),
        'active' => array(
        'type' => 'integer',
        'default' => -1,
        ),
        'adjust_separator_size' => array(
        'type' => 'integer',
        'default' => 1,
        ),
        'amount' => array(
        'type' => 'integer',
        'default' => 0,
        ),
        'append' => array(
        'type' => 'string',
        'default' => '',
        ),
        'assigned_class' => array(
        'type' => 'string',
        'default' => '',
        ),
        'collapsible' => array(
        'type' => 'integer',
        'default' => 0,
        ),
        'custom_title' => array(
        'type' => 'string',
        'default' => '{description} ({count})',
        ),
        'custom_title_zero' => array(
        'type' => 'string',
        'default' => '{description} ({count})',
        ),
        'custom_title_plural' => array(
        'type' => 'string',
        'default' => '{description} ({count})',
        ),
        'delay' => array(
        'type' => 'integer',
        'default' => 1,
        ),
        'div_class' => array(
        'type' => 'string',
        'default' => 'tag-groups-cloud',
        ),
        'div_id' => array(
        'type' => 'string',
        'default' => '',
        ),
        // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
        'exclude' => array(
        'type' => 'string',
        'default' => '',
        ),
        'exclude_letters' => array(
        'type' => 'string',
        'default' => '',
        ),
        'exclude_terms' => array(
        'type' => 'string',
        'default' => '',
        ),
        'hide_empty' => array(
        'type' => 'integer',
        'default' => 1,
        ),
        'include' => array(
        'type' => 'string',
        'default' => '',
        ),
        'include_letters' => array(
        'type' => 'string',
        'default' => '',
        ),
        'include_terms' => array(
        'type' => 'string',
        'default' => '',
        ),
        'largest' => array(
        'type' => 'integer',
        'default' => 22,
        ),
        'link_append' => array(
        'type' => 'string',
        'default' => '',
        ),
        'link_target' => array(
        'type' => 'string',
        'default' => '_self',
        ),
        'locale' => array(
        'type' => 'string',
        'default' => '',
        ),
        'mouseover' => array(
        'type' => 'integer',
        'default' => 0,
        ),
        'order' => array(
        'type' => 'string',
        'default' => 'ASC',
        ),
        'orderby' => array(
        'type' => 'string',
        'default' => 'name',
        ),
        'prepend' => array(
        'type' => 'string',
        'default' => '',
        ),
        'separator' => array(
        'type' => 'string',
        'default' => '',
        ),
        'separator_size' => array(
        'type' => 'integer',
        'default' => 22,
        ),
        'show_tag_count' => array(
        'type' => 'integer',
        'default' => 1,
        ),
        'smallest' => array(
        'type' => 'integer',
        'default' => 12,
        ),
        'tags_post_id' => array(
        'type' => 'integer',
        'default' => -1,
        ),
        'taxonomy' => array(
        'type' => 'string',
        'default' => '',
        ),
        'threshold' => array(
        'type' => 'integer',
        'default' => 0,
        ),
        'ul_class' => array(
        'type' => 'string',
        'default' => '',
        ),
        );
        public $html_tags;
        public $html_tabs;

      /**
      *
      * Render the tabbed tag cloud, usually by a shortcode, or returning a multidimensional array
      *
      * @param array $atts
      * @param bool $return_array
      * @return string
      */
        function tag_groups_alphabet_tabs($atts = array())
        {

            $this->init();
            $this->shortcode_id = 'tag_groups_alphabet_tabs';
            $this->set_attributes(shortcode_atts(array(
            'active' => -1,
            'adjust_separator_size' => true,
            'amount' => 0,
            'append' => '',
            'assigned_class' => null,
            'do_not_cache' => false,
            'collapsible' => null,
            'custom_title' => null,
            'custom_title_zero' => null,
            'custom_title_plural' => null,
            'delay' => true,
            'div_class' => 'tag-groups-cloud',  // tag-groups-cloud preserved to create tab functionality
            'div_id' => '',
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
            'exclude' => '',
            'exclude_letters' => '',
            'exclude_terms' => '',
            // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
            // 'hide_empty_tabs' => false, // doesn't make sense here
            'hide_empty' => true,
            'ignore_accents' => false,
            'include' => '',
            'include_letters' => '',
            'include_terms' => '',
            'largest' => 22,
            'link_append' => '',
            'link_target' => '',
            'locale'  => '',
            'min_max_per_letter' => 1, // option to assign post counts to font sizes for each letter separately; here different than Alphabetical Tag List because we don't see all tab contents simultaneously
            'mouseover' => null,
            'order' => 'ASC',
            'orderby' => 'name',
            'prepend' => '',
            'remove_filters' => 1,
            'separator' => '',
            'separator_size' => 12,
            'show_tag_count' => true,
            'smallest' => 12,
            'source' => 'shortcode',
            'tags_post_id' => -1,
            'target_size' => 0,
            'taxonomy' => implode(',', TagGroups_Taxonomy::get_enabled_taxonomies()),
            'threshold' => 0, // minimum number of posts, total (independent of groups)
            'ul_class' => ''
            ), $atts));
    /**
             * Don't set it as default in extract( shortcode_atts() ) because the block sends an empty string
             */
            if (empty($this->attributes->html_id)) {
                $this->attributes->html_id = 'tag-groups-cloud-alphabet-tabs-' . uniqid();
            }

          /**
          * Keep always jQuery UI class to produce correct output
          */
            if (! in_array('tag-groups-cloud', array_map('trim', explode(' ', $this->attributes->div_class)))) {
                $this->attributes->div_class .= ' tag-groups-cloud';
            }

            if ($this->attributes->delay) {
                $this->attributes->div_class .= ' tag-groups-cloud-hidden';
            }
            $this->attributes->div_class = TagGroups_Shortcode_Statics::maybe_add_target_size_class($this->attributes, $this->attributes->div_class);

            $div_id_output = $this->attributes->html_id ? ' id="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->html_id) . '"' : '';
            $div_class_output = $this->attributes->div_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->div_class) . '"' : '';
            if (is_array($atts)) {
                asort($atts);
            }

      /**
       * Call this before creating the cache key
       */
            $this->get_post_id();
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
            $this->cache_key = md5('alphabet_tabs' . serialize($atts) . serialize($this->attributes->tags_post_id));
    // check for a cached version (premium plugin)
            $html = apply_filters('tag_groups_hook_cache_get', false, $this->cache_key);
            if ($html) {
                $html = $this->finalize_html($html, $div_id_output, $div_class_output, $atts);
                return $html;
            }


            $this->check_attributes();
            $this->get_taxonomies();
            $this->get_tags();
            $this->make_include_array();
            $this->maybe_add_post_tags_or_groups();
            $this->html_tabs = array();
            $this->html_tags = array();
            $ul_class_output = $this->attributes->ul_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->ul_class) . '"' : '';
    // apply sorting that cannot be done on database level
            if ('natural' == $this->attributes->orderby || 'random' == $this->attributes->orderby || $this->attributes->threshold) {
                $this->sort();
            }

      /**
      * Extract the alphabet
      */
            $this->extract_alphabet();
            $this->include_exclude_letters();
            $this->sort_alphabet();
            $html = '';
    /*
      *  render the tabs
      */
            $this->make_tabs_HTML();
    /*
      *  render the tab content
      */
            $this->determine_min_max_alphabet();
            $this->make_tags_HTML();
    /*
      * assemble tabs
      */
            $html .= '<ul' . $ul_class_output . '>' . implode("\n", $this->html_tabs) . '</ul>';
    /*
      * assemble tags
      */
            $html .= implode("\n", $this->html_tags);
            if (! $this->attributes->do_not_cache) {
              // create a cached version (premium plugin)
                do_action('tag_groups_hook_cache_set', $this->cache_key, $html);
            }


            $html = $this->finalize_html($html, $div_id_output, $div_class_output, $atts);
            return $html;
        }


      /**
       * creates the HTML for the tab part
       *
       * @return void
       */
        function make_tabs_HTML()
        {

            $i = 0;
            foreach ($this->attributes->alphabet as $letter) {
            /**
                      * Convert to upper case only now; otherwise ß would become SS and affect all cases with S
                      */
                  $this->html_tabs[ $i ] = '<li data-letter="' . htmlentities(mb_strtolower($letter), ENT_QUOTES, "UTF-8") . '"><a href="#tabs-1' . $i . '" >' . htmlentities(mb_strtolower($letter), ENT_QUOTES, "UTF-8") . '</a></li>';
                $i++;
            }
        }


      /**
       * creates the HTML for the tag part
       *
       * @return void
       */
        function make_tags_HTML()
        {

            $i = 0;
            foreach ($this->attributes->alphabet as $letter) {
                $count_amount = 0;
                $this->html_tags[ $i ] = '';
                foreach ($this->tags as $key => $tag) {
                    $other_tag_classes = '';
                    if (! empty($this->attributes->amount) && $count_amount >= $this->attributes->amount) {
                        break;
                    }

                    if ($this->get_first_letter($tag->name) != $letter) {
                        continue;
                    }

                    if (! empty($this->include_array)) {
                        $tg_term = new TagGroups_Term($tag);
                        if (! $tg_term->has_group($this->include_array)) {
                            continue;
                        }
                    }

                    $post_count = $tag->count;
                    if (! $this->attributes->hide_empty || $post_count > 0) {
                            $tag_link = $this->get_tag_link($tag);
                            $font_size = $this->font_size($post_count, $this->min_max[ $letter ]['min'], $this->min_max[ $letter ]['max']);
                            $font_size_separator = $this->attributes->adjust_separator_size ? $font_size : $this->attributes->separator_size;
                        if ($count_amount > 0 && ! empty($this->attributes->separator)) {
                            $this->html_tags[ $i ] .= TagGroups_Shortcode_Statics::separator_html($this->attributes->separator, $font_size_separator);
                        }

                        $other_tag_classes = $this->get_assigned_tag_class($tag->term_id);

                        $title = $this->get_title($tag, $post_count);
                        $title = $this->maybe_filter_title($title, $tag->description, $post_count);
                        $title_html = ( $title == '' ) ? '' : ' title="' .  esc_attr($title) . '"';
            // replace placeholders in prepend and append
                        $prepend_output = $this->get_prepend_output($post_count);
                        $append_output = $this->get_append_output($post_count);
            // adding link target
                        $link_target = TagGroups_Shortcode_Statics::sanitize_link_target($this->attributes->link_target);
                        $link_target_html = ! empty($link_target) ? 'target="' . esc_attr($link_target) . '"' : '';
            // assembling a tag
                        $this->html_tags[ $i ] .= '<span class="tag-groups-tag' . $other_tag_classes . '" style="font-size:' . $font_size . 'px"><a href="' . $tag_link . '" ' . $link_target_html . '' . $title_html . '  class="' . $tag->slug . '">';
                        if ('' != $prepend_output) {
                                    $prepend_html = '<span class="tag-groups-prepend" style="font-size:' . $font_size . 'px">' . htmlentities($prepend_output, ENT_QUOTES, "UTF-8") . '</span>';
                        } else {
                                $prepend_html = '';
                        }

            /**
             * Hook to filter the prepended HTML
             * 
             * @param string $prepend_html
             * @param int $tag->term_id
             * @param int $font_size
             * @param int $post_count
             * @param string $this->shortcode_id
             * @return string
             */
                        $this->html_tags[ $i ] .= apply_filters('tag_groups_cloud_tag_prepend', $prepend_html, $tag->term_id, $font_size, $post_count, $this->shortcode_id);
            /**
                         * Hook to filter inner HTML
                         * 
                         * @param string $tag->name
                         * @param int $tag->term_id
                         * @param string $this->shortcode_id
                         * @return string
                         */
                        $inner_html = apply_filters('tag_groups_cloud_tag_inner', $tag->name, $tag->term_id, $this->shortcode_id);
            /**
                         * Hook to filter outer HTML
                         * 
                         * @param string HTML
                         * @param int $tag->term_id
                         * @param string $this->shortcode_id
                         * @return string
                         */
                        $this->html_tags[ $i ] .= apply_filters('tag_groups_cloud_tag_outer', '<span class="tag-groups-label" style="font-size:' . $font_size . 'px">' . $inner_html . '</span>', $tag->term_id, $this->shortcode_id);
                        if ('' != $append_output) {
                            $append_html = '<span class="tag-groups-append" style="font-size:' . $font_size . 'px">' . htmlentities($append_output, ENT_QUOTES, "UTF-8") . '</span>';
                        } else {
                            $append_html = '';
                        }

            /**
             * Hook to filter the appended HTML
             * 
             * @param string $append_html
             * @param int $tag->term_id
             * @param int $font_size
             * @param int $post_count
             * @param string $this->shortcode_id
             * @return string
             */
                        $this->html_tags[ $i ] .= apply_filters('tag_groups_cloud_tag_append', $append_html, $tag->term_id, $font_size, $post_count, $this->shortcode_id);
                        $this->html_tags[ $i ] .= TagGroups_Shortcode_Statics::sr_count_html($this->attributes, $post_count);
                        $this->html_tags[ $i ] .= '</a></span> ';
                        $count_amount++;
                    }

                    unset($this->tags[ $key ]);
            // We don't need to look into that one again, since it can only appear under on tab
                }

                if (! $count_amount) {
                    unset($this->html_tabs[ $i ]);
                    unset($this->html_tags[ $i ]);
                } elseif (isset($this->html_tags[ $i ])) {
                    $this->html_tags[ $i ] = '<div id="tabs-1' . $i . '">' .  $this->html_tags[ $i ] . '</div>';
                }

                $i++;
            }
        }


      /**
       * wrap the HTML in code that is independent of caching
       *
       * @param string $html
       * @param string $div_id_output
       * @param string $div_class_output
       * @param array $atts
       * @return string
       */
        function finalize_html($html, $div_id_output, $div_class_output, $atts)
        {

            // Panel IDs must be unique when there are several tab clouds on a page; the first one keeps its IDs.
            $html = TagGroups_Shortcode_Statics::prefix_tab_panel_ids($html, TagGroups_Shortcode_Statics::next_instance('tabs'));
            $html = '<div' . $div_id_output . $div_class_output . '>' . $html . '</div>';
    // entire wrapper
            if ($this->attributes->delay) {
                $html .= TagGroups_Shortcode_Statics::noscript_unhide_html();
            }

            $html .= $this->custom_js_tabs();
    /**
             * Hook to filter final HTML
             * 
             * @param string $html
             * @param string $this->shortcode_id
             * @param array $atts
             * @return string
             */
            $html = apply_filters('tag_groups_cloud_html', $html, $this->shortcode_id, $atts);
            return $html;
        }
    }


}
