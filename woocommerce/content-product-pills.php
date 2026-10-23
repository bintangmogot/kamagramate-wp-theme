<?php
/**
 * Legacy entry point kept for third-party calls; the pack-size pills now live
 * in the shared product card.
 */

defined('ABSPATH') || exit;

wc_get_template_part('content', 'product');
