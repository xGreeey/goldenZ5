<?php

declare(strict_types=1);

/**
 * Session bootstrap: secure cookie params and session start.
 * Include this before any output. Session cookie is shared across tabs (same browser profile).
 */

if (session_status() !== PHP_SESSION_NONE) {
    return;
}

// Session hardening (before session_start)
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');

// App deployed on local network with HTTPS only — session cookie must be Secure
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443');

// Cookie params: HttpOnly, SameSite Lax, Secure (true when HTTPS; required for local HTTPS)
if (PHP_VERSION_ID >= 70300) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
} else {
    session_set_cookie_params(0, '/', '', $isHttps, true);
}

$sessionPath = dirname(__DIR__) . '/storage/sessions';
if (!is_dir($sessionPath)) {
    @mkdir($sessionPath, 0755, true);
}
if (is_dir($sessionPath)) {
    session_save_path($sessionPath);
}

session_start();
