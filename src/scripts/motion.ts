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
			);

		// the scroll indicator only exists on the homepage hero
		if (document.querySelector('.hero__scroll')) {
			tl.from('.hero__scroll', { opacity: 0, duration: 0.8 }, 1.4);
		}
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

	/* --------------------------------------------- masked headline reveals */
	// Every .section-head__title is split into lines (at <br>) and each line
	// rises out of an overflow-hidden mask as it scrolls into view. Big
	// statements and CTA titles opt in with [data-headline].
	const maskHeadline = (el: HTMLElement) => {
		if (el.dataset.masked) return;
		el.dataset.masked = 'true';

		const lines: Node[][] = [[]];
		Array.from(el.childNodes).forEach((node) => {
			if (node.nodeName === 'BR') {
				lines.push([]);
				return;
			}
			lines[lines.length - 1].push(node);
		});
		const keep = lines.filter((line) =>
			line.some(
				(node) => node.nodeType !== Node.TEXT_NODE || (node.textContent ?? '').trim() !== ''
			)
		);
		if (keep.length === 0) return;

		el.textContent = '';
		keep.forEach((line) => {
			const mask = document.createElement('span');
			mask.className = 'title-mask';
			const inner = document.createElement('span');
			inner.className = 'title-mask__line';
			line.forEach((node) => inner.appendChild(node));
			mask.appendChild(inner);
			el.appendChild(mask);
		});

		gsap.from(el.querySelectorAll<HTMLElement>('.title-mask__line'), {
			yPercent: 118,
			duration: 1.05,
			stagger: 0.09,
			ease: 'power4.out',
			scrollTrigger: { trigger: el, start: 'top 88%', once: true },
		});
	};

	document
		.querySelectorAll<HTMLElement>('.section-head__title, [data-headline]')
		.forEach(maskHeadline);

	/* ----------------------------------------- section heads rise as a unit */
	document.querySelectorAll<HTMLElement>('.section-head').forEach((head) => {
		gsap.from(head.children, {
			opacity: 0,
			y: 24,
			duration: 0.85,
			ease: 'power3.out',
			stagger: 0.08,
			scrollTrigger: { trigger: head, start: 'top 88%', once: true },
		});
	});

	/* ------------------------------------------------- image wipe reveals */
	// [data-img-reveal] containers clip-reveal upward while the photo inside
	// settles from a slight zoom. The image transform is cleared on
	// completion so CSS hover states keep full control afterwards.
	document.querySelectorAll<HTMLElement>('[data-img-reveal]').forEach((wrap) => {
		const img = wrap.querySelector('img');
		const tl = gsap.timeline({
			scrollTrigger: { trigger: wrap, start: 'top 82%', once: true },
		});
		tl.fromTo(
			wrap,
			{ clipPath: 'inset(0 0 100% 0)' },
			{ clipPath: 'inset(0 0 0% 0)', duration: 1.15, ease: 'power3.inOut' },
			0
		);
		if (img) {
			tl.fromTo(
				img,
				{ scale: 1.18 },
				{ scale: 1, duration: 1.4, ease: 'power3.out', clearProps: 'transform' },
				0
			);
		}
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
