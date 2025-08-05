<?php
	if (!defined('ABSPATH')) exit;
	
	
	$acfai_available_langs = acfai_get_active_languages();
	$acfai_default_lang = acfai_get_default_language_code();
?>

<div class="acfai-language-switcher__title" id="acfai-language-switcher__title">
	<?php include plugin_dir_path(__FILE__) . 'language-switcher.php'; ?>
</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		const switcher = document.getElementById('acfai-language-switcher__title');
		const titleWrap = document.getElementById('titlewrap');
		const titleInput = document.getElementById('title');

		if (switcher && titleWrap && titleInput && titleInput.parentNode) {
			switcher.remove();

			titleInput.parentNode.insertBefore(switcher, titleInput);
		}
	});

	jQuery(document).ready(() => {
		const $switcher = jQuery('#acfai-language-switcher__title');
		const $titleInput = jQuery('#title');

		let translations = window.acfaiTranslations || {};
		const defaultLang = '<?= esc_js($acfai_default_lang) ?>';
		let currentLang = defaultLang;

		if (translations && Object.keys(translations).length > 0) {
			if (translations[defaultLang] && translations[defaultLang].title) {
				$titleInput.val(translations[defaultLang].title);
			}
		}

		if ($switcher.length && $titleInput.length) {
			$switcher.detach();
			$titleInput.parent().prepend($switcher);
		}

		$switcher.find('button').removeClass('active');
		$switcher.find(`button[data-lang="${defaultLang}"]`).addClass('active');

		$titleInput.on('input', function () {
			translations[currentLang] = translations[currentLang] || {};
			translations[currentLang].title = jQuery(this).val();
		});

		$switcher.find('button').on('click', function () {
			const selectedLang = jQuery(this).data('lang');
			if (!selectedLang || selectedLang === currentLang) return;

			translations[currentLang] = translations[currentLang] || {};
			translations[currentLang].title = $titleInput.val();

			currentLang = selectedLang;

			if (translations[currentLang] && Object.keys(translations[currentLang]).length > 0) {
				$titleInput.val(translations[currentLang].title || '');
			}

			$switcher.find('button').removeClass('active');
			jQuery(this).addClass('active');
		});

		let $hiddenTranslations = jQuery('<input>', {
			type: 'hidden',
			id: 'acfai-translations',
			name: 'acfai_translations',
			value: JSON.stringify(translations),
		});
		$titleInput.closest('form').append($hiddenTranslations);

		$titleInput.closest('form').on('submit', function () {
			$hiddenTranslations.val(JSON.stringify(translations));
		});
	});
</script>