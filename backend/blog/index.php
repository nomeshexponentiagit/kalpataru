<?php
/** Public blog list — card grid, 9 posts per page, newest first. */

require_once __DIR__ . '/../lib/blog.php';

$page   = max(1, (int) ($_GET['page'] ?? 1));
$result = blog_list($page, 9);
$posts  = $result['posts'];
$pages  = $result['pages'];
$page   = min($page, $pages);

$pageTitle       = 'Blog — Kalpataru Exhibition';
$pageDescription = 'News, insights and updates from Kalpataru Exhibition — exhibition stall design, fabrication and installation.';

require __DIR__ . '/_partials/header.php';
?>
<section class="blog-hero theme-dark tone-ink" id="top">
	<div class="container">
		<span class="eyebrow">Journal</span>
		<h1 class="blog-hero__title">Insights &amp; updates</h1>
		<p class="blog-hero__sub">
			Stories from our stalls and pavilions, tips for exhibitors, and news from
			the exhibitions we build around the world.
		</p>
	</div>
</section>

<section class="section">
	<div class="container">
		<?php if ($posts === []): ?>
			<div class="blog-empty">
				<h2 class="blog-empty__title">No articles yet</h2>
				<p class="blog-empty__text">The first posts are on their way — check back soon for news and exhibition stories.</p>
				<a class="btn btn--ghost" href="/contact">
					<span class="btn__label">Talk to us</span>
					<span class="btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="m13 6 6 6-6 6" /></svg></span>
				</a>
			</div>
		<?php else: ?>
			<div class="post-grid">
				<?php foreach ($posts as $p): ?>
					<a class="post-card" href="/blog/<?= e($p['slug']) ?>">
						<span class="post-card__media">
							<?php if ($p['cover'] !== ''): ?>
								<img src="/blog-images/<?= e($p['cover']) ?>" alt="" loading="lazy" />
							<?php endif; ?>
						</span>
						<span class="post-card__body">
							<span class="post-card__date"><?= e(date('d M Y', strtotime((string) $p['created_at']))) ?></span>
							<span class="post-card__title"><?= e($p['title']) ?></span>
							<span class="post-card__excerpt"><?= e($p['excerpt']) ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>

			<?php if ($pages > 1): ?>
				<nav class="pagination" aria-label="Blog pages">
					<?php if ($page > 1): ?>
						<a href="/blog/?page=<?= $page - 1 ?>" rel="prev">← Prev</a>
					<?php endif; ?>
					<?php for ($i = 1; $i <= $pages; $i++): ?>
						<?php if ($i === $page): ?>
							<span class="cur" aria-current="page"><?= $i ?></span>
						<?php elseif ($i === 1 || $i === $pages || abs($i - $page) <= 2): ?>
							<a href="/blog/?page=<?= $i ?>"><?= $i ?></a>
						<?php elseif ($i === 2 || $i === $pages - 1): ?>
							<span class="gap">…</span>
						<?php endif; ?>
					<?php endfor; ?>
					<?php if ($page < $pages): ?>
						<a href="/blog/?page=<?= $page + 1 ?>" rel="next">Next →</a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
<?php require __DIR__ . '/_partials/footer.php';
