<?php

/**
 * Tag Groups
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * @package     Tag Groups Pro
 *
 * @author      Christoph Amthor
 * @copyright   2017 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license     see official vendor website
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, Squiz.Scope.MethodScope.Missing, PSR1.Methods.CamelCapsMethodName.NotCamelCaps, Squiz.PHP.CommentedOutCode.Found, WordPress.Security.NonceVerification.Recommended
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_WPML')) {
    class TagGroups_WPML
    {
          /**
           * Whether this is a multilingual site
           *
           * checking for:
           * - WPML
           * - Polylang
           *
           * @return boolean
           */
        static function is_multilingual()
        {

            if (defined('ICL_LANGUAGE_CODE')) {
                return true;
            }

            if (function_exists('pll_current_language')) {
                return true;
            }

        // if ( ! empty( $_GET[ 'lang' ] ) ) {

        //   return true;

        // }

            return false;
        }

      /**
       * Returns the language code (by default ICL_LANGUAGE_CODE)
       *
       * @return string
       */
        static function get_current_language()
        {

            if (defined('ICL_LANGUAGE_CODE')) {
                return (string) ICL_LANGUAGE_CODE;
            }

            if (function_exists('pll_current_language')) {
                $current_language = pll_current_language();
                if ($current_language) {
                    return (string) $current_language;
                }

                if (isset($_GET['lang'])) {
                    return sanitize_key($_GET['lang']);
                }
            }

            return '';
        }


      /**
       * Get the transient name for tag_groups_group_terms
       *
       * In case we use the WPML plugin: consider the language
       *
       * @param  void
       * @return string
       */
        public static function get_tag_groups_group_terms_transient_name()
        {

            $current_language = self::get_current_language();
            if ($current_language) {
                return 'tag_groups_group_terms-' . $current_language;
            } else {
                return 'tag_groups_group_terms';
            }
        }


      /**
       * Get the transient name for tag_groups_post_counts
       *
       * In case we use the WPML plugin: consider the language
       * Use $language if provided, else use current language
       *
       * @param  string   $language
       * @return string
       */
        public static function get_tag_groups_post_counts_transient_name($language_code = null)
        {

            if (! empty($language_code)) {
                return 'tag_groups_post_counts-' . (string) $language_code;
            }

            $current_language = self::get_current_language();
            if ($current_language) {
                return 'tag_groups_post_counts-' . $current_language;
            } else {
                return 'tag_groups_post_counts';
            }
        }


      /**
       * Sync the group(s) of a tag to all translations of this tag
       *
       * @param int $term_id
       * @param int $tt_id
       * @param string $taxonomy
       * @return void
       */
        static function sync_groups($term_id, $tt_id, $taxonomy)
        {

            /**
             * Test for is_multilingual only here because we don't yet know when adding hooks
             */
            if (! TagGroups_WPML::is_multilingual()) {
                return;
            }

            if (! in_array($taxonomy, TagGroups_Taxonomy::get_enabled_taxonomies())) {
                return;
            }

            TagGroups_Error::verbose_log('[Tag Groups] Syncing groups of term ID %d', $term_id);
    /**
             * get translations
             */
            $trid = apply_filters('wpml_element_trid', null, $tt_id, "tax_{$taxonomy}");
            $translations = apply_filters('wpml_get_element_translations', null, $trid, "tax_{$taxonomy}");
            if (empty($translations) || ! is_array($translations)) {
                TagGroups_Error::verbose_log('[Tag Groups] Cannot get translations for term ID %d', $term_id);
                return;
            }

            /**
             * get groups of saved term
             */
            $tg_term = new TagGroups_Term($term_id);
            $groups = $tg_term->get_groups();
            foreach ($translations as $language => $translation) {
                if ($translation->element_id == $term_id) {
                    continue;
                }
        
              /**
               * do the sync
               */
                $tg_translated_term = new TagGroups_Term($translation->element_id);
                $tg_translated_term->set_group($groups)->save();
                TagGroups_Error::verbose_log('[Tag Groups] Groups synced of term ID %d for language %s', $translation->element_id, $language);
            }
        }
    }


}
