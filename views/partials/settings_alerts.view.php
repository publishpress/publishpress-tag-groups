<?php defined('ABSPATH') || exit;
?>
<div class="tg-alert-rounded">
  <ul style="list-style-type: disc; margin-left: 25px;">
    <li><?php echo implode('</li><li>', array_map('esc_html', $alerts)) ?></li>
  </ul>
</div>
