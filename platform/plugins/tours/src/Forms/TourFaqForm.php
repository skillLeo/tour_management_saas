<?php

namespace Botble\Tours\Forms;

use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\HiddenFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\HiddenField;
use Botble\Base\Forms\FormAbstract;
use Botble\Tours\Http\Requests\TourFaqRequest;
use Botble\Tours\Models\TourFaq;

class TourFaqForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(TourFaq::class)
            ->setValidatorClass(TourFaqRequest::class)
            ->add('tour_id', HiddenField::class, HiddenFieldOption::make())
            ->add('question', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/tours::tours.faq.question'))
                ->required()
                ->rows(3)
            )
            ->add('answer', EditorField::class, EditorFieldOption::make()
                ->label(trans('plugins/tours::tours.faq.answer'))
                ->required()
                ->rows(4)
            )
            ->add('order', NumberField::class, NumberFieldOption::make()
                ->label(trans('plugins/tours::tours.faq.order'))
                ->defaultValue(0)
                ->min(0)
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
} 