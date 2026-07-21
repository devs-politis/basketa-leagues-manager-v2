<?php
/**
 * Plugin Name: Basketa Leagues Manager
 * Description: Manage Basketball Leagues, Ticker & Standings from API Sports
 * Version: 3.0.0
 * Author: Efi Kakouni
 */

defined('ABSPATH') || exit;

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

define('BLM_VERSION', '3.0.0');
define('BLM_PATH', plugin_dir_path(__FILE__));
define('BLM_URL', plugin_dir_url(__FILE__));

/*
|--------------------------------------------------------------------------
| Core Classes
|--------------------------------------------------------------------------
*/

require_once BLM_PATH . 'includes/class-api.php';
require_once BLM_PATH . 'includes/class-admin.php';
require_once BLM_PATH . 'includes/class-shortcode.php';
require_once BLM_PATH . 'includes/Helpers/class-helper.php';

/*
|--------------------------------------------------------------------------
| Modules
|--------------------------------------------------------------------------
*/

require_once BLM_PATH . 'includes/class-ticker.php';
require_once BLM_PATH . 'includes/class-standings.php';
require_once BLM_PATH . 'includes/class-settings.php';
require_once BLM_PATH . 'includes/Modules/Standings/Frontend.php';

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
*/

new BLM_API();
new BLM_Admin();
new BLM_Shortcode();
new BLM_Ticker();
new BLM_Standings();
new BLM_Settings();