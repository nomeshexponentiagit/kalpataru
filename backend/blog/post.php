<?php
/** Single blog post. Reached as /blog/<slug> (rewritten by .htaccess) or
 *  directly as /blog/post.php?slug=<slug>. Drafts 404 for visitors but
 *  render for logged-in admins (cheap preview). */

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/blog.php';

session_start_secure();

$slug    = clean_input($_GET['slug'] ?? '', 190);
$post    = $slug === '' ? null : blog_get_by_slug($slug);
$allowed = $post !== null && ($post['status'] === 'published' || is_logged_in());

if (!$allowed) {
	http_response_code(404);
}

$pageTitle = $allowed
	? $post['title'] . ' — Kalpataru Exhibition'
	: 'Post not found — Kalpataru Exhibition';
$pageDescription = $allowed ? $post['excerpt'] : '';

require __DIR__ . '/_partials/header.php';

if (!$allowed):
?>
<section class="blog-hero theme-dark tone-ink" id="top">
	<div class="container">
		<span class="eyebrow">Journal</span>
		<h1 class="blog-hero__title">Post not found</h1>
		<p class="blog-hero__sub">That article doesn't exist, or it has been moved or unpublished.</p>
	</div>
</section>
<section class="section">
	<div class="container">
		<div class="blog-empty">
			<a class="btn btn--solid" href="/blog/">
				<span class="btn__label">All articles</span>
				<span class="btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m13 6 6 6-6 6" /></svg></span>
			</a>
		</div>
	</div>
</section>
<?php else: ?>
<section class="blog-hero theme-dark tone-ink" id="top">
	<div class="container">
		<a class="blog-hero__back" href="/blog/">← All articles</a>
		<p class="blog-hero__date">
			<?= e(date('d M Y', strtotime((string) $post['created_at']))) ?>
			<?php if ($post['status'] === 'draft'): ?>&nbsp;·&nbsp;Draft preview<?php endif; ?>
		</p>
		<h1 class="blog-hero__title"><?= e($post['title']) ?></h1>
	</div>
</section>

<?php if ($post['cover'] !== ''): ?>
	<div class="container">
		<figure class="post-cover">
			<img src="/blog-images/<?= e($post['cover']) ?>" alt="<?= e($post['title']) ?>" />
		</figure>
	</div>
<?php endif; ?>

<section class="section">
	<div class="container">
		<article class="blog-prose">
			<?= blog_body_html((string) $post['body']) ?>
		</article>
	</div>
</section>
<?php
endif;

require __DIR__ . '/_partials/footer.php';
