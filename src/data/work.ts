/**
 * REAL portfolio photos and client captions — pulled verbatim from the live
 * kalpataruexhibition.com portfolio ("What We Have Clients" + portfolio
 * sections), the approved source for these assets. Files were batch-resized
 * into public/images/work/ by /tmp/kalpataru-verify/make-work-gallery.mjs —
 * re-run that script after adding new photos, then rebuild.
 *
 * Captions are copied exactly as written on the live site (typos included).
 * The sector blurbs below are our own copy — DUMMY until the user approves.
 */
export interface WorkGroup {
	category: 'Engineering' | 'Real Estate' | 'Jewellery';
	blurb: string;
	items: { src: string; client: string }[];
}

export const WORK_GROUPS: WorkGroup[] = [
	{
		category: 'Engineering',
		blurb: 'Stalls for industrial and technology brands — Ginni, MOJJ, Nichrome, Ansys and more.',
		items: [
			{ src: '/images/work/en-1.jpg', client: 'Ginni' },
			{ src: '/images/work/en-2.jpg', client: 'MOJJ Engineering Systems Ltd.' },
			{ src: '/images/work/en-3.jpg', client: 'AKSH Engineering Systems Ltd.' },
			{ src: '/images/work/en-5.jpg', client: 'Nichrome' },
			{ src: '/images/work/en-6.jpg', client: 'Nichrome' },
			{ src: '/images/work/en-7.jpg', client: 'APPL' },
			{ src: '/images/work/en-9.jpg', client: 'Ansys' },
			{ src: '/images/work/en-10.jpg', client: 'HI-Tech polymers' },
			{ src: '/images/work/en-11.jpg', client: 'Nichrome' },
			{ src: '/images/work/en-12.jpg', client: 'Nichrome' },
			{ src: '/images/work/en-13.jpg', client: 'Nichrome' },
		],
	},
	{
		category: 'Real Estate',
		blurb: 'Pavilions for developers and builders — Kolte Patil, Gera, Goel Ganga, Pride World City and more.',
		items: [
			{ src: '/images/work/re-1.jpg', client: 'Gera Developer' },
			{ src: '/images/work/re-2.jpg', client: 'Karda Constructions' },
			{ src: '/images/work/re-3.jpg', client: 'Nayati Group' },
			{ src: '/images/work/re-5.jpg', client: 'Rama Group' },
			{ src: '/images/work/re-6.jpg', client: 'Bramha Corp' },
			{ src: '/images/work/re-7.jpg', client: 'Shapoorji Pallonji' },
			{ src: '/images/work/re-9.jpg', client: 'Rama Group' },
			{ src: '/images/work/re-10.jpg', client: 'Amanora' },
			{ src: '/images/work/re-11.jpg', client: 'Goel Ganga' },
			{ src: '/images/work/re-12.jpg', client: 'Nyati Group' },
			{ src: '/images/work/re-13.jpg', client: 'Rama Group' },
			{ src: '/images/work/re-15.jpg', client: 'Bramha Corp' },
			{ src: '/images/work/re-16.jpg', client: 'Shri Hari Krushna Developers' },
			{ src: '/images/work/re-17.jpg', client: 'Dhatrak Group' },
			{ src: '/images/work/re-18.jpg', client: 'PARKSYDE' },
			{ src: '/images/work/re-19.jpg', client: 'Real Estate' },
			{ src: '/images/work/re-20.jpg', client: 'Pride World City' },
			{ src: '/images/work/re-21.jpg', client: 'Kolte Patil' },
			{ src: '/images/work/re-22.jpg', client: 'Energia' },
			{ src: '/images/work/re-23.jpg', client: 'The Ideal' },
			{ src: '/images/work/re-24.jpg', client: 'City ONE' },
			{ src: '/images/work/re-25.jpg', client: 'Anmol Nayantara' },
			{ src: '/images/work/re-27.jpg', client: 'Tata Metaliks' },
			{ src: '/images/work/re-28.jpg', client: 'Venky\'s Nutrition' },
			{ src: '/images/work/re-29.jpg', client: 'RGS Realty' },
			{ src: '/images/work/re-30.jpg', client: 'Avishkar Infra' },
			{ src: '/images/work/re-31.jpg', client: 'Avishkar Infra' },
			{ src: '/images/work/re-32.jpg', client: 'Ravima' },
			{ src: '/images/work/re-33.jpg', client: 'Ravima' },
			{ src: '/images/work/re-34.jpg', client: 'Ravima' },
			{ src: '/images/work/re-35.jpg', client: 'Avishkar Infra' },
			{ src: '/images/work/re-36.jpg', client: 'Gangotree Homes' },
			{ src: '/images/work/re-37.jpg', client: 'Gangotree Homes' },
			{ src: '/images/work/re-38.jpg', client: 'Gaikwad Infrastructure' },
			{ src: '/images/work/re-39.jpg', client: 'Ashapuri Construcations' },
			{ src: '/images/work/re-40.jpg', client: 'Shrinath Realty' },
			{ src: '/images/work/re-41.jpg', client: 'Avishkar Infra' },
			{ src: '/images/work/re-42.jpg', client: 'Avishkar Infra' },
			{ src: '/images/work/re-43.jpg', client: 'Avishkar Infra' },
			{ src: '/images/work/re-44.jpg', client: 'Avishkar Infra' },
			{ src: '/images/work/re-45.jpg', client: 'Avishkar Infra' },
			{ src: '/images/work/re-46.jpg', client: 'Majestique Landmarks' },
			{ src: '/images/work/re-47.jpg', client: 'Real Estate' },
			{ src: '/images/work/re-48.jpg', client: 'ESBEE Realty' },
			{ src: '/images/work/re-49.jpg', client: 'Holystico Group' },
			{ src: '/images/work/re-50.jpg', client: 'Holystico Group' },
			{ src: '/images/work/re-51.jpg', client: 'Holystico Group' },
			{ src: '/images/work/re-52.jpg', client: 'Holystico Group' },
			{ src: '/images/work/re-53.jpg', client: 'Jhamtani' },
			{ src: '/images/work/re-54.jpg', client: 'Kohinoor' },
			{ src: '/images/work/re-55.jpg', client: 'Kohinoor' },
			{ src: '/images/work/re-56.jpg', client: 'Kolte Patil' },
			{ src: '/images/work/re-57.jpg', client: 'Kolte Patil' },
			{ src: '/images/work/re-58.jpg', client: 'Kumar Properties' },
			{ src: '/images/work/re-59.jpg', client: 'Mantra' },
			{ src: '/images/work/re-60.jpg', client: 'Pride World City' },
			{ src: '/images/work/re-61.jpg', client: 'Wisteria Properties' },
			{ src: '/images/work/re-62.jpg', client: 'Real Estate' },
			{ src: '/images/work/re-63.jpg', client: 'Real Estate' },
			{ src: '/images/work/re-64.jpg', client: 'Real Estate' },
			{ src: '/images/work/re-65.jpg', client: 'Real Estate' },
		],
	},
	{
		category: 'Jewellery',
		blurb: 'Showcases for gold and jewellery houses — Nakoda Gold & Silver, MAA Group, RAJAT and more.',
		items: [
			{ src: '/images/work/je-1.jpg', client: 'Nakoda Gold & Silver' },
			{ src: '/images/work/je-2.jpg', client: 'Nakoda Gold & Silver' },
			{ src: '/images/work/je-3.jpg', client: 'MAA Group' },
			{ src: '/images/work/je-4.jpg', client: 'Laxmi Gold Ornament' },
			{ src: '/images/work/je-5.jpg', client: 'MAA Group' },
			{ src: '/images/work/je-6.jpg', client: 'MAA Group' },
			{ src: '/images/work/je-7.jpg', client: 'RAJAT' },
			{ src: '/images/work/je-8.jpg', client: 'Nakoda Gold & Silver' },
			{ src: '/images/work/je-9.jpg', client: 'Siddhesh Jewellers' },
		],
	},
];
