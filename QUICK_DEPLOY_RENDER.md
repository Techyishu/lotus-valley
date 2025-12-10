# Quick Deploy to Render.com - 5 Minutes Guide

## 🚀 Fast Track Deployment

### Step 1: Push to GitHub (2 minutes)
```bash
git init
git add .
git commit -m "Ready for Render deployment"
git remote add origin https://github.com/YOUR_USERNAME/anthem-school.git
git push -u origin main
```

### Step 2: Create Database on Render (1 minute)
1. Go to https://dashboard.render.com
2. Click **New +** → **PostgreSQL**
3. Name: `anthem-school-db`
4. Click **Create Database**
5. **Copy the Internal Database URL** (you'll need it)

### Step 3: Create Web Service (1 minute)
1. Click **New +** → **Web Service**
2. Connect your GitHub repo
3. Settings:
   - **Name**: `anthem-school`
   - **Environment**: **Docker**
   - **Dockerfile Path**: `Dockerfile`
   - **Plan**: Free

### Step 4: Add Environment Variables (1 minute)
In Web Service → Environment, add:

```
PORT=10000
DB_HOST=your-db-host-from-render
DB_NAME=anthem_school_db
DB_USER=your-db-user
DB_PASS=your-db-password
```

**For PostgreSQL on Render**, parse the Internal Database URL:
```
postgresql://user:password@host:port/database
```

Extract:
- DB_HOST: The host part (e.g., `dpg-xxxxx-a.oregon-postgres.render.com`)
- DB_NAME: The database name
- DB_USER: The user
- DB_PASS: The password

### Step 5: Deploy & Install
1. Click **Create Web Service**
2. Wait for build (5-10 minutes)
3. Visit: `https://your-service.onrender.com/install.php`
4. Enter database credentials
5. Done! 🎉

---

## ⚠️ Important Notes

### For MySQL Instead of PostgreSQL
If you prefer MySQL, use an external service:
- **PlanetScale** (free): https://planetscale.com
- **Railway** (free): https://railway.app
- Then use those credentials in environment variables

### File Storage Warning
Render.com uses ephemeral storage. Uploaded files will be lost on redeploy.

**Solutions:**
1. Use **Cloudinary** (free tier) for image uploads
2. Use **AWS S3** for file storage
3. Use **Render Disk** addon (persistent storage)

### Free Tier Limitations
- Spins down after 15 min inactivity
- Takes ~30 seconds to wake up
- 750 hours/month free

---

## 🔧 Troubleshooting

**Build fails?**
- Check Dockerfile is in root directory
- Verify all files are committed to Git

**Database connection fails?**
- Double-check environment variables
- Verify database is running
- Check Internal Database URL format

**500 errors?**
- Check logs in Render dashboard
- Verify file permissions
- Check PHP error logs

---

## 📝 After Deployment

1. ✅ Change admin password
2. ✅ Delete/protect `install.php`
3. ✅ Test all features
4. ✅ Set up custom domain (optional)

**Your site will be live at**: `https://your-service.onrender.com`

