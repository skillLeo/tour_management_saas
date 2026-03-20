<?php

namespace Botble\Tours\Forms;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\MediaImagesField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\RepeaterField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\FormAbstract;
use Botble\Tours\Http\Requests\TourRequest;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourCategory;
use Botble\Tours\Models\TourCity;
use Botble\Tours\Models\TourLanguage;
use Botble\Media\Facades\RvMedia;

class TourForm extends FormAbstract
{
    public function setup(): void
    {
        $tourCategories = TourCategory::query()
            ->where('status', 'published')
            ->pluck('name', 'id')
            ->all() ?: [];
            
        $tourCities = TourCity::query()
            ->where('status', 'published')
            ->pluck('name', 'id')
            ->all() ?: [];
            
        $tourLanguages = TourLanguage::query()
            ->where('status', 'published')
            ->pluck('name', 'id')
            ->all() ?: [];

        // Ensure all variables are arrays
        $tourCategories = is_array($tourCategories) ? $tourCategories : [];
        $tourCities = is_array($tourCities) ? $tourCities : [];
        $tourLanguages = is_array($tourLanguages) ? $tourLanguages : [];

        $this
            ->model(Tour::class)
            ->setValidatorClass(TourRequest::class)
            ->add('name', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tours.form.name'))
                ->required()
                ->maxLength(255)
            )

            ->add('description', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/tours::tours.form.description'))
                ->rows(4)
            )
            ->add('content', EditorField::class, EditorFieldOption::make()
                ->label(trans('plugins/tours::tours.form.content'))
            )
            ->add('image', MediaImageField::class, MediaImageFieldOption::make()
                ->label(trans('plugins/tours::tours.form.image'))
            )
            ->add('gallery[]', MediaImagesField::class, [
                'label' => trans('plugins/tours::tours.form.gallery'),
                'values' => $this->getModel() ? $this->getModel()->gallery : [],
            ])
            ->add('category_id', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/tours::tours.form.category'))
                ->choices(['' => trans('plugins/tours::tour-categories.select_category')] + $tourCategories)
                ->required()
            )
            ->add('city_id', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/tours::tour-cities.city'))
                ->choices(['' => trans('plugins/tours::tour-cities.select_city')] + $tourCities)
            )
            ->add('tour_type', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/tours::tours.form.tour_type'))
                ->choices([
                    'shared' => trans('plugins/tours::tours.form.tour_types.shared'),
                    'private' => trans('plugins/tours::tours.form.tour_types.private'),
                    'transfer' => trans('plugins/tours::tours.form.tour_types.transfer'),
                    'small_group' => trans('plugins/tours::tours.form.tour_types.small_group'),
                ])
                ->searchable()
                ->emptyValue(trans('plugins/tours::tours.form.select_tour_type'))
            )
            ->add('tour_length', SelectField::class, SelectFieldOption::make()
                ->label(trans('plugins/tours::tours.form.tour_length'))
                ->choices([
                    'half_day' => trans('plugins/tours::tours.form.tour_lengths.half_day'),
                    'full_day' => trans('plugins/tours::tours.form.tour_lengths.full_day'),
                ])
                ->searchable()
                ->emptyValue(trans('plugins/tours::tours.form.select_tour_length'))
            )
            ->add('duration_days', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.duration_days'))
                ->defaultValue(0)
                ->min(0)
                ->helperText(trans('plugins/tours::tours.form.duration_days_help'))
            )
            ->add('duration_hours', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.duration_hours'))
                ->defaultValue(0)
                ->min(0)
                ->helperText(trans('plugins/tours::tours.form.duration_hours_help'))
            )
            ->add('duration_nights', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.duration_nights'))
                ->defaultValue(0)
                ->min(0)
            )
            ->add('max_people', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.max_people'))
                ->required()
                ->defaultValue(10)
                ->min(1)
            )
            ->add('min_people', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.min_people'))
                ->required()
                ->defaultValue(1)
                ->min(1)
            )
            ->add('price', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.price'))
                ->required()
                ->step(0.01)
                ->min(0)
                ->helperText(trans('plugins/tours::tours.form.adult_price_help'))
            )
            ->add('children_price', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.children_price'))
                ->step(0.01)
                ->min(0)
                ->helperText(trans('plugins/tours::tours.form.children_price_help'))
            )
            ->add('infants_price', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.infants_price'))
                ->step(0.01)
                ->min(0)
                ->helperText(trans('plugins/tours::tours.form.infants_price_help'))
            )
            ->add('sale_percentage', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.sale_percentage'))
                ->step(0.01)
                ->min(0)
                ->max(100)
                ->helperText(trans('plugins/tours::tours.form.sale_percentage_help'))
            )

            ->add('location', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tours.form.location'))
                ->maxLength(255)
            )
            ->add('departure_location', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tours.form.departure_location'))
                ->maxLength(255)
            )

            ->add('return_location', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tours.form.return_location'))
                ->maxLength(255)
            )

            ->add('latitude', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.latitude'))
                ->step(0.00000001)
            )
            ->add('longitude', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.longitude'))
                ->step(0.00000001)
            )
            ->add('included_services', TextareaField::class, [
                'label' => trans('plugins/tours::tours.form.included_services'),
                'help_block' => [
                    'text' => trans('plugins/tours::tours.form.services_help_comma'),
                ],
                'rows' => 3,
                'value' => $this->getModel() && !empty($this->getModel()->included_services) 
                    ? (is_array($this->getModel()->included_services) 
                        ? implode(',', $this->getModel()->included_services) 
                        : $this->getModel()->included_services)
                    : '',
            ])
            ->add('excluded_services', TextareaField::class, [
                'label' => trans('plugins/tours::tours.form.excluded_services'),
                'help_block' => [
                    'text' => trans('plugins/tours::tours.form.services_help_comma'),
                ],
                'rows' => 3,
                'value' => $this->getModel() && !empty($this->getModel()->excluded_services) 
                    ? (is_array($this->getModel()->excluded_services) 
                        ? implode(',', $this->getModel()->excluded_services) 
                        : $this->getModel()->excluded_services)
                    : '',
            ])
            ->add('activities', TextareaField::class, [
                'label' => trans('plugins/tours::tours.form.activities'),
                'help_block' => [
                    'text' => trans('plugins/tours::tours.form.services_help_comma'),
                ],
                'rows' => 3,
                'value' => $this->getModel() && !empty($this->getModel()->activities) 
                    ? (is_array($this->getModel()->activities) 
                        ? implode(',', $this->getModel()->activities) 
                        : $this->getModel()->activities)
                    : '',
            ])
            ->add('tour_highlights', TextareaField::class, [
                'label' => trans('plugins/tours::tours.form.tour_highlights'),
                'help_block' => [
                    'text' => trans('plugins/tours::tours.form.services_help_comma'),
                ],
                'rows' => 3,
                'value' => $this->getModel() && !empty($this->getModel()->tour_highlights) 
                    ? (is_array($this->getModel()->tour_highlights) 
                        ? implode(',', $this->getModel()->tour_highlights) 
                        : $this->getModel()->tour_highlights)
                    : '',
            ])
            ->add('is_featured', CheckboxField::class, CheckboxFieldOption::make()
                ->label(trans('plugins/tours::tours.form.is_featured'))
                ->defaultValue(false)
            )
            ->add('allow_booking', CheckboxField::class, CheckboxFieldOption::make()
                ->label(trans('plugins/tours::tours.form.allow_booking'))
                ->defaultValue(true)
            )
            ->add('booking_advance_days', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.form.booking_advance_days'))
                ->defaultValue(1)
                ->min(0)
            )
            ->add('languages[]', SelectField::class, [
                'label' => trans('plugins/tours::tour-languages.languages'),
                'choices' => $tourLanguages,
                'value' => $this->getModel() ? ($this->getModel()->languages->pluck('id')->all() ?: []) : [],
                'attr' => [
                    'class' => 'form-control select-multiple',
                    'multiple' => 'multiple',
                ],
                'help_block' => [
                    'text' => trans('plugins/tours::tour-languages.available_languages'),
                ],
            ])

            ->add('status', SelectField::class, StatusFieldOption::make())
            ->add('tour_faqs_section', HtmlField::class, [
                'html' => $this->getFaqsSection(),
            ])
            ->add('tour_places_section', HtmlField::class, [
                'html' => $this->getPlacesSection(),
            ])
            ->add('tour_schedules_section', HtmlField::class, [
                'html' => $this->getSchedulesSection(),
            ])
            ->add('tour_time_slots_section', HtmlField::class, [
                'html' => $this->getTimeSlotsSection(),
            ])
            ->setBreakFieldPoint('status');
    }

    protected function getFaqsSection(): string
    {
        $tour = $this->getModel();
        $tourId = $tour?->id;
        
        if (!$tourId) {
            return '<div class="form-group mb-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> ' . trans('plugins/tours::tours.faq.save_tour_first') . '
                </div>
            </div>';
        }

        $faqs = $tour->faqs ?? collect();
        
        $html = '<div class="form-group mb-3">
            <label class="control-label">
                <span>' . trans('plugins/tours::tours.faq.title') . '</span>
            </label>
            <div id="tour-faqs-container">';
            
        foreach ($faqs as $index => $faq) {
            $html .= '
                <div class="faq-item border rounded p-3 mb-3" data-index="' . $index . '" style="background: #f8f9fa;">
                    <input type="hidden" name="faqs[' . $index . '][id]" value="' . $faq->id . '">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">' . trans('plugins/tours::tours.faq.question') . '</label>
                        <textarea name="faqs[' . $index . '][question]" class="form-control" rows="2" required placeholder="أدخل السؤال هنا...">' . e($faq->question) . '</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">' . trans('plugins/tours::tours.faq.answer') . '</label>
                        <textarea name="faqs[' . $index . '][answer]" class="form-control" rows="4" required placeholder="أدخل الإجابة هنا...">' . e($faq->answer) . '</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.faq.order') . '</label>
                            <input type="number" name="faqs[' . $index . '][order]" class="form-control" value="' . $faq->order . '" min="0" placeholder="الترتيب">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-faq w-100">
                                <i class="fas fa-trash"></i> ' . trans('plugins/tours::tours.faq.remove') . '
                            </button>
                        </div>
                    </div>
                </div>';
        }
        
        $html .= '
            </div>
            <button type="button" id="add-faq" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> ' . trans('plugins/tours::tours.faq.add') . '
            </button>
        </div>
        
        <script>
        $(document).ready(function() {
            var faqIndex = ' . $faqs->count() . ';
            
            $("#add-faq").click(function() {
                var faqHtml = `
                    <div class="faq-item border rounded p-3 mb-3" data-index="${faqIndex}" style="background: #f8f9fa;">
                        <div class="mb-3">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.faq.question') . '</label>
                            <textarea name="faqs[${faqIndex}][question]" class="form-control" rows="2" required placeholder="أدخل السؤال هنا..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.faq.answer') . '</label>
                            <textarea name="faqs[${faqIndex}][answer]" class="form-control" rows="4" required placeholder="أدخل الإجابة هنا..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">' . trans('plugins/tours::tours.faq.order') . '</label>
                                <input type="number" name="faqs[${faqIndex}][order]" class="form-control" value="0" min="0" placeholder="الترتيب">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-faq w-100">
                                    <i class="fas fa-trash"></i> ' . trans('plugins/tours::tours.faq.remove') . '
                                </button>
                            </div>
                        </div>
                    </div>`;
                $("#tour-faqs-container").append(faqHtml);
                faqIndex++;
            });
            
            $(document).on("click", ".remove-faq", function() {
                $(this).closest(".faq-item").remove();
            });
        });
        </script>';
        
        return $html;
    }

    protected function getTimeSlotsSection(): string
    {
        $tour = $this->getModel();
        $tourId = $tour?->id;
        
        if (!$tourId) {
            return '<div class="form-group mb-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> ' . trans('plugins/tours::tours.time_slots.save_tour_first') . '
                </div>
            </div>';
        }

        $timeSlots = $tour->timeSlots ?? collect();
        
        $days = [
            'sunday' => 'Sunday', 
            'monday' => 'Monday', 
            'tuesday' => 'Tuesday', 
            'wednesday' => 'Wednesday', 
            'thursday' => 'Thursday', 
            'friday' => 'Friday', 
            'saturday' => 'Saturday'
        ];
        
        $html = '<div class="form-group mb-3">
            <label class="control-label">
                <span>' . trans('plugins/tours::tours.time_slots.title') . '</span>
            </label>
            <div id="tour-time-slots-container">';
            
        foreach ($timeSlots as $index => $slot) {
            $availableDaysLabel = $slot->getAvailableDaysLabel();
            $html .= '
                <div class="time-slot-item border rounded p-3 mb-3" data-index="' . $index . '" style="background: #f0f8ff;">
                    <input type="hidden" name="time_slots[' . $index . '][id]" value="' . $slot->id . '">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.time_slots.start_time') . '</label>
                            <input type="time" name="time_slots[' . $index . '][start_time]" class="form-control" value="' . $slot->start_time->format('H:i') . '" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.time_slots.order') . '</label>
                            <input type="number" name="time_slots[' . $index . '][order]" class="form-control" value="' . $slot->order . '" min="0" placeholder="الترتيب">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.time_slots.restricted_days') . '</label>
                            <div class="d-flex flex-wrap">';
                            
            foreach ($days as $dayValue => $dayLabel) {
                $isChecked = in_array($dayValue, $slot->restricted_days ?? []) ? 'checked' : '';
                $html .= '
                                <div class="form-check form-check-inline mr-3">
                                    <input class="form-check-input time-slot-restricted-day" 
                                           type="checkbox" 
                                           name="time_slots[' . $index . '][restricted_days][]"
                                           id="day-' . $dayValue . '-' . $index . '" 
                                           value="' . $dayValue . '"
                                           ' . $isChecked . '>
                                    <label class="form-check-label" for="day-' . $dayValue . '-' . $index . '">
                                        ' . $dayLabel . '
                                    </label>
                                </div>';
            }
            
            $html .= '
                            </div>
                            <small class="form-text text-muted">' . 
                                trans('plugins/tours::tours.time_slots.available_days_help', [
                                    'days' => $availableDaysLabel
                                ]) . 
                            '</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm remove-time-slot">
                                <i class="fas fa-trash"></i> ' . trans('plugins/tours::tours.time_slots.remove') . '
                            </button>
                        </div>
                    </div>
                </div>';
        }
        
        $html .= '
            </div>
            <button type="button" class="btn btn-success btn-sm" id="add-time-slot">
                <i class="fas fa-plus"></i> ' . trans('plugins/tours::tours.time_slots.add') . '
            </button>
        </div>';

        $html .= '<script>
        $(document).ready(function() {
            let timeSlotIndex = ' . $timeSlots->count() . ';
            
            $("#add-time-slot").click(function() {
                const days = ' . json_encode($days) . ';
                const newSlot = `
                <div class="time-slot-item border rounded p-3 mb-3" data-index="${timeSlotIndex}" style="background: #f0f8ff;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.time_slots.start_time') . '</label>
                            <input type="time" name="time_slots[${timeSlotIndex}][start_time]" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.time_slots.order') . '</label>
                            <input type="number" name="time_slots[${timeSlotIndex}][order]" class="form-control" value="${timeSlotIndex}" min="0">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">' . trans('plugins/tours::tours.time_slots.restricted_days') . '</label>
                            <div class="d-flex flex-wrap">
                                ${Object.entries(days).map(([dayValue, dayLabel]) => `
                                    <div class="form-check form-check-inline mr-3">
                                        <input class="form-check-input time-slot-restricted-day" 
                                               type="checkbox" 
                                               name="time_slots[${timeSlotIndex}][restricted_days][]"
                                               id="day-${dayValue}-${timeSlotIndex}" 
                                               value="${dayValue}">
                                        <label class="form-check-label" for="day-${dayValue}-${timeSlotIndex}">
                                            ${dayLabel}
                                        </label>
                                    </div>
                                `).join("")}
                            </div>
                            <small class="form-text text-muted">' . 
                                trans('plugins/tours::tours.time_slots.available_days_help', [
                                    'days' => implode(', ', array_keys($days))
                                ]) . 
                            '</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm remove-time-slot">
                                <i class="fas fa-trash"></i> ' . trans('plugins/tours::tours.time_slots.remove') . '
                            </button>
                        </div>
                    </div>
                </div>`;
                
                $("#tour-time-slots-container").append(newSlot);
                timeSlotIndex++;
            });
            
            $(document).on("click", ".remove-time-slot", function() {
                $(this).closest(".time-slot-item").remove();
            });
        });
        </script>';
        
        return $html;
    }



    protected function getPlacesSection(): string
    {
        $tour = $this->getModel();
        $tourId = $tour?->id;
        
        if (!$tourId) {
            return '<div class="form-group mb-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Please save the tour first to add places.
                </div>
            </div>';
        }

        $places = $tour->places ?? collect();
        
        $html = '<div class="form-group mb-3">
            <label class="control-label">
                <span>Places You\'ll See</span>
            </label>
            <div id="tour-places-container">';
            
        foreach ($places as $index => $place) {
            $html .= '
                <div class="place-item border rounded p-3 mb-3" data-index="' . $index . '" style="background: #f8f9fa;">
                    <input type="hidden" name="places[' . $index . '][id]" value="' . $place->id . '">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Place Name</label>
                        <input type="text" name="places[' . $index . '][name]" class="form-control" value="' . e($place->name) . '" required placeholder="Enter place name...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Place Image</label>
                        <div class="image-box">
                            <input type="text" name="places[' . $index . '][image]" class="form-control image-data" value="' . e($place->image) . '">
                            <div class="preview-image-wrapper" style="margin-top: 10px; ' . ($place->image ? '' : 'display: none;') . '">
                                <img src="' . RvMedia::getImageUrl($place->image, 'thumb') . '" alt="preview" class="preview-image" style="max-width: 150px;">
                                <a class="btn_remove_image" data-toggle="tooltip" data-placement="bottom" title="Remove image" data-bb-toggle="image-picker-remove" style="position: absolute; top: 5px; right: 5px; background: rgba(255,0,0,0.8); color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 12px;">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                            <div class="image-box-actions">
                                <a href="#" class="btn btn-primary btn-sm btn_gallery" data-action="select-image">
                                    <i class="fa fa-image me-1"></i> Choose image
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Order</label>
                            <input type="number" name="places[' . $index . '][order]" class="form-control" value="' . $place->order . '" min="0" placeholder="Order">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-place w-100">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>';
        }
        
        $html .= '
            </div>
            <button type="button" id="add-place" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Place
            </button>
        </div>
        
        <script>
        $(document).ready(function() {
            var placeIndex = ' . $places->count() . ';
            
            $("#add-place").click(function() {
                var placeHtml = `
                <div class="place-item border rounded p-3 mb-3" data-index="${placeIndex}" style="background: #f8f9fa;">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Place Name</label>
                        <input type="text" name="places[${placeIndex}][name]" class="form-control" required placeholder="Enter place name...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Place Image</label>
                        <div class="image-box">
                            <input type="text" name="places[${placeIndex}][image]" class="form-control image-data">
                            <div class="preview-image-wrapper" style="display: none; margin-top: 10px;">
                                <img src="" alt="preview" class="preview-image" style="max-width: 150px;">
                                <a class="btn_remove_image" data-toggle="tooltip" data-placement="bottom" title="Remove image" data-bb-toggle="image-picker-remove" style="position: absolute; top: 5px; right: 5px; background: rgba(255,0,0,0.8); color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 12px;">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                            <div class="image-box-actions">
                                <a href="#" class="btn btn-primary btn-sm btn_gallery" data-action="select-image">
                                    <i class="fa fa-image me-1"></i> Choose image
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Order</label>
                            <input type="number" name="places[${placeIndex}][order]" class="form-control" value="0" min="0" placeholder="Order">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-place w-100">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>`;
                
                $("#tour-places-container").append(placeHtml);
                placeIndex++;
                
                // Re-initialize btn_gallery for the new element
                Botble.initMediaIntegrate();
            });
            
            $(document).on("click", ".remove-place", function() {
                $(this).closest(".place-item").remove();
            });
            
            // Handle image removal
            $(document).on("click", "[data-bb-toggle=\"image-picker-remove\"]", function(e) {
                e.preventDefault();
                const $imageBox = $(this).closest(".image-box");
                $imageBox.find(".image-data").val("");
                $imageBox.find(".preview-image-wrapper").hide();
            });
        });
        </script>';
        
        return $html;
    }

    protected function getSchedulesSection(): string
    {
        $tour = $this->getModel();
        $tourId = $tour?->id;
        
        if (!$tourId) {
            return '<div class="form-group mb-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Please save the tour first to add schedule details.
                </div>
            </div>';
        }

        $schedules = $tour->schedules ?? collect();
        
        $html = '<div class="form-group mb-3">
            <label class="control-label">
                <span>' . trans('plugins/tours::tours.form.schedule_details') . '</span>
            </label>
            <div class="help-block">' . trans('plugins/tours::tours.form.schedules_help') . '</div>
            <div id="tour-schedules-container">';
            
        foreach ($schedules as $index => $schedule) {
            $html .= '
                <div class="schedule-item border rounded p-3 mb-3" data-index="' . $index . '" style="background: #f8f9fa;">
                    <input type="hidden" name="schedules[' . $index . '][id]" value="' . $schedule->id . '">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">' . trans('plugins/tours::tours.form.schedule_title') . '</label>
                        <input type="text" name="schedules[' . $index . '][title]" class="form-control" value="' . e($schedule->title) . '" required placeholder="Enter schedule title...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">' . trans('plugins/tours::tours.form.schedule_description') . '</label>
                        <textarea name="schedules[' . $index . '][description]" class="form-control" rows="4" required placeholder="Enter schedule description...">' . e($schedule->description) . '</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Order</label>
                            <input type="number" name="schedules[' . $index . '][order]" class="form-control" value="' . $schedule->order . '" min="0" placeholder="Order">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-schedule w-100">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>';
        }
        
        $html .= '
            </div>
            <button type="button" id="add-schedule" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Schedule
            </button>
        </div>
        
        <script>
        $(document).ready(function() {
            var scheduleIndex = ' . $schedules->count() . ';
            
            $("#add-schedule").click(function() {
                var scheduleHtml = `
                <div class="schedule-item border rounded p-3 mb-3" data-index="${scheduleIndex}" style="background: #f8f9fa;">
                    <div class="mb-3">
                        <label class="form-label fw-bold">' . trans('plugins/tours::tours.form.schedule_title') . '</label>
                        <input type="text" name="schedules[${scheduleIndex}][title]" class="form-control" required placeholder="Enter schedule title...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">' . trans('plugins/tours::tours.form.schedule_description') . '</label>
                        <textarea name="schedules[${scheduleIndex}][description]" class="form-control" rows="4" required placeholder="Enter schedule description..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Order</label>
                            <input type="number" name="schedules[${scheduleIndex}][order]" class="form-control" value="0" min="0" placeholder="Order">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-schedule w-100">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>`;
                
                $("#tour-schedules-container").append(scheduleHtml);
                scheduleIndex++;
            });
            
            $(document).on("click", ".remove-schedule", function() {
                $(this).closest(".schedule-item").remove();
            });
        });
        </script>';
        
        return $html;
    }
}