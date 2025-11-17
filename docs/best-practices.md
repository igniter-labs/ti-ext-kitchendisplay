---
title: "Best Practices"
section: "extensions"
sortOrder: 7
---

# Best Practices

Recommendations for optimal use of the Kitchen Display System.

## Configuration Best Practices

### 1. Start Simple, Then Specialize

**Initial Setup**
- Create one display with all orders
- No filters (show everything)
- All columns visible
- Orders limit: 20

**After Testing**
- Review workflow
- Identify bottlenecks
- Create specialized displays
- Add filters as needed

### 2. Name Displays Descriptively

**Good Names**
- ✅ "Main Kitchen"
- ✅ "Pizza Station"
- ✅ "Grill Area"
- ✅ "Delivery Tracking"
- ✅ "Dessert Station"

**Bad Names**
- ❌ "Display 1"
- ❌ "KDS"
- ❌ "Orders"
- ❌ "Test"

**Benefits**
- Staff knows which display to use
- Easy to identify in management list
- Clear purpose at a glance

### 3. Set Realistic Orders Limits

| Kitchen Type | Recommended Limit | Reasoning |
|--------------|-------------------|-----------|
| Small (1-2 staff) | 10-15 | Avoid overwhelming |
| Medium (3-5 staff) | 20-30 | Good balance |
| Large (5+ staff) | 30-40 | More context |
| High-volume | 15-20 | Quick turnover |
| Slow-paced | 20-25 | Lower priority |

**Testing Tips**
- Start with 20 (default)
- Monitor performance
- Adjust if staff complains about speed or too much info
- Higher isn't always better

### 4. Map Statuses to Workflow

**Standard Workflow**
```
Received → Preparation → Delivery → Completed
```

**Configuration**
- New: Received
- Preparing: Preparation
- Ready: Delivery
- Completed: Completed
- On Hold: Pending

**Complex Workflow**
```
Received → Validation → Preparation → QA → Delivery → Completed
```

**Configuration**
- New: Received, Validation
- Preparing: Preparation, QA
- Ready: Delivery
- Completed: Completed
- On Hold: Pending

### 5. Use Category Filters for Specialty Stations

**Example 1: Pizza Station**
```
Title: Pizza Station
Locations: Main Location
Order Status: Preparation, Delivery
Menu Categories: Pizza
Orders Limit: 20
```

**Example 2: Dessert Station**
```
Title: Dessert Station
Locations: Main Location
Order Status: Preparation
Menu Categories: Desserts, Pastries
Orders Limit: 15
```

**Example 3: Allergies/Dietary**
```
Title: Gluten-Free Prep
Locations: Main Location
Menu Categories: Gluten-Free
Orders Limit: 10
```

**Benefits**
- Staff focuses only on relevant orders
- Reduces cognitive load
- Faster processing
- Better quality control

### 6. Hide Fields Appropriately

**Public Kitchen (Customer Visible)**
- Hide: Customer Name, Order ID
- Keep: Order Type (indicates delivery/collection)
- Reasoning: Privacy, cleanliness

**Private Kitchen (Staff Only)**
- Hide: Nothing (keep full information)
- Reasoning: Staff needs all details

**Mixed Setup**
- Hide: Customer Name only
- Reasoning: Balance privacy and utility

### 7. Location-Based Separation

**Multi-Location Restaurant**
```
Create displays:
- "Main Location Kitchen"
- "Secondary Location Kitchen"
- "Delivery Tracking (All Locations)"
```

**Benefits**
- Each location sees only their orders
- Reduces noise
- Better accountability
- Easy to scale

### 8. Test Configuration Changes

Before relying on configuration:

1. **Create Test Order**
   - Use admin system to create order
   - Set specific status/location/category
   - Note the order number

2. **Verify Display**
   - Open kitchen display
   - Confirm test order appears
   - Verify it's in correct column

3. **Test Status Update**
   - Click Next button
   - Change status
   - Verify order moves to correct column

4. **Test Other Features**
   - Test pause button
   - Test wait time update
   - Test order details modal

## Operational Best Practices

### 1. Regular Display Monitoring

**Daily**
- Review configuration relevance
- Monitor for stuck orders
- Check for performance issues
- Verify real-time updates working

**Weekly**
- Analyze workflow effectiveness
- Identify bottlenecks
- Review staff feedback
- Update configuration if needed

**Monthly**
- Comprehensive review
- Performance analysis
- Consider new displays/filters
- Update documentation

### 2. Order Management Workflow

**New Orders**
- Scan display for new orders
- Identify priorities
- Start preparation

**During Preparation**
- Update status as needed
- Use pause for issues
- Monitor wait times

**Completion**
- Update to Ready status
- Confirm customer notification
- Monitor for pickup

**Follow-up**
- Remove from display (move to Completed)
- Address any issues
- Gather feedback

### 3. Communication Protocol

**Within Team**
- One person per order (avoid conflicts)
- Use pause button to signal issues
- Verbally communicate delays
- Alert manager for problems

**With Other Departments**
- Share display link with managers
- Provide real-time updates via display
- Escalate issues quickly
- Keep records of problems

### 4. Issue Handling

**Stuck Orders**
1. Click refresh to sync
2. Check order status in details
3. Move to correct status
4. If still stuck, contact administrator

**Missing Orders**
1. Check location/status filters
2. Verify order exists in system
3. Refresh display
4. Search in main orders section

**Slow Display**
1. Reduce Orders Limit
2. Hide unnecessary columns
3. Close other browser tabs
4. Report to IT if persists

### 5. Shift Handoff

**Outgoing Shift**
- Update all order statuses
- Clear out completed orders (archive)
- Document any issues
- Brief incoming team

**Incoming Shift**
- Review on-hold orders
- Understand any issues
- Set performance targets
- Ask questions

## Performance Best Practices

### 1. Database Performance

**For Administrators**

- Monitor query performance
- Ensure database indexes exist
- Regularly backup database
- Archive old completed orders:
  ```sql
  DELETE FROM orders WHERE status_id = 'completed' AND created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);
  ```

### 2. Server Performance

**Keep Queue Workers Running**
```bash
# Check status
php artisan queue:failed

# Restart if needed
php artisan queue:restart
```

**Monitor Resources**
- CPU usage
- Memory usage
- Disk space
- Network bandwidth

**Optimize for High Volume**
- Use Redis instead of database for caching
- Enable query result caching
- Monitor database slow queries

### 3. Browser Performance

**Staff Tips**
- Close unnecessary tabs
- Use modern browser (Chrome/Firefox preferred)
- Clear cache monthly
- Don't run heavy applications alongside

**Administrator Setup**
- Use hardware-accelerated browsers
- Enable JavaScript caching
- Optimize network latency
- Consider dedicated display hardware

### 4. Network Performance

**Setup**
- Use wired connection when possible
- Position Wi-Fi router near kitchen
- Reduce interference
- Monitor bandwidth usage

**Testing**
- Use speedtest.net to verify
- Monitor latency
- Check for packet loss
- Use dedicated network if available

## Security Best Practices

### 1. Permission Management

**Role-Based Access**
- Kitchen Staff: View only
- Shift Managers: View + Update
- Kitchen Manager: Full access
- IT: Configuration access

**Implementation**
- Use TastyIgniter roles
- Assign `KitchenDisplay.Manage` only to trusted users
- Review permissions regularly
- Audit access logs

### 2. Data Protection

**Sensitive Information**
- Hide customer names in public kitchens
- Restrict kitchen display to kitchen only
- Don't share display credentials
- Use strong passwords

**Access Control**
- Limit display access to authorized users
- Use VPN for remote access
- Monitor failed login attempts
- Disable display when not in use

### 3. System Updates

**Keep Updated**
- Update TastyIgniter regularly
- Update all extensions
- Apply security patches immediately
- Test updates before production

## Disaster Recovery

### 1. Data Backup

**Regular Backups**
- Daily: Complete database backup
- Weekly: Complete file system backup
- Monthly: Off-site backup
- Test restore procedures quarterly

### 2. Business Continuity

**If Kitchen Display Fails**
1. Continue with manual order tracking
2. Restore from backup if needed
3. Restart services:
   ```bash
   php artisan queue:restart
   php artisan cache:clear
   ```
4. Verify all data intact
5. Resume operations

### 3. Documentation

**Keep Records Of**
- Display configurations
- Staff procedures
- Escalation contacts
- Backup locations
- Disaster recovery procedures

---

## Scenario-Based Examples

### Scenario 1: Restaurant Opening Day

1. **Setup** (1 day before)
   - Create "Main Kitchen" display
   - Configure for expected order types
   - Set orders limit to 20
   - Test with sample orders

2. **Opening Day**
   - Have backup person monitoring display
   - Start conservative with filters
   - Increase orders limit if needed
   - Document issues

3. **After Opening**
   - Gather staff feedback
   - Fine-tune configuration
   - Create specialized displays if needed

### Scenario 2: Holiday/Special Event

1. **Preparation**
   - Increase orders limit (25-30)
   - Create focused displays by station
   - Test with sample orders
   - Brief all staff

2. **Event Day**
   - Have display visible at all times
   - Designate person to monitor
   - Use pause button proactively
   - Communicate delays

3. **After Event**
   - Archive orders
   - Gather metrics
   - Document lessons learned
   - Reset normal configuration

### Scenario 3: New Kitchen Staff Member

1. **Onboarding**
   - Show how to access display
   - Explain column meanings
   - Demonstrate status updates
   - Practice with test orders

2. **Supervised Shift**
   - Monitor their usage
   - Answer questions
   - Correct mistakes
   - Build confidence

3. **Independent Shift**
   - Let them manage display
   - Be available for help
   - Provide feedback
   - Document training completion

---

## Continuous Improvement

### 1. Metrics to Track

- Average order processing time
- Orders completed per shift
- Peak order times
- Common bottlenecks
- Staff satisfaction

### 2. Regular Reviews

**Monthly Review**
- Analyze performance metrics
- Survey staff feedback
- Identify improvements
- Plan changes

**Quarterly Review**
- Comprehensive assessment
- Technology updates
- Process optimization
- Training needs

### 3. Feedback Collection

**From Kitchen Staff**
- What works well
- What's frustrating
- Feature suggestions
- Performance issues

**From Management**
- Order accuracy
- Customer satisfaction
- Cost efficiency
- Growth metrics

---

## Conclusion

The Kitchen Display System is a powerful tool that can significantly improve kitchen efficiency and customer satisfaction. Success comes from:

1. **Proper Configuration** - Tailored to your specific workflow
2. **Operator Training** - Staff understanding and proficiency
3. **Regular Maintenance** - Monitoring and optimization
4. **Continuous Improvement** - Adapting to changing needs

By following these best practices, you'll maximize the value of the system and create a more efficient kitchen operation.

---

[← Back to Index](./index.md) | [Troubleshooting Guide](./troubleshooting.md)
