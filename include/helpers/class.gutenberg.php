<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2020 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (! class_exists('TagGroups_Gutenberg')) {
/**
  *
  */
    class TagGroups_Gutenberg
    {
      /**
       * Check if Gutenberg is active.
       * Must be used not earlier than plugins_loaded action fired.
       * from https://gist.github.com/mihdan/8ba1a70d8598460421177c7d31202908
       *
       * @return bool
       */
        public static function is_gutenberg_active()
        {
            $gutenberg    = false;
            $block_editor = false;
            if (has_filter('replace_editor', 'gutenberg_init')) {
                    // Gutenberg is installed and activated.
                      $gutenberg = true;
            }

            if (version_compare($GLOBALS['wp_version'], '5.0-beta', '>')) {
    // Block editor.
                $block_editor = true;
            }

            if (! $gutenberg && ! $block_editor) {
                return false;
            }

            include_once ABSPATH . 'wp-admin/includes/plugin.php';
            if (! is_plugin_active('classic-editor/classic-editor.php')) {
                return true;
            }

            $use_block_editor = ( get_option('classic-editor-replace') === 'no-replace' );
            return $use_block_editor;
        }


      /**
       * Create a new block category
       *
       * @param array $categories
       * @return array
       */
        public static function block_categories($categories)
        {

            $category_slugs = wp_list_pluck($categories, 'slug');
            return in_array('chatty-mango', $category_slugs, true) ? $categories : array_merge($categories, array(
              array(
                  'slug'  => 'chatty-mango',
                  'title' => 'PublishPress Tag Groups',
                  // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
                  'icon'  => null, //'admin-plugins', // icons might be removed in future
              ),
            ));
        }
    }


}
