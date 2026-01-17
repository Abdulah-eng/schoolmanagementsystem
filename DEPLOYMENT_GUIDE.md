# Deployment Guide for EduFocus Laravel Application

## ❌ Why NOT Vercel?

Vercel is designed for:
- Next.js, React, Vue (JavaScript frameworks)
- Serverless functions
- Static sites

Laravel needs:
- Traditional PHP server environment
- Persistent file storage
- Database connections
- Background job processing
- Session management

**Vercel is NOT suitable for Laravel applications.**

---

## ✅ Recommended Deployment Platforms

### 1. **Railway** (⭐ Easiest - Recommended)
**Best for:** Quick deployment, automatic SSL, easy database setup

**Pros:**
- Very easy setup (connect GitHub repo)
- Automatic HTTPS/SSL
- Built-in PostgreSQL/MySQL
- Free tier available ($5 credit/month)
- Auto-deploys on git push

**Steps:**
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub
3. Click "New Project" → "Deploy from GitHub repo"
4. Select your repository
5. Railway auto-detects Laravel
6. Add PostgreSQL or MySQL service
7. Set environment variables
8. Deploy!

**Cost:** Free tier with $5 credit/month, then pay-as-you-go

---

### 2. **Render** (⭐ Great Free Tier)
**Best for:** Free hosting, easy setup

**Pros:**
- Free tier available (with limitations)
- Automatic SSL
- Easy database setup
- Auto-deploys on git push

**Steps:**
1. Go to [render.com](https://render.com)
2. Sign up with GitHub
3. Click "New" → "Web Service"
4. Connect your GitHub repo
5. Settings:
   - **Build Command:** `composer install --no-dev --optimize-autoloader && php artisan key:generate --force && npm install && npm run build`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`
6. Add PostgreSQL database
7. Set environment variables
8. Deploy!

**Cost:** Free tier (spins down after inactivity), paid from $7/month

---

### 3. **Laravel Forge** (⭐ Best for Laravel)
**Best for:** Production-ready, Laravel-optimized

**Pros:**
- Built specifically for Laravel
- One-click deployments
- Server management
- SSL certificates
- Queue workers, cron jobs

**Steps:**
1. Go to [forge.laravel.com](https://forge.laravel.com)
2. Connect DigitalOcean/Linode/AWS account
3. Create server
4. Connect GitHub repo
5. Deploy!

**Cost:** $12/month + server costs (~$6-12/month)

---

### 4. **DigitalOcean App Platform**
**Best for:** Scalable, managed infrastructure

**Pros:**
- Managed platform
- Auto-scaling
- Built-in databases
- Easy SSL

**Steps:**
1. Go to [cloud.digitalocean.com](https://cloud.digitalocean.com)
2. Create App Platform
3. Connect GitHub repo
4. Configure build/run commands
5. Add database
6. Deploy!

**Cost:** From $5/month

---

### 5. **Shared Hosting** (Budget Option)
**Best for:** Low cost, simple sites

**Providers:**
- Hostinger
- SiteGround
- A2 Hosting
- Bluehost

**Steps:**
1. Purchase hosting plan
2. Upload files via FTP/SFTP
3. Create database via cPanel
4. Configure `.env`
5. Run migrations

**Cost:** $3-10/month

---

## 📋 Pre-Deployment Checklist

### 1. Environment Variables
Create `.env` file with:
```env
APP_NAME=EduFocus
APP_ENV=production
APP_KEY=base64:... (generate with: php artisan key:generate)
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Build Assets
```bash
npm install
npm run build
```

### 3. Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 4. Database Setup
```bash
php artisan migrate --force
php artisan db:seed --force  # Optional: seed demo data
```

---

## 🚀 Quick Start: Railway Deployment

> **📖 For detailed Railway deployment instructions, see [RAILWAY_DEPLOYMENT.md](./RAILWAY_DEPLOYMENT.md)**

### Step 1: Prepare Repository
```bash
# Make sure .env.example exists
# Commit all changes
git add .
git commit -m "Ready for deployment"
git push origin main
```

### Step 2: Deploy on Railway
1. Visit [railway.app](https://railway.app)
2. Sign up with GitHub
3. Click "New Project" → "Deploy from GitHub repo"
4. Select your repository
5. Railway will auto-detect Laravel

### Step 3: Add Database
1. Click "+ New" → "Database" → "PostgreSQL" (or MySQL)
2. Railway creates database automatically

### Step 4: Configure Environment
In Railway project settings → "Variables" tab, add:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=pgsql  # or mysql
DB_HOST=${{Postgres.PGHOST}}  # Railway auto-provides
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}
```

**Note**: The `railway-start.sh` script will automatically:
- Generate APP_KEY if not set
- Run migrations
- Cache configuration
- Start the server

### Step 5: Deploy
Railway will automatically build and deploy. Check logs if issues occur.

---

## 🔧 Platform-Specific Configurations

### For Render
Create `render.yaml`:
```yaml
services:
  - type: web
    name: edufocus
    env: php
    buildCommand: composer install --no-dev --optimize-autoloader && php artisan key:generate --force && npm install && npm run build
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
```

### For Railway
Create `railway.json` (optional):
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=$PORT",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

---

## 📝 Important Notes

1. **Never commit `.env` file** - It contains sensitive data
2. **Set `APP_DEBUG=false`** in production
3. **Use strong `APP_KEY`** - Generate with `php artisan key:generate`
4. **Configure proper `APP_URL`** - Must match your domain
5. **Set up database** - Use managed database service
6. **Configure email** - Use services like Mailtrap, SendGrid, or AWS SES
7. **Set up storage** - Use S3 or similar for file uploads in production
8. **Enable HTTPS** - Most platforms do this automatically

---

## 🆘 Troubleshooting

### Issue: 500 Error
- Check `APP_DEBUG=true` temporarily to see errors
- Verify database connection
- Check `APP_KEY` is set
- Review logs: `php artisan log:show`

### Issue: Assets Not Loading
- Run `npm run build` before deployment
- Check `APP_URL` matches your domain
- Verify Vite configuration

### Issue: Database Connection Failed
- Verify database credentials
- Check database is accessible from your app
- Ensure migrations ran: `php artisan migrate:status`

---

## 🎯 Recommended: Start with Railway

**Why Railway?**
- ✅ Easiest setup (5 minutes)
- ✅ Auto-detects Laravel
- ✅ Free tier to test
- ✅ Automatic SSL
- ✅ Built-in database
- ✅ Auto-deploys on git push

**Get Started:**
1. Push code to GitHub
2. Sign up at [railway.app](https://railway.app)
3. Deploy from GitHub
4. Add database
5. Set environment variables
6. Done! 🎉

---

## 📞 Need Help?

- Railway Docs: https://docs.railway.app
- Render Docs: https://render.com/docs
- Laravel Deployment: https://laravel.com/docs/deployment
