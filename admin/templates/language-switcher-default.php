<?php
	if (!defined('ABSPATH')) exit;
	
	
	$acfai_available_langs = acfai_get_active_languages();
	$acfai_default_lang = acfai_get_default_language_code();
?>

<div class="acfai-lang-switcher" data-default-lang="<?= esc_attr($acfai_default_lang) ?>">
	<?php foreach ($acfai_available_langs as $lang): ?>
		<button type="button"
						data-lang="<?= esc_attr($lang['code']) ?>"
						title="<?= esc_attr($lang['label']) ?>"
						class="<?= $lang['code'] === $acfai_default_lang ? 'active' : '' ?>">
			<?php if (!empty($lang['flag_url'])): ?>
				<img src="<?= esc_url($lang['flag_url']) ?>" alt="<?= esc_attr($lang['code']) ?>" />
			<?php else: ?>
				<?= esc_html(strtoupper($lang['code'])) ?>
			<?php endif; ?>
			<?= esc_html($lang['label']) ?>
		</button>
	<?php endforeach; ?>
</div>