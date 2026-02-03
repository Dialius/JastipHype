# Task 13: Checkpoint - Order and Customer Management Testing

## Status: ✅ READY FOR TESTING

## Overview
This checkpoint validates the implementation of Order Management (Task 11) and Customer Management (Task 12) including messaging functionality. All features should be tested to ensure they work correctly before proceeding to Banner Management.

## Prerequisites

### 1. Admin User Setup
Ensure you have an admin user account:
```bash
php artisan tinker
```
```php
$user = User::find(1); // or create new user
$user->is_admin = true;
$user->save();
```

### 2. Test Data
Ensure you have test data:
- At least 5-10 customers
- At least 5-10 orders with various statuses
- At least 1 order per customer
- Some customers with multiple orders

You can use the setup script:
```bash
php setup-admin-test.php
```

### 3. Access Admin Panel
Navigate to: `http://localhost:8000/admin/dashboard`
Login with admin credentials

---

## Testing Checklist

### A. Order Management Testing (Task 11)

#### A1. Order List Page (`/admin/orders`)
- [ ] **Access**: Can access order list page
- [ ] **Display**: Orders are displayed in table format
- [ ] **Status Tabs**: Can see status filter tabs (All, Pending, Processing, Shipped, Delivered, Cancelled)
- [ ] **Click Tabs**: Clicking tabs filters orders by status
- [ ] **Search**: Can search orders by order ID or customer name
- [ ] **Date Filter**: Can filter orders by date range
- [ ] **Payment Filter**: Can filter orders by payment method
- [ ] **Pagination**: Pagination works correctly
- [ ] **Order Count**: Shows correct order count per status

**Expected Results:**
- All orders displayed with correct information
- Filters work without errors
- Status badges show correct colors
- Pagination shows correct page numbers

#### A2. Order Detail Page (`/admin/orders/{id}`)
- [ ] **Access**: Can access order detail from list
- [ ] **Order Info**: Shows complete order information (ID, date, status, total)
- [ ] **Customer Info**: Shows customer name, email, phone
- [ ] **Shipping Info**: Shows shipping address and method
- [ ] **Order Items**: Shows all order items with product names, quantities, prices
- [ ] **Payment Info**: Shows payment method and status
- [ ] **Timeline**: Shows order timeline with status changes
- [ ] **Status Badge**: Shows current status with correct color

**Expected Results:**
- All order details displayed correctly
- Timeline shows chronological events
- Totals calculated correctly (subtotal + shipping = total)

#### A3. Update Order Status
- [ ] **Status Button**: Can click "Update Status" button
- [ ] **Modal Opens**: Status update modal opens
- [ ] **Status Options**: Can select new status from dropdown
- [ ] **Email Toggle**: Can toggle email notification checkbox
- [ ] **Submit**: Can submit status update
- [ ] **Success Message**: Shows success message after update
- [ ] **Status Updated**: Order status updated in database
- [ ] **Timeline Updated**: New status appears in timeline
- [ ] **Email Sent**: Email notification sent (check logs if SMTP not configured)

**Test Scenarios:**
1. Update order from "pending" to "processing"
2. Update order from "processing" to "shipped"
3. Update order from "shipped" to "delivered"
4. Try updating with email notification enabled
5. Try updating with email notification disabled

**Expected Results:**
- Status updates successfully
- Timeline shows new status with timestamp
- Email logged (or sent if SMTP configured)
- No errors in console or logs

#### A4. Cancel Order
- [ ] **Cancel Button**: Can click "Cancel Order" button
- [ ] **Modal Opens**: Cancellation modal opens
- [ ] **Reason Input**: Can enter cancellation reason
- [ ] **Submit**: Can submit cancellation
- [ ] **Success Message**: Shows success message
- [ ] **Status Changed**: Order status changed to "cancelled"
- [ ] **Stock Restored**: Product stock restored (check product page)
- [ ] **Timeline Updated**: Cancellation appears in timeline

**Test Scenarios:**
1. Cancel an order with reason "Customer requested"
2. Verify product stock increased
3. Check timeline shows cancellation

**Expected Results:**
- Order cancelled successfully
- Stock restored to products
- Cancellation reason saved
- Timeline updated

#### A5. Sync Payment Status
- [ ] **Sync Button**: Can click "Sync Payment Status" button
- [ ] **Loading State**: Shows loading indicator
- [ ] **Status Updated**: Payment status synced from Midtrans
- [ ] **Success Message**: Shows success or error message
- [ ] **Display Updated**: Payment status updated on page

**Note:** This requires valid Midtrans configuration and order_id

#### A6. Export Orders
- [ ] **Export Button**: Can click "Export Orders" button
- [ ] **Date Range**: Can select date range (optional)
- [ ] **Download**: CSV file downloads
- [ ] **File Content**: CSV contains correct order data
- [ ] **Columns**: All expected columns present (ID, Customer, Date, Total, Status, etc.)

**Expected Results:**
- CSV file downloads successfully
- Data matches orders in database
- Proper CSV formatting

---

### B. Customer Management Testing (Task 12)

#### B1. Customer List Page (`/admin/customers`)
- [ ] **Access**: Can access customer list page
- [ ] **Statistics**: Shows 4 stat cards (Total, Active, Suspended, New This Month)
- [ ] **Display**: Customers displayed in table format
- [ ] **Search**: Can search by name or email
- [ ] **Status Filter**: Can filter by status (All, Active, Suspended)
- [ ] **Sort Options**: Can sort by (Latest, Oldest, Most Orders, Highest Spending)
- [ ] **Orders Count**: Shows correct order count per customer
- [ ] **Total Spent**: Shows correct total spent per customer
- [ ] **Status Badge**: Shows correct status badge (Active/Suspended)
- [ ] **Pagination**: Pagination works correctly
- [ ] **Action Buttons**: View and Edit buttons work

**Expected Results:**
- All customers displayed correctly
- Statistics match database counts
- Filters and sorting work without errors
- Pagination shows correct counts

#### B2. Customer Detail Page (`/admin/customers/{id}`)
- [ ] **Access**: Can access customer detail from list
- [ ] **Profile Card**: Shows customer profile with avatar icon
- [ ] **Status Badge**: Shows correct status (Active/Suspended)
- [ ] **Analytics Cards**: Shows 4 metric cards (Total Orders, Total Spent, Avg Order Value, Total Reviews)
- [ ] **Tabs**: Can see 3 tabs (Orders, Profile, Messages)
- [ ] **Orders Tab**: Shows recent orders table
- [ ] **Profile Tab**: Shows customer information table
- [ ] **Messages Tab**: Shows message interface
- [ ] **Edit Button**: Edit button navigates to edit page
- [ ] **Suspend Button**: Suspend button visible for active customers
- [ ] **Activate Button**: Activate button visible for suspended customers

**Expected Results:**
- All customer data displayed correctly
- Analytics calculated correctly
- Tabs switch without page reload
- Action buttons work

#### B3. Customer Edit
- [ ] **Access**: Can access edit page from detail
- [ ] **Form Fields**: Shows name, email, phone fields
- [ ] **Pre-filled**: Fields pre-filled with current data
- [ ] **Validation**: Form validates required fields
- [ ] **Email Unique**: Email uniqueness validated (except current)
- [ ] **Submit**: Can submit form
- [ ] **Success Message**: Shows success message
- [ ] **Data Updated**: Customer data updated in database
- [ ] **Redirect**: Redirects to customer detail page

**Test Scenarios:**
1. Update customer name
2. Update customer email (to unique email)
3. Try updating email to existing email (should fail)
4. Update phone number
5. Leave required field empty (should fail)

**Expected Results:**
- Valid updates succeed
- Invalid updates show error messages
- Validation works correctly

#### B4. Suspend Customer
- [ ] **Suspend Button**: Can click "Suspend" button
- [ ] **Modal Opens**: Suspension modal opens
- [ ] **Warning Message**: Shows warning about suspension
- [ ] **Reason Input**: Can enter suspension reason
- [ ] **Required**: Reason is required
- [ ] **Submit**: Can submit suspension
- [ ] **Success Message**: Shows success message
- [ ] **Status Changed**: Customer status changed to "suspended"
- [ ] **Reason Saved**: Suspension reason saved in database
- [ ] **Button Changed**: Suspend button changes to Activate button

**Test Scenarios:**
1. Suspend customer with reason "Fraudulent activity"
2. Try suspending without reason (should fail)
3. Verify status changed in database

**Expected Results:**
- Customer suspended successfully
- Reason saved and visible
- Status badge updated

#### B5. Activate Customer
- [ ] **Activate Button**: Can click "Activate" button (on suspended customer)
- [ ] **Confirmation**: Shows confirmation dialog
- [ ] **Submit**: Can confirm activation
- [ ] **Success Message**: Shows success message
- [ ] **Status Changed**: Customer status changed to "active"
- [ ] **Reason Cleared**: Suspension reason cleared
- [ ] **Button Changed**: Activate button changes to Suspend button

**Expected Results:**
- Customer activated successfully
- Status badge updated
- Can access account again

#### B6. Export Customers
- [ ] **Export Button**: Can click "Export CSV" button
- [ ] **Download**: CSV file downloads
- [ ] **File Content**: CSV contains correct customer data
- [ ] **Columns**: All expected columns present (ID, Name, Email, Phone, Orders, Spent, Status, Registered)
- [ ] **Filter Respect**: Export respects current filters

**Expected Results:**
- CSV downloads successfully
- Data matches customers in database
- Proper CSV formatting

---

### C. Customer Messaging Testing (Task 12.2)

#### C1. Individual Messaging
- [ ] **Messages Tab**: Can access Messages tab in customer detail
- [ ] **Message Form**: Shows send message form
- [ ] **Email Toggle**: Shows email notification checkbox
- [ ] **Send Message**: Can type and send message
- [ ] **Success**: Message sent successfully
- [ ] **Display**: Message appears in thread
- [ ] **Admin Side**: Admin message on right with blue background
- [ ] **Timestamp**: Shows correct timestamp
- [ ] **Email Logged**: Email notification logged (check logs)
- [ ] **Form Reset**: Form clears after sending

**Test Scenarios:**
1. Send message with email notification enabled
2. Send message with email notification disabled
3. Send multiple messages to same customer
4. Check message thread displays correctly

**Expected Results:**
- Messages sent successfully
- Thread displays chronologically
- Email notifications logged
- No JavaScript errors

#### C2. Message Thread Display
- [ ] **Load Messages**: Messages load when tab is clicked
- [ ] **Thread Format**: Messages displayed in conversation format
- [ ] **Admin Messages**: Admin messages on right (blue)
- [ ] **Customer Messages**: Customer messages on left (white)
- [ ] **Timestamps**: All messages show timestamps
- [ ] **Auto Scroll**: Thread auto-scrolls to latest message
- [ ] **Empty State**: Shows "No messages yet" when empty

**Expected Results:**
- Messages load via AJAX
- Thread displays correctly
- Scrolling works smoothly

#### C3. Bulk Messaging
- [ ] **Access**: Can access bulk messaging at `/admin/messages/bulk/form`
- [ ] **Menu Item**: "Bulk Messaging" menu item in sidebar works
- [ ] **Form Fields**: Shows subject, message, segment fields
- [ ] **Segmentation**: Can select segment (All, Active, Suspended, High Spenders, Recent Orders)
- [ ] **Advanced Filters**: Shows advanced filter fields
- [ ] **Info Sidebar**: Shows segmentation guide
- [ ] **Validation**: Form validates required fields
- [ ] **Submit**: Can submit bulk message
- [ ] **Success Message**: Shows success with sent count
- [ ] **Messages Created**: Messages created in database
- [ ] **Emails Logged**: Email notifications logged

**Test Scenarios:**
1. Send to "All Customers"
2. Send to "Active Customers Only"
3. Send to "High Spenders" with min_spending = 500000
4. Send to "Recent Orders" with days_since_order = 30
5. Use advanced filters (min_spending, min_orders)

**Expected Results:**
- Bulk messages sent successfully
- Correct customers selected based on segment
- Success message shows correct count
- All messages logged

#### C4. Bulk Messaging Segmentation
- [ ] **All Customers**: Sends to all customers
- [ ] **Active Only**: Sends only to active customers
- [ ] **Suspended**: Sends only to suspended customers
- [ ] **High Spenders**: Filters by spending amount
- [ ] **Recent Orders**: Filters by order date
- [ ] **Min Spending**: Advanced filter works
- [ ] **Max Spending**: Advanced filter works
- [ ] **Min Orders**: Advanced filter works
- [ ] **Days Since Order**: Advanced filter works

**Test Scenarios:**
1. Create customers with different spending amounts
2. Test each segmentation option
3. Verify correct customers receive messages
4. Check sent count matches expected count

**Expected Results:**
- Segmentation filters work correctly
- Only targeted customers receive messages
- Counts are accurate

---

## Integration Testing

### Scenario 1: Complete Order Flow
1. View order list
2. Click on an order
3. View order details
4. Update status to "processing"
5. Update status to "shipped"
6. Update status to "delivered"
7. Check timeline shows all status changes
8. Verify email notifications logged

**Expected:** Order progresses through statuses correctly

### Scenario 2: Order Cancellation Flow
1. View order list
2. Click on a pending order
3. Note product stock before cancellation
4. Cancel order with reason
5. Check product stock increased
6. Verify order status is "cancelled"
7. Check timeline shows cancellation

**Expected:** Order cancelled and stock restored

### Scenario 3: Customer Management Flow
1. View customer list
2. Click on a customer
3. View customer analytics
4. Send message to customer
5. Check message appears in thread
6. Edit customer information
7. Suspend customer account
8. Activate customer account

**Expected:** All customer operations work correctly

### Scenario 4: Bulk Messaging Flow
1. Access bulk messaging form
2. Select "High Spenders" segment
3. Set min_spending to 1000000
4. Enter subject and message
5. Send bulk message
6. Check success message with count
7. Verify messages in database
8. Check email notifications logged

**Expected:** Bulk message sent to correct customers

---

## Common Issues and Solutions

### Issue 1: Orders Not Displaying
**Symptoms:** Order list is empty or shows error
**Solutions:**
- Check if orders exist in database: `SELECT * FROM orders;`
- Check admin middleware is applied
- Check OrderController and OrderRepository
- Check browser console for JavaScript errors

### Issue 2: Customer Statistics Incorrect
**Symptoms:** Statistics cards show wrong numbers
**Solutions:**
- Check CustomerRepository count methods
- Verify database queries
- Check status field values in database
- Clear cache: `php artisan cache:clear`

### Issue 3: Messages Not Sending
**Symptoms:** Message form submits but nothing happens
**Solutions:**
- Check browser console for JavaScript errors
- Check network tab for AJAX request
- Check MessageController route is registered
- Check CSRF token is present
- Check Laravel logs: `storage/logs/laravel.log`

### Issue 4: Email Notifications Not Working
**Symptoms:** No email notifications received
**Solutions:**
- Check SMTP configuration in `.env`
- Check NotificationService is logging emails
- Check queue is running: `php artisan queue:work`
- Check mail logs: `storage/logs/laravel.log`
- For testing, emails are logged (not sent)

### Issue 5: Export CSV Not Downloading
**Symptoms:** Export button doesn't download file
**Solutions:**
- Check browser console for errors
- Check export route is registered
- Check controller export method
- Check file permissions
- Try different browser

### Issue 6: Status Update Not Working
**Symptoms:** Status update modal submits but status doesn't change
**Solutions:**
- Check OrderController updateStatus method
- Check OrderService is updating correctly
- Check database for status change
- Check validation rules
- Check Laravel logs for errors

---

## Performance Testing

### Load Testing
- [ ] Test with 100+ orders
- [ ] Test with 100+ customers
- [ ] Test pagination with large datasets
- [ ] Test search with large datasets
- [ ] Test export with large datasets

**Expected:** No significant slowdown, queries optimized

### Response Time
- [ ] Order list loads in < 2 seconds
- [ ] Customer list loads in < 2 seconds
- [ ] Order detail loads in < 1 second
- [ ] Customer detail loads in < 1 second
- [ ] Message sending responds in < 1 second

**Expected:** Fast response times, no timeouts

---

## Security Testing

### Authentication
- [ ] Non-admin users cannot access admin panel
- [ ] Logged out users redirected to login
- [ ] Session expires after inactivity
- [ ] Admin middleware protects all routes

### Authorization
- [ ] Only admins can update order status
- [ ] Only admins can suspend customers
- [ ] Only admins can send messages
- [ ] Only admins can export data

### Input Validation
- [ ] Form validation prevents empty submissions
- [ ] Email validation works correctly
- [ ] SQL injection prevented (use parameterized queries)
- [ ] XSS prevented (use Blade escaping)
- [ ] CSRF protection enabled on all forms

---

## Browser Compatibility

Test on multiple browsers:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

Test on mobile:
- [ ] Chrome Mobile
- [ ] Safari Mobile

**Expected:** Consistent behavior across browsers

---

## Responsive Design Testing

Test on different screen sizes:
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

**Expected:** Layout adapts correctly, no horizontal scrolling

---

## Final Checklist

Before proceeding to Task 14:
- [ ] All order management features work correctly
- [ ] All customer management features work correctly
- [ ] All messaging features work correctly
- [ ] No critical bugs found
- [ ] No console errors
- [ ] No Laravel errors in logs
- [ ] Performance is acceptable
- [ ] Security checks passed
- [ ] Responsive design works
- [ ] Browser compatibility confirmed

---

## Sign-off

**Tested By:** _________________
**Date:** _________________
**Status:** [ ] PASS [ ] FAIL
**Notes:**

---

## Next Steps

After completing this checkpoint:
1. Document any bugs found
2. Fix critical bugs before proceeding
3. Mark Task 13 as complete in tasks.md
4. Proceed to Task 14: Banner Management

---

## Quick Test Commands

```bash
# Clear cache
php artisan cache:clear

# Check routes
php artisan route:list | grep admin

# Check database
php artisan tinker
>>> Order::count()
>>> User::where('is_admin', false)->count()
>>> CustomerMessage::count()

# Check logs
tail -f storage/logs/laravel.log

# Run queue worker (for email notifications)
php artisan queue:work
```

---

## Summary

This checkpoint ensures that:
1. ✅ Order Management (Task 11) is fully functional
2. ✅ Customer Management (Task 12.1) is fully functional
3. ✅ Customer Messaging (Task 12.2) is fully functional
4. ✅ All features integrate correctly
5. ✅ No critical bugs exist
6. ✅ Ready to proceed to Banner Management

**Estimated Testing Time:** 2-3 hours for complete testing
