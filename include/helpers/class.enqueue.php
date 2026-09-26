<?php

/**
 * @package     Tag Groups
 *
 * @author      Christoph Amthor
 * @copyright   2019 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     GPL-3.0+
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
if (! class_exists('TagGroups_Enqueue')) {

  /**
   *
   */
    class TagGroups_Enqueue
    {
      /**
       * Add js and css to frontend
       *
       * @param  void
       * @return void
       */
        public function wp_enqueue_scripts()
        {
      
            if (is_admin()) {
                return;
            }

            if (TagGroups_Options::get_option('tag_group_enqueue_jquery', 1)) {
                wp_enqueue_script('jquery');

                wp_enqueue_script('jquery-ui-core');

                wp_enqueue_script('jquery-ui-tabs');

                wp_enqueue_script('jquery-ui-accordion');
            }

            if (defined('WP_DEBUG') && WP_DEBUG) {
                wp_register_script('tag-groups-js-frontend', TAG_GROUPS_PLUGIN_URL . '/assets/js/frontend.js', array(), TAG_GROUPS_VERSION);
            } else {
                wp_register_script('tag-groups-js-frontend', TAG_GROUPS_PLUGIN_URL . '/assets/js/frontend.min.js', array(), TAG_GROUPS_VERSION);
            }

            wp_enqueue_script('tag-groups-js-frontend');

            $this->load_theme_css();

            wp_enqueue_style('tag-groups-css-frontend-structure');

            wp_enqueue_style('tag-groups-css-frontend-theme');

            $this->enqueue_frontend_css();
        }

      /**
       * Add css to backend
       *
       * @param  string $where
       * @return void
       */
        public function admin_enqueue_scripts($where)
        {

            if (strpos($where, 'tag-groups-settings') !== false) {
                wp_enqueue_script('jquery');
        
                wp_enqueue_script('jquery-ui-core');
        
                wp_enqueue_script('jquery-ui-accordion');

                wp_enqueue_script('jquery-ui-tabs');
        
                wp_enqueue_script('jquery-ui-tooltip');

                wp_register_style('tag-groups-css-backend-structure', TAG_GROUPS_PLUGIN_URL . '/assets/css/jquery-ui.structure.min.css', array(), TAG_GROUPS_VERSION);

                wp_enqueue_style('tag-groups-css-backend-structure');

                wp_register_style('tag-groups-css-backend-theme', TAG_GROUPS_PLUGIN_URL . '/assets/css/base/jquery-ui.theme.min.css', array(), TAG_GROUPS_VERSION);

                wp_enqueue_style('tag-groups-css-backend-theme');

                if (defined('WP_DEBUG') && WP_DEBUG) {
                    wp_register_style('tag-groups-css-backend-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend.css', array(), TAG_GROUPS_VERSION);
                } else {
                    wp_register_style('tag-groups-css-backend-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend.min.css', array(), TAG_GROUPS_VERSION);
                }

                wp_enqueue_style('tag-groups-css-backend-tgb');

                wp_register_script('tag-groups-sumoselect-js', TAG_GROUPS_PLUGIN_URL . '/assets/js/jquery.sumoselect.min.js', array(), TAG_GROUPS_VERSION);

                wp_enqueue_script('tag-groups-sumoselect-js');

                $banners_style_path = '/publishpress/wordpress-banners/assets/css/style.css';
                $banners_vendor_url = '/vendor';

                if (defined('TAG_GROUPS_LIB_VENDOR_PATH') && file_exists(TAG_GROUPS_LIB_VENDOR_PATH . $banners_style_path)) {
                    $banners_vendor_url = '/lib/vendor';
                }

                wp_enqueue_style('pp-wordpress-banners-style', TAG_GROUPS_PLUGIN_URL . $banners_vendor_url . $banners_style_path, false, TAG_GROUPS_VERSION);

        
                $this->load_sumoselect_css();
            } elseif (strpos($where, '_page_tag-groups') !== false) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    wp_register_style('tag-groups-css-backend-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend.css', array(), TAG_GROUPS_VERSION);
                } else {
                    wp_register_style('tag-groups-css-backend-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend.min.css', array(), TAG_GROUPS_VERSION);
                }

                wp_enqueue_style('tag-groups-css-backend-tgb');

                if (defined('WP_DEBUG') && WP_DEBUG) {
                    wp_register_script('tag-groups-js-backend', TAG_GROUPS_PLUGIN_URL . '/assets/js/backend.js', array(), TAG_GROUPS_VERSION);
                } else {
                    wp_register_script('tag-groups-js-backend', TAG_GROUPS_PLUGIN_URL . '/assets/js/backend.min.js', array(), TAG_GROUPS_VERSION);
                }

                wp_enqueue_script('tag-groups-js-backend');

                wp_enqueue_script('jquery-ui-sortable');

                wp_enqueue_script('jquery-ui-core');

                wp_enqueue_script('jquery-ui-accordion');
        
                wp_enqueue_script('jquery-ui-tooltip');
            } elseif (strpos($where, 'edit-tags.php') !== false || strpos($where, 'term.php') !== false || strpos($where, 'edit.php') !== false) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    wp_register_script('tag-groups-sumoselect-js', TAG_GROUPS_PLUGIN_URL . '/assets/js/jquery.sumoselect.js', array(), TAG_GROUPS_VERSION);
                } else {
                    wp_register_script('tag-groups-sumoselect-js', TAG_GROUPS_PLUGIN_URL . '/assets/js/jquery.sumoselect.min.js', array(), TAG_GROUPS_VERSION);
                }

                wp_enqueue_script('tag-groups-sumoselect-js');

                $this->load_sumoselect_css();

                if (defined('WP_DEBUG') && WP_DEBUG) {
                    wp_register_style('tag-groups-css-backend-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend.css', array(), TAG_GROUPS_VERSION);
                } else {
                    wp_register_style('tag-groups-css-backend-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend.min.css', array(), TAG_GROUPS_VERSION);
                }

                wp_enqueue_style('tag-groups-css-backend-tgb');
            } elseif (strpos($where, 'post-new.php') !== false || strpos($where, 'post.php') !== false) {
            // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
            // use following line to enable gutenberg on Appearance > Widgets
            // } elseif ( strpos( $where, 'post-new.php' ) !== false || strpos( $where, 'post.php' ) !== false || strpos( $where, 'widgets.php' ) !== false ) {

                if (defined('WP_DEBUG') && WP_DEBUG) {
                    wp_register_style('tag-groups-react-select-css', TAG_GROUPS_PLUGIN_URL . '/assets/css/react-select.css', array(), TAG_GROUPS_VERSION);
                } else {
                    wp_register_style('tag-groups-react-select-css', TAG_GROUPS_PLUGIN_URL . '/assets/css/react-select.min.css', array(), TAG_GROUPS_VERSION);
                }

                wp_enqueue_style('tag-groups-react-select-css');

                if (defined('WP_DEBUG') && WP_DEBUG) {
                    wp_register_style('tag-groups-css-backend-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend.css', array(), TAG_GROUPS_VERSION);
                } else {
                    wp_register_style('tag-groups-css-backend-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend.min.css', array(), TAG_GROUPS_VERSION);
                }

                wp_enqueue_style('tag-groups-css-backend-tgb');
            }

          /* If we have RTL, we load an additional file for support */
            if (wp_style_is('tag-groups-css-backend-tgb', 'enqueued') && is_rtl()) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    wp_register_style('tag-groups-css-backend-rtl-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend-rtl.css', array(), TAG_GROUPS_VERSION);
                } else {
                    wp_register_style('tag-groups-css-backend-rtl-tgb', TAG_GROUPS_PLUGIN_URL . '/assets/css/backend-rtl.min.css', array(), TAG_GROUPS_VERSION);
                }

                wp_enqueue_style('tag-groups-css-backend-rtl-tgb');
            }

            if (TagGroups_Gutenberg::is_gutenberg_active()) {
                $this->admin_enqueue_scripts_for_gutenberg();
            }
        }

      /**
       * Adds js and css to the Gutenberg editor page
       *
       *
       * @param  void
       * @return void
       */
        public function admin_enqueue_scripts_for_gutenberg()
        {

          /** enqueue frontend scripts and styling only if shortcode in use */

            $screen = get_current_screen();

            if (is_object($screen) && property_exists($screen, 'base') && 'post' != $screen->base && 'site-editor' != $screen->base) {
            // use following line to enable gutenberg on Appearance > Widgets
            // if ( is_object( $screen ) && property_exists( $screen, 'base' ) && 'post' != $screen->base && 'widgets' != $screen->base ) {

                return;
            }

            wp_enqueue_script('jquery');

            wp_enqueue_script('jquery-ui-core');

            wp_enqueue_script('jquery-ui-tabs');

            wp_enqueue_script('jquery-ui-accordion');
        
            wp_enqueue_script('jquery-ui-tooltip');

            $this->load_theme_css();

            if (! is_admin()) {
                $this->enqueue_frontend_css();
            }

          /**
           * load the JS
           */
            if (defined('WP_DEBUG') && WP_DEBUG) {
                wp_register_script('tag-groups-js-frontend', TAG_GROUPS_PLUGIN_URL . '/assets/js/frontend.js', array(), TAG_GROUPS_VERSION);
            } else {
                wp_register_script('tag-groups-js-frontend', TAG_GROUPS_PLUGIN_URL . '/assets/js/frontend.min.js', array(), TAG_GROUPS_VERSION);
            }

            wp_enqueue_script('tag-groups-js-frontend');
        }

      /**
       * enqueue CSS for free features
       *
       * @return void
       */
        private function enqueue_frontend_css()
        {

            if (defined('WP_DEBUG') && WP_DEBUG) {
                wp_register_style('tag-groups-css-frontend', TAG_GROUPS_PLUGIN_URL . '/assets/css/frontend.css', array(), TAG_GROUPS_VERSION);
            } else {
                wp_register_style('tag-groups-css-frontend', TAG_GROUPS_PLUGIN_URL . '/assets/css/frontend.min.css', array(), TAG_GROUPS_VERSION);
            }

            wp_enqueue_style('tag-groups-css-frontend');
        }


      /**
       * Load the CSS of the theme
       *
       * @return void
       */
        public function load_theme_css()
        {

            $theme = TagGroups_Options::get_option('tag_group_theme', TAG_GROUPS_STANDARD_THEME);

            if ('' == $theme) {
                return;
            }

            wp_register_style('tag-groups-css-frontend-structure', TAG_GROUPS_PLUGIN_URL . '/assets/css/jquery-ui.structure.min.css', array(), TAG_GROUPS_VERSION);

            $default_themes = explode(',', TAG_GROUPS_BUILT_IN_THEMES);

            if (in_array($theme, $default_themes)) {
                wp_register_style('tag-groups-css-frontend-theme', TAG_GROUPS_PLUGIN_URL . '/assets/css/' . $theme . '/jquery-ui.theme.min.css', array(), TAG_GROUPS_VERSION);
            } else {

              /**
               * Load minimized css if available
               */
                if (file_exists(WP_CONTENT_DIR . '/uploads/' . $theme . '/jquery-ui.theme.min.css')) {
                    wp_register_style('tag-groups-css-frontend-theme', get_bloginfo('wpurl') . '/wp-content/uploads/' . $theme . '/jquery-ui.theme.min.css', array(), TAG_GROUPS_VERSION);
                } elseif (file_exists(WP_CONTENT_DIR . '/uploads/' . $theme . '/jquery-ui.theme.css')) {
                    wp_register_style('tag-groups-css-frontend-theme', get_bloginfo('wpurl') . '/wp-content/uploads/' . $theme . '/jquery-ui.theme.css', array(), TAG_GROUPS_VERSION);
                } else {

                  /**
                   * Fallback: Is this a custom theme of an old version or did we revert to old plugin version?
                   */
                    if (file_exists(WP_CONTENT_DIR . '/uploads/' . $theme)) {
                        $dh = opendir(WP_CONTENT_DIR . '/uploads/' . $theme);

                        if (! empty($dh)) {
                            while (false !== ( $filename = @readdir($dh) )) {
                                if (preg_match("/jquery-ui-\d+\.\d+\.\d+\.custom\.(min\.)?css/i", $filename)) {
                                        wp_register_style('tag-groups-css-frontend-theme', get_bloginfo('wpurl') . '/wp-content/uploads/' . $theme . '/' . $filename, array(), TAG_GROUPS_VERSION);

                                        break;
                                }
                            }
                        }
                    } else {
                        TagGroups_Error::log('[Tag Groups] Error finding %s/uploads/%s', WP_CONTENT_DIR, $theme);
                    }
                }
            }

            wp_enqueue_style('tag-groups-css-frontend-structure');

            wp_enqueue_style('tag-groups-css-frontend-theme');
        }


      /**
       * Load the Sumoselect CSS for the text direction
       *
       * @return void
       */
        public function load_sumoselect_css()
        {

            $direction = is_rtl() ? '-rtl' : '';

            if (defined('WP_DEBUG') && WP_DEBUG) {
                wp_register_style('tag-groups-sumoselect-css', TAG_GROUPS_PLUGIN_URL . '/assets/css/sumoselect' . $direction . '.css', array(), TAG_GROUPS_VERSION);
            } else {
                wp_register_style('tag-groups-sumoselect-css', TAG_GROUPS_PLUGIN_URL . '/assets/css/sumoselect' . $direction . '.min.css', array(), TAG_GROUPS_VERSION);
            }

            wp_enqueue_style('tag-groups-sumoselect-css');
        }
    }

}
