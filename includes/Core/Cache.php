<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_Cache
{
    /**
     * Get cached value or generate it.
     */
    public static function remember(
        string $key,
        int $expiration,
        callable $callback
    ) {
        $cached = get_transient($key);

        if ($cached !== false) {
            return $cached;
        }

        $value = $callback();

        set_transient(
            $key,
            $value,
            $expiration
        );

        return $value;
    }

    /**
     * Delete cache.
     */
    public static function forget(string $key): void
    {
        delete_transient($key);
    }

    /**
     * Flush all Basketa cache.
     * (Θα το επεκτείνουμε αργότερα.)
     */
    public static function flush(): void
    {
        // TODO
    }
}