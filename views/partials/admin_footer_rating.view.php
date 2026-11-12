<?php defined('ABSPATH') || exit;
?>
<p class="footer_st">
    <?php
    /* translators: 1: Opening TaxoPress link tag, 2: Closing link tag, 3: Plugin version. */
    printf(__('Thanks for using Tag Groups | %1$sTaxoPress.com%2$s | Version %3$s', 'tag-groups'), '<a target="_blank" href="https://taxopress.com/">', '</a>', esc_html(TAG_GROUPS_VERSION)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    ?>
</p>
