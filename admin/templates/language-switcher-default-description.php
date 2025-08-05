<?php
	if (!defined('ABSPATH')) exit;
?>

<div class="acfai-language-switcher__title" id="acfai-language-switcher__content">
	<?php include plugin_dir_path(__FILE__) . 'language-switcher.php'; ?>
</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		const switcher = document.getElementById('acfai-language-switcher__content');
		const postdivrich = document.getElementById('wp-content-editor-container');

		if (switcher && postdivrich && postdivrich.parentNode) {
			switcher.remove();

			postdivrich.parentNode.insertBefore(switcher, postdivrich);
		}
	});
</script>