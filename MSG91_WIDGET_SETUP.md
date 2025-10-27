# MSG91 OTP Widget Integration Guide

## Overview

This document provides setup and integration instructions for the MSG91 OTP Widget in the UN Brahma College Election Application.

## Files Modified

-   `app/Http/Controllers/VoteController.php` - Updated with MSG91 token verification
-   `resources/views/candidates/show.blade.php` - Integrated MSG91 OTP Widget frontend
-   `config/services.php` - Added MSG91 service configuration
-   `.env` - Added MSG91 environment variables

---

## Setup Instructions

### 1. Environment Variables Configuration

Add the following environment variables to your `.env` file:

```env
MSG91_WIDGET_ID=your_widget_id_here
MSG91_AUTH_KEY=your_auth_key_here
MSG91_ACCESS_TOKEN=your_access_token_here
```

**Where to find these values:**

-   **MSG91_WIDGET_ID**: Go to MSG91 Dashboard → OTP Widget → Widget Settings → Copy the Widget ID
-   **MSG91_AUTH_KEY**: Found in MSG91 Dashboard → Settings → API Key (AuthKey)
-   **MSG91_ACCESS_TOKEN**: Generated from MSG91 Dashboard → OTP Widget → Authentication

### 2. Server-Side Configuration

The following configuration has been added to `config/services.php`:

```php
'msg91' => [
    'widget_id' => env('MSG91_WIDGET_ID'),
    'auth_key' => env('MSG91_AUTH_KEY'),
    'access_token' => env('MSG91_ACCESS_TOKEN'),
],
```

### 3. API Endpoints

Two main API endpoints handle OTP operations:

#### **POST /vote/send-otp**

Sends OTP to the voter's mobile number.

**Request:**

```json
{
    "mobile": "9876543210",
    "candidate_id": 1
}
```

**Response (Success):**

```json
{
    "success": true,
    "message": "OTP sent successfully to your mobile number.",
    "widgetConfig": {
        "widgetId": "356a41657456333234313434",
        "identifier": "919876543210",
        "exposeMethods": true
    }
}
```

#### **POST /vote/verify-otp**

Verifies the OTP and records the vote.

**Request:**

```json
{
    "mobile": "919876543210",
    "token": "jwt_token_from_msg91_widget",
    "candidate_id": 1
}
```

**Response (Success):**

```json
{
    "success": true,
    "message": "Your vote has been recorded successfully!",
    "redirect": "http://localhost/results"
}
```

---

## Frontend Implementation

### Client-Side Methods Exposed by MSG91 Widget

The following methods are exposed on the `window` object:

#### **1. window.sendOtp(identifier, successCallback, failureCallback)**

Sends OTP to the provided mobile number or email.

```javascript
window.sendOtp(
    "919876543210", // Mobile with country code
    (data) => console.log("OTP sent:", data),
    (error) => console.error("Error:", error)
);
```

#### **2. window.verifyOtp(otp, successCallback, failureCallback, reqId)**

Verifies the entered OTP.

```javascript
window.verifyOtp(
    "123456", // OTP entered by user
    (data) => {
        console.log("Verification token:", data.token);
    },
    (error) => console.error("Verification failed:", error)
);
```

#### **3. window.retryOtp(channel, successCallback, failureCallback, reqId)**

Resends OTP through specified channel.

```javascript
window.retryOtp(
    null, // null for default channel, or '11' for SMS, '4' for Voice, '3' for Email
    (data) => console.log("OTP resent:", data),
    (error) => console.error("Retry failed:", error)
);
```

#### **4. window.getWidgetData()**

Returns current widget configuration.

```javascript
const widgetData = window.getWidgetData();
console.log("Widget Data:", widgetData);
```

### Voting Flow

1. **User enters mobile number** → Validates 10-digit format
2. **Click "Send OTP"** → Calls `/vote/send-otp` endpoint
3. **MSG91 Widget initializes** → Shows OTP input field
4. **User receives OTP** → Via SMS to their phone
5. **User enters OTP** → Input field accepts 6 digits
6. **Click "Verify & Vote"** → Calls `window.verifyOtp()` method
7. **Backend verifies token** → Calls MSG91 verification API
8. **Vote recorded** → If token is valid, vote is saved
9. **Redirect to results** → User sees live election results

---

## Key Features

### ✅ Security

-   **Token-based verification**: OTP verification handled through secure MSG91 API
-   **Double validation**: Mobile number checked for existing votes before processing
-   **Transaction safety**: Vote creation wrapped in database transaction

### ✅ User Experience

-   **Real-time validation**: Mobile number format checked before sending OTP
-   **Retry mechanism**: Users can resend OTP if not received
-   **Clear feedback**: Success/error messages displayed with Font Awesome icons
-   **Mobile-friendly**: Responsive design works on all devices

### ✅ Reliability

-   **Fallback OTP storage**: Local OTP records kept for reference
-   **Configurable channels**: Support for SMS, Voice, Email, WhatsApp
-   **Error handling**: Comprehensive try-catch blocks with user-friendly messages

---

## Testing the Integration

### 1. Local Development Setup

```bash
# Clear configuration cache
php artisan config:clear

# Add test credentials to .env
MSG91_WIDGET_ID=your_test_widget_id
MSG91_AUTH_KEY=your_test_auth_key
MSG91_ACCESS_TOKEN=your_test_access_token

# Run development server
npm run dev   # Frontend assets
php artisan serve  # Backend server
```

### 2. Testing Steps

1. Navigate to candidate page: `/candidates/{id}`
2. Enter a test mobile number (10 digits)
3. Click "Send OTP"
4. Verify MSG91 widget appears
5. Enter OTP received in SMS/test console
6. Click "Verify & Vote"
7. Confirm vote recorded on results page

### 3. Test Cases

| Test Case      | Steps                                      | Expected Result                    |
| -------------- | ------------------------------------------ | ---------------------------------- |
| Valid vote     | Enter 10-digit mobile, receive OTP, verify | Vote recorded, redirect to results |
| Duplicate vote | Try voting with same mobile twice          | Error: "You have already voted"    |
| Invalid OTP    | Enter wrong 6-digit code                   | Error message from MSG91           |
| Invalid mobile | Submit less than 10 digits                 | Error: "Please enter valid mobile" |
| Expired OTP    | Wait 10+ minutes after sending             | Error: "Invalid or expired OTP"    |

---

## Troubleshooting

### Issue: "MSG91 widget not loaded"

**Solution**:

-   Verify MSG91 widget script is loading: Check browser console
-   Ensure `MSG91_WIDGET_ID` is set correctly in .env
-   Check network tab for script load errors

### Issue: "Token verification failed"

**Solution**:

-   Verify `MSG91_AUTH_KEY` and `MSG91_ACCESS_TOKEN` are correct
-   Check MSG91 API key hasn't expired
-   Ensure backend can reach `https://control.msg91.com`

### Issue: OTP not received

**Solution**:

-   Check mobile number format (must be 10 digits without country code)
-   Verify MSG91 account has sufficient balance
-   Check spam folder for SMS
-   Try resend using "Retry OTP" button

### Issue: Vote not recorded after verification

**Solution**:

-   Check Laravel logs: `storage/logs/laravel.log`
-   Verify database connection working
-   Ensure `votes` table has proper schema
-   Check if mobile number already has a vote

---

## API Response Codes

### Success (200)

```json
{
    "success": true,
    "message": "Operation successful"
}
```

### Already Voted (400)

```json
{
    "success": false,
    "message": "You have already voted."
}
```

### Invalid Request (422)

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "mobile": ["Mobile must be 10 digits"]
    }
}
```

### Token Verification Failed (400)

```json
{
    "success": false,
    "message": "Token verification failed with MSG91."
}
```

---

## Security Considerations

1. **Never expose credentials**: Keep MSG91 keys in `.env`, never commit to git
2. **HTTPS only**: Ensure production uses HTTPS for all OTP operations
3. **Rate limiting**: Consider adding rate limiting to prevent brute force
4. **Input validation**: All inputs validated on both client and server
5. **CSRF protection**: All form submissions include CSRF token

---

## Production Deployment

### Before Going Live:

1. ✅ Set production MSG91 credentials in environment
2. ✅ Enable HTTPS for all OTP endpoints
3. ✅ Set `APP_DEBUG=false` in production
4. ✅ Configure proper error logging
5. ✅ Test OTP flow with real phone numbers
6. ✅ Set up monitoring for API failures
7. ✅ Configure backup SMS service (optional)

### Environment Variables for Production:

```env
APP_ENV=production
APP_DEBUG=false
MSG91_WIDGET_ID=prod_widget_id
MSG91_AUTH_KEY=prod_auth_key
MSG91_ACCESS_TOKEN=prod_access_token
```

---

## Support & Documentation

-   **MSG91 Documentation**: https://msg91.com/docs/
-   **OTP Widget Docs**: https://msg91.com/docs/guide/otp-provider/
-   **Laravel Documentation**: https://laravel.com/docs/

---

## Version History

| Date         | Version | Changes                          |
| ------------ | ------- | -------------------------------- |
| Oct 27, 2025 | 1.0     | Initial MSG91 Widget integration |
