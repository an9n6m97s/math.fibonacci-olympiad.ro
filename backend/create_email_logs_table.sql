-- SQL pentru email logs system
-- Rulează asta în baza de date pentru email management system

CREATE TABLE IF NOT EXISTS `email_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipients` longtext NOT NULL,
  `subject` varchar(500) NOT NULL,
  `content` longtext NOT NULL,
  `sent_by` int(11) NOT NULL,
  `sent_count` int(11) DEFAULT 0,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sent_by` (`sent_by`),
  KEY `idx_sent_at` (`sent_at`),
  KEY `idx_subject` (`subject`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pentru a verifica tabelul după creare:
-- SELECT * FROM email_logs ORDER BY sent_at DESC;