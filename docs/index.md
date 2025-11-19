---
title: "Kitchen Display"
section: "extensions"
sortOrder: 999
---

## Installation

You can install the extension via composer using the following command:

```bash
composer require igniterlabs/ti-ext-kitchendisplay -W
```

Run the database migrations to create the required tables:

```bash
php artisan igniter:up
```


## Getting Started

Navigate to **Tools → Kitchen Display** in the admin panel to create and manage displays.

### Quick Setup

1. Navigate to **Tools → Kitchen Display**
2. Click the **New** button
3. Enter a title (e.g., "Main Kitchen" or "Pizza Station")
4. Follow the on-screen configuration options
5. Enable the display using the **Status** toggle
6. Click **Save**

## Configuration

Kitchen displays can be customized via the configuration form under **Tools → Kitchen Display** from the form view. The following settings are available:

**General Settings**

| Setting               | Description                                |
|-----------------------|--------------------------------------------|
| **Title**             | Display name                               |
| **Locations**         | Filter by location (empty = all)           |
| **Order Types**       | Filter by delivery/collection              |
| **Menu Categories**   | Filter by category                         |
| **Orders Limit**      | Max orders to display (1-100, default: 20) |

**Board Columns**

Configure up to 5 columns:

- **New** - Status for new orders (default: Received)
- **Preparing** - Status for orders in progress (default: Preparation)
- **Ready** - Status for orders ready for delivery/collection (default: Delivery)
- **Completed** - Status for completed orders (default: Completed)
- **On Hold** - Single status for paused orders (default: Pending)

Each column can be shown/hidden and assigned associated order status.

**Hide card details**

Hide details from order cards: Customer Name, Order ID, Order Type.

## Usage

### Viewing Orders

1. Navigate to **Tools → Kitchen Display**
2. Click the desired kitchen display from the list
3. Orders appear in columns based on their status.

### Updating Status

1. Navigate to **Tools → Kitchen Display**
2. Click the desired kitchen display from the list
3. Click **Next** button, then select a status from the dropdown 
4. Click **Pause** button to move order to On Hold column
5. Order moves automatically to match column status configuration.

### Wait Time Management

1. Navigate to **Tools → Kitchen Display**
2. Click the desired kitchen display from the list 
3. Click order time, then select preset increment (+15, +30, +45 mins)
4. Or select **Custom** to enter a specific time in HH:MM format

### Real-time Updates

This extension uses event broadcasting to provide real-time updates to the kitchen display when new orders arrive or order statuses change. You must have the [TastyIgniter broadcast extension](https://tastyigniter.com/marketplace/item/igniter-broadcast) installed and configured for this feature to work.

### Permissions

The Reports extension registers the following permission:

- `IgniterLabs.KitchenDisplay.Manage`: Control who can view & manage kitchen displays in the admin area.

For more on restricting access to the admin area, see the [TastyIgniter Permissions](https://tastyigniter.com/docs/customize/permissions) documentation.
