# Security Setup Guide

## Protecting Database Credentials

Your database credentials are currently hardcoded in `includes/config.php`. For production security, follow these steps:

## Option 1: Use Environment Variables (Recommended)

### For Hostinger Shared Hosting:

**✅ Hostinger DOES support environment variables via .htaccess!**

1. **Via .htaccess** (Recommended for Hostinger):
   - Edit `.htaccess` file in your project root
   - Uncomment the environment variables section (around line 58)
   - Replace placeholder values with your actual database credentials:
   ```apache
   <IfModule mod_env.c>
       SetEnv DB_HOST "localhost"
       SetEnv DB_NAME "u532478260_anthem_school"
       SetEnv DB_USER "u532478260_anthem"
       SetEnv DB_PASS "Ishansingh123@"
       SetEnv DB_PORT "3306"
       SetEnv DB_TYPE "mysql"
   </IfModule>
   ```
   - Save the file
   - Your PHP code will automatically read these via `getenv()`

2. **Alternative: Direct in config.php** (Less secure):
   - If .htaccess doesn't work, you can keep credentials in `config.php`
   - But make sure `config.php` is in `.gitignore` (already done)

3. **After setting environment variables:**
   - Edit `includes/config.php`
   - Remove or comment out the hardcoded credentials
   - Keep only the environment variable reads

## Option 2: Keep config.php Out of Git

✅ **Already done!** `config.php` is now in `.gitignore`

- Your `config.php` with real credentials will NOT be committed to Git
- Only `config.php.example` (template) will be in the repository
- Each deployment needs its own `config.php` file

## Current Status

- ✅ `config.php` added to `.gitignore`
- ✅ `config.php.example` template created
- ✅ `.htaccess` configured for environment variables
- ⚠️ Hardcoded credentials still in `config.php` (remove after setting env vars)

## Next Steps

1. **Set environment variables** using one of the methods above
2. **Test** that the website still works
3. **Remove hardcoded credentials** from `config.php` (set to empty strings)
4. **Verify** `config.php` is not in your Git repository

## Security Checklist

- [ ] Environment variables set in hosting
- [ ] Hardcoded credentials removed from `config.php`
- [ ] `config.php` is in `.gitignore` (already done)
- [ ] `config.php` is not accessible via web (protected by `.htaccess`)
- [ ] Tested that website still works after changes

