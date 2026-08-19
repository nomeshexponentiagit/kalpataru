<?php
/** Lead queries shared by the dashboard and the CSV export. */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

/**
 * Normalised filters: status is one of new|contacted|closed|spam|'' (all),
 * q is a free-text search across name/email/phone/company/message.
 */
function leads_filters(array $in): array
{
	$statuses = ['new', 'contacted', 'closed', 'spam'];
	$status = clean_input($in['status'] ?? '', 20);
	return [
		'status' => in_array($status, $statuses, true) ? $status : '',
		'q'      => clean_input($in['q'] ?? '', 120),
	];
}

/** WHERE clause + bound params for the current filters. */
function leads_where(array $f): array
{
	$where  = [];
	$params = [];
	if ($f['status'] !== '') {
		$where[]  = 'status = ?';
		$params[] = $f['status'];
	}
	if ($f['q'] !== '') {
		$where[]  = '(name LIKE ? OR email LIKE ? OR phone LIKE ? OR company LIKE ? OR message LIKE ?)';
		$like     = '%' . $f['q'] . '%';
		array_push($params, $like, $like, $like, $like, $like);
	}
	return ['sql' => $where ? 'WHERE ' . implode(' AND ', $where) : '', 'params' => $params];
}

/** Total leads matching the filters. */
function leads_total(array $f): int
{
	$w      = leads_where($f);
	$stmt   = db()->prepare('SELECT COUNT(*) FROM leads ' . $w['sql']);
	$stmt->execute($w['params']);
	return (int) $stmt->fetchColumn();
}

/** One page of leads (newest first). */
function leads_page(array $f, int $page, int $perPage = 20): array
{
	$w     = leads_where($f);
	$off   = max(0, $page - 1) * $perPage;
	$stmt  = db()->prepare(
		'SELECT id, name, email, phone, company, message, page, status, admin_note, created_at
		 FROM leads ' . $w['sql'] . ' ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?'
	);
	$p     = $w['params'];
	$p[]   = $perPage;
	$p[]   = $off;
	$stmt->execute($p);
	return $stmt->fetchAll();
}

/** Counts per status (for the dashboard cards), plus 7-day deltas for the "+N" badges. */
function leads_stats(): array
{
	$rows = db()->query(
		'SELECT status, COUNT(*) AS n FROM leads GROUP BY status'
	)->fetchAll();
	$out = ['new' => 0, 'contacted' => 0, 'closed' => 0, 'spam' => 0];
	foreach ($rows as $r) {
		if (isset($out[$r['status']])) $out[$r['status']] = (int) $r['n'];
	}
	$out['total'] = array_sum($out);

	// how many arrived in the last 7 days (for the green "+N" badges)
	foreach (['new', 'contacted', 'closed', 'spam', 'total'] as $k) {
		$out[$k . '_7d'] = 0;
	}
	$rows7 = db()->query(
		'SELECT status, COUNT(*) AS n FROM leads WHERE created_at > (NOW() - INTERVAL 7 DAY) GROUP BY status'
	)->fetchAll();
	foreach ($rows7 as $r) {
		if (isset($out[$r['status'] . '_7d'])) $out[$r['status'] . '_7d'] = (int) $r['n'];
	}
	$out['total_7d'] = $out['new_7d'] + $out['contacted_7d'] + $out['closed_7d'] + $out['spam_7d'];
	return $out;
}

/** Manually add a lead (walk-in / phone enquiry) — always starts as new. */
function lead_add(array $lead): int
{
	db()->prepare(
		'INSERT INTO leads (name, email, phone, company, message, page, ip_hash)
		 VALUES (?, ?, ?, ?, ?, ?, ?)'
	)->execute([
		clean_input($lead['name'] ?? '', 120),
		clean_input($lead['email'] ?? '', 190),
		clean_input($lead['phone'] ?? '', 40),
		clean_input($lead['company'] ?? '', 160),
		clean_input($lead['message'] ?? '', 5000),
		'manual',
		ip_hash(),
	]);
	return (int) db()->lastInsertId();
}

/** All leads matching the filters (for CSV export). */
function leads_all(array $f): array
{
	$w    = leads_where($f);
	$stmt = db()->prepare(
		'SELECT id, name, email, phone, company, message, page, status, admin_note, created_at
		 FROM leads ' . $w['sql'] . ' ORDER BY created_at DESC, id DESC'
	);
	$stmt->execute($w['params']);
	return $stmt->fetchAll();
}

/** Update a lead's status (validated enum). */
function lead_set_status(int $id, string $status): bool
{
	if (!in_array($status, ['new', 'contacted', 'closed', 'spam'], true)) return false;
	$stmt = db()->prepare('UPDATE leads SET status = ? WHERE id = ?');
	$stmt->execute([$status, $id]);
	return $stmt->rowCount() > 0;
}

/** Save a private note on a lead. */
function lead_set_note(int $id, string $note): void
{
	$stmt = db()->prepare('UPDATE leads SET admin_note = ? WHERE id = ?');
	$stmt->execute([clean_input($note, 500), $id]);
}

function lead_delete(int $id): void
{
	db()->prepare('DELETE FROM leads WHERE id = ?')->execute([$id]);
}
