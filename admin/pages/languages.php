<?php
	require_once plugin_dir_path(__FILE__) . '/../includes/languages-list.php';
	require_once plugin_dir_path(__FILE__) . '/../includes/languages-functions.php';
	
	global $wpdb;
	$table = $wpdb->prefix . 'acfai_languages';
	$error = '';
	$success = '';
	
	if (!$wpdb->get_var("SELECT COUNT(*) FROM $table")) {
		acfai_insert_default_language();
	}
	if ($_SERVER['REQUEST_METHOD']) {
		$result = ['success' => '', 'error' => ''];
		
		if (isset($_POST['acfai_add_language'])) {
			$result = acfai_add_language();
		}
		
		if (isset($_POST['acfai_set_default_language'])) {
			$result = acfai_set_default_language();
		}
		
		if (isset($_POST['acfai_toggle_active_language'])) {
			$result = acfai_toggle_active_language();
		}
		
		$success = $result['success'] ?? '';
		$error = $result['error'] ?? '';
	}
	
	$langs = $wpdb->get_results("SELECT * FROM $table ORDER BY name ASC, position ASC");
?>

	<div class="acfai-page">
		<div class="acfai-page__title">
			<div class="acfai-page__title-text">
				<h1><?php esc_html_e('Languages', 'acf-ai-translations'); ?></h1>
			</div>
			
			<?php if ($success): ?>
				<div class="acfai-page__success">
					<div class="notice notice-success"><?= $success ?></div>
				</div>
			<?php endif; ?>
			<?php if ($error): ?>
				<div class="acfai-page__error">
					<div class="notice notice-error"><?= $error ?></div>
				</div>
			<?php endif; ?>
		</div>

		<div class="acfai-page__section">
			<form
					method="post"
					id="acfai-lang-form"
					class="acfai-languages-add"
			>
				<div class="acfai-languages-add__item acfai-languages-add__select">
					<div class="acfai-languages-add__label">
						<label for="acfai-lang-select">
							<?php esc_html_e('Select language', 'acf-ai-translations'); ?>
						</label>
					</div>

					<div class="acfai-languages-add__field">
						<select
								id="acfai-lang-select"
								name="language_code"
								style="width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px;" required
						>
							<option value=""></option>
							<?php foreach (ACFAI_ALL_LANGUAGES as $code => $data): ?>
								<option value="<?= esc_attr($code) ?>">
									<?= esc_html($data['name']) ?>(<?= esc_html($code) ?>)
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="acfai-languages-add__item acfai-languages-add__default">
					<div class="acfai-languages-add__label">
						<label for="switch-default">
							<?php esc_html_e('Default', 'acf-ai-translations'); ?>
						</label>
					</div>
					<div class="acfai-languages-add__field">
						<input type="checkbox" id="switch-default" name="is_default">
					</div>
				</div>
				<div class="acfai-languages-add__item acfai-languages-add__button">
					<button
							type="submit"
							name="acfai_add_language"
							class="acfai-button acfai-button__blue"
					>
						<?php esc_html_e('Add', 'acf-ai-translations'); ?>
					</button>
				</div>
			</form>
		</div>

		<div class="acfai-languages__table">
			<div class="acfai-languages__table-title">
				<h2><?php esc_html_e('Existing languages', 'acf-ai-translations'); ?></h2>
			</div>
			<div class="acfai-page__section">
				<table class="mdc-data-table__table widefat fixed striped">
					<thead>
					<tr>
						<th style="width: 40px;">Flag</th>
						<th style="width: 200px;">Name</th>
						<th style="width: 50px;">Code</th>
						<th>Default</th>
						<th>Active</th>
					</tr>
					</thead>
					<tbody class="mdc-data-table__content">
					<?php foreach ($langs as $lang): ?>
						<tr class="mdc-data-table__row<?= $lang->is_default ? ' mdc-data-table__row--selected' : '' ?>">
							<td>
								<?php if (!empty($lang->flag_url)): ?>
									<img src="<?= esc_url($lang->flag_url) ?>" width="40" height="26" alt="<?= esc_attr($lang->name) ?>">
								<?php endif; ?>
							</td>
							<td><?= esc_html($lang->name) ?></td>
							<td><?= esc_html($lang->code) ?></td>
							<td>
								<?php if (!$lang->is_default): ?>
									<form method="post" style="display:inline;">
										<input type="hidden" name="acfai_set_default_language" value="<?= esc_attr($lang->code) ?>">
										<button type="submit" class="button-link">Set Default</button>
									</form>
								<?php else: ?>
									✅
								<?php endif; ?>
							</td>
							<td>
								<form method="post" style="display:inline;">
									<input type="hidden" name="language_code" value="<?= esc_attr($lang->code) ?>">
									<input type="hidden" name="acfai_toggle_active_language" value="1">
									<input type="checkbox" name="toggle_active" onchange="this.form.submit()" <?= $lang->active ? 'checked' : '' ?>>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php
	add_action('admin_footer', function () {
		if (!isset($_GET['page']) || $_GET['page'] !== 'acfai-languages') return;
		?>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script>
      document.addEventListener('DOMContentLoaded', () => {
        if (window.mdc) mdc.autoInit();
        jQuery('#acfai-lang-select').select2({
          placeholder: '<?php esc_attr_e('Select a language...', 'acf-ai-translations'); ?>',
          width: '100%',
          allowClear: true
        });
      });
		</script>
		<?php
	});
?>