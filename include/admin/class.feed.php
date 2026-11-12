<?php

/**
* @package     Tag Groups
* @author      Christoph Amthor
* @copyright   2018 Christoph Amthor (@ Chatty Mango, chattymango.com)
* @license     GPL-3.0+
*/

/**
* Inspiration from: https://bavotasan.com/2010/display-rss-feed-with-php/
*/

defined('ABSPATH') || exit;

if (! class_exists('TagGroups_Feed')) {
    // phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ValidClassName.NotCamelCaps -- Legacy class structure
    class TagGroups_Feed
    {
          private $dom_doc;
        private $url;
        private $posts_url;
        private $feed = array();
        private $cache;
        private $debug = false;
        private $limit = 5;
        private $amount = 5;
        public $expired = false;


          /**
          * instantiation of DOMDocument and ChattyMango_Cache
          *
          * @param void
          * @return void
          */
        public function __construct()
        {

        // experimental solution for "DOMDocument::load(): I/O warning : failed to load external entity" server errors
            if (defined('PHP_VERSION_ID') && PHP_VERSION_ID < 80000 && function_exists('libxml_disable_entity_loader')) {
                libxml_disable_entity_loader(false);
            }

            $this->dom_doc = new DOMDocument();
            if (class_exists('TagGroups_Object_Cache')) {
                $this->cache = new TagGroups_Object_Cache();
                $this->cache
                      ->type(TagGroups_Object_Cache::WP_TRANSIENTS) // Don't use here user settings
                      ->lifetime(60 * 60 * 6);
            }
        }


      /**
      * Turns debugging on.
      *
      * @param void
      * @return object $this
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public function set_debug($debug = true)
        {

            $this->debug = $debug;
            return $this;
        }


      /**
      * Sets length of description
      *
      * @param int $limit
      * @return object $this
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public function set_limit($length)
        {

            $this->limit = $length;
            return $this;
        }


      /**
      * Sets max number of items
      *
      * @param int $amount
      * @return object $this
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public function set_amount($amount)
        {

            $this->amount = $amount;
            return $this;
        }


      /**
      * sets the URL where we can find the feed
      *
      * @param string $url
      * @return object $this
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public function set_url($url)
        {

            $this->url = $url;
            if (isset($this->cache)) {
                $this->cache->key(md5($url));
            }

            return $this;
        }


      /**
      * sets the URL where we can find the posts
      *
      * @param string $posts_url
      * @return object $this
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public function set_posts_url($posts_url)
        {

            $this->posts_url = $posts_url;
            return $this;
        }


      /**
       * Shortcode to return the HTML, cached or live
       *
       * @return string
       */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public function get_html()
        {

            $html = $this->cache_get();
            if (empty($html)) {
                $html = $this->load()->parse()->render(true);
            }

            return $html;
        }

      /**
      * tries to read the rendered feed content from the cache, returns false if not possible
      *
      * @param void
      * @return bool|string
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        private function cache_get()
        {

            if (isset($this->cache)) {
                $data = $this->cache->get();
                $this->expired = $this->cache->expired;
                if ($data && $this->debug) {
                    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Debug logging
                    error_log('[Tag Groups] Feed items found in cache');
                }

                return $data;
            } else {
                return false;
            }
        }


      /**
      * saves rendered content to the cache
      *
      * @param string $data
      * @return bool success?
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        private function cache_set($data)
        {

            if (isset($this->cache)) {
                return $this->cache->set($data);
            } else {
                return false;
            }
        }


      /**
      * purges data (of this feed) from the cache
      *
      * @param void
      * @return object $this
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        public function cache_purge()
        {

            if (isset($this->cache)) {
                return $this->cache->purge();
            } else {
                return false;
            }
        }



      /**
      * loads the content from the feed
      *
      * @param string $url
      * @return object $this
      */
        private function load()
        {

            $options = array(
            'http' => array(
            'user_agent' => 'PHP libxml agent',
            'method' => 'GET',
            'timeout' => '5'
            )
            );
            $context = stream_context_create($options);
            libxml_set_streams_context($context);
            $this->dom_doc->load($this->url);
            $xml = $this->dom_doc->saveXML();
    // hack to make <media:content url="..." medium="image" /> available as new tag
            $xml = preg_replace('#<media:content\s+url="([^"]+)"\s+medium="image"\s*\/>#', '<mediacontent>$1</mediacontent>', $xml);
            $this->dom_doc->loadXML($xml);
            return $this;
        }


      /**
      * parses the feed data to retrieve items and relevant fields
      *
      * @param void
      * @return object $this
      */
        public function parse()
        {

            foreach ($this->dom_doc->getElementsByTagName('item') as $node) {
                $item = array (
                'title'     => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'desc'      => $node->getElementsByTagName('description')->item(0)->nodeValue,
                'link'      => $node->getElementsByTagName('link')->item(0)->nodeValue,
                'date'      => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                'image_src' => $node->getElementsByTagName('mediacontent')->item(0)->nodeValue
                );
    // multiple categories and tags
                $cats = $node->getElementsByTagName('category');
                $item['categories'] = array();

                for ($i = 0; $i < $cats->length; $i++) {
                    $item['categories'][] = $cats->item($i)->nodeValue;
                }

                array_push($this->feed, $item);
            }

            return $this;
        }


      /**
      * Renders the feed to HTML
      *
      * @param int $limit maximum number of feed items
      * @param bool $return true: return output as string, false: echo output
      * @return string
      */
        private function render($return = true)
        {

            $html = '';
            $date_format = get_option('date_format');
            $view = new TagGroups_View('partials/admin_feed_item');

            for ($x = 0; $x < $this->amount; $x++) {
                if (isset($this->feed[$x])) {
                    $title = str_replace(' & ', ' &amp; ', $this->feed[$x]['title']);
                    $link = $this->feed[$x]['link'];
                    $description = $this->truncate_string_at_word($this->feed[$x]['desc'], $this->limit);
                    $date = gmdate($date_format, strtotime($this->feed[$x]['date']));
                    $view->set(array(
                    'date'        => $date,
                    'link'        => esc_url($link),
                    'title'       => sanitize_text_field($title),
                    'description' => sanitize_text_field($description),
                    'image_src'   => esc_url($this->feed[$x]['image_src']),
                    ));
                    $html .= $view->return_html();
                // <p><small><b>' . sanitize_text_field( implode( ', ', $this->feed[$x]['categories'] ) ) . '</b></small></p>
                }
            }

            $posts_url_campagin = $this->posts_url . ( strpos($this->posts_url, '?') ? '&pk_campaign=rss' : '?pk_campaign=rss' );
            if (! empty($html)) {
                $view = new TagGroups_View('partials/admin_feed_footer');
                $view->set('link', esc_url($posts_url_campagin));
                $html .= $view->return_html();
                $this->cache_set($html);
            } else {
            /*
                * Fallback: Ask user to go directly to the posts.
                  */
                  $view = new TagGroups_View('partials/admin_feed_fallback');
                $view->set(array(
                'posts_url_campaign'  => esc_url($posts_url_campagin),
                'posts_url'           => $this->posts_url
                ));
                $html = $view->return_html();
                TagGroups_Error::log('[Tag Groups] Feed is empty: ' . $this->url);
            }

            if ($return) {
                return $html;
            } else {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML already escaped in view
                echo $html;
            }
        }


      /**
      *
      *
      * modified from https://wp-mix.com/php-truncate-text-word/
      */
        // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Legacy method naming
        private function truncate_string_at_word($string, $limit, $break = ". ", $pad = " ...")
        {

            if (mb_strlen($string) <= $limit) {
                return $string;
            }

            if (false !== ($max = mb_strpos($string, $break, $limit))) {
                if ($max < mb_strlen($string) - 1) {
                    $string = mb_substr($string, 0, $max) . $pad;
                }
            }

            return $string;
        }
    }
    // phpcs:ignore PSR12.Classes.ClosingBrace.StatementAfter -- Legacy code style


}
