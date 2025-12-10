# Deploying Anthem Public School Website to Render.com

## Prerequisites

1. **Render.com Account**: Sign up at https://render.com (free tier available)
2. **GitHub Account**: Render.com requires code to be in a Git repository
3. **Database**: You'll need a PostgreSQL or MySQL database (Render provides PostgreSQL by default)

## Step-by-Step Deployment Guide

### Step 1: Push Code to GitHub

1. Initialize Git repository (if not already done):
```bash
git init
git add .
git commit -m "Initial commit - Anthem School Website"
```

2. Create a new repository on GitHub

3. Push your code:
```bash
git remote add origin https://github.com/yourusername/anthem-school.git
git push -u origin main
```

### Step 2: Create PostgreSQL Database on Render

1. Go to Render Dashboard → **New +** → **PostgreSQL**
2. Configure:
   - **Name**: `anthem-school-db`
   - **Database**: `anthem_school_db`
   - **User**: (auto-generated)
   - **Region**: Choose closest to you
   - **Plan**: Free (or paid for production)
3. Click **Create Database**
4. **Important**: Note down:
   - **Internal Database URL**
   - **External Database URL** (for local connections)
   - **Host, Database, User, Password**

### Step 3: Create Web Service on Render

1. Go to Render Dashboard → **New +** → **Web Service**
2. Connect your GitHub repository
3. Configure:
   - **Name**: `anthem-school-website`
   - **Environment**: **Docker**
   - **Region**: Same as database
   - **Branch**: `main` (or your default branch)
   - **Root Directory**: Leave empty (or `.` if needed)
   - **Dockerfile Path**: `Dockerfile`
   - **Plan**: Free (or paid for production)

### Step 4: Configure Environment Variables

In the Web Service settings, add these environment variables:

```
PORT=10000
DB_HOST=your-db-host-from-render
DB_NAME=anthem_school_db
DB_USER=your-db-user
DB_PASS=your-db-password
```

**Note**: For Render PostgreSQL, the connection string format is:
- Host: `dpg-xxxxx-a.oregon-postgres.render.com`
- Port: `5432` (default PostgreSQL port)
- Database: Your database name
- User: Your database user
- Password: Your database password

### Step 5: Update Database Configuration

Since Render uses PostgreSQL by default, you have two options:

#### Option A: Use PostgreSQL (Recommended for Render)

You'll need to update the database schema and PDO connection to use PostgreSQL instead of MySQL. The SQL syntax is slightly different.

#### Option B: Use External MySQL Database

You can use an external MySQL database service like:
- **PlanetScale** (free tier)
- **Aiven** (free tier)
- **Railway** (free tier)

Then use those credentials in environment variables.

### Step 6: Deploy

1. Click **Create Web Service**
2. Render will automatically:
   - Build the Docker image
   - Start the container
   - Deploy your application

3. Wait for deployment to complete (usually 5-10 minutes)

### Step 7: Run Installation

1. Once deployed, visit: `https://your-service-name.onrender.com/install.php`
2. Enter your database credentials
3. Complete the installation
4. **Important**: Delete or protect `install.php` after installation

### Step 8: Access Your Website

- **Website**: `https://your-service-name.onrender.com`
- **Admin Panel**: `https://your-service-name.onrender.com/admin/login.php`
  - Username: `admin`
  - Password: `admin123` (change immediately!)

## Important Notes for Render.com

### Free Tier Limitations

- **Spins down after 15 minutes of inactivity** (takes ~30 seconds to wake up)
- **512MB RAM limit**
- **Limited CPU**
- **750 hours/month free** (enough for 24/7 on one service)

### Production Considerations

For production, consider:
- **Paid plan** ($7/month) for always-on service
- **Custom domain** (free SSL included)
- **Database backups** (configure in Render dashboard)
- **Environment variables** for sensitive data

### File Storage

**Important**: Render.com uses ephemeral storage. Uploaded files will be lost on redeploy unless you:
1. Use **external storage** (AWS S3, Cloudinary, etc.)
2. Use **Render Disk** (persistent storage addon)
3. Store files in database as BLOB (not recommended for images)

### Recommended: Use Cloud Storage for Uploads

For production, configure cloud storage:
- **AWS S3** (with free tier)
- **Cloudinary** (free tier available)
- **DigitalOcean Spaces**
- **Backblaze B2**

## Troubleshooting

### Build Fails
- Check Dockerfile syntax
- Verify all files are in repository
- Check Render build logs

### Database Connection Fails
- Verify environment variables are set correctly
- Check database is running and accessible
- Verify firewall rules allow connections

### 500 Errors
- Check application logs in Render dashboard
- Verify file permissions
- Check PHP error logs

### Images Not Uploading
- Verify `uploads/` directory has write permissions
- Check disk space
- Consider using cloud storage

## Security Checklist

- [ ] Change default admin password
- [ ] Delete or protect `install.php`
- [ ] Use environment variables for sensitive data
- [ ] Enable HTTPS (automatic on Render)
- [ ] Set up database backups
- [ ] Review `.htaccess` security rules
- [ ] Use strong database passwords

## Support

- Render Documentation: https://render.com/docs
- Render Community: https://community.render.com
- Status Page: https://status.render.com

---

**Your website will be live at**: `https://your-service-name.onrender.com`

