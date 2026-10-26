<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Shortcode_Tag_List')) {
    class TagGroups_Shortcode_Tag_List extends TagGroups_Shortcode_Common
    {
        /**
         * attributes that we can use in the Gutenberg editor for server-side render
         *
         * @var array
         */
        public static $serverside_render_attributes = array(
            'source'              => array(
            'type'    => 'string',
            'default' => '',
        ),
            'add_premium_filter'  => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'amount'              => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'append'              => array(
            'type'    => 'string',
            'default' => '',
        ),
            'assigned_class'      => array(
            'type'    => 'string',
            'default' => '',
        ),
            'column_count'        => array(
            'type'    => 'integer',
            'default' => 2,
        ),
            'column_gap'          => array(
            'type'    => 'string',
            'default' => '10px',
        ),
            'custom_title'        => array(
            'type'    => 'string',
            'default' => '{description} ({count})',
        ),
            'custom_title_zero'   => array(
            'type'    => 'string',
            'default' => '{description} ({count})',
        ),
            'custom_title_plural' => array(
            'type'    => 'string',
            'default' => '{description} ({count})',
        ),
            'div_class'           => array(
            'type'    => 'string',
            'default' => 'tag-groups-tag-list',
        ),
            'div_id'              => array(
            'type'    => 'string',
            'default' => '',
        ),
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
            'exclude'             => array(
            'type'    => 'string',
            'default' => '',
        ),
            'exclude_terms'       => array(
            'type'    => 'string',
            'default' => '',
        ),
            'groups_post_id'      => array(
            'type'    => 'integer',
            'default' => -1,
        ),
            'h_level'             => array(
            'type'    => 'integer',
            'default' => 3,
        ),
            'header_class'        => array(
            'type'    => 'string',
            'default' => '',
        ),
            'hide_empty_content'  => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'hide_empty'          => array(
            'type'    => 'integer',
            'default' => 1,
        ),
            'include'             => array(
            'type'    => 'string',
            'default' => '',
        ),
            'include_terms'       => array(
            'type'    => 'string',
            'default' => '',
        ),
            'keep_together'       => array(
            'type'    => 'integer',
            'default' => 1,
        ),
            'largest'             => array(
            'type'    => 'integer',
            'default' => 12,
        ),
            'link_append'         => array(
            'type'    => 'string',
            'default' => '',
        ),
            'link_target'         => array(
            'type'    => 'string',
            'default' => '_self',
        ),
            'not_assigned_name'   => array(
            'type'    => 'string',
            'default' => '',
        ),
            'order'               => array(
            'type'    => 'string',
            'default' => 'ASC',
        ),
            'orderby'             => array(
            'type'    => 'string',
            'default' => 'name',
        ),
            'prepend'             => array(
            'type'    => 'string',
            'default' => '',
        ),
            'show_not_assigned'   => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'show_all_groups'     => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'show_tag_count'      => array(
            'type'    => 'integer',
            'default' => 1,
        ),
            'smallest'            => array(
            'type'    => 'integer',
            'default' => 12,
        ),
            'tags_div_class'      => array(
            'type'    => 'string',
            'default' => 'tag-groups-tag-list-tags',
        ),
            'tags_post_id'        => array(
            'type'    => 'integer',
            'default' => -1,
        ),
            'taxonomy'            => array(
            'type'    => 'string',
            'default' => '',
        ),
            'threshold'           => array(
            'type'    => 'integer',
            'default' => 0,
        ),
        ) ;
        /**
         *
         * Render the accordion tag cloud
         *
         * @param array $atts
         * @return string
         */
        function tag_groups_tag_list($atts = array())
        {
            $this->init();
            $this->shortcode_id = 'tag_groups_tag_list';
            $this->set_attributes(shortcode_atts(array(
                'add_premium_filter'  => 0,
                'amount'              => 0,
                'append'              => '',
                'assigned_class'      => null,
                'column_count'        => 2,
                'column_gap'          => '10px',
                'custom_title'        => null,
                'custom_title_zero'   => null,
                'custom_title_plural' => null,
                'div_class'           => 'tag-groups-tag-list',
                'div_id'              => '',
                'do_not_cache'        => false,
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
                'exclude'             => '',
                'exclude_terms'       => '',
                'group_in_class'      => 0,
                'groups_post_id'      => -1,
                'h_level'             => 3,
                'header_class'        => '',
                'hide_empty_content'  => false,
                'hide_empty'          => true,
                'include'             => '',
                'include_terms'       => '',
                'keep_together'       => 1,
                'largest'             => 12,
                'link_append'         => '',
                'link_target'         => '',
                'not_assigned_name'   => 'not assigned',
                'order'               => 'ASC',
                'orderby'             => 'name',
                'prepend'             => '',
                'remove_filters'      => 1,
                'show_not_assigned'   => false,
                'show_all_groups'     => false,
                'show_tag_count'      => true,
                'smallest'            => 12,
                'source'              => 'shortcode',
                'tags_div_class'      => 'tag-groups-tag-list-tags',
                'tags_post_id'        => -1,
                'target_size'         => 0,
                'taxonomy'            => implode(',', TagGroups_Taxonomy::get_enabled_taxonomies()),
                'threshold'           => 0,
            ), $atts));
            /**
             * Don't set it as default in extract( shortcode_atts() ) because the block sends an empty string
             */
            if (empty($this->attributes->html_id)) {
                $this->attributes->html_id = 'tag-groups-tag-list-' . uniqid();
            }
            // Hook for the narrow-screen single-column rule; no styles of its own otherwise.
            if (!empty($this->attributes->column_count)) {
                $this->attributes->div_class .= ' tag-groups-columns';
            }
            $this->attributes->div_class = TagGroups_Shortcode_Statics::maybe_add_target_size_class($this->attributes, $this->attributes->div_class);
            $div_id_output = ( $this->attributes->html_id ? ' id="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->html_id) . '"' : '' );
            $div_class_output = ( $this->attributes->div_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->div_class) . '"' : '' );
            $div_column_output = ( empty($this->attributes->column_count) ? '' : ' style="column-count:' . (int) $this->attributes->column_count . '; column-gap:' . $this->attributes->column_gap . '"' );
            if (is_array($atts)) {
                asort($atts);
            }
            /**
             * Call this before creating the cache key
             */
            $this->get_post_id();
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
            $this->cache_key = md5('tag_list' . serialize($atts) . serialize($this->attributes->tags_post_id) . serialize($this->attributes->groups_post_id));
            // check for a cached version (premium plugin)
            $html = apply_filters('tag_groups_hook_cache_get', false, $this->cache_key);

            if ($html) {
                $html = $this->finalize_html(
                    $html,
                    $div_id_output,
                    $div_class_output,
                    $div_column_output,
                    $atts
                );
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
            $html = $this->make_html();
            $html = $this->finalize_html(
                $html,
                $div_id_output,
                $div_class_output,
                $div_column_output,
                $atts
            );
            return $html;
        }

        /**
         * Creates the HTML
         *
         * @return string
         */
        function make_html()
        {
            global  $tag_group_groups, $tag_group_premium_terms ;
            $html = '';
            $tags_div_class_output = ( $this->attributes->tags_div_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->tags_div_class) . '"' : '' );
            $this->post_counts = array();
            if (TagGroups_Utilities::is_premium_plan()) {
                if (class_exists('TagGroups_Premium_Term') && method_exists($tag_group_premium_terms, 'get_post_counts')) {
                    if (TagGroups_Premium_Meta_Box::metabox_is_activated($this->taxonomies)) {
                        $this->post_counts = $tag_group_premium_terms->maybe_get_post_counts();
                    }
                }
            }

            $this->determine_min_max();
            $first = true;
            for ($i = $this->start_group; $i <= $tag_group_groups->get_max_position(); $i++) {
                if (!isset($this->tag_group_data[$i])) {
                    continue;
                }
                $html_header = '';
                $html_tags = '';
                $count_amount = 0;
                if (!$this->attributes->show_all_groups && !empty($this->include_array) && !in_array($this->tag_group_data[$i]['term_group'], $this->include_array)) {
                    continue;
                }
                /*
                 *  headers
                 */

                if ($i == 0) {
                    $group_name = $this->attributes->not_assigned_name;
                } else {
                    $group_name = $this->tag_group_data[$i]['label'];
                }

                $header_class_group = $this->attributes->header_class;
                if (!empty($this->attributes->group_in_class)) {
                    $header_class_group .= ' ' . sanitize_html_class(' tg_header_group_id_' . $this->tag_group_data[$i]['term_group']) . ' ' . sanitize_html_class('tg_header_group_label_' . strtolower($this->tag_group_data[$i]['label']));
                }

                if ($first) {
                    $header_class_group .= ' tag-groups-first-group';
                    $first = false;
                }

                $header_class_output = ( $header_class_group ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($header_class_group) . '"' : '' );
                $html_header .= '<h' . $this->attributes->h_level . $header_class_output . '>' . htmlentities($group_name, ENT_QUOTES, "UTF-8") . '</h' . $this->attributes->h_level . '>';
                if (TagGroups_Utilities::is_premium_plan()) {
                    if ('count' == $this->final_orderby && !empty($this->post_counts)) {
                        $this->sort_within_groups($this->tag_group_data[$i]['term_group']);
                    }
                }

                /*
                 *  render the content
                 */
                foreach ($this->tags as $tag) {
                    $other_tag_classes = '';
                    if (!empty($this->attributes->amount) && $count_amount >= $this->attributes->amount) {
                        break;
                    }
                    $term_o = new TagGroups_Term($tag);
                    if (!$term_o->has_group($this->tag_group_data[$i]['term_group'])) {
                        continue;
                    }
                    if (!empty($this->include_tags_post_id_groups) && !in_array($tag->term_id, $this->include_tags_post_id_groups[$this->tag_group_data[$i]['term_group']])) {
                        continue;
                    }
                    // check if tag has posts for this particular group

                    if (!empty($this->post_counts) && isset($this->post_counts[$tag->term_id][$this->tag_group_data[$i]['term_group']])) {
                        $post_count = $this->post_counts[$tag->term_id][$this->tag_group_data[$i]['term_group']];
                    } else {
                        $post_count = $tag->count;
                    }


                    if (!$this->attributes->hide_empty || $post_count > 0) {
                        $tag_link = $this->get_tag_link($tag, $i);
                        $font_size = $this->font_size($post_count, $this->min_max[$this->tag_group_data[$i]['term_group']]['min'], $this->min_max[$this->tag_group_data[$i]['term_group']]['max']);
                        $other_tag_classes = $this->get_assigned_tag_class($tag->term_id);
                        $title = $this->get_title($tag, $post_count);
                        $title = $this->maybe_filter_title($title, $tag->description, $post_count);
                        $title_html = ( $title == '' ? '' : ' title="' . esc_attr($title) . '"' );
                        // replace placeholders in prepend and append
                        $prepend_output = $this->get_prepend_output($post_count);
                        $append_output = $this->get_append_output($post_count);
                        // adding link target
                        $link_target = TagGroups_Shortcode_Statics::sanitize_link_target($this->attributes->link_target);
                        $link_target_html = ( !empty($link_target) ? 'target="' . esc_attr($link_target) . '"' : '' );
                        // adding class for group
                        if (!empty($this->attributes->group_in_class)) {
                            $other_tag_classes .= ' ' . sanitize_html_class(' tg_tag_group_id_' . $this->tag_group_data[$i]['term_group']) . ' ' . sanitize_html_class('tg_tag_group_label_' . strtolower($this->tag_group_data[$i]['label']));
                        }
                        // assembling a tag
                        $html_tags .= '<span class="tag-groups-tag' . $other_tag_classes . '" style="font-size:' . $font_size . 'px"><a href="' . $tag_link . '" ' . $link_target_html . '' . $title_html . '  class="' . $tag->slug . '">';

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
                        $html_tags .= apply_filters(
                            'tag_groups_cloud_tag_prepend',
                            $prepend_html,
                            $tag->term_id,
                            $font_size,
                            $post_count,
                            $this->shortcode_id
                        );
                        /**
                         * Hook to filter inner HTML
                         *
                         * @param string $tag->name
                         * @param int $tag->term_id
                         * @param string $this->shortcode_id
                         * @return string
                         */
                        $inner_html = apply_filters(
                            'tag_groups_cloud_tag_inner',
                            $tag->name,
                            $tag->term_id,
                            $this->shortcode_id
                        );
                        /**
                         * Hook to filter outer HTML
                         *
                         * @param string HTML
                         * @param int $tag->term_id
                         * @param string $this->shortcode_id
                         * @return string
                         */
                        $html_tags .= apply_filters(
                            'tag_groups_cloud_tag_outer',
                            '<span class="tag-groups-label" style="font-size:' . $font_size . 'px">' . $inner_html . '</span>',
                            $tag->term_id,
                            $this->shortcode_id
                        );

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
                        $html_tags .= apply_filters(
                            'tag_groups_cloud_tag_append',
                            $append_html,
                            $tag->term_id,
                            $font_size,
                            $post_count,
                            $this->shortcode_id
                        );
                        $html_tags .= TagGroups_Shortcode_Statics::sr_count_html($this->attributes, $post_count);
                        $html_tags .= '</a></span> ';
                        $count_amount++;
                    }
                }
                if (!empty($html_header) && (!$this->attributes->hide_empty_content || $count_amount)) {
                    if ($this->attributes->keep_together) {
                        $html .= '<div class="tag-groups-keep-together">' . $html_header . '<div' . $tags_div_class_output . '>' . $html_tags . '</div></div>' . "\n";
                    } else {
                        $html .= $html_header . '<div' . $tags_div_class_output . '>' . $html_tags . '</div>' . "\n";
                    }
                }
            }
            if (!empty($this->post_counts) && !$this->attributes->do_not_cache) {
                // we don't cache if we used a preliminary post count
                // create a cached version (premium plugin)
                do_action('tag_groups_hook_cache_set', $this->cache_key, $html);
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
        function finalize_html(
            $html,
            $div_id_output,
            $div_class_output,
            $div_column_output,
            $atts
        ) {
            $html = '<div' . $div_id_output . $div_class_output . $div_column_output . '>' . $html . '</div>';
            /**
             * Hook to filter final HTML
             *
             * @param string $html
             * @param string $this->shortcode_id
             * @param array $atts
             * @return string
             */
            $html = apply_filters(
                'tag_groups_cloud_html',
                $html,
                $this->shortcode_id,
                $atts
            );
            return $html;
        }
    }
    // class
}
