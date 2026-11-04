<?php

/**
* Tag Groups Pro
*
* @package     Tag Groups Pro
* @author      Christoph Amthor
* @copyright   2017 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     see official vendor website
*
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*
*/

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps, PSR1.Methods.CamelCapsMethodName.NotCamelCaps
defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Update')) {
    class TagGroups_Update
    {
          private $old_version;
        private $new_version;


          /**
           * 
           * @unittest
           *
           * @param string $old_version
           * @param string $new_version
           */
        public function __construct($old_version, $new_version)
        {

            $this->old_version = $old_version;
            $this->new_version = $new_version;
        }


      /**
       * Run scripts for specific old or new versions
       *
       * @unittest
       * @return void
       */
        public function run_specific_scripts()
        {

            if (empty($this->old_version) || empty($this->new_version)) {
                return;
            }

            // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
            // if ( version_compare( $this->old_version, '1.18.0' , '<' ) ) {
            // }
        }


      /**
      * Run processes for all version number steps
      *
      * @param void
      * @return void
      */
        public function run_general_scripts()
        {

            /*
           * Taxonomy should not be empty
           */
            $tag_group_taxonomy = TagGroups_Options::get_option('tag_group_taxonomy', array());
            if (empty($tag_group_taxonomy)) {
                update_option('tag_group_taxonomy', array('post_tag'));
            } elseif (! is_array($tag_group_taxonomy)) {
            // Prevent some weird errors
                  TagGroups_Options::update_option('tag_group_taxonomy', array( $tag_group_taxonomy ));
            }

            /*
           * Theme should not be empty
           */
            if ('' == TagGroups_Options::get_option('tag_group_theme', '')) {
                TagGroups_Options::update_option('tag_group_theme', TAG_GROUPS_STANDARD_THEME);
            }


            /**
             * Register time of first use
             */
            if (defined('TAG_GROUPS_PLUGIN_IS_FREE') && TAG_GROUPS_PLUGIN_IS_FREE) {
                if (! TagGroups_Options::get_option('tag_group_base_first_activation_time', false)) {
                    update_option('tag_group_base_first_activation_time', time());
                }
            }


            // If requested and new options exist, then remove old options.
            if (
                defined('TAG_GROUPS_REMOVE_OLD_OPTIONS')
                && TAG_GROUPS_REMOVE_OLD_OPTIONS
                && TagGroups_Options::get_option('term_groups', false)
                && TagGroups_Options::get_option('term_group_positions', false)
                && TagGroups_Options::get_option('term_group_labels', false)
            ) {
                delete_option('tag_group_labels');
                delete_option('tag_group_ids');
                delete_option('max_tag_group_id');
                TagGroups_Error::log('[Tag Groups] Deleted deprecated options');
            }


            /**
             * Start with some delay so that in the case of simultaneous activation the base plugin will be available
             */
            TagGroups_Cron::schedule_in_secs(2, 'tag_groups_check_tag_migration');
            TagGroups_Cron::schedule_in_secs(500, 'tag_groups_check_if_migrations_done');
    // long enough after premium tasks

            /**
             * Reset the group filter above the tags list
             */
            update_option('tag_group_tags_filter', array());
        }


      /**
       * Update the database entry with the latest version
       *
       * @unittest
       * @return void
       */
        public function update_version_number()
        {

            // save new version
            TagGroups_Options::update_option('tag_group_base_version', $this->new_version);
        }
    }


}
