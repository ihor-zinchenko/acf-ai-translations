<?php
	if (!defined('ABSPATH')) exit;
	
	add_action('enqueue_block_editor_assets', function () {
//		wp_enqueue_script(
//			'acfai-gutenberg-language-switcher',
//			plugins_url('admin/assets/js/gutenberg-language-switcher.js', ACFAI_PLUGIN_FILE),
//			['wp-plugins', 'wp-edit-post', 'wp-element', 'wp-data'],
//			filemtime(plugin_dir_path(ACFAI_PLUGIN_FILE) . '/admin/assets/js/gutenberg-language-switcher.js'),
//			true
//		);
		
		wp_localize_script('acfai-gutenberg-language-switcher', 'acfaiData', [
			'pluginUrl' => plugins_url('', __DIR__),
			'languages' => acfai_get_active_languages(),
			'defaultLanguageCode' => acfai_get_default_language_code(),
		]);
		
		wp_localize_script('acfai-gutenberg-language-switcher', 'acfaiLanguages', [
			'languages' => acfai_get_active_languages(),
		]);
	});
	
	add_filter('acf/field_wrapper_attributes', function ($wrapper, $field) {
		$excluded_types = [
			'true_false', 'checkbox', 'file', 'image', 'gallery',
			'radio', 'button_group', 'taxonomy', 'user', 'relationship',
			'oembed', 'google_map', 'color_picker'
		];
		
		if (in_array($field['type'], $excluded_types, true)) {
			return $wrapper;
		}
		
		if (isset($wrapper['class'])) {
			$wrapper['class'] .= ' acfai-translatable';
		} else {
			$wrapper['class'] = 'acfai-translatable';
		}
		
		return $wrapper;
	}, 10, 2);
	
	add_action('acf/render_field', function ($field) {
		$excluded_types = [
			'true_false', 'checkbox', 'file', 'image', 'gallery',
			'radio', 'button_group', 'taxonomy', 'user', 'relationship',
			'oembed', 'google_map', 'color_picker'
		];
		
		if (in_array($field['type'], $excluded_types, true)) {
			return;
		}
		
		echo '<div class="acfai-extra-element">Додатковий контент</div>';
	});
	
	add_action('edit_form_after_title', function ($post) {
		if (!in_array($post->post_type, ['post', 'page'])) return;
		
		include plugin_dir_path(__FILE__) . '../admin/templates/language-switcher-default-title.php';
	});
//
//	add_action('category_edit_form_fields', function($term) {
//		include plugin_dir_path(__FILE__) . '/admin/templates/language-switcher-taxonomy.php';
//	});
//
//	add_action('category_edit_form_fields', function($term) {
//		include plugin_dir_path(__FILE__) . '/admin/templates/language-switcher-taxonomy.php';
//	});
	
	add_action('edit_form_after_editor', function ($post) {
		include plugin_dir_path(__DIR__) . 'admin/templates/language-switcher-default-description.php';
	});
	
	add_action('save_post', 'acfai_save_post_translations', 20, 3);
	
	function acfai_save_post_translations($post_id, $post, $update) {
		global $wpdb;
		$table = $wpdb->prefix . 'acfai_translations';
		
		if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id) || $post->post_status === 'auto-draft') {
			return;
		}
		
		// Переклади передаються через $_POST, наприклад:
		// $_POST['acfai_translation']['title']['uk'], $_POST['acfai_translation']['content']['uk'], $_POST['acfai_translation']['excerpt']['uk']
		
		if (empty($_POST['acfai_translation']) || !is_array($_POST['acfai_translation'])) {
			return;
		}
		
		$translations = $_POST['acfai_translation']; // Мульти-мовні дані
		
		$fields = ['title', 'content', 'excerpt'];
		$entity_type = 'post';
		
		foreach ($fields as $field_name) {
			if (empty($translations[$field_name]) || !is_array($translations[$field_name])) continue;
			
			foreach ($translations[$field_name] as $lang => $value) {
				$value = maybe_serialize($value);
				$field_key = ''; // Якщо треба, можна поставити унікальний ключ
				
				// Перевіряємо чи вже є запис
				$exists = $wpdb->get_var($wpdb->prepare(
					"SELECT id FROM $table WHERE entity_id=%d AND entity_type=%s AND field_name=%s AND lang=%s",
					$post_id, $entity_type, $field_name, $lang
				));
				
				if ($exists) {
					$wpdb->update($table, [
						'value' => $value,
						'updated_at' => current_time('mysql'),
					], ['id' => $exists]);
				} else {
					$wpdb->insert($table, [
						'entity_id' => $post_id,
						'entity_type' => $entity_type,
						'field_name' => $field_name,
						'field_key' => $field_key,
						'lang' => $lang,
						'value' => $value,
						'created_at' => current_time('mysql'),
						'updated_at' => current_time('mysql'),
					]);
				}
			}
		}
	}