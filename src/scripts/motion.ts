/**
 * GSAP entrance + scroll animations.
 * Transform/opacity only — nothing that triggers layout.
 * Disabled entirely when the user prefers reduced motion.
 */
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initMotion(reduceMotion: boolean): void {
	// No hiding, no animation — content simply stays visible.
	if (reduceMotion) return;

	/* -------------------------------------------------------- hero entrance */
	const heroMedia = document.querySelector<HTMLElement>('[data-hero-media]');
	if (heroMedia) {
		gsap.set('[data-hero-line]', { yPercent: 115 });

		const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
		// (the media's transform is owned by the parallax setup below)
		tl.from(heroMedia, { opacity: 0, duration: 1.4, ease: 'power2.out' }, 0)
			.from('[data-hero-eyebrow]', { opacity: 0, y: 18, duration: 0.8 }, 0.25)
			.to('[data-hero-line]', { yPercent: 0, duration: 1.1, stagger: 0.11, ease: 'power4.out' }, 0.3)
			.from(
				'[data-hero-copy], [data-hero-markets], [data-hero-ctas]',
				{ opacity: 0, y: 22, duration: 0.9, stagger: 0.1 },
				0.85
			)
			.from('.hero__scroll', { opacity: 0, duration: 0.8 }, 1.4);
	}

	/* --------------------------------------- parallax on full-bleed media */
	const parallax = (el: HTMLElement | null, amount: number) => {
		if (!el) return;
		gsap.fromTo(
			el,
			{ scale: 1.14, yPercent: -amount },
			{
				yPercent: amount,
				ease: 'none',
				scrollTrigger: {
					trigger: el.closest('section') ?? el,
					start: 'top bottom',
					end: 'bottom top',
					scrub: true,
				},
			}
		);
	};

	parallax(heroMedia, 6);
	parallax(document.querySelector<HTMLElement>('.industries__bg'), 8);
	parallax(document.querySelector<HTMLElement>('.cta__media'), 8);

	/* hero content gently drifts and dims as you scroll away */
	const heroInner = document.querySelector<HTMLElement>('.hero__inner');
	if (heroInner) {
		gsap.to(heroInner, {
			opacity: 0.2,
			yPercent: 10,
			ease: 'none',
			scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom 25%', scrub: true },
		});
	}

	/* reading-progress hairline along the very top of the page */
	const progress = document.querySelector<HTMLElement>('[data-scroll-progress]');
	if (progress) {
		gsap.to(progress, {
			scaleX: 1,
			ease: 'none',
			scrollTrigger: { trigger: document.body, start: 'top top', end: 'bottom bottom', scrub: 0.3 },
		});
	}

	/* ------------------------------------------------- scroll-triggered reveals */
	gsap.set('[data-reveal]', { opacity: 0, y: 28 });

	ScrollTrigger.batch('[data-reveal]', {
		start: 'top 88%',
		once: true,
		onEnter: (batch) =>
			gsap.to(batch, {
				opacity: 1,
				y: 0,
				duration: 0.9,
				ease: 'power3.out',
				stagger: 0.12,
				overwrite: true,
			}),
	});

	/* ------------------------------------------------- staggered service rows */
	gsap.utils.toArray<HTMLElement>('.service-row').forEach((row, i) => {
		gsap.from(row, {
			opacity: 0,
			y: 26,
			duration: 0.8,
			delay: i * 0.06,
			ease: 'power3.out',
			scrollTrigger: { trigger: row, start: 'top 94%', once: true },
		});
	});

	/* --------------------------------------------------- animated statistics */
	document.querySelectorAll<HTMLElement>('[data-stat]').forEach((stat) => {
		const target = stat.querySelector<HTMLElement>('[data-stat-value]');
		const raw = stat.dataset.value ?? '';
		if (!target) return;

		// Placeholder values ("XX") are not numeric — leave them as-is.
		// Real digits count up automatically once provided.
		if (!/^\d+$/.test(raw)) return;

		const counter = { n: 0 };
		gsap.to(counter, {
			n: parseInt(raw, 10),
			duration: 1.8,
			ease: 'power2.out',
			scrollTrigger: { trigger: stat, start: 'top 85%', once: true },
			onUpdate: () => {
				target.textContent = String(Math.round(counter.n));
			},
		});
	});
}
