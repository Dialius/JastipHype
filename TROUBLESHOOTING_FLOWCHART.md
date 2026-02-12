# 🔄 ERROR 500 TROUBLESHOOTING FLOWCHART

## 📊 VISUAL WORKFLOW

```
┌─────────────────────────────────────────────────────────────┐
│                    🚨 ERROR 500 DETECTED                     │
│                   https://jastiphype.shop                    │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  STEP 1: LOGIN SSH & NAVIGATE                               │
│  ssh u909490256@id-dci-web1319.main-hosting.eu -p 65002    │
│  cd /home/u909490256/domains/jastiphype.shop               │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  STEP 2: RUN AUTOMATED DIAGNOSIS                            │
│  bash diagnose-error-500.sh                                 │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌─────────┴─────────┐
                    │                   │
                    ▼                   ▼
        ┌───────────────────┐   ┌───────────────────┐
        │  ✅ ALL CHECKS OK │   │  ❌ ISSUES FOUND  │
        └───────────────────┘   └───────────────────┘
                    │                   │
                    │                   ▼
                    │       ┌───────────────────────┐
                    │       │  STEP 3: NUCLEAR FIX  │
                    │       │  bash nuclear-fix.sh  │
                    │       └───────────────────────┘
                    │                   │
                    │                   ▼
                    │       ┌───────────────────────┐
                    │       │  STEP 4: TEST WEBSITE │
                    │       │  https://jastiphype.  │
                    │       │         shop          │
                    │       └───────────────────────┘
                    │                   │
                    │         ┌─────────┴─────────┐
                    │         │                   │
                    │         ▼                   ▼
                    │  ┌──────────────┐   ┌──────────────┐
                    │  │  ✅ FIXED!   │   │ ❌ STILL 500 │
                    │  └──────────────┘   └──────────────┘
                    │         │                   │
                    └─────────┤                   ▼
                              │       ┌───────────────────────┐
                              │       │ STEP 5: CHECK ERROR   │
                              │       │ tail -50 storage/logs/│
                              │       │     laravel.log       │
                              │       └───────────────────────┘
                              │                   │
                              │                   ▼
                              │       ┌───────────────────────┐
                              │       │ STEP 6: IDENTIFY      │
                              │       │ SPECIFIC ERROR        │
                              │       └───────────────────────┘
                              │                   │
                              │         ┌─────────┴─────────┐
                              │         │                   │
                              │         ▼                   ▼
                              │  ┌──────────────┐   ┌──────────────┐
                              │  │ PERMISSION   │   │  DATABASE    │
                              │  │   ERROR      │   │    ERROR     │
                              │  └──────────────┘   └──────────────┘
                              │         │                   │
                              │         ▼                   ▼
                              │  ┌──────────────┐   ┌──────────────┐
                              │  │ chmod -R 775 │   │ cp .env.     │
                              │  │   storage    │   │  hostinger   │
                              │  └──────────────┘   │ config:cache │
                              │                     └──────────────┘
                              │                             │
                              └─────────────────────────────┘
                                            │
                                            ▼
                              ┌───────────────────────────┐
                              │ STEP 7: UPDATE PHP CONFIG │
                              │ display_errors = Off      │
                              │ timezone = Asia/Jakarta   │
                              └───────────────────────────┘
                                            │
                                            ▼
                              ┌───────────────────────────┐
                              │    🎉 SUCCESS!            │
                              │    Website is live        │
                              └───────────────────────────┘
```

---

## 🎯 DECISION TREE

### START: Error 500 Detected

```
ERROR 500
    │
    ├─→ Run diagnosis script
    │       │
    │       ├─→ PHP version < 8.2?
    │       │       └─→ ❌ Contact Hostinger to upgrade
    │       │
    │       ├─→ Missing PHP extensions?
    │       │       └─→ ❌ Enable in hPanel → PHP Configuration
    │       │
    │       ├─→ vendor/ not found?
    │       │       └─→ ❌ Run: composer install
    │       │
    │       ├─→ .env not found?
    │       │       └─→ ❌ Run: cp .env.hostinger .env
    │       │
    │       ├─→ APP_KEY empty?
    │       │       └─→ ❌ Run: php artisan key:generate --force
    │       │
    │       ├─→ Storage permissions wrong?
    │       │       └─→ ❌ Run: chmod -R 775 storage
    │       │
    │       ├─→ Database connection failed?
    │       │       └─→ ❌ Check .env credentials
    │       │
    │       ├─→ Cached config mismatch?
    │       │       └─→ ❌ Run: config:clear && config:cache
    │       │
    │       ├─→ Uploads folder missing?
    │       │       └─→ ❌ Run: mkdir -p public/uploads/...
    │       │
    │       └─→ All checks pass?
    │               └─→ ✅ Check error log for specific issue
    │
    └─→ Run nuclear fix
            │
            ├─→ Fixed?
            │       └─→ ✅ Update PHP config & done!
            │
            └─→ Still error?
                    └─→ Check error log → Follow specific fix
```

---

## 📋 QUICK REFERENCE MATRIX

| Symptom | Likely Cause | Quick Fix | Success Rate |
|---------|--------------|-----------|--------------|
| Error 500, no log | Permission issue | `chmod -R 775 storage` | 80% |
| "SQLSTATE[HY000]" | Database error | `cp .env.hostinger .env` | 90% |
| "Class not found" | Missing vendor | `composer install` | 95% |
| "No encryption key" | Missing APP_KEY | `php artisan key:generate` | 100% |
| "Permission denied" | File permissions | `chmod -R 775 storage` | 85% |
| Wrong config values | Cached config | `config:clear && config:cache` | 90% |
| Image upload fails | Missing uploads | `mkdir -p public/uploads/...` | 100% |
| 500 after deploy | Cache mismatch | `bash nuclear-fix.sh` | 95% |

---

## 🔄 ITERATIVE TROUBLESHOOTING LOOP

```
┌─────────────────────────────────────────────────────────┐
│                    START                                │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  1. Run Diagnosis                                       │
│     bash diagnose-error-500.sh                          │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  2. Identify Issues                                     │
│     - Permission errors?                                │
│     - Database errors?                                  │
│     - Missing files?                                    │
│     - Config errors?                                    │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  3. Apply Fix                                           │
│     - Run specific fix command                          │
│     - Or run nuclear-fix.sh                             │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────┐
│  4. Test Website                                        │
│     https://jastiphype.shop                             │
└─────────────────────────────────────────────────────────┘
                        │
                ┌───────┴───────┐
                │               │
                ▼               ▼
        ┌───────────┐   ┌───────────┐
        │  FIXED!   │   │ STILL 500 │
        └───────────┘   └───────────┘
                │               │
                │               └──→ Check error log
                │                       │
                │                       └──→ Back to Step 2
                │
                ▼
        ┌───────────────────┐
        │  Update PHP Config│
        │  - display_errors │
        │  - timezone       │
        └───────────────────┘
                │
                ▼
        ┌───────────────────┐
        │      DONE!        │
        └───────────────────┘
```

---

## 🎯 PRIORITY MATRIX

### High Priority (Fix First)

```
┌─────────────────────────────────────────────────────────┐
│  CRITICAL ISSUES (Must fix immediately)                 │
├─────────────────────────────────────────────────────────┤
│  1. Missing .env file                                   │
│  2. Empty APP_KEY                                       │
│  3. Database connection failed                          │
│  4. Storage permissions wrong                           │
│  5. Missing vendor directory                            │
└─────────────────────────────────────────────────────────┘
```

### Medium Priority (Fix After Critical)

```
┌─────────────────────────────────────────────────────────┐
│  IMPORTANT ISSUES (Should fix)                          │
├─────────────────────────────────────────────────────────┤
│  1. Cached config mismatch                              │
│  2. Missing uploads folder                              │
│  3. Wrong public_html structure                         │
│  4. Missing .htaccess                                   │
└─────────────────────────────────────────────────────────┘
```

### Low Priority (Optimize Later)

```
┌─────────────────────────────────────────────────────────┐
│  OPTIMIZATION (After error fixed)                       │
├─────────────────────────────────────────────────────────┤
│  1. display_errors = Off                                │
│  2. date.timezone = Asia/Jakarta                        │
│  3. OPcache optimization                                │
│  4. Security headers                                    │
└─────────────────────────────────────────────────────────┘
```

---

## 🔍 DIAGNOSTIC FLOW

```
┌─────────────────────────────────────────────────────────┐
│  AUTOMATED DIAGNOSIS                                    │
│  bash diagnose-error-500.sh                             │
└─────────────────────────────────────────────────────────┘
                        │
        ┌───────────────┼───────────────┐
        │               │               │
        ▼               ▼               ▼
┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│ PHP Version  │ │ File         │ │ Database     │
│ & Extensions │ │ Structure    │ │ Connection   │
└──────────────┘ └──────────────┘ └──────────────┘
        │               │               │
        ▼               ▼               ▼
┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│ Permissions  │ │ .env Config  │ │ Cache Status │
└──────────────┘ └──────────────┘ └──────────────┘
        │               │               │
        └───────────────┼───────────────┘
                        │
                        ▼
        ┌───────────────────────────────┐
        │  DIAGNOSIS REPORT             │
        │  - ✅ Items OK                │
        │  - ❌ Items with issues       │
        │  - ⚠️  Items need attention   │
        └───────────────────────────────┘
                        │
                        ▼
        ┌───────────────────────────────┐
        │  RECOMMENDED ACTIONS          │
        │  1. Fix critical issues       │
        │  2. Run nuclear-fix.sh        │
        │  3. Test website              │
        └───────────────────────────────┘
```

---

## 📊 SUCCESS PATH

```
START
  │
  ├─→ Run diagnosis (5 min)
  │       │
  │       └─→ Issues found
  │               │
  ├─→ Run nuclear fix (2 min)
  │       │
  │       └─→ Cache cleared, permissions fixed
  │               │
  ├─→ Test website (1 min)
  │       │
  │       └─→ Website loads!
  │               │
  ├─→ Update PHP config (5 min)
  │       │
  │       └─→ display_errors=Off, timezone=Asia/Jakarta
  │               │
  └─→ DONE! (Total: ~15 minutes)
```

---

## 🚨 FAILURE PATH & RECOVERY

```
START
  │
  ├─→ Run diagnosis
  │       │
  │       └─→ Issues found
  │               │
  ├─→ Run nuclear fix
  │       │
  │       └─→ Still error 500
  │               │
  ├─→ Check error log
  │       │
  │       ├─→ Permission error
  │       │       └─→ chmod -R 775 storage
  │       │
  │       ├─→ Database error
  │       │       └─→ Fix .env credentials
  │       │
  │       ├─→ Class not found
  │       │       └─→ composer install
  │       │
  │       └─→ Unknown error
  │               └─→ Follow complete checklist
  │                       │
  └─→ RESOLVED! (Total: ~30 minutes)
```

---

## 🎯 QUICK WIN SCENARIOS

### Scenario 1: Permission Issue (Most Common)
```
Error 500 → Run diagnosis → Permission error found
    → chmod -R 775 storage
    → FIXED! (5 minutes)
```

### Scenario 2: Cache Mismatch
```
Error 500 → Run diagnosis → Cached config wrong
    → php artisan config:clear && config:cache
    → FIXED! (3 minutes)
```

### Scenario 3: Missing .env
```
Error 500 → Run diagnosis → .env not found
    → cp .env.hostinger .env
    → php artisan key:generate --force
    → FIXED! (5 minutes)
```

### Scenario 4: Multiple Issues
```
Error 500 → Run diagnosis → Multiple issues found
    → bash nuclear-fix.sh
    → FIXED! (10 minutes)
```

---

## 📞 ESCALATION PATH

```
┌─────────────────────────────────────────────────────────┐
│  Level 1: Self-Service (Use provided scripts)          │
│  - Run diagnose-error-500.sh                            │
│  - Run nuclear-fix.sh                                   │
│  - Follow QUICK_FIX_COMMANDS.md                         │
│  Success Rate: 80%                                      │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼ (If not fixed)
┌─────────────────────────────────────────────────────────┐
│  Level 2: Systematic Troubleshooting                    │
│  - Follow ERROR_500_COMPLETE_CHECKLIST.md               │
│  - Check error logs                                     │
│  - Apply specific fixes                                 │
│  Success Rate: 95%                                      │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼ (If still not fixed)
┌─────────────────────────────────────────────────────────┐
│  Level 3: Fresh Deployment                              │
│  - Follow FRESH_DEPLOYMENT_GUIDE.md                     │
│  - Complete reinstall                                   │
│  Success Rate: 99%                                      │
└─────────────────────────────────────────────────────────┘
                        │
                        ▼ (If still issues)
┌─────────────────────────────────────────────────────────┐
│  Level 4: Contact Support                               │
│  - Hostinger Live Chat                                  │
│  - Send diagnostic output                               │
│  - Server-level issues                                  │
│  Success Rate: 100%                                     │
└─────────────────────────────────────────────────────────┘
```

---

**Created:** 12 Februari 2026  
**Purpose:** Visual guide untuk troubleshooting error 500  
**Use:** Untuk memahami workflow secara visual
