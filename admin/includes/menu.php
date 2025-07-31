<?php
	function acfai_render_languages_page() {
		require plugin_dir_path(__FILE__) . '../pages/languages.php';
	}

	function acfai_render_settings_page() {
		require plugin_dir_path(__FILE__) . '../pages/settings.php';
	}

	add_action('admin_menu', function () {
		$capability = 'manage_options';

		add_menu_page(
				__('ACF AI Translations', 'acf-ai-translations'),
				__('ACF AI', 'acf-ai-translations'),
				$capability,
				'acfai',
				'acfai_render_settings_page',
				'dashicons-translation',
				65
		);

		add_submenu_page(
				'acfai',
				__('Languages', 'acf-ai-translations'),
				__('Languages', 'acf-ai-translations'),
				$capability,
				'acfai-languages',
				'acfai_render_languages_page'
		);

		add_submenu_page(
				'acfai',
				__('Settings', 'acf-ai-translations'),
				__('Settings', 'acf-ai-translations'),
				$capability,
				'acfai',
				'acfai_render_settings_page'
		);

		remove_submenu_page('acfai', 'acfai');
	});