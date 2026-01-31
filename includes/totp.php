<?php

declare(strict_types=1);

/**
 * TOTP verification for two-factor authentication.
 * Base32 decode + HMAC-SHA1 HOTP/TOTP (RFC 6238).
 */

if (!function_exists('totp_base32_decode')) {
    /**
     * Base32 decode (RFC 3548) — uppercase only.
     *
     * @return string|false Raw binary or false on invalid input
     */
    function totp_base32_decode(string $data): string|false
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        // Normalize: only keep valid Base32 chars (RFC 3548). Handles DB encoding/BOM/whitespace.
        $data = preg_replace('/[^A-Za-z2-7]/', '', $data);
        $data = strtoupper($data);
        $v = 0;
        $vBits = 0;
        $output = '';
        for ($i = 0, $len = strlen($data); $i < $len; $i++) {
            $pos = strpos($alphabet, $data[$i]);
            if ($pos === false) {
                return false;
            }
            $v = ($v << 5) | $pos;
            $vBits += 5;
            while ($vBits >= 8) {
                $vBits -= 8;
                $output .= chr(($v >> $vBits) & 255);
                $v = $v & ((1 << $vBits) - 1);
            }
        }
        return $output;
    }
}

if (!function_exists('totp_verify')) {
    /**
     * Verify a 6-digit TOTP code. Allows ±3 time steps (90s) for clock skew (Google Authenticator).
     *
     * @param string $secretBase32 Base32-encoded secret (e.g. from users.two_factor_secret)
     * @param string $code         6-digit code from authenticator app
     * @return bool True if code is valid
     */
    function totp_verify(string $secretBase32, string $code): bool
    {
        $code = preg_replace('/\D/', '', $code);
        if (strlen($code) !== 6) {
            return false;
        }
        $secretBase32 = trim((string) $secretBase32);
        if ($secretBase32 === '') {
            return false;
        }
        $secret = totp_base32_decode($secretBase32);
        if ($secret === false || $secret === '') {
            return false;
        }
        $timeSlice = (int) floor(time() / 30);
        for ($i = -3; $i <= 3; $i++) {
            $counter = $timeSlice + $i;
            $expected = totp_hotp($secret, $counter);
            if ($expected !== null && hash_equals($expected, $code)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('totp_hotp')) {
    /**
     * HOTP(K, C) = Truncate(HMAC-SHA1(K, C)) mod 10^6 (RFC 4226).
     *
     * @return string|null 6-digit zero-padded string or null on failure
     */
    function totp_hotp(string $secret, int $counter): ?string
    {
        // RFC 4226: counter C is 8-byte big-endian
        $counterBytes = pack('NN', ($counter >> 32) & 0xFFFFFFFF, $counter & 0xFFFFFFFF);
        $hash = hash_hmac('sha1', $counterBytes, $secret, true);
        // HMAC-SHA1 is 20 bytes; we need at least offset+4 (offset ≤ 15) so 20 bytes is enough
        if ($hash === false || strlen($hash) < 20) {
            return null;
        }
        $offset = ord($hash[strlen($hash) - 1]) & 0x0f;
        $binary = (
            ((ord($hash[$offset]) & 0x7f) << 24)
            | ((ord($hash[$offset + 1]) & 0xff) << 16)
            | ((ord($hash[$offset + 2]) & 0xff) << 8)
            | (ord($hash[$offset + 3]) & 0xff)
        );
        $otp = $binary % 1000000;
        return str_pad((string) $otp, 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('totp_get_debug_info')) {
    /**
     * Return debug info for TOTP verification (only use when 2FA debug=1).
     * Helps compare server-side expected codes with the code from the app.
     *
     * @return array{secret_length: int, secret_preview: string, decode_ok: bool, decode_error: string|null, decoded_bytes: int, time_slice: int, server_time_utc: string, server_timestamp: int, expected_current: string|null, expected_prev: string|null, expected_next: string|null, code_entered: string, code_matches_current: bool, code_matches_prev: bool, code_matches_next: bool}
     */
    function totp_get_debug_info(string $secretBase32, string $codeEntered): array
    {
        $codeEntered = preg_replace('/\D/', '', $codeEntered);
        $secretBase32 = trim((string) $secretBase32);
        $secretLen = strlen($secretBase32);
        $preview = $secretLen > 10
            ? substr($secretBase32, 0, 4) . '...' . substr($secretBase32, -4)
            : ($secretLen > 0 ? str_repeat('*', $secretLen) : '(empty)');

        $decode_ok = false;
        $decode_error = null;
        $decoded_bytes = 0;
        $secret = null;

        if ($secretBase32 === '') {
            $decode_error = 'Secret from DB is empty.';
        } else {
            $secret = totp_base32_decode($secretBase32);
            if ($secret === false || $secret === '') {
                $decode_ok = false;
                $decode_error = 'Base32 decode failed (invalid characters or length).';
            } else {
                $decode_ok = true;
                $decoded_bytes = strlen($secret);
            }
        }

        $timeSlice = (int) floor(time() / 30);
        $expected_current = null;
        $expected_prev = null;
        $expected_next = null;
        if ($secret !== null && $secret !== '') {
            $expected_current = totp_hotp($secret, $timeSlice);
            $expected_prev = totp_hotp($secret, $timeSlice - 1);
            $expected_next = totp_hotp($secret, $timeSlice + 1);
        }

        return [
            'secret_length' => $secretLen,
            'secret_preview' => $preview,
            'decode_ok' => $decode_ok,
            'decode_error' => $decode_error,
            'decoded_bytes' => $decoded_bytes,
            'time_slice' => $timeSlice,
            'server_time_utc' => gmdate('Y-m-d H:i:s'),
            'server_timestamp' => time(),
            'expected_current' => $expected_current,
            'expected_prev' => $expected_prev,
            'expected_next' => $expected_next,
            'code_entered' => $codeEntered,
            'code_matches_current' => $expected_current !== null && hash_equals($expected_current, $codeEntered),
            'code_matches_prev' => $expected_prev !== null && hash_equals($expected_prev, $codeEntered),
            'code_matches_next' => $expected_next !== null && hash_equals($expected_next, $codeEntered),
        ];
    }
}
