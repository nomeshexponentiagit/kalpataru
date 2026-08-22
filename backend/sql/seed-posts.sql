-- Sample blog posts for Kalpataru Exhibition (6 articles, published).
-- Import on Hostinger AFTER schema.sql (phpMyAdmin > Import), on an empty
-- blog_posts table. Explicit ids 1-6 so the cover filenames below line up.
--
-- Cover images: upload backend/blog/seed-covers/cover-1.jpg … cover-6.jpg
-- into public_html/blog-images/ (the admin uploads folder) with the SAME
-- filenames. The photos are real portfolio shots from public/images/.
--
-- Content notes (for the user): the client names are real (taken from the
-- live portfolio captions); dates, tips and process details are written
-- generically — adjust anything that doesn't match how you actually work.
-- Bodies are plain text: a blank line becomes a paragraph on the website.

INSERT INTO blog_posts (id, title, slug, excerpt, body, cover, status, created_at) VALUES
(1,
 'From sketch to show floor',
 'from-sketch-to-show-floor',
 'A walk through the Kalpataru Exhibition process — from the first brief to the final screw on the show floor.',
 'Every stall on an exhibition floor starts as a conversation. A client tells us what they are launching, who they want to meet, and what should happen when a visitor walks in. From that brief, our design team works up the first concepts — layouts, material palettes, lighting moods and the all-important front elevation.\n\nOnce a direction is approved, the design moves into detailed drawings. Every panel, counter, display case and cable run is drawn before anything is cut. This is where surprises are removed: a stall is built in a workshop, but it is finished on the show floor, where there is no time to improvise.\n\nFabrication happens in-house. Carpentry, metalwork, printing, paint and electrical work are all done under one roof, which is why we can hold a stall to tight deadlines without losing quality.\n\nThe show floor is the final workshop. The stall arrives in carefully packed modules, and our installation team assembles it in the time the organisers give — usually just a day or two. Lighting is focused, graphics are aligned, and every surface is cleaned before the doors open.\n\nAfter the show, the same team dismantles the stall, salvages what can be reused and makes sure the client leaves with nothing to worry about.\n\nFrom sketch to show floor, the whole cycle is designed so you do one thing only: meet visitors and tell your story.',
 'cover-1.jpg',
 'published',
 '2026-08-01 10:00:00'),

(2,
 'Five things exhibitors forget',
 'five-things-exhibitors-forget',
 'Small oversights that cost the most on show day — and how to close them before the doors open.',
 'We have built enough stalls to know that the biggest problems on show day are rarely the big ones. The design is approved, the stall is standing — and then a small oversight takes the morning. Here are five things to check in the final week.\n\nSpare everything that lights up or moves. Bulbs, LED strips, power supplies and the little adaptors that go missing from the box. Bring spares for every light and every screen cable. They cost almost nothing in the office and are priceless at 9 am on opening day.\n\nKnow where your power comes from. Confirm how many sockets your stall gets, where they are, and what the organisers charge for extra power. An unplanned extension cable across the aisle is a hazard, and every exhibition has rules about it.\n\nPack a storage plan. Brochures, gifts, spare uniforms and tools all need a home. A stall without storage hides boxes badly; a stall with a lockable back room looks effortless. Build this into the design, not after.\n\nBrief your team properly. Everyone on the stall should know the product story, who the important visitors are, and what counts as a good conversation. Thirty minutes of briefing on day one beats ten panicked messages during the show.\n\nHave a lead-capture system that actually works. Notebooks get lost; phone notes get forgotten. Decide how you record a visitor, their interest and the follow-up — before the first visitor arrives.\n\nNone of this is glamorous. All of it decides whether the show pays for itself.',
 'cover-2.jpg',
 'published',
 '2026-08-05 10:00:00'),

(3,
 'Small space, big sparkle',
 'jewellery-exhibition-stall-design',
 'Light, security and display craft — how we approach stalls for jewellery brands like Anmol Nayantara and Jhamtani.',
 'A jewellery stall is a different discipline altogether. The products are small, precious and photographed endlessly — every visitor with a phone is a potential catalogue. Everything on the stall has to serve the pieces, not compete with them.\n\nLighting comes first. Jewellery needs focused, colour-true light that makes metal gleam and stones sparkle without glare. We layer warm spotlights over display cases with soft ambient light, so every showcase reads clearly from across the aisle.\n\nDisplay is craft. Cases are positioned at hand height, collections are grouped by story, and every shelf is angled for the eye. A visitor should be able to take in a full range at a glance, then step close for the piece they like.\n\nSecurity is designed in, not added on. Lockable showcases, a staffed back area, and sightlines that keep every counter visible from the reception desk — the stall should feel open and inviting while keeping every piece accounted for.\n\nFinally, the branding. Jewellery brands carry a mood — heritage, celebration, modern luxury — and the stall must carry the same mood in its walls, finishes and signage. We have built stalls for jewellery names like Anmol Nayantara and Jhamtani, and each one starts from that mood, not from a template.\n\nSmall floor area, enormous expectations. That is exactly what makes these builds rewarding.',
 'cover-3.jpg',
 'published',
 '2026-08-09 10:00:00'),

(4,
 'Exhibiting in Japan, China and the USA',
 'exhibition-floors-japan-china-usa',
 'How we prepare stalls for international shows — packing, logistics, regulations and time zones.',
 'A stall built for a show in India and a stall shipped to an international exhibition are two very different projects. The design can be the same, but everything around it changes.\n\nPacking comes first. An international stall travels by sea or air, through customs and across handling yards. Every module has to be light, strong and replaceable — if a panel is damaged in transit, a local carpenter must be able to build a new one from our drawings.\n\nRegulations differ in every country. Power standards, fire rules, aisle clearances and even the height of signage change between Japan, China and the USA. Our team works through the organiser manuals early, so approvals do not become last-minute surprises.\n\nTime zones are a design constraint too. When the build begins in Tokyo or Shanghai, the client team in India is asleep. We plan approvals and sign-offs around that — send drawings early, get decisions before the build window opens, and leave nothing to a midnight phone call.\n\nOn the ground, we work with local teams in each market who know the venues, the suppliers and the schedules. The result is the same quality of finish our clients expect in India, delivered on the other side of the world.\n\nInternational shows are where preparation shows most. The stall that goes up smoothly abroad is the one that was designed for the journey, not just for the floor.',
 'cover-4.jpg',
 'published',
 '2026-08-12 10:00:00'),

(5,
 'Engineering exhibitions',
 'engineering-exhibition-stalls',
 'Building stalls for industrial brands — Ginni, MOJJ, Nichrome, Ansys and more — where machines are the stars.',
 'An engineering stall has a different kind of visitor. People arrive with technical questions, compare specifications and often plan serious investments. The stall has to do two jobs at once: look impressive from the aisle, and stand up to close, detailed inspection.\n\nMachines are usually the stars. A big machine on the stand needs a platform that can carry its weight, power that arrives where it is needed, and space around it for a crowd. We plan the floor loads and the cable routes with the exhibitor\'s engineers before a single panel is drawn.\n\nDemos decide the day. Live demonstrations pull visitors like nothing else, so we build the demo area as the centrepiece — sightlines from the aisles, screens above for the people at the back, and room for the machine operator to work safely.\n\nThen there is the quieter stuff. Product walls, spec sheets, meeting corners for serious conversations — the spaces where a handshake becomes an order. Engineering visitors often want to sit, talk numbers and examine samples, so we give them a proper table and a proper chair.\n\nWe have built stalls for engineering and technology brands like Ginni, MOJJ, Nichrome and Ansys, and the lesson is always the same: in this sector, the stall is judged like the product — on how well it is engineered.',
 'cover-5.jpg',
 'published',
 '2026-08-15 10:00:00'),

(6,
 'What makes a stall a magnet',
 'what-makes-a-stall-a-magnet',
 'The design principles that pull visitors across the aisle — sightlines, story, light and movement.',
 'Some stalls are busy all day and some stay quiet, and the difference is rarely luck. Visitors on an exhibition floor make split-second decisions about where to go, and the best stalls are designed around how those decisions are made.\n\nSightlines come first. A visitor should understand your stall from across the hall — who you are, what you do, and why it matters. That means one big message up high, one clear product story at eye level, and nothing competing for attention.\n\nOpenness invites people in. Stalls that feel like closed rooms with a counter at the door push visitors away; stalls with an open front, a clear path and room to wander pull them in. The physical shape of the entrance does more than any sign.\n\nLight guides the eye. On a bright, busy floor, the stall with deliberate lighting — bright where the products are, softer where people talk — looks calm and confident. Visitors walk towards light without realising it.\n\nMovement is the final hook. A rotating product, a looping screen, a live demonstration — something alive on the stall tells the eye there is something happening here. Even small motion works, as long as it supports the message.\n\nNone of these principles need a bigger budget. They need decisions made early, before the stall is designed — which is exactly the conversation we start every project with.',
 'cover-6.jpg',
 'published',
 '2026-08-21 10:00:00');
