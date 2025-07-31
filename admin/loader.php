<?php
	require_once __DIR__ . '/includes/menu.php';

	add_action('admin_enqueue_scripts', function () {
		wp_enqueue_script(
				'acfai-admin-ui',
				plugins_url('assets/js/languages.js', __FILE__),
				[],
				null,
				true
		);

		wp_enqueue_style(
				'acfai-style',
				plugins_url('assets/css/languages.css', __FILE__)
		);
	});
