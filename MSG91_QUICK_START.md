# MSG91 OTP Widget Integration - Quick Start Guide

## 🚀 Quick Setup (5 minutes)

### Step 1: Get Your MSG91 Credentials

1. Go to [MSG91 Dashboard](https://control.msg91.com)
2. Create an account if you don't have one
3. Create an OTP Widget:
    - Navigate to **OTP → Widget**
    - Click **Create Widget**
    - Configure settings (SMS channel recommended)
    - Copy the **Widget ID**
4. Get your credentials:
    - **Auth Key**: Settings → API Keys
    - **Access Token**: OTP → Widget → Authentication section

### Step 2: Configure Environment Variables

Update your `.env` file:

```env
MSG91_WIDGET_ID=356a41657456333234313434
MSG91_AUTH_KEY=345269AqHLkx1yux68ff0247P1
MSG91_ACCESS_TOKEN=345269T5KFK6vYGSOU68ff016aP1
```

### Step 3: Test the Integration

1. Start development server:

    ```bash
    npm run dev      # Terminal 1 - Frontend
    php artisan serve # Terminal 2 - Backend
    ```

2. Open voting page:

    ```
    http://localhost:8000/candidates/1
    ```

3. Test the flow:
    - Enter a mobile number
    - Click "Send OTP"
    - Receive OTP via SMS
    - Enter OTP and verify
    - Vote recorded ✅

---

## 📱 Integration Points

### Frontend (`resources/views/candidates/show.blade.php`)

The voting form includes:

-   Mobile number input with validation
-   MSG91 widget container
-   OTP input field
-   Verify, Retry, and Change Number buttons

Key JavaScript functions:

```javascript
initiateMSG91OTP(); // Send OTP
verifyMSG91OTP(); // Verify OTP
retryMSG91OTP(); // Resend OTP
resetMSG91Form(); // Reset form
```

### Backend (`app/Http/Controllers/VoteController.php`)

Two API endpoints:

```php
POST /vote/send-otp        // Initiate OTP sending
POST /vote/verify-otp      // Verify OTP and record vote
```

---

## 🔑 Environment Variables

| Variable           | Description            | Example                        |
| ------------------ | ---------------------- | ------------------------------ |
| MSG91_WIDGET_ID    | Your widget identifier | `356a41657456333234313434`     |
| MSG91_AUTH_KEY     | API authentication key | `345269AqHLkx1yux68ff0247P1`   |
| MSG91_ACCESS_TOKEN | JWT token for widget   | `345269T5KFK6vYGSOU68ff016aP1` |

---

## 🧪 Testing Checklist

-   [ ] Mobile number validation works
-   [ ] OTP sends successfully
-   [ ] User receives OTP via SMS
-   [ ] OTP verification works
-   [ ] Vote records successfully
-   [ ] Duplicate vote prevention works
-   [ ] Error messages display correctly
-   [ ] Redirect to results page works

---

## 🐛 Troubleshooting

### Widget not showing?

```bash
# Clear config cache
php artisan config:clear

# Check .env has correct values
grep MSG91 .env
```

### OTP not received?

-   Verify mobile number is 10 digits
-   Check MSG91 account balance
-   Check spam folder for SMS

### Token verification fails?

-   Verify AUTH_KEY is correct
-   Check ACCESS_TOKEN hasn't expired
-   Ensure backend has internet access

---

## 📊 Database Schema

The system uses these tables:

### `votes` table

```
- id (primary key)
- candidate_id (foreign key)
- voter_mobile (string, unique)
- verified (boolean)
- created_at, updated_at
```

### `otp_verifications` table

```
- id (primary key)
- mobile (string, unique)
- otp (string)
- expires_at (timestamp)
- verified (boolean)
```

---

## 🔒 Security Features

✅ **Token-based verification** - Secure MSG91 API validation  
✅ **Duplicate vote prevention** - Mobile number indexed and checked  
✅ **CSRF protection** - All endpoints protected  
✅ **Input validation** - Client and server-side validation  
✅ **Transaction safety** - Database transactions for vote creation

---

## 📞 Support

| Issue              | Solution                          |
| ------------------ | --------------------------------- |
| Widget not loading | Check Widget ID in config         |
| OTP timeout        | Increase expiry in code (line 43) |
| High SMS cost      | Consider reducing test sends      |
| Rate limiting      | Check MSG91 account limits        |

---

## 🎯 What's Implemented

✅ Client-side MSG91 OTP Widget integration  
✅ Server-side token verification  
✅ Vote recording with OTP validation  
✅ Duplicate vote prevention  
✅ Real-time error handling  
✅ Responsive mobile UI  
✅ Admin dashboard updates

---

## 📦 Files Modified

1. `app/Http/Controllers/VoteController.php` - Backend integration
2. `resources/views/candidates/show.blade.php` - Frontend widget
3. `config/services.php` - Service configuration
4. `.env` - Environment variables

---

## 🚢 Production Deployment

Before going live:

1. Update `.env` with production credentials
2. Enable HTTPS on all endpoints
3. Set `APP_DEBUG=false`
4. Test with real phone numbers
5. Monitor API error logs
6. Set up SMS balance alerts

---

**Created**: October 27, 2025  
**Version**: 1.0  
**Status**: ✅ Ready for Testing
