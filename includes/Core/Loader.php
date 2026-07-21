<?php

if (!defined('ABSPATH')) {
    exit;
}

class BLM_Loader
{
    public function __construct()
    {
        $this->loadFiles();
        $this->boot();
    }

    /**
     * Load all plugin classes.
     */
    private function loadFiles(): void
    {
        /*
        |--------------------------------------------------------------------------
        | API
        |--------------------------------------------------------------------------
        */

        require_once BLM_PATH . 'includes/class-api.php';

        /*
        |--------------------------------------------------------------------------
        | Core
        |--------------------------------------------------------------------------
        */

        require_once BLM_PATH . 'includes/class-admin.php';
        require_once BLM_PATH . 'includes/class-shortcode.php';
        require_once BLM_PATH . 'includes/class-ticker.php';
        require_once BLM_PATH . 'includes/class-standings.php';
        require_once BLM_PATH . 'includes/Core/Assets.php';

        /*
        |--------------------------------------------------------------------------
        | Helpers
        |--------------------------------------------------------------------------
        */

        require_once BLM_PATH . 'includes/Helpers/class-helper.php';

        /*
        |--------------------------------------------------------------------------
        | Standings Module
        |--------------------------------------------------------------------------
        */

        require_once BLM_PATH . 'includes/Modules/Standings/Repository.php';
        require_once BLM_PATH . 'includes/Modules/Standings/Season.php';
        require_once BLM_PATH . 'includes/Modules/Standings/Renderer.php';
        require_once BLM_PATH . 'includes/Modules/Standings/Ajax.php';
        require_once BLM_PATH . 'includes/Modules/Standings/Frontend.php';
    }

    /**
     * Boot plugin.
     */
    private function boot(): void
    {
        new BLM_API();

        new BLM_Admin();

        new BLM_Shortcode();

        new BLM_Ticker();

        new BLM_Standings();

    }
}