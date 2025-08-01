<?php
	if (!defined('ABSPATH')) {
		exit;
	}
	
	global $wpdb;
	$table = $wpdb->prefix . 'acfai_languages';
	$success = '';
	$error = '';
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['set_default_language'])) {
			$defaultCode = sanitize_text_field($_POST['default_language'] ?? '');
			if ($defaultCode) {
				$wpdb->query("UPDATE $table SET is_default=0");
				$wpdb->update(
					$table,
					['is_default' => 1],
					['code' => $defaultCode]
				);
				$success = 'Default language updated.';
			}
		}
		
		if (isset($_POST['acfai_add_language'])) {
			$code = sanitize_text_field($_POST['language_code'] ?? '');
			$name = ACFAI_ALL_LANGUAGES[$code]['name'] ?? '';
			$is_default = isset($_POST['is_default']) ? 1 : 0;
			
			if ($code && $name) {
				if ($is_default) {
					$wpdb->query("UPDATE $table SET is_default=0");
				}
				if (!$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE code=%s", $code))) {
					$wpdb->insert($table, [
						'code' => $code,
						'name' => $name,
						'flag_url' => ACFAI_ALL_LANGUAGES[$code]['flag'] ?? '',
						'is_default' => $is_default,
						'active' => 1,
						'position' => 0
					]);
					$success = 'Language added.';
				} else {
					$error = 'Language exists.';
				}
			} else {
				$error = 'Invalid.';
			}
		}
	}