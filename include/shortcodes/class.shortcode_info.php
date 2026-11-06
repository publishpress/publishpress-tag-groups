<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Shortcode_Info')) {
    class TagGroups_Shortcode_Info extends TagGroups_Shortcode_Common
    {
        /**
         *
         * Render information about tag groups
         *
         * For <p> wrapping issue check: https://gist.github.com/bitfade/4555047
         *
         * @param array $atts
         * @return string
         */
        function tag_groups_info($atts = array())
        {
            $this->init();
            global  $tag_group_groups ;
            $this->shortcode_id = 'tag_groups_info';
            if (is_array($atts)) {
                asort($atts);
            }
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
            $cache_key = md5('tag_groups_info' . serialize($atts));
            // check for a cached version (premium plugin)
            $html = apply_filters('tag_groups_hook_cache_get', false, $cache_key);
            if ($html) {
                return $html;
            }
            extract(shortcode_atts(array(
                'do_not_cache' => false,
                'info'         => 'number_of_tags',
                'group_id'     => 'all',
                'html_id'      => '',
                'html_class'   => '',
                'taxonomy'     => null,
                'target'       => '_self',
                'link_pattern' => '{slug}',
            ), $atts));
            
            if (!empty($html_id)) {
                $id_string = ' id="' . esc_attr(sanitize_html_class($html_id)) . '"';
            } else {
                $id_string = '';
            }
            
            
            if (!empty($html_class)) {
                $class_string = ' class="' . esc_attr(TagGroups_Shortcode_Statics::sanitize_html_classes($html_class)) . '"';
            } else {
                $class_string = '';
            }

            $target = TagGroups_Shortcode_Statics::sanitize_link_target($target);
            
            $this->get_taxonomies();
            
            if ('all' == $group_id) {
                $term_groups = $tag_group_groups->get_group_ids_by_position();
                $output = $this->render_table(
                    $id_string,
                    $class_string,
                    $term_groups,
                    $info,
                    $target,
                    $link_pattern
                );
            } elseif (strpos($group_id, ',') !== false) {
                $term_groups = array_map('intval', explode(',', $group_id));
                $output = $this->render_table(
                    $id_string,
                    $class_string,
                    $term_groups,
                    $info,
                    $target,
                    $link_pattern
                );
            } else {
                $output = $this->render_one(
                    $id_string,
                    $class_string,
                    (int) $group_id,
                    $info,
                    $target,
                    $link_pattern
                );
            }
            
            if (!$this->attributes->do_not_cache) {
                // create a cached version (premium plugin)
                do_action('tag_groups_hook_cache_set', $this->cache_key, $html);
            }
            return $output;
        }
        
        /**
         * Renders the information of several groups
         *
         * @param string $id_string
         * @param string $class_string
         * @param array $term_groups
         * @param string $info
         * @param string $target
         * @param string $link_pattern
         * @return string
         */
        function render_table(
            $id_string,
            $class_string,
            $term_groups,
            $info,
            $target,
            $link_pattern
        ) {
            
            $output = '<table' . $id_string . $class_string . '>';
            $output .= $this->render_table_header($info);
            foreach ($term_groups as $term_group) {
                $tg_group = new TagGroups_Group($term_group);
                
                if (!$tg_group->exists()) {
                    TagGroups_Error::verbose_log('[Tag Groups] Unknown group ID in "tag_groups_info": %s', $term_group);
                    continue;
                }
                
                switch ($info) {
                    case 'label':
                        $output .= '<tr>
            <td class="tag-groups-td-label" data-group-id="' . $term_group . '">';
                        $output .= $tg_group->get_label();
                        $output .= '</td>
            </tr>';
                        break;
                    case 'link':
                        if (TagGroups_Utilities::is_premium_plan()) {
                            $path = str_replace('{id}', (int) $term_group, $link_pattern);
                            $slug = sanitize_title($tg_group->get_label());
                            $path = str_replace('{slug}', $slug, $path);

                            $output .= '<tr>
              <td class="tag-groups-td-label" data-group-id="' . $term_group . '">';
                            $output .= '<a' . $id_string . $class_string . ' href="' . esc_url(site_url($path)) . '" target="' . esc_attr($target) . '">';
                            $output .= $tg_group->get_label();
                            $output .= '</a>';
                            $output .= '</td>
              </tr>';
                        }

                        break;
                    case 'number_of_tags':
                    default:
                        $output .= '<tr>
            <td class="tag-groups-td-label" data-group-id="' . $term_group . '">';
                        $output .= $tg_group->get_label();
                        $output .= '</td>
            <td class="tag-groups-td-number">';
                        $output .= (int) $tg_group->get_number_of_terms($this->taxonomies);
                        $output .= '</td>
            </tr>';
                        break;
                }
            }
            $output .= '</table>';
            return $output;
        }
        
        /**
         * Renders a visually hidden caption and column header row for the table
         *
         * The header cells have no padding, border or in-flow content, so the row takes up no space.
         *
         * @param string $info
         * @return string
         */
        function render_table_header($info)
        {
            $columns = array( __('Tag group', 'tag-groups') );
            if (!in_array($info, array( 'label', 'link' ), true)) {
                $columns[] = __('Number of tags', 'tag-groups');
            }

            $output = '<caption class="tag-groups-sr" style="' . TagGroups_Shortcode_Statics::SR_ONLY_STYLE . '">' . esc_html__('Tag groups', 'tag-groups') . '</caption>';
            $output .= '<thead><tr style="height:0;border:0;padding:0;margin:0;">';
            foreach ($columns as $column) {
                $output .= '<th scope="col" style="height:0;padding:0;margin:0;border:0;">' . TagGroups_Shortcode_Statics::sr_only_html(esc_html($column)) . '</th>';
            }
            $output .= '</tr></thead>';
            return $output;
        }

        /**
         * Renders the information of one group
         *
         * @param string $id_string
         * @param string $class_string
         * @param integer $group_id
         * @param string $info
         * @param string $target
         * @param string $link_pattern
         * @return string
         */
        function render_one(
            $id_string,
            $class_string,
            $group_id,
            $info,
            $target,
            $link_pattern
        ) {
            
            $output = '';
            $tg_group = new TagGroups_Group($group_id);
            
            if (!$tg_group->exists()) {
                TagGroups_Error::verbose_log('[Tag Groups] Unknown group ID in "tag_groups_info": %s', $group_id);
                return '';
            }
            
            switch ($info) {
                case 'label':
                    $output .= '<span' . $id_string . $class_string . '>';
                    $output .= $tg_group->get_label();
                    $output .= '</span>';
                    break;
                case 'link':
                    if (TagGroups_Utilities::is_premium_plan()) {
                        $path = str_replace('{id}', (int) $group_id, $link_pattern);
                        $slug = sanitize_title($tg_group->get_label());
                        $path = str_replace('{slug}', $slug, $path);

                        $output .= '<a' . $id_string . $class_string . ' href="' . esc_url(site_url($path)) . '" target="' . esc_attr($target) . '">';
                        $output .= $tg_group->get_label();
                        $output .= '</a>';
                    }

                    break;
                case 'number_of_tags':
                default:
                    $output .= '<span' . $id_string . $class_string . '>';
                    $output .= (int) $tg_group->get_number_of_terms($this->taxonomies);
                    $output .= '</span>';
                    break;
            }
            return $output;
        }
    }
    // class
}
