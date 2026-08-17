/**
 * Central site data.
 * Anything marked PLACEHOLDER is intentionally unfinished and must be
 * replaced with verified company information before launch.
 */
export const SITE = {
	name: 'Kalpataru Exhibition',
	// DUMMY — replace with the production domain when live.
	url: 'https://www.kalpataru-exhibition.com',
	// DUMMY — replace with the real company email.
	email: 'hello@kalpataru-exhibition.com',
	// DUMMY — replace with the real company phone.
	phone: '+91 98220 00000',
	// DUMMY — replace with the real registered office address.
	address: 'Kalpataru Exhibition, Unit 4, Exhibition Complex, Pune 411001, Maharashtra, India',
	// DUMMY — replace with real working hours.
	hours: 'Mon – Sat, 10:00 – 19:00 IST',
};

/* Work, Services and Industries are real pages; remaining hash links point
   at homepage sections, so they are written "/#locations" etc. Header/Footer
   strip the leading slash from hash links only while already on the homepage. */
export const NAV_LINKS = [
	{ label: 'Work', href: '/work' },
	{ label: 'Services', href: '/services' },
	{ label: 'Industries', href: '/industries' },
	{ label: 'About', href: '/about' },
	{ label: 'Contact', href: '/contact' },
] as const;

export const MARKETS = ['India', 'Japan', 'China', 'USA'] as const;

export const CITIES = ['Mumbai', 'Pune', 'Delhi', 'Hyderabad'] as const;

export const FOOTER_NAV = [
	{ label: 'Work', href: '/work' },
	{ label: 'Services', href: '/services' },
	{ label: 'Industries', href: '/industries' },
	{ label: 'Locations', href: '/#locations' },
	{ label: 'About', href: '/about' },
	{ label: 'Contact', href: '/contact' },
] as const;

/* Legal pages live in the footer bottom bar on every page. */
export const LEGAL_LINKS = [
	{ label: 'Privacy Policy', href: '/privacy-policy' },
	{ label: 'Terms & Conditions', href: '/terms-and-conditions' },
] as const;

// DUMMY — real social profile URLs to be added.
export const SOCIALS = [
	{ label: 'Instagram', href: '#' },
	{ label: 'LinkedIn', href: '#' },
	{ label: 'YouTube', href: '#' },
] as const;
