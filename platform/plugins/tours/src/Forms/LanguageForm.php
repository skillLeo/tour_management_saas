<?php

namespace Botble\Tours\Forms;

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\StatusField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Tours\Http\Requests\LanguageRequest;
use Botble\Tours\Models\Language;

class LanguageForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Language::class)
            ->setValidatorClass(LanguageRequest::class)
            ->add('name', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::languages.form.name'))
                ->required()
                ->toArray()
            )
            ->add('code', TextField::class, TextFieldOption::make()
                ->label(trans('plugins/tours::languages.form.code'))
                ->required()
                ->placeholder('en, ar, fr, etc.')
                ->toArray()
            )
            ->add('flag', MediaImageField::class, MediaImageFieldOption::make()
                ->label(trans('plugins/tours::languages.form.flag'))
                ->toArray()
            )
            ->add('order', TextField::class, TextFieldOption::make()
                ->label(trans('core/base::forms.order'))
                ->defaultValue(0)
                ->toArray()
            )
            ->add('status', StatusField::class, StatusFieldOption::make()->toArray())
            ->setBreakFieldPoint('status');
    }
}
