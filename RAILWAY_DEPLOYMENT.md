# Railway Deployment Guide

## 🚀 Quick Deployment Steps

### 1. Connect Repository
1. Go to [railway.app](https://railway.app)
2. Sign up/Login with GitHub
3. Click "New Project" → "Deploy from GitHub repo"
4. Select `Abdulah-eng/schoolmanagementsystem`

### 2. Add Database
1. In your Railway project, click "+ New"
2. Select "Database" → "PostgreSQL" (or MySQL)
3. Railway will automatically create the database

### 3. Configure Environment Variables

Go to your service → "Variables" tab and add:

#### Required Variables:
```env
APP_NAME=EduFocus
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

# Database (Railway auto-provides these, use the reference variables)
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (Optional - configure with your email service)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS="noreply@edufocus.com"
MAIL_FROM_NAME="EduFocus"

# OpenAI (Optional - for AI features)
OPENAI_API_KEY=your-openai-api-key-here
```

#### Important Notes:
- **APP_KEY**: Railway will auto-generate this on first deploy, or you can set it manually
- **Database Variables**: Use Railway's reference variables (e.g., `${{Postgres.PGHOST}}`) for automatic connection
- **APP_URL**: Update this after deployment with your actual Railway domain

### 4. Deploy

Railway will automatically:
1. Build your application
2. Install dependencies
3. Run migrations (via `railway-start.sh`)
4. Start the server

### 5. View Logs

If deployment fails:
1. Go to your service → "Deployments"
2. Click on the failed deployment
3. Check "Build Logs" and "Deploy Logs"

## 🔧 Troubleshooting

### Issue: "APP_KEY not found" or 500 Error
**Solution**: The startup script will auto-generate it. If it persists:

**Option 1: Let script auto-generate (Recommended)**
- The `railway-start.sh` script will automatically create .env and generate APP_KEY
- Just redeploy and check logs

**Option 2: Manually set APP_KEY**
1. Generate a key locally:
   ```bash
   php artisan key:generate --show
   ```
2. Copy the output (e.g., `base64:yXPZLo/L352Sy5rUdnLJB96+5IjEEJvkJSHSV6Mw5JY=`)
3. Go to Railway → Your Service → Variables tab
4. Add new variable:
   - Key: `APP_KEY`
   - Value: `base64:yXPZLo/L352Sy5rUdnLJB96+5IjEEJvkJSHSV6Mw5JY=` (your generated key)
5. Redeploy

**Option 3: Check deployment logs**
- Go to Deployments → Latest → Deploy Logs
- Look for "📝 Generating application key..." message
- If it fails, you'll see the error there

### Issue: "Database connection failed"
**Solution**: 
1. Verify database service is running
2. Check database variables use Railway references: `${{Postgres.PGHOST}}`
3. Ensure database service is in the same project

### Issue: "Migration failed"
**Solution**:
1. Check database connection variables
2. Verify database exists
3. Check deploy logs for specific error

### Issue: "Assets not loading"
**Solution**:
1. Verify `npm run build` completed successfully in build logs
2. Check `APP_URL` matches your Railway domain
3. Clear browser cache

### Issue: "Port already in use"
**Solution**: Railway automatically sets `$PORT` variable. Don't hardcode port numbers.

## 📝 Post-Deployment

### 1. Set Custom Domain (Optional)
1. Go to your service → "Settings" → "Networking"
2. Click "Generate Domain" or add custom domain
3. Update `APP_URL` variable to match

### 2. Enable HTTPS
Railway automatically provides HTTPS for all deployments.

### 3. Monitor Application
- View logs: Service → "Logs" tab
- Check metrics: Service → "Metrics" tab
- Monitor errors: Service → "Logs" → Filter by "ERROR"

## 🎯 Environment Variables Reference

### Database (PostgreSQL - Auto-provided by Railway)
```
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}
```

### Database (MySQL - Auto-provided by Railway)
```
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

## ✅ Deployment Checklist

- [ ] Repository connected to Railway
- [ ] Database service added
- [ ] Environment variables configured
- [ ] APP_URL set correctly
- [ ] Database connection variables use Railway references
- [ ] Build completed successfully
- [ ] Migrations ran successfully
- [ ] Application accessible at Railway domain
- [ ] HTTPS working
- [ ] Can login with demo users

## 🆘 Need Help?

- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- Check deployment logs for specific errors
