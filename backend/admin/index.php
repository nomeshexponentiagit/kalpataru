<?php
/** Admin dashboard — the leads list. */

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/leads.php';

require_login();

// ---------------------------------------------------------------- actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
	csrf_check();
	$action = clean_input($_POST['action'] ?? '', 20);
	$id     = (int) ($_POST['id'] ?? 0);

	// redirect back to the same list view (filters preserved)
	$back = '/admin/?status=' . rawurlencode($_GET['status'] ?? '')
		. '&q=' . rawurlencode($_GET['q'] ?? '')
		. '&page=' . rawurlencode($_GET['page'] ?? '1');

	try {
		if ($action === 'status') {
			if (lead_set_status($id, $_POST['status'] ?? '')) {
				flash_set('ok', 'Lead #' . $id . ' updated.');
			}
		} elseif ($action === 'note') {
			lead_set_note($id, $_POST['note'] ?? '');
			flash_set('ok', 'Note saved on lead #' . $id . '.');
		} elseif ($action === 'delete') {
			lead_delete($id);
			flash_set('ok', 'Lead #' . $id . ' deleted.');
		}
	} catch (Throwable $e) {
		error_log('[admin] ' . $e->getMessage());
		flash_set('bad', 'That action failed — please try again.');
	}
	header('Location: ' . $back);
	exit;
}

// ------------------------------------------------------------------ list
$filters = leads_filters($_GET);
$page    = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;

$total = leads_total($filters);
$pages = max(1, (int) ceil($total / $perPage));
$page  = min($page, $pages);
$leads = leads_page($filters, $page, $perPage);
$stats = leads_stats();

$query  = function (array $over = []) use ($filters, $page) {
	$q = [
		'status' => $filters['status'],
		'q'      => $filters['q'],
		'page'   => $over['page'] ?? $page,
	];
	return '?' . http_build_query($q);
};

$flash = flash_take();

$title  = 'Leads';
$active = 'leads';
require __DIR__ . '/_partials/header.php';
?>

<div class="topbar">
	<div class="topbar__titles">
		<h1 class="page-title">Leads</h1>
		<p class="page-sub">Enquiries from the contact form</p>
	</div>
	<div class="topbar__actions">
		<form class="search" method="get" action="/admin/">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7" /><path d="m21 21-4.35-4.35" /></svg>
			<input type="search" name="q" value="<?= e($filters['q']) ?>" placeholder="Search enquiries" aria-label="Search enquiries" />
			<input type="hidden" name="status" value="<?= e($filters['status']) ?>" />
		</form>
		<a class="btn btn--primary" href="/admin/export.php?status=<?= e(rawurlencode($filters['status'])) ?>&amp;q=<?= e(rawurlencode($filters['q'])) ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" /></svg>
			Export CSV
		</a>
	</div>
</div>

<div class="content">
	<?php if ($flash): ?>
		<div class="alert alert--<?= e($flash['kind']) ?>"><?= e($flash['text']) ?></div>
	<?php endif; ?>

	<div class="cards">
		<a class="stat" href="/admin/">
			<div class="stat__head">Total leads<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-6l-2 3h-4l-2-3H2" /><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" /></svg></div>
			<div class="stat__num"><?= (int) $stats['total'] ?></div>
			<div class="stat__foot">All enquiries</div>
		</a>
		<div class="stat">
			<div class="stat__head">New<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6l1.4 1.4M17 17l1.4 1.4M18.4 5.6 17 7M7 17l-1.4 1.4" /></svg></div>
			<div class="stat__num"><?= (int) $stats['new'] ?></div>
			<div class="stat__foot">Awaiting reply</div>
		</div>
		<div class="stat">
			<div class="stat__head">Contacted<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" /></svg></div>
			<div class="stat__num"><?= (int) $stats['contacted'] ?></div>
			<div class="stat__foot">In conversation</div>
		</div>
		<div class="stat">
			<div class="stat__head">Closed<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg></div>
			<div class="stat__num"><?= (int) $stats['closed'] ?></div>
			<div class="stat__foot">Won / done</div>
		</div>
		<div class="stat">
			<div class="stat__head">Spam<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><line x1="4.93" y1="4.93" x2="19.07" y2="19.07" /></svg></div>
			<div class="stat__num"><?= (int) $stats['spam'] ?></div>
			<div class="stat__foot">Filtered out</div>
		</div>
	</div>

	<section class="card">
		<div class="card__head">
			<h2 class="card__title">Enquiries</h2>
			<span class="card__meta">
				<?= (int) $total ?> of <?= (int) $stats['total'] ?>
				<?php if ($filters['status'] !== '' || $filters['q'] !== ''): ?>
					· <a class="clear-link" href="/admin/">Clear filters</a>
				<?php endif; ?>
			</span>
		</div>

		<nav class="tabs" aria-label="Filter by status">
			<a class="tab <?= $filters['status'] === '' ? 'is-active' : '' ?>" href="/admin/">All <span class="tab__count"><?= (int) $stats['total'] ?></span></a>
			<a class="tab <?= $filters['status'] === 'new' ? 'is-active' : '' ?>" href="/admin/?status=new">New <span class="tab__count"><?= (int) $stats['new'] ?></span></a>
			<a class="tab <?= $filters['status'] === 'contacted' ? 'is-active' : '' ?>" href="/admin/?status=contacted">Contacted <span class="tab__count"><?= (int) $stats['contacted'] ?></span></a>
			<a class="tab <?= $filters['status'] === 'closed' ? 'is-active' : '' ?>" href="/admin/?status=closed">Closed <span class="tab__count"><?= (int) $stats['closed'] ?></span></a>
			<a class="tab <?= $filters['status'] === 'spam' ? 'is-active' : '' ?>" href="/admin/?status=spam">Spam <span class="tab__count"><?= (int) $stats['spam'] ?></span></a>
		</nav>

		<div class="card__body">
			<?php if (!$leads): ?>
				<div class="empty">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-6l-2 3h-4l-2-3H2" /><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" /></svg>
					<p class="empty__title">No enquiries here</p>
					<p class="empty__sub">New contact-form submissions will appear automatically.</p>
				</div>
			<?php else: ?>
				<div class="table-wrap">
					<table class="leads">
						<thead>
							<tr>
								<th>Who</th>
								<th>Message</th>
								<th>Status</th>
								<th>When</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($leads as $l): ?>
								<tr>
									<td>
										<div class="who"><?= e($l['name']) ?></div>
										<div class="sub"><a href="mailto:<?= e($l['email']) ?>"><?= e($l['email']) ?></a></div>
										<?php if ($l['phone'] !== ''): ?><div class="sub"><?= e($l['phone']) ?></div><?php endif; ?>
										<?php if ($l['company'] !== ''): ?><div class="sub"><?= e($l['company']) ?></div><?php endif; ?>
									</td>
									<td>
										<div class="msg" title="<?= e($l['message']) ?>"><?= nl2br(e($l['message'])) ?></div>
										<?php if ($l['page'] !== ''): ?>
											<div class="sub">Page: <?= e($l['page']) ?></div>
										<?php endif; ?>
									</td>
									<td>
										<span class="pill pill--<?= e($l['status']) ?>"><?= e($l['status']) ?></span>
										<?php if ($l['admin_note'] !== '' && $l['admin_note'] !== null): ?>
											<div class="sub" style="margin-top:.45rem">Note: <?= e($l['admin_note']) ?></div>
										<?php endif; ?>
									</td>
									<td class="sub" style="white-space:nowrap"><?= e(date('d M Y', strtotime((string) $l['created_at']))) ?><br /><?= e(date('H:i', strtotime((string) $l['created_at']))) ?></td>
									<td>
										<div class="row-actions">
											<form class="inline-form" method="post" action="<?= e($query()) ?>">
												<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" />
												<input type="hidden" name="action" value="status" />
												<input type="hidden" name="id" value="<?= (int) $l['id'] ?>" />
												<select class="status-select" name="status" aria-label="Status for lead #<?= (int) $l['id'] ?>">
													<?php foreach (['new', 'contacted', 'closed', 'spam'] as $s): ?>
														<option value="<?= e($s) ?>" <?= $l['status'] === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
													<?php endforeach; ?>
												</select>
												<button class="btn btn--ghost btn--sm" type="submit">Save</button>
											</form>
											<form class="inline-form" method="post" action="<?= e($query()) ?>">
												<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" />
												<input type="hidden" name="action" value="note" />
												<input type="hidden" name="id" value="<?= (int) $l['id'] ?>" />
												<input class="note-input" type="text" name="note" value="<?= e((string) $l['admin_note']) ?>" placeholder="Add private note…" aria-label="Note for lead #<?= (int) $l['id'] ?>" />
												<button class="btn btn--ghost btn--sm" type="submit">Note</button>
											</form>
											<form class="inline-form" method="post" action="<?= e($query()) ?>"
												onsubmit="return confirm('Delete lead #<?= (int) $l['id'] ?> from <?= e($l['name']) ?>? This cannot be undone.')">
												<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" />
												<input type="hidden" name="action" value="delete" />
												<input type="hidden" name="id" value="<?= (int) $l['id'] ?>" />
												<button class="btn btn--danger btn--icon" type="submit" title="Delete" aria-label="Delete lead #<?= (int) $l['id'] ?>">
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
