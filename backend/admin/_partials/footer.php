	</main>
</div>
<script>
	// mobile sidebar drawer (plain JS, no dependencies)
	(function () {
		var toggle = document.querySelector('[data-side-toggle]');
		var side = document.querySelector('[data-side]');
		var backdrop = document.querySelector('[data-side-backdrop]');
		if (!toggle || !side) return;
		function close() {
			side.classList.remove('is-open');
			document.body.classList.remove('nav-open');
			toggle.setAttribute('aria-expanded', 'false');
		}
		toggle.addEventListener('click', function () {
			var open = side.classList.toggle('is-open');
			document.body.classList.toggle('nav-open', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		if (backdrop) backdrop.addEventListener('click', close);
	})();
</script>
</body>
</html>
