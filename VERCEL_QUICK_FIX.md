# Vercel Quick Fix Guide

## 🎯 Masalah Utama yang Diperbaiki

1. ✅ **File Upload** - Tidak bisa create directory
2. ✅ **Settings Update** - Tidak bisa write .env file  
3. ✅ **Email Templates** - Tidak bisa edit templates
4. ✅ **Session** - Session tidak persist
5. ✅ **Cache** - File cache tidak work
6. ✅ **Queue** - Background jobs tidak jalan

## ⚡ Quick Setup

### 1. Update Environment Variables di Vercel Dashboard

```env
# Required
APP_ENV=production
APP_DEBUG=false
APP_KEY=your-app-key
APP_URL=https://your-domain.vercel.app

# Database
DB_CONNECTION=mysql
DB_HOST=your-host
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Session & Cache (IMPORTANT!)
SESSION_DRIVER=cookie
CACHE_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync

# Storage (IMPORTANT!)
FILESYSTEM_DISK=public
# Or use S3 (recommended):
# FILESYSTEM_DISK=s3
# AWS_ACCESS_KEY_ID=your-key
# AWS_SECRET_ACCESS_KEY=your-secret
# AWS_BUCKET=your-bucket
```

### 2. Deploy
```bash
git add .
git commit -m "Fix: Vercel serverless compatibility"
git push
```

### 3. Test
- ✅ Upload banner/product images
- ✅ Update settings (akan ada warning)
- ✅ Send email
- ✅ Create order

## ⚠️ Important Notes

### File Uploads
- Files uploaded ke `/tmp` akan **hilang** setelah function execution
- **Recommendation**: Use S3 untuk production
- Local development tetap work normal

### Settings Updates
- Settings disimpan di **database**, bukan .env
- Untuk permanent changes, update di **Vercel Dashboard**
- Akan ada warning message saat update settings

### Email Templates
- Template edits disimpan di **cache**, bukan file
- Changes akan **hilang** on deployment
- **Recommendation**: Edit templates di code, bukan admin panel

## 🚀 Production Setup (Recommended)

### Setup S3 Storage
```bash
# 1. Install package
composer require league/flysystem-aws-s3-v3 "^3.0"

# 2. Add to Vercel environment variables
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
AWS_URL=https://your-bucket.s3.amazonaws.com
```

### Setup Redis Cache (Optional)
```bash
# Add to Vercel environment variables
CACHE_DRIVER=redis
REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-password
REDIS_PORT=6379
```

## 🔍 Troubleshooting

### Upload Still Fails
```bash
# Check logs
vercel logs

# Verify environment variables
# FILESYSTEM_DISK should be set
# If using S3, verify AWS credentials
```

### Settings Not Saving
```bash
# Check database connection
# Verify CACHE_DRIVER=database in Vercel
# Check error logs
```

### Session Issues
```bash
# Verify SESSION_DRIVER=cookie in Vercel
# Clear browser cookies
# Check database if using database sessions
```

## 📚 Full Documentation

- `VERCEL_ISSUES_COMPLETE_FIX.md` - Complete technical documentation
- `STORAGE_FIX_GUIDE.md` - File upload fix details
- `FILE_UPLOAD_COMPLETE_FIX.md` - Upload implementation details

## ✅ Checklist

- [ ] Environment variables updated in Vercel
- [ ] Deployed to Vercel
- [ ] Tested file upload
- [ ] Tested settings update
- [ ] Tested email sending
- [ ] (Optional) S3 configured
- [ ] (Optional) Redis configured

## 🎉 Done!

Your application is now compatible with Vercel serverless environment!
