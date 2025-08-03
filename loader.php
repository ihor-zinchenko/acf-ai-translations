<?php
	
	if (!defined('ABSPATH')) {
		exit;
	}
	
	function acfai_create_tables() {
		global $wpdb;
		$collate = $wpdb->get_charset_collate();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		
		// translations
		$translationsTableName = $wpdb->prefix . 'acfai_translations';
		$translationsTableSql = "CREATE TABLE $translationsTableName (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		entity_id BIGINT UNSIGNED NOT NULL,
		entity_type VARCHAR(32) NOT NULL,
		field_name VARCHAR(255) NOT NULL,
		field_key VARCHAR(255) DEFAULT NULL,
		lang VARCHAR(10) NOT NULL,
		value LONGTEXT,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY unique_translation (entity_id, entity_type, field_name, lang),
		KEY idx_entity (entity_type, entity_id),
		KEY idx_lang (lang),
		KEY idx_field_name (field_name)
	) $collate;";
		dbDelta($translationsTableSql);
		
		// settings
		$settingsTableName = $wpdb->prefix . 'acfai_settings';
		$settingsTableSql = "CREATE TABLE $settingsTableName (
		id INT NOT NULL AUTO_INCREMENT,
		`key` VARCHAR(255) NOT NULL,
		`value` LONGTEXT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY `key` (`key`)
	) $collate;";
		dbDelta($settingsTableSql);
		
		// languages
		$languagesTableName = $wpdb->prefix . 'acfai_languages';
		$languagesTableSql = "CREATE TABLE $languagesTableName (
		id INT UNSIGNED NOT NULL AUTO_INCREMENT,
		code VARCHAR(10) NOT NULL UNIQUE,
		name VARCHAR(100) NOT NULL,
		flag_url VARCHAR(255) NULL,
		position INT UNSIGNED NOT NULL DEFAULT 0,
		active TINYINT(1) NOT NULL DEFAULT 1,
		is_default TINYINT(1) NOT NULL DEFAULT 0,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		KEY idx_active_pos (active, position)
	) $collate;";
		dbDelta($languagesTableSql);
	}
	
	add_action('admin_enqueue_scripts', function () {
		wp_enqueue_script(
			'acfai-admin-ui',
			plugins_url('assets/scripts.js', __FILE__),
			[],
			null,
			true
		);
		
		wp_enqueue_style(
			'acfai-style',
			plugins_url('assets/styles.css?t=2', __FILE__)
		);
		
		wp_enqueue_style(
			'acfai-language-switcher-style',
			plugins_url('admin/assets/css/language-switcher.css', __FILE__)
		);

//    $langs = get_field('languages', 'option') ?: [];
//    $default = get_field('default_lang', 'option') ?: 'uk';
//
//    wp_add_inline_script('acfai-admin-ui', 'window.acfaiAvailableLangs = ' . json_encode($langs) . ';');
//    wp_add_inline_script('acfai-admin-ui', 'window.acfaiDefaultLang = ' . json_encode($default) . ';');
	});
	
	require_once __DIR__ . '/functions.php';
	
	require_once __DIR__ . '/includes/helpers.php';
	require_once __DIR__ . '/includes/overrides.php';
	require_once __DIR__ . '/includes/translator.php';
	require_once __DIR__ . '/includes/api.php';
	require_once __DIR__ . '/includes/languages-router.php';
	
	require_once __DIR__ . '/admin/loader.php';
	
	require_once __DIR__ . '/includes/translations-hooks.php';