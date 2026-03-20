<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;


AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\Tours\Http\Controllers', 'prefix' => 'tours'], function (): void {
        Route::group(['prefix' => 'tour-categories', 'as' => 'tour-categories.'], function (): void {
            Route::resource('', 'TourCategoryController')->parameters(['' => 'tour_category']);

            Route::get('search', [
                'as' => 'search',
                'uses' => 'TourCategoryController@search',
            ]);
        });

        Route::group(['prefix' => 'tour-cities', 'as' => 'tour-cities.'], function (): void {
            Route::resource('', 'TourCityController')->parameters(['' => 'tour_city']);

            Route::get('search', [
                'as' => 'search',
                'uses' => 'TourCityController@search',
            ]);
        });
        
        Route::group(['prefix' => 'tour-languages', 'as' => 'tour-languages.'], function (): void {
            Route::resource('', 'TourLanguageController')->parameters(['' => 'tour_language']);

            Route::get('search', [
                'as' => 'search',
                'uses' => 'TourLanguageController@search',
            ]);
        });

        Route::group(['prefix' => '', 'as' => 'tours.'], function (): void {
            Route::resource('', 'TourController')->parameters(['' => 'tour']);
        });

        Route::group(['prefix' => 'tour-bookings', 'as' => 'tour-bookings.'], function (): void {
            Route::resource('', 'TourBookingController')->parameters(['' => 'tour_booking']);

            Route::post('{tour_booking}/confirm', [
                'as' => 'confirm',
                'uses' => 'TourBookingController@confirm',
            ]);

            Route::post('{tour_booking}/cancel', [
                'as' => 'cancel',
                'uses' => 'TourBookingController@cancel',
            ]);
        });

        Route::group(['prefix' => 'tour-reviews'], function (): void {
            Route::resource('', 'TourReviewController')->parameters(['' => 'tour_review'])->names([
                'index' => 'tour-reviews.index',
                'create' => 'tour-reviews.create',
                'store' => 'tour-reviews.store',
                'show' => 'tour-reviews.show',
                'edit' => 'tour-reviews.edit',
                'update' => 'tour-reviews.update',
                'destroy' => 'tour-reviews.destroy',
            ]);
        });

        Route::group(['prefix' => 'tour-enquiries', 'as' => 'tour-enquiries.'], function (): void {
            Route::resource('', 'TourEnquiryController')
                ->parameters(['' => 'tour_enquiry'])
                ->only(['index', 'destroy']);
        });

        // Route::group(['prefix' => 'settings'], function (): void {
        //     Route::get('tours', [
        //         'as' => 'tours.settings',
        //         'uses' => 'Settings\TourSettingController@edit',
        //     ]);

        //     Route::put('tours', [
        //         'as' => 'tours.settings.update',
        //         'uses' => 'Settings\TourSettingController@update',
        //         'permission' => 'tours.settings',
        //     ]);
        // });
    });
});

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Theme::registerRoutes(function (): void {
        // Customer Tour Bookings Routes (Standalone Pages)
        Route::middleware('customer')
            ->namespace('Botble\Tours\Http\Controllers')
            ->name('customer.')
            ->group(function (): void {
                Route::prefix('tour-bookings')->name('tour-bookings.')->group(function (): void {
                    Route::get('/', [
                        'as' => 'index',
                        'uses' => 'CustomerTourBookingController@index',
                    ]);
                    
                    Route::get('{id}', [
                        'as' => 'show',
                        'uses' => 'CustomerTourBookingController@show',
                    ])->wherePrimaryKey();
                    
                    Route::post('{id}/cancel', [
                        'as' => 'cancel',
                        'uses' => 'CustomerTourBookingController@cancel',
                    ])->wherePrimaryKey();
                });
            });

        Route::group(['namespace' => 'Botble\Tours\Http\Controllers'], function (): void {
            // Cities Routes
            Route::get('cities', [
                'as' => 'public.cities.index',
                'uses' => 'PublicController@cities',
            ]);
            
            Route::get('tour-cities/{slug}', [
                'as' => 'public.city.detail',
                'uses' => 'PublicController@cityDetail',
            ]);
            
            Route::group(['prefix' => 'tours', 'as' => 'public.tours.'], function (): void {
                Route::get('', [
                    'as' => 'index',
                    'uses' => 'PublicController@index',
                ]);

                Route::post('booking', [
                    'as' => 'booking.store',
                    'uses' => 'PublicController@storeBooking',
                ]);

                Route::post('add-to-cart', [
                    'as' => 'add-to-cart',
                    'uses' => 'PublicController@addToCart',
                ]);
                // Tour Checkout Routes - must come before {slug} route
                Route::get('checkout', [
                    'as' => 'checkout',
                    'uses' => 'PublicController@checkout',
                ]);

                Route::post('checkout/process', [
                    'as' => 'checkout.process',
                    'uses' => 'PublicController@processCheckout',
                ]);

                Route::get('payment/callback', [
                    'as' => 'payment.callback',
                    'uses' => 'PublicController@paymentCallback',
                ]);

                Route::post('payment/callback', [
                    'as' => 'payment.callback.post',
                    'uses' => 'PublicController@paymentCallback',
                ]);

                Route::get('booking/thank-you/{id}', [
                    'as' => 'booking.thank-you',
                    'uses' => 'PublicController@bookingThankYou',
                ])->where('id', '[0-9]+');

                // Public Review Routes
                Route::post('reviews', [
                    'as' => 'reviews.store',
                    'uses' => 'PublicController@storeReview',
                ]);
                Route::post('enquiry', [
                    'as' => 'enquiry.store',
                    'uses' => 'PublicController@storeEnquiry',
                ]);

                // Time Slots API
                Route::get('{slug}/time-slots', [
                    'as' => 'time-slots',
                    'uses' => 'PublicController@getTimeSlots',
                ]);

                // This must be last because it catches everything
                Route::get('{slug}', [
                    'as' => 'detail',
                    'uses' => 'PublicController@detail',
                ]);
            });

            Route::group(['prefix' => 'tour-categories', 'as' => 'public.tours.'], function (): void {
                Route::get('{slug}', [
                    'as' => 'category',
                    'uses' => 'PublicController@category',
                ]);
            });
        });
    });
}

// Ensure public API endpoint for time slots is always available, even when detail page is resolved via theme fallback
Route::group(['namespace' => 'Botble\Tours\Http\Controllers', 'prefix' => 'tours', 'as' => 'public.tours.'], function (): void {
    Route::get('{slug}/time-slots', [
        'as' => 'time-slots',
        'uses' => 'PublicController@getTimeSlots',
    ]);
});