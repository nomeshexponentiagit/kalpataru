<?php
/** Shared blog shell — replicates the site's dark footer (including the
 *  Blog link in the Navigation column — footer only, per user request). */
?>
</main>
<footer class="footer theme-dark tone-ink">
	<div class="container">
		<div class="footer__top">
			<div class="footer__brand">
				<img class="footer__logo" src="/brand/logo.png" alt="Kalpataru Exhibition" width="324" height="81" />
				<p class="footer__tagline">Exhibition design &amp; build</p>
				<p class="footer__about">
					Kalpataru Exhibition designs, fabricates and installs exhibition stalls
					and pavilions across India and international markets — concept, build and
					installation under one roof.
				</p>
				<ul class="footer__contact">
					<li><a class="footer__link" href="tel:+919822000000">+91 98220 00000</a></li>
					<li><a class="footer__link" href="mailto:hello@kalpataru-exhibition.com">hello@kalpataru-exhibition.com</a></li>
				</ul>
			</div>

			<div class="footer__cols">
				<nav class="footer__col" aria-label="Footer navigation">
					<p class="footer__head">Navigation</p>
					<ul>
						<li><a class="footer__link" href="/work">Work</a></li>
						<li><a class="footer__link" href="/services">Services</a></li>
						<li><a class="footer__link" href="/industries">Industries</a></li>
						<li><a class="footer__link" href="/#locations">Locations</a></li>
						<li><a class="footer__link" href="/about">About</a></li>
						<li><a class="footer__link" href="/contact">Contact</a></li>
						<li><a class="footer__link" href="/blog/">Blog</a></li>
					</ul>
				</nav>

				<div class="footer__col">
					<p class="footer__head">Markets</p>
					<ul>
						<li>Mumbai</li>
						<li>Pune</li>
						<li>Delhi</li>
						<li>Hyderabad</li>
						<li>India</li>
						<li>Japan</li>
						<li>China</li>
						<li>USA</li>
					</ul>
				</div>

				<div class="footer__col">
					<p class="footer__head">Social</p>
					<ul>
						<li><a class="footer__link" href="#">Instagram</a></li>
						<li><a class="footer__link" href="#">LinkedIn</a></li>
						<li><a class="footer__link" href="#">YouTube</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="footer__bottom">
			<p>© <?= date('Y') ?> Kalpataru Exhibition</p>
			<nav class="footer__legal" aria-label="Legal">
				<a class="footer__link" href="/privacy-policy">Privacy Policy</a>
				<a class="footer__link" href="/terms-and-conditions">Terms &amp; Conditions</a>
			</nav>
			<a class="footer__top-link" href="#top">Back to top ↑</a>
		</div>
	</div>
</footer>
<script>
	// header scrolled state + fullscreen mobile menu (plain JS, no dependencies,
	// same behaviour as the site's main.ts)
	(function () {
		var header = document.querySelector('[data-header]');
		if (header) {
			var onScroll = function () {
				header.classList.toggle('is-scrolled', window.scrollY > 12);
			};
			window.addEventListener('scroll', onScroll, { passive: true });
			onScroll();
		}

		var toggle = document.querySelector('[data-menu-toggle]');
		var menu = document.querySelector('[data-menu]');
		if (toggle && menu) {
			var links = menu.querySelectorAll('a');
			var firstLink = links[0];

			var setOpen = function (open) {
				toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
				toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
				menu.classList.toggle('is-open', open);
				document.body.classList.toggle('menu-open', open);
				// the light menu sits under the bar, so the bar's ink must go dark
				if (header) header.classList.toggle('is-open', open);
			};

			toggle.addEventListener('click', function () {
				var open = toggle.getAttribute('aria-expanded') !== 'true';
				setOpen(open);
				if (open && firstLink) firstLink.focus();
			});

			for (var i = 0; i < links.length; i++) {
				links[i].addEventListener('click', function () { setOpen(false); });
			}

			document.addEventListener('keydown', function (event) {
				if (event.key === 'Escape' && menu.classList.contains('is-open')) {
					setOpen(false);
					toggle.focus();
				}
			});
		}
	})();
</script>
</body>
</html>
