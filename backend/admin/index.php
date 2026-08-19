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
	<h1 class="page-title">Leads<span>.</span></h1>
	<a class="btn btn--ghost" href="/admin/export.php?status=<?= e(rawurlencode($filters['status'])) ?>&amp;q=<?= e(rawurlencode($filters['q'])) ?>">Export CSV</a>
</div>

<?php if ($flash): ?>
	<div class="alert alert--<?= e($flash['kind']) ?>"><?= e($flash['text']) ?></div>
<?php endif; ?>

<div class="cards">
	<div class="stat"><div class="stat__num"><?= (int) $stats['total'] ?></div><div class="stat__label">Total leads</div></div>
	<div class="stat"><div class="stat__num"><?= (int) $stats['new'] ?></div><div class="stat__label">New</div></div>
	<div class="stat"><div class="stat__num"><?= (int) $stats['contacted'] ?></div><div class="stat__label">Contacted</div></div>
	<div class="stat"><div class="stat__num"><?= (int) $stats['closed'] ?></div><div class="stat__label">Closed</div></div>
	<div class="stat"><div class="stat__num"><?= (int) $stats['spam'] ?></div><div class="stat__label">Spam</div></div>
</div>

<div class="panel">
	<form class="filters" method="get" action="/admin/">
		<select class="select" name="status" style="width:auto">
			<option value="">All statuses</option>
			<?php foreach (['new', 'contacted', 'closed', 'spam'] as $s): ?>
				<option value="<?= e($s) ?>" <?= $filters['status'] === $s ? 'selected' : '' ?>><?= e(ucfirst($s)) ?></option>
			<?php endforeach; ?>
		</select>
		<input class="input" type="search" name="q" value="<?= e($filters['q']) ?>" placeholder="Search name, email, phone, message…" />
		<button class="btn btn--sm" type="submit">Filter</button>
		<?php if ($filters['status'] !== '' || $filters['q'] !== ''): ?>
			<a class="btn btn--ghost btn--sm" href="/admin/">Clear</a>
		<?php endif; ?>
	</form>
</div>

<div class="panel">
	<div class="panel__head">
		<h2 class="panel__title">Enquiries</h2>
		<span class="muted"><?= (int) $total ?> found</span>
	</div>

	<?php if (!$leads): ?>
		<p class="muted">No enquiries yet. New form submissions will appear here automatically.</p>
	<?php else: ?>
		<div class="table-wrap">
			<table class="leads">
				<thead>
					<tr>
						<th>Who</th>
						<th>Message</th>
						<th>Status</th>
						<th>Actions</th>
						<th>When</th>
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
								<div class="msg"><?= nl2br(e($l['message'])) ?></div>
								<?php if ($l['page'] !== ''): ?>
									<div class="sub">Page: <?= e($l['page']) ?></div>
								<?php endif; ?>
							</td>
							<td>
								<span class="pill pill--<?= e($l['status']) ?>"><?= e($l['status']) ?></span>
								<?php if ($l['admin_note'] !== '' && $l['admin_note'] !== null): ?>
									<div class="sub" style="margin-top:.4rem">Note: <?= e($l['admin_note']) ?></div>
								<?php endif; ?>
							</td>
							<td>
								<div class="row-actions">
									<form class="inline-form" method="post" action="<?= e($query()) ?>">
										<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" />
										<input type="hidden" name="action" value="status" />
										<input type="hidden" name="id" value="<?= (int) $l['id'] ?>" />
										<select class="select btn--sm" name="status" style="width:auto;padding:.4rem .5rem;font-size:.72rem">
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
										<input class="note-input" type="text" name="note" value="<?= e((string) $l['admin_note']) ?>" placeholder="Private note…" />
										<button class="btn btn--ghost btn--sm" type="submit">Note</button>
									</form>
									<form class="inline-form" method="post" action="<?= e($query()) ?>"
										onsubmit="return confirm('Delete lead #<?= (int) $l['id'] ?> from <?= e($l['name']) ?>? This cannot be undone.')">
										<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" />
										<input type="hidden" name="action" value="delete" />
										<input type="hidden" name="id" value="<?= (int) $l['id'] ?>" />
										<button class="btn btn--danger btn--sm" type="submit">Delete</button>
									</form>
								</div>
							</td>
							<td class="sub" style="white-space:nowrap"><?= e(date('d M Y', strtotime((string) $l['created_at']))) ?><br /><?= e(date('H:i', strtotime((string) $l['created_at']))) ?></td>
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

<?php require __DIR__ . '/_partials/footer.php'; ?>
