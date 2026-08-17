# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project status

Kalpataru Exhibition — business website, scaffolded 2026-08-17 with Astro 7 (minimal template). Homepage V1 is built and verified: light warm-paper theme, single brass accent, 12 sections (Hero, Introduction, Stats, Featured Work, Services, Industries, Global Presence, Process, Case Studies, Why Kalpataru, Testimonials, Final CTA). GSAP + ScrollTrigger motion with prefers-reduced-motion support. Nothing has been committed yet.

**Dummy-data policy (user-mandated):** the site currently uses DUMMY stock photos and DUMMY content, marked with "DUMMY" comments in source. All dummy images live in `public/images/` (replace by overwriting the file, or update the `src` prop in the section component). Client names, stats, testimonials, case studies, and contact details in `src/data/site.ts` are invented and must be replaced with real, verified information before launch. The header auto-detects a logo file (`logo.svg/png/webp/jpg`) dropped into `public/brand/` and falls back to a text wordmark until then.

## Clean project rule (user-mandated)

This is a completely new website, built from scratch. A rule set by the user:

- **Never** search for, open, inspect, or copy from any older Kalpataru Exhibition website/project that may exist elsewhere on this computer. If one is discovered, ignore it.
- Do not reuse CSS, JavaScript, PHP, HTML, design, architecture, or content from any previous project.
- Treat this project as completely blank.

The **only** sources of truth for this website are:
1. Instructions provided in this project
2. Information explicitly provided by the user
3. Approved assets provided for this project
4. Technical requirements documented in this CLAUDE.md

## Commands

- `npm run dev` — start the dev server at http://localhost:4321. For long-running work prefer `astro dev --background`; manage it with `astro dev stop`, `astro dev status`, `astro dev logs`.
- `npm run build` — production build to `dist/` (run before deploying)
- `npm run preview` — serve the built site locally
- `npx astro check` — typecheck the project
- `npx astro add <integration>` — add an integration (e.g. `npx astro add tailwind`)

## Architecture

**Approved stack (user-mandated):** Astro 7 (static site, SSG), HTML5, CSS3, vanilla JavaScript, GSAP for animations. PHP only if a backend is later required; MySQL only if a database is later required.

- **Do NOT add** React, Next.js, Tailwind, Bootstrap, or any other frontend framework unless explicitly instructed.
- **Do NOT use Stitch** (or other design-generation tools) for this project — design is done directly in this Astro codebase with Claude Code, based on user requirements.
- **File-based routing**: `src/pages/` — every `.astro` file is a route (`src/pages/index.astro` → `/`).
- `src/layouts/Layout.astro` — shared page shell (head, nav, footer); wrap every new page in it.
- `src/styles/global.css` — global styles, imported by the layout. Light theme by default (warm paper `--bg`, near-black `--ink`); photo-led sections (Hero, Industries, Final CTA) carry `.theme-dark` to keep light-on-dark ink.
- `src/components/ui/PlaceholderImage.astro` — image wrapper: renders `<img>` when given `src`, else a neutral SVG motif. Handles aspect-ratio, tone, alt/aria.
- `public/` — static assets served at the root: `images/` (dummy photos), `brand/` (user logo), favicon, fonts. Add brand assets here.
- `astro.config.mjs` — Astro config; `tsconfig.json` — TypeScript config.
- Pure `.astro` components — no JS framework installed. Node >= 22.12 required.
