# Tours Plugin for Botble CMS

A comprehensive tours management plugin for Botble CMS that allows you to create and manage tour packages, categories, and bookings.

## Features

### Tour Management
- Create and manage tour packages
- Rich content editor for tour descriptions
- Image gallery support
- Pricing with sale prices and multi-currency support
- Duration management (days/nights)
- Capacity management (min/max people)
- Geographic location support with coordinates
- Tour highlights and itinerary management
- SEO optimization (meta title, description, keywords)
- Featured tours
- Booking policies and cancellation rules

### Tour Categories
- Organize tours into categories
- Category images and icons
- Custom ordering
- SEO-friendly slugs

### Booking System
- Complete booking management
- Customer information collection
- Booking status tracking (pending, confirmed, cancelled, completed)
- Payment status tracking (pending, paid, failed, refunded)
- Automatic booking code generation
- Special requests and notes
- Booking confirmation and cancellation

### Admin Features
- Intuitive admin interface
- Data tables with filtering and sorting
- Bulk actions
- Permission-based access control
- Dashboard integration
- Settings panel

## Installation

1. Copy the `tours` folder to `platform/plugins/`
2. Run migrations:
   ```bash
   php artisan migrate
   ```
3. Activate the plugin in Admin Panel > Plugins
4. Configure permissions in Admin Panel > Settings > Users & Permissions

## Database Schema

### Tour Categories (`tour_categories`)
- Basic information (name, slug, description)
- Media (image, icon)
- Organization (order, status)
- SEO fields
- Timestamps

### Tours (`tours`)
- Basic information (name, slug, description, content)
- Media (image, gallery)
- Pricing (price, sale_price, currency)
- Duration (duration_days, duration_nights)
- Capacity (min_people, max_people)
- Location (location, departure_location, return_location, coordinates)
- Features (included_services, excluded_services, tour_highlights, itinerary)
- Booking settings (allow_booking, booking_advance_days, cancellation_hours)
- Dates (start_date, end_date, available_dates)
- Relations (category_id, author_id)
- SEO fields
- Status and featured flags
- Timestamps

### Tour Bookings (`tour_bookings`)
- Booking information (booking_code, tour_id, booking_date)
- Customer details (name, email, phone, address)
- Booking details (number_of_people, total_amount, currency)
- Status tracking (payment_status, booking_status)
- Additional information (special_requests, notes)
- Timestamps

## Usage

### Creating Tours

1. Go to Admin Panel > Tours > Tours
2. Click "Create New Tour"
3. Fill in the tour details:
   - Basic information (name, description, content)
   - Category selection
   - Pricing and currency
   - Duration and capacity
   - Location details
   - Booking settings
   - SEO information
4. Save the tour

### Managing Categories

1. Go to Admin Panel > Tours > Categories
2. Create categories to organize your tours
3. Set category images, icons, and ordering

### Managing Bookings

1. Go to Admin Panel > Tours > Bookings
2. View all bookings with filtering options
3. Update booking and payment status
4. Add notes and manage customer information

## Permissions

The plugin includes granular permissions:

- **Tours**: `tours.index`, `tours.create`, `tours.edit`, `tours.destroy`
- **Categories**: `tour-categories.index`, `tour-categories.create`, `tour-categories.edit`, `tour-categories.destroy`
- **Bookings**: `tour-bookings.index`, `tour-bookings.create`, `tour-bookings.edit`, `tour-bookings.destroy`
- **Settings**: `tours.settings`

## Configuration

### Settings
The plugin includes configurable settings:
- Default currency
- Booking advance days
- Cancellation policy
- Email notifications
- Payment methods

### Customization
- All text strings are translatable
- Templates can be customized
- Additional fields can be added through hooks

## API Integration

The plugin is designed to work with:
- Payment gateways (PayPal, Stripe, etc.)
- Email services
- SMS notifications
- Third-party booking systems

## Hooks and Filters

The plugin provides various hooks for customization:
- Before/after tour creation
- Before/after booking creation
- Email notification hooks
- Template rendering hooks

## Requirements

- Botble CMS 7.0+
- PHP 8.1+
- MySQL 5.7+ or PostgreSQL 10+

## Support

For support and documentation, please refer to the Botble CMS documentation or contact the plugin developer.

## License

This plugin is licensed under the same license as Botble CMS.

## Changelog

### Version 1.0.0
- Initial release
- Complete tour management system
- Booking functionality
- Admin interface
- Multi-language support
- SEO optimization 