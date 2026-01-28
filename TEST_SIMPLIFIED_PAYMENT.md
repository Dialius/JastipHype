# Test Guide - Simplified Payment Flow

## Quick Test Steps

### 1. Prepare Test Environment
```bash
# Make sure server is running
php artisan serve

# Clear cache
php artisan cache:clear
php artisan view:clear
```

### 2. Test Checkout Flow

#### Step 1: Add Product to Cart
1. Go to homepage: `http://localhost:8000`
2. Browse products
3. Click on any product
4. Select size and quantity
5. Click "Add to Cart"
6. Verify cart icon shows item count

#### Step 2: Go to Checkout
1. Click cart icon or go to: `http://localhost:8000/cart`
2. Verify items are in cart
3. Click "Proceed to Checkout"
4. You should be redirected to: `http://localhost:8000/checkout`

#### Step 3: Fill Checkout Form
1. **Contact Information**
   - Email: test@example.com
   - Name: Test User
   - Phone: 08123456789

2. **Shipping Address**
   - Address: Jl. Test No. 123
   - Click "Select Province, City & Postal Code"
   - Select Province (e.g., DKI Jakarta)
   - Select City (e.g., Jakarta Selatan)
   - Select Postal Code (e.g., 12160)

3. **Shipping Method**
   - Wait for shipping options to load
   - Select any shipping method (e.g., JNE REG)

4. **Payment Method**
   - ✅ **SHOULD NOT BE VISIBLE** - This section should be hidden!
   - If you still see payment method selection, something is wrong

#### Step 4: Place Order
1. Click "PLACE ORDER" button
2. You should be redirected to: `http://localhost:8000/payment/ORD-XXXXXXX`

#### Step 5: Verify Payment Page
1. **Check Page Elements:**
   - ✅ Header shows "Complete Your Payment"
   - ✅ Order number is displayed
   - ✅ Total amount is shown
   - ✅ Status badge shows "Pending"
   - ✅ **Midtrans Snap UI is embedded** (payment method selection)
   - ✅ Order details are listed below

2. **Check Snap Container:**
   - Should see Midtrans payment options:
     - E-Wallets (GoPay, ShopeePay, QRIS)
     - Virtual Account (BCA, BNI, BRI, Mandiri, Permata)
     - Convenience Store (Indomaret, Alfamart)
     - Credit Card (if enabled)

#### Step 6: Test Payment Selection
1. Click on any payment method in Snap UI
2. Follow Midtrans instructions
3. For testing, you can use Midtrans sandbox credentials

### 3. Verify Database

```sql
-- Check order was created
SELECT * FROM orders ORDER BY created_at DESC LIMIT 1;

-- Check payment record
SELECT * FROM payments ORDER BY created_at DESC LIMIT 1;

-- Verify payment_method is 'snap'
SELECT order_number, payment_method, payment_detail, status 
FROM orders 
ORDER BY created_at DESC LIMIT 1;
```

Expected results:
- `payment_method` = 'snap'
- `payment_detail` = NULL (will be updated after payment)
- `status` = 'pending'

### 4. Check Logs

```bash
# View Laravel logs
tail -f storage/logs/laravel.log
```

Look for:
- ✅ "Checkout process started"
- ✅ "Validation passed"
- ✅ "Order created successfully with Snap"
- ❌ No "Mapping payment to Snap" (this should NOT appear)

### 5. Browser Console Check

Open browser DevTools (F12) and check:

1. **Network Tab:**
   - POST to `/checkout/process` should return 302 redirect
   - GET to `/payment/ORD-XXX` should return 200

2. **Console Tab:**
   - Should see Snap script loaded
   - No JavaScript errors
   - Should see: "Form validation passed. Submitting with data:"

### 6. Test Different Scenarios

#### Scenario A: Guest Checkout
1. Logout if logged in
2. Follow steps 1-5 above
3. Should work without login

#### Scenario B: Logged In User
1. Login first
2. Follow steps 1-5 above
3. Should work with user data pre-filled

#### Scenario C: Empty Cart
1. Clear cart
2. Try to access `/checkout`
3. Should redirect to cart with error message

#### Scenario D: Missing Address
1. Fill form but don't select province/city
2. Click "Place Order"
3. Should show error: "Please select Province, City, and Postal Code"

## Expected Behavior Summary

### ✅ What Should Happen
- Checkout page shows NO payment method selection
- After "Place Order", redirect to payment page
- Payment page shows Midtrans Snap UI embedded
- All payment methods available in Snap UI
- User selects payment in Midtrans, not on website

### ❌ What Should NOT Happen
- Payment method selection on checkout page
- Separate pages for different payment methods
- Manual payment instructions (VA number, QRIS code, etc.)
- Error about missing payment_method

## Troubleshooting

### Issue: Payment method section still visible
**Solution:** Clear view cache
```bash
php artisan view:clear
```

### Issue: Snap UI not loading
**Check:**
1. Midtrans credentials in `.env`
2. Browser console for errors
3. Network tab for failed requests
4. `snap_token` exists in payment record

### Issue: Validation error about payment_method
**Check:**
1. Hidden inputs exist in checkout form
2. Form validation in JavaScript
3. Controller validation rules

### Issue: Redirect to wrong page
**Check:**
1. Route configuration
2. Controller redirect logic
3. Payment record creation

## Success Criteria

✅ Checkout page loads without payment selection
✅ Form validation works (address required)
✅ Order created successfully
✅ Redirected to payment page
✅ Snap UI embedded and visible
✅ All payment methods shown in Snap
✅ No errors in console or logs
✅ Database records correct

## Midtrans Sandbox Test Cards

For testing in sandbox mode:

### Credit Card
- Card Number: `4811 1111 1111 1114`
- CVV: `123`
- Exp: Any future date
- 3DS: `112233`

### E-Wallet
- GoPay: Use Midtrans simulator
- ShopeePay: Use Midtrans simulator

### Virtual Account
- Will generate test VA numbers
- Use Midtrans simulator to mark as paid

## Next Steps After Testing

If all tests pass:
1. ✅ Payment flow is simplified
2. ✅ Ready for production (after changing to production credentials)
3. ✅ Monitor webhook for payment updates
4. ✅ Test webhook handler separately

If tests fail:
1. Check error messages
2. Review logs
3. Verify code changes
4. Check Midtrans configuration
