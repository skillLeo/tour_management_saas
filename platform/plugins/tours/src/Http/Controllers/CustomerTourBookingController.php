<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Tours\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Botble\Theme\Facades\Theme;

class CustomerTourBookingController extends BaseController
{
    /**
     * Display a listing of the customer's tour bookings.
     */
    public function index(Request $request)
    {
        $this->pageTitle(__('My Tour Bookings'));

        $customer = auth('customer')->user();
        
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $query = TourBooking::query()
            ->with(['tour', 'tour.category', 'tour.city', 'timeSlot'])
            ->where('customer_email', $customer->email)
            ->orderBy('created_at', 'desc');

        // Add status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'LIKE', "%{$search}%")
                  ->orWhereHas('tour', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $bookings = $query->paginate(10);
        
        // Keep query parameters in pagination links
        $bookings->appends($request->query());

        return Theme::scope('customer-tour-bookings', compact('bookings'), 'plugins/tours::customers.tour-bookings.index')
            ->render();
    }

    /**
     * Display the specified tour booking.
     */
    public function show($id)
    {
        $this->pageTitle(__('Tour Booking Details'));

        $customer = auth('customer')->user();
        
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $booking = TourBooking::query()
            ->with(['tour', 'tour.category', 'tour.city', 'timeSlot', 'order'])
            ->where('id', $id)
            ->where('customer_email', $customer->email)
            ->firstOrFail();

        return Theme::scope('customer-tour-booking-detail', compact('booking'), 'plugins/tours::customers.tour-bookings.show')
            ->render();
    }

    /**
     * Cancel a tour booking.
     */
    public function cancel(Request $request, $id)
    {
        $customer = auth('customer')->user();
        
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $booking = TourBooking::query()
            ->where('id', $id)
            ->where('customer_email', $customer->email)
            ->firstOrFail();

        if (!$booking->canBeCancelled()) {
            return redirect()
                ->back()
                ->with('error', __('This booking cannot be cancelled. Either it is already cancelled/completed or the cancellation deadline has passed.'));
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->input('cancellation_reason'),
        ]);

        return redirect()
            ->route('customer.tour-bookings.show', $booking->id)
            ->with('success', __('Your tour booking has been cancelled successfully.'));
    }
}
