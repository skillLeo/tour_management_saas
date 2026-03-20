<?php

namespace Botble\Tours\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Forms\MetaBox;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Tours\Http\Requests\VendorTourRequest;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourCategory;
use Botble\Tours\Models\TourCity;
use Botble\Tours\Models\TourLanguage;
use Illuminate\Support\Facades\Action;

class VendorTourForm extends FormAbstract
{
    public function setup(): void
    {
        // Add assets
        add_action('form_after_assets', function () {
            echo '<script src="' . asset('vendor/core/plugins/tours/js/vendor-tour-form.js') . '"></script>';
            echo '<link rel="stylesheet" href="' . asset('vendor/core/plugins/tours/css/vendor-tour-form.css') . '">';
        });
        
        $currencies = get_all_currencies();
        $selectedCategories = [];
        $selectedCities = [];

        if ($this->getModel()) {
            $selectedCategories = [$this->getModel()->category_id];
            $selectedCities = [$this->getModel()->city_id];
        }

        $this
            ->model(Tour::class)
            ->setValidatorClass(VendorTourRequest::class)
            ->template(MarketplaceHelper::viewPath('vendor-dashboard.forms.base'))
            ->hasFiles()
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'description',
                EditorField::class,
                EditorFieldOption::make()
                    ->label(__('Description'))
                    ->placeholder(__('Tour description'))
                    ->rows(4)
            )
            ->add(
                'content',
                EditorField::class,
                ContentFieldOption::make()
                    ->label(__('Content'))
                    ->placeholder(__('Detailed tour content'))
            )
            ->add('image', MediaImageField::class, MediaImageFieldOption::make()->label(__('Featured Image')))
            ->add('gallery', 'mediaImages', [
                'label' => __('Gallery'),
                'values' => $this->getModel() ? $this->getModel()->gallery : [],
            ])
            ->addMetaBoxes([
                'tour_details' => [
                    'title' => __('Tour Details'),
                    'content' => view('plugins/tours::vendor.forms.tour-details', [
                        'currencies' => $currencies,
                        'tour' => $this->getModel(),
                    ])->render(),
                    'priority' => 1,
                ],
                'tour_location' => [
                    'title' => __('Location Information'),
                    'content' => view('plugins/tours::vendor.forms.tour-location', [
                        'tour' => $this->getModel(),
                    ])->render(),
                    'priority' => 2,
                ],
                'tour_services' => [
                    'title' => __('Services & Highlights'),
                    'content' => view('plugins/tours::vendor.forms.tour-services', [
                        'tour' => $this->getModel(),
                    ])->render(),
                    'priority' => 3,
                ],
                'tour_languages' => [
                    'title' => __('Languages'),
                    'content' => view('plugins/tours::vendor.forms.tour-languages', [
                        'tour' => $this->getModel(),
                    ])->render(),
                    'priority' => 3.5,
                ],
                'tour_schedule' => [
                    'title' => __('Itinerary & Schedule'),
                    'content' => view('plugins/tours::vendor.forms.tour-schedule', [
                        'tour' => $this->getModel(),
                    ])->render(),
                    'priority' => 4,
                ],
            ])
            ->add(
                'category_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Category'))
                    ->choices(TourCategory::query()->where('status', 'published')->pluck('name', 'id')->all())
                    ->searchable()
                    ->allowClear()
                    ->emptyValue(__('-- Select Category --'))
                    ->selected(old('category_id', $selectedCategories))
                    ->required()
            )
            ->add(
                'city_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('City'))
                    ->choices(TourCity::query()->where('status', 'published')->pluck('name', 'id')->all())
                    ->searchable()
                    ->allowClear()
                    ->emptyValue(__('-- Select City --'))
                    ->selected(old('city_id', $selectedCities))
                    ->required()
            )
            ->add(
                'is_featured',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(__('Is Featured'))
                    ->defaultValue(false)
            )
            ->add(
                'allow_booking',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(__('Allow Booking'))
                    ->defaultValue(true)
            )
            ->setBreakFieldPoint('category_id');
    }
}