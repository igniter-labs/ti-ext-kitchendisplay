---
title: "Getting Started"
section: "extensions"
sortOrder: 1
---

# Getting Started with Kitchen Display System

## Requirements

- TastyIgniter 4.0 or higher
- PHP 8.1 or higher
- Event broadcasting configured (Redis, Pusher, or other broadcast driver)

## Installation

### Step 1: Install via Composer

```bash
composer require igniterlabs/ti-ext-kitchendisplay -W
```

The `-W` flag updates your composer.lock file and ensures all dependencies are properly resolved.

### Step 2: Run Database Migrations

```bash
php artisan igniter:up
```

This command will:
- Create the `kitchen_displays` table with all required columns
- Set up the necessary database schema for storing kitchen display configurations

### Step 3: Verify Installation

1. Log in to your TastyIgniter admin panel
2. Navigate to **Tools** menu
3. You should see **Kitchen Display** option
4. Click on it to access the kitchen display management page

## Quick Start (5 Steps)

### 1. Create Your First Kitchen Display

1. Navigate to **Tools → Kitchen Display**
2. Click the **Create New** button
3. Enter a title (e.g., "Main Kitchen" or "Pizza Station")
4. Enable the display using the **Status** toggle
5. Click **Save**

### 2. Configure Basic Settings

The default configuration includes:
- All locations (can be filtered)
- All order statuses (can be filtered)
- All order types (delivery/collection)
- Orders limit: 20
- Display from: Today

This is a good starting point for initial testing.

### 3. Access the Kitchen Display

1. Click on your newly created display in the list
2. The kitchen display view opens
3. Orders will appear in columns based on their status (if any orders exist)

### 4. Test with Sample Orders

If you don't have existing orders:
1. Create a test order through the restaurant ordering system
2. Place it with a status that matches your configured columns (e.g., "Received")
3. Return to the kitchen display
4. The order should appear in the corresponding column

### 5. Customize as Needed

Once you verify it's working:
- Return to edit your display configuration
- Adjust filters (locations, statuses, order types)
- Configure which columns to show/hide
- Map statuses to columns based on your workflow
- Hide sensitive fields if needed

See [Configuration Guide](./configuration.md) for detailed customization options.

## Common First-Time Issues

### Kitchen Display Not Appearing in Menu
- Ensure you have `IgniterLabs.KitchenDisplay.Manage` permission
- Contact your administrator if you don't have this permission

### No Orders Showing
- Check that at least one order exists in the system
- Verify order status matches your configured column statuses
- Check the "Display from Date" setting
- Ensure location/type filters aren't excluding the order

### Real-time Updates Not Working
- Verify event broadcasting is configured in your `.env` file
- Check that your broadcast driver (Redis, Pusher) is running
- Restart your queue workers if using Redis
- Check browser console (F12) for any JavaScript errors

## Next Steps

1. **Read [Features](./features.md)** - Understand all available capabilities
2. **Read [Configuration Guide](./configuration.md)** - Set up your displays optimally
3. **Read [User Guide](./user-guide.md)** - Learn how kitchen staff use the system
4. **Read [Best Practices](./best-practices.md)** - Set up your displays for maximum efficiency

## Need Help?

- Check the [Troubleshooting Guide](./troubleshooting.md)
- Review the [Configuration Guide](./configuration.md) for detailed option explanations
- Visit [GitHub Issues](https://github.com/igniter-labs/ti-ext-kitchendisplay/issues)
