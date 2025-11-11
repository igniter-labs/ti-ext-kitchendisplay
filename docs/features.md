---
title: "Features"
section: "extensions"
sortOrder: 2
---

# Kitchen Display System Features

## Core Functionality

### Real-time Order Display
View incoming orders instantly with automatic updates via event broadcasting. When a new order arrives or an existing order is updated elsewhere in the system, the kitchen display refreshes automatically without requiring manual intervention.

### Kanban Board Layout
Orders are organized into a customizable Kanban board with up to 5 columns:
- **New** - Newly received orders
- **Preparing** - Orders currently being prepared
- **Ready to Collect** - Orders ready for customer pickup
- **Completed** - Finished orders
- **On Hold** - Orders on pause/waiting

Each column can be individually enabled/disabled and configured with different order statuses.

### Order Status Management
Update order statuses with a single click from dropdown menus. The system immediately updates the order and moves it to the appropriate column on the board. Only relevant statuses appear in the dropdown based on your configuration.

### On-Hold Status
Dedicated pause button for holding orders without cluttering the status options. The on-hold functionality is completely separate from the status progression:
- Click the **Pause** button to hold an order
- Order moves to the "On Hold" column
- The pause button automatically hides when order is on hold
- On-hold orders don't appear in the regular "Next" dropdown options
- Use the "Next" button to move orders out of on-hold status

### Wait Time Management
Multiple ways to track and update order wait times:

**Quick Increment Options**
- Click the order time display
- Select from preset increments: 15, 30, or 45 minutes
- Time updates automatically

**Custom Time Setting**
- Click the order time display
- Select "Custom" option
- Enter desired time in HH:MM format
- Save the new time

### Order Details Modal
Quick access to full order information without leaving the kitchen display:
- Order number
- Customer name (if not hidden)
- Current status
- Order preparation time
- Complete list of items ordered with quantities
- Delivery address (for delivery orders)

Opens by clicking the order number on any card.

## Filtering & Configuration

### Location-Based Filtering
Display orders from specific locations only. Useful for:
- Multi-location restaurants showing location-specific displays
- Dedicated displays for individual kitchen stations within a location

### Status-Based Filtering
Show only orders with selected statuses. Examples:
- Show only "Preparation" and "Delivery" orders (exclude pending/processing)
- Focus on specific order stages relevant to a kitchen station

### Order Type Filtering
Filter by delivery or collection orders. Useful for:
- Separate displays for dine-in vs. delivery
- Kitchen stations handling specific order types

### Menu Category Filtering
Display only orders containing items from specific menu categories. Examples:
- Pizza Station: Shows only orders containing pizza items
- Dessert Station: Shows only orders with desserts
- Gluten-Free Station: Shows orders marked as gluten-free category

### Date Range Display
Choose the date from which orders are displayed. Defaults to today. Examples:
- Show only today's orders
- Include yesterday's uncompleted orders
- Start from a specific date to display orders from a multi-day event

### Order Limit Control
Control how many orders are displayed simultaneously:
- Default: 20 orders
- Range: 1-100 orders
- Set based on kitchen capacity and screen size
- Higher limits show more context but may overwhelm staff

## Customization Options

### Column Visibility
Show or hide specific board columns per configuration. Use to:
- Focus on relevant workflow stages
- Simplify displays for smaller kitchens
- Hide completed orders to reduce clutter

### Column Status Mapping
Assign which order statuses appear in each column. Examples:
- **New Column** → "Received" status only
- **Preparing Column** → "Preparation", "In Progress" statuses
- **Ready Column** → "Delivery", "Ready for Pickup" statuses
- **Completed Column** → "Completed" status
- **On Hold Column** → "Pending" status (single status only)

### Hidden Card Fields
Hide sensitive information from order cards:
- **Customer Name** - Protect customer privacy in public displays
- **Order ID** - Reduce visual information on crowded boards
- **Order Type** - Simplify cards in single-type kitchens

### Responsive Design
Optimized layouts for different screen sizes:
- **Desktop**: Full Kanban board with sidebar
- **Tablet**: Optimized touch interface with collapsible columns
- **Mobile**: Limited functionality for monitoring (not recommended for primary use)

Sidebar can be toggled to maximize board space on any screen.

## User Experience Enhancements

### Scroll Position Preservation
Both vertical and horizontal scroll positions are maintained during automatic refreshes. Benefits:
- No disorientation when orders update
- Maintains focus on specific board area
- Smooth user experience during high-volume order flow

### Auto-Refresh
Orders update automatically when new orders arrive via event broadcasting:
- Real-time visibility of incoming orders
- No manual refresh needed
- Reduces kitchen staff workload

### Manual Refresh
Refresh button allows on-demand order list updates:
- Force immediate refresh without waiting for event broadcast
- Visual feedback (spinning icon) during refresh
- Useful for troubleshooting or catching up on updates

### Dropdown Prevention
Prevents multiple modal backdrops when interacting with dropdowns:
- Cleaner UI without overlapping backdrops
- Better performance with rapid interactions
- Prevents accidental clicks behind backdrops

### Persistent UI State
Sidebar visibility preference saved to browser localStorage:
- User preference persists across visits
- Sidebar state remembered per user
- Desktop only (not applicable on mobile/tablet)

### Expandable Order Items
Order item list can be expanded/collapsed on each card:
- Shows summary by default (item count)
- Click expand to see full item list
- Saves screen space on crowded boards

## Visual Indicators

### Order Counters
Each column displays the count of orders in real-time:
- Color-coded by column (matches column header background)
- Updates immediately when orders move between columns
- At-a-glance overview of kitchen workload

### Order Type Icons
Visual indicators for order type:
- **Truck Icon** → Delivery orders
- **Shopping Bag Icon** → Collection orders

### Status Labels
Current order status displayed on each card:
- Color coded for quick identification
- Updated immediately on status change

### Time Display
Order preparation time displayed prominently:
- Shows current wait time in HH:MM format
- Clickable to increment or set custom time
- Highlights orders exceeding thresholds (future feature)

## Integration Features

### Event Broadcasting Integration
Seamlessly integrates with TastyIgniter's event broadcasting:
- Real-time order updates across multiple displays
- No database polling required
- Scales efficiently with high order volumes

### Permission System
Built-in permission checks ensure:
- Only authorized users can manage displays
- Only authorized users can view displays
- Admin control over access levels

### Order Status Integration
Works with TastyIgniter's order status system:
- Uses built-in order statuses
- Respects status configuration
- Automatically excludes rejected orders

---

[← Back to Index](./index.md) | [Configuration Guide →](./configuration.md)
