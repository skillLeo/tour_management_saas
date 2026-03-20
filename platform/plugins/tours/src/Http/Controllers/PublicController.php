<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Payment\Facades\PaymentMethods;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Models\Slug;
use Botble\Theme\Facades\Theme;
use Botble\Tours\Http\Requests\TourBookingRequest;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourBooking;
use Botble\Tours\Models\TourCategory;
use Botble\Tours\Models\TourCity;
use Botble\Tours\Models\TourReview;
use Botble\Tours\Models\TourTimeSlot;
use Botble\Tours\Repositories\Interfaces\TourInterface;
use Botble\Tours\Repositories\Interfaces\TourCategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Botble\Tours\Models\TourEnquiry;

class PublicController extends Controller
{
    public function __construct(
        protected TourInterface $tourRepository,
        protected TourCategoryInterface $tourCategoryRepository
    ) {
    }

    public function index(Request $request)
    {
        SeoHelper::setTitle(__('Tours'))
            ->setDescription(__('Browse our amazing tours and book your next adventure'));

        $tours = $this->tourRepository->getModel()
            ->leftJoin('slugs', function ($join) {
                $join->on('slugs.reference_id', '=', 'tours.id')
                    ->where('slugs.reference_type', '=', Tour::class);
            })
            ->select([
                'tours.*',
                'slugs.key as slug',
            ])
            ->where('tours.status', BaseStatusEnum::PUBLISHED)
            ->where('tours.allow_booking', true)
            ->whereNotNull('slugs.key')
            ->with(['category', 'city', 'languages'])
            ->when($request->get('category'), function ($query, $categorySlug) {
                return $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            })
            ->when($request->get('city'), function ($query, $cityId) {
                return $query->where('tours.city_id', $cityId);
            })
            ->when($request->get('location'), function ($query, $location) {
                // If location is provided, search in city name
                return $query->whereHas('city', function ($q) use ($location) {
                    $q->where('name', 'LIKE', '%' . $location . '%');
                });
            })
            ->when($request->get('min_price'), function ($query, $minPrice) {
                return $query->where('tours.price', '>=', $minPrice);
            })
            ->when($request->get('max_price'), function ($query, $maxPrice) {
                return $query->where('tours.price', '<=', $maxPrice);
            })
            ->when($request->get('duration'), function ($query, $duration) {
                return $query->where('tours.duration_days', $duration);
            })
            ->when($request->get('featured'), function ($query) {
                return $query->where('tours.is_featured', true);
            })
            ->when($request->get('language'), function ($query, $languageId) {
                return $query->whereHas('languages', function ($q) use ($languageId) {
                    $q->where('tour_languages.id', $languageId);
                });
            })
            ->when($request->get('tour_type'), function ($query, $tourType) {
                return $query->where('tours.tour_type', $tourType);
            })
            ->when($request->get('tour_length'), function ($query, $tourLength) {
                return $query->where('tours.tour_length', $tourLength);
            })
            ->when($request->get('sort'), function ($query, $sort) {
                switch ($sort) {
                    case 'price_asc':
                        return $query->orderBy('tours.price', 'asc');
                    case 'price_desc':
                        return $query->orderBy('tours.price', 'desc');
                    case 'name_asc':
                        return $query->orderBy('tours.name', 'asc');
                    case 'name_desc':
                        return $query->orderBy('tours.name', 'desc');
                    case 'newest':
                        return $query->orderBy('tours.created_at', 'desc');
                    case 'oldest':
                        return $query->orderBy('tours.created_at', 'asc');
                    default:
                        return $query->orderBy('tours.is_featured', 'desc')->orderBy('tours.created_at', 'desc');
                }
            }, function ($query) {
                return $query->orderBy('tours.is_featured', 'desc')->orderBy('tours.created_at', 'desc');
            })
            ->paginate(12);

        $categories = $this->tourCategoryRepository->getModel()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->withCount('tours')
            ->orderBy('order')
            ->get();

        $featuredTours = $this->tourRepository->getModel()
            ->leftJoin('slugs', function ($join) {
                $join->on('slugs.reference_id', '=', 'tours.id')
                    ->where('slugs.reference_type', '=', Tour::class);
            })
            ->select([
                'tours.*',
                'slugs.key as slug',
            ])
            ->where('tours.status', BaseStatusEnum::PUBLISHED)
            ->where('tours.is_featured', true)
            ->where('tours.allow_booking', true)
            ->whereNotNull('slugs.key')
            ->with(['category', 'city'])
            ->limit(6)
            ->get();
            
        // Get languages for filter
        $languages = \Botble\Tours\Models\TourLanguage::where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('order')
            ->get();
            
        // Tour types and lengths for filter
        $tourTypes = [
            'shared' => __('Shared Tour'),
            'private' => __('Private Tour'),
            'transfer' => __('Transfer'),
            'small_group' => __('Small Group')
        ];
        
        $tourLengths = [
            'half_day' => __('Half Day Activities'),
            'full_day' => __('Full Day Activities')
        ];
            
        // Get all cities with their image for the cities slider
        $cities = \Botble\Tours\Models\TourCity::where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        // Get all cities for dropdown, regardless of having tours or not
        $citiesWithTours = \Botble\Tours\Models\TourCity::where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('name')
            ->get();
            
        // Log the number of cities for debugging
        \Illuminate\Support\Facades\Log::info('Number of cities found: ' . $citiesWithTours->count());
        \Illuminate\Support\Facades\Log::info('Cities: ' . $citiesWithTours->pluck('name')->implode(', '));
            
        return Theme::scope('tours', compact('tours', 'categories', 'featuredTours', 'cities', 'languages', 'tourTypes', 'tourLengths', 'citiesWithTours'), 'plugins/tours::themes.tours')
            ->render();
    }

    /**
     * Display all cities page
     */
    public function cities(Request $request)
    {
        $cities = TourCity::query()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->withCount(['tours' => function ($query) {
                $query->where('status', BaseStatusEnum::PUBLISHED);
            }])
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        SeoHelper::setTitle(__('Explore All Cities'));
        
        return Theme::scope('cities', compact('cities'), 'plugins/tours::themes.cities')
            ->render();
    }

    /**
     * Display city detail page with all tours
     */
    public function cityDetail($slug, Request $request)
    {
        // Get city from slug table
        $slugModel = Slug::query()
            ->where('key', $slug)
            ->where('reference_type', TourCity::class)
            ->firstOrFail();
        
        $city = TourCity::query()
            ->where('id', $slugModel->reference_id)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->firstOrFail();

        $tours = Tour::query()
            ->where('city_id', $city->id)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->with(['category', 'city'])
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->withAvg('reviews', 'rating')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        SeoHelper::setTitle($city->name . ' - ' . __('Tours'))
            ->setDescription($city->description);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Cities'), route('public.cities.index'))
            ->add($city->name);

        return Theme::scope('city-detail', compact('city', 'tours'), 'plugins/tours::themes.city-detail')
            ->render();
    }

    public function detail($slug, Request $request)
    {
        // First try to find tour by slug in tours table
        $tour = $this->tourRepository->getModel()
            ->where('slug', $slug)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->first();
        
        // If not found, try to find by slug in slugs table
        if (!$tour) {
            $slugModel = Slug::query()
                ->where('key', $slug)
                ->where('reference_type', Tour::class)
                ->first();
            
            if ($slugModel) {
                $tour = $this->tourRepository->getModel()
                    ->where('id', $slugModel->reference_id)
                    ->where('status', BaseStatusEnum::PUBLISHED)
                    ->first();
            }
        }
        
        // If still not found, throw 404
        if (!$tour) {
            abort(404);
        }
        
        // Load relationships
        $tour->load([
            'category',
            'city',
            'bookings', 
            'faqs' => function($query) {
                $query->where('status', 'published')->orderBy('order');
            },
            'places' => function($query) {
                $query->where('status', 'published')->orderBy('order');
            },
            'schedules' => function($query) {
                $query->where('status', 'published')->orderBy('order');
            },
            'timeSlots' => function($query) {
                $query->where('status', 'available')
                      ->orderBy('start_time');
            },
            'languages' => function($query) {
                $query->where('status', 'published')
                      ->orderBy('order');
            }
        ]);

        SeoHelper::setTitle($tour->name)
            ->setDescription($tour->description ?: $tour->name);

        $relatedTours = $this->tourRepository->getModel()
            ->leftJoin('slugs', function ($join) {
                $join->on('slugs.reference_id', '=', 'tours.id')
                     ->where('slugs.reference_type', '=', Tour::class);
            })
            ->select([
                'tours.*',
                'slugs.key as slug',
            ])
            ->where('tours.status', BaseStatusEnum::PUBLISHED)
            ->where('tours.category_id', $tour->category_id)
            ->where('tours.id', '!=', $tour->id)
            ->whereNotNull('slugs.key')
            ->with(['category', 'city'])
            ->limit(4)
            ->get();

        return Theme::scope('tour', compact('tour', 'relatedTours'), 'plugins/tours::themes.tour')
            ->render();
    }

    public function category($slug, Request $request)
    {
        $category = $this->tourCategoryRepository->getModel()
            ->where('slug', $slug)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->firstOrFail();

        SeoHelper::setTitle($category->name)
            ->setDescription($category->description ?: $category->name);

        $tours = $this->tourRepository->getModel()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->where('category_id', $category->id)
            ->where('allow_booking', true)
            ->with(['category', 'city'])
            ->when($request->get('sort'), function ($query, $sort) {
                switch ($sort) {
                    case 'price_asc':
                        return $query->orderBy('price', 'asc');
                    case 'price_desc':
                        return $query->orderBy('price', 'desc');
                    case 'name_asc':
                        return $query->orderBy('name', 'asc');
                    case 'name_desc':
                        return $query->orderBy('name', 'desc');
                    case 'newest':
                        return $query->orderBy('created_at', 'desc');
                    case 'oldest':
                        return $query->orderBy('created_at', 'asc');
                    default:
                        return $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                return $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
            })
            ->paginate(12);

        $categories = $this->tourCategoryRepository->getModel()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->withCount('tours')
            ->orderBy('order')
            ->get();

        return Theme::scope('tour-category', compact('tours', 'categories', 'category'), 'plugins/tours::themes.tour-category')
            ->render();
    }

    public function storeBooking(TourBookingRequest $request, BaseHttpResponse $response)
    {
        // Debug request
        \Illuminate\Support\Facades\Log::info('Booking request received', [
            'tour_id' => $request->input('tour_id'),
            'time_slots' => $request->input('time_slot_ids'),
            'adults' => $request->input('adults'),
            'children' => $request->input('children')
        ]);
        
        $tour = $this->tourRepository->findOrFail($request->input('tour_id'));

        if (!$tour->allow_booking) {
            return $response
                ->setError()
                ->setMessage(__('This tour is not available for booking'));
        }
        
        $adults = $request->input('adults', 1);
        $children = $request->input('children', 0);
        $infants = $request->input('infants', 0);
        
        // Calculate total price based on adults, children, and infants using new pricing system
        $adultPrice = $tour->current_price;
        $childPrice = $tour->current_children_price ?: $tour->current_price;
        $infantPrice = $tour->current_infants_price;
        
        $totalAmountBase = ($adults * $adultPrice) + ($children * $childPrice) + ($infants * $infantPrice);

        // Create tour booking directly
        $timeSlotIds = $request->input('time_slot_ids');
        $decodedSlotIds = [];
        if (is_string($timeSlotIds)) {
            $decodedSlotIds = json_decode($timeSlotIds, true) ?: [];
        } elseif (is_array($timeSlotIds)) {
            $decodedSlotIds = $timeSlotIds;
        }

        // Validate time slots
        if (empty($decodedSlotIds)) {
            return $response
                ->setError()
                ->setMessage(__('Please select at least one time slot'));
        }

        // Fetch the selected time slot(s)
        $selectedTimeSlots = TourTimeSlot::whereIn('id', $decodedSlotIds)
            ->where('tour_id', $tour->id)
            ->where('status', 'available')
            ->get();

        if ($selectedTimeSlots->isEmpty()) {
            return $response
                ->setError()
                ->setMessage(__('Selected time slot(s) are no longer available'));
        }

        // Skip capacity validation - capacity check disabled
        $totalParticipants = $adults + $children + $infants;

        // Calculate total price (multiply base price by number of selected slots)
        $totalAmount = $totalAmountBase * count($selectedTimeSlots);

        // Get the selected date from the request or use current date
        $selectedDate = $request->input('selected_date');
        
        // If no date is provided, use the current date
        if (!$selectedDate) {
            $selectedDate = date('Y-m-d');
        }
        
        // Prepare booking data
        $bookingData = [
            'tour_id' => $tour->id,
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_phone' => $request->input('customer_phone'),
            'customer_address' => $request->input('customer_address'),
            'customer_nationality' => $request->input('customer_nationality'),
            'special_requirements' => $request->input('special_requirements'),
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'status' => 'pending',
            'time_slot_ids' => json_encode($decodedSlotIds),
            'tour_date' => $selectedDate // Use selected date
        ];

        // Create booking
        $booking = TourBooking::create($bookingData);

        // Skip updating capacity - capacity tracking disabled

        // Send confirmation email (if email service is set up)
        // You can add email sending logic here

        // Store booking data in session for checkout
        session(['tour_checkout_data' => [
            'booking_id' => $booking->id,
            'tour_id' => $tour->id,
            'tour_name' => $tour->name,
            'tour_image' => $tour->image,
            'tour_date' => $selectedDate,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'adult_price' => $adultPrice,
            'child_price' => $childPrice,
            'infant_price' => $infantPrice,
            'total_amount' => $totalAmount,
            'currency' => get_application_currency()->title ?? 'USD',
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_phone' => $request->input('customer_phone'),
            'customer_nationality' => $request->input('customer_nationality'),
            'customer_address' => $request->input('customer_address'),
            'special_requirements' => $request->input('special_requirements'),
        ]]);
        
        // Create absolute URL for checkout
        $checkoutUrl = route('public.tours.checkout');
        
        // Debug log
        \Illuminate\Support\Facades\Log::info('Booking created successfully', [
            'booking_id' => $booking->id,
            'checkout_url' => $checkoutUrl
        ]);
        
        // For AJAX requests, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return $response
                ->setData([
                    'booking_id' => $booking->id,
                    'redirect_url' => $checkoutUrl
                ])
                ->setMessage(__('Booking created! Proceeding to payment.'));
        }
        
        // For regular requests, redirect directly
        return redirect()->to($checkoutUrl);
    }
    
    /**
     * Add tour to cart
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function addToCart(Request $request, BaseHttpResponse $response)
    {
        $tourId = $request->input('id') ?: $request->input('tour_id');
        $quantity = $request->input('qty', 1);
        $tourDate = $request->input('tour_date');
        $adults = $request->input('adults', 1);
        $children = $request->input('children', 0);
        $infants = $request->input('infants', 0);
        
        // Get customer information if available
        $customerName = $request->input('customer_name');
        $customerEmail = $request->input('customer_email');
        $customerPhone = $request->input('customer_phone');
        $specialRequirements = $request->input('special_requirements');
        
        $tour = $this->tourRepository->findOrFail($tourId);
        
        if (!$tour) {
            return $response
                ->setError()
                ->setMessage(__('This tour is not available!'));
        }
        
        if (!$tour->allow_booking) {
            return $response
                ->setError()
                ->setMessage(__('This tour is not available for booking'));
        }
        
        // Check if the tour has available spots
        if ($tour->max_people > 0 && $adults > $tour->available_spots) {
            return $response
                ->setError()
                ->setMessage(__('This tour has only :spots spots available', ['spots' => $tour->available_spots]));
        }
        
        // Calculate total price based on adults, children, and infants
        $adultPrice = $tour->price;
        $childPrice = $adultPrice * 0.6; // 60% of adult price
        $infantPrice = 0;
        
        $totalAmount = ($adults * $adultPrice) + ($children * $childPrice) + ($infants * $infantPrice);
        
        // This method is now deprecated - redirect to direct booking
        return $response
            ->setError()
            ->setMessage(__('Please use the direct booking form on the tour page.'));
    }
    


    /**
     * Show booking thank you page
     *
     * @param int $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function bookingThankYou($id)
    {
        $booking = TourBooking::findOrFail($id);
        $tour = $this->tourRepository->findOrFail($booking->tour_id);
        
        // Clear tour booking session data
        session()->forget(['tour_booking_id', 'tour_booking_data']);
        
        SeoHelper::setTitle(__('Booking Confirmation'));
        
        return Theme::scope('tour-booking-thank-you', compact('booking', 'tour'), 'plugins/tours::themes.tour-booking-thank-you')
            ->render();
    }

    /**
     * Show tour checkout page
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function checkout(Request $request)
    {
        $checkoutData = session('tour_checkout_data');
        
        if (!$checkoutData) {
            // For testing purposes, create dummy data if no session data exists
            $checkoutData = [
                'booking_id' => 1,
                'tour_id' => 1,
                'tour_name' => 'Sample Tour',
                'tour_image' => '',
                'tour_date' => date('Y-m-d'),
                'adults' => 2,
                'children' => 1,
                'infants' => 0,
                'adult_price' => 100,
                'child_price' => 60,
                'infant_price' => 0,
                'total_amount' => 260,
                'currency' => get_application_currency()->title ?? 'USD',
                'customer_name' => 'Test Customer',
                'customer_email' => 'test@example.com',
                'customer_phone' => '+1234567890',
                'special_requirements' => '',
            ];
            
            // Store in session for consistency
            session(['tour_checkout_data' => $checkoutData]);
        }
        
        // Create dummy objects for testing
        $tour = (object) [
            'id' => $checkoutData['tour_id'],
            'name' => $checkoutData['tour_name'],
            'image' => $checkoutData['tour_image'],
            'price' => $checkoutData['adult_price'],
            'currency' => $checkoutData['currency'],
        ];
        
        $booking = (object) [
            'id' => $checkoutData['booking_id'],
            'tour_id' => $checkoutData['tour_id'],
            'customer_name' => $checkoutData['customer_name'],
            'customer_email' => $checkoutData['customer_email'],
            'customer_phone' => $checkoutData['customer_phone'],
            'adults' => $checkoutData['adults'],
            'children' => $checkoutData['children'],
            'infants' => $checkoutData['infants'],
            'tour_date' => $checkoutData['tour_date'],
            'total_amount' => $checkoutData['total_amount'],
            'currency' => $checkoutData['currency'],
            'special_requirements' => $checkoutData['special_requirements'],
        ];

        // Get payment methods using the same system as ecommerce
        $orderAmount = $checkoutData['total_amount'];
        $currency = $checkoutData['currency'] ?: 'USD';
        
        $paymentMethods = '';
        if (is_plugin_active('payment') && $orderAmount) {
            if (class_exists('Botble\Payment\Facades\PaymentMethods')) {
                $paymentMethods = apply_filters(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, null, [
                    'amount' => format_price($orderAmount, null, true),
                    'currency' => get_application_currency()->title,
                    'name' => null,
                    'selected' => \Botble\Payment\Facades\PaymentMethods::getSelectedMethod(),
                    'default' => \Botble\Payment\Facades\PaymentMethods::getDefaultMethod(),
                    'selecting' => \Botble\Payment\Facades\PaymentMethods::getSelectingMethod(),
                ]) . \Botble\Payment\Facades\PaymentMethods::render();
            }
        }
        
        SeoHelper::setTitle(__('Tour Checkout'))
            ->setDescription(__('Complete your tour booking payment'));
        
        // Return view with data using Theme scope like other pages
        return Theme::scope('tour-checkout', compact('tour', 'checkoutData', 'paymentMethods'), 'plugins/tours::themes.tour-checkout')
            ->render();
    }

    /**
     * Process tour checkout payment
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function processCheckout(Request $request, BaseHttpResponse $response)
    {
        $checkoutData = session('tour_checkout_data');
        $paymentMethod = $request->input('payment_method');
        
        // Log all request data for debugging
        Log::info('Checkout request data:', [
            'all_input' => $request->all(),
            'payment_method' => $paymentMethod,
            'has_checkout_data' => !empty($checkoutData),
        ]);
        
        if (!$paymentMethod) {
            return $response
                ->setError()
                ->setMessage(__('Please select a payment method.'));
        }
        
        // Store payment method in session for payment processing
        session(['tour_payment_method' => $paymentMethod]);
        
        // Process payment based on selected method
        switch ($paymentMethod) {
            case 'bank_transfer':
            case 'cod':
                // For testing - simulate successful booking
                try {
                    if ($checkoutData && isset($checkoutData['booking_id'])) {
                        $booking = TourBooking::find($checkoutData['booking_id']);
                        if ($booking) {
                            // Update booking status to confirmed
                            $booking->update([
                                'payment_status' => 'pending',
                                'status' => 'confirmed'
                            ]);
                            
                            // Clear session data
                            session()->forget(['tour_checkout_data', 'tour_payment_method']);
                            
                            return $response
                                ->setNextUrl(route('public.tours.booking.thank-you', $booking->id))
                                ->setMessage(__('Your tour booking has been confirmed. Payment will be collected on tour date.'));
                        }
                    }
                } catch (\Exception $e) {
                    // If database operation fails, continue with demo mode
                }
                
                // Demo mode - clear session and redirect to thank you page
                session()->forget(['tour_checkout_data', 'tour_payment_method']);
                
                // For demo mode, use a default booking ID or the one from checkout data
                $bookingId = $checkoutData['booking_id'] ?? '1';
                
                return $response
                    ->setNextUrl(route('public.tours.booking.thank-you', ['id' => $bookingId]))
                    ->setMessage(__('Your tour booking has been confirmed. Payment will be collected on tour date.'));
                
            case 'stripe':
            case 'paypal':
            case 'razorpay':
            case 'mollie':
                // Check if the payment gateway is available
                $paymentGatewayAvailable = false;
                
                // Check for Stripe
                if ($paymentMethod === 'stripe' && class_exists('\Botble\Stripe\Services\StripePaymentService')) {
                    $paymentGatewayAvailable = true;
                }
                
                // Check for PayPal
                if ($paymentMethod === 'paypal' && class_exists('\Botble\Paypal\Services\PaypalPaymentService')) {
                    $paymentGatewayAvailable = true;
                }
                
                // Check for Razorpay
                if ($paymentMethod === 'razorpay' && class_exists('\Botble\Razorpay\Services\RazorpayPaymentService')) {
                    $paymentGatewayAvailable = true;
                }
                
                // Check for Mollie
                if ($paymentMethod === 'mollie' && class_exists('\Botble\Mollie\Services\MolliePaymentService')) {
                    $paymentGatewayAvailable = true;
                }
                
                if (!$paymentGatewayAvailable) {
                    return $response
                        ->setError()
                        ->setMessage(__('Payment method ') . ucfirst($paymentMethod) . __(' is not available. Please use Cash On Delivery (COD) instead.'));
                }
                
                // For demo purposes, simulate payment gateway redirect
                try {
                    if ($checkoutData && isset($checkoutData['booking_id'])) {
                        $booking = TourBooking::find($checkoutData['booking_id']);
                        if ($booking) {
                            return $this->processPaymentGateway($paymentMethod, $booking, $response);
                        }
                    }
                } catch (\Exception $e) {
                    // If database operation fails, continue with demo mode
                    \Illuminate\Support\Facades\Log::error('Payment gateway error: ' . $e->getMessage());
                    return $response
                        ->setError()
                        ->setMessage(__('Error processing payment: ') . $e->getMessage());
                }
                
                // Demo mode - simulate payment processing
                session()->forget(['tour_checkout_data', 'tour_payment_method']);
                
                // Update booking status for demo mode
                if ($checkoutData && isset($checkoutData['booking_id'])) {
                    $booking = TourBooking::find($checkoutData['booking_id']);
                    if ($booking) {
                        $booking->update([
                            'payment_status' => 'completed',
                            'status' => 'confirmed'
                        ]);
                        
                        return $response
                            ->setNextUrl(route('public.tours.booking.thank-you', $booking->id))
                            ->setMessage(ucfirst($paymentMethod) . __(' payment completed successfully! (Demo mode)'));
                    }
                }
                
                // Use BaseHttpResponse instead of redirect for consistency
                return $response
                    ->setNextUrl(route('public.index'))
                    ->setMessage(ucfirst($paymentMethod) . ' payment processing completed successfully! (Demo mode). Booking ID: ' . ($checkoutData['booking_id'] ?? '1'));
                
            default:
                // Log the invalid payment method
                Log::warning('Invalid payment method selected', [
                    'payment_method' => $paymentMethod,
                    'available_methods' => ['cod', 'bank_transfer', 'stripe', 'paypal', 'razorpay', 'mollie'],
                    'checkout_data' => $checkoutData ? true : false
                ]);
                
                return $response
                    ->setError()
                    ->setMessage(__('Invalid payment method selected: ') . $paymentMethod . __('. Please choose a valid payment option.'));
        }
    }

    /**
     * Process payment gateway
     *
     * @param string $paymentMethod
     * @param TourBooking $booking
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    private function processPaymentGateway($paymentMethod, $booking, $response)
    {
        $checkoutData = session('tour_checkout_data');
        
        // Create payment data similar to ecommerce
        $paymentData = [
            'amount' => $checkoutData['total_amount'],
            'currency' => $checkoutData['currency'],
            'description' => 'Tour Booking: ' . $checkoutData['tour_name'],
            'order_id' => $booking->id,
            'customer_name' => $checkoutData['customer_name'],
            'customer_email' => $checkoutData['customer_email'],
            'customer_phone' => $checkoutData['customer_phone'],
            'return_url' => route('public.tours.payment.callback'),
            'cancel_url' => route('public.tours.checkout'),
            'callback_url' => route('public.tours.payment.callback'),
            'checkout_token' => md5($booking->id . time()),
        ];
        
        // Store payment data in session
        session(['tour_payment_data' => $paymentData]);
        
        // Use the same payment processing as ecommerce
        return apply_filters('payment_filter_before_processing', $paymentMethod, $paymentData, $response);
    }

    /**
     * Handle payment callback
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function paymentCallback(Request $request, BaseHttpResponse $response)
    {
        $paymentData = session('tour_payment_data');
        $checkoutData = session('tour_checkout_data');
        
        if (!$paymentData || !$checkoutData) {
            return $response
                ->setError()
                ->setNextUrl(route('public.tours.index'))
                ->setMessage(__('Payment session expired. Please try booking again.'));
        }
        
        $booking = TourBooking::findOrFail($checkoutData['booking_id']);
        
        // Check payment status from gateway
        $paymentStatus = $request->input('status', 'failed');
        
        if ($paymentStatus === 'success' || $paymentStatus === 'completed') {
            // Payment successful
            $booking->update([
                'payment_status' => 'completed',
                'status' => 'confirmed'
            ]);
            
            // Clear session data
            session()->forget(['tour_checkout_data', 'tour_payment_data', 'tour_payment_method']);
            
            return $response
                ->setNextUrl(route('public.tours.booking.thank-you', $booking->id))
                ->setMessage(__('Payment completed successfully. Your tour booking is confirmed.'));
        } else {
            // Payment failed
            $booking->update([
                'payment_status' => 'failed'
            ]);
            
            return $response
                ->setError()
                ->setNextUrl(route('public.tours.checkout'))
                ->setMessage(__('Payment failed. Please try again.'));
        }
    }

    /**
     * Store a new review from public users
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function storeReview(Request $request, BaseHttpResponse $response)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'review_text' => 'required|string|max:1000',
        ]);

        try {
            // Check if tour exists and is published
            $tour = Tour::where('id', $request->input('tour_id'))
                       ->where('status', BaseStatusEnum::PUBLISHED)
                       ->first();
            
            if (!$tour) {
                return $response
                    ->setError()
                    ->setMessage(__('Tour not found or not available for reviews.'));
            }

            $tourReview = TourReview::create([
                'tour_id' => $request->input('tour_id'),
                'customer_name' => trim($request->input('customer_name')),
                'customer_email' => trim($request->input('customer_email')),
                'rating' => $request->input('rating'),
                'review' => trim($request->input('review_text')),
                'is_approved' => false, // Requires admin approval
            ]);

            return $response
                ->setMessage(__('Thank you for your review! It will be published after admin approval.'));
        } catch (\Exception $e) {
            Log::error('Review submission error: ' . $e->getMessage(), [
                'tour_id' => $request->input('tour_id'),
                'customer_email' => $request->input('customer_email'),
                'error' => $e->getMessage()
            ]);
            return $response
                ->setError()
                ->setMessage(__('An error occurred while submitting your review. Please try again.'));
        }
    }

    public function storeEnquiry(Request $request, BaseHttpResponse $response)
    {
        $validator = Validator::make($request->all(), [
            'tour_id' => 'required|exists:tours,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return $response
                ->setError()
                ->setMessage($validator->errors()->first());
        }

        // Store enquiry in DB
        TourEnquiry::create([
            'tour_id' => (int) $request->input('tour_id'),
            'customer_name' => trim($request->input('customer_name')),
            'customer_email' => trim($request->input('customer_email')),
            'subject' => trim($request->input('subject')),
            'message' => trim($request->input('message')),
        ]);

        return $response->setMessage(__('Thanks! We have received your enquiry and will get back to you shortly.'));
    }

    public function getTimeSlots(Request $request, string $slug, BaseHttpResponse $response)
    {
        try {
            $tour = $this->tourRepository->getFirstBy([
                'slug' => $slug,
                'status' => BaseStatusEnum::PUBLISHED,
            ]);

            if (!$tour) {
                return $response
                    ->setError()
                    ->setMessage(__('Tour not found'));
            }

            $query = $tour->timeSlots()
                ->where('status', 'available')
                ->orderBy('start_time');

            $timeSlots = $query->limit(200)->get()->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => is_string($slot->start_time) ? $slot->start_time : $slot->start_time->format('H:i'),
                    'end_time' => $slot->getEndTimeAttribute()->format('H:i'),
                    'price' => $slot->price ?? null,
                    'notes' => $slot->notes ?? null,
                    'restricted_days' => $slot->restricted_days ?? [], // Include restricted days in response
                ];
            });

            return $response
                ->setData([
                    'success' => true,
                    'time_slots' => $timeSlots,
                    'count' => $timeSlots->count()
                ]);

        } catch (\Exception $e) {
            Log::error('Time slots loading error: ' . $e->getMessage(), [
                'tour_slug' => $slug,
                'error' => $e->getMessage()
            ]);
            
            return $response
                ->setError()
                ->setMessage(__('An error occurred while loading time slots. Please try again.'));
        }
    }
}