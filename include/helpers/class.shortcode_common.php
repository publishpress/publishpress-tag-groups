<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (!class_exists('TagGroups_Shortcode_Common')) {
    #[\AllowDynamicProperties]
    class TagGroups_Shortcode_Common
    {
        public $attributes ;
        public $assigned_terms ;
        public $cache_key ;
        public $count_amount ;
        public $final_order ;
        public $final_orderby ;
        public $include_array ;
        public $include_tags_post_id_groups ;
        public $min_max ;
        public $post_counts ;
        public $post_id ;
        public $shortcode_id ;
        public $start_group ;
        public $tag_group_data ;
        public $tag_group_ids ;
        public $tags ;
        public $taxonomies ;
        public $wpml_language ;
        public $remove_filters ;
        /**
         * loads commonly required data
         *
         * @return object $this
         */
        public function init()
        {
            global  $tag_group_groups ;
            $this->attributes = (object) [];
            $this->include_tags_post_id_groups = array();
            $this->tag_group_data = $tag_group_groups->get_all_with_position_as_key();
            $this->tag_group_ids = $tag_group_groups->get_group_ids_by_position();
            $this->post_counts = array();
            $this->assigned_terms = array();
            $this->remove_filters = true;
            $this->attributes->do_not_cache = false;
            /**
             * In case we use the WPML plugin: consider the language
             */
            $current_language = TagGroups_WPML::get_current_language();
            
            if ($current_language) {
                $this->wpml_language = $current_language;
            } else {
                $this->wpml_language = '';
            }
            
            return $this;
        }
        
        /**
         * Set shortcode attributes as properties
         *
         * @param array $atts
         * @return void
         */
        public function set_attributes($atts)
        {
            foreach ($atts as $key => $value) {
                if ('div_id' == $key || 'table_id' == $key) {
                    $key = 'html_id';
                }
                if (is_null($value)) {
                    $this->attributes->{$key} = $value;
                    continue;
                }
                /**
                 * Many people copy formatted quotes
                 */
                
                if ('”' == mb_substr($value, 0, 1) && '”' == mb_substr($value, -1, 1)) {
                    TagGroups_Error::verbose_log('[Tag Groups] The value %s seems to be enclosed by wrong quotes. We remove them.', $value);
                    $value = mb_substr($value, 1, -1);
                }
                
                $this->attributes->{$key} = $value;
            }
        }
        
        /**
         * Calculates the font size for the cloud tag for a particular tag
         *
         * @param int $count Post count of this tag
         * @param int $min Smallest post count
         * @param int $max Largest post count
         * @return int
         */
        public function font_size($count, $min, $max)
        {
            
            if ($max > $min) {
                $size = round(($count - $min) * ($this->attributes->largest - $this->attributes->smallest) / ($max - $min) + $this->attributes->smallest);
            } else {
                $size = round($this->attributes->smallest);
            }
            
            return $size;
        }
        
        /**
         * Adds the inline JavaScript part for tabs
         *
         * @return string
         */
        public function custom_js_tabs()
        {
            $options = array();
            
            if (isset($this->attributes->mouseover)) {
                if ($this->attributes->mouseover) {
                    $options['event'] = 'mouseover';
                }
            } else {
                if (TagGroups_Options::get_option('tag_group_mouseover', 0)) {
                    $options['event'] = 'mouseover';
                }
            }
            
            
            if (isset($this->attributes->collapsible)) {
                if ($this->attributes->collapsible) {
                    $options['collapsible'] = true;
                }
            } else {
                if (TagGroups_Options::get_option('tag_group_collapsible', 0)) {
                    $options['collapsible'] = true;
                }
            }
            
            if (isset($this->attributes->active)) {
                if ($this->attributes->active >= 0) {
                    $options['active'] = (int) $this->attributes->active;
                } else {
                    $options['active'] = false;
                }
            }
            
            if (empty($this->attributes->html_id)) {
                $this->attributes->html_id = 'tag-groups-cloud-tabs';
            } else {
                $this->attributes->html_id = TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->html_id);
            }
            
            $view = new TagGroups_View('shortcodes/js_tabs_snippet');
            $view->set(array(
                'id'                => $this->attributes->html_id,
                'options_js_object' => wp_json_encode((object) $options),
                'delay'             => $this->attributes->delay,
            ));
            return $view->return_html();
        }
        
        /**
         * Adds the inline JavaScript part for an accordion
         *
         * @return string
         */
        public function custom_js_accordion()
        {
            $options = array();
            
            if (isset($this->attributes->mouseover)) {
                if ($this->attributes->mouseover) {
                    $options['event'] = 'mouseover';
                }
            } else {
                if (TagGroups_Options::get_option('tag_group_mouseover', 0)) {
                    $options['event'] = 'mouseover';
                }
            }
            
            
            if (isset($this->attributes->collapsible)) {
                if ($this->attributes->html_id) {
                    $options['collapsible'] = true;
                }
            } else {
                if (TagGroups_Options::get_option('tag_group_collapsible', 0)) {
                    $options['collapsible'] = true;
                }
            }
            
            if (!empty($this->attributes->heightstyle)) {
                $options['heightStyle'] = sanitize_title($this->attributes->heightstyle);
            }
            if (isset($this->attributes->active)) {
                if ($this->attributes->active >= 0) {
                    $options['active'] = (int) $this->attributes->active;
                } else {
                    $options['active'] = false;
                }
            }
            
            if (!isset($this->attributes->html_id)) {
                $this->attributes->html_id = 'tag-groups-cloud-accordion';
            } else {
                $this->attributes->html_id = TagGroups_Shortcode_Statics::sanitize_html_classes($this->attributes->html_id);
            }
            
            $view = new TagGroups_View('shortcodes/js_accordion_snippet');
            $view->set(array(
                'id'                => $this->attributes->html_id,
                'options_js_object' => wp_json_encode((object) $options),
                'delay'             => $this->attributes->delay,
            ));
            return $view->return_html();
        }
        
        /**
         *  find minimum and maximum of quantity of posts for each tag
         *
         * @param void
         * @return void
         */
        public function determine_min_max()
        {
            $this->min_max = array();
            $count_amount = array();
            foreach ($this->tag_group_ids as $tag_group_id) {
                $count_amount[$tag_group_id] = 0;
                $this->min_max[$tag_group_id]['min'] = 0;
                $this->min_max[$tag_group_id]['max'] = 0;
            }
            if (empty($this->tags) || !is_array($this->tags)) {
                return;
            }
            foreach ($this->tags as $tag) {
                $term_o = new TagGroups_Term($tag);
                if (!$term_o->has_group($this->tag_group_ids)) {
                    continue;
                }
                $post_count_per_group = array();
                $post_count_total = 0;
                // check if tag has posts for a particular group
                
                if (!empty($this->tag_group_data) && !empty($this->post_counts)) {
                    foreach ($this->tag_group_ids as $tag_group_id) {
                        if (isset($this->post_counts[$tag->term_id][$tag_group_id])) {
                            $post_count_per_group[$tag_group_id] = $this->post_counts[$tag->term_id][$tag_group_id];
                            $post_count_total += $this->post_counts[$tag->term_id][$tag_group_id];
                        } else {
                            $post_count_per_group[$tag_group_id] = $tag->count;
                            $post_count_total += $tag->count;
                        }
                    }
                } else {
                    $post_count_total = $tag->count;
                }
                
                
                if ($post_count_total > 0) {
                    /**
                     * Use only groups that are in the list
                     */
                    $term_groups = array_intersect($term_o->get_groups(), $this->tag_group_ids);
                    foreach ($term_groups as $term_group) {
                        if (isset($post_count_per_group[$term_group])) {
                            $tag_count_this_group = $post_count_per_group[$term_group];
                        } else {
                            $tag_count_this_group = $post_count_total;
                        }
                        
                        
                        if ((0 == $this->attributes->amount || $count_amount[$term_group] < $this->attributes->amount) && (empty($this->include_tags_post_id_groups) || in_array($tag->term_id, $this->include_tags_post_id_groups[$term_group]))) {
                            if (isset($this->min_max[$term_group]['max']) && $tag_count_this_group > $this->min_max[$term_group]['max']) {
                                $this->min_max[$term_group]['max'] = $tag_count_this_group;
                            }
                            if (isset($this->min_max[$term_group]['min']) && ($tag_count_this_group < $this->min_max[$term_group]['min'] || 0 == $this->min_max[$term_group]['min'])) {
                                $this->min_max[$term_group]['min'] = $tag_count_this_group;
                            }
                            $count_amount[$term_group]++;
                        }
                    }
                }
            }
        }
        
        /**
         * Helper for natural sorting of names
         *
         * Inspired by _wp_object_name_sort_cb
         *
         * @param void
         * @return void
         */
        public function natural_sorting()
        {
            $factor = ( 'desc' == strtolower($this->final_order) ? -1 : 1 );
            uasort($this->tags, function ($a, $b) use ($factor) {
                return $factor * strnatcasecmp($a->name, $b->name);
            });
            $this->tags = array_values($this->tags);
        }
        
        /**
         * Helper for (pseudo-)random sorting
         *
         * @param void
         * @return void
         */
        public function random_sorting()
        {
            uasort($this->tags, function ($a, $b) {
                // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_mt_rand
                return 2 * mt_rand(0, 1) - 1;
            });
            $this->tags = array_values($this->tags);
        }
        
        /**
         * Sort terms
         *
         * @return void
         */
        public function sort()
        {
            if (count($this->tags) == 0) {
                return $this->tags;
            }
            if ('random' == $this->final_orderby) {
                return $this->random_sorting();
            }
            if ('natural' == $this->final_orderby) {
                return $this->natural_sorting($this->tags, $this->final_order);
            }
            $factor = ( 'desc' == strtolower($this->final_order) ? -1 : 1 );
            /**
             * name
             * count
             * slug
             * term_id
             * description
             * term_order
             */
            switch ($this->final_orderby) {
                case 'name':
                    uasort($this->tags, function ($a, $b) use ($factor) {
                        return $factor * strnatcasecmp($a->name, $b->name);
                    });
                    break;
                case 'count':
                    uasort($this->tags, function ($a, $b) use ($factor) {
                        return $factor * (( $a->count > $b->count ? 1 : -1 ));
                    });
                    break;
                case 'slug':
                    uasort($this->tags, function ($a, $b) use ($factor) {
                        return $factor * strcmp($a->slug, $b->slug);
                    });
                    break;
                case 'term_id':
                    uasort($this->tags, function ($a, $b) use ($factor) {
                        return $factor * (( $a->term_id > $b->term_id ? 1 : -1 ));
                    });
                    break;
                case 'description':
                    uasort($this->tags, function ($a, $b) use ($factor) {
                        return $factor * strcmp($a->description, $b->description);
                    });
                    break;
                case 'term_order':
                    if (!isset($this->tags[0]->term_order)) {
                        TagGroups_Error::log('[Tag Groups] Field term_order not found.');
                    }
                    uasort($this->tags, function ($a, $b) use ($factor) {
                        return $factor * (( $a->term_order > $b->term_order ? 1 : -1 ));
                    });
                    break;
                default:
                    break;
            }
            $this->tags = array_values($this->tags);
        }
        
        /**
         * Checks tags_post_id and groups_post_id and calls $this->add_tags_of_post() if needed
         *
         * @return void
         */
        public function maybe_add_post_tags_or_groups()
        {
            /*
             *  applying parameter tags_post_id
             */
            
            if (property_exists($this->attributes, 'tags_post_id') && $this->attributes->tags_post_id > 0) {
                $this->post_id = $this->attributes->tags_post_id;
                $this->add_tags_of_post();
            }
            
            /*
             *  applying parameter groups_post_id
             */
            
            if (property_exists($this->attributes, 'groups_post_id') && $this->attributes->groups_post_id > 0) {
                $this->post_id = $this->attributes->groups_post_id;
                $this->add_groups_of_post();
            }
        }
        
        /**
         * Adds all IDs of groups that provide tags for a given post
         *
         * @param void
         * @return void
         */
        public function add_groups_of_post()
        {
            if (TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_Post')) {
                $tg_post = new TagGroups_Premium_Post($this->post_id);

                foreach ($this->tag_group_ids as $tag_group_id) {
                    if ($tg_post->has_group($tag_group_id)) {
                        $this->include_array[] = $tag_group_id;
                    }
                }

                return;
            }
            
            $post_id_terms = array();
            /*
             *  get all tags of this post
             */
            foreach ($this->taxonomies as $taxonomy_item) {
                $terms = get_the_terms((int) $this->post_id, $taxonomy_item);
                if (!empty($terms) && is_array($terms)) {
                    $post_id_terms = array_merge($post_id_terms, $terms);
                }
            }
            /*
             *  get all involved groups, append them to $this->attributes->include
             */
            if ($post_id_terms) {
                foreach ($post_id_terms as $term) {
                    $term_o = new TagGroups_Term($term);
                    $groups_of_term = $term_o->get_groups();
                    $this->include_array = array_merge($this->include_array, $groups_of_term);
                }
            }
        }
        
        /**
         * Adds the tags of a particular post to the tags of a tag cloud
         *
         * @param void
         * @return void
         */
        public function add_tags_of_post()
        {
            
            $post_id_terms = array();
            $this->include_tags_post_id_groups = array();
            /*
             *  we have a particular post ID
             *  get all tags of this post
             */
            foreach ($this->taxonomies as $taxonomy_item) {
                $terms = get_the_terms((int) $this->post_id, $taxonomy_item);
                /*
                 *  merging the results of selected taxonomies
                 */
                if (!empty($terms) && is_array($terms)) {
                    $post_id_terms = array_merge($post_id_terms, $terms);
                }
            }
            /*
             *  clean all others from $this->tags
             */
            foreach ($this->tags as $key => $tag) {
                $found = false;
                foreach ($post_id_terms as $id_tag) {
                    if ($tag->term_id == $id_tag->term_id) {
                        $found = true;
                        break;
                    }
                }
                
                if (!empty($this->attributes->assigned_class)) {
                    /*
                     *  Keep all terms but mark for different styling
                     */
                    if ($found) {
                        $this->assigned_terms[$tag->term_id] = true;
                    }
                } else {
                    /*
                     *  Remove unused terms.
                     */
                    if (!$found) {
                        unset($this->tags[$key]);
                    }
                }
            }

            if (TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_Post')) {
                $post_o = new TagGroups_Premium_Post($this->post_id);
                $terms_by_group_tmp = $post_o->get_terms_by_group();

                foreach ($terms_by_group_tmp as $key => $value) {
                    if (!isset($this->include_tags_post_id_groups[$key])) {
                        $this->include_tags_post_id_groups[$key] = array();
                    }

                    $this->include_tags_post_id_groups[$key] = array_merge(
                        $this->include_tags_post_id_groups[$key],
                        $value
                    );
                }
            }
        }
        
        /**
         * Sorts the tags array according to the post count of a particular group
         *
         * @since 1.21.3
         * @param int $group_id
         * @param string $order
         * @return void
         */
        public function sort_within_groups($group_id)
        {
            $shortcode_this = $this;
            uasort($this->tags, function ($a, $b) use (&$shortcode_this, $group_id) {
                if (!isset($shortcode_this->post_counts[$a->term_id][$group_id])) {
                    $shortcode_this->post_counts[$a->term_id][$group_id] = $a->count;
                }
                if (!isset($shortcode_this->post_counts[$b->term_id][$group_id])) {
                    $shortcode_this->post_counts[$b->term_id][$group_id] = $b->count;
                }
                if ($shortcode_this->post_counts[$a->term_id][$group_id] == $shortcode_this->post_counts[$b->term_id][$group_id]) {
                    return 0;
                }
                
                if ('asc' == strtolower($shortcode_this->final_order)) {
                    return ( $shortcode_this->post_counts[$a->term_id][$group_id] > $shortcode_this->post_counts[$b->term_id][$group_id] ? 1 : -1 );
                } else {
                    return ( $shortcode_this->post_counts[$a->term_id][$group_id] > $shortcode_this->post_counts[$b->term_id][$group_id] ? -1 : 1 );
                }
            });
        }
        
        /**
         * Extract the first letter of a name
         *
         * @param string $tag tag name
         * @return string
         */
        public function get_first_letter($tag)
        {
            $first_letter = mb_strtolower(mb_substr($tag, 0, 1));
            if ($this->attributes->ignore_accents) {
                $first_letter = remove_accents($first_letter);
            }
            return $first_letter;
        }
        
        /**
         * Extract the first letters of the tags
         *
         * @return void
         */
        public function extract_alphabet()
        {
            $this->attributes->alphabet = array();
            foreach ($this->tags as $tag) {
                $first_letter = $this->get_first_letter($tag->name);
                if (!in_array($first_letter, $this->attributes->alphabet)) {
                    $this->attributes->alphabet[] = $first_letter;
                }
            }
        }
        
        /**
         * Use provided list from parameters to include and exclude letters
         *
         * @return void
         */
        public function include_exclude_letters()
        {
            /**
             * include
             */
            $this->attributes->include_letters = str_replace(' ', '', $this->attributes->include_letters);
            
            if ($this->attributes->include_letters != '') {
                // don't use empty()
                $include_letters_array = array();
                $this->attributes->include_letters = mb_strtolower($this->attributes->include_letters);
                for ($i = 0; $i < mb_strlen($this->attributes->include_letters); $i++) {
                    $include_letters_array[] = mb_substr($this->attributes->include_letters, $i, 1);
                }
                $this->attributes->alphabet = array_intersect($this->attributes->alphabet, $include_letters_array);
            }
            
            /**
             * exclude
             */
            $this->attributes->exclude_letters = str_replace(' ', '', $this->attributes->exclude_letters);
            
            if ($this->attributes->exclude_letters != '') {
                // don't use empty()
                $exclude_letters_array = array();
                $this->attributes->exclude_letters = mb_strtolower($this->attributes->exclude_letters);
                for ($i = 0; $i < mb_strlen($this->attributes->exclude_letters); $i++) {
                    $exclude_letters_array[] = mb_substr($this->attributes->exclude_letters, $i, 1);
                }
                $this->attributes->alphabet = array_diff($this->attributes->alphabet, $exclude_letters_array);
            }
        }
        
        /**
         * Sorts the alphabet according to the current sort order
         *
         * @param void
         * @return void
         */
        public function sort_alphabet()
        {
            if (empty($this->attributes->locale)) {
                $this->attributes->locale = get_locale();
            }
            
            if (class_exists('Collator')) {
                // Collator is more reliable
                $collator = new Collator($this->attributes->locale);
                
                if (!(array) $collator) {
                    $error_message = intl_get_error_message();
                    if ('U_USING_DEFAULT_WARNING' == $error_message) {
                        $error_message = sprintf('Collator used the default locale data ("%s"); neither the requested locale "%s" nor any of its fall back locales could be found.', $collator->getLocale(Locale::ACTUAL_LOCALE), $this->attributes->locale);
                    }
                    if ('U_USING_FALLBACK_WARNING' == $error_message) {
                        $error_message = sprintf('Collator used the fall back locale "%s" because the requested locale "%s" could not be found.', $collator->getLocale(Locale::ACTUAL_LOCALE), $this->attributes->locale);
                    }
                    TagGroups_Error::verbose_log('[Tag Groups] ' . $error_message);
                }
                
                $collator->sort($this->attributes->alphabet);
            } else {
                if (strpos($this->attributes->locale, ',') !== false) {
                    $this->attributes->locale = array_map('trim', explode(',', $this->attributes->locale));
                }
                $result = @setlocale(LC_COLLATE, $this->attributes->locale);
                
                if (false === $result) {
                    TagGroups_Error::verbose_log('[Tag Groups] Cannot set locale %s', $this->attributes->locale);
                } else {
                    sort($this->attributes->alphabet, SORT_LOCALE_STRING);
                }
            }
        }
        
        /**
         *  find minimum and maximum of quantity of posts for each tag
         * 
         * @return void
         */
        public function determine_min_max_alphabet()
        {
            $this->min_max = array();
            $count_amount = array();
            
            if ($this->attributes->min_max_per_letter) {
                foreach ($this->attributes->alphabet as $letter) {
                    $count_amount[$letter] = 0;
                    $this->min_max[$letter]['min'] = 0;
                    $this->min_max[$letter]['max'] = 0;
                }
                if (empty($this->tags) || !is_array($this->tags)) {
                    return;
                }
                foreach ($this->tags as $tag) {
                    $first_letter = $this->get_first_letter($tag->name, $this->attributes->ignore_accents);
                    if (!in_array($first_letter, $this->attributes->alphabet)) {
                        continue;
                    }
                    if ($this->attributes->amount > 0 && $count_amount[$first_letter] > $this->attributes->amount) {
                        continue;
                    }
                    $post_count_total = $tag->count;
                    
                    if ($post_count_total > 0) {
                        if (isset($this->min_max[$first_letter]['max']) && $post_count_total > $this->min_max[$first_letter]['max']) {
                            $this->min_max[$first_letter]['max'] = $post_count_total;
                        }
                        if (isset($this->min_max[$first_letter]['min']) && ($post_count_total < $this->min_max[$first_letter]['min'] || 0 == $this->min_max[$first_letter]['min'])) {
                            $this->min_max[$first_letter]['min'] = $post_count_total;
                        }
                        $count_amount[$first_letter]++;
                    }
                }
            } else {
                $absolute_min = 0;
                $absolute_max = 0;
                foreach ($this->attributes->alphabet as $letter) {
                    $count_amount[$letter] = 0;
                }
                foreach ($this->tags as $tag) {
                    $first_letter = $this->get_first_letter($tag->name, $this->attributes->ignore_accents);
                    if (!in_array($first_letter, $this->attributes->alphabet)) {
                        continue;
                    }
                    if ($count_amount[$first_letter] > $this->attributes->amount) {
                        continue;
                    }
                    $post_count_total = $tag->count;
                    
                    if ($post_count_total > 0) {
                        if ($post_count_total > $absolute_max) {
                            $absolute_max = $post_count_total;
                        }
                        if ($post_count_total < $absolute_min || 0 == $absolute_min) {
                            $absolute_min = $post_count_total;
                        }
                        $count_amount[$first_letter]++;
                    }
                }
                foreach ($this->attributes->alphabet as $letter) {
                    $this->min_max[$letter]['min'] = $absolute_min;
                    $this->min_max[$letter]['max'] = $absolute_max;
                }
            }
        }
        
        /**
         * get the taxonomies for the term query, based on the shortcode arguments
         *
         *
         * @param void
         * @return void
         */
        public function get_taxonomies()
        {
            $requested_taxonomies = array();
            if (!empty($this->attributes->taxonomy)) {
                $requested_taxonomies = array_map('trim', explode(',', $this->attributes->taxonomy));
            }
            $this->taxonomies = TagGroups_Taxonomy::get_enabled_taxonomies();
            
            if (!empty($requested_taxonomies)) {
                $this->taxonomies = array_intersect($this->taxonomies, $requested_taxonomies);
                
                if (empty($this->taxonomies)) {
                    TagGroups_Error::log('[Tag Groups Pro] Wrong taxonomy or taxonomies (%s) in shortcode %s', implode(',', $requested_taxonomies), $this->shortcode_id);
                    // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                    // return ''; // We are forgiving and let the shortcode work with any taxonomy
                    $this->taxonomies = $requested_taxonomies;
                }
            }
        }
        
        /**
         * Enable sorting by term_order if needed
         *
         * @return void
         */
        public function maybe_enable_terms_order()
        {
            global  $tag_group_terms, $wpdb ;
            
            if ('term_order' == $this->final_orderby) {
                $tables = $wpdb->tables();
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $columns = $wpdb->get_col("DESC {$tables['terms']}", 0);
                
                if (!in_array('term_order', $columns)) {
                    TagGroups_Error::log('[Tag Groups] If you use orderby=term_order, your database needs to have the term_order column.');
                    return;
                }
                
                add_filter(
                    'get_terms_orderby',
                    array( $tag_group_terms, 'enable_terms_order' ),
                    10,
                    2
                );
            }
        }
        
        /**
         * Try to get the ID of the current post
         *
         * @return void
         */
        public function get_post_id()
        {
            
            if (property_exists($this->attributes, 'tags_post_id') && 0 == $this->attributes->tags_post_id) {
                $post_id = get_the_ID();
                
                if (false !== $post_id) {
                    $this->attributes->tags_post_id = $post_id;
                } else {
                    TagGroups_Error::verbose_log('[Tag Groups Pro] Cannot get the post ID');
                    $this->attributes->do_not_cache = true;
                }
            }
            
            
            if (property_exists($this->attributes, 'groups_post_id') && 0 == $this->attributes->groups_post_id) {
                $post_id = get_the_ID();
                
                if (false !== $post_id) {
                    $this->attributes->groups_post_id = $post_id;
                } else {
                    TagGroups_Error::verbose_log('[Tag Groups Pro] Cannot get the post ID');
                    $this->attributes->do_not_cache = true;
                }
            }
        }
        
        /**
         * Performes some checks on the supplied attributes
         *
         * @return void
         */
        public function check_attributes()
        {
            
            if ('shortcode' == $this->attributes->source) {
                $this->attributes->prepend = html_entity_decode($this->attributes->prepend);
                $this->attributes->append = html_entity_decode($this->attributes->append);
                if (property_exists($this->attributes, 'separator')) {
                    $this->attributes->separator = html_entity_decode($this->attributes->separator);
                }
            }
            
            if (property_exists($this->attributes, 'separator')) {
                $this->attributes->separator = esc_html($this->attributes->separator);
            }
            
            
            if (property_exists($this->attributes, 'threshold') && $this->attributes->threshold) {
                $this->final_orderby = $this->attributes->orderby;
                $this->final_order = $this->attributes->order;
                $this->attributes->orderby = 'count';
                $this->attributes->order = 'DESC';
                add_filter(
                    'terms_clauses',
                    array( 'TagGroups_Shortcode_Statics', 'terms_clauses_threshold' ),
                    10,
                    3
                );
            } else {
                $this->final_orderby = $this->attributes->orderby;
                $this->final_order = $this->attributes->order;
            }
            
            if (property_exists($this->attributes, 'smallest') && $this->attributes->smallest < 1) {
                $this->attributes->smallest = 1;
            }
            if (property_exists($this->attributes, 'largest') && $this->attributes->largest < $this->attributes->smallest) {
                $this->attributes->largest = $this->attributes->smallest;
            }
            if (property_exists($this->attributes, 'amount') && $this->attributes->amount < 0) {
                $this->attributes->amount = 0;
            }
            
            if (property_exists($this->attributes, 'show_not_assigned') && !empty($this->attributes->show_not_assigned)) {
                $this->start_group = 0;
            } else {
                $this->start_group = 1;
            }
            
            if (property_exists($this->attributes, 'link_append') && !empty($this->attributes->link_append) && mb_strpos($this->attributes->link_append, '?') === 0) {
                $this->attributes->link_append = mb_substr($this->attributes->link_append, 1);
            }
            if (property_exists($this->attributes, 'separator_size') && !empty($this->attributes->separator_size)) {
                if ($this->attributes->separator_size < 1) {
                    $this->attributes->separator_size = 12;
                } else {
                    $this->attributes->separator_size = (int) $this->attributes->separator_size;
                }
            }
            if (property_exists($this->attributes, 'tags_post_id')) {
                if ($this->attributes->tags_post_id < -1) {
                    $this->attributes->tags_post_id = -1;
                } elseif ($this->attributes->tags_post_id > -1) {
                    $this->attributes->include_terms = '';
                }
            }
            if (property_exists($this->attributes, 'groups_post_id')) {
                if ($this->attributes->groups_post_id < -1) {
                    $this->attributes->groups_post_id = -1;
                } elseif ($this->attributes->groups_post_id > -1) {
                    $this->attributes->include = '';
                }
            }
            if (property_exists($this->attributes, 'h_level')) {
                $this->attributes->h_level = (int) $this->attributes->h_level;
            }
        }
        
        /**
         * Get the tags
         *
         * @return void
         */
        public function get_tags()
        {
            $tag_groups_hooks = new TagGroups_Hooks();
            /**
             * Reduce the risk of interference from other plugins
             */
            
            if ($this->remove_filters) {
                $tag_groups_hooks->remove_all_filters(array( 'get_terms_orderby', 'get_terms', 'list_terms_exclusions' ));
                // keep terms_clauses for WPML
            }
            
            /**
             * term_order requires special treatment
             */
            $this->maybe_enable_terms_order();
            $term_query = new WP_Term_Query(array(
                'taxonomy'   => $this->taxonomies,
                'hide_empty' => $this->attributes->hide_empty,
                'orderby'    => $this->attributes->orderby,
                'order'      => $this->attributes->order,
                'include'    => $this->attributes->include_terms,
                // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
                'exclude'    => $this->attributes->exclude_terms,
                'threshold'  => $this->attributes->threshold,
            ));
            $this->tags = ( empty($term_query->terms) ? array() : $term_query->terms );
            /**
             * Filters the terms of a group before it's returned or saved to the transient
             * 
             * @param WP_Term[]|int[]|string[]|string|WP_Error $terms Return type depends on $fields
             * @param int group ID
             * @param string|string[] $taxonomies
             * @param bool|int $hide_empty Whether to hide terms with post count zero
             * @param string $fields What to return. See WP's get_terms()
             * @param int $post_id This parameter is only relevant if the tags depend on the language of a post
             * @param string $orderby
             * @param string $order
             * @param string $include
             * @param string $exclude
             * @param int $threshold
             * 
             * @return mixed $terms Must be the same type as the input $terms
             */
            $this->tags = apply_filters(
                'tag_groups_get_terms',
                $this->tags,
                null,
                $this->taxonomies,
                $this->attributes->hide_empty,
                '',
                $this->post_id,
                $this->attributes->orderby,
                $this->attributes->order,
                $this->attributes->include_terms,
                $this->attributes->exclude_terms,
                $this->attributes->threshold
            );
            $tag_groups_hooks->restore_hooks();
            /**
             * In case of errors: return empty array
             */
            
            if (!is_array($this->tags)) {
                $this->tags = array();
                TagGroups_Error::log('[Tag Groups] Error retrieving tags with WP_Term_Query.');
            }
        }
        
        /**
         * Use the parameters of include and exclude to create an array of groups
         *
         * @param void
         * @return void
         */
        public function make_include_array()
        {
            global  $tag_group_groups ;
            
            if (property_exists($this->attributes, 'include') && $this->attributes->include !== '') {
                $this->include_array = array_map('intval', explode(',', $this->attributes->include));
                $this->include_array = $tag_group_groups->expand_parents($this->include_array);
            } elseif (!property_exists($this->attributes, 'groups_post_id') || $this->attributes->groups_post_id < 0) {
                $this->include_array = $this->tag_group_ids;
            } else {
                $this->include_array = array();
            }
            
            
            if (property_exists($this->attributes, 'exclude') && $this->attributes->exclude !== '') {
                $exclude_array = array_map('intval', explode(',', $this->attributes->exclude));
                $exclude_array = $tag_group_groups->expand_parents($exclude_array);
                $this->include_array = array_diff($this->include_array, $exclude_array);
            }
        }
        
        /**
         * Process the prepend placeholder
         *
         * @param int $post_count
         * @return string
         */
        public function get_prepend_output($post_count)
        {
            
            if (!empty($this->attributes->prepend)) {
                return preg_replace("/(\\{count\\})/", $post_count, $this->attributes->prepend);
            } else {
                return '';
            }
        }
        
        /**
         * Process the append placeholder
         *
         * @param int $post_count
         * @return string
         */
        public function get_append_output($post_count)
        {
            
            if (!empty($this->attributes->append)) {
                return preg_replace("/(\\{count\\})/", $post_count, $this->attributes->append);
            } else {
                return '';
            }
        }
        
        /**
         * Process the title placeholders
         *
         * @param object $tag
         * @param int $post_count
         * @return string
         */
        public function get_title($tag, $post_count)
        {
            /**
             * Don't test for empty() because the user might need to display an empty title
             */
            if (
                ( property_exists($this->attributes, 'custom_title') && ! is_null($this->attributes->custom_title) ) ||
                ( property_exists($this->attributes, 'custom_title_zero') && ! is_null($this->attributes->custom_title_zero) ) ||
                ( property_exists($this->attributes, 'custom_title_plural') && ! is_null($this->attributes->custom_title_plural) )
            ) {
                if (0 == $post_count && property_exists($this->attributes, 'custom_title_zero') && !is_null($this->attributes->custom_title_zero)) {
                    $title = $this->attributes->custom_title_zero;
                } elseif ($post_count > 1 && property_exists($this->attributes, 'custom_title_plural') && !is_null($this->attributes->custom_title_plural)) {
                    $title = $this->attributes->custom_title_plural;
                } else {
                    $title = $this->attributes->custom_title;
                }
                
                /**
                 * Filters the title attribute of a tag before replacing all placeholders
                 * 
                 * @param string $title
                 * @param object $tag WP Term Object
                 * @param int $post_count
                 * 
                 * @return string
                 */
                $title = (string) $title;
                $title = apply_filters(
                    'tag_groups_custom_title',
                    $title,
                    $tag,
                    $post_count
                );
                // use the provided template
                $description = ( !empty($tag->description) ? esc_html($tag->description) : '' );
                $title = preg_replace("/(\\{description\\})/", $description, $title);
                $name = ( !empty($tag->name) ? esc_html($tag->name) : '' );
                $title = preg_replace("/(\\{name\\})/", $name, $title);
                $title = preg_replace("/(\\{count\\})/", $post_count, $title);
                if (trim($title) === '') {
                    $tag_count_brackets = $this->attributes->show_tag_count ? '(' . $post_count . ')' : '';
                    return $description . $tag_count_brackets;
                }
          
                  return $title;
            } else {
                // use just description and number
                $description = ( !empty($tag->description) ? esc_html($tag->description) . ' ' : '' );
                $tag_count_brackets = ( $this->attributes->show_tag_count ? '(' . $post_count . ')' : '' );
                return $description . $tag_count_brackets;
            }
        }
        
        /**
         * create the link for a tag
         *
         * @param object $tag
         * @return string
         */
        public function get_tag_link($tag, $i = null)
        {
            $tag_link = get_term_link($tag);
            if (!empty($this->attributes->link_append)) {
                if (mb_strpos($tag_link, '?') === false) {
                    $tag_link = esc_url($tag_link . '?' . $this->attributes->link_append);
                } else {
                    $tag_link = esc_url($tag_link . '&' . $this->attributes->link_append);
                }
            }
            /**
             * Append a parameter to separate terms by group on the archive page
             */
            if (!is_null($i) && class_exists('TagGroups_Premium_Term') && property_exists($this->attributes, 'add_premium_filter') && $this->attributes->add_premium_filter && isset($this->tag_group_data[$i]['term_group'])) {
                if (mb_strpos($tag_link, '?') === false) {
                    $tag_link = esc_url($tag_link . '?term_group=' . $this->tag_group_data[$i]['term_group'] . '&term_id=' . $tag->term_id);
                } else {
                    $tag_link = esc_url($tag_link . '&term_group=' . $this->tag_group_data[$i]['term_group'] . '&term_id=' . $tag->term_id);
                }
            }
            return $tag_link;
        }
        
        /**
         * Depending on the settings, keeps or filters the tag description for output in the 'title' attribute
         * 
         *
         * @param string $title
         * @param string $description
         * @param integer $post_count
         * @return string
         */
        public function maybe_filter_title($title, $description, $post_count)
        {
            $tag_group_html_description = TagGroups_Options::get_option('tag_group_html_description', 0);
            switch ($tag_group_html_description) {
                case 0:
                default:
                    /**
                     * Convert double quotes to smart/curly quotes and replace any left over so that the HTML won't break
                     */
                    $title = str_replace('"', "”", wptexturize(wp_strip_all_tags(html_entity_decode($title))));
                    break;
                case 1:
                    /**
                     * only apply the filter below
                     */
                    break;
                case 2:
                    if (!current_user_can('unfiltered_html')) {
                        /**
                         * Convert double quotes to smart/curly quotes and replace any left over so that the HTML won't break
                         */
                        $title = str_replace('"', "”", wptexturize(wp_strip_all_tags(html_entity_decode($title))));
                    }
                    break;
            }
            /**
             * Filter hook to modify the HTML title attribute of tags
             * 
             * @param string $title
             * @param string $this->shortcode_id The name of the shortcode
             * @param string $tag->description The description of the tag, unescaped.
             * @param integer $post_count The number of posts using this tag.
             */
            return apply_filters(
                'tag_groups_tag_title',
                $title,
                $this->shortcode_id,
                $description,
                $post_count
            );
        }

        /**
         * Returns the sanitized assigned-class marker for a tag.
         *
         * @param integer $term_id 
         * @return string
         */
        protected function get_assigned_tag_class($term_id)
        {
            if (empty($this->attributes->assigned_class)) {
                return '';
            }

            $assigned_class = sanitize_html_class($this->attributes->assigned_class);

            if (empty($assigned_class)) {
                return '';
            }

            if (!empty($this->assigned_terms[$term_id])) {
                return ' ' . sanitize_html_class($assigned_class . '_1');
            }

            return ' ' . sanitize_html_class($assigned_class . '_0');
        }
    }
    // class
}
