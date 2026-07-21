<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_Assets
{
    /**
     * Standings assets.
     */
    public static function standings(): void
    {
        wp_enqueue_style(
            'blm-standings',
            BLM_URL . 'assets/css/standings.css',
            [],
            BLM_VERSION
        );

        wp_enqueue_script(
            'blm-standings',
            BLM_URL . 'assets/js/standings.js',
            [],
            BLM_VERSION,
            true
        );

        wp_localize_script(
            'blm-standings',
            'blmStandings',
            [
                'ajax'  => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('blm_standings'),
            ]
        );
    }

    /**
     * Ticker assets.
     */
    public static function ticker(): void
    {
        wp_enqueue_style(
            'blm-ticker',
            BLM_URL . 'assets/css/ticker.css',
            [],
            BLM_VERSION
        );

        wp_enqueue_script(
            'blm-ticker',
            BLM_URL . 'assets/js/ticker.js',
            [],
            BLM_VERSION,
            true
        );
    }
}