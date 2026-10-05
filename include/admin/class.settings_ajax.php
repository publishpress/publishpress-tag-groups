<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright  2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Settings_Ajax')) {
    /**
     *
     */
    class TagGroups_Settings_Ajax
    {
        private const BENCHMARK_CACHE_GROUP = 'tag-groups-benchmark';

        /**
         * Runs selected routines for benchmarking
         *
         * @param void
         * @return string HTML
         * @since 1.23.0
         */
        public static function ajax_benchmark()
        {
            if (!current_user_can('manage_options')) {
                return wp_send_json_error(array( 'message' => __('You are not allowed to run cache benchmarks.', 'tag-groups') ), 403);
            }

            $request_method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper(sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD']))) : '';
            if ('POST' !== $request_method) {
                return wp_send_json_error(array( 'message' => __('Invalid request method.', 'tag-groups') ), 405);
            }

            if (false === check_ajax_referer('tag_groups_cache_benchmark', 'nonce', false)) {
                return wp_send_json_error(array( 'message' => __('The security check failed.', 'tag-groups') ), 403);
            }

            $task = isset($_POST['task']) ? sanitize_key(wp_unslash($_POST['task'])) : '';
            if ('cache' !== $task) {
                return wp_send_json_error(array( 'message' => __('Invalid benchmark task.', 'tag-groups') ), 400);
            }

            $lock_name = 'tag_groups_cache_benchmark_lock';
            $lock_time = (int) get_option($lock_name, 0);
            if ($lock_time && $lock_time > time() - MINUTE_IN_SECONDS) {
                return wp_send_json_error(array( 'message' => __('A cache benchmark is already running.', 'tag-groups') ), 429);
            }

            if ($lock_time) {
                delete_option($lock_name);
            }

            if (!add_option($lock_name, time(), '', false)) {
                return wp_send_json_error(array( 'message' => __('A cache benchmark is already running.', 'tag-groups') ), 429);
            }

            $benchmark = array();

            try {
                if (TagGroups_Utilities::is_premium_plan()) {
                    $selected_cache = TagGroups_Options::get_option(
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

                        self::benchmark_cache_set($object_cache_option_id, $cache_key . '-efficacy-test', 'sample content');
                        $result = self::benchmark_cache_get($object_cache_option_id, $cache_key . '-efficacy-test');

                        if ('sample content' !== $result) {
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
                                    self::benchmark_cache_set($object_cache_option_id, $cache_key_loop, 'sample content');
                                }

                                $result = self::benchmark_cache_get($object_cache_option_id, $cache_key_loop);

                                if ('sample content' !== $result) {
                                    break;
                                }
                            }

                            if ('sample content' !== $result) {
                                $benchmark['value'][$object_cache_option_id] = sprintf(
                                    '%s: error',
                                    $object_cache_option_name
                                );
                            } else {
                                $selected = $object_cache_option_id == $selected_cache ? '(selected)' : '';

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
                }
            } finally {
                delete_option($lock_name);
            }

            echo wp_json_encode($benchmark);
            wp_die();
        }

        /**
         * Writes a fixed benchmark key directly to the selected backend.
         *
         * @param int    $cache_type Cache backend identifier.
         * @param string $key        Benchmark key.
         * @param mixed  $value      Benchmark value.
         * @return void
         */
        private static function benchmark_cache_set($cache_type, $key, $value)
        {
            if (9 === (int) $cache_type) {
                wp_cache_set($key, $value, self::BENCHMARK_CACHE_GROUP, MINUTE_IN_SECONDS);
                return;
            }

            $cache = new TagGroups_Object_Cache();
            $cache->type($cache_type)
                ->path(WP_CONTENT_DIR . '/chatty-mango/cache/')
                ->lifetime(MINUTE_IN_SECONDS)
                ->key($key)
                ->set($value);
        }

        /**
         * Reads a fixed benchmark key directly from the selected backend.
         *
         * @param int    $cache_type Cache backend identifier.
         * @param string $key        Benchmark key.
         * @return mixed
         */
        private static function benchmark_cache_get($cache_type, $key)
        {
            if (9 === (int) $cache_type) {
                return wp_cache_get($key, self::BENCHMARK_CACHE_GROUP);
            }

            $cache = new TagGroups_Object_Cache();

            return $cache->type($cache_type)
                ->path(WP_CONTENT_DIR . '/chatty-mango/cache/')
                ->key($key)
                ->get();
        }
    }
}
