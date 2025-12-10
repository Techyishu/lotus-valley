# PostgreSQL Support Added ✅

Your website now supports both **MySQL** and **PostgreSQL** databases!

## What's Been Updated

### 1. **config.php** ✅
- Now detects database type from `DB_TYPE` environment variable
- Supports both MySQL and PostgreSQL connections
- Automatically uses correct DSN format

### 2. **database_pgsql.sql** ✅
- Complete PostgreSQL version of your database schema
- All tables converted (SERIAL instead of AUTO_INCREMENT, etc.)
- Sample data included

### 3. **install.php** ✅
- Added database type selector (MySQL/PostgreSQL)
- Auto-detects port (3306 for MySQL, 5432 for PostgreSQL)
- Uses correct SQL file based on database type

### 4. **Dockerfile** ✅
- Added PostgreSQL PHP extensions (pdo_pgsql)
- Now supports both database types

## For Render.com Deployment

### Environment Variables to Set:

```
PORT=10000
DB_HOST=dpg-d4snl9ccjiac739ndr0g-a
DB_NAME=anthem_school_db
DB_USER=anthem_school_db_user
DB_PASS=FBtOLsLeGb1e375A13vuQfp0vbrMJthD
DB_PORT=5432
DB_TYPE=pgsql
```

### Installation Steps:

1. **Set Environment Variables** in Render Dashboard
2. **Deploy** your service
3. **Visit**: `https://your-service.onrender.com/install.php`
4. **Select**: PostgreSQL
5. **Enter** your database credentials
6. **Install** ✅

## Database Connection Details

From your Render PostgreSQL Internal Database URL:
```
anthem_school_db_user:FBtOLsLeGb1e375A13vuQfp0vbrMJthD@dpg-d4snl9ccjiac739ndr0g-a/anthem_school_db
```

**Extracted values:**
- **Host**: `dpg-d4snl9ccjiac739ndr0g-a`
- **Database**: `anthem_school_db`
- **User**: `anthem_school_db_user`
- **Password**: `FBtOLsLeGb1e375A13vuQfp0vbrMJthD`
- **Port**: `5432` (PostgreSQL default)

## Testing

After deployment, test the connection:
1. Visit your website
2. If you see database errors, check:
   - Environment variables are set correctly
   - Database is running and accessible
   - Port is correct (5432 for PostgreSQL)

## Files Changed

- ✅ `includes/config.php` - PostgreSQL support
- ✅ `database_pgsql.sql` - PostgreSQL schema
- ✅ `install.php` - Database type selection
- ✅ `Dockerfile` - PostgreSQL extensions

## Still Using MySQL?

If you prefer MySQL, you can:
- Use PlanetScale (free MySQL)
- Use Railway MySQL
- Set `DB_TYPE=mysql` in environment variables

The code now supports both! 🎉

