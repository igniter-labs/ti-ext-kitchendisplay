---
title: "Technical Reference"
section: "extensions"
sortOrder: 5
---

# Technical Reference

This document provides technical details for developers and system administrators.

## Database Schema

### Table: `kitchen_displays`

The kitchen display configuration is stored in the `kitchen_displays` table.

#### Column Definitions

| Column | Type | Default | Description |
|--------|------|---------|-------------|
| `id` | BIGINT UNSIGNED | Auto-increment | Primary key |
| `title` | VARCHAR(255) | Required | Display configuration name |
| `is_enabled` | BOOLEAN | true | Enable/disable this display |
| `locations` | JSON | NULL | Array of location IDs |
| `order_statuses` | JSON | NULL | Array of status IDs to display |
| `order_types` | JSON | NULL | Array of order types (delivery, collection) |
| `menu_categories` | JSON | NULL | Array of menu category IDs |
| `orders_limit` | UNSIGNED INT | 20 | Max orders to show (1-100) |
| `display_from_date` | DATE | TODAY | Start date for order display |
| `column_new_statuses` | JSON | ["Received"] | Status IDs for "New" column |
| `column_new_visible` | BOOLEAN | true | Show/hide "New" column |
| `column_preparing_statuses` | JSON | ["Preparation"] | Status IDs for "Preparing" column |
| `column_preparing_visible` | BOOLEAN | true | Show/hide "Preparing" column |
| `column_ready_statuses` | JSON | ["Delivery"] | Status IDs for "Ready" column |
| `column_ready_visible` | BOOLEAN | true | Show/hide "Ready" column |
| `column_completed_statuses` | JSON | ["Completed"] | Status IDs for "Completed" column |
| `column_completed_visible` | BOOLEAN | true | Show/hide "Completed" column |
| `column_on_hold_status` | UNSIGNED INT | NULL | Single status ID for "On Hold" column |
| `column_on_hold_visible` | BOOLEAN | true | Show/hide "On Hold" column |
| `hidden_card_fields` | JSON | NULL | Fields to hide (customer_name, order_id, order_type) |
| `created_at` | TIMESTAMP | NOW | Record creation timestamp |
| `updated_at` | TIMESTAMP | NOW | Record last update timestamp |

#### JSON Column Examples

**locations** (Multiple locations):
```json
["1", "2", "3"]
```

**order_statuses** (Multiple statuses):
```json
["2", "3", "4"]
```

**column_new_statuses** (Multiple statuses in array):
```json
["1"]
```

**hidden_card_fields** (Multiple hidden fields):
```json
["customer_name", "order_type"]
```

## Frontend Architecture

### Data Attributes

The kitchen display container uses HTML5 data attributes to pass configuration to JavaScript:

```html
<div
    data-control="kitchen-display"
    data-kitchen-display-id="1"
    data-on-hold-status-id="5"
    class="p-4"
>
    <!-- Board content -->
</div>
```

| Attribute | Purpose | Example |
|-----------|---------|---------|
| `data-control` | jQuery plugin identifier | `kitchen-display` |
| `data-kitchen-display-id` | Current display ID | `1` |
| `data-on-hold-status-id` | On-hold status ID | `5` |

### jQuery Plugin Structure

The Kitchen Display System is implemented as a jQuery plugin:

```javascript
$.fn.kitchenDisplay = function(option) {
    // Plugin initialization and method invocation
}
```

#### Key Methods

| Method | Purpose |
|--------|---------|
| `init()` | Initialize plugin, set up event handlers |
| `refreshOrdersWithScrollRestore()` | Refresh board while preserving scroll position |
| `updateOrderStatus()` | Update order status via AJAX |
| `updateWaitTime()` | Update order wait time via AJAX |
| `showOrderDetailsModal()` | Display order details modal |
| `showCustomTimeModal()` | Display custom time input modal |
| `initWaitTimeHandlers()` | Set up wait time event listeners |
| `initRefreshButton()` | Set up refresh button handler |
| `hideSidebar()` | Toggle sidebar visibility with persistence |

#### Event Delegation

All event handlers use event delegation for persistence across DOM updates:

```javascript
$(document).on('click', '.status-option', function() {
    // Handle status option click
});
```

This ensures handlers continue working after AJAX refresh.

### Scroll Position Preservation

The plugin preserves both scroll axes during refresh:

```javascript
let scrollTop = $(window).scrollTop();  // Vertical scroll
let scrollLeft = $board.scrollLeft();   // Horizontal scroll

// After AJAX update:
$(window).scrollTop(scrollTop);
$board.scrollLeft(scrollLeft);
```

## Event Broadcasting

### Channel Configuration

Kitchen Display uses Laravel's event broadcasting for real-time updates:

- **Channel**: `igniterlabs.kitchendisplay`
- **Event**: `.kitchendisplay.updated`

### Broadcasting in Code

Events are broadcast when orders are updated:

```php
Broadcast::on('igniterlabs.kitchendisplay')
    ->send('.kitchendisplay.updated', $orderData);
```

### Client-Side Listening

The JavaScript plugin listens for events:

```javascript
Broadcast.channel('igniterlabs.kitchendisplay')
    .listen('.kitchendisplay.updated', (e) => {
        $this.refreshOrdersWithScrollRestore();
    })
```

### Required Setup

For event broadcasting to work, configure in `.env`:

```bash
BROADCAST_DRIVER=redis  # or pusher, other drivers
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Ensure your broadcast driver is running:
- **Redis**: Redis server running
- **Pusher**: Pusher account configured
- **Local**: For development/testing only

## API Endpoints

### Controller: `KitchenDisplay`

#### `view(string $context, string $kitchenDisplayId)`

**Purpose**: Display the kitchen display interface

**Parameters**:
- `$context`: Route context (typically 'kitchen_display')
- `$kitchenDisplayId`: ID of the kitchen display configuration

**Returns**: Kitchen display view with board columns and orders

**Permissions**: User must have `IgniterLabs.KitchenDisplay.Manage` permission

#### `onRefreshOrders()`

**Purpose**: AJAX endpoint to refresh order list

**POST Data**:
- `kitchenDisplayId`: ID of the kitchen display

**Returns**: JSON with rendered board partial HTML

**Used by**: Manual refresh button and auto-refresh on event broadcast

#### `onUpdateOrderStatus()`

**Purpose**: AJAX endpoint to update order status

**POST Data**:
- `order_id`: Order ID to update
- `status`: New status ID

**Returns**: Success/error response

**Used by**: Status dropdown and pause button

#### `onUpdateWaitTime()`

**Purpose**: AJAX endpoint to update order wait time

**POST Data**:
- `order_id`: Order ID to update
- `minutes`: Increment minutes (integer)
- `custom_time`: Custom time in HH:MM format (string)

**Returns**: Success/error response

**Used by**: Wait time increment and custom time modal

## Model: `KitchenDisplay`

### Key Properties

```php
protected $table = 'kitchen_displays';

protected $casts = [
    'is_enabled' => 'boolean',
    'locations' => 'array',
    'order_statuses' => 'array',
    'order_types' => 'array',
    'menu_categories' => 'array',
    'column_new_statuses' => 'array',
    'column_new_visible' => 'boolean',
    'column_preparing_statuses' => 'array',
    'column_preparing_visible' => 'boolean',
    'column_ready_statuses' => 'array',
    'column_ready_visible' => 'boolean',
    'column_completed_statuses' => 'array',
    'column_completed_visible' => 'boolean',
    'column_on_hold_status' => 'integer',
    'column_on_hold_visible' => 'boolean',
    'hidden_card_fields' => 'array',
    'display_from_date' => 'date',
];
```

### Static Methods

#### `getOrderStatus(string $statusName)`

**Purpose**: Get status ID by name

**Parameters**:
- `$statusName`: Status name (e.g., "Received", "Preparation")

**Returns**: Array of status IDs or empty array

**Example**:
```php
$statusIds = KitchenDisplay::getOrderStatus('Received');
// Returns: ['1']
```

## Order Query Logic

### Filtering Pipeline

Orders are filtered through the following pipeline:

1. **Exclude Status 0**: `status_id <> 0`
2. **Exclude Rejected**: `whereDoesntHave('status', 'status_name' = 'Rejected')`
3. **Location Filter**: `whereIn('location_id', $locations)` (if set)
4. **Status Filter**: `whereIn('status_id', $statuses)` (if set)
5. **Type Filter**: `whereIn('order_type', $types)` (if set)
6. **Category Filter**: `whereHas('menus.menu.categories', ...)` (if set)
7. **Date Filter**: `whereDate('order_date', '>=', $displayFromDate)`
8. **Order**: `latest()` (most recent first)
9. **Limit**: `take($limit)` (max orders to display)

### Category Filtering

Categories are filtered through the order-menu-category relationship chain:

```php
whereHas('menus.menu.categories', function ($q) use ($categories) {
    $q->whereIn('category_id', $categories);
});
```

This requires:
- Order has many Menus (through OrderMenu)
- Menu has many Categories (through menu_categories pivot)
- Filter by category_id on the categories table

## Permissions

### Required Permission

```
IgniterLabs.KitchenDisplay.Manage
```

This permission controls:
- Viewing the kitchen display list
- Creating new displays
- Editing existing displays
- Deleting displays
- Accessing/viewing the kitchen display interface

### Permission Definition

Permissions are defined in the extension's admin configuration.

## File Structure

```
├── docs/                                    # Documentation
│   ├── index.md                            # Main documentation index
│   ├── getting-started.md                  # Installation & quick start
│   ├── configuration.md                    # Configuration guide
│   ├── user-guide.md                       # User instructions
│   ├── features.md                         # Feature overview
│   ├── technical-reference.md              # This file
│   ├── troubleshooting.md                  # Troubleshooting guide
│   └── best-practices.md                   # Best practices
├── src/
│   ├── Http/
│   │   ├── Controllers/KitchenDisplay.php  # Main controller
│   │   └── Requests/KitchenDisplayRequest.php
│   └── Models/KitchenDisplay.php           # Model
├── resources/
│   ├── lang/
│   │   └── en/default.php                  # Language strings
│   ├── views/
│   │   ├── kitchendisplay.blade.php        # Main template
│   │   ├── partials/
│   │   │   ├── kitchendisplay-board.blade.php
│   │   │   ├── order-card-header.blade.php
│   │   │   ├── order-meta.blade.php
│   │   │   ├── order-type.blade.php
│   │   │   ├── order-items.blade.php
│   │   │   └── order-actions.blade.php
│   │   └── modals/
│   │       ├── custom-time-modal.blade.php
│   │       └── order-details-modal.blade.php
│   ├── js/
│   │   └── kitchendisplay.js              # Main jQuery plugin
│   └── css/
│       └── kitchendisplay.css             # Styles
├── database/
│   └── migrations/
│       └── 2025_10_15_create_kitchen_displays_table.php
└── resources/
    └── models/
        └── kitchendisplay.php              # Admin form config
```

## Performance Considerations

### Database Queries

The main query fetches orders with relationships:
- Filters are applied progressively to minimize result set
- Uses `latest()` with `take()` to limit rows
- Relationship loading is eager (whereHas) to avoid N+1 queries

### Frontend Performance

- Event delegation prevents memory leaks from repeated binding
- AJAX partial return reduces payload size
- Scroll position preservation prevents re-rendering of off-screen content
- jQuery selectors are cached where possible

### Optimization Tips

1. **Reduce Orders Limit** if display is slow
2. **Use specific filters** (location, status) to reduce data
3. **Hide unnecessary columns** to reduce rendering
4. **Monitor queue workers** if using Redis broadcasting

## Security Considerations

### Permission Checks
- All actions require `IgniterLabs.KitchenDisplay.Manage` permission
- Controller aborts with 404/403 if display doesn't exist or is disabled

### Input Validation
- Form requests validate all configuration inputs
- Status IDs are verified against database
- Location IDs are verified against existing locations
- Date inputs are properly validated

### Output Escaping
- Blade templates use `{{ }}` syntax for automatic escaping
- Modal content is sanitized before display
- User input is validated before storing

---

[← Back to Index](./index.md) | [Troubleshooting →](./troubleshooting.md)
