-- Create processed_emails table to track processed email IDs
-- This prevents duplicate processing of the same email

CREATE TABLE IF NOT EXISTS `processed_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_uid` varchar(255) NOT NULL COMMENT 'Email UID or message ID',
  `server` varchar(255) NOT NULL COMMENT 'IMAP server identifier',
  `conversation_id` varchar(255) NOT NULL COMMENT 'Associated conversation ID',
  `processed_at` datetime NOT NULL COMMENT 'When the email was processed',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email_server` (`email_uid`, `server`),
  KEY `conversation_id` (`conversation_id`),
  KEY `processed_at` (`processed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tracks processed emails to prevent duplicates';

-- Add index for better performance
ALTER TABLE `processed_emails` ADD INDEX `idx_conversation_processed` (`conversation_id`, `processed_at`);
