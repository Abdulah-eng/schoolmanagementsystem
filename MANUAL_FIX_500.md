# Manual Fix for 500 Error on Railway

## 🎯 Quick Fix Steps (Do These in Order)

### Step 1: Enable Debug Mode to See Actual Error

1. Go to Railway → Your Service → **Variables** tab
2. Find `APP_DEBUG` variable
3. If it exists, change value to: `true`
4. If it doesn't exist, click **+ New Variable**:
   - Key: `APP_DEBUG`
   - Value: `true`
5. Save (Railway will auto-redeploy)
6. Wait for redeploy (1-2 minutes)
7. Visit your site: `https://web-production-bf625.up.railway.app`
8. **You should now see the actual error message** instead of 500

### Step 2: Set APP_KEY Manually (Most Common Fix)

1. **Generate APP_KEY locally:**
   ```bash
   php artisan key:generate --show
   ```
   Copy the entire output (e.g., `base64:yXPZLo/L352Sy5rUdnLJB96+5IjEEJvkJSHSV6Mw5JY=`)

2. **Add to Railway:**
   - Railway → Your Service → **Variables** tab
   - Click **+ New Variable**
   - Key: `APP_KEY`
   - Value: `base64:yXPZLo/L352Sy5rUdnLJB96+5IjEEJvkJSHSV6Mw5JY=` (paste your key)
   - Click **Add**
   - Railway will auto-redeploy

3. **Verify:**
   - Wait for redeploy
   - Check Deploy Logs - should show "✅ APP_KEY already configured"
   - Visit your site again

### Step 3: Verify All Required Environment Variables

Go to Railway → Your Service → **Variables** tab and ensure these exist:

#### Critical Variables:
```
APP_NAME=EduFocus
APP_ENV=production
APP_DEBUG=false (set to true temporarily for debugging)
APP_URL=https://web-production-bf625.up.railway.app
APP_KEY=base64:... (your generated key)
```

#### Database Variables (if using Railway database):
```
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}
```

#### Other Important:
```
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Step 4: Check Deploy Logs for Errors

1. Railway → Your Service → **Deployments**
2. Click on latest deployment
3. Click **Deploy Logs**
4. Look for:
   - ❌ Error messages
   - ⚠️ Warnings
   - ✅ Success messages
5. Copy any error messages you see

### Step 5: Common Error Messages & Fixes

#### Error: "No application encryption key has been specified"
**Fix:** Follow Step 2 above to set APP_KEY

#### Error: "SQLSTATE[HY000] [2002] Connection refused"
**Fix:** 
- Check database service is running in Railway
- Verify database variables use `${{Postgres.PGHOST}}` format
- Ensure database service is in same project

#### Error: "Class 'X' not found"
**Fix:**
- Check Build Logs - dependencies may not have installed
- Redeploy to rebuild

#### Error: "The stream or file could not be opened"
**Fix:** Storage permissions issue - this is handled by Railway, but you can:
- Add to Variables: `LOG_CHANNEL=stderr`
- Redeploy

#### Error: "Route [X] not defined"
**Fix:**
- Clear route cache: Add temporary variable `CLEAR_CACHE=true` and redeploy
- Or check routes/web.php for typos

### Step 6: Test Database Connection

If database errors appear:

1. Check database service is running:
   - Railway → Your Project
   - Look for database service (PostgreSQL/MySQL)
   - Should show "Active" status

2. Verify database variables:
   - Variables should use Railway references: `${{Postgres.PGHOST}}`
   - NOT hardcoded values like `localhost`

3. Test connection:
   - Check Deploy Logs for "Running database migrations..."
   - Should show "DONE" for migrations
   - If migrations fail, database connection is the issue

### Step 7: Clear All Caches

Add these temporary variables to force cache clear:

1. Railway → Variables → Add:
   ```
   CLEAR_CACHE=true
   ```
2. Redeploy
3. After successful deploy, remove `CLEAR_CACHE` variable

### Step 8: Check HTTP Logs

1. Railway → Your Service → **Deployments** → Latest → **HTTP Logs**
2. Look at the actual requests:
   - Status codes (500, 404, etc.)
   - Response times
   - Error messages

## 🔍 Debugging Checklist

Run through this checklist:

- [ ] APP_DEBUG=true is set (to see errors)
- [ ] APP_KEY is set in Variables
- [ ] APP_URL matches your Railway domain exactly
- [ ] Database variables are configured
- [ ] Database service is running
- [ ] Deploy Logs show no critical errors
- [ ] Build Logs show successful build
- [ ] HTTP Logs show the actual error
- [ ] All migrations completed successfully

## 📋 Step-by-Step: Complete Manual Fix

### Option A: Quick Fix (5 minutes)

1. **Set APP_DEBUG=true** → Redeploy → Check site for error message
2. **Set APP_KEY** (generate locally, add to Railway) → Redeploy
3. **Verify APP_URL** matches your domain → Redeploy
4. **Check Deploy Logs** for any remaining errors

### Option B: Complete Reset (10 minutes)

1. **Remove all cached variables:**
   - Remove: `CLEAR_CACHE` if exists
   - Keep essential variables only

2. **Set these variables in order:**
   ```
   APP_DEBUG=true
   APP_KEY=base64:... (generate locally)
   APP_URL=https://web-production-bf625.up.railway.app
   APP_NAME=EduFocus
   APP_ENV=production
   ```

3. **Verify database variables:**
   - Should use `${{Postgres.PGHOST}}` format
   - NOT `localhost` or hardcoded IPs

4. **Redeploy and check:**
   - Deploy Logs for errors
   - Site for actual error message
   - HTTP Logs for status codes

5. **Once working, set APP_DEBUG=false**

## 🆘 Still Getting 500?

1. **Share the actual error message** (from Step 1 - with APP_DEBUG=true)
2. **Share Deploy Logs** (last 50 lines)
3. **Share your Variables list** (screenshot or list, hide passwords)
4. **Check if database service exists** and is running

## 💡 Pro Tips

- **Always check Deploy Logs first** - they show what happened during startup
- **HTTP Logs show what users see** - the actual 500 responses
- **Build Logs show dependency installation** - check if npm/composer succeeded
- **Set APP_DEBUG=true temporarily** to see real errors
- **Don't forget to set APP_DEBUG=false** after fixing!

## 🎯 Most Likely Fix

Based on common issues, try this first:

1. Generate APP_KEY: `php artisan key:generate --show`
2. Add to Railway Variables: `APP_KEY` = `base64:...`
3. Set `APP_DEBUG=true` to see actual error
4. Redeploy
5. Check site - you'll see the real error
6. Fix that specific error
7. Set `APP_DEBUG=false`
8. Done!
