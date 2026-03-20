<?php

namespace Botble\Tours\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Supports\ServiceProvider;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourBooking;
use Botble\Tours\Models\TourCategory;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter('checkout_success_content', [$this, 'processOrderTourData'], 1);
        add_filter('order_form_after_create', [$this, 'processOrderTourBooking'], 1, 2);
        add_action('after_order_status_completed_when_order_payment_failed', [$this, 'completeOrderTourBooking'], 12);
        add_filter('get_order_products', [$this, 'modifyOrderProducts'], 1);
        add_filter('order_detail_extra_html', [$this, 'addTourDetailToOrderHistory'], 1);
        add_filter('cart_item_name', [$this, 'modifyCartItemName'], 1, 2);
        add_filter('checkout_content_package', [$this, 'addTourCheckoutOptions'], 1, 2);
        add_filter('cart_item_price', [$this, 'modifyCartItemPrice'], 1, 2);
        
        // Add tour payment processing filters
        add_filter('payment_filter_before_processing', [$this, 'processTourPayment'], 10, 3);
        add_filter('payment_filter_redirect_url', [$this, 'getTourPaymentRedirectUrl'], 10, 1);
        add_filter('payment_filter_cancel_url', [$this, 'getTourPaymentCancelUrl'], 10, 1);
        
        // Handle payment success/failure for tours
        add_action('payment_action_after_success', [$this, 'handleTourPaymentSuccess'], 10, 2);
        add_action('payment_action_after_failure', [$this, 'handleTourPaymentFailure'], 10, 2);
        
        // Add SEO meta box for tours
        add_filter('base_action_form_actions', [$this, 'addSeoBox'], 24, 2);
        add_action('base_action_meta_boxes', [$this, 'addSeoMetaBox'], 55, 2);
    }
    
    /**
     * Modify the cart item name for tours
     * 
     * @param string $name
     * @param \Botble\Ecommerce\Cart\CartItem $cartItem
     * @return string
     */
    public function modifyCartItemName($name, $cartItem)
    {
        if (Arr::get($cartItem->options, 'is_tour', false)) {
            $tourDate = Arr::get($cartItem->options, 'tour_date');
            $adults = Arr::get($cartItem->options, 'adults', 1);
            $children = Arr::get($cartItem->options, 'children', 0);
            $infants = Arr::get($cartItem->options, 'infants', 0);
            
            $name = '<strong>' . $name . ' (' . __('Tour Booking') . ')</strong>';
            $name .= '<div class="small mt-1">';
            $name .= '<div><strong>' . __('Date') . ':</strong> ' . BaseHelper::formatDate($tourDate) . '</div>';
            $name .= '<div><strong>' . __('Adults') . ':</strong> ' . $adults . '</div>';
            
            if ($children > 0) {
                $name .= '<div><strong>' . __('Children') . ':</strong> ' . $children . '</div>';
            }
            
            if ($infants > 0) {
                $name .= '<div><strong>' . __('Infants') . ':</strong> ' . $infants . '</div>';
            }
            
            $name .= '</div>';
        }
        
        return $name;
    }
    
    /**
     * Modify the cart item price for tours
     * 
     * @param float $price
     * @param \Botble\Ecommerce\Cart\CartItem $cartItem
     * @return float
     */
    public function modifyCartItemPrice($price, $cartItem)
    {
        if (Arr::get($cartItem->options, 'is_tour', false)) {
            $customPrice = Arr::get($cartItem->options, 'custom_price');
            if ($customPrice) {
                return $customPrice;
            }
        }
        
        return $price;
    }
    
    /**
     * Add tour options to the checkout page
     * 
     * @param string $html
     * @param Collection $products
     * @return string
     */
    public function addTourCheckoutOptions($html, $products)
    {
        if (!$products->count()) {
            return $html;
        }
        
        $tourItems = [];
        
        foreach ($products as $product) {
            if (Arr::get($product->options, 'is_tour', false)) {
                $tourItems[] = $product;
            }
        }
        
        if (!count($tourItems)) {
            return $html;
        }
        
        $tourOptionsHtml = '';
        
        foreach ($tourItems as $tourItem) {
            $tourDate = Arr::get($tourItem->options, 'tour_date');
            $adults = Arr::get($tourItem->options, 'adults', 1);
            $children = Arr::get($tourItem->options, 'children', 0);
            $infants = Arr::get($tourItem->options, 'infants', 0);
            
            $tourOptionsHtml .= '
                <div class="bg-light p-3 mb-3">
                    <h6>' . $tourItem->name . '</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Tour Date') . '</label>
                                <input type="text" class="form-control" value="' . BaseHelper::formatDate($tourDate) . '" readonly>
                                <input type="hidden" name="tour_options[' . $tourItem->id . '][tour_date]" value="' . $tourDate . '">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Adults') . '</label>
                                <input type="text" class="form-control" value="' . $adults . '" readonly>
                                <input type="hidden" name="tour_options[' . $tourItem->id . '][adults]" value="' . $adults . '">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Children') . '</label>
                                <input type="text" class="form-control" value="' . $children . '" readonly>
                                <input type="hidden" name="tour_options[' . $tourItem->id . '][children]" value="' . $children . '">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Infants') . '</label>
                                <input type="text" class="form-control" value="' . $infants . '" readonly>
                                <input type="hidden" name="tour_options[' . $tourItem->id . '][infants]" value="' . $infants . '">
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
        
        return $html . '
            <div class="card mt-3">
                <div class="card-header">
                    <h5>' . __('Tour Booking Information') . '</h5>
                </div>
                <div class="card-body">
                    ' . $tourOptionsHtml . '
                </div>
            </div>
        ';
    }
    
    /**
     * Process order tour data
     * 
     * @param string $content
     * @return string
     */
    public function processOrderTourData($content)
    {
        $order = Order::query()->find(session('order_id'));
        
        if (!$order) {
            return $content;
        }
        
        $products = $order->products;
        
        $hasTourBookings = false;
        
        foreach ($products as $product) {
            $options = $product->options;
            
            if (!$options || !isset($options['is_tour'])) {
                continue;
            }
            
            $hasTourBookings = true;
            
            // Create tour booking
            $tourId = Arr::get($options, 'tour_id') ?: $product->product_id;
            $tour = Tour::find($tourId);
            
            if (!$tour) {
                continue;
            }
            
            // Check if booking already exists
            $existingBookingId = Arr::get($options, 'booking_id');
            
            if ($existingBookingId) {
                // Update existing booking with order details
                $existingBooking = TourBooking::find($existingBookingId);
                if ($existingBooking) {
                    $existingBooking->update([
                        'order_id' => $order->id,
                        'payment_status' => $order->payment ? $order->payment->status : 'pending',
                        'status' => 'confirmed',
                    ]);
                }
            } else {
                // Create new booking (fallback)
                TourBooking::create([
                    'tour_id' => $tourId,
                    'store_id' => $tour->store_id, // Link booking to the tour's store
                    'customer_name' => $order->address->name,
                    'customer_email' => $order->address->email,
                    'customer_phone' => $order->address->phone,
                    'adults' => Arr::get($options, 'adults', 1),
                    'children' => Arr::get($options, 'children', 0),
                    'infants' => Arr::get($options, 'infants', 0),
                    'tour_date' => Arr::get($options, 'tour_date'),
                    'special_requirements' => $order->description,
                    'total_amount' => $product->price,

                    'payment_status' => $order->payment ? $order->payment->status : 'pending',
                    'status' => 'confirmed',
                    'order_id' => $order->id,
                ]);
            }
        }
        
        if ($hasTourBookings) {
            // Check if we have a tour booking ID in session
            $tourBookingId = session('tour_booking_id');
            if ($tourBookingId) {
                $content .= '
                    <div class="alert alert-success mt-3">
                        <p>' . __('Your tour booking has been confirmed successfully!') . '</p>
                        <a href="' . route('public.tours.booking.thank-you', ['id' => $tourBookingId]) . '" class="btn btn-primary">' . __('View Booking Details') . '</a>
                    </div>
                ';
            } else {
                $content .= '
                    <div class="alert alert-success mt-3">
                        <p>' . __('Your tour bookings have been created successfully. You will receive a confirmation email soon.') . '</p>
                    </div>
                ';
            }
        }
        
        return $content;
    }
    
    /**
     * Process order tour booking
     * 
     * @param Order $order
     * @param array $data
     * @return Order
     */
    public function processOrderTourBooking($order, $data)
    {
        $tourOptions = Arr::get($data, 'tour_options', []);
        
        if (!$tourOptions) {
            return $order;
        }
        
        $orderProducts = $order->products;
        
        foreach ($orderProducts as $orderProduct) {
            $productId = $orderProduct->product_id;
            
            if (!isset($tourOptions[$productId])) {
                continue;
            }
            
            $options = $orderProduct->options ?: [];
            $options = array_merge($options, $tourOptions[$productId]);
            
            $orderProduct->options = $options;
            $orderProduct->save();
        }
        
        return $order;
    }
    
    /**
     * Complete order tour booking
     * 
     * @param Order $order
     * @return void
     */
    public function completeOrderTourBooking($order)
    {
        $tourBookings = TourBooking::where('order_id', $order->id)->get();
        
        foreach ($tourBookings as $booking) {
            $booking->status = 'confirmed';
            $booking->payment_status = 'completed';
            $booking->save();
        }
    }
    
    /**
     * Modify order products
     * 
     * @param Collection $products
     * @return Collection
     */
    public function modifyOrderProducts($products)
    {
        foreach ($products as $product) {
            $options = $product->options;
            
            if (!$options || !isset($options['is_tour'])) {
                continue;
            }
            
            $tourId = Arr::get($options, 'tour_id') ?: $product->product_id;
            $tour = Tour::find($tourId);
            
            if (!$tour) {
                continue;
            }
            
            $product->model = $tour;
        }
        
        return $products;
    }
    
    /**
     * Add tour detail to order history
     * 
     * @param string $html
     * @return string
     */
    public function addTourDetailToOrderHistory($html)
    {
        $order = null;
        $routeName = request()->route()->getName();
        
        if ($routeName == 'public.orders.tracking') {
            $order = app(OrderInterface::class)->getFirstBy(['code' => request()->input('order_code')]);
        } elseif (auth('customer')->check()) {
            $order = app(OrderInterface::class)->findOrFail(request()->route('id'));
        }
        
        if (!$order) {
            return $html;
        }
        
        $products = $order->products;
        
        $hasTourBookings = false;
        
        foreach ($products as $product) {
            $options = $product->options;
            
            if (!$options || !isset($options['is_tour'])) {
                continue;
            }
            
            $hasTourBookings = true;
        }
        
        if (!$hasTourBookings) {
            return $html;
        }
        
        // Get tour bookings
        $tourBookings = TourBooking::where('order_id', $order->id)->get();
        
        if (!$tourBookings->count()) {
            return $html;
        }
        
        $tourBookingsHtml = '';
        
        foreach ($tourBookings as $booking) {
            $tour = $booking->tour;
            
            if (!$tour) {
                continue;
            }
            
            $tourBookingsHtml .= '
                <div class="bg-light p-3 mb-3">
                    <h6>' . $tour->name . '</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Tour Date') . '</label>
                                <div>' . BaseHelper::formatDate($booking->tour_date) . '</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>' . __('Status') . '</label>
                                <div><span class="badge badge-' . ($booking->status == 'confirmed' ? 'success' : 'warning') . '">' . $booking->status . '</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>' . __('Adults') . '</label>
                                <div>' . $booking->adults . '</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>' . __('Children') . '</label>
                                <div>' . $booking->children . '</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>' . __('Infants') . '</label>
                                <div>' . $booking->infants . '</div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
        
        $html .= '
            <div class="card mt-3">
                <div class="card-header">
                    <h5>' . __('Tour Booking Information') . '</h5>
                </div>
                <div class="card-body">
                    ' . $tourBookingsHtml . '
                </div>
            </div>
        ';
        
        return $html;
    }

    /**
     * Process tour payment
     */
    public function processTourPayment($paymentMethod, $paymentData, $response)
    {
        // Check if this is a tour payment
        if (!session()->has('tour_payment_data')) {
            return $response;
        }

        $tourPaymentData = session('tour_payment_data');
        
        // Merge tour payment data with standard payment data
        $paymentData = array_merge($paymentData, [
            'amount' => $tourPaymentData['amount'],
            'currency' => $tourPaymentData['currency'],
            'description' => $tourPaymentData['description'],
            'order_id' => 'tour_' . $tourPaymentData['order_id'],
            'customer_name' => $tourPaymentData['customer_name'],
            'customer_email' => $tourPaymentData['customer_email'],
            'customer_phone' => $tourPaymentData['customer_phone'],
            'return_url' => $tourPaymentData['return_url'],
            'cancel_url' => $tourPaymentData['cancel_url'],
            'callback_url' => $tourPaymentData['callback_url'],
            'checkout_token' => $tourPaymentData['checkout_token'],
            'type' => 'tour_booking',
        ]);

        // Process based on payment method
        switch ($paymentMethod) {
            case 'stripe':
                return $this->processStripePayment($paymentData, $response);
            case 'paypal':
                return $this->processPaypalPayment($paymentData, $response);
            case 'razorpay':
                return $this->processRazorpayPayment($paymentData, $response);
            case 'mollie':
                return $this->processMolliePayment($paymentData, $response);
            default:
                return $response;
        }
    }

    /**
     * Process Stripe payment for tours
     */
    private function processStripePayment($paymentData, $response)
    {
        if (!class_exists('\Botble\Stripe\Services\StripePaymentService')) {
            return $response->setError()->setMessage(__('Stripe payment is not available.'));
        }

        try {
            $stripeService = app('\Botble\Stripe\Services\StripePaymentService');
            $stripeResponse = $stripeService->execute($paymentData);
            
            if ($stripeResponse && isset($stripeResponse['url'])) {
                return $response->setNextUrl($stripeResponse['url']);
            }
            
            return $response->setError()->setMessage(__('Payment processing failed.'));
        } catch (\Exception $e) {
            return $response->setError()->setMessage($e->getMessage());
        }
    }

    /**
     * Process PayPal payment for tours
     */
    private function processPaypalPayment($paymentData, $response)
    {
        if (!class_exists('\Botble\Paypal\Services\PaypalPaymentService')) {
            return $response->setError()->setMessage(__('PayPal payment is not available.'));
        }

        try {
            $paypalService = app('\Botble\Paypal\Services\PaypalPaymentService');
            $paypalResponse = $paypalService->execute($paymentData);
            
            if ($paypalResponse && isset($paypalResponse['url'])) {
                return $response->setNextUrl($paypalResponse['url']);
            }
            
            return $response->setError()->setMessage(__('Payment processing failed.'));
        } catch (\Exception $e) {
            return $response->setError()->setMessage($e->getMessage());
        }
    }

    /**
     * Process Razorpay payment for tours
     */
    private function processRazorpayPayment($paymentData, $response)
    {
        if (!class_exists('\Botble\Razorpay\Services\RazorpayPaymentService')) {
            return $response->setError()->setMessage(__('Razorpay payment is not available.'));
        }

        try {
            $razorpayService = app('\Botble\Razorpay\Services\RazorpayPaymentService');
            $razorpayResponse = $razorpayService->execute($paymentData);
            
            if ($razorpayResponse && isset($razorpayResponse['url'])) {
                return $response->setNextUrl($razorpayResponse['url']);
            }
            
            return $response->setError()->setMessage(__('Payment processing failed.'));
        } catch (\Exception $e) {
            return $response->setError()->setMessage($e->getMessage());
        }
    }

    /**
     * Process Mollie payment for tours
     */
    private function processMolliePayment($paymentData, $response)
    {
        if (!class_exists('\Botble\Mollie\Services\MolliePaymentService')) {
            return $response->setError()->setMessage(__('Mollie payment is not available.'));
        }

        try {
            $mollieService = app('\Botble\Mollie\Services\MolliePaymentService');
            $mollieResponse = $mollieService->execute($paymentData);
            
            if ($mollieResponse && isset($mollieResponse['url'])) {
                return $response->setNextUrl($mollieResponse['url']);
            }
            
            return $response->setError()->setMessage(__('Payment processing failed.'));
        } catch (\Exception $e) {
            return $response->setError()->setMessage($e->getMessage());
        }
    }

    /**
     * Get tour payment redirect URL
     */
    public function getTourPaymentRedirectUrl($checkoutToken)
    {
        if (session()->has('tour_payment_data')) {
            return route('public.tours.payment.callback', ['status' => 'success', 'token' => $checkoutToken]);
        }
        
        return null;
    }

    /**
     * Get tour payment cancel URL
     */
    public function getTourPaymentCancelUrl($checkoutToken)
    {
        if (session()->has('tour_payment_data')) {
            return route('public.tours.payment.callback', ['status' => 'cancelled', 'token' => $checkoutToken]);
        }
        
        return null;
    }

    /**
     * Handle successful tour payment
     */
    public function handleTourPaymentSuccess($paymentData, $request)
    {
        if (!isset($paymentData['type']) || $paymentData['type'] !== 'tour_booking') {
            return;
        }

        $orderId = str_replace('tour_', '', $paymentData['order_id']);
        $booking = TourBooking::find($orderId);
        
        if ($booking) {
            $booking->update([
                'payment_status' => 'completed',
                'status' => 'confirmed'
            ]);
        }
    }

    /**
     * Handle failed tour payment
     */
    public function handleTourPaymentFailure($paymentData, $request)
    {
        if (!isset($paymentData['type']) || $paymentData['type'] !== 'tour_booking') {
            return;
        }

        $orderId = str_replace('tour_', '', $paymentData['order_id']);
        $booking = TourBooking::find($orderId);
        
        if ($booking) {
            $booking->update([
                'payment_status' => 'failed'
            ]);
        }
    }
    
    /**
     * Add SEO meta box to tour forms
     */
    public function addSeoBox($actions, $data)
    {
        if (get_class($data) == Tour::class || get_class($data) == TourCategory::class) {
            $actions['seo-meta'] = SeoHelper::seoMetaBox($data);
        }
        
        return $actions;
    }
    
    /**
     * Add SEO meta box action
     */
    public function addSeoMetaBox($screen, $data)
    {
        if (get_class($data) == Tour::class || get_class($data) == TourCategory::class) {
            add_meta_box('seo_wrap', trans('packages/seo-helper::seo-helper.meta_box_header'), function () use ($data) {
                return SeoHelper::seoMetaBox($data);
            }, $screen, 'advanced', 'low');
        }
    }
}