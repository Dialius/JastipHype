# Checkout Payment Logos Integration Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Integrate local payment method logos into JastipHype's checkout payment selection UI and update the collapsed header previews.

**Architecture:** We will modify the Laravel Blade checkout view template (`resources/views/checkout/index.blade.php`) to dynamically reference the locally copied image files in `/images/payment/` and structure the radio item templates to hold these logos cleanly.

**Tech Stack:** Laravel, Tailwind CSS, Alpine.js, HTML5, PHP.

---

### Task 1: Update Checkout Template
**Files:**
- Modify: `resources/views/checkout/index.blade.php`

**Step 1: Write the failing test**
N/A (Frontend/Blade changes do not have a dedicated backend PHPUnit test for image paths, but we will test visually).

**Step 2: Run test to verify it fails**
N/A

**Step 3: Write minimal implementation**
We will edit `resources/views/checkout/index.blade.php` to:
1. Replace Wikipedia URLs in `$bank['logo']` with `asset('images/payment/banks/<bank>-va.webp')` or `asset('images/payment/banks/bca.svg')`.
2. Update the Virtual Account header collapsed preview images to use `asset('images/payment/banks/mandiri-va.webp')` and `asset('images/payment/banks/bri-va.webp')`.
3. Add a logo key to E-wallet options pointing to their local paths:
   - QRIS: `asset('images/payment/ewallet/qrisfinal.webp')`
   - GoPay: `asset('images/payment/ewallet/qris-gopay.webp')`
   - ShopeePay: `asset('images/payment/ewallet/qris-shopee.webp')`
   - DANA (New): `asset('images/payment/ewallet/qris-dana.webp')`
4. Update E-wallet item template to display these logos inside a `bg-white p-1 rounded border` box, similar to Virtual Accounts.
5. Update E-wallet header collapsed preview to use `asset('images/payment/ewallet/qrisfinal.webp')`.
6. Add logo key to Convenience Store options:
   - Indomaret: `asset('images/payment/cstore/indomaret.png')`
   - Alfamart: `asset('images/payment/cstore/alfamart.png')`
7. Update Convenience Store item template to display these logos inside a `bg-white p-1 rounded border` box.
8. Update Convenience Store header collapsed preview to use `asset('images/payment/cstore/alfamart.png')` and `asset('images/payment/cstore/indomaret.png')`.

**Step 4: Run test to verify it passes**
View checkout index file syntax.

**Step 5: Commit**
```bash
git add resources/views/checkout/index.blade.php
git commit -m "feat: integrate local payment logos and collapsed header previews"
```

---

### Task 2: Sync Files to Hostinger Production
**Files:**
- Modify: `upload_fixes.py`

**Step 1: Update sync script to include new images and view**
Make sure `upload_fixes.py` uploads the contents of the `public/images/payment/` folder recursively to Hostinger `public_html/images/payment/` using SFTP. Also ensure it uploads `resources/views/checkout/index.blade.php`.

**Step 2: Run sync script**
Run: `python upload_fixes.py`
Expected: SFTP uploads complete successfully.

**Step 3: Commit**
```bash
git add upload_fixes.py
git commit -m "chore: update hostinger deployment script for new payment assets"
```

---

### Task 3: Verify Live Production Site
**Files:**
- Test: browser verification

**Step 1: Check live site checkout payment options**
Load `https://jastiphype.shop/checkout` or a product page checkout.
Expected: The payment selection shows all logos correctly, collapsed headers display the correct preview logos, and selecting DANA registers a valid payment option.
