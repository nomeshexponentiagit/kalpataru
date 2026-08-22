<?php
/** Admin — blog posts list (drafts included). */

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/blog.php';

require_login();

// ---------------------------------------------------------------- actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
	csrf_check();
	$action = clean_input($_POST['action'] ?? '', 20);
	$id     = (int) ($_POST['id'] ?? 0);

	$back = '/admin/blog.php?page=' . rawurlencode($_GET['page'] ?? '1');

	try {
		if ($action === 'delete') {
			blog_delete($id);
			flash_set('ok', 'Post #' . $id . ' deleted.');
		}
	} catch (Throwable $e) {
		error_log('[admin] ' . $e->getMessage());
		flash_set('bad', 'That action failed — please try again.');
	}
	header('Location: ' . $back);
	exit;
}

// ------------------------------------------------------------------ list
$page    = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;

$result = blog_list($page, $perPage, true); // drafts included for the admin
$posts  = $result['posts'];
$pages  = $result['pages'];
$page   = min($page, $pages);

$query = function (array $over = []) use ($page) {
	return '/admin/blog.php?' . http_build_query(['page' => $over['page'] ?? $page]);
};

$flash = flash_take();

$title  = 'Blog';
$active = 'blog';
require __DIR__ . '/_partials/header.php';
?>

<div class="topbar">
	<div class="topbar__titles">
		<h1 class="page-title">Blog posts</h1>
		<p class="page-sub">Articles shown on the website's <a href="/blog/" target="_blank" rel="noopener">blog page</a></p>
	</div>
	<div class="topbar__actions">
		<a class="btn btn--ghost" href="/blog/" target="_blank" rel="noopener">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" /><polyline points="15 3 21 3 21 9" /><line x1="10" y1="14" x2="21" y2="3" /></svg>
			View blog
		</a>
		<a class="btn btn--primary" href="/admin/blog-edit.php">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
			New post
		</a>
	</div>
</div>

<div class="content">
	<?php if ($flash): ?>
		<div class="alert alert--<?= e($flash['kind']) ?>"><?= e($flash['text']) ?></div>
	<?php endif; ?>

	<section class="card">
		<div class="card__head">
			<h2 class="card__title">All posts</h2>
			<span class="card__meta"><?= (int) $result['total'] ?> total · drafts stay private until published</span>
		</div>

		<div class="card__body">
			<?php if (!$posts): ?>
				<div class="empty">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" /><line x1="16" y1="13" x2="8" y2="13" /><line x1="16" y1="17" x2="8" y2="17" /></svg>
					<p class="empty__title">No posts yet</p>
					<p class="empty__sub">Write your first article — it appears on /blog/ once published.</p>
				</div>
			<?php else: ?>
				<div class="table-wrap">
					<table class="leads">
						<thead>
							<tr>
								<th>Title</th>
								<th>Status</th>
								<th>Updated</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($posts as $p): ?>
								<tr>
									<td>
										<div class="who-row">
											<?php if ($p['cover'] !== ''): ?>
												<img class="cover-thumb" src="/blog-images/<?= e($p['cover']) ?>" alt="" loading="lazy" />
											<?php else: ?>
												<span class="cover-thumb cover-thumb--empty" aria-hidden="true"></span>
											<?php endif; ?>
											<div>
												<div class="who">
													<?php if ($p['status'] === 'published'): ?>
														<a href="/blog/<?= e($p['slug']) ?>" target="_blank" rel="noopener"><?= e($p['title']) ?></a>
													<?php else: ?>
														<?= e($p['title']) ?>
													<?php endif; ?>
												</div>
												<div class="sub">/blog/<?= e($p['slug']) ?></div>
											</div>
										</div>
									</td>
									<td>
										<?php if ($p['status'] === 'published'): ?>
											<span class="pill pill--published">published</span>
										<?php else: ?>
											<span class="pill pill--draft">draft</span>
										<?php endif; ?>
									</td>
									<td class="sub" style="white-space:nowrap"><?= e(date('d M Y', strtotime((string) $p['updated_at']))) ?></td>
									<td>
										<div class="row-actions">
											<a class="btn btn--ghost btn--sm" href="/admin/blog-edit.php?id=<?= (int) $p['id'] ?>">Edit</a>
											<form class="inline-form" method="post" action="<?= e($query()) ?>"
												onsubmit="return confirm('Delete “<?= e($p['title']) ?>”? This cannot be undone.')">
												<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" />
												<input type="hidden" name="action" value="delete" />
												<input type="hidden" name="id" value="<?= (int) $p['id'] ?>" />
												<button class="btn btn--danger btn--icon" type="submit" title="Delete" aria-label="Delete post #<?= (int) $p['id'] ?>">
													<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /></svg>
												</button>
											</form>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<?php if ($pages > 1): ?>
					<div class="pagination">
						<?php for ($p = 1; $p <= $pages; $p++): ?>
							<?php if ($p === $page): ?>
								<span class="cur"><?= $p ?></span>
							<?php else: ?>
								<a href="<?= e($query(['page' => $p])) ?>"><?= $p ?></a>
							<?php endif; ?>
						<?php endfor; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</section>
</div>

<?php require __DIR__ . '/_partials/footer.php'; ?>
