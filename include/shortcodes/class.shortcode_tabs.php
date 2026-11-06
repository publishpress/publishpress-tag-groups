<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Shortcode_Tabs')) {
    class TagGroups_Shortcode_Tabs extends TagGroups_Shortcode_Common
    {
        /**
         * attributes that we can use in the Gutenberg editor for server-side render
         *
         * @var array
         */
        public static $serverside_render_attributes = array(
            'source'                => array(
            'type'    => 'string',
            'default' => '',
        ),
            'active'                => array(
            'type'    => 'integer',
            'default' => -1,
        ),
            'adjust_separator_size' => array(
            'type'    => 'integer',
            'default' => 1,
        ),
            'add_premium_filter'    => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'amount'                => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'append'                => array(
            'type'    => 'string',
            'default' => '',
        ),
            'assigned_class'        => array(
            'type'    => 'string',
            'default' => '',
        ),
            'collapsible'           => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'custom_title'          => array(
            'type'    => 'string',
            'default' => '{description} ({count})',
        ),
            'custom_title_zero'     => array(
            'type'    => 'string',
            'default' => '{description} ({count})',
        ),
            'custom_title_plural'   => array(
            'type'    => 'string',
            'default' => '{description} ({count})',
        ),
            'delay'                 => array(
            'type'    => 'integer',
            'default' => 1,
        ),
            'div_class'             => array(
            'type'    => 'string',
            'default' => 'tag-groups-cloud',
        ),
            'div_id'                => array(
            'type'    => 'string',
            'default' => '',
        ),
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
            'exclude'               => array(
            'type'    => 'string',
            'default' => '',
        ),
            'exclude_terms'         => array(
            'type'    => 'string',
            'default' => '',
        ),
            'groups_post_id'        => array(
            'type'    => 'integer',
            'default' => -1,
        ),
            'hide_empty'            => array(
            'type'    => 'integer',
            'default' => 1,
        ),
            'hide_empty_tabs'       => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'include'               => array(
            'type'    => 'string',
            'default' => '',
        ),
            'include_terms'         => array(
            'type'    => 'string',
            'default' => '',
        ),
            'largest'               => array(
            'type'    => 'integer',
            'default' => 22,
        ),
            'link_append'           => array(
            'type'    => 'string',
            'default' => '',
        ),
            'link_target'           => array(
            'type'    => 'string',
            'default' => '_self',
        ),
            'mouseover'             => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'not_assigned_name'     => array(
            'type'    => 'string',
            'default' => 'not assigned',
        ),
            'order'                 => array(
            'type'    => 'string',
            'default' => 'ASC',
        ),
            'orderby'               => array(
            'type'    => 'string',
            'default' => 'name',
        ),
            'prepend'               => array(
            'type'    => 'string',
            'default' => '',
        ),
            'separator_size'        => array(
            'type'    => 'integer',
            'default' => 22,
        ),
            'separator'             => array(
            'type'    => 'string',
            'default' => '',
        ),
            'show_not_assigned'     => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'show_all_groups'       => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'show_tabs'             => array(
            'type'    => 'integer',
            'default' => 1,
        ),
            'show_tag_count'        => array(
            'type'    => 'integer',
            'default' => 1,
        ),
            'smallest'              => array(
            'type'    => 'integer',
            'default' => 12,
        ),
            'tag_style'             => array(
            'type'    => 'string',
            'default' => '',
        ),
            'tags_post_id'          => array(
            'type'    => 'integer',
            'default' => -1,
        ),
            'taxonomy'              => array(
            'type'    => 'string',
            'default' => '',
        ),
            'threshold'             => array(
            'type'    => 'integer',
            'default' => 0,
        ),
            'ul_class'              => array(
            'type'    => 'string',
            'default' => '',
        ),
        ) ;
        public $html_tags ;
        public $html_tabs ;
        /**
         *
         * Render the tabbed tag cloud, usually by a shortcode, or returning a multidimensional array
         *
         * @param array $atts
         * @param bool $return_array
         * @return string
         */
        public function tag_groups_cloud($atts = array(), $return_array = false)
        {
            $this->init();
            $this->shortcode_id = 'tag_groups_cloud';
            $this->set_attributes(shortcode_atts(array(
                'active'                => -1,
                'adjust_separator_size' => true,
                'add_premium_filter'    => 0,
                'amount'                => 0,
                'append'                => '',
                'assigned_class'        => null,
                'collapsible'           => null,
                'custom_title'          => null,
                'custom_title_zero'     => null,
                'custom_title_plural'   => null,
                'delay'                 => true,
                'div_class'             => 'tag-groups-cloud',
                'div_id'                => '',
                'do_not_cache'          => false,
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
                'exclude'               => '',
                'exclude_terms'         => '',
                'group_in_class'        => 0,
                'groups_post_id'        => -1,
                'hide_empty_tabs'       => false,
                'hide_empty'            => true,
                'include'               => '',
                'include_terms'         => '',
                'largest'               => 22,
                'link_append'           => '',
                'link_target'           => '',
                'mouseover'             => null,
                'not_assigned_name'     => 'not assigned',
                'order'                 => 'ASC',
                'orderby'               => 'name',
                'prepend'               => '',
                'remove_filters'        => 1,
                'separator_size'        => 12,
                'separator'             => '',
                'show_not_assigned'     => false,
                'show_all_groups'       => false,
                'show_tabs'             => '1',
                'show_tag_count'        => true,
                'smallest'              => 12,
                'source'                => 'shortcode',
                'tag_style'             => '',
                'tags_post_id'          => -1,
                'taxonomy'              => implode(',', TagGroups_Taxonomy::get_enabled_taxonomies()),
                'threshold'             => 0,
                'ul_class'              => '',
            ), $atts));
            /**
             * Don't set it as default in extract( shortcode_atts() ) because the block sends an empty string
             */
            if (empty($this->attributes->html_id)) {
                $this->attributes->html_id = 'tag-groups-cloud-tabs-' . uniqid();
            }
            /**
             * Keep always jQuery UI class to produce correct output
             */
            if (!in_array('tag-groups-cloud', array_map('trim', explode(' ', $this->attributes->div_class)))) {
                $this->attributes->div_class .= ' tag-groups-cloud';
            }
            if ($this->attributes->delay) {
                $this->attributes->div_class .= ' tag-groups-cloud-hidden';
            }
            $this->add_tag_style_class();
            $div_id_output = ( $this->attributes->html_id ? ' id="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->html_id) . '"' : '' );
            $div_class_output = ( $this->attributes->div_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->div_class) . '"' : '' );
            if (is_array($atts)) {
                asort($atts);
            }
            /**
             * Call this before creating the cache key
             */
            $this->get_post_id();
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize, WordPress.PHP.DevelopmentFunctions.error_log_var_export
            $this->cache_key = md5('tabs' . serialize($atts) . var_export($return_array, true) . serialize($this->attributes->tags_post_id) . serialize($this->attributes->groups_post_id));
            // check for a cached version (premium plugin)
            $html = apply_filters('tag_groups_hook_cache_get', false, $this->cache_key);

            if ($html) {
                if (!$return_array) {
                    $html = $this->finalize_html(
                        $html,
                        $div_id_output,
                        $div_class_output,
                        $atts
                    );
                }
                return $html;
            }

            $this->check_attributes();
            $this->get_taxonomies();
            $this->get_tags();
            $this->make_include_array();
            $this->maybe_add_post_tags_or_groups();
            $ul_class_output = ( $this->attributes->ul_class ? ' class="' . TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->ul_class) . '"' : '' );
            // apply sorting that cannot be done on database level
            if ('natural' == $this->attributes->orderby || 'random' == $this->attributes->orderby || $this->attributes->threshold) {
                $this->sort();
            }
            if ($return_array) {
                return $this->construct_array();
            }
            $html = '';
            $this->html_tabs = array();
            $this->html_tags = array();
            /*
             *  render the tabs
             */
            if ($this->attributes->show_tabs) {
                $this->make_tabs_HTML();
            }
            /*
             *  render the tab content
             */
            $this->make_tags_HTML();
            if (!empty($this->html_tabs)) {
                $html .= '<ul' . $ul_class_output . '>' . implode("\n", $this->html_tabs) . '</ul>';
            }
            $html .= implode("\n", $this->html_tags);
            if (!empty($this->post_counts) && !$this->attributes->do_not_cache) {
                // we don't cache if we used a preliminary post count
                // create a cached version (premium plugin)
                do_action('tag_groups_hook_cache_set', $this->cache_key, $html);
            }
            $html = $this->finalize_html(
                $html,
                $div_id_output,
                $div_class_output,
                $atts
            );
            return $html;
        }

        /**
         * Compile the array of groups and tags
         *
         * @return void
         */
        function construct_array()
        {
            global $tag_group_premium_terms, $tag_group_groups ;
            /*
             *  return tags as array
             */
            $output = array();
            $this->post_counts = array();
            if (TagGroups_Utilities::is_premium_plan()) {
                if (class_exists('TagGroups_Premium_Term') && method_exists($tag_group_premium_terms, 'get_post_counts')) {
                    if (TagGroups_Premium_Meta_Box::metabox_is_activated($this->taxonomies)) {
                        $this->post_counts = $tag_group_premium_terms->maybe_get_post_counts();
                    }
                }
            }

            $this->determine_min_max();
            for ($i = $this->start_group; $i <= $tag_group_groups->get_max_position(); $i++) {
                if (!isset($this->tag_group_data[$i])) {
                    continue;
                }
                if (!($this->attributes->show_all_groups || in_array($this->tag_group_data[$i]['term_group'], $this->include_array))) {
                    continue;
                }

                if ($i == 0) {
                    $output[$i]['name'] = $this->attributes->not_assigned_name;
                } else {
                    $output[$i]['name'] = $this->tag_group_data[$i]['label'];
                }

                $output[$i]['term_group'] = $this->tag_group_data[$i]['term_group'];
                $count_amount = 0;
                if (TagGroups_Utilities::is_premium_plan()) {
                    if ('count' == $this->final_orderby && !empty($this->post_counts)) {
                        $this->sort_within_groups($this->tag_group_data[$i]['term_group']);
                    }
                }

                $output[$i]['tags'] = array();
                foreach ($this->tags as $tag) {
                    if (!empty($this->attributes->amount) && $count_amount >= $this->attributes->amount) {
                        break;
                    }
                    $term_o = new TagGroups_Term($tag);
                    if (!$term_o->has_group($this->tag_group_data[$i]['term_group'])) {
                        continue;
                    }

                    if (empty($this->include_tags_post_id_groups) || in_array($tag->term_id, $this->include_tags_post_id_groups[$this->tag_group_data[$i]['term_group']])) {
                        // check if tag has posts for this particular group

                        if (!empty($this->post_counts) && isset($this->post_counts[$tag->term_id][$this->tag_group_data[$i]['term_group']])) {
                            $post_count = $this->post_counts[$tag->term_id][$this->tag_group_data[$i]['term_group']];
                        } else {
                            $post_count = $tag->count;
                        }


                        if (!$this->attributes->hide_empty || $post_count > 0) {
                            $output[$i]['tags'][$count_amount] = array();
                            $output[$i]['tags'][$count_amount]['term_id'] = $tag->term_id;
                            $output[$i]['tags'][$count_amount]['link'] = get_term_link($tag);
                            $output[$i]['tags'][$count_amount]['description'] = $tag->description;
                            $output[$i]['tags'][$count_amount]['count'] = $post_count;
                            $output[$i]['tags'][$count_amount]['slug'] = $tag->slug;
                            $output[$i]['tags'][$count_amount]['name'] = $tag->name;
                            $output[$i]['tags'][$count_amount]['tg_font_size'] = $this->font_size(
                                $post_count,
                                $this->min_max[$this->tag_group_data[$i]['term_group']]['min'],
                                $this->min_max[$this->tag_group_data[$i]['term_group']]['max'],
                                $this->attributes->smallest,
                                $this->attributes->largest
                            );
                            if (!empty($this->attributes->assigned_class)) {
                                $output[$i]['tags'][$count_amount]['assigned'] = $this->assigned_terms[$tag->term_id];
                            }
                            $count_amount++;
                        }
                    }
                }
                $output[$i]['amount'] = $count_amount;
            }
            if (!empty($this->post_counts) && !$this->attributes->do_not_cache) {
                // we don't cache if we used a preliminary post count
                // create a cached version (premium plugin)
                do_action('tag_groups_hook_cache_set', $this->cache_key, $output);
            }
            return $output;
        }

        /**
         * Compile the HTML of groups and tags
         *
         * @return array
         */
        private function make_tabs_HTML()
        {
            global  $tag_group_groups ;
            for ($i = $this->start_group; $i <= $tag_group_groups->get_max_position(); $i++) {
                if (!isset($this->tag_group_data[$i])) {
                    continue;
                }

                if ($this->attributes->show_all_groups || in_array($this->tag_group_data[$i]['term_group'], $this->include_array)) {
                    if ($i == 0) {
                        $group_name = $this->attributes->not_assigned_name;
                    } else {
                        $group_name = $this->tag_group_data[$i]['label'];
                    }

                    if (!empty($this->attributes->group_in_class)) {
                        $header_class_group = sanitize_html_class(' tg_header_group_id_' . $this->tag_group_data[$i]['term_group']) . ' ' . sanitize_html_class('tg_header_group_label_' . strtolower($this->tag_group_data[$i]['label']));
                    }
                    $header_class_output = ( !empty($header_class_group) ? ' class="' . $header_class_group . '"' : '' );
                    $this->html_tabs[$i] = '<li data-group="' . $this->tag_group_data[$i]['term_group'] . '#"><a href="#tabs-1' . $i . '" ' . $header_class_output . '>' . htmlentities($group_name, ENT_QUOTES, "UTF-8") . '</a></li>';
                }
            }
        }

        /**
         * Create the HTML of the tag part
         *
         * @return array
         */
        private function make_tags_HTML()
        {
            global $tag_group_premium_terms, $tag_group_groups ;
            if (TagGroups_Utilities::is_premium_plan()) {
                if (class_exists('TagGroups_Premium_Term') && method_exists($tag_group_premium_terms, 'get_post_counts')) {
                    if (TagGroups_Premium_Meta_Box::metabox_is_activated($this->taxonomies)) {
                        $this->post_counts = $tag_group_premium_terms->maybe_get_post_counts();
                    }
                }
            }

            $this->determine_min_max();
            for ($i = $this->start_group; $i <= $tag_group_groups->get_max_position(); $i++) {
                if (!isset($this->tag_group_data[$i])) {
                    continue;
                }
                if (!$this->attributes->show_all_groups && !empty($this->include_array) && !in_array($this->tag_group_data[$i]['term_group'], $this->include_array)) {
                    continue;
                }
                $count_amount = 0;
                $this->html_tags[$i] = '';
                if (TagGroups_Utilities::is_premium_plan()) {
                    if ('count' == $this->final_orderby && !empty($this->post_counts)) {
                        $this->sort_within_groups($this->tag_group_data[$i]['term_group']);
                    }
                }

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

                    if (!empty($this->post_counts) && !empty($this->post_counts[$tag->term_id][$this->tag_group_data[$i]['term_group']])) {
                        $post_count = $this->post_counts[$tag->term_id][$this->tag_group_data[$i]['term_group']];
                    } else {
                        $post_count = $tag->count;
                    }

                    if ($this->attributes->hide_empty && 0 == $post_count) {
                        continue;
                    }
                    $tag_link = $this->get_tag_link($tag, $i);
                    $font_size = $this->font_size($post_count, $this->min_max[$this->tag_group_data[$i]['term_group']]['min'], $this->min_max[$this->tag_group_data[$i]['term_group']]['max']);
                    $font_size_separator = ( $this->attributes->adjust_separator_size ? $font_size : $this->attributes->separator_size );
                    if ($count_amount > 0 && !empty($this->attributes->separator)) {
                        $this->html_tags[$i] .= '<span style="font-size:' . $font_size_separator . 'px">' . $this->attributes->separator . '</span> ';
                    }
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
                    $this->html_tags[$i] .= '<span class="tag-groups-tag' . $other_tag_classes . '" style="font-size:' . $font_size . 'px" data-group="' . $this->tag_group_data[$i]['term_group'] . '#"><a href="' . $tag_link . '" ' . $link_target_html . '' . $title_html . '  class="' . $tag->slug . '">';

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
                    $this->html_tags[$i] .= apply_filters(
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
                    $this->html_tags[$i] .= apply_filters(
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
                    $this->html_tags[$i] .= apply_filters(
                        'tag_groups_cloud_tag_append',
                        $append_html,
                        $tag->term_id,
                        $font_size,
                        $post_count,
                        $this->shortcode_id
                    );
                    $this->html_tags[$i] .= '</a></span> ';
                    $count_amount++;
                }

                if ($this->attributes->hide_empty_tabs && !$count_amount) {
                    unset($this->html_tabs[$i]);
                    unset($this->html_tags[$i]);
                } elseif (isset($this->html_tags[$i])) {
                    $this->html_tags[$i] = '<div id="tabs-1' . $i . '">' . $this->html_tags[$i] . '</div>';
                }
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
        function finalize_html(
            $html,
            $div_id_output,
            $div_class_output,
            $atts
        ) {
            $html = '<div' . $div_id_output . $div_class_output . '>' . $html . '</div>';
            // entire wrapper
            $html .= $this->custom_js_tabs();
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
