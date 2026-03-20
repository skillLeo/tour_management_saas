<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Tours\Models\TourBooking;
use Illuminate\Http\Request;

class VendorTourBookingController extends BaseController
{
    // Helper: get current vendor's store_id
    private function getStoreId(): int|null
    {
        $customer = auth('customer')->user();
        return $customer && $customer->store ? $customer->store->id : null;
    }

    // Helper: base query scoped to vendor's tours
    // store_id lives on TOURS table, NOT on tour_bookings!
    private function vendorBookingQuery(int $storeId)
    {
        return TourBooking::with(['tour'])
            ->whereHas('tour', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            });
    }

    public function index(Request $request)
    {
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return view('plugins/tours::themes.vendor-dashboard.tour-bookings.index', [
                'bookings' => TourBooking::whereNull('id')->paginate(10),
            ]);
        }

        $query = $this->vendorBookingQuery($storeId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('plugins/tours::themes.vendor-dashboard.tour-bookings.index', compact('bookings'));
    }

    public function show(int|string $id)
    {
        $storeId = $this->getStoreId();
        $booking = $this->vendorBookingQuery($storeId)->findOrFail($id);

        return view('plugins/tours::themes.vendor-dashboard.tour-bookings.show', compact('booking'));
    }

    public function edit(int|string $id)
    {
        $storeId = $this->getStoreId();
        $booking = $this->vendorBookingQuery($storeId)->findOrFail($id);

        return view('plugins/tours::themes.vendor-dashboard.tour-bookings.edit', compact('booking'));
    }

    public function update(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $storeId = $this->getStoreId();
        $booking = $this->vendorBookingQuery($storeId)->findOrFail($id);

        $booking->update($request->only(['status', 'payment_status', 'notes']));

        return $response
            ->setPreviousUrl(route('marketplace.vendor.tour-bookings.index'))
            ->setMessage(__('Booking updated successfully!'));
    }

    // ✅ ADDED: called by "Confirm Booking", "Mark as Completed", "Cancel Booking" buttons
    public function updateStatus(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $storeId = $this->getStoreId();
        $booking = $this->vendorBookingQuery($storeId)->findOrFail($id);

        $newStatus = $request->input('status');
        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];

        if (!in_array($newStatus, $validStatuses)) {
            return $response
                ->setError()
                ->setMessage(__('Invalid status value.'));
        }

        $booking->status = $newStatus;
        $booking->save();

        return $response
            ->setPreviousUrl(route('marketplace.vendor.tour-bookings.show', $booking->id))
            ->setMessage(__('Booking status updated to :status.', ['status' => ucfirst($newStatus)]));
    }

    // ✅ ADDED: called by "Mark as Paid" button
    public function updatePaymentStatus(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $storeId = $this->getStoreId();
        $booking = $this->vendorBookingQuery($storeId)->findOrFail($id);

        $newPaymentStatus = $request->input('payment_status');
        $validStatuses = ['pending', 'paid', 'failed', 'refunded'];

        if (!in_array($newPaymentStatus, $validStatuses)) {
            return $response
                ->setError()
                ->setMessage(__('Invalid payment status value.'));
        }

        $booking->payment_status = $newPaymentStatus;
        if ($newPaymentStatus === 'paid') {
            $booking->payment_date = now();
        }
        $booking->save();

        return $response
            ->setPreviousUrl(route('marketplace.vendor.tour-bookings.show', $booking->id))
            ->setMessage(__('Payment status updated to :status.', ['status' => ucfirst($newPaymentStatus)]));
    }

    public function destroy(int|string $id, BaseHttpResponse $response)
    {
        $storeId = $this->getStoreId();
        $booking = $this->vendorBookingQuery($storeId)->findOrFail($id);

        if (!in_array($booking->status, ['pending', 'cancelled'])) {
            return $response
                ->setError()
                ->setMessage(__('Cannot delete confirmed or completed bookings.'));
        }

        $booking->delete();

        return $response
            ->setMessage(__('Booking deleted successfully!'));
    }
}