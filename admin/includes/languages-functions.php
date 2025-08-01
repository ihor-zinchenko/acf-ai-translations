<?php
	if (!defined('ABSPATH')) {
		exit;
	}
	
	function acfai_get_flag_url(string $code): string {
		if (defined('ACFAI_ALL_LANGUAGES') && isset(ACFAI_ALL_LANGUAGES[$code]['flag'])) {
			return plugins_url(ACFAI_ALL_LANGUAGES[$code]['flag'], ACFAI_PLUGIN_FILE);
		}
		return '';
	}
	
	function acfai_insert_default_language() {
		global $wpdb;
		$table = $wpdb->prefix . 'acfai_languages';
		
		if (!$wpdb->get_var("SELECT COUNT(*) FROM $table")) {
			$flag_url = acfai_get_flag_url('gb');
			$wpdb->insert($table, [
				'code' => 'en',
				'name' => 'English',
				'flag_url' => $flag_url,
				'is_default' => 1,
				'active' => 1,
				'position' => 0,
			]);
		}
	}
	
	function acfai_add_language() {
		if (!isset($_POST['acfai_add_language'])) {
			return ['success' => '', 'error' => ''];
		}
		
		global $wpdb;
		$table = $wpdb->prefix . 'acfai_languages';
		
		$code = sanitize_text_field($_POST['language_code'] ?? '');
		$name = ACFAI_ALL_LANGUAGES[$code]['name'] ?? '';
		$is_default = isset($_POST['is_default']) ? 1 : 0;
		
		$success = '';
		$error = '';
		
		if ($code && $name) {
			if ($is_default) {
				$wpdb->query("UPDATE $table SET is_default=0");
			}
			if (!$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE code=%s", $code))) {
				$flag_url = acfai_get_flag_url($code);
				$inserted = $wpdb->insert($table, [
					'code' => $code,
					'name' => $name,
					'flag_url' => $flag_url,
					'is_default' => $is_default,
					'active' => 1,
					'position' => 0,
				]);
				
				if ($inserted !== false) {
					$success = 'Language added.';
				} else {
					$error = 'Failed to add language to database.';
				}
			} else {
				$error = 'Language exists.';
			}
		} else {
			$error = 'Invalid language selection.';
		}
		
		return compact('success', 'error');
	}
	
	function acfai_set_default_language() {
		$success = '';
		$error = '';
		
		if (!isset($_POST['acfai_set_default_language'])) {
			return compact('success', 'error');
		}
		
		global $wpdb;
		$table = $wpdb->prefix . 'acfai_languages';
		
		$defaultCode = sanitize_text_field($_POST['acfai_set_default_language'] ?? '');
		
		if (!$defaultCode) {
			$error = 'No language code provided for default.';
			return compact('success', 'error');
		}
		
		$wpdb->query("UPDATE $table SET is_default=0");
		$updated = $wpdb->update($table, ['is_default' => 1], ['code' => $defaultCode]);
		
		if ($updated === false) {
			$error = 'Failed to update default language in the database.';
			return compact('success', 'error');
		}
		
		$success = 'Default language updated.';
		return compact('success', 'error');
	}