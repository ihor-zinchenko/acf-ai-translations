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
	
	use function is_plugin_active;
	
	add_action('admin_init', function() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( !is_plugin_active('classic-editor/classic-editor.php') ) {
			deactivate_plugins(plugin_basename(__FILE__));
			add_action('admin_notices', function() {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php esc_html_e('ACF: AI Translations requires Classic Editor plugin. The plugin has been deactivated.', 'acf-ai-translations'); ?></p>
				</div>
				<?php
			});
		}
	});
	
	define('ACFAI_PLUGIN_FILE', __FILE__);
	
	register_activation_hook(__FILE__, 'acfai_on_activate');
	
	function acfai_on_activate() {
		require_once plugin_dir_path(__FILE__) . 'loader.php';
		if (function_exists('acfai_create_tables')) {
			acfai_create_tables();
		}
	}
	
	
	require_once __DIR__ . '/loader.php';