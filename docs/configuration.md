---
title: "Configuration Guide"
section: "extensions"
sortOrder: 3
---

# Configuration Guide

## Creating a Kitchen Display

1. Navigate to **Tools → Kitchen Display** in the admin panel
2. Click the **Create New** button
3. Fill in the configuration details below
4. Click **Save**

## General Settings

These settings control which orders appear in your kitchen display.

| Setting | Description | Default | Notes |
|---------|-------------|---------|-------|
| **Title** | Name for this configuration | Required | Use descriptive names like "Main Kitchen", "Pizza Station", "Grill Area" |
| **Locations** | Limit to specific locations | All locations | Leave empty to include orders from all locations |
| **Order Status** | Show only these statuses | All statuses | Leave empty to show all statuses (except Rejected) |
| **Order Types** | Filter by order type | All types | Options: Delivery, Collection, or both |
| **Menu Categories** | Show orders with items from these categories | All categories | Leave empty to show all menu categories. Only orders containing items from selected categories will appear |
| **Status** | Enable or disable this display | Enabled | When disabled, the display cannot be accessed |
| **Orders Limit** | Maximum orders to show simultaneously | 20 | Range: 1-100. Higher = more context but potentially overwhelming |
| **Display Orders From** | Start date for order display | Today | Orders from this date onwards will be shown. Useful for multi-day events or carry-over orders |

### General Settings Tips

- **Multi-Location Setup**: Create separate displays for each location with location-specific filters
- **Focused Displays**: Use status and category filters to create specialized displays (e.g., "Preparation Orders Only")
- **Performance**: If system is slow, reduce the Orders Limit
- **Historical Orders**: Adjust "Display From Date" to include orders from previous days if needed

## Board Columns Configuration

The kitchen display has 5 configurable columns. Each can be customized independently.

### Column: New
**Purpose**: Newly received orders awaiting preparation

| Setting | Default | Description |
|---------|---------|-------------|
| **Statuses** | Received | Which statuses appear in this column. Can select multiple statuses |
| **Visible** | On | Toggle this column visibility |

**Example Configurations**:
- Pizza only: `Received`
- Multi-station: `Received`, `Pending`

### Column: Preparing
**Purpose**: Orders currently being prepared

| Setting | Default | Description |
|---------|---------|-------------|
| **Statuses** | Preparation | Which statuses appear in this column |
| **Visible** | On | Toggle this column visibility |

**Example Configurations**:
- Standard kitchen: `Preparation`
- Complex kitchen: `Preparation`, `In Progress`, `Quality Check`

### Column: Ready to Collect
**Purpose**: Orders completed and waiting for customer

| Setting | Default | Description |
|---------|---------|-------------|
| **Statuses** | Delivery | Which statuses appear in this column |
| **Visible** | On | Toggle this column visibility |

**Example Configurations**:
- Pickup focused: `Ready for Pickup`
- Delivery focused: `Delivery`, `Ready for Delivery`
- Mixed: `Ready for Pickup`, `Delivery`

### Column: Completed
**Purpose**: Orders successfully handed off

| Setting | Default | Description |
|---------|---------|-------------|
| **Statuses** | Completed | Which statuses appear in this column |
| **Visible** | On | Toggle this column visibility |

**Notes**:
- Typically only "Completed" status
- Archive these orders regularly to keep display clean

### Column: On Hold
**Purpose**: Orders paused for any reason

| Setting | Default | Description |
|---------|---------|-------------|
| **Status** | Pending | Single status ONLY (different from other columns). This is the status applied when pause button is clicked |
| **Visible** | On | Toggle this column visibility |

**Key Differences**:
- Accepts **ONE status only** (not multiple like other columns)
- Has dedicated pause button separate from status dropdown
- Orders on hold are excluded from "Next" status dropdown in other columns
- Use for temporary holds: waiting for ingredients, customer clarification, customer delivery delay, etc.

#### On Hold Usage Examples

**Standard Setup**:
- Status: `Pending`
- When pause button clicked, order status changes to "Pending"
- Click "Next" to move order to another status

**Custom Setup for Kitchens with "Hold" Status**:
- Status: `On Hold` (if your system has this)
- Dedicated on-hold status instead of repurposing another status

## Hidden Card Fields

Choose which information to hide from order cards. Useful for reducing clutter or protecting privacy.

| Field | Default | Use Case |
|-------|---------|----------|
| **Customer Name** | Visible | Hide in public kitchen displays to protect privacy |
| **Order ID** | Visible | Hide to reduce visual clutter on crowded boards |
| **Order Type** | Visible | Hide in single-type kitchens (e.g., delivery-only) |

### Hidden Fields Tips
- Hiding fields reduces visual noise without losing functionality
- Hidden information is still available in the order details modal
- Useful for customer-facing displays in open kitchens
- Reduces information overload for staff

## Configuration Examples

### Example 1: Main Kitchen (All Orders)
```
Title: Main Kitchen
Locations: Main Location
Order Status: (Leave empty - all statuses)
Order Types: (Leave empty - both)
Menu Categories: (Leave empty - all)
Orders Limit: 25
Display From: Today
Hidden Fields: (None)

Board Columns:
- New: Received (Visible)
- Preparing: Preparation (Visible)
- Ready: Delivery (Visible)
- Completed: Completed (Visible)
- On Hold: Pending (Visible)
```

### Example 2: Pizza Station (Category-Specific)
```
Title: Pizza Station
Locations: Main Location
Order Status: Preparation, Delivery
Order Types: (Leave empty - both)
Menu Categories: Pizza
Orders Limit: 20
Display From: Today
Hidden Fields: Customer Name

Board Columns:
- New: Received (Visible)
- Preparing: Preparation (Visible)
- Ready: Delivery (Visible)
- Completed: (Unchecked - Hidden)
- On Hold: Pending (Visible)
```

### Example 3: Delivery Orders Only
```
Title: Delivery Tracking
Locations: (Leave empty - all)
Order Status: Delivery, In Transit
Order Types: Delivery
Menu Categories: (Leave empty - all)
Orders Limit: 30
Display From: Today
Hidden Fields: Order ID, Order Type

Board Columns:
- New: (Unchecked - Hidden)
- Preparing: (Unchecked - Hidden)
- Ready: Delivery (Visible)
- Completed: Completed (Visible)
- On Hold: Pending (Visible)
```

### Example 4: Dessert Station (Minimal)
```
Title: Dessert Station
Locations: Main Location
Order Status: Preparation
Order Types: (Leave empty - both)
Menu Categories: Desserts
Orders Limit: 15
Display From: Today
Hidden Fields: Customer Name, Order Type

Board Columns:
- New: Received (Visible)
- Preparing: Preparation (Visible)
- Ready: (Unchecked - Hidden)
- Completed: Completed (Visible)
- On Hold: Pending (Visible)
```

## Editing Existing Configurations

1. Navigate to **Tools → Kitchen Display**
2. Click the edit button (pencil icon) next to the display
3. Make your changes
4. Click **Save**

**Note**: Changes take effect immediately for all users viewing the display.

## Deleting a Configuration

1. Navigate to **Tools → Kitchen Display**
2. Click the delete button (trash icon) next to the display
3. Confirm deletion

**Note**: Deleting a configuration doesn't affect existing orders.

## Best Practices

1. **Start Simple**: Begin with all orders, then narrow down with filters
2. **Test Configuration**: Create a test order and verify it displays correctly
3. **Name Clearly**: Use descriptive titles that indicate the display's purpose
4. **Monitor Performance**: Watch for slowness and reduce Orders Limit if needed
5. **Review Regularly**: Periodically review and adjust filters as your workflow changes
6. **Use Backups**: Document your configurations in case you need to recreate them

## Troubleshooting Configuration

**No orders appearing?**
- Check that "Display From Date" is not in the future
- Verify location/status filters match your test order
- Check that menu categories (if filtered) match your test order's items

**Too many orders appearing?**
- Add more filters (status, location, category)
- Reduce Orders Limit to show only most recent orders
- Create separate specialized displays instead of one generic one

**Display looks too cluttered?**
- Hide unnecessary fields (Customer Name, Order ID)
- Hide completed orders column (set to Unchecked)
- Reduce Orders Limit
- Use more specific status/category filters

---

[← Back to Index](./index.md) | [User Guide →](./user-guide.md)
