<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (!class_exists('TagGroups_Settings_Ajax')) {
    /**
     *
     */
    class TagGroups_Settings_Ajax
    {
        /**
         * Runs selected routines for benchmarking
         *
         * @param void
         * @return string HTML
         * @since 1.23.0
         */
        public static function ajax_benchmark()
        {
            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- AJAX handler
            if (!isset($_POST['task'])) {
                return;
            }
            
            $benchmark = array();
            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- AJAX handler
            switch ($_POST['task']) {
                case "cache":
                    if (TagGroups_Utilities::is_premium_plan()) {
                        $tag_group_object_cache = TagGroups_Options::get_option(
                            'tag_group_object_cache',
                            TagGroups_Object_Cache::WP_OPTIONS
                        );

                        $object_cache_options = array(
                            1 => __('Transients', 'tag-groups'),
                            2 => __('Database', 'tag-groups'),
                            3 => __('Filesystem', 'tag-groups'),
                            9 => __('WP Object Cache', 'tag-groups'),
                        );

                        $benchmark['name'] = __('Cache', 'tag-groups') . ' ' . __('(1000x read, 100x write)', 'tag-groups');
                        $benchmark['value'] = array();
                        $cache_key = md5('benchmark');

                        foreach ($object_cache_options as $object_cache_option_id => $object_cache_option_name) {
                            $benchmark['value'][$object_cache_option_id] = '';

                            TagGroups_Options::update_option('tag_group_object_cache', $object_cache_option_id);

                            $result = apply_filters('tag_groups_hook_cache_get', false, $cache_key . '-efficacy-test');

                            if ($result === false) {
                                $benchmark['value'][$object_cache_option_id] .= sprintf(
                                    '%s: %s',
                                    $object_cache_option_name,
                                    __('ineffective', 'tag-groups')
                                );
                            } else {
                                $start_time = microtime(true);
                                $cache_key_loop = $cache_key . '-0';

                                for ($i = 0; $i < 1000; $i++) {
                                    if ($i % 10 == 0) {
                                        $cache_key_loop = $cache_key . '-' . $i;
                                        do_action('tag_groups_hook_cache_set', $cache_key_loop, 'sample content');
                                    }

                                    $result = apply_filters('tag_groups_hook_cache_get', false, $cache_key_loop);

                                    if ($result != 'sample content') {
                                        break;
                                    }
                                }

                                if ($result != 'sample content') {
                                    $benchmark['value'][$object_cache_option_id] = sprintf(
                                        '%s: error',
                                        $object_cache_option_name
                                    );
                                } else {
                                    $selected = $object_cache_option_id == $tag_group_object_cache ? '(selected)' : '';

                                    $benchmark['value'][$object_cache_option_id] .= sprintf(
                                        '%s: %d ms %s',
                                        $object_cache_option_name,
                                        1000 * (microtime(true) - $start_time),
                                        $selected
                                    );
                                }
                            }
                        }

                        $benchmark['value'] = implode('<br/>', $benchmark['value']);

                        TagGroups_Options::update_option('tag_group_object_cache', $tag_group_object_cache);
                    }

                    break;
            }
            echo  wp_json_encode($benchmark) ;
            wp_die();
        }
    }
}
