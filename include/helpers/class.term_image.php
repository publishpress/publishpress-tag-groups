<?php

/**
 * @package     Tag Groups
 * @license     GPL-3.0+
 */

defined('ABSPATH') || exit;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (!class_exists('TagGroups_Term_Image')) {
    /**
     * Image of a tag, chosen from the media library and stored as an attachment ID in term meta
     */
    class TagGroups_Term_Image
    {
        /**
         * Term meta key of the attachment ID
         */
        public const META_KEY = '_tg_image';

        /**
         * Attachment ID of the tag's image, or 0 if there is none or it is not an image any more
         *
         * @param int $term_id
         * @return int
         */
        public static function get_id($term_id)
        {
            $attachment_id = (int) get_term_meta((int) $term_id, self::META_KEY, true);

            return self::is_valid($attachment_id) ? $attachment_id : 0;
        }

        /**
         * Whether an attachment can be used as a tag image
         *
         * @param int $attachment_id
         * @return bool
         */
        public static function is_valid($attachment_id)
        {
            return $attachment_id > 0 && 'attachment' === get_post_type($attachment_id) && wp_attachment_is_image($attachment_id);
        }

        /**
         * Loads the attachments of several tags, with their meta data, in one query
         *
         * Call this before rendering many tags, so get_id() and get_html() don't query each attachment separately.
         *
         * @param int[] $term_ids
         * @return void
         */
        public static function prime_caches($term_ids)
        {
            $attachment_ids = array();
            foreach ($term_ids as $term_id) {
                $attachment_id = (int) get_term_meta((int) $term_id, self::META_KEY, true);
                if ($attachment_id > 0) {
                    $attachment_ids[ $attachment_id ] = $attachment_id;
                }
            }

            if (empty($attachment_ids)) {
                return;
            }

            // get_posts() caches the posts and, by default, their meta data
            get_posts(array(
                'post__in'               => array_values($attachment_ids),
                'post_type'              => 'attachment',
                'post_status'            => 'inherit',
                'posts_per_page'         => count($attachment_ids),
                'no_found_rows'          => true,
                'update_post_term_cache' => false,
            ));
        }

        /**
         * Saves or removes the image of a tag
         *
         * @param int $term_id
         * @param int $attachment_id
         * @return void
         */
        public static function set_id($term_id, $attachment_id)
        {
            $attachment_id = (int) $attachment_id;

            if (self::is_valid($attachment_id)) {
                update_term_meta((int) $term_id, self::META_KEY, $attachment_id);
            } else {
                delete_term_meta((int) $term_id, self::META_KEY);
            }
        }

        /**
         * The tag's image as an <img> element; the alt text is empty because the tag name follows
         *
         * @param int $term_id
         * @param string $size
         * @param string $class
         * @return string
         */
        public static function get_html($term_id, $size = 'medium', $class = 'tag-groups-card-image')
        {
            $attachment_id = self::get_id($term_id);

            if (!$attachment_id) {
                return '';
            }

            return wp_get_attachment_image($attachment_id, $size, false, array(
                'class'   => $class,
                'alt'     => '',
                'loading' => 'lazy',
            ));
        }
    }
}
