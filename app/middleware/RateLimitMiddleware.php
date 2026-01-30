<?php

declare(strict_types=1);

/**
 * Rate limit middleware: login throttling (max 5 attempts per 10 minutes per username).
 * Data stored in storage/cache/ratelimit.json. Create directory if missing.
 */
class RateLimitMiddleware
{
    /** Max login attempts in the window */
    public static int $maxAttempts = 5;

    /** Window length in seconds (10 minutes) */
    public static int $windowSeconds = 600;

    private static function getStoragePath(): string
    {
        $appRoot = dirname(__DIR__, 2);
        $dir = $appRoot . '/storage/cache';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        return $dir . '/ratelimit.json';
    }

    private static function read(): array
    {
        $path = self::getStoragePath();
        if (!is_file($path)) {
            return [];
        }
        $raw = @file_get_contents($path);
        if ($raw === false) {
            return [];
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private static function write(array $data): void
    {
        $path = self::getStoragePath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
    }

    /**
     * Check if login is allowed for this username. Returns true if allowed, false if rate limited.
     */
    public static function checkLogin(string $username): bool
    {
        $key = self::normalizeKey($username);
        $data = self::read();
        $now = time();
        if (!isset($data[$key]) || !is_array($data[$key])) {
            return true;
        }
        $entry = $data[$key];
        $windowStart = $now - self::$windowSeconds;
        $attempts = array_filter($entry['attempts'] ?? [], static function (int $t) use ($windowStart) {
            return $t > $windowStart;
        });
        return count($attempts) < self::$maxAttempts;
    }

    /**
     * Record a failed login attempt for this username.
     */
    public static function recordFail(string $username): void
    {
        $key = self::normalizeKey($username);
        $data = self::read();
        $now = time();
        if (!isset($data[$key]) || !is_array($data[$key])) {
            $data[$key] = ['attempts' => []];
        }
        $data[$key]['attempts'][] = $now;
        $data[$key]['attempts'] = array_slice($data[$key]['attempts'], -self::$maxAttempts * 2);
        self::write($data);
    }

    /**
     * Clear rate limit for this username (e.g. after successful login).
     */
    public static function clear(string $username): void
    {
        $key = self::normalizeKey($username);
        $data = self::read();
        unset($data[$key]);
        self::write($data);
    }

    private static function normalizeKey(string $username): string
    {
        return 'login_' . md5(strtolower(trim($username)));
    }
}
