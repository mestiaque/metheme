# User Activity Logging System

## Overview
This is a comprehensive user activity logging system that tracks all user authentication activities including login, logout, registration, and password reset operations. It captures detailed information about each activity including device information, browser details, IP addresses, and more.

## Features

- ✅ **Login Activity Tracking** - Logs successful and failed login attempts
- ✅ **Logout Tracking** - Records when users log out
- ✅ **Registration Tracking** - Tracks new user registrations
- ✅ **Password Reset Tracking** - Logs password reset requests and completions
- ✅ **Device Detection** - Identifies device type (phone, tablet, desktop)
- ✅ **Browser Detection** - Captures browser name and version
- ✅ **OS Detection** - Records operating system information
- ✅ **IP Address Tracking** - Logs client IP addresses
- ✅ **Advanced Filtering** - Filter activities by user, type, status, date range, browser, device, IP, etc.
- ✅ **Activity Statistics** - View activity metrics over time periods
- ✅ **CSV Export** - Export activity logs to CSV format
- ✅ **Responsive UI** - Modern admin dashboard for viewing activities

## Installation & Setup

### 1. Run Migration
```bash
php artisan migrate
```

This will create the `user_activities` table with all necessary columns.

### 2. Verify Installation
Check if the migration was successful by verifying the table exists in your database:
```bash
php artisan tinker
>>> Schema::hasTable('user_activities')
```

### 3. (Optional) Install Browser Detection Package
If you don't have `jenssegers/agent` installed:
```bash
composer require jenssegers/agent
```

## Files Created

### Migration
- `src/database/migrations/2025_05_06_000000_create_user_activities_table.php`

### Models
- `src/Models/UserActivity.php` - Activity model with relationships and helper methods
- Updated `src/Models/User.php` - Added `activities()` relationship

### Services
- `src/Services/ActivityLoggerService.php` - Service for logging activities with device/browser detection

### Controllers
- `src/Http/Controllers/ActivityController.php` - Handles activity listing, filtering, statistics, and export

### Views
- `src/resources/views/activity/index.blade.php` - Main activity listing with filters
- `src/resources/views/activity/show.blade.php` - Detailed activity view

### Routes
- `src/routes/web.php` - Added activity routes (already updated)

## Routes

The following routes have been added:

```php
GET     /me/activities              # List all activities
GET     /me/activities/{activity}   # View activity details
GET     /me/activities/statistics   # Get activity statistics (JSON API)
GET     /me/activities/export       # Export activities to CSV
```

## Database Schema

### user_activities table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users table |
| activity_type | enum | login, logout, registration, forgot_password, password_reset, profile_update |
| ip_address | string | Client IP address |
| browser_name | string | Browser name (Chrome, Firefox, Safari, etc.) |
| browser_version | string | Browser version |
| device_name | string | Device model/name |
| device_type | string | phone, tablet, desktop, unknown |
| os_name | string | Operating system name |
| os_version | string | Operating system version |
| user_agent | text | Raw user agent string |
| country | string | Country (if available) |
| city | string | City (if available) |
| latitude | decimal | GPS latitude (if available) |
| longitude | decimal | GPS longitude (if available) |
| status | string | success, failed, pending |
| description | text | Additional details about the activity |
| activity_at | timestamp | When the activity occurred |
| created_at | timestamp | Record creation time |
| updated_at | timestamp | Record update time |

## Activity Types

1. **Login** - User successfully or unsuccessfully logs in
2. **Logout** - User logs out from their account
3. **Registration** - New user creates an account
4. **Forgot Password** - User initiates password reset
5. **Password Reset** - User completes password reset
6. **Profile Update** - User updates their profile (extensible)

## Usage Examples

### Access the Activity Dashboard
Navigate to: `http://your-app.com/me/activities`

### Filter Activities
The dashboard provides multiple filter options:
- **Search User** - Search by name, email, or phone
- **Activity Type** - Filter by activity type
- **Status** - Filter by success/failed/pending
- **Device Type** - Filter by phone/tablet/desktop
- **Date Range** - Filter by date from/to
- **IP Address** - Search by IP address range
- **Browser** - Search by browser name

### Export Activities
Click the "Export CSV" button to download a CSV file of filtered activities.

### View Activity Statistics
Access the statistics API endpoint:
```
GET /me/activities/statistics?days=7
```

Returns JSON with:
- `total_logins` - Successful logins in period
- `total_registrations` - New registrations in period
- `total_password_resets` - Password resets in period
- `failed_logins` - Failed login attempts
- `active_users` - Unique users who logged in

### Programmatic Activity Logging
You can log activities manually in your code:

```php
use ME\Services\ActivityLoggerService;
use Illuminate\Http\Request;

// In your controller
public function someAction(Request $request)
{
    $activityLogger = new ActivityLoggerService($request);
    
    $activityLogger->logActivity(
        userId: Auth::id(),
        activityType: 'profile_update',
        status: 'success',
        description: 'User updated their profile'
    );
    
    // Your code here
}
```

## Activity Type Labels

The system provides internationalized labels for activity types:

```php
$activity->getActivityTypeLabel()
// Returns: "Login", "Logout", "Registration", etc.
```

## Status Colors

Activities are color-coded in the dashboard:
- **Success** - Green badge
- **Failed** - Red badge
- **Pending** - Yellow badge

## Data Privacy & Security

⚠️ **Important Considerations:**
- The system logs IP addresses - ensure compliance with privacy laws (GDPR, CCPA, etc.)
- Store sensitive information securely
- Regularly archive or delete old activity logs if needed
- Implement access control to restrict who can view activities

## Customization

### Add Custom Activity Types
Edit the enum in the migration and add new activity types:
```php
'custom_activity_type' => __('Custom Activity Label')
```

### Add Location Tracking
The schema supports latitude/longitude - integrate a geolocation service:
```php
$geoLocation = geoip('your-ip-address');
$activity->latitude = $geoLocation->lat;
$activity->longitude = $geoLocation->lon;
$activity->country = $geoLocation->country;
$activity->city = $geoLocation->city;
```

### Extend Activity Logging
Create custom event listeners or middleware to automatically log additional activities.

## Troubleshooting

### Activities not being logged
1. Check that `ActivityLoggerService` is properly instantiated
2. Verify the `user_activities` table exists
3. Check Laravel logs for errors

### Browser detection shows unknown
- Ensure `jenssegers/agent` is installed: `composer require jenssegers/agent`
- The package uses user-agent strings, so custom/bots may show as unknown

### Performance Issues
- Add indexes on frequently queried columns
- Archive old activities periodically
- Use pagination (already implemented - 20 per page)

## Future Enhancements

Potential improvements:
- Real-time activity dashboard
- Activity alerts for suspicious login attempts
- Geolocation mapping
- Activity heatmaps
- Mobile app activity sync
- Webhook notifications for critical activities
- Activity archival automation

## Support

For issues or questions about the activity logging system, please check:
1. Laravel logs in `storage/logs/`
2. Database for data consistency
3. Browser console for frontend errors

---

**Created:** May 6, 2026
**Version:** 1.0.0
