-- Lotus Valley School Database Schema
-- Run this file once to create all necessary tables

-- Create admin_users table
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin account (password: admin123)
INSERT INTO `admin_users` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@lotusvalley.edu');

-- Create toppers table
CREATE TABLE IF NOT EXISTS `toppers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `marks` varchar(50) NOT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `year` int(4) NOT NULL,
  `board` varchar(100) NOT NULL,
  `class` varchar(50) NOT NULL,
  `achievement` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample toppers
INSERT INTO `toppers` (`name`, `marks`, `percentage`, `year`, `board`, `class`, `achievement`) VALUES
('Rahul Sharma', '498/500', 99.60, 2024, 'CBSE', 'Class 12', 'All India Rank 15'),
('Priya Patel', '495/500', 99.00, 2024, 'CBSE', 'Class 12', 'State Topper'),
('Amit Kumar', '497/500', 99.40, 2024, 'CBSE', 'Class 10', 'Perfect Score in Mathematics'),
('Sneha Reddy', '493/500', 98.60, 2024, 'CBSE', 'Class 10', 'Science Excellence Award'),
('Arjun Singh', '496/500', 99.20, 2023, 'CBSE', 'Class 12', 'IIT JEE Advanced Qualifier'),
('Neha Gupta', '494/500', 98.80, 2023, 'CBSE', 'Class 12', 'NEET Top 100');

-- Create staff table
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `designation` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `experience` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialization` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample staff
INSERT INTO `staff` (`name`, `designation`, `department`, `qualification`, `experience`, `specialization`, `display_order`) VALUES
('Dr. Rajesh Kumar', 'Principal', 'Administration', 'Ph.D. in Education, M.Ed.', '25 Years', 'Educational Leadership', 1),
('Mrs. Sunita Verma', 'Vice Principal', 'Administration', 'M.Ed., M.A. English', '20 Years', 'English Literature', 2),
('Mr. Anil Sharma', 'Senior Teacher', 'Science', 'M.Sc. Physics, B.Ed.', '15 Years', 'Physics', 3),
('Ms. Kavita Singh', 'Senior Teacher', 'Mathematics', 'M.Sc. Mathematics, B.Ed.', '12 Years', 'Advanced Mathematics', 4),
('Mr. Deepak Patel', 'Teacher', 'English', 'M.A. English, B.Ed.', '10 Years', 'English Language', 5),
('Mrs. Meera Reddy', 'Teacher', 'Social Science', 'M.A. History, B.Ed.', '8 Years', 'History and Civics', 6);

-- Create gallery table
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create announcements table
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample announcements
INSERT INTO `announcements` (`title`, `content`, `date`, `priority`, `status`) VALUES
('Annual Sports Day 2024', 'The Annual Sports Day will be celebrated on December 15, 2024. All students are requested to participate actively.', '2024-12-15', 'high', 'published'),
('Winter Vacation Notice', 'School will remain closed for winter vacation from December 25, 2024 to January 5, 2025.', '2024-12-20', 'medium', 'published'),
('Parent-Teacher Meeting', 'Parent-Teacher meeting scheduled for December 18, 2024. Parents are requested to attend.', '2024-12-18', 'high', 'published');

-- Create events table
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('upcoming','completed','cancelled') DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample events
INSERT INTO `events` (`title`, `description`, `event_date`, `event_time`, `location`, `status`) VALUES
('Annual Day Celebration', 'Grand celebration of school annual day with cultural programs and prize distribution.', '2024-12-20', '10:00:00', 'School Auditorium', 'upcoming'),
('Science Exhibition', 'Students will showcase their innovative science projects and experiments.', '2024-12-12', '09:00:00', 'Science Lab', 'upcoming'),
('Republic Day Celebration', 'Flag hoisting ceremony and cultural programs to celebrate Republic Day.', '2025-01-26', '08:00:00', 'School Ground', 'upcoming');

-- Create testimonials table
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `role` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `rating` int(1) DEFAULT 5,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample testimonials
INSERT INTO `testimonials` (`name`, `role`, `content`, `rating`, `status`, `is_featured`) VALUES
('Mr. Rajiv Mehta', 'Parent', 'Lotus Valley has provided excellent education to my child. The teachers are dedicated and the infrastructure is outstanding.', 5, 'approved', 1),
('Ananya Sharma', 'Alumni - 2023', 'The best years of my life were spent at Lotus Valley. The school not only focused on academics but also on overall personality development.', 5, 'approved', 1),
('Mrs. Priya Singh', 'Parent', 'I am extremely satisfied with the quality of education and care my child receives at this school. Highly recommended!', 5, 'approved', 1);

-- Create site_settings table
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert site settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('school_name', 'Lotus Valley International School'),
('school_email', 'info@lotusvalley.edu'),
('school_phone', '9896421785'),
('school_address', 'Lotus Valley International School, Choura (Under the aegis of Lotus Valley Social & Educational Trust)'),
('facebook_url', 'https://facebook.com/lotusvalleyschool'),
('twitter_url', 'https://twitter.com/lotusvalley'),
('instagram_url', 'https://instagram.com/lotusvalleyschool'),
('youtube_url', 'https://youtube.com/lotusvalleyschool'),
('principal_message', 'Welcome to Lotus Valley International School. We aim to create a joyful learning environment where curiosity is encouraged and talents are nurtured.'),
('about_text', 'Lotus Valley International School has been established at Choura campus with a fresh vision, modern outlook, and a strong academic foundation.'),
('students_count', '1500'),
('faculty_count', '100'),
('years_established', '25'),
('awards_count', '50');

-- Create admission_inquiries table
CREATE TABLE IF NOT EXISTS `admission_inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(150) NOT NULL,
  `parent_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `class_applying` varchar(50) NOT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','reviewed','contacted') DEFAULT 'pending',
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

