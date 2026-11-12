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

defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Admin_Notice')) {
    // phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps -- Legacy class structure
    class TagGroups_Admin_Notice
    {
        public function __construct()
        {
        }

      /**
       * Add an admin notice to the queue
       *
       * @param string $type One of: error, info
       * @param string $content with HTML
       * @return void
       */
        public static function add($type, $content)
        {

            $notices = TagGroups_Options::get_option('tag_group_admin_notice', array());
    /**
            * Avoid duplicate entries
            */
            $found = false;
            foreach ($notices as $notice) {
                if ($notice['type'] == $type && $notice['content'] == $content) {
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $notices[] = array(
                'type' => $type,
                'content' => $content
                );
                TagGroups_Options::update_option('tag_group_admin_notice', $notices);
            }
        }


      /**
       * Check if an admin notice is pending and, if necessary, display it
       *
       * @param void
       * @return void
       */
        public static function display()
        {

            $notices = TagGroups_Options::get_option('tag_group_admin_notice', array());
            if (! empty($notices)) {
                foreach ($notices as $notice) {
                    if ('cache' == $notice['type']) {
                        $notice['type'] = 'info';
                        $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
                        $ajax_link = admin_url('admin-ajax.php?', $protocol);
                    } else {
                            $ajax_link = '';
                    }

              // wrap the message in <p></p> if not already a complex formatting
                    if (strpos('<p>', $notice['content']) === false) {
                        $notice['content'] = '<p>' . $notice['content'] . '</p>';
                    }

                    $view = new TagGroups_View('partials/admin_notice');
                    $view->set(array(
                    'ajax_link' => $ajax_link,
                    'notice'    => $notice,
                    ));
                    $view->render();
                }

                update_option('tag_group_admin_notice', array());
            }
        }
    }

}
