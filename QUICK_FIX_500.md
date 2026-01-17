# Quick Fix for 500 Error on Railway

## 🚨 Problem
Getting 500 error after successful deployment. Server is running but application returns 500.

## ✅ Solution

### Step 1: Check Current Status
1. Go to Railway → Your Service → **Deployments**
2. Click on the latest deployment
3. Check **Deploy Logs** for errors

### Step 2: Set APP_KEY Manually (If Needed)

If you see "APP_KEY not found" or key generation failed:

1. **Generate APP_KEY locally:**
   ```bash
   php artisan key:generate --show
   ```
   This will output something like: `base64:yXPZLo/L352Sy5rUdnLJB96+5IjEEJvkJSHSV6Mw5JY=`

2. **Add to Railway:**
   - Go to Railway → Your Service → **Variables** tab
   - Click **+ New Variable**
   - Key: `APP_KEY`
   - Value: `base64:yXPZLo/L352Sy5rUdnLJB96+5IjEEJvkJSHSV6Mw5JY=` (paste your generated key)
   - Click **Add**

3. **Redeploy:**
   - Railway will automatically redeploy when you add variables
   - Or go to Deployments → Click **Redeploy**

### Step 3: Verify Environment Variables

Make sure these are set in Railway Variables:

**Required:**
```
APP_NAME=EduFocus
APP_ENV=production
APP_DEBUG=false
APP_URL=https://web-production-bf625.up.railway.app
```

**Database (if using Railway database):**
```
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}
```

### Step 4: Check Logs After Redeploy

After redeploy, check:
1. **Deploy Logs** - Should show "✅ APP_KEY already configured" or "✅ Application key generated"
2. **HTTP Logs** - Should show successful requests (200 status)

## 🔍 Debugging

### Enable Debug Mode Temporarily

To see actual error messages:

1. Add to Railway Variables:
   ```
   APP_DEBUG=true
   ```
2. Redeploy
3. Visit your site - you'll see the actual error
4. **IMPORTANT:** Set `APP_DEBUG=false` back after fixing!

### Common Issues

1. **"No application encryption key has been specified"**
   → APP_KEY not set. Follow Step 2 above.

2. **"SQLSTATE[HY000] [2002] Connection refused"**
   → Database not connected. Check database variables.

3. **"Class 'X' not found"**
   → Dependencies not installed. Check build logs.

4. **"Route [X] not defined"**
   → Routes not cached properly. The startup script handles this.

## 📝 Quick Checklist

- [ ] APP_KEY is set in Railway Variables
- [ ] APP_URL matches your Railway domain
- [ ] Database variables are configured
- [ ] APP_DEBUG=false (for production)
- [ ] Deployment logs show no errors
- [ ] Server is running (check Deploy Logs)

## 🆘 Still Not Working?

1. Check **Deploy Logs** for specific error messages
2. Temporarily set `APP_DEBUG=true` to see error details
3. Verify all environment variables are set correctly
4. Check that database service is running and connected
