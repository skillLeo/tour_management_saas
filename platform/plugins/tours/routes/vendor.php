<?php

use Illuminate\Support\Facades\Route;



Route::group([
    'prefix' => 'vendor',
    'as' => 'marketplace.vendor.',
    'middleware' => ['web', 'core', 'vendor'],
], function (): void {
    // Upload route for tour images
    Route::post('tours/upload', 'Botble\Tours\Http\Controllers\VendorTourController@postUpload')->name('tours.upload');
    
    Route::group(['prefix' => 'tours', 'as' => 'tours.'], function (): void {
        Route::get('', 'Botble\Tours\Http\Controllers\VendorTourController@index')->name('index');
        Route::get('create', 'Botble\Tours\Http\Controllers\VendorTourController@create')->name('create');
        Route::post('', 'Botble\Tours\Http\Controllers\VendorTourController@store')->name('store');
        Route::get('{tour}/edit', 'Botble\Tours\Http\Controllers\VendorTourController@edit')->name('edit');
        Route::put('{tour}', 'Botble\Tours\Http\Controllers\VendorTourController@update')->name('update');
        Route::delete('{tour}', 'Botble\Tours\Http\Controllers\VendorTourController@destroy')->name('destroy');
        Route::get('{tour}/duplicate', 'Botble\Tours\Http\Controllers\VendorTourController@duplicate')->name('duplicate');
    });

    // Tour Bookings routes
    Route::group(['prefix' => 'tour-bookings', 'as' => 'tour-bookings.'], function (): void {
        Route::get('', 'Botble\Tours\Http\Controllers\VendorTourBookingController@index')->name('index');
        Route::get('{booking}', 'Botble\Tours\Http\Controllers\VendorTourBookingController@show')->name('show');
        Route::get('{booking}/edit', 'Botble\Tours\Http\Controllers\VendorTourBookingController@edit')->name('edit');
        Route::put('{booking}', 'Botble\Tours\Http\Controllers\VendorTourBookingController@update')->name('update');
        Route::delete('{booking}', 'Botble\Tours\Http\Controllers\VendorTourBookingController@destroy')->name('destroy');
        
        // Status update routes
        Route::post('{booking}/status', 'Botble\Tours\Http\Controllers\VendorTourBookingController@updateStatus')->name('update-status');
        Route::post('{booking}/payment-status', 'Botble\Tours\Http\Controllers\VendorTourBookingController@updatePaymentStatus')->name('update-payment-status');
    });
});
