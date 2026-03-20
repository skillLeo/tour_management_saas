<?php

namespace Botble\Tours\Forms;

use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Tours\Http\Requests\TourBookingRequest;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourBooking;
use Botble\Tours\Models\TourTimeSlot;

class TourBookingForm extends FormAbstract
{
    public function setup(): void
    {
        $tours = Tour::query()
            ->where('status', 'published')
            ->where('allow_booking', true)
            ->pluck('name', 'id')
            ->all();

        // Get time slots for the selected tour (if editing)
        $timeSlots = [];
        if ($this->getModel() && $this->getModel()->tour_id) {
            // Get all available time slots for this tour
            $allTimeSlots = TourTimeSlot::query()
                ->where('tour_id', $this->getModel()->tour_id)
                ->where('status', 'available')
                ->orderBy('start_time')
                ->get();
                
            // Get the selected time slot IDs from the booking
            $selectedSlotIds = [];
            
            // Handle time_slot_ids (JSON string or array)
            if (!empty($this->getModel()->time_slot_ids)) {
                if (is_string($this->getModel()->time_slot_ids)) {
                    $decodedIds = json_decode($this->getModel()->time_slot_ids, true);
                    if (is_array($decodedIds)) {
                        $selectedSlotIds = $decodedIds;
                    }
                } elseif (is_array($this->getModel()->time_slot_ids)) {
                    $selectedSlotIds = $this->getModel()->time_slot_ids;
                }
            }
            
            // Handle single time_slot_id if present
            if (!empty($this->getModel()->time_slot_id)) {
                if (is_numeric($this->getModel()->time_slot_id)) {
                    $selectedSlotIds[] = (int)$this->getModel()->time_slot_id;
                }
            }
            
            // Ensure $selectedSlotIds is always an array
            if (!is_array($selectedSlotIds)) {
                $selectedSlotIds = [];
            }
            
            // Create the time slots array for the dropdown
            $timeSlots = $allTimeSlots
                ->mapWithKeys(function ($slot) use ($selectedSlotIds) {
                    $tourDate = $this->getModel()->tour_date ?? date('Y-m-d');
                    
                    // Safe check for in_array with proper type casting
                    $slotId = (int)$slot->id;
                    $isSelected = false;
                    
                    // Only check if $selectedSlotIds is an array
                    if (is_array($selectedSlotIds) && !empty($selectedSlotIds)) {
                        // Convert all values to integers for consistent comparison
                        $numericSelectedSlotIds = array_map('intval', $selectedSlotIds);
                        $isSelected = in_array($slotId, $numericSelectedSlotIds, true);
                    }
                    
                    $label = $tourDate . ' - ' . $slot->start_time->format('H:i') . ' to ' . $slot->end_time->format('H:i');
                    
                    // Mark selected slots
                    if ($isSelected) {
                        $label .= ' (Selected)';
                    }
                    
                    return [
                        $slot->id => $label
                    ];
                })
                ->all();
        }

        $this
            ->model(TourBooking::class)
            ->setValidatorClass(TourBookingRequest::class)
            ->add('tour_id', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.tour'))
                ->required()
                ->choices(['' => trans('plugins/tours::tours.select_tour')] + $tours)
            )
            ->add('time_slot_ids', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.time_slot'))
                ->choices(['' => trans('plugins/tours::tour-bookings.form.select_time_slot')] + $timeSlots)
                ->helperText(trans('plugins/tours::tour-bookings.form.time_slot_help') . ' (Selected time slots are marked)')
            )
            ->add('booking_code', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.booking_code'))
                ->maxLength(50)
                ->disabled()
            )
            ->add('customer_name', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.customer_name'))
                ->required()
                ->maxLength(255)
            )
            ->add('customer_email', EmailField::class, EmailFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.customer_email'))
                ->required()
                ->maxLength(255)
            )
            ->add('customer_phone', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.customer_phone'))
                ->maxLength(20)
            )
            ->add('customer_nationality', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tours.Spoken Language'))
                ->maxLength(100)
            )
            ->add('customer_address', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.customer_address'))
                ->rows(3)
            )
            ->add('adults', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.adults'))
                ->required()
                ->defaultValue(1)
                ->min(1)
            )
            ->add('children', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.children'))
                ->defaultValue(0)
                ->min(0)
            )
            ->add('infants', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.infants'))
                ->defaultValue(0)
                ->min(0)
            )
            ->add('booking_date', DatePickerField::class, DatePickerFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.booking_date'))
                ->required()
            )
            ->add('total_amount', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.total_amount'))
                ->required()
                ->step(0.01)
                ->min(0)
            )

            ->add('payment_status', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.payment_status'))
                ->choices([
                    'pending' => trans('plugins/tours::tour-bookings.payment_status.pending'),
                    'paid' => trans('plugins/tours::tour-bookings.payment_status.paid'),
                    'failed' => trans('plugins/tours::tour-bookings.payment_status.failed'),
                    'refunded' => trans('plugins/tours::tour-bookings.payment_status.refunded'),
                ])
                ->defaultValue('pending')
            )
            ->add('status', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.booking_status'))
                ->choices([
                    'pending' => trans('plugins/tours::tour-bookings.booking_status.pending'),
                    'confirmed' => trans('plugins/tours::tour-bookings.booking_status.confirmed'),
                    'cancelled' => trans('plugins/tours::tour-bookings.booking_status.cancelled'),
                    'completed' => trans('plugins/tours::tour-bookings.booking_status.completed'),
                ])
                ->defaultValue('pending')
            )
            ->add('special_requests', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.special_requests'))
                ->rows(4)
            )
            ->add('notes', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/tours::tour-bookings.form.notes'))
                ->rows(4)
            );
    }
}