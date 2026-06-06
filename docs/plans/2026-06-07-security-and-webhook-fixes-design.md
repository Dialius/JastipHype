# Design Document: Security and Webhook Fixes

## Overview
This design document addresses two critical issues in the JastipHype project:
1. **Security Exposure:** Sensitive environment files (`.env.hostinger`) containing production credentials (SMTP, Database, Google OAuth, Midtrans Server Key) are currently tracked in Git and exposed on GitHub. Historical commits also contain exposed keys.
2. **Midtrans Webhook Failure:** The `/payment/webhook` endpoint returns a non-200 status (likely 419) when called by Midtrans because it lacks CSRF verification bypass in Laravel 11/12.

---

## 🛠 Proposed Changes

### 1. Git & Credentials Safety
* **Action:** Untrack `.env.hostinger` from Git tracking while preserving the local file.
* **Command:** `git rm --cached .env.hostinger`
* **Gitignore Update:** Add `.env.hostinger` to `.gitignore` so it is never committed again.

### 2. Laravel Middleware Configuration
* **Action:** Exclude the `payment/webhook` route from CSRF verification in `bootstrap/app.php`.
* **Implementation:**
  ```php
  $middleware->validateCsrfTokens(except: [
      'payment/webhook',
  ]);
  ```

### 3. Security Remediation Report
* **Action:** Document the exact credentials exposed and provide step-by-step instructions for the user to rotate the compromised credentials and clean up remote history if necessary.

---

## 🧪 Verification & Testing Plan
1. **Git Tracking Check:** Run `git ls-files .env.hostinger` to confirm the file is no longer tracked.
2. **Compilation/Artisan Check:** Run `php artisan config:clear` and `php artisan route:clear` to verify that there are no syntax errors in `bootstrap/app.php`.
3. **CSRF Bypass Verification:** Ensure the route compiles correctly and does not require CSRF token validation when accessed via POST.
