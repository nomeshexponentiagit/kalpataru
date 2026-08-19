-- Kalpataru Exhibition — backend database schema (Phase 1)
-- Import once in phpMyAdmin (Hostinger: Databases > phpMyAdmin > your DB > Import).

CREATE TABLE IF NOT EXISTS leads (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(120) NOT NULL,
	email VARCHAR(190) NOT NULL,
	phone VARCHAR(40) NOT NULL DEFAULT '',
	company VARCHAR(160) NOT NULL DEFAULT '',
	message TEXT NOT NULL,
	page VARCHAR(255) NOT NULL DEFAULT '',
	ip_hash CHAR(64) NOT NULL DEFAULT '',
	status ENUM('new', 'contacted', 'closed', 'spam') NOT NULL DEFAULT 'new',
	admin_note TEXT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	KEY idx_status (status),
	KEY idx_created (created_at)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Anti-spam rate limiting: one row per submission attempt, pruned by age.
CREATE TABLE IF NOT EXISTS form_submissions (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	ip_hash CHAR(64) NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	KEY idx_ip_time (ip_hash, created_at)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
