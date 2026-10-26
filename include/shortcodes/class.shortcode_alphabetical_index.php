<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (! class_exists('TagGroups_Shortcode_Alphabetical_Index')) {
    class TagGroups_Shortcode_Alphabetical_Index extends TagGroups_Shortcode_Common
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
        'amount' => array(
        'type' => 'integer',
        'default' =>  0,
        ),
        'append' => array(
        'type' => 'string',
        'default' => '',
        ),
        'assigned_class' => array(
        'type' => 'string',
        'default' => '',
        ),
        'column_count' => array(
        'type' => 'integer',
        'default' => 2,
        ),
        'column_gap' => array(
        'type' => 'string',
        'default' => '10px',
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
        'div_class' => array(
        'type' => 'string',
        'default' => 'tag-groups-alphabetical-index',
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
        'h_level' => array(
        'type' => 'integer',
        'default' => 3,
        ),
        'header_class' => array(
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
        'keep_together' => array(
        'type' => 'integer',
        'default' => 1,
        ),
        'largest' => array(
        'type' => 'integer',
        'default' => 12,
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
        'show_tag_count' => array(
        'type' => 'integer',
        'default' => 1,
        ),
        'smallest' => array(
        'type' => 'integer',
        'default' => 12,
        ),
        'tags_div_class' => array(
        'type' => 'string',
        'default' => 'tag-groups-alphabetical-index-tags',
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
        );
        public $html_headings;
        public $html_tags;
    
      /**
      *
      * Render the accordion tag cloud
      *
      * @param array $atts
      * @return string
      */
        function tag_groups_alphabetical_index($atts = array())
        {

            $this->init();
            $this->shortcode_id = 'tag_groups_alphabetical_index';
            $this->set_attributes(shortcode_atts(array(
            'amount' => 0,
            'append' => '',
            'assigned_class' => '',
            'do_not_cache' => false,
            'column_count'  => 2,
            'column_gap'  => '10px',
            'custom_title' => null,
            'custom_title_zero' => null,
            'custom_title_plural' => null,
            'div_class' => 'tag-groups-alphabetical-index',
            'div_id' => '',
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
            'exclude' => '',
            'exclude_letters' => '',
            'exclude_terms' => '',
            'h_level' => 3,
            'header_class' => '',
            'hide_empty' => true,
            // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
            // 'hide_empty_content' => false, // doesn't make sense here
            'ignore_accents' => false,
            'include' => '',
            'include_letters' => '',
            'include_terms' => '',
            'keep_together' => 1,
            'largest' => 12,
            'link_append' => '',
            'link_target' => '',
            'locale'  => '',
            'min_max_per_letter' => 0, // option to assign post counts to font sizes for each letter separately
            'order' => 'ASC',
            'orderby' => 'name',
            'prepend' => '',
            'remove_filters' => 1,
            'show_tag_count' => true,
            'smallest' => 12,
            'source' => 'shortcode',
            'tags_div_class' => 'tag-groups-alphabetical-index-tags',
            'tags_post_id' => -1,
            'target_size' => 0,
            'taxonomy' => implode(',', TagGroups_Taxonomy::get_enabled_taxonomies()),
            'threshold' => 0, // minimum number of posts, total (independent of groups)
            ), $atts));
    /**
             * Don't set it as default in extract( shortcode_atts() ) because the block sends an empty string
             */
            if (empty($this->attributes->html_id)) {
                $this->attributes->html_id = 'tag-groups-alphabetical-index-' . uniqid();
            }

            // Hook for the narrow-screen single-column rule; no styles of its own otherwise.
            if (! empty($this->attributes->column_count)) {
                $this->attributes->div_class .= ' tag-groups-columns';
            }
            $this->attributes->div_class = TagGroups_Shortcode_Statics::maybe_add_target_size_class($this->attributes, $this->attributes->div_class);

            $div_id_output = $this->attributes->html_id ? ' id="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->html_id) . '"' : '';
            $div_class_output = $this->attributes->div_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->div_class) . '"' : '';
            $div_column_output = empty($this->attributes->column_count) ? '' : ' style="column-count:' . (int) $this->attributes->column_count . '; column-gap:' . $this->attributes->column_gap . '"' ;
            if (is_array($atts)) {
                asort($atts);
            }

          /**
           * Call this before creating the cache key
           */
            $this->get_post_id();
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
            $this->cache_key = md5('tag_alphabetical_index' . serialize($atts) . serialize($this->attributes->tags_post_id));
    // check for a cached version (premium plugin)
            $html = apply_filters('tag_groups_hook_cache_get', false, $this->cache_key);
            if ($html) {
                $html = $this->finalize_html($html, $div_id_output, $div_class_output, $div_column_output, $atts);
                return $html;
            }

            $this->check_attributes();
            $this->attributes->h_level = TagGroups_Shortcode_Statics::clamp_heading_level($this->attributes->h_level);
            $this->get_taxonomies();
            $this->get_tags();
            $this->make_include_array();
            $this->maybe_add_post_tags_or_groups();
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
            $this->make_headings_HTML();
    /*
      *  render the tab content
      */
            $this->determine_min_max_alphabet();
            $html = $this->add_tags_HTML();
            if (! $this->attributes->do_not_cache) {
              // create a cached version (premium plugin)
                do_action('tag_groups_hook_cache_set', $this->cache_key, $html);
            }

            $html = $this->finalize_html($html, $div_id_output, $div_class_output, $div_column_output, $atts);
            return $html;
        }


      /**
       * Create the HTML headings
       *
       * @return void
       */
        function make_headings_HTML()
        {

            $i = 0;
            foreach ($this->attributes->alphabet as $letter) {
                $header_class_output = $this->attributes->header_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->header_class) . '"' : '';
            /**
                      * Convert to upper case only now; otherwise ß would become SS and affect all cases with S
                      */
                  $this->html_headings[ $i ] = '<h' . $this->attributes->h_level . $header_class_output . '>'
                  . htmlentities(mb_strtoupper($letter), ENT_QUOTES, "UTF-8")
                  . '</h'  . $this->attributes->h_level . '>';
                $i++;
            }
        }


      /**
       * Create the HTML tag part
       *
       * @return void
       */
        function add_tags_HTML()
        {

            $html = '';
            $i = 0;
            $tags_div_class_output = $this->attributes->tags_div_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->tags_div_class) . '"' : '';
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
                    unset($this->html_headings[ $i ]);
                    unset($this->html_tags[ $i ]);
                } elseif (isset($this->html_tags[ $i ])) {
                    if ($this->attributes->keep_together) {
                        $html .= '<div class="tag-groups-keep-together">' . $this->html_headings[ $i ] . '<div ' . $tags_div_class_output . '>' .  $this->html_tags[ $i ] . '</div></div>' . "\n";
                    } else {
                        $html .= $this->html_headings[ $i ] . '<div ' . $tags_div_class_output . '>' .  $this->html_tags[ $i ] . '</div>' . "\n";
                    }
                }

                $i++;
            }

            return $html;
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
        function finalize_html($html, $div_id_output, $div_class_output, $div_column_output, $atts)
        {

            $html = '<div' . $div_id_output . $div_class_output . $div_column_output . '>' . $html . '</div>';
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
