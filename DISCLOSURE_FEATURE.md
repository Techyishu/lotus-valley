# Mandatory Public Disclosure Feature

## Overview
This feature allows school administrators to upload and manage mandatory disclosure documents (like affiliation certificates, NOCs, safety certificates, etc.) through the admin panel. These documents are displayed on a public-facing page organized by category.

## Files Created

### Frontend
- **disclosure.php** - Public page displaying all disclosures grouped by category
  - Beautiful design matching the site's theme
  - Documents organized by category (Certificates, Safety & Compliance, etc.)
  - Responsive card layout with hover effects
  - Links to view/download documents in new tab

### Admin Panel
- **admin/disclosures.php** - Admin interface for managing disclosures
  - Add new disclosures with file upload
  - Edit existing disclosures
  - Delete disclosures (also removes files)
  - Supports PDF, JPG, PNG, DOC, DOCX files
  - Categorization for organization
  - Display order control
  - Optional descriptions

### Database
- **database_disclosures.sql** - SQL migration to create the `disclosures` table
  - Includes sample data for testing

### Navigation Updates
- Added "Disclosure" link to main website navigation (desktop + mobile)
- Added "Disclosures" link to admin sidebar under Content Management

## Setup Instructions

### 1. Run Database Migration
Execute the SQL file to create the disclosures table:

```bash
# For MySQL
mysql -u your_username -p lotus_valley < database_disclosures.sql

# Or run the SQL directly in phpMyAdmin or your database tool
```

The migration creates:
- `disclosures` table with fields: id, title, description, category, file_path, display_order, created_at
- Sample disclosure records (optional - you can delete these after testing)

### 2. Create Uploads Directory
The upload directory will be created automatically when you upload your first file, but you can create it manually:

```bash
mkdir -p uploads/disclosures
chmod 755 uploads/disclosures
```

### 3. Access the Features

#### Admin Panel
1. Log in to admin panel: `http://yoursite.com/admin/`
2. Click "Disclosures" in the sidebar (under Content Management)
3. Add your first disclosure:
   - Enter title (e.g., "CBSE Affiliation Certificate")
   - Select category (e.g., "Certificates")
   - Upload file (PDF, JPG, PNG, DOC, DOCX)
   - Set display order (lower numbers appear first)
   - Add optional description

#### Public Page
Visit: `http://yoursite.com/disclosure.php`

Or click "Disclosure" in the main navigation menu

## Features

### For Administrators
- ✅ Easy file upload interface
- ✅ Categorization for organization
- ✅ Display order control
- ✅ Edit existing disclosures
- ✅ Replace files without changing other details
- ✅ Delete with automatic file cleanup
- ✅ Support for multiple file formats
- ✅ Grouped list view by category

### For Website Visitors
- ✅ Clean, modern interface
- ✅ Documents organized by category
- ✅ Quick access links
- ✅ Responsive design (mobile-friendly)
- ✅ Hover effects for better UX
- ✅ Opens documents in new tab

## Usage Tips

### Recommended Categories
- **Certificates** - Affiliation, NOC, Recognition
- **Safety & Compliance** - Building Safety, Fire Safety, Water Health
- **Financial** - Fee Structure, Conveyance Charges
- **Academic** - Calendar, Curriculum, Examination Details
- **Governance** - SMC List, PTA List, Staff Details
- **Infrastructure** - Land Certificate, Building Plans
- **Results** - Board Results by Year

### Best Practices
1. Use clear, descriptive titles
2. Group related documents in same category
3. Keep file sizes reasonable (under 5MB)
4. Use PDFs for official documents when possible
5. Set appropriate display order for logical grouping
6. Add descriptions to help users understand content

### Display Order Examples
```
Category: Certificates
- Affiliation Letter (order: 1)
- NOC (order: 2)  
- Recognition Certificate (order: 3)

Category: Safety & Compliance
- Building Safety (order: 1)
- Fire Safety (order: 2)
```

## File Structure
```
lotus-valley/
├── disclosure.php              # Public disclosure page
├── database_disclosures.sql    # Database migration
├── uploads/
│   └── disclosures/           # Uploaded files stored here
├── admin/
│   └── disclosures.php        # Admin management page
└── includes/
    └── header.php             # Updated with disclosure nav link
```

## Security Notes
- Only logged-in admins can manage disclosures
- File uploads are validated for type and size
- Filenames are sanitized to prevent security issues
- Files are stored with unique names to prevent conflicts
- Old files are automatically deleted when replaced

## Troubleshooting

### "Table 'disclosures' doesn't exist"
- Make sure you ran the database migration (database_disclosures.sql)

### "Failed to upload file"
- Check that uploads/disclosures directory exists and is writable (chmod 755)
- Verify file size is under 5MB
- Ensure file type is allowed (PDF, JPG, PNG, DOC, DOCX)

### Files not displaying on public page
- Verify files exist in uploads/disclosures/
- Check file permissions (644 for files, 755 for directories)
- Clear browser cache

### Admin page not accessible
- Ensure you're logged in to admin panel
- Check that admin includes are properly set up

## Future Enhancements (Optional)
- Bulk upload functionality
- File version history
- Download statistics
- Expiration date tracking
- Automatic notifications for updates
- Multi-language support
