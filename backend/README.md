# Kalpataru Exhibition — backend (contact form + leads + blog)

This folder is the website's backend. It runs on your **Hostinger shared hosting** (PHP 8 + MySQL — no Node.js needed) next to the static Astro site.

**What it does today**

- The contact form on `/contact` posts to `/api/contact.php`. Enquiries are validated, stored in a MySQL database and emailed to your notification address. Spam protection is built in (hidden honeypot field, minimum-fill-time check, max 3 messages per visitor per 15 minutes).
- `/admin` is a private login page where you see every enquiry (leads), mark them *new / contacted / closed / spam*, add private notes and export everything to a CSV file for Excel.
- `/admin/blog.php` — write blog posts and publish them instantly on the website's `/blog/` page (no website rebuild needed). Drafts stay private until you publish them.

**What is coming later (Phases 2–4)**

- Change the website logo from the admin panel
- Add / edit / delete work cards, services and industries (with photos)
- Edit contact details, testimonials and stats
- Automatic website rebuild so the changes also show on Google

---

## Setting it up on Hostinger (one time, ~15 minutes)

You need: your **hPanel login**, the **admin password** you want for `/admin`, and the **email address** where new enquiries should arrive.

### Step 1 — Create the database (hPanel)

1. Log in to hPanel (hostinger.com → your hosting → **Manage**).
2. Open **Databases → MySQL Databases**.
3. Click **Create a new database**:
   - Database name: `kalpataru` (hPanel adds a prefix like `u123456789_` automatically — the full name becomes e.g. `u123456789_kalpataru`).
   - Username: `kalpataru` (same — becomes e.g. `u123456789_kalpataru`).
   - Password: click the dice icon to generate a strong one, then **copy it and save it somewhere safe** (you'll type it into `config.php`).
4. Click **Create**. Remember these three values: **full database name, full username, password**.

### Step 2 — Create the tables (phpMyAdmin)

1. In hPanel open **Databases → phpMyAdmin**.
2. On the left, click your new database (the `u123456789_kalpataru` one).
3. Click the **Import** tab → **Choose file** → select `sql/schema.sql` from this folder → **Go**.
4. You should see three tables: `leads`, `form_submissions` and `blog_posts`.

### Step 3 — Create the admin password hash

The admin password is stored encrypted (not as plain text), so we generate a "hash" first:

1. On your computer (this project folder), open a terminal and run:
   ```
   php tools/hash.php "your-chosen-admin-password"
   ```
   (If `php` is not installed locally, ask Claude to generate the hash for you.)
2. Copy the output line that starts with `$2y$10$...` — that is the hash.

### Step 4 — Fill in `lib/config.php`

Open `lib/config.php` in a text editor and change only these lines:

| Setting | Put |
|---|---|
| `DB_NAME` | your full database name, e.g. `u123456789_kalpataru` |
| `DB_USER` | your full database username, e.g. `u123456789_kalpataru` |
| `DB_PASS` | the database password from Step 1 |
| `ADMIN_PASSWORD_HASH` | the `$2y$10$...` line from Step 3 |
| `NOTIFY_EMAIL` | the email where new enquiries should arrive |
| `NOTIFY_FROM` | keep as `website@kalpataruexhibition.com` (or a real mailbox on your domain) |
| `IP_SALT` | change to any long random text (this is for privacy — IPs are stored one-way hashed) |

Leave `DB_HOST` as `localhost` and everything else as it is.

### Step 5 — Upload to your hosting

1. Build the website locally: `npm run build` (creates the `dist/` folder).
2. In hPanel open **Files → File Manager** (or use FTP — see below).
3. Go into **public_html**.
4. Upload the **contents** of `dist/` (the files inside it: `index.html`, `work/`, `images/` …) to the root of `public_html`.
5. Upload the **contents** of the `backend` folder (the folders inside it: `api/`, `admin/`, `lib/`, `sql/`, `tools/`, `blog/`, `blog-images/` and the `.htaccess`) **also into the root of `public_html`** — not into a subfolder. The admin panel, contact API and blog must sit at the website address (`/admin`, `/api/contact.php`, `/blog/`).
6. Make sure `public_html/blog-images/` exists (it should after the upload) and is writable — blog cover photos are saved there. If cover uploads fail with an error, ask Claude to check the folder permissions in hPanel.

> With FTP: connect to `ftp.yourdomain.com` with your hPanel FTP details, open `public_html`, and drag the same files in. Hostinger also has a free "Hostinger Website Builder"/"Easy Website Transfer" — but plain File Manager or FTP is all you need here.

**Important:** the admin panel must sit at the website address, so the files have to land in `public_html` — not in a subfolder like `public_html/website/`.

### Step 6 — Test it

1. Open `https://kalpataruexhibition.com/contact`, fill the form and send it. You should see the green success message, and the enquiry should appear in your email.
2. Open `https://kalpataruexhibition.com/admin` and log in with:
   - username: `admin`
   - password: the one you chose in Step 3
3. You should see your test enquiry in the list. Mark it `contacted`, add a note, try **Export CSV** — done. 🎉
4. Open `https://kalpataruexhibition.com/blog/` — the blog page. In the admin, go to **Blog → New post**, write something, set the status to *Published* and save. Refresh the blog page — your post is there with its own address like `https://kalpataruexhibition.com/blog/my-first-post`.

---

## Using the admin panel (day to day)

- **Leads** — every contact-form enquiry lands here, newest first.
- **Status** — each lead can be `new` → `contacted` → `closed`, or `spam` if junk gets through.
- **Note** — private notes per lead (e.g. "Called on Monday, follow up Friday").
- **Search / filter** — search by name, email, phone or message; filter by status.
- **Export CSV** — downloads all (filtered) leads for Excel, including notes and dates.

### Blog

- **Blog posts** (sidebar → Blog) — every article, newest first, with a *published* (green) or *draft* (grey) badge.
- **New post** — title, URL slug (fills itself from the title), summary, article text, cover photo and status. The article is plain text: **leave a blank line between paragraphs** and they appear as separate paragraphs on the website. No HTML needed.
- **Cover photo** — JPG, PNG or WebP up to 3 MB, uploaded from your computer and stored in `blog-images/`. Optional, but recommended (the blog card looks empty without one).
- **Draft vs published** — drafts are only visible to you (and, while logged in, you can preview them from the admin). Switch to *Published* and save to make the post live instantly — no website rebuild needed.
- The public pages live at `/blog/` (all posts) and `/blog/<slug>` (one post). The Blog link appears in the **footer only**, not the header — by request.

## Security (already built in)

- Login protected with encrypted password + throttling (delays after wrong attempts).
- Every admin action protected against CSRF (cross-site forgery).
- Database queries use prepared statements (SQL-injection safe).
- `lib/`, `sql/`, `tools/` are blocked from the web via `.htaccess` (`Require all denied`) — visitors can never download your config or password hash.
- IP addresses are stored one-way hashed with a secret salt (`IP_SALT`), so a database leak does not reveal visitor IPs.

---

## Local development (for Claude / developers)

- Requires PHP 8 + MySQL running locally (`brew install php mysql`, `brew services start mysql`).
- `lib/config.local.php` (gitignored) overrides the real settings locally — see the template comment in `config.php`.
- Create the local DB: `mysql -u root -e "CREATE DATABASE kalpataru"` then `mysql -u root kalpataru < sql/schema.sql`.
- Staging: merge `dist/` + `backend/` into a folder and serve with `php -S 127.0.0.1:8081 tools/blog-router.php` — the router maps pretty blog URLs (`/blog/<slug>`) to `blog/post.php` (Apache does this on Hostinger via `blog/.htaccess`).
- The admin panel uses the same self-hosted Manrope/Inter fonts as the public site (`/fonts/`).

## Troubleshooting

- **Form says "Something went wrong"** → the email may still have arrived; check `error_log` in hPanel (or ask Claude to look). Usually means the database details in `config.php` don't match hPanel.
- **Email doesn't arrive** → Hostinger sends mail from a mailbox on your domain; make sure the mailbox in `NOTIFY_FROM` exists, or switch to a free SMTP service (ask Claude to wire in SMTP if needed).
- **/admin says "not found"** → the `admin/` folder is not at the root of `public_html` (see Step 5 — the backend contents must be merged into `public_html` itself, not uploaded as a `backend/` subfolder).
- **Blog post opens a "not found" page** → the post is still a draft (publish it in the admin), or the `blog/.htaccess` rewrite is missing on the hosting (pretty URLs need it — the direct form `/blog/post.php?slug=...` will still work).
- **Login always says wrong password** → re-run Step 3 and make sure you pasted the whole `$2y$...` line into `config.php`.
