<?php if (!defined('ABSPATH')) exit; ?>

<div class="acfai-lang-switcher" style="margin-bottom: 6px;">
	<label style="font-weight: 600; margin-right: 8px;">
		<?= esc_html__('Select language for translations:', 'acf-ai-translations') ?>
	</label>
	<select class="acfai-lang-switcher-select" style="padding: 4px; min-width: 140px;">
		<?php foreach (ACFAI_ALL_LANGUAGES as $code => $data): ?>
			<option value="<?= esc_attr($code) ?>"><?= esc_html($data['name']) ?></option>
		<?php endforeach; ?>
	</select>
</div>