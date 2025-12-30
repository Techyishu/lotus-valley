# Anthem Public School Website

A complete, modern, and feature-rich school website built with PHP, MySQL, and TailwindCSS.

## Features

### Frontend
- **Modern, Responsive Design**: Beautiful UI built with TailwindCSS
- **Home Page**: Hero section, stats, toppers showcase, announcements, events, gallery preview, testimonials
- **About Page**: School information, mission/vision, facilities, principal's message
- **Toppers Page**: Filterable showcase of academic achievers
- **Staff Page**: Department-wise faculty listing with detailed profiles
- **Gallery**: Category-based photo gallery with lightbox
- **Contact Page**: Contact form, map integration, office hours
- **Admission Form**: Complete inquiry form with email notifications
- **Search Functionality**: AJAX-powered search across toppers, staff, and announcements

### Admin Panel
- **Secure Authentication**: Session-based login with password hashing
- **Dashboard**: Overview with statistics and recent activity
- **Toppers Management**: Add, edit, delete toppers with photo upload
- **Staff Management**: Complete CRUD operations for faculty members
- **Gallery Management**: Upload and categorize images
- **Announcements**: Create and manage school announcements
- **Events**: Schedule and manage upcoming events
- **Testimonials**: Moderate and approve testimonials
- **Admission Inquiries**: View and manage admission requests
- **Settings**: Configure website content and social media links

### Security Features
- **SQL Injection Prevention**: PDO with prepared statements
- **XSS Protection**: Output sanitization with htmlspecialchars()
- **CSRF Protection**: Token-based form validation
- **Secure File Uploads**: File type validation and sanitization
- **Password Security**: Bcrypt hashing
- **Session Security**: Secure session handling with timeout
- **.htaccess Protection**: Directory access control

## Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Minimum 50MB disk space

### Steps

1. **Upload Files**: Upload all files to your web server (public_html or htdocs)

2. **Create Database**: Create a new MySQL database through cPanel/Plesk

3. **Run Installer**: Visit `http://yourwebsite.com/install.php` in your browser

4. **Fill Database Details**:
   - Database Host: usually `localhost`
   - Database Name: your database name
   - Database Username: your database username
   - Database Password: your database password

5. **Complete Installation**: Click "Install Now"

6. **Login to Admin Panel**: Visit `http://yourwebsite.com/admin/login.php`
   - Default Username: `admin`
   - Default Password: `admin123`
   - ⚠️ **IMPORTANT**: Change the password immediately after first login!

## Default Admin Credentials

```
Username: admin
Password: admin123
```

**⚠️ Security Warning**: Change these credentials immediately after installation!

## File Structure

```
/
├── admin/                  # Admin panel files
│   ├── includes/          # Admin header/footer
│   ├── login.php          # Admin login
│   ├── dashboard.php      # Admin dashboard
│   ├── toppers.php        # Manage toppers
│   ├── staff.php          # Manage staff
│   ├── gallery.php        # Manage gallery
│   ├── announcements.php  # Manage announcements
│   ├── events.php         # Manage events
│   ├── testimonials.php   # Manage testimonials
│   ├── admissions.php     # View inquiries
│   └── settings.php       # Website settings
├── includes/              # Core PHP files
│   ├── config.php         # Database configuration
│   ├── functions.php      # Common functions
│   ├── auth.php           # Authentication
│   ├── header.php         # Website header
│   └── footer.php         # Website footer
├── uploads/               # Uploaded files
│   ├── toppers/           # Topper photos
│   ├── staff/             # Staff photos
│   ├── gallery/           # Gallery images
│   └── testimonials/      # Testimonial photos
├── assets/                # Static assets (optional)
├── index.php              # Homepage
├── about.php              # About page
├── toppers.php            # Toppers page
├── staff.php              # Staff page
├── gallery.php            # Gallery page
├── contact.php            # Contact page
├── admission.php          # Admission form
├── search.php             # Search handler
├── database.sql           # Database schema
├── install.php            # Installation script
├── .htaccess              # Apache configuration
└── README.md              # This file
```

## Configuration

### Database Configuration
After installation, database settings are stored in `/includes/config.php`

### Website Settings
Configure from Admin Panel → Settings:
- School name, contact details
- Social media links
- About text and principal's message
- Statistics (students count, faculty count, etc.)

## Usage Guide

### Managing Toppers
1. Login to admin panel
2. Go to "Toppers" section
3. Click "Add New Topper"
4. Fill in details and upload photo
5. Click "Save"

### Managing Staff
1. Go to "Staff" section
2. Click "Add New Staff"
3. Enter faculty details
4. Upload photo and save

### Uploading Gallery Images
1. Go to "Gallery" section
2. Click "Add Images"
3. Select category
4. Upload multiple images
5. Save

### Viewing Admission Inquiries
1. Go to "Admission Inquiries"
2. View all submitted forms
3. Update status (Pending/Reviewed/Contacted)
4. Contact applicants directly via email/phone

## Security Recommendations

1. **Change Default Password**: Immediately after installation
2. **Delete install.php**: After successful installation (or the script will auto-lock)
3. **Regular Backups**: Backup database and uploads folder regularly
4. **Update PHP**: Keep PHP and MySQL updated
5. **Strong Passwords**: Use strong passwords for admin accounts
6. **HTTPS**: Enable SSL certificate for secure connections
7. **File Permissions**: Set appropriate permissions (755 for directories, 644 for files)

## Troubleshooting

### Installation Issues
- **Database connection failed**: Check database credentials
- **Permission denied**: Ensure proper file permissions (755/644)
- **Upload errors**: Check PHP upload_max_filesize and post_max_size

### Admin Panel Issues
- **Cannot login**: Verify database connection, check admin credentials
- **Session timeout**: Increase session timeout in config
- **File upload fails**: Check upload directory permissions and PHP limits

### Frontend Issues
- **Images not displaying**: Check file paths and upload directory permissions
- **Search not working**: Verify database connection and search.php file
- **Forms not submitting**: Check CSRF token generation and validation

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Credits

- **TailwindCSS**: CSS Framework
- **Font Awesome**: Icons
- **Google Fonts**: Typography (Poppins)

## Support

For issues or questions:
- Check this README first
- Verify all installation steps were completed
- Check file and directory permissions
- Review PHP error logs

## License

This project is provided as-is for educational and commercial use.

---

**Developed for Lotus Valley School**

*Version 1.0 - December 2024*

