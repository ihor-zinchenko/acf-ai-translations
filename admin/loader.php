<?php
	require_once __DIR__ . '/includes/menu.php';
	define('ACFAI_PLUGIN_FILE', dirname(__DIR__) . '/acf-ai-translations.php');

	add_action('admin_enqueue_scripts', function () {
		if (!isset($_GET['page']) || strpos($_GET['page'], 'acfai') !== 0) return;
		
		wp_enqueue_style('normalize', 'https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css');
		wp_enqueue_style('mdc-css', 'https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css');
		wp_enqueue_script('mdc-js', 'https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js', [], null, true);

		wp_enqueue_style(
				'acfai-languages-style',
				plugins_url('admin/assets/css/languages.css', ACFAI_PLUGIN_FILE)
		);

		wp_enqueue_script(
				'acfai-admin-ui',
				plugins_url('admin/assets/js/languages.js', ACFAI_PLUGIN_FILE),
				[],
				null,
				true
		);
	});