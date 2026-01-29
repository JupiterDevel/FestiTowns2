# Legal Compliance Feature Documentation

## Overview

This document describes the legal compliance feature implemented in FestiTowns, which includes mandatory acceptance of Terms & Conditions and Cookies Policy for all users.

## Features Implemented

### 1. Database Migration

A new migration adds the `accepted_legal` field to the `users` table:

```bash
php artisan migrate
```

**Migration File:** `database/migrations/YYYY_MM_DD_HHMMSS_add_accepted_legal_to_users_table.php`

- Field: `accepted_legal` (boolean, default: false)
- Position: After `password` column

### 2. User Model Updates

The `User` model has been updated to:
- Include `accepted_legal` in the `$fillable` array
- Cast `accepted_legal` as boolean in the `casts()` method

### 3. Registration Flow

#### Form Registration
- Registration form (`resources/views/auth/register.blade.php`) includes a required checkbox for legal acceptance
- Validation rule: `'accepted_legal' => ['required', 'accepted']`
- New users are created with `accepted_legal = true`

#### Google Auth Registration
- New users via Google Auth are redirected to `/legal/accept` before account creation
- Google user data is stored in session temporarily
- User account is created only after legal acceptance
- Existing users without acceptance are also redirected to `/legal/accept`

### 4. Legal Pages

#### Legal Index (`/legal`)
- Displays complete Terms & Conditions and Cookies Policy
- Includes references to:
  - Google AdSense (advertising cookies)
  - Google Maps (location services)
  - Google Auth (OAuth)
  - Google Analytics (if applicable)
- Contact email: `almadelasfiestas2000@gmail.com`
- Anchor links: `#terms`, `#cookies`, `#contact`
- Table of contents for easy navigation

#### Legal Acceptance (`/legal/accept`)
- Required acceptance form for users who haven't accepted
- Checkbox validation
- Handles both:
  - New Google Auth users (creates account after acceptance)
  - Existing users (updates `accepted_legal` field)

### 5. Global Footer

A footer partial (`resources/views/partials/footer.blade.php`) has been added to:
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/guest.blade.php`

Footer includes:
- Copyright notice with current year
- Links to Terms (`/legal#terms`)
- Links to Cookies Policy (`/legal#cookies`)
- Contact email link (`mailto:almadelasfiestas2000@gmail.com`)

### 6. Middleware: EnsureAcceptedLegal

**Location:** `app/Http/Middleware/EnsureAcceptedLegal.php`

**Functionality:**
- Checks if authenticated users have `accepted_legal = true`
- Redirects to `/legal/accept` if not accepted
- Allows access to:
  - Legal pages (`/legal`, `/legal/accept`)
  - Authentication routes (login, register, logout)
  - Password reset routes
  - Email verification routes
  - Google Auth routes

**Registration:** Registered in `bootstrap/app.php` as alias `legal.accepted`

**Application:** Applied to all routes in the `auth` middleware group

## Routes

### Public Routes
- `GET /legal` - Legal information page
- `GET /legal/accept` - Legal acceptance form
- `POST /legal/accept` - Process legal acceptance

### Protected Routes
All routes in the `auth` middleware group now require legal acceptance via the `legal.accepted` middleware.

## Workflow

### New User Registration (Form)
1. User fills registration form
2. User must check legal acceptance checkbox
3. User is created with `accepted_legal = true`
4. User is logged in and redirected to home

### New User Registration (Google Auth)
1. User clicks "Continue with Google"
2. Google OAuth callback receives user data
3. User data stored in session (`google_auth_pending`)
4. User redirected to `/legal/accept`
5. User accepts legal terms
6. User account created with `accepted_legal = true`
7. User logged in and redirected to home

### Existing User Login (Without Acceptance)
1. User logs in (form or Google Auth)
2. Middleware detects `accepted_legal = false`
3. User redirected to `/legal/accept`
4. User accepts legal terms
5. `accepted_legal` updated to `true`
6. User redirected to intended destination

## Configuration

### Contact Email
The contact email `almadelasfiestas2000@gmail.com` is used in:
- Legal pages (`/legal`)
- Footer partial
- All legal documentation

### Legal Text Customization
To modify legal text:
1. Edit `resources/views/legal/index.blade.php`
2. Update Terms & Conditions section (`#terms`)
3. Update Cookies Policy section (`#cookies`)
4. Update contact information section (`#contact`)

**Important:** The current legal text is a template and should be reviewed by a legal professional before production use.

## Testing

### Manual Testing Checklist

- [ ] New user registration via form requires legal acceptance
- [ ] New user registration via Google Auth redirects to acceptance page
- [ ] Existing user without acceptance is redirected on login
- [ ] Legal acceptance page displays correctly
- [ ] Legal index page displays all sections
- [ ] Footer appears on all pages
- [ ] Footer links work correctly
- [ ] Middleware allows access to legal pages
- [ ] Middleware blocks access to protected routes without acceptance

## Migration Instructions

1. **Run the migration:**
   ```bash
   php artisan migrate
   ```

2. **Update existing users (optional):**
   ```php
   // In tinker or a seeder
   User::where('accepted_legal', false)->update(['accepted_legal' => false]);
   // Note: Keep as false to force acceptance on next login
   ```

3. **Clear cache (if needed):**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

## Notes

- All existing users will have `accepted_legal = false` by default
- Existing users will be forced to accept on their next login
- The legal text includes disclaimers that it should be reviewed by a legal professional
- Google services mentioned: AdSense, Maps, Auth, and Analytics (if applicable)
- All email references use: `almadelasfiestas2000@gmail.com`

## Support

For questions or issues related to this feature, contact: `almadelasfiestas2000@gmail.com`

