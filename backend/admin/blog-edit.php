<?php
/** Admin — create or edit a blog post (plain text, optional cover upload). */

require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/blog.php';

require_login();

$id   = (int) ($_GET['id'] ?? 0);
$post = $id > 0 ? blog_get($id) : null;
if ($id > 0 && $post === null) {
	flash_set('bad', "That post doesn't exist.");
	header('Location: /admin/blog.php');
	exit;
}

// ---------------------------------------------------------------- actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
	csrf_check();
	$action = clean_input($_POST['action'] ?? '', 20);

	$backEdit = '/admin/blog-edit.php' . ($id > 0 ? '?id=' . $id : '');

	try {
		if ($action === 'save') {
			$title   = clean_input($_POST['title'] ?? '', 190);
			$slug    = clean_input($_POST['slug'] ?? '', 190);
			$excerpt = clean_input($_POST['excerpt'] ?? '', 500);
			$body    = clean_input($_POST['body'] ?? '', 100000);
			$status  = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

			if ($title === '' || !preg_match('/^[a-z0-9-]{1,190}$/', $slug) || $body === '') {
				flash_set('bad', 'A post needs a title, a valid URL slug (lowercase letters, numbers, dashes) and some body text.');
				header('Location: ' . $backEdit);
				exit;
			}

			$existing = (string) ($post['cover'] ?? '');
			$remove   = ($_POST['remove_cover'] ?? '') === '1';

			// cover upload: extension + sniffed MIME, max 3 MB
			$upload = $_FILES['cover'] ?? null;
			$hasUpload = $upload !== null && (int) ($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

			if ($hasUpload) {
				if ((int) $upload['error'] !== UPLOAD_ERR_OK) {
					flash_set('bad', 'The cover upload failed — please try again.');
					header('Location: ' . $backEdit);
					exit;
				}
				if ((int) $upload['size'] > 3 * 1024 * 1024) {
					flash_set('bad', 'The cover image is larger than 3 MB — please use a smaller file.');
					header('Location: ' . $backEdit);
					exit;
				}
				$ext  = strtolower(pathinfo((string) $upload['name'], PATHINFO_EXTENSION));
				$mime = (new finfo(FILEINFO_MIME_TYPE))->file((string) $upload['tmp_name']);
				$allowed = [
					'jpg'  => 'image/jpeg',
					'jpeg' => 'image/jpeg',
					'png'  => 'image/png',
					'webp' => 'image/webp',
				];
				if (!isset($allowed[$ext]) || $mime !== $allowed[$ext]) {
					flash_set('bad', 'The cover must be a JPG, PNG or WebP image.');
					header('Location: ' . $backEdit);
					exit;
				}
			}

			// save once (with the existing cover) so we have a real id for the filename
			$id = blog_save([
				'title'   => $title,
				'slug'    => $slug,
				'excerpt' => $excerpt,
				'body'    => $body,
				'cover'   => $existing,
				'status'  => $status,
			], $id > 0 ? $id : null);

			$cover = $existing;
			if ($hasUpload) {
				$cover = 'cover-' . $id . '.' . ($ext === 'jpeg' ? 'jpg' : $ext);
				if (!is_dir(BLOG_UPLOAD_DIR)) {
					mkdir(BLOG_UPLOAD_DIR, 0755, true);
				}
				if (!move_uploaded_file((string) $upload['tmp_name'], BLOG_UPLOAD_DIR . '/' . $cover)) {
					throw new RuntimeException('could not move cover file');
				}
				if ($existing !== '' && $existing !== $cover) {
					blog_cover_unlink($existing);
				}
			} elseif ($remove && $existing !== '') {
				blog_cover_unlink($existing);
				$cover = '';
			}

			if ($cover !== $existing) {
				blog_save(['title' => $title, 'slug' => $slug, 'excerpt' => $excerpt, 'body' => $body, 'cover' => $cover, 'status' => $status], $id);
			}

			$slugNow = blog_get($id)['slug'] ?? $slug;
			flash_set('ok', 'Post saved.' . ($status === 'published' ? ' It is live at /blog/' . $slugNow . '.' : ' It is a draft — publish it to make it live.'));
			header('Location: /admin/blog.php');
			exit;
		}
	} catch (Throwable $e) {
		error_log('[admin] ' . $e->getMessage());
		flash_set('bad', 'That action failed — please try again.');
		header('Location: ' . $backEdit);
		exit;
	}
}

$flash = flash_take();

$title  = $post !== null ? 'Edit post' : 'New post';
$active = 'blog';
require __DIR__ . '/_partials/header.php';
?>

<div class="topbar">
	<div class="topbar__titles">
		<h1 class="page-title"><?= $post !== null ? 'Edit post' : 'New post' ?></h1>
		<p class="page-sub"><?= $post !== null ? 'Post #' . (int) $post['id'] : 'Write a new article for the blog page' ?></p>
	</div>
	<div class="topbar__actions">
		<a class="btn btn--ghost" href="/admin/blog.php">← All posts</a>
	</div>
</div>

<div class="content">
	<?php if ($flash): ?>
		<div class="alert alert--<?= e($flash['kind']) ?>"><?= e($flash['text']) ?></div>
	<?php endif; ?>

	<section class="card">
		<div class="card__head">
			<h2 class="card__title"><?= $post !== null ? e($post['title']) : 'Article' ?></h2>
			<span class="card__meta">
				<?php if ($post !== null && $post['status'] === 'published'): ?>
					Live at <a href="/blog/<?= e($post['slug']) ?>" target="_blank" rel="noopener">/blog/<?= e($post['slug']) ?></a>
				<?php else: ?>
					Not published yet
				<?php endif; ?>
			</span>
		</div>

		<div class="card__body">
			<form class="editor" method="post" action="<?= e('/admin/blog-edit.php' . ($id > 0 ? '?id=' . $id : '')) ?>" enctype="multipart/form-data">
				<div class="editor__row">
					<div class="field">
						<label for="p-title">Title *</label>
						<input class="input" id="p-title" name="title" type="text" required maxlength="190"
							value="<?= e((string) ($post['title'] ?? '')) ?>" placeholder="e.g. How we built a 200 sqm pavilion in 9 days" />
					</div>
					<div class="field">
						<label for="p-slug">URL slug *</label>
						<input class="input" id="p-slug" name="slug" type="text" required maxlength="190" pattern="[a-z0-9\-]+"
							value="<?= e((string) ($post['slug'] ?? '')) ?>" placeholder="how-we-built-a-pavilion" />
						<p class="form-hint">Lowercase letters, numbers and dashes. Fills itself from the title — edit to change the link.</p>
					</div>
				</div>

				<div class="field">
					<label for="p-excerpt">Summary</label>
					<textarea class="textarea" id="p-excerpt" name="excerpt" rows="2" maxlength="500"
						placeholder="One or two sentences shown on the card in the blog list. Leave empty to use the start of the article."><?= e((string) ($post['excerpt'] ?? '')) ?></textarea>
				</div>

				<div class="field">
					<label for="p-body">Article *</label>
					<textarea class="textarea editor__body" id="p-body" name="body" rows="16" required
						placeholder="Write your article here…"><?= e((string) ($post['body'] ?? '')) ?></textarea>
					<p class="form-hint">Plain text — leave a blank line between paragraphs and they will show as separate paragraphs on the website.</p>
				</div>

				<div class="editor__row">
					<div class="field">
						<label for="p-cover">Cover photo</label>
						<input class="input" id="p-cover" name="cover" type="file" accept="image/jpeg,image/png,image/webp" />
						<p class="form-hint">JPG, PNG or WebP, up to 3 MB. Shown on the blog card and at the top of the article.</p>
					</div>
					<div class="field">
						<label for="p-status">Status</label>
						<select class="input" id="p-status" name="status">
							<option value="draft" <?= ($post['status'] ?? 'draft') !== 'published' ? 'selected' : '' ?>>Draft — only you can see it</option>
							<option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published — live on the website</option>
						</select>
					</div>
				</div>

				<?php if ($post !== null && $post['cover'] !== ''): ?>
					<div class="cover-row">
						<img class="cover-thumb cover-thumb--big" src="/blog-images/<?= e($post['cover']) ?>" alt="Current cover" />
						<label class="cover-remove">
							<input type="checkbox" name="remove_cover" value="1" />
							Remove this cover photo
						</label>
					</div>
				<?php endif; ?>

				<div class="editor__actions">
					<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" />
					<input type="hidden" name="action" value="save" />
					<button class="btn btn--primary" type="submit">Save post</button>
					<a class="btn btn--ghost" href="/admin/blog.php">Cancel</a>
				</div>
			</form>
		</div>
	</section>
</div>

<script>
	// auto-fill the slug from the title until the slug is edited by hand
	(function () {
		var title = document.getElementById('p-title');
		var slug = document.getElementById('p-slug');
		if (!title || !slug) return;
		var auto = true;
		slug.addEventListener('input', function () { auto = false; });
		title.addEventListener('input', function () {
			if (!auto) return;
			var s = title.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 190);
			slug.value = s || 'post';
		});
	})();
</script>

<?php require __DIR__ . '/_partials/footer.php'; ?>
