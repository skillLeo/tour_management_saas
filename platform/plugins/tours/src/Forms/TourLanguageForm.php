<?php

namespace Botble\Tours\Forms;

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Tours\Http\Requests\TourLanguageRequest;
use Botble\Tours\Models\TourLanguage;

class TourLanguageForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(TourLanguage::class)
            ->setValidatorClass(TourLanguageRequest::class)
            ->add('name', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tour-languages.form.name'))
                ->required()
                ->maxLength(255)
            )
            ->add('code', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::tour-languages.form.code'))
                ->required()
                ->maxLength(10)
                ->helperText(trans('plugins/tours::tour-languages.form.code_help'))
            )
            ->add('flag', MediaImageField::class, MediaImageFieldOption::make()
                ->label(trans('plugins/tours::tour-languages.form.flag'))
            )
            ->add('order', NumberField::class, NumberFieldOption::make()
                ->label(trans('core/base::forms.order'))
                ->defaultValue(0)
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
