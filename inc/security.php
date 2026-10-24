<?php
/**
 * Security hardening that belongs to the theme.
 * - XML-RPC off (not used; common brute-force target).
 * - User listing endpoints hidden from logged-out visitors (stops username harvesting).
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', static function ($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});
add_filter('pings_open', '__return_false', 20);

// Answer xmlrpc.php requests with 403 instead of the XML-RPC server.
add_action('init', static function () {
    if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
        status_header(403);
        exit;
    }
}, 0);

// Remove /wp/v2/users routes for anyone who cannot list users.
add_filter('rest_endpoints', static function ($endpoints) {
    if (current_user_can('list_users')) {
        return $endpoints;
    }
    foreach (array_keys($endpoints) as $route) {
        if (strpos($route, '/wp/v2/users') === 0) {
            unset($endpoints[$route]);
        }
    }
    return $endpoints;
});
