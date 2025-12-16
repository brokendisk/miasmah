# WordPress Migration Plan: Localhost to Staging Server

## Overview

Migrate WordPress installation from `miasmah.test` to `staging.miasmah.com` using manual deployment via SSH. This includes transferring files, exporting/importing database, and updating URLs. The existing WordPress installation on the remote server will be overwritten.

## Prerequisites

- SSH access to remote server
- LAMP stack installed on remote server
- Database credentials for remote MySQL server
- Remote server web root path (typically `/var/www/html/staging.miasmah.com` or similar)

## Migration Steps

### 1. Export Local Database

Export the local WordPress database to a SQL file:

```bash
mysqldump -u root miasmah > miasmah_backup.sql
```

### 2. Update Database URLs in SQL Dump

Replace localhost URLs with staging domain:

```bash
sed -i '' 's/miasmah\.test/staging.miasmah.com/g' miasmah_backup.sql
sed -i '' 's|http://|https://|g' miasmah_backup.sql  # If using HTTPS
```

### 3. Transfer Database Dump to Remote Server

Transfer the SQL file to the remote server:

```bash
scp miasmah_backup.sql user@staging.miasmah.com:/tmp/miasmah_backup.sql
```

Or using rsync:

```bash
rsync -avz miasmah_backup.sql user@staging.miasmah.com:/tmp/miasmah_backup.sql
```

### 4. Transfer Files to Remote Server

**Option A: Complete overwrite (delete remote files not in local)**
Use rsync with `--delete` to mirror exactly:

```bash
rsync -avz --delete --exclude='wp-config.php' --exclude='.git' \
  /Users/maxwell.croy/Herd/miasmah/ \
  user@staging.miasmah.com:/path/to/webroot/
```

**Option B: Clean slate (remove directory first)**
SSH into remote server and remove existing installation, then sync:

```bash
# On remote server
ssh user@staging.miasmah.com
rm -rf /path/to/webroot/*

# Then sync from local
rsync -avz --exclude='wp-config.php' --exclude='.git' \
  /Users/maxwell.croy/Herd/miasmah/ \
  user@staging.miasmah.com:/path/to/webroot/
```

**Note:** rsync overwrites existing files by default. The `--delete` flag ensures remote files not in local source are removed.

### 5. Import Database on Remote Server

SSH into remote server and import:

```bash
ssh user@staging.miasmah.com
mysql -u remote_user -p remote_db_name < /tmp/miasmah_backup.sql

# Optionally clean up the SQL file after import
rm /tmp/miasmah_backup.sql
```

### 6. Update wp-config.php on Remote Server

Update `wp-config.php` on remote server with:

- Remote database credentials (DB_NAME, DB_USER, DB_PASSWORD, DB_HOST)
- **Important:** Ensure the table prefix matches your imported tables. If you imported tables with `wp_` prefix, make sure `$table_prefix = 'wp_';` in wp-config.php

Check existing table prefix:
```bash
grep "table_prefix" /path/to/webroot/wp-config.php
```

If tables were imported with `wp_` prefix but config uses `staging_`, update it:
```bash
sed -i "s/\$table_prefix = 'staging_';/\$table_prefix = 'wp_';/g" /path/to/webroot/wp-config.php
```

### 7. Verify Database Tables

Check that tables were imported correctly:

```bash
mysql -u remote_user -p remote_db_name -e "SHOW TABLES;"
```

You should see tables with the correct prefix (e.g., `wp_posts`, `wp_users`, etc.).

### 8. Clean Up Old Tables (Optional)

If you have old tables from the previous installation that you want to remove:

```bash
mysql -u remote_user -p remote_db_name -e "DROP TABLE old_table1, old_table2, ...;"
```

### 9. Update File Permissions

Set appropriate permissions on remote server:

```bash
find /path/to/webroot -type d -exec chmod 755 {} \;
find /path/to/webroot -type f -exec chmod 644 {} \;
chmod 600 wp-config.php
```

### 10. Flush Permalinks

After migration, you'll likely get 404 errors on pages. Fix this by flushing permalinks:

**Option A: Via WordPress Admin**
- Log into WordPress admin: `https://staging.miasmah.com/wp-admin`
- Go to Settings → Permalinks
- Click "Save Changes" (no changes needed)

**Option B: Via WP-CLI**
```bash
ssh user@staging.miasmah.com
cd /path/to/webroot
wp rewrite flush
```

### 11. Verify .htaccess File

Ensure `.htaccess` exists and contains WordPress rewrite rules:

```bash
cat /path/to/webroot/.htaccess
```

It should contain:
```
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
```

If missing, WordPress will recreate it when you save permalinks in admin.

### 12. Verify Apache mod_rewrite is Enabled

Check if mod_rewrite is enabled:

```bash
# On remote server
apache2ctl -M | grep rewrite
# or
httpd -M | grep rewrite
```

If not enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 13. Verify DNS Configuration

Ensure staging.miasmah.com DNS points to remote server IP, or update local hosts file for testing.

### 14. Test Installation

- Visit staging.miasmah.com
- Test admin login
- Verify permalinks work (pages should load after flushing permalinks)
- Check media files are loading correctly

## Key Files to Modify

- `wp-config.php` - Update database credentials and table prefix for remote server
- SQL dump - Replace `miasmah.test` with `staging.miasmah.com`

## Common Issues and Troubleshooting

### Issue: Database tables imported but data not showing

**Symptom:** Import command completed but no data visible in WordPress.

**Solution:** 
- Check table prefix matches between wp-config.php and imported tables
- Verify tables exist: `mysql -u user -p dbname -e "SHOW TABLES;"`
- Ensure wp-config.php uses correct prefix (e.g., `$table_prefix = 'wp_';`)

### Issue: 404 errors on all pages except homepage

**Symptom:** Homepage loads but all other pages return 404.

**Solution:**
- Flush permalinks (see Step 10)
- Verify .htaccess file exists and has correct rewrite rules
- Ensure Apache mod_rewrite is enabled
- Check file permissions on .htaccess

### Issue: Media files not loading

**Symptom:** Images and other media return 404 or broken links.

**Solution:**
- Verify wp-content/uploads/ directory was transferred correctly
- Check file permissions on uploads directory
- May need to run search-replace on database for media URLs if they still reference old domain

## Rollback Plan

- Keep local installation intact until migration verified
- Keep database dump as backup
- If migration fails, restore from backup or re-run import

## Future Production Migration

When ready to move from staging to production (miasmah.com):

1. Follow same steps but replace `staging.miasmah.com` with `miasmah.com`
2. Update URLs in database dump accordingly
3. Ensure DNS is properly configured for production domain
