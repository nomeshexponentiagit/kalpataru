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
};

export const NAV_LINKS = [
	{ label: 'Work', href: '#work' },
	{ label: 'Services', href: '#services' },
	{ label: 'Industries', href: '#industries' },
	{ label: 'About', href: '#about' },
	{ label: 'Contact', href: '#contact' },
] as const;

export const MARKETS = ['India', 'Japan', 'China', 'USA'] as const;

export const CITIES = ['Mumbai', 'Pune', 'Delhi', 'Hyderabad'] as const;

export const FOOTER_NAV = [
	{ label: 'Work', href: '#work' },
	{ label: 'Services', href: '#services' },
	{ label: 'Industries', href: '#industries' },
	{ label: 'Locations', href: '#locations' },
	{ label: 'About', href: '#about' },
	// TODO — link once a resources page exists.
	{ label: 'Resources', href: '#' },
	{ label: 'Contact', href: '#contact' },
] as const;

// DUMMY — real social profile URLs to be added.
export const SOCIALS = [
	{ label: 'Instagram', href: '#' },
	{ label: 'LinkedIn', href: '#' },
	{ label: 'YouTube', href: '#' },
] as const;
