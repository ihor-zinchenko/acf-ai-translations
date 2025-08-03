<?php
	function acfai_get_all_languages() {
		static $langs = null;
		if ($langs === null) {
			$langs = require plugin_dir_path(__FILE__) . '/admin/includes/languages-list.php';
		}
		return $langs;
	}
	
	function acfai_get_active_languages() {
		global $wpdb;
		$table = $wpdb->prefix . 'acfai_languages';
		$results = $wpdb->get_results("SELECT * FROM $table WHERE active = 1 ORDER BY is_default DESC, position ASC");
		
		$languages = [];
		foreach ($results as $lang) {
			$languages[$lang->code] = [
				'name' => $lang->name,
				'flag_url' => $lang->flag_url,
				'is_default' => (bool)$lang->is_default,
				'active' => (bool)$lang->active,
				'position' => intval($lang->position),
			];
		}
		
		return $languages;
	}
	
	function acfai_get_default_language() {
		global $wpdb;
		$table = $wpdb->prefix . 'acfai_languages';
		return $wpdb->get_row("SELECT * FROM $table WHERE is_default = 1 LIMIT 1");
	}
	
	function acfai_get_default_language_code(): string {
		global $wpdb;
		$table = $wpdb->prefix . 'acfai_languages';
		return $wpdb->get_var("SELECT code FROM $table WHERE is_default=1 LIMIT 1");
	}