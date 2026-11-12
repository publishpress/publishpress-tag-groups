<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Transients', false)) {
    require_once dirname(__DIR__, 2) . '/helpers/cache/class.transients.php';
}

if (!class_exists('TagGroups_Cron_Handlers')) {
    /**
     *
     */
    class TagGroups_Cron_Handlers
    {
        /**
         * Retrieves all transients created by Tag Groups Pro and deletes what is expired
         *
         * @param  void
         * @return int
         */
        public static function purge_expired_transients()
        {
            $count = 0;
            TagGroups_Error::verbose_log('[Tag Groups Pro] Purging expired transients.');
            $count += TagGroups_Transients::delete_all_expired_transients();
            TagGroups_Error::verbose_log('[Tag Groups Pro] Purged %d expired transients.', $count);
            return $count;
        }
        
        /**
         * executes the routines to add the required term meta
         *
         * @param  void
         * @return void
         */
        public static function run_term_migration()
        {
            global  $tag_group_terms ;
            /**
             * Add group affiliation to the term meta; term_group will be only secondary
             */
            TagGroups_Error::verbose_log('[Tag Groups] Migrating terms.');
            $start_time = microtime(true);
            $offset = TagGroups_Options::get_option('tag_group_run_term_migration_offset', 0);
            
            if (defined('TAG_GROUPS_CHUNK_SIZE')) {
                $length = (int) TAG_GROUPS_CHUNK_SIZE;
            } else {
                $length = 50;
            }
            
            $term_count = TagGroups_Term_Meta_Tools::convert_to_term_meta(false, $offset, $length);
            TagGroups_Error::verbose_log('[Tag Groups] %d term(s) migrated in %d milliseconds.', $term_count, round((microtime(true) - $start_time) * 1000));
            
            if ($term_count === false) {
                TagGroups_Options::update_option('tag_group_run_term_migration_offset', 0);
                TagGroups_Error::verbose_log('[Tag Groups Pro] tag_groups_run_term_migration done.');
            } else {
                TagGroups_Options::update_option('tag_group_run_term_migration_offset', $offset + $length);
                TagGroups_Cron::schedule_in_secs(1, 'tag_groups_run_term_migration');
                TagGroups_Error::verbose_log('[Tag Groups Pro] Rescheduled tag_groups_run_term_migration from offset %d.', $offset + $length);
            }
            
            if (false === $term_count || empty($length) && $term_count > 0) {
                $tag_group_terms->clear_term_cache();
            }
        }
        
        /**
         * Check if we need to run the migration of terms
         *
         * @since 1.24.0
         *
         * @param  void
         * @return void
         */
        public static function maybe_schedule_term_migration()
        {
            TagGroups_Error::verbose_log('[Tag Groups] Checking if we should migrate terms.');
            $convert_term_count = TagGroups_Term_Meta_Tools::convert_to_term_meta(true);
            
            if ($convert_term_count) {
                TagGroups_Error::verbose_log('[Tag Groups] %d terms should be migrated.', $convert_term_count);
                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                // TagGroups_Term_Meta_Tools::convert_to_term_meta();
                TagGroups_Cron::schedule_in_secs(2, 'tag_groups_run_term_migration');
            }
        }
        
        /**
         * Check if we need to run the migration manually
         *
         * @since 1.39.8
         *
         * @param  void
         * @return void
         */
        public static function tag_groups_check_if_migrations_done()
        {
            
            TagGroups_Error::verbose_log('[Tag Groups] Checking if we should migrate terms.');
            $convert_term_count = TagGroups_Term_Meta_Tools::convert_to_term_meta(true);
            $recommend_post_migration = false;
            if (TagGroups_Utilities::is_premium_plan() && class_exists('TagGroups_Premium_Post_Meta_Tools')) {
                TagGroups_Cron::schedule_in_secs(5, 'tag_groups_run_post_migration');
                $post_ids = TagGroups_Premium_Post_Meta_Tools::get_post_ids_with_missing_group();

                if (count($post_ids) > 1000) {
                    $recommend_post_migration = true;
                }
            }

            if ($convert_term_count > 0 || $recommend_post_migration) {
                // If there's a lot to do, we also want to show the admin notice
                TagGroups_Admin::recommend_to_run_migration();
            }
        }
        
        /**
         * Clear the transient cache tag_groups_group_terms
         *
         * @param  void
         * @return void
         */
        public static function clear_tag_groups_group_terms()
        {
            TagGroups_Error::verbose_log('[Tag Groups] Clearing the transient cache tag_groups_group_terms.');
            $languages = apply_filters('wpml_active_languages', null, '');
            
            if (!empty($languages)) {
                foreach ($languages as $language_code => $language_info) {
                    TagGroups_Transients::delete_all_transients('tag_groups_group_terms-' . $language_code);
                }
            } else {
                TagGroups_Transients::delete_all_transients('tag_groups_group_terms');
            }
        }
    }
}
