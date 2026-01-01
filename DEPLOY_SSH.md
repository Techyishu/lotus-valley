# Deploying to Hostinger via SSH

This guide covers deploying your **Lotus Valley** website using SSH access to Hostinger shared hosting.

## Prerequisites

### 1. Check Your Hosting Plan
- **SSH is available on:** Business, Cloud, and VPS plans
- **Not available on:** Single/Starter shared hosting
- Check your plan in hPanel → Dashboard

### 2. Enable SSH Access (First Time Only)

1. Log in to **Hostinger hPanel**
2. Go to **Advanced** → **SSH Access**
3. **Enable SSH** (if not already enabled)
4. Note down your SSH credentials:
   - **SSH Host:** (e.g., `ssh.hostinger.com` or your server IP)
   - **SSH Port:** Usually `65002` (Hostinger custom port)
   - **Username:** (e.g., `u123456789`)
   - **Password:** Your hosting password

---

## 🚀 Quick SSH Deployment (30 seconds!)

### One-Command Deployment
```bash
# SSH in, pull code, import database - all in one!
ssh -p 65002 u123456789@ssh.hostinger.com << 'ENDSSH'
cd ~/public_html
git pull origin main
mysql -u your_db_user -p'your_db_password' your_db_name < database_disclosures.sql
chmod -R 755 uploads/disclosures
echo "✅ Deployment complete!"
ENDSSH
```

---

## 📋 Step-by-Step Deployment

### Step 1: Push Code to Git (Local)

```bash
# On your local machine
cd /Users/wiredtechie/Desktop/lotus-valley

git add .
git commit -m "feat: Add mandatory disclosure feature"
git push origin main
```

---

### Step 2: Connect to Hostinger via SSH

```bash
# Connect using custom port (Hostinger uses 65002)
ssh -p 65002 u123456789@ssh.hostinger.com

# Enter your password when prompted
```

**Common Hostinger SSH Details:**
- **Port:** `65002` (different from standard port 22!)
- **Host:** Check hPanel → SSH Access for exact hostname
- **Username:** Your hosting username (starts with `u` usually)

---

### Step 3: Navigate to Your Website Directory

```bash
# Find your website directory
cd ~/public_html

# Or if deployed to subfolder:
# cd ~/public_html/yourdomain

# Verify you're in the right place
ls -la
# You should see: index.php, admin/, includes/, etc.
```

---

### Step 4: Pull Latest Code from Git

```bash
# Pull latest changes
git pull origin main

# If you get "Permission denied" or need to set up Git first:
# See "First-Time Git Setup" section below
```

**Expected Output:**
```
From https://github.com/yourusername/lotus-valley
 * branch            main       -> FETCH_HEAD
Updating abc1234..def5678
Fast-forward
 disclosure.php              | 150 +++++++++++++++++
 admin/disclosures.php       | 320 ++++++++++++++++++++++++++++++++
 database_disclosures.sql    |  20 +++
 includes/header.php         |   6 +-
 admin/includes/admin_header.php | 5 +-
 5 files changed, 500 insertions(+), 1 deletion(-)
```

---

### Step 5: Run Database Migration

#### Method A: Using MySQL Command Line

```bash
# Get your database credentials from .htaccess
cat .htaccess | grep DB_

# Run the migration
mysql -u your_db_user -p'your_db_password' your_db_name < database_disclosures.sql

# Or interactive mode (will prompt for password):
mysql -u your_db_user -p your_db_name < database_disclosures.sql
```

#### Method B: Using MySQL Interactive Shell

```bash
# Connect to MySQL
mysql -u your_db_user -p your_db_name

# Enter password when prompted

# Then run these commands:
mysql> SOURCE database_disclosures.sql;
mysql> SHOW TABLES LIKE 'disclosures';
mysql> SELECT COUNT(*) FROM disclosures;
mysql> EXIT;
```

**Expected Output:**
```
Query OK, 0 rows affected (0.01 sec)
Query OK, 1 row affected (0.02 sec)
Query OK, 8 rows affected (0.01 sec)
```

---

### Step 6: Create/Verify Upload Directory

```bash
# Create directory if doesn't exist
mkdir -p uploads/disclosures

# Set correct permissions
chmod 755 uploads/disclosures

# Verify it exists
ls -la uploads/
```

**Expected Output:**
```
drwxr-xr-x  2 u123456789 u123456789  4096 Jan  1 20:55 disclosures
drwxr-xr-x  2 u123456789 u123456789  4096 Dec  9 22:12 gallery
drwxr-xr-x  2 u123456789 u123456789  4096 Dec  9 22:03 toppers
```

---

### Step 7: Verify Deployment

```bash
# Check if files were deployed
ls -la disclosure.php admin/disclosures.php

# Verify database table exists
mysql -u your_db_user -p your_db_name -e "DESCRIBE disclosures;"

# Check permissions
ls -la uploads/disclosures/
```

---

### Step 8: Exit SSH

```bash
# All done!
exit
```

Then visit:
- Frontend: `https://yourdomain.com/disclosure.php`
- Admin: `https://yourdomain.com/admin/disclosures.php`

---

## 🔧 First-Time Git Setup on Server

If Git isn't set up yet on your Hostinger account:

```bash
# Connect via SSH
ssh -p 65002 u123456789@ssh.hostinger.com

# Navigate to website directory
cd ~/public_html

# Clone repository (first time only)
git clone https://github.com/yourusername/lotus-valley.git .

# If directory is not empty, you might need to:
rm -rf * .git  # ⚠️ CAREFUL: This deletes everything
git clone https://github.com/yourusername/lotus-valley.git .

# For private repos, set up authentication:
git config --global user.name "Your Name"
git config --global user.email "your-email@example.com"

# Use personal access token instead of password
git config --global credential.helper store
```

---

## 🎯 Creating an SSH Alias (Recommended!)

Make connecting easier by creating an SSH alias:

```bash
# On your LOCAL machine, edit SSH config
nano ~/.ssh/config

# Add these lines:
Host hostinger
    HostName ssh.hostinger.com
    Port 65002
    User u123456789
    # Optional: Add key-based auth
    # IdentityFile ~/.ssh/id_rsa
```

Now you can connect with just:
```bash
ssh hostinger
```

---

## 🔐 SSH Key-Based Authentication (More Secure!)

Instead of typing password every time:

### 1. Generate SSH Key (Local Machine)
```bash
# Generate key if you don't have one
ssh-keygen -t rsa -b 4096 -C "your-email@example.com"

# Press Enter to accept default location
# Set a passphrase or press Enter for none
```

### 2. Copy Public Key to Hostinger
```bash
# Display your public key
cat ~/.ssh/id_rsa.pub

# Copy the output
```

### 3. Add to Hostinger
1. Log in via SSH with password
2. Add your public key:
```bash
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Paste your public key
# Save: Ctrl+O, Enter, Ctrl+X

# Set correct permissions
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

### 4. Test Key-Based Login
```bash
# Should connect without password!
ssh -p 65002 u123456789@ssh.hostinger.com
```

---

## 🔄 Future Deployments (Super Fast!)

### Option 1: All-in-One Script (Local Machine)

Create a deployment script on your local machine:

```bash
# Create file: deploy.sh
nano deploy.sh
```

Add this content:
```bash
#!/bin/bash

echo "🚀 Deploying Lotus Valley to Hostinger..."

# 1. Push to Git
echo "📤 Pushing to GitHub..."
git add .
git commit -m "Update: $(date '+%Y-%m-%d %H:%M')"
git push origin main

# 2. Deploy via SSH
echo "🔄 Pulling changes on server..."
ssh -p 65002 u123456789@ssh.hostinger.com << 'ENDSSH'
    cd ~/public_html
    git pull origin main
    echo "✅ Code updated!"
ENDSSH

echo "✨ Deployment complete!"
echo "🌐 Visit: https://yourdomain.com"
```

Make it executable:
```bash
chmod +x deploy.sh
```

Use it:
```bash
./deploy.sh
```

### Option 2: Quick Manual Deploy
```bash
# Local: Push changes
git push origin main

# Server: Pull changes (one command)
ssh -p 65002 u123456789@ssh.hostinger.com "cd ~/public_html && git pull origin main"
```

---

## 🛠️ Useful SSH Commands

### File Management
```bash
# List files with permissions
ls -la

# Check disk usage
du -sh *

# Find files
find . -name "*.php"

# Edit file
nano disclosure.php

# View file
cat .htaccess | grep DB_
```

### Database Management
```bash
# Export database
mysqldump -u user -p database_name > backup.sql

# Import database
mysql -u user -p database_name < file.sql

# Run single query
mysql -u user -p database_name -e "SELECT COUNT(*) FROM disclosures;"

# Show tables
mysql -u user -p database_name -e "SHOW TABLES;"
```

### Git Operations
```bash
# Check status
git status

# View recent commits
git log --oneline -5

# Discard local changes
git reset --hard origin/main

# Check current branch
git branch

# Create new branch
git checkout -b feature-name
```

### Permissions
```bash
# Fix common permission issues
chmod 755 uploads/disclosures
chmod 644 uploads/disclosures/*.pdf

# Recursive permission fix
find uploads -type d -exec chmod 755 {} \;
find uploads -type f -exec chmod 644 {} \;
```

### Monitoring
```bash
# Check PHP errors
tail -f error_log

# Check website is running
curl -I https://yourdomain.com

# Check specific file
curl https://yourdomain.com/disclosure.php
```

---

## 🔍 Troubleshooting SSH

### Issue: "Connection Refused"
```bash
# Solution: Check you're using correct port
ssh -p 65002 u123456789@ssh.hostinger.com  # ✅ Correct
ssh u123456789@ssh.hostinger.com            # ❌ Wrong (uses port 22)
```

### Issue: "Permission Denied (publickey)"
```bash
# Solution: Use password authentication
ssh -p 65002 -o PreferredAuthentications=password u123456789@ssh.hostinger.com
```

### Issue: "Git: Permission Denied"
```bash
# Solution: Fix Git permissions
cd ~/public_html
git config --global --add safe.directory ~/public_html
```

### Issue: "Database Import Failed"
```bash
# Check MySQL connection
mysql -u user -p -e "SELECT 1;"

# Verify database name
mysql -u user -p -e "SHOW DATABASES;"

# Check file exists
ls -la database_disclosures.sql
```

---

## 📊 Comparison: SSH vs Web Interface

| Task | SSH Method | Web Interface |
|------|-----------|---------------|
| **Deploy Code** | `git pull` (2 sec) | Click Deploy (10 sec) |
| **Database Migration** | `mysql < file.sql` (2 sec) | Copy/paste in phpMyAdmin (15 sec) |
| **File Permissions** | `chmod 755 folder` (1 sec) | Right-click → Permissions (10 sec) |
| **View Logs** | `tail -f error_log` | Navigate File Manager |
| **Total Time** | ~5 seconds | ~35 seconds |

**SSH is 7x faster!** ⚡

---

## 🎓 Best Practices

1. **Always backup before deploying:**
   ```bash
   mysqldump -u user -p database > backup_$(date +%Y%m%d).sql
   ```

2. **Test locally first:**
   - Never deploy untested code
   - Use local development environment

3. **Use Git branches:**
   ```bash
   git checkout -b feature-name
   # Make changes
   git push origin feature-name
   # Merge after testing
   ```

4. **Keep credentials secure:**
   - Never commit `.htaccess` with passwords
   - Use environment variables
   - Use SSH keys instead of passwords

5. **Monitor after deployment:**
   ```bash
   # Watch error log for 30 seconds
   timeout 30 tail -f error_log
   ```

---

## ✅ Quick Reference

### Connect to Server
```bash
ssh -p 65002 u123456789@ssh.hostinger.com
```

### Deploy in 3 Commands
```bash
cd ~/public_html
git pull origin main
mysql -u user -p database < database_disclosures.sql
```

### Check Everything Works
```bash
ls -la disclosure.php
mysql -u user -p database -e "SELECT COUNT(*) FROM disclosures;"
ls -la uploads/disclosures/
```

---

## 🚀 Next Steps

1. **Set up SSH key authentication** (no more passwords!)
2. **Create deployment script** (one-command deploy)
3. **Set up Git aliases** (faster commands)
4. **Learn more SSH commands** (become a command-line ninja!)

---

**Pro Tip:** Once you get comfortable with SSH, you'll never want to go back to web interfaces! It's faster, more powerful, and makes you look like a hacker in movies 😎

Need help with any specific SSH task? Just ask!
