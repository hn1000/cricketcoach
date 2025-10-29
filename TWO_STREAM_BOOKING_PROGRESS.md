# Two-Stream Booking System - Implementation Progress

## ✅ Completed (Phase 1 - Database & Models)

### 1. Database Migrations
- ✅ **Migration**: `add_booking_system_on_to_companies_table`
  - Added `booking_system_on` BOOLEAN field (default: false)
  - Ran successfully

- ✅ **Migration**: `create_enquiry_messages_table`
  - Full table with all fields:
    - company_id, staff_id, user_id (foreign keys)
    - message (text)
    - preferred_date, preferred_time, preferred_time_slot
    - status (new/read/replied/archived)
    - read_at, replied_at timestamps
    - Proper indexes for performance
  - Ran successfully

### 2. Models Created/Updated
- ✅ **EnquiryMessage Model** - Full implementation with:
  - All fillable fields
  - Casts for dates/datetimes
  - Relationships: company(), staff(), user()
  - Methods: markAsRead(), markAsReplied()
  - Scopes: new(), forCompany(), forStaff()

- ✅ **Company Model** - Updated with:
  - Added `booking_system_on` to fillable and casts
  - Added `enquiryMessages()` relationship
  - Added `usesOnlineBooking()` helper method

- ✅ **Staff Model** - Updated with:
  - Added `enquiryMessages()` relationship

## 🚧 Next Steps (Remaining Work - ~4-5 hours)

### Phase 2: Controllers (1.5 hours)
1. **Public EnquiryMessageController** - Handle customer-facing enquiries
   - `create()` - Show enquiry form (check booking_system_on first)
   - `store()` - Save enquiry, send email, require auth
   - `success()` - Thank you page

2. **Admin EnquiryMessageController** - Company management
   - `index()` - List all enquiries for company
   - `show()` - View single enquiry detail
   - `updateStatus()` - Mark as read/replied/archived

### Phase 3: Frontend Pages (2 hours)
1. **Enquiry/Create.vue** - Customer enquiry form
   - Login check (redirect if not authenticated)
   - Coach info sidebar
   - Message textarea (1000 char limit with counter)
   - Optional date/time preferences
   - Submit button

2. **Enquiry/Success.vue** - Thank you page
   - Success message
   - Enquiry summary
   - What happens next
   - Response time estimate

3. **Admin/Enquiries/Index.vue** - Dashboard for companies
   - Table of all enquiries
   - Filters (status, company, date)
   - Status badges
   - Quick actions

4. **Admin/Enquiries/Show.vue** - Single enquiry detail
   - Full customer info
   - Full message
   - Status management
   - Reply via email link
   - Archive button

### Phase 4: Email Notification (30 min)
1. **EnquiryReceived Mail** - Notifies coach
   - Customer details
   - Message content
   - Preferred timing
   - Link to view in admin panel

2. **Email Template** - Markdown view
   - Professional formatting
   - Clear action items

### Phase 5: Update Existing Pages (30 min)
1. **Companies/Show.vue** - Conditional button logic
   ```javascript
   if (company.booking_system_on) {
       // "Book Online" → booking.create route
   } else {
       // "Send Enquiry" → enquiry.create route
   }
   ```
   - Add badges (Instant Booking / Contact for Availability)
   - Update button colors/icons

### Phase 6: Routes (10 min)
1. **Public routes** (`routes/public.php`)
   - GET `/enquiry/companies/{company}/staff/{staff}` → create
   - POST `/enquiry/companies/{company}/staff/{staff}` → store (auth required)
   - GET `/enquiry/success/{enquiry}` → success (auth required)

2. **Admin routes** (`routes/admin.php`)
   - GET `/admin/enquiries` → index
   - GET `/admin/enquiries/{enquiry}` → show
   - PATCH `/admin/enquiries/{enquiry}/status` → updateStatus

### Phase 7: Testing (30 min)
1. Test enquiry submission flow
2. Test email notifications
3. Test admin dashboard
4. Test booking_system_on toggle
5. Test both streams (enquiry vs online booking)

## How It Works

### Stream 1: Enquiry Mode (DEFAULT - booking_system_on = false)
1. Customer browses companies → clicks coach
2. Sees "Send Enquiry" button
3. Must login/register
4. Fills enquiry form (message + optional date/time)
5. Submits → stored in `enquiry_messages` table
6. Coach receives email notification
7. User sees success page
8. Company admin views in enquiries dashboard
9. Coach contacts customer directly
10. Manual arrangement/booking

### Stream 2: Online Booking (booking_system_on = true)
1. Customer browses companies → clicks coach
2. Sees "Book Online" button with badge
3. Redirects to existing booking flow
4. Calendar → select slot → payment
5. Instant confirmation
6. Booking stored in `bookings` table

## Files Created

### Database
1. `database/migrations/2025_10_29_211532_add_booking_system_on_to_companies_table.php`
2. `database/migrations/2025_10_29_211550_create_enquiry_messages_table.php`

### Models
3. `app/Models/EnquiryMessage.php`

### Models Updated
4. `app/Models/Company.php` - Added booking_system_on support
5. `app/Models/Staff.php` - Added enquiryMessages relationship

## Files To Create (Remaining)

### Controllers
6. `app/Http/Controllers/Sites/Public/EnquiryMessageController.php`
7. `app/Http/Controllers/Sites/Admin/EnquiryMessageController.php`

### Frontend Pages
8. `resources/js/Pages/Sites/Public/Enquiry/Create.vue`
9. `resources/js/Pages/Sites/Public/Enquiry/Success.vue`
10. `resources/js/Pages/Sites/Admin/Enquiries/Index.vue`
11. `resources/js/Pages/Sites/Admin/Enquiries/Show.vue`

### Email
12. `app/Mail/EnquiryReceived.php`
13. `resources/views/emails/enquiry-received.blade.php`

### Routes
14. Update `routes/public.php`
15. Update `routes/admin.php`

### Updates
16. Update `resources/js/Pages/Sites/Public/Companies/Show.vue`

## Quick Commands for Next Session

```bash
# Create controllers
php artisan make:controller Sites/Public/EnquiryMessageController
php artisan make:controller Sites/Admin/EnquiryMessageController

# Create mail
php artisan make:mail EnquiryReceived --markdown=emails.enquiry-received

# Run build
npm run build

# Test
php artisan tinker
# > \App\Models\Company::first()->update(['booking_system_on' => false]);
# > \App\Models\EnquiryMessage::create([...test data...]);
```

## Current Database State

- ✅ `companies` table has `booking_system_on` field (all default to `false`)
- ✅ `enquiry_messages` table created and ready
- ✅ All relationships configured

## Notes

- **Default behavior**: All companies start in enquiry mode (safer, easier)
- **Upgrade path**: Companies can enable online booking when ready
- **Both systems coexist**: No conflicts, clean separation
- **Authentication required**: Customers must be logged in to send enquiries
- **Email notifications**: Coaches notified immediately on new enquiries
- **Company-level control**: One toggle affects all coaches in that company

---

**Current Status**: Database & Models Complete (Phase 1 Done)
**Next Task**: Create Controllers (Phase 2)
**Estimated Time Remaining**: 4-5 hours
