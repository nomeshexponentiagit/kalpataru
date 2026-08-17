/**
 * REAL portfolio photos and client captions — pulled verbatim from the live
 * kalpataruexhibition.com portfolio ("What We Have Clients" + portfolio
 * sections), the approved source for these assets. Files were batch-resized
 * into public/images/work/ by /tmp/kalpataru-verify/make-work-gallery.mjs —
 * re-run that script after adding new photos, then rebuild.
 *
 * Captions are copied exactly as written on the live site (typos included).
 * The sector blurbs below are our own copy — DUMMY until the user approves.
 * Each item's date/event is a DUMMY placeholder so the work cards render
 * their full design — replace with real exhibition names and dates before
 * launch.
 */
export interface WorkItem {
	src: string;
	client: string;
	date: string;
	event: string;
}

export interface WorkGroup {
	category: 'Engineering' | 'Real Estate' | 'Jewellery';
	blurb: string;
	items: WorkItem[];
}

export const WORK_GROUPS: WorkGroup[] = [
	{
		category: 'Engineering',
		blurb: 'Stalls for industrial and technology brands — Ginni, MOJJ, Nichrome, Ansys and more.',
		items: [
			{ src: '/images/work/en-1.jpg', client: 'Ginni', date: 'Jan 2023', event: 'Engineering & Automation Expo' },
			{ src: '/images/work/en-2.jpg', client: 'MOJJ Engineering Systems Ltd.', date: 'Aug 2024', event: 'Manufacturing Technology Show' },
			{ src: '/images/work/en-3.jpg', client: 'AKSH Engineering Systems Ltd.', date: 'Mar 2025', event: 'Industrial Machinery Expo' },
			{ src: '/images/work/en-5.jpg', client: 'Nichrome', date: 'Oct 2023', event: 'Machine Tools & Robotics Fair' },
			{ src: '/images/work/en-6.jpg', client: 'Nichrome', date: 'May 2024', event: 'Engineering & Automation Expo' },
			{ src: '/images/work/en-7.jpg', client: 'APPL', date: 'Dec 2025', event: 'Manufacturing Technology Show' },
			{ src: '/images/work/en-9.jpg', client: 'Ansys', date: 'Jul 2023', event: 'Industrial Machinery Expo' },
			{ src: '/images/work/en-10.jpg', client: 'HI-Tech polymers', date: 'Feb 2024', event: 'Machine Tools & Robotics Fair' },
			{ src: '/images/work/en-11.jpg', client: 'Nichrome', date: 'Sep 2025', event: 'Engineering & Automation Expo' },
			{ src: '/images/work/en-12.jpg', client: 'Nichrome', date: 'Apr 2023', event: 'Manufacturing Technology Show' },
			{ src: '/images/work/en-13.jpg', client: 'Nichrome', date: 'Nov 2024', event: 'Industrial Machinery Expo' },
		],
	},
	{
		category: 'Real Estate',
		blurb: 'Pavilions for developers and builders — Kolte Patil, Gera, Goel Ganga, Pride World City and more.',
		items: [
			{ src: '/images/work/re-1.jpg', client: 'Gera Developer', date: 'Jan 2023', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-2.jpg', client: 'Karda Constructions', date: 'Aug 2024', event: 'City Property Expo' },
			{ src: '/images/work/re-3.jpg', client: 'Nayati Group', date: 'Mar 2025', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-5.jpg', client: 'Rama Group', date: 'Oct 2023', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-6.jpg', client: 'Bramha Corp', date: 'May 2024', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-7.jpg', client: 'Shapoorji Pallonji', date: 'Dec 2025', event: 'City Property Expo' },
			{ src: '/images/work/re-9.jpg', client: 'Rama Group', date: 'Jul 2023', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-10.jpg', client: 'Amanora', date: 'Feb 2024', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-11.jpg', client: 'Goel Ganga', date: 'Sep 2025', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-12.jpg', client: 'Nyati Group', date: 'Apr 2023', event: 'City Property Expo' },
			{ src: '/images/work/re-13.jpg', client: 'Rama Group', date: 'Nov 2024', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-15.jpg', client: 'Bramha Corp', date: 'Jun 2025', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-16.jpg', client: 'Shri Hari Krushna Developers', date: 'Jan 2023', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-17.jpg', client: 'Dhatrak Group', date: 'Aug 2024', event: 'City Property Expo' },
			{ src: '/images/work/re-18.jpg', client: 'PARKSYDE', date: 'Mar 2025', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-19.jpg', client: 'Real Estate', date: 'Oct 2023', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-20.jpg', client: 'Pride World City', date: 'May 2024', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-21.jpg', client: 'Kolte Patil', date: 'Dec 2025', event: 'City Property Expo' },
			{ src: '/images/work/re-22.jpg', client: 'Energia', date: 'Jul 2023', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-23.jpg', client: 'The Ideal', date: 'Feb 2024', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-24.jpg', client: 'City ONE', date: 'Sep 2025', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-25.jpg', client: 'Anmol Nayantara', date: 'Apr 2023', event: 'City Property Expo' },
			{ src: '/images/work/re-27.jpg', client: 'Tata Metaliks', date: 'Nov 2024', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-28.jpg', client: 'Venky\'s Nutrition', date: 'Jun 2025', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-29.jpg', client: 'RGS Realty', date: 'Jan 2023', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-30.jpg', client: 'Avishkar Infra', date: 'Aug 2024', event: 'City Property Expo' },
			{ src: '/images/work/re-31.jpg', client: 'Avishkar Infra', date: 'Mar 2025', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-32.jpg', client: 'Ravima', date: 'Oct 2023', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-33.jpg', client: 'Ravima', date: 'May 2024', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-34.jpg', client: 'Ravima', date: 'Dec 2025', event: 'City Property Expo' },
			{ src: '/images/work/re-35.jpg', client: 'Avishkar Infra', date: 'Jul 2023', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-36.jpg', client: 'Gangotree Homes', date: 'Feb 2024', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-37.jpg', client: 'Gangotree Homes', date: 'Sep 2025', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-38.jpg', client: 'Gaikwad Infrastructure', date: 'Apr 2023', event: 'City Property Expo' },
			{ src: '/images/work/re-39.jpg', client: 'Ashapuri Construcations', date: 'Nov 2024', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-40.jpg', client: 'Shrinath Realty', date: 'Jun 2025', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-41.jpg', client: 'Avishkar Infra', date: 'Jan 2023', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-42.jpg', client: 'Avishkar Infra', date: 'Aug 2024', event: 'City Property Expo' },
			{ src: '/images/work/re-43.jpg', client: 'Avishkar Infra', date: 'Mar 2025', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-44.jpg', client: 'Avishkar Infra', date: 'Oct 2023', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-45.jpg', client: 'Avishkar Infra', date: 'May 2024', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-46.jpg', client: 'Majestique Landmarks', date: 'Dec 2025', event: 'City Property Expo' },
			{ src: '/images/work/re-47.jpg', client: 'Real Estate', date: 'Jul 2023', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-48.jpg', client: 'ESBEE Realty', date: 'Feb 2024', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-49.jpg', client: 'Holystico Group', date: 'Sep 2025', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-50.jpg', client: 'Holystico Group', date: 'Apr 2023', event: 'City Property Expo' },
			{ src: '/images/work/re-51.jpg', client: 'Holystico Group', date: 'Nov 2024', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-52.jpg', client: 'Holystico Group', date: 'Jun 2025', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-53.jpg', client: 'Jhamtani', date: 'Jan 2023', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-54.jpg', client: 'Kohinoor', date: 'Aug 2024', event: 'City Property Expo' },
			{ src: '/images/work/re-55.jpg', client: 'Kohinoor', date: 'Mar 2025', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-56.jpg', client: 'Kolte Patil', date: 'Oct 2023', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-57.jpg', client: 'Kolte Patil', date: 'May 2024', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-58.jpg', client: 'Kumar Properties', date: 'Dec 2025', event: 'City Property Expo' },
			{ src: '/images/work/re-59.jpg', client: 'Mantra', date: 'Jul 2023', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-60.jpg', client: 'Pride World City', date: 'Feb 2024', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-61.jpg', client: 'Wisteria Properties', date: 'Sep 2025', event: 'Homes & Builders Show' },
			{ src: '/images/work/re-62.jpg', client: 'Real Estate', date: 'Apr 2023', event: 'City Property Expo' },
			{ src: '/images/work/re-63.jpg', client: 'Real Estate', date: 'Nov 2024', event: 'Real Estate Investment Fair' },
			{ src: '/images/work/re-64.jpg', client: 'Real Estate', date: 'Jun 2025', event: 'Property & Realty Expo' },
			{ src: '/images/work/re-65.jpg', client: 'Real Estate', date: 'Jan 2023', event: 'Homes & Builders Show' },
		],
	},
	{
		category: 'Jewellery',
		blurb: 'Showcases for gold and jewellery houses — Nakoda Gold & Silver, MAA Group, RAJAT and more.',
		items: [
			{ src: '/images/work/je-1.jpg', client: 'Nakoda Gold & Silver', date: 'Jan 2023', event: 'Diamond & Gem Fair' },
			{ src: '/images/work/je-2.jpg', client: 'Nakoda Gold & Silver', date: 'Aug 2024', event: 'Precious Metals Show' },
			{ src: '/images/work/je-3.jpg', client: 'MAA Group', date: 'Mar 2025', event: 'Gold & Jewellery Show' },
			{ src: '/images/work/je-4.jpg', client: 'Laxmi Gold Ornament', date: 'Oct 2023', event: 'Bridal Jewellery Expo' },
			{ src: '/images/work/je-5.jpg', client: 'MAA Group', date: 'May 2024', event: 'Diamond & Gem Fair' },
			{ src: '/images/work/je-6.jpg', client: 'MAA Group', date: 'Dec 2025', event: 'Precious Metals Show' },
			{ src: '/images/work/je-7.jpg', client: 'RAJAT', date: 'Jul 2023', event: 'Gold & Jewellery Show' },
			{ src: '/images/work/je-8.jpg', client: 'Nakoda Gold & Silver', date: 'Feb 2024', event: 'Bridal Jewellery Expo' },
			{ src: '/images/work/je-9.jpg', client: 'Siddhesh Jewellers', date: 'Sep 2025', event: 'Diamond & Gem Fair' },
		],
	},
];
