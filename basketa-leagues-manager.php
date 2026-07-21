<?php
/**
 * Plugin Name: Basketa Leagues Manager
 * Description: Manage Basketball Leagues, Ticker & Standings from API Sports
 * Version: 3.0.0
 * Author: Efi Kakouni
 */

defined('ABSPATH') || exit;

define('BLM_VERSION', '3.0.0');
define('BLM_PATH', plugin_dir_path(__FILE__));
define('BLM_URL', plugin_dir_url(__FILE__));

require_once BLM_PATH . 'includes/Core/Loader.php';

new BLM_Loader();