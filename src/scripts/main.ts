/**
 * UI interactions: header scroll state, mobile menu, services preview,
 * industry background reveal. Entrance animations live in ./motion.
 */
import { initMotion } from './motion';

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

/* ------------------------------------------------------------ header state */

function initHeader(): void {
	const header = document.querySelector<HTMLElement>('[data-header]');
	if (!header) return;

	const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 12);
	onScroll();
	window.addEventListener('scroll', onScroll, { passive: true });
}

/* ------------------------------------------------------------ mobile menu */

function initMenu(): void {
	const toggle = document.querySelector<HTMLButtonElement>('[data-menu-toggle]');
	const menu = document.querySelector<HTMLElement>('[data-menu]');
	if (!toggle || !menu) return;

	const links = menu.querySelectorAll<HTMLAnchorElement>('a');
	const firstLink = links[0];

	const setOpen = (open: boolean) => {
		toggle.setAttribute('aria-expanded', String(open));
		toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		menu.classList.toggle('is-open', open);
		document.body.classList.toggle('menu-open', open);
		// the light menu sits under the bar, so the bar's ink must go dark
		document.querySelector('[data-header]')?.classList.toggle('is-open', open);
	};

	toggle.addEventListener('click', () => {
		const open = toggle.getAttribute('aria-expanded') !== 'true';
		setOpen(open);
		if (open) firstLink?.focus();
	});

	links.forEach((link) => link.addEventListener('click', () => setOpen(false)));

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && menu.classList.contains('is-open')) {
			setOpen(false);
			toggle.focus();
		}
	});
}

/* -------------------------------------- services: hover reveals the preview */

function initServices(): void {
	const list = document.querySelector<HTMLElement>('[data-services-list]');
	const previews = document.querySelectorAll<HTMLElement>('[data-service-preview]');
	if (!list || previews.length === 0) return;

	const rows = list.querySelectorAll<HTMLElement>('[data-service]');

	const setActive = (index: number) => {
		rows.forEach((row, i) => row.classList.toggle('is-active', i === index));
		previews.forEach((preview, i) => preview.classList.toggle('is-active', i === index));
	};

	rows.forEach((row, i) => {
		row.addEventListener('pointerenter', () => setActive(i));
		row.addEventListener('focusin', () => setActive(i));
	});

	// leaving the whole list returns to the first service
	list.addEventListener('pointerleave', () => setActive(0));
}

/* ------------------------------ industries: hover reveals the background */

function initIndustries(): void {
	const rows = document.querySelectorAll<HTMLElement>('[data-industry]');
	const backgrounds = document.querySelectorAll<HTMLElement>('[data-industry-bg]');
	if (rows.length === 0 || backgrounds.length === 0) return;

	const setActive = (index: number) => {
		rows.forEach((row, i) => row.classList.toggle('is-active', i === index));
		backgrounds.forEach((bg, i) => bg.classList.toggle('is-active', i === index));
	};

	rows.forEach((row, i) => {
		row.addEventListener('pointerenter', () => setActive(i));
		row.addEventListener('focusin', () => setActive(i));
		// tap support on touch devices
		row.addEventListener('click', () => setActive(i));
	});
}

/* ------------------------------------------------------------------ boot */

initHeader();
initMenu();
initServices();
initIndustries();
initMotion(reduceMotion.matches);
