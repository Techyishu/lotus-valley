-- Anthem Public School Database Schema (PostgreSQL Version)
-- Run this file once to create all necessary tables

-- Create admin_users table
CREATE TABLE IF NOT EXISTS admin_users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin account (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@anthemschool.com')
ON CONFLICT (username) DO NOTHING;

-- Create toppers table
CREATE TABLE IF NOT EXISTS toppers (
  id SERIAL PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  marks VARCHAR(50) NOT NULL,
  percentage DECIMAL(5,2) DEFAULT NULL,
  year INTEGER NOT NULL,
  board VARCHAR(100) NOT NULL,
  class VARCHAR(50) NOT NULL,
  achievement TEXT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample toppers
INSERT INTO toppers (name, marks, percentage, year, board, class, achievement) VALUES
('Rahul Sharma', '498/500', 99.60, 2024, 'CBSE', 'Class 12', 'All India Rank 15'),
('Priya Patel', '495/500', 99.00, 2024, 'CBSE', 'Class 12', 'State Topper'),
('Amit Kumar', '497/500', 99.40, 2024, 'CBSE', 'Class 10', 'Perfect Score in Mathematics'),
('Sneha Reddy', '493/500', 98.60, 2024, 'CBSE', 'Class 10', 'Science Excellence Award'),
('Arjun Singh', '496/500', 99.20, 2023, 'CBSE', 'Class 12', 'IIT JEE Advanced Qualifier'),
('Neha Gupta', '494/500', 98.80, 2023, 'CBSE', 'Class 12', 'NEET Top 100')
ON CONFLICT DO NOTHING;

-- Create staff table
CREATE TABLE IF NOT EXISTS staff (
  id SERIAL PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  designation VARCHAR(100) NOT NULL,
  department VARCHAR(100) NOT NULL,
  qualification VARCHAR(255) DEFAULT NULL,
  experience VARCHAR(50) DEFAULT NULL,
  email VARCHAR(150) DEFAULT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  specialization TEXT DEFAULT NULL,
  display_order INTEGER DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample staff
INSERT INTO staff (name, designation, department, qualification, experience, specialization, display_order) VALUES
('Dr. Rajesh Kumar', 'Principal', 'Administration', 'Ph.D. in Education, M.Ed.', '25 Years', 'Educational Leadership', 1),
('Mrs. Sunita Verma', 'Vice Principal', 'Administration', 'M.Ed., M.A. English', '20 Years', 'English Literature', 2),
('Mr. Anil Sharma', 'Senior Teacher', 'Science', 'M.Sc. Physics, B.Ed.', '15 Years', 'Physics', 3),
('Ms. Kavita Singh', 'Senior Teacher', 'Mathematics', 'M.Sc. Mathematics, B.Ed.', '12 Years', 'Advanced Mathematics', 4),
('Mr. Deepak Patel', 'Teacher', 'English', 'M.A. English, B.Ed.', '10 Years', 'English Language', 5),
('Mrs. Meera Reddy', 'Teacher', 'Social Science', 'M.A. History, B.Ed.', '8 Years', 'History and Civics', 6)
ON CONFLICT DO NOTHING;

-- Create gallery table
CREATE TABLE IF NOT EXISTS gallery (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  image VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL,
  description TEXT DEFAULT NULL,
  uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create announcements table
CREATE TABLE IF NOT EXISTS announcements (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  date DATE NOT NULL,
  priority VARCHAR(20) DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high')),
  status VARCHAR(20) DEFAULT 'published' CHECK (status IN ('draft', 'published')),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample announcements
INSERT INTO announcements (title, content, date, priority, status) VALUES
('Annual Sports Day 2024', 'The Annual Sports Day will be celebrated on December 15, 2024. All students are requested to participate actively.', '2024-12-15', 'high', 'published'),
('Winter Vacation Notice', 'School will remain closed for winter vacation from December 25, 2024 to January 5, 2025.', '2024-12-20', 'medium', 'published'),
('Parent-Teacher Meeting', 'Parent-Teacher meeting scheduled for December 18, 2024. Parents are requested to attend.', '2024-12-18', 'high', 'published')
ON CONFLICT DO NOTHING;

-- Create events table
CREATE TABLE IF NOT EXISTS events (
  id SERIAL PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  event_date DATE NOT NULL,
  event_time TIME DEFAULT NULL,
  location VARCHAR(255) DEFAULT NULL,
  status VARCHAR(20) DEFAULT 'upcoming' CHECK (status IN ('upcoming', 'completed', 'cancelled')),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample events
INSERT INTO events (title, description, event_date, event_time, location, status) VALUES
('Annual Day Celebration', 'Grand celebration of school annual day with cultural programs and prize distribution.', '2024-12-20', '10:00:00', 'School Auditorium', 'upcoming'),
('Science Exhibition', 'Students will showcase their innovative science projects and experiments.', '2024-12-12', '09:00:00', 'Science Lab', 'upcoming'),
('Republic Day Celebration', 'Flag hoisting ceremony and cultural programs to celebrate Republic Day.', '2025-01-26', '08:00:00', 'School Ground', 'upcoming')
ON CONFLICT DO NOTHING;

-- Create testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
  id SERIAL PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  role VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  rating INTEGER DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
  photo VARCHAR(255) DEFAULT NULL,
  status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
  is_featured BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample testimonials
INSERT INTO testimonials (name, role, content, rating, status, is_featured) VALUES
('Mr. Rajiv Mehta', 'Parent', 'Anthem Public School has provided excellent education to my child. The teachers are dedicated and the infrastructure is outstanding.', 5, 'approved', TRUE),
('Ananya Sharma', 'Alumni - 2023', 'The best years of my life were spent at Anthem. The school not only focused on academics but also on overall personality development.', 5, 'approved', TRUE),
('Mrs. Priya Singh', 'Parent', 'I am extremely satisfied with the quality of education and care my child receives at this school. Highly recommended!', 5, 'approved', TRUE)
ON CONFLICT DO NOTHING;

-- Create site_settings table
CREATE TABLE IF NOT EXISTS site_settings (
  id SERIAL PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT DEFAULT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Insert site settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('school_name', 'Anthem Public School'),
('school_email', 'info@anthempublicschool.com'),
('school_phone', '+91-9876543210'),
('school_address', 'Sector 12, Education City, New Delhi - 110001'),
('facebook_url', 'https://facebook.com/anthempublicschool'),
('twitter_url', 'https://twitter.com/anthemschool'),
('instagram_url', 'https://instagram.com/anthempublicschool'),
('youtube_url', 'https://youtube.com/anthempublicschool'),
('principal_message', 'Welcome to Anthem Public School, where we nurture young minds to become future leaders.'),
('about_text', 'Anthem Public School is a premier educational institution dedicated to providing quality education and holistic development of students.'),
('students_count', '2500'),
('faculty_count', '150'),
('years_established', '25'),
('awards_count', '50')
ON CONFLICT (setting_key) DO UPDATE SET setting_value = EXCLUDED.setting_value;

-- Create admission_inquiries table
CREATE TABLE IF NOT EXISTS admission_inquiries (
  id SERIAL PRIMARY KEY,
  student_name VARCHAR(150) NOT NULL,
  parent_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  class_applying VARCHAR(50) NOT NULL,
  previous_school VARCHAR(255) DEFAULT NULL,
  message TEXT DEFAULT NULL,
  status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'reviewed', 'contacted')),
  submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

