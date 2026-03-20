<?php

namespace Botble\Tours\Forms;

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Tours\Http\Requests\TourCategoryRequest;
use Botble\Tours\Models\TourCategory;

class TourCategoryForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(TourCategory::class)
            ->setValidatorClass(TourCategoryRequest::class)
            ->add('name', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tour-categories.form.name'))
                ->required()
                ->maxLength(120)
            )

            ->add('description', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/tours::tour-categories.form.description'))
                ->rows(4)
                ->maxLength(400)
            )
            ->add('image', MediaImageField::class, MediaImageFieldOption::make()
                ->label(trans('plugins/tours::tour-categories.form.image'))
            )
            ->add('icon', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tour-categories.form.icon'))
                ->maxLength(60)
                ->placeholder('ti ti-map-pin')
            )
            ->add('order', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tour-categories.form.order'))
                ->defaultValue(0)
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}