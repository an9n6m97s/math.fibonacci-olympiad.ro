-- SQL pentru crearea tabelului regulation_backups
-- Rulează asta în baza de date înainte să testezi backup-ul

CREATE TABLE IF NOT EXISTS `regulation_backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `backup_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_backup_date` (`backup_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pentru a verifica tabelul după creare:
-- SELECT * FROM regulation_backups ORDER BY backup_date DESC;