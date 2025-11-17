---
title: "User Guide"
section: "extensions"
sortOrder: 4
---

# Kitchen Display System User Guide

This guide is for kitchen staff using the kitchen display to manage orders.

## Accessing the Kitchen Display

1. Log in to your TastyIgniter admin account
2. Navigate to **Tools → Kitchen Display**
3. Click on the display you want to use
4. The kitchen display opens in full-screen mode

## The Kitchen Display Interface

### Top Navigation Bar
- **Back Button** (←): Return to kitchen display list
- **Display Title**: Shows the name of the current display (e.g., "Main Kitchen")
- **Refresh Button** (↻): Manually refresh the order list

### Board Layout
The main area shows a Kanban board with up to 5 columns:

```
┌─────────────┬──────────────┬──────────────┬───────────────┬──────────┐
│   New       │  Preparing   │    Ready     │   Completed   │ On Hold  │
│    (5)      │     (8)      │     (3)      │      (2)      │   (1)    │
├─────────────┼──────────────┼──────────────┼───────────────┼──────────┤
│ [Order 101] │ [Order 104]  │ [Order 107]  │ [Order 110]   │[Order111]│
│ [Order 102] │ [Order 105]  │ [Order 108]  │ [Order 112]   │          │
│ [Order 103] │ [Order 106]  │              │               │          │
│             │ [Order 109]  │              │               │          │
└─────────────┴──────────────┴──────────────┴───────────────┴──────────┘
```

## Understanding Order Cards

Each order appears as a card with the following information:

```
┌──────────────────────────────┐
│        [Order Header]        │  ← Click for full details
├──────────────────────────────┤
│ Order: #12345                │
│ Customer: John Smith         │ ← May be hidden
│ 🚗 Delivery                  │ ← May be hidden
├──────────────────────────────┤
│ 2x Pepperoni Pizza           │
│ 1x Caesar Salad              │ ← Click [+] to expand/collapse
│ 1x Coke                      │
├──────────────────────────────┤
│ Time: 12:45     [Increment]  │ ← Click to change
├──────────────────────────────┤
│  [⏸ Pause]  [Next ▼]        │  ← Action buttons
└──────────────────────────────┘
```

### Card Elements Explained

**Order Header**
- Click to view complete order details in a modal
- Shows order number and timestamp

**Customer Information** (May be hidden)
- Customer name
- Helps identify the order

**Order Type** (May be hidden)
- 🚗 Truck icon = Delivery order
- 🛍️ Shopping bag icon = Collection order

**Order Items**
- List of items ordered with quantities
- Click [+] button to expand/collapse the full list
- Default shows collapsed summary with item count

**Order Time**
- Shows current wait time in HH:MM format
- Click to increment or set custom time
- Blue background indicates editable field

**Action Buttons**
- **Pause Button** [⏸]: Pause/hold the order
- **Next Button** [Next ▼]: Progress to next status

## Managing Orders

### Updating Order Status

**Via Dropdown Menu**:
1. Locate the order card
2. Click the **Next** button
3. A dropdown menu appears with available status options
4. Click on the desired status
5. Order immediately moves to the corresponding column
6. Status updates are saved automatically

**Note**: The on-hold status doesn't appear in the dropdown if already available via pause button.

### Pausing an Order (On Hold)

**When to use**:
- Waiting for ingredients
- Customer requesting delay
- Quality issue requiring clarification
- Any other temporary pause needed

**How to pause**:
1. Locate the order card
2. Click the **Pause Button** [⏸]
3. Order immediately moves to the "On Hold" column
4. Pause button disappears (order is now on hold)

**How to resume**:
1. Locate the paused order in the "On Hold" column
2. Click the **Next** button
3. Select the appropriate status to resume preparation
4. Order moves back to the active workflow

**Note**: The pause button only appears when the order is NOT on hold.

### Managing Wait Times

#### Quick Increment (Preset Times)

1. Click on the **Order Time** display (blue time field)
2. A menu appears with preset options:
   - [+15 mins]
   - [+30 mins]
   - [+45 mins]
3. Click the desired increment
4. Time updates immediately

**Example**: If time shows 12:45 and you click [+15 mins], it becomes 13:00.

#### Custom Time Setting

1. Click on the **Order Time** display
2. Select "Set Custom Time" option
3. A modal appears with time input field
4. Enter the desired time in **HH:MM** format (e.g., 13:30)
5. Click **Save**
6. Time updates immediately

**Note**: Use 24-hour format (e.g., 14:30 for 2:30 PM, not 02:30 PM).

### Viewing Order Details

**Quick View**:
1. Click on the **Order Number** at the top of any card
2. Order Details modal opens
3. Shows complete information including:
   - Full order number
   - Customer name (if not hidden)
   - Current status
   - Current wait time
   - Complete item list with quantities
   - Delivery address (for delivery orders)
4. Click outside the modal or close button to dismiss

**Information Available in Detail View**:
- Order number
- Customer name
- Delivery address (for delivery orders)
- Current order status
- Order time
- Complete list of all items
- Item quantities
- Special notes or instructions

## Managing the Display

### Manual Refresh

1. Click the **Refresh Button** (↻) in the top right
2. The button icon spins while refreshing
3. Display updates with latest orders
4. Your scroll position is preserved

**When to use**:
- If orders aren't updating automatically
- To catch up on rapid order flow
- After making configuration changes

### Auto-Refresh

The kitchen display automatically refreshes when:
- New orders arrive in the system
- Orders are updated elsewhere in the system
- No manual action needed

This happens in the background without interrupting your work.

## Tips for Efficient Use

### Organization
1. **Process top to bottom**: Process orders in oldest-to-newest order (top to bottom in each column)
2. **Batch similar items**: When possible, prepare similar items together across different orders
3. **Watch time counters**: Pay attention to orders with high wait times

### Speed
1. **Use quick increments** for wait times instead of custom times when possible
2. **Click Next button directly** when you know the next status
3. **Use pause sparingly** - pause only when truly needed, not for brief delays

### Accuracy
1. **Verify order details** by clicking order number before starting
2. **Double-check items** in the expandable item list
3. **Confirm special instructions** by viewing full details

### Teamwork
1. **Clear communication**: Use on-hold button to signal issues to team
2. **One action per order**: Only one person should update an order status
3. **Regular refreshes**: If orders seem out of sync, click refresh

## Troubleshooting While Working

### Order is in Wrong Column
- The order status in the system may have changed elsewhere
- Click the **Refresh** button to sync the display
- If still wrong, check the order status in the full details modal

### Time Not Updating
- Ensure you clicked **Save** in the custom time modal
- Time should update immediately after save
- If not, click refresh

### Can't Find an Order
- Order may have been completed and moved to Completed column
- Use Refresh button to ensure latest data
- Check if order filters (location, status) might be hiding it

### Order Details Modal Won't Open
- Click on the order number again
- Try refreshing the page (Ctrl+R or Cmd+R)
- If still stuck, close and reopen the kitchen display

### Display Not Updating Automatically
- Check that your internet connection is stable
- Wait a few seconds - updates may be delayed
- Click Refresh button to force manual update
- Contact IT if persistent

## Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `Ctrl+R` or `Cmd+R` | Refresh the display |
| `Esc` | Close any open modal |
| `Tab` | Navigate between elements |

## Performance Tips

1. **Don't scroll excessively**: Minimize scrolling to reduce strain
2. **Take breaks**: Monitor orders during breaks, don't stare continuously
3. **Adjust brightness**: Dim display if it causes eye strain in bright kitchens
4. **Keep display clean**: Hide unnecessary fields to reduce visual clutter

## Getting Help

If you encounter issues:

1. **Check [Troubleshooting Guide](./troubleshooting.md)** for common solutions
2. **Contact your administrator** for permission or configuration issues
3. **Report bugs** to your IT department with details about what happened

---

[← Back to Index](./index.md) | [Technical Reference →](./technical-reference.md)
