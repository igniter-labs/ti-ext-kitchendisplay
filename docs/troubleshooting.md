---
title: "Troubleshooting"
section: "extensions"
sortOrder: 6
---

# Troubleshooting Guide

## Common Issues and Solutions

### No Orders Appearing on Display

#### Symptoms
- Kitchen display is enabled and accessible
- No order cards appear in any column
- Order count shows 0 in all columns

#### Possible Causes & Solutions

**1. Display Not Enabled**
- Check the **Status** setting is enabled
- Navigate to **Tools → Kitchen Display** and click edit
- Toggle the **Status** switch to ON
- Save and refresh the display

**2. Wrong Location Filter**
- Check if orders exist in the selected location(s)
- Go to **Orders** section and verify order locations match
- If location filter is set, try clearing it (set to "all")
- Create a test order in the correct location

**3. Status Filter Too Restrictive**
- Check **Order Status** filter
- Verify test order status matches one of the selected statuses
- Try clearing status filter (set to "all")
- Check what status your test orders have

**4. Wrong Display Date**
- Check **Display Orders From** setting
- If set to future date, no orders will show
- Default should be "Today"
- Change to today's date if set incorrectly
- Create a test order and wait a few seconds

**5. Category Filter Mismatch**
- If **Menu Categories** filter is set, orders must have items from those categories
- Create a test order with items from the filtered categories
- Check that the items are actually assigned to the selected categories
- Try clearing category filter to test

**6. Orders Limit Too Low**
- If **Orders Limit** is 1, only 1 order shows
- Increase the limit if you expect more orders
- Default is 20, reasonable range is 10-50

**7. Orders Status is "Rejected"**
- Rejected orders are automatically excluded
- Create a test order with a different status
- Check order status in **Orders** section

#### Quick Diagnostic Steps
1. Create a test order in admin system
2. Set order status to "Received" (default for New column)
3. Wait 5 seconds for event broadcast
4. Refresh the kitchen display manually
5. If test order appears, your filters are wrong
6. If test order doesn't appear, configuration is wrong

---

### Real-time Updates Not Working

#### Symptoms
- Orders don't update automatically when new orders arrive
- Must manually click refresh for orders to appear
- Event broadcasting seems inactive

#### Possible Causes & Solutions

**1. Event Broadcasting Not Configured**
- Check your `.env` file:
  ```bash
  BROADCAST_DRIVER=redis  # or pusher
  ```
- Default is `log` - change to `redis` or `pusher`
- Requires service restart after `.env` change:
  ```bash
  php artisan queue:restart
  ```

**2. Redis Not Running**
- If using Redis driver, verify Redis is running:
  ```bash
  redis-cli ping
  # Should return: PONG
  ```
- Start Redis if not running:
  ```bash
  redis-server
  ```

**3. Queue Workers Not Running**
- Start queue workers:
  ```bash
  php artisan queue:work --daemon
  ```
- Or use Supervisor for auto-restart
- Keep terminal window open or use background process manager

**4. Pusher Configuration Wrong**
- If using Pusher, check `.env`:
  ```bash
  PUSHER_APP_ID=your_app_id
  PUSHER_APP_KEY=your_app_key
  PUSHER_APP_SECRET=your_app_secret
  PUSHER_APP_CLUSTER=your_cluster
  ```
- Verify all values match your Pusher account
- Restart queue workers after changes

**5. Browser Cache Issue**
- Clear browser cache (Ctrl+Shift+Delete)
- Or open in private/incognito window
- Or press F5 multiple times to force refresh

**6. JavaScript Console Errors**
- Open browser developer tools (F12)
- Go to Console tab
- Check for JavaScript errors
- Look for messages about broadcast connection

#### Quick Diagnostic Steps
1. Check `.env` file for `BROADCAST_DRIVER` setting
2. Verify Redis is running: `redis-cli ping`
3. Verify queue workers are running: `php artisan queue:work --daemon`
4. Create a test order
5. Open browser console (F12)
6. Look for broadcast connection messages
7. Wait 10 seconds and check if order appears
8. If not, check for JavaScript errors in console

---

### Orders Disappearing or Moving Unexpectedly

#### Symptoms
- Orders appear but then disappear
- Orders move columns without action
- Status changes unexpectedly

#### Possible Causes & Solutions

**1. Completed Orders Hidden**
- Completed column might be hidden (visibility toggle OFF)
- Orders move to Completed when status changes
- Go to configuration and ensure **Completed column Visible** is ON
- Or just expect this behavior

**2. Another User Updated Order**
- Multiple users can access same display
- If one user updates order status, it moves immediately for all users
- This is correct behavior in multi-user environments
- Orders sync automatically

**3. Order Status Updated Elsewhere**
- Order status might change from mobile app or other system
- Kitchen display shows real-time status
- Refresh button shows latest state
- This is correct behavior

**4. Auto-Refresh Triggered**
- Display auto-refreshes when new orders arrive
- Scroll position is preserved, order positions may change
- If seeing cards move unexpectedly, check if new orders arrived

**5. Time Filter Moved Order Off Display**
- If "Display From Date" is set to specific date
- Orders from earlier dates disappear when setting advances
- Not common unless manually adjusting date

#### Solutions
1. Check configuration visibility settings
2. Monitor who is making changes in multi-user setup
3. Understand auto-refresh is normal behavior
4. Click refresh to see latest state if confused

---

### Modal Not Opening or Closing

#### Symptoms
- Order details modal doesn't appear when clicking order number
- Custom time modal won't open
- Modal appears but can't close it

#### Possible Causes & Solutions

**1. JavaScript Error**
- Check browser console (F12 → Console tab)
- Look for red error messages
- Common error: "bootstrap is not defined"
- Solution: Reload entire page (Ctrl+R)

**2. Bootstrap Modal Conflict**
- If multiple modals exist, close current one first
- Press Escape key to close current modal
- Or click outside modal to dismiss

**3. Multiple Backdrom Issue (Fixed)**
- Previous versions had multiple backdrop issue
- Current version reuses modal instances
- If seeing multiple backdrops, refresh page

**4. Content Not Rendering**
- Order details might be empty/missing data
- Try refreshing the board (click refresh button)
- Click order number again
- Check browser console for errors

#### Quick Fixes
1. Press Escape key to close
2. Reload page (Ctrl+R or Cmd+R)
3. Check browser console for errors
4. Try in different browser or incognito mode
5. Clear browser cache (Ctrl+Shift+Delete)

---

### Scroll Position Not Preserved

#### Symptoms
- Vertical scroll position resets after refresh
- Horizontal scroll position resets
- Must scroll back to same position after each refresh

#### Possible Causes & Solutions

**1. This Should Not Happen**
- Scroll preservation is a core feature
- Vertical scroll preserved via `$(window).scrollTop()`
- Horizontal scroll preserved via `.board.scrollLeft()`

**2. Browser JavaScript Disabled**
- Check if JavaScript is enabled
- In browser settings, enable JavaScript
- Reload page

**3. jQuery Not Loaded**
- Check browser console (F12)
- Look for "jQuery is not defined"
- Indicates jQuery not loading properly
- Reload page or contact IT

**4. Multiple Refreshes in Quick Succession**
- If clicking refresh multiple times rapidly
- Scroll restoration might not complete
- Wait for first refresh to finish (spinner stops)
- Then click refresh again if needed

#### Quick Diagnostic
1. Open browser console (F12)
2. Scroll to middle of page
3. Click refresh button
4. Watch spinner
5. Wait for spinner to stop
6. Check if scroll position maintained
7. If not, you found the bug!

---

### Pause Button Not Working

#### Symptoms
- Pause button not responding to clicks
- Pause button disabled or grayed out
- Orders not moving to on-hold column

#### Possible Causes & Solutions

**1. Order Already On Hold**
- Pause button hidden when order is on hold
- This is correct behavior - button only shows for active orders
- Click "Next" button instead to move out of on-hold

**2. On-Hold Status Not Configured**
- Admin must set on-hold status in configuration
- Default is "Pending" but can be changed
- Check with administrator if on-hold not working

**3. Status Doesn't Allow Hold**
- Some statuses might not support hold action
- Try pausing different orders
- Check order status in details modal

**4. Permission Issue**
- User might not have permission to update statuses
- Check with administrator
- Verify user has `IgniterLabs.KitchenDisplay.Manage` permission

**5. Network Issue**
- Pause action requires successful AJAX request
- Check internet connection
- Check browser console for network errors
- Try again after connection stable

#### Solutions
1. Verify order is not already on hold
2. Check order status in details modal
3. Try different order to test if pause works
4. Contact administrator if permission issue
5. Check internet connection stability

---

### Wait Time Not Updating

#### Symptoms
- Time doesn't change after increment
- Custom time modal saves but time doesn't update
- Time resets to original value

#### Possible Causes & Solutions

**1. Didn't Click Save in Custom Modal**
- In custom time modal, must click "Save" button
- Simply closing modal doesn't save change
- Re-open modal and verify save was clicked

**2. Invalid Time Format**
- Custom time must be in HH:MM format (24-hour)
- Valid: 14:30, 09:00, 23:45
- Invalid: 2:30 (missing leading zero), 14:30:00 (with seconds)
- Check format and try again

**3. Network Error During Save**
- Check internet connection
- Refresh page to sync latest time
- Try updating again

**4. Browser Auto-Complete Issue**
- Browser might auto-fill wrong value
- Clear the input field completely
- Type time carefully
- Verify before clicking save

**5. Order Status Locked**
- Some statuses might prevent time updates
- Try moving order to different status first
- Then update time

#### Quick Fixes
1. Verify you clicked Save button
2. Check time format (HH:MM, 24-hour)
3. Refresh page to sync time
4. Try quick increment (+15 mins) instead of custom
5. Try updating different order to test

---

### Performance Issues (Slow Display)

#### Symptoms
- Display loads slowly
- Takes long time to refresh
- Cards take long to render
- Clicking buttons feels sluggish

#### Possible Causes & Solutions

**1. Too Many Orders**
- If Orders Limit is high (50+), display becomes slow
- Reduce Orders Limit in configuration:
  - Try 20 (default)
  - Or 15 for high-volume kitchens
  - Maximum reasonable is 30-40

**2. Too Many Visible Columns**
- Each column requires rendering
- Hide columns you don't use:
  - Go to configuration
  - Toggle Visible OFF for unused columns
  - Save changes

**3. Too Detailed Order Items**
- Many items per order slow down rendering
- Items list is collapsible by default
- This helps performance

**4. Browser Performance Issue**
- Other browser tabs using resources
- Browser cache full
- Close other tabs/windows
- Clear browser cache (Ctrl+Shift+Delete)

**5. Server Performance Issue**
- Database query slow
- Server overloaded
- Contact administrator:
  - Check database performance
  - Monitor server resources
  - Optimize database indexes

**6. Network Slow**
- Slow internet connection
- AJAX requests slow
- High latency
- Check connection speed

#### Quick Performance Optimization
1. Reduce Orders Limit to 20
2. Hide Completed column if not needed
3. Set more restrictive filters (specific location/status)
4. Close other browser tabs
5. Clear browser cache
6. If still slow, contact administrator

---

## Getting More Help

### Before Contacting Support

1. **Reproduce the issue**: Try multiple times to confirm
2. **Note the steps**: Write down exactly what you did
3. **Check browser console**: F12 → Console for errors
4. **Note your setup**: Browser, OS, screen size
5. **Check configuration**: Verify all settings are correct

### Information to Provide When Reporting

- **Browser & Version**: Chrome 120, Firefox 121, Safari 17
- **OS**: Windows 11, macOS 14, Linux
- **Issue Description**: Detailed description of what's wrong
- **Steps to Reproduce**: Exact steps to make it happen
- **Expected Result**: What should happen
- **Actual Result**: What actually happens
- **Console Errors**: Any errors in F12 console
- **Configuration**: Your display settings
- **Screenshot/Video**: If possible

### Contact Methods

1. Check [GitHub Issues](https://github.com/igniter-labs/ti-ext-kitchendisplay/issues)
2. Review [Documentation](./index.md)
3. Contact your system administrator
4. Post on TastyIgniter forums

---

[← Back to Index](./index.md) | [Best Practices →](./best-practices.md)
