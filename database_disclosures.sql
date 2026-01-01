-- Add Mandatory Disclosures Table
-- Run this SQL to add the disclosures feature to your database

CREATE TABLE IF NOT EXISTS `disclosures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Disclosure Data (Optional)
INSERT INTO `disclosures` (`title`, `description`, `category`, `file_path`, `display_order`) VALUES
('Affiliation Letter', 'CBSE Affiliation Certificate', 'Certificates', 'sample_affiliation.pdf', 1),
('NOC (No Objection Certificate)', 'Government NOC for school operation', 'Certificates', 'sample_noc.pdf', 2),
('Building Safety Certificate', 'Certificate of building safety compliance', 'Safety & Compliance', 'sample_building_safety.pdf', 1),
('Fire Safety Certificate', 'Fire department safety certificate', 'Safety & Compliance', 'sample_fire_safety.pdf', 2),
('Fee Structure 2024-25', 'Annual fee structure for all classes', 'Financial', 'sample_fee_structure.pdf', 1),
('Annual Academic Calendar', 'Academic calendar for current session', 'Academic', 'sample_calendar.pdf', 1),
('List of SMC Members', 'School Management Committee members list', 'Governance', 'sample_smc.pdf', 1),
('List of PTA Members', 'Parent Teacher Association members', 'Governance', 'sample_pta.pdf', 2);
