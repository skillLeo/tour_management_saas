<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Tours\Forms\TourBookingForm;
use Botble\Tours\Http\Requests\TourBookingRequest;
use Botble\Tours\Models\TourBooking;
use Botble\Tours\Repositories\Interfaces\TourBookingInterface;
use Botble\Tours\Tables\TourBookingTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourBookingController extends BaseController
{
    public function __construct(protected TourBookingInterface $tourBookingRepository)
    {
    }

    public function index(TourBookingTable $table)
    {
        $this->pageTitle(trans('plugins/tours::tour-bookings.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        $this->pageTitle(trans('plugins/tours::tour-bookings.create'));

        return $formBuilder->create(TourBookingForm::class)->renderForm();
    }

    public function store(TourBookingRequest $request, BaseHttpResponse $response)
    {
        $data = $request->input();
        
        // Generate booking code if not provided
        if (empty($data['booking_code'])) {
            $data['booking_code'] = 'TB-' . strtoupper(Str::random(8));
        }

        $tourBooking = $this->tourBookingRepository->createOrUpdate($data);

        event(new CreatedContentEvent(TOUR_BOOKING_MODULE_SCREEN_NAME, $request, $tourBooking));

        return $response
            ->setPreviousUrl(route('tour-bookings.index'))
            ->setNextUrl(route('tour-bookings.edit', $tourBooking->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function show(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $tourBooking = $this->tourBookingRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $tourBooking));

        return view('plugins/tours::tour-bookings.show', compact('tourBooking'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $tourBooking = $this->tourBookingRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $tourBooking));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $tourBooking->booking_code]));

        return $formBuilder->create(TourBookingForm::class, ['model' => $tourBooking])->renderForm();
    }

    public function update(int|string $id, TourBookingRequest $request, BaseHttpResponse $response)
    {
        $tourBooking = $this->tourBookingRepository->findOrFail($id);

        $tourBooking->fill($request->input());

        $this->tourBookingRepository->createOrUpdate($tourBooking);

        event(new UpdatedContentEvent(TOUR_BOOKING_MODULE_SCREEN_NAME, $request, $tourBooking));

        return $response
            ->setPreviousUrl(route('tour-bookings.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $tourBooking = $this->tourBookingRepository->findOrFail($id);

            $this->tourBookingRepository->delete($tourBooking);

            event(new DeletedContentEvent(TOUR_BOOKING_MODULE_SCREEN_NAME, $request, $tourBooking));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function confirm(int|string $id, BaseHttpResponse $response)
    {
        try {
            $tourBooking = $this->tourBookingRepository->findOrFail($id);
            
            $tourBooking->status = 'confirmed';
            $this->tourBookingRepository->createOrUpdate($tourBooking);

            return $response->setMessage(trans('plugins/tours::tour-bookings.booking_confirmed'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function cancel(int|string $id, BaseHttpResponse $response)
    {
        try {
            $tourBooking = $this->tourBookingRepository->findOrFail($id);
            
            $tourBooking->status = 'cancelled';
            $this->tourBookingRepository->createOrUpdate($tourBooking);

            return $response->setMessage(trans('plugins/tours::tour-bookings.booking_cancelled'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
} 