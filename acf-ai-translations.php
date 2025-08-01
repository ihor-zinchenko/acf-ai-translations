<?php
	/*
	Plugin Name: ACF: AI Translations
	Description: Adds multilingual support for via AI and manual input.
	Version: 0.0.1
	Author: Ihor Zinchenko
	*/
	
	if (!defined('ABSPATH')) {
		exit;
	}
	
	define('ACFAI_PLUGIN_FILE', __FILE__);
	
	register_activation_hook(__FILE__, 'acfai_on_activate');
	
	function acfai_on_activate() {
		require_once plugin_dir_path(__FILE__) . 'loader.php';
		if (function_exists('acfai_create_tables')) {
			acfai_create_tables();
		}
	}
	
	
	require_once __DIR__ . '/loader.php';