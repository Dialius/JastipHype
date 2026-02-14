# Deployment Rollback Guide

## 🔄 Quick Rollback

If a deployment causes issues, you can quickly rollback to a previous version.

### Method 1: Rollback via Git (Recommended)

```bash
# 1. Find the last working commit
git log --oneline -10

# 2. Revert to that commit
git reset --hard <commit-hash>

# 3. Force push to trigger deployment
git push origin master --force
```

### Method 2: Rollback via SSH (Emergency)

```bash
# Connect to server
ssh -p 65002 u909490256@153.92.9.187

# Navigate to project
cd domains/jastiphype.shop

# Check recent commits
git log --oneline -10

# Rollback to previous commit
git reset --hard <commit-hash>

# Clear caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Copy to public_html
cp -rf public/* public_html/
```

## 📋 Rollback Checklist

Before rolling back:
- [ ] Identify the issue (error logs, user reports)
- [ ] Determine the last working commit
- [ ] Backup current state (if needed)
- [ ] Notify team about rollback
- [ ] Execute rollback
- [ ] Verify site is working
- [ ] Check error logs
- [ ] Notify team rollback is complete

## 🔍 Finding the Right Commit

### View Recent Commits
```bash
git log --oneline -20
```

### View Commits with Dates
```bash
git log --pretty=format:"%h - %an, %ar : %s" -10
```

### View Commits for Specific File
```bash
git log --oneline -- path/to/file
```

## 🚨 Emergency Rollback Scenarios

### Scenario 1: Site is Down (500 Error)
```bash
# Quick rollback to previous commit
ssh -p 65002 u909490256@153.92.9.187
cd domains/jastiphype.shop
git reset --hard HEAD~1
php artisan optimize:clear
cp -rf public/* public_html/
```

### Scenario 2: Database Migration Failed
```bash
# Rollback migration
ssh -p 65002 u909490256@153.92.9.187
cd domains/jastiphype.shop
php artisan migrate:rollback

# If that doesn't work, restore from backup
# (Make sure you have database backups!)
```

### Scenario 3: Assets Not Loading
```bash
# Rebuild assets
npm run build
git add public/build -f
git commit -m "fix: rebuild assets"
git push origin master
```

## 📊 Rollback History

Keep track of rollbacks for future reference:

| Date | Commit | Reason | Rolled Back To | Duration |
|------|--------|--------|----------------|----------|
| - | - | - | - | - |

## 🛡️ Prevention Tips

1. **Test Locally First**: Always test changes on local environment
2. **Use Staging**: Deploy to staging before production
3. **Small Changes**: Deploy small, incremental changes
4. **Monitor**: Watch logs after deployment
5. **Backup**: Keep regular database backups
6. **Health Checks**: Verify site after each deployment

## 📞 Emergency Contacts

If rollback doesn't work:
1. Check GitHub Actions logs
2. Check server error logs: `tail -f storage/logs/laravel.log`
3. Check web server logs (if accessible)
4. Contact hosting support if server issue

## 🔗 Related Documents

- [AUTO_DEPLOY_SUCCESS.md](AUTO_DEPLOY_SUCCESS.md) - Deployment guide
- [AUTO_DEPLOY_ANALYSIS.md](AUTO_DEPLOY_ANALYSIS.md) - System analysis
- [FINAL_SUMMARY.md](FINAL_SUMMARY.md) - Project summary

---

**Last Updated**: February 14, 2026
**Status**: Active
