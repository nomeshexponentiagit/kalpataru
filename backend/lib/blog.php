<?php
/** Blog queries shared by the public pages and the admin editor. */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

/** 'My First Post' -> 'my-first-post'; fallback 'post' if nothing survives. */
function blog_slugify(string $title): string
{
	$slug = strtolower(trim($title));
	$slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
	$slug = trim($slug, '-');
	return $slug === '' ? 'post' : mb_substr($slug, 0, 190);
}

/** Slug guaranteed unique in blog_posts: appends -2, -3, … while taken. */
function blog_unique_slug(string $base, ?int $exceptId = null): string
{
	$slug = $base;
	$n    = 2;
	while (true) {
		$stmt = db()->prepare('SELECT id FROM blog_posts WHERE slug = ?');
		$stmt->execute([$slug]);
		$row = $stmt->fetch();
		if (!$row || ($exceptId !== null && (int) $row['id'] === $exceptId)) {
			return $slug;
		}
		$suffix = '-' . $n;
		$slug   = mb_substr($base, 0, 190 - strlen($suffix)) . $suffix;
		$n++;
	}
}

/** One post by id, or null. */
function blog_get(int $id): ?array
{
	$stmt = db()->prepare('SELECT * FROM blog_posts WHERE id = ?');
	$stmt->execute([$id]);
	$row = $stmt->fetch();
	return $row === false ? null : $row;
}

/** One post by slug (any status — the caller decides draft rules), or null. */
function blog_get_by_slug(string $slug): ?array
{
	$stmt = db()->prepare('SELECT * FROM blog_posts WHERE slug = ?');
	$stmt->execute([$slug]);
	$row = $stmt->fetch();
	return $row === false ? null : $row;
}

/**
 * One page of posts, newest first. Published only unless $includeDrafts.
 * Returns ['posts' => array, 'total' => int, 'pages' => int].
 */
function blog_list(int $page, int $perPage = 9, bool $includeDrafts = false): array
{
	$where = $includeDrafts ? '' : "WHERE status = 'published'";
	$total = (int) db()->query("SELECT COUNT(*) FROM blog_posts $where")->fetchColumn();

	$off  = max(0, $page - 1) * $perPage;
	$stmt = db()->prepare(
		"SELECT id, title, slug, excerpt, body, cover, status, created_at, updated_at
		 FROM blog_posts $where ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?"
	);
	$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
	$stmt->bindValue(2, $off, PDO::PARAM_INT);
	$stmt->execute();

	return [
		'posts' => $stmt->fetchAll(),
		'total' => $total,
		'pages' => max(1, (int) ceil($total / $perPage)),
	];
}

/** Insert (id null) or update a post. Returns the post id. */
function blog_save(array $post, ?int $id): int
{
	$title   = clean_input($post['title'] ?? '', 190);
	$slug    = blog_unique_slug(clean_input($post['slug'] ?? '', 190), $id);
	$excerpt = clean_input($post['excerpt'] ?? '', 500);
	$body    = clean_input($post['body'] ?? '', 100000);
	$cover   = clean_input($post['cover'] ?? '', 255);
	$status  = ($post['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

	// auto-excerpt: first ~150 characters of the body, cut at a word boundary
	if ($excerpt === '' && $body !== '') {
		$first  = trim(preg_replace('/\s+/', ' ', mb_substr($body, 0, 160)));
		$cut    = mb_strlen($first) > 150 ? mb_strrpos(mb_substr($first, 0, 151), ' ') : false;
		$first  = $cut === false ? $first : mb_substr($first, 0, $cut);
		$excerpt = rtrim($first, '.,;:-') . (mb_strlen($first) >= 150 ? '…' : '');
	}

	if ($id === null) {
		db()->prepare(
			'INSERT INTO blog_posts (title, slug, excerpt, body, cover, status)
			 VALUES (?, ?, ?, ?, ?, ?)'
		)->execute([$title, $slug, $excerpt, $body, $cover, $status]);
		return (int) db()->lastInsertId();
	}

	db()->prepare(
		'UPDATE blog_posts SET title = ?, slug = ?, excerpt = ?, body = ?, cover = ?, status = ?
		 WHERE id = ?'
	)->execute([$title, $slug, $excerpt, $body, $cover, $status, $id]);
	return $id;
}

/** Delete a post and its cover image (if any). */
function blog_delete(int $id): void
{
	$post = blog_get($id);
	if ($post !== null && $post['cover'] !== '') {
		blog_cover_unlink((string) $post['cover']);
	}
	db()->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([$id]);
}

/** Remove one cover file from blog-images/ (missing file is fine). */
function blog_cover_unlink(string $filename): void
{
	$path = BLOG_UPLOAD_DIR . '/' . basename($filename);
	if (is_file($path)) {
		@unlink($path);
	}
}

/** Plain text -> safe HTML: blank lines split paragraphs. */
function blog_body_html(string $body): string
{
	$paras = preg_split('/\n\s*\n/', trim($body)) ?: [];
	return implode('', array_map(static function (string $p): string {
		$p = trim($p);
		return $p === '' ? '' : '<p>' . nl2br(e($p)) . '</p>';
	}, $paras));
}
