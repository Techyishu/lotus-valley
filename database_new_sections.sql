-- Lotus Valley School - New Sections Database Schema
-- Run this file to add tables for Sports, SLC, Bus Route, and Fee Structure

-- Create sports table
CREATE TABLE IF NOT EXISTS `sports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create slc table (Student Life Circle/Activities)
CREATE TABLE IF NOT EXISTS `slc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('image','pdf') DEFAULT 'image',
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create bus_routes table
CREATE TABLE IF NOT EXISTS `bus_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_number` varchar(50) NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `stops` text NOT NULL,
  `bus_number` varchar(50) DEFAULT NULL,
  `driver_name` varchar(150) DEFAULT NULL,
  `driver_phone` varchar(20) DEFAULT NULL,
  `fare` decimal(10,2) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create fee_structure table
CREATE TABLE IF NOT EXISTS `fee_structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(100) NOT NULL,
  `admission_fee` decimal(10,2) DEFAULT 0.00,
  `tuition_fee` decimal(10,2) DEFAULT 0.00,
  `annual_charges` decimal(10,2) DEFAULT 0.00,
  `total_fee` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
