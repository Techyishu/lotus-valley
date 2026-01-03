# Deployment Guide for Hostinger Shared Hosting

## Prerequisites
- SSH access enabled on Hostinger
- Git repository (GitHub/GitLab/Bitbucket)

## Step 1: Enable SSH on Hostinger
1. Log in to Hostinger hPanel
2. Go to **Advanced** → **SSH Access**
3. Enable SSH access (if not already enabled)
4. Note your SSH credentials:
   - Host: usually `your-domain.com` or server IP
   - Port: `22` or `21` (check hPanel)
   - Username: your hosting username
   - Password: your hosting password

## Step 2: Connect via SSH
```bash
ssh username@your-domain.com
# or with port if different
ssh -p PORT username@your-domain.com
```

## Step 3: Set Up Git on Server
```bash
# Check if git is installed
git --version

# If not installed, ask Hostinger support or use this on some plans:
# (Some shared hosting plans already have git)
```

## Step 4: Clone or Pull Changes

### First Time Setup (Clone):
```bash
cd public_html  # or your website directory
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git .
# Note the "." at the end - it clones into current directory
```

### For Subsequent Updates:
```bash
cd public_html
git pull origin main
```

## Step 5: Import New SQL Tables
```bash
# Access MySQL
mysql -u USERNAME -p DATABASE_NAME

# Then inside MySQL:
source database_new_sections.sql;

# Or via command line directly:
mysql -u USERNAME -p DATABASE_NAME < database_new_sections.sql
```

## Alternative: Use FTP/SFTP with FileZilla (Selective Sync)

If SSH doesn't work, use FileZilla with selective sync:

1. Only upload these NEW/CHANGED files:
   - database_new_sections.sql
   - admin/sports.php
   - admin/slc.php
   - admin/bus-routes.php
   - admin/fee-structure.php
   - admin/includes/admin_header.php
   - sports.php
   - slc.php
   - bus-routes.php
   - fee-structure.php
   - includes/header.php

## Quick Deploy Script (Save as deploy.sh)
```bash
#!/bin/bash
# Run this from your local machine

HOST="username@your-domain.com"
REMOTE_DIR="public_html"

echo "Deploying to Hostinger..."

# Sync only changed files
rsync -avz --delete \
  --exclude='.git' \
  --exclude='node_modules' \
  --exclude='.DS_Store' \
  ./ $HOST:$REMOTE_DIR/

echo "Deployment complete!"
```

## Files Changed (For Manual Upload)
```
NEW FILES:
├── database_new_sections.sql
├── admin/
│   ├── sports.php
│   ├── slc.php
│   ├── bus-routes.php
│   └── fee-structure.php
├── sports.php
├── slc.php
├── bus-routes.php
└── fee-structure.php

MODIFIED FILES:
├── admin/includes/admin_header.php
└── includes/header.php
```

## Post-Deployment Steps
1. Import the SQL file via phpMyAdmin or SSH
2. Create uploads directory if not exists:
   ```bash
   mkdir -p uploads/slc
   chmod 755 uploads
   chmod 755 uploads/slc
   ```
3. Test admin panel pages
4. Test public pages
