# Security and Webhook Fixes Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Remove sensitive configuration files from Git, configure `.gitignore` to prevent future commits of `.env.hostinger`, and bypass CSRF protection for the Midtrans webhook `/payment/webhook` to fix order notifications.

**Architecture:** 
1. Run Git commands to stop tracking `.env.hostinger` cached files without deleting them locally.
2. Edit `.gitignore` to append `.env.hostinger`.
3. Modify `bootstrap/app.php` to add `payment/webhook` to the CSRF exclusion list.
4. Document exposed secrets and provide credential rotation steps for the user.

**Tech Stack:** Git, PHP, Laravel 12

---

### Task 1: Git and Gitignore Updates

**Files:**
- Modify: `.gitignore:27-28`

**Step 1: Write the failing test**
Run command to verify if `.env.hostinger` is currently tracked.
Run: `git ls-files .env.hostinger`
Expected: Output is `.env.hostinger` (indicating it is tracked).

**Step 2: Run test to verify it fails**
Run: `git ls-files .env.hostinger`
Expected: `.env.hostinger` is outputted.

**Step 3: Write minimal implementation**
Run untracking commands:
`git rm --cached .env.hostinger`

And modify `.gitignore` to include `.env.hostinger` at the end:
```gitignore
deploy_hostinger.py
upload_fixes.py
.env.hostinger
```

**Step 4: Run test to verify it passes**
Run: `git ls-files .env.hostinger`
Expected: Empty output (meaning it is no longer tracked).
Run: `git status`
Expected: Shows `.env.hostinger` is deleted in git (staged), and `.gitignore` is modified.

**Step 5: Commit**
```bash
git add .gitignore
git commit -m "security: stop tracking env.hostinger and add it to gitignore"
```

---

### Task 2: Exclude Midtrans Webhook from CSRF Protection

**Files:**
- Modify: `bootstrap/app.php:19-38`

**Step 1: Write the failing test**
Verify `/payment/webhook` POST request fails with 419 when called locally (or check routing / middleware configuration).
Wait, we will verify the code change is correct and compiles.

**Step 2: Run test to verify it fails**
Before code changes, running a mock post to `/payment/webhook` returns 419 because csrf validation is not bypassed.

**Step 3: Write minimal implementation**
Update `bootstrap/app.php` around line 21 to exclude the webhook:
```php
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust Vercel proxies
        $middleware->trustProxies(at: '*');

        // Exclude payment webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'payment/webhook',
        ]);

        // Register admin middleware alias
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.ip.blocked' => \App\Http\Middleware\CheckIpBlocked::class,
            'log.security' => \App\Http\Middleware\LogSecurityEvents::class,
        ]);
        
        // Register tracking middleware for web routes
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
            \App\Http\Middleware\UpdateOnlineStatus::class,
            \App\Http\Middleware\EnsureStorageDirectories::class,
            \App\Http\Middleware\CheckIpBlocked::class,
            \App\Http\Middleware\LogSecurityEvents::class,
        ]);
    })
```

**Step 4: Run test to verify it passes**
Run artisan commands to verify code syntax and config compilation:
`php artisan config:clear`
`php artisan route:clear`
Expected: Command completes with success.

**Step 5: Commit**
```bash
git add bootstrap/app.php
git commit -m "fix: exclude payment/webhook route from CSRF verification"
```
