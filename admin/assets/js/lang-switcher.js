document.addEventListener('DOMContentLoaded', () => {
	const switcher = document.getElementById('acfai-lang-guttenberg-switcher');
	if (!switcher) return;

	const buttons = switcher.querySelectorAll('button');
	let currentLang = switcher.dataset.defaultLang;

	buttons.forEach(button => {
		button.addEventListener('click', () => {
			if (button.dataset.lang === currentLang) return;

			// Зняти активність з попередньої
			switcher.querySelector('button.active').classList.remove('active');
			// Додати активність до нової
			button.classList.add('active');
			currentLang = button.dataset.lang;

			// Вкладемо тут логіку оновлення полів ACF за мовою, наприклад, подія або виклик функції
			// Наприклад, window.dispatchEvent(new CustomEvent('acfai-lang-changed', {detail: currentLang}));
		});
	});
});