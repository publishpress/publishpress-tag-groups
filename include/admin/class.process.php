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
 * @package    Tag Groups
 *
 * @author     Christoph Amthor
 * @copyright  2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
 * @license    see official vendor website
 *
 * @since      1.24.0
 */

defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Process')) {
/**
   * Background workers for the progress bar
   *
   */
    // phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps -- Legacy class structure
    class TagGroups_Process
    {
      /**
       * Receives the Ajax call to process chunks of long tasks, to avoid timeouts
       *
       *
       * @param  void
       * @return void
       */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function tg_ajax_process()
        {

            $affected = 0;
            $error = false;
            $nonce = '';
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified below
            if (isset($_REQUEST['nonce'])) {
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended -- Nonce verified below
                    $nonce = sanitize_text_field(wp_unslash($_REQUEST['nonce']));
            }

            if (! current_user_can('manage_options')) {
                die('Access denied.');
            }

            if (! wp_verify_nonce($nonce, 'tag-groups-process-nonce')) {
                die('Access denied.');
            }

            if (isset($_REQUEST['task'])) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized in switch statement
                $task = sanitize_key(wp_unslash($_REQUEST['task']));
            } else {
                  $error = true;
            }

            if (isset($_REQUEST['offset'])) {
                $offset = (int) $_REQUEST['offset'];
            } else {
                $error = true;
            }

            if (isset($_REQUEST['length'])) {
                $length = (int) $_REQUEST['length'];
            } else {
                $error = true;
            }

            if (! $error) {
                switch ($task) {
                    case 'migratetermmeta':
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           $affected = TagGroups_Term_Meta_Tools::convert_to_term_meta(false, $offset, $length);


                        break;
                    case 'fixgroups':
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   $affected = TagGroups_Group_Tools::check_fix_groups(false, $offset, $length);


                        break;
                    case 'fixmissinggroups':
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   $affected = TagGroups_Term_Meta_Tools::remove_missing_groups(false, $offset, $length);


                        break;
                    case 'sortgroups':
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   $affected = TagGroups_Term_Meta_Tools::sort_groups(false, false, $offset, $length);


                        break;
                    default:
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   $error = true;


                        break;
                }
            }

            if ($error) {
                echo wp_json_encode(array(
                'data'     => 'error',
                'done'     => 0,
                'affected' => 0,
                ));
                TagGroups_Utilities::die();
            }

            echo wp_json_encode(array(
            'data'     => 'success',
            'done'     => 1,
            'affected' => $affected,
            ));
            TagGroups_Utilities::die();
        }

      /**
       * Gets the total number of things to do for a particular task
       *
       * @param  string $task
       * @return int
       */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public static function get_task_total($task, $language_code = null)
        {

            switch ($task) {
                case 'migratetermmeta':
                    return TagGroups_Term_Meta_Tools::convert_to_term_meta(true);
                case 'fixgroups':
                    return TagGroups_Group_Tools::check_fix_groups(true);
                break;
                case 'fixmissinggroups':
                    return TagGroups_Term_Meta_Tools::remove_missing_groups(true);
                break;
                case 'sortgroups':
                    return TagGroups_Term_Meta_Tools::sort_groups(true, true);
                break;
                break;
                case 'fixmissinggroups':
                    return TagGroups_Term_Meta_Tools::remove_missing_groups(true);
                break;
                default:
                    return false;
                break;
            }

            return 0;
        }
    }


}
