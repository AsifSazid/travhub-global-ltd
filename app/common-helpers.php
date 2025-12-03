<?php

use Carbon\Carbon;

/**
 * Safely return a value or empty string
 */
if (!function_exists('safe')) {
    function safe($value, $default = '')
    {
        return isset($value) ? htmlspecialchars($value) : $default;
    }
}

/**
 * Format currency with comma
 * Example: 20000 → 20,000.00
 */
if (!function_exists('currency_format')) {
    function currency_format($amount, $symbol = '৳')
    {
        if (!is_numeric($amount)) return $amount;

        return $symbol . ' ' . number_format((float)$amount, 2, '.', ',');
    }
}

/**
 * Format number compactly
 * 1500 → 1.5K
 * 3000000 → 3M
 */
if (!function_exists('short_number')) {
    function short_number($n)
    {
        if ($n < 900) {
            $n = number_format($n);
        } elseif ($n < 900000) {
            $n = number_format($n / 1000, 1) . 'K';
        } elseif ($n < 900000000) {
            $n = number_format($n / 1000000, 1) . 'M';
        } else {
            $n = number_format($n / 1000000000, 1) . 'B';
        }
        return $n;
    }
}

/**
 * Convert a string to uppercase safely
 */
if (!function_exists('upper')) {
    function upper($value)
    {
        return strtoupper(safe($value));
    }
}
