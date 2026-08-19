<?php
/** CSV export of the leads (same filters as the dashboard). */

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/leads.php';

require_login();

$filters = leads_filters($_GET);
$leads   = leads_all($filters);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="kalpataru-leads-' . date('Y-m-d') . '.csv"');

// UTF-8 BOM so Excel opens it with the right encoding
$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");

fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Company', 'Message', 'Page', 'Status', 'Note', 'Date'], ',', '"', '');

foreach ($leads as $l) {
	fputcsv($out, [
		$l['id'],
		$l['name'],
		$l['email'],
		$l['phone'],
		$l['company'],
		$l['message'],
		$l['page'],
		$l['status'],
		$l['admin_note'],
		$l['created_at'],
	], ',', '"', '');
}

fclose($out);
