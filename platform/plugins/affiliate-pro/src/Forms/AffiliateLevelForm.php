<?php

namespace Botble\AffiliatePro\Forms;

use Botble\AffiliatePro\Http\Requests\AffiliateLevelRequest;
use Botble\AffiliatePro\Models\AffiliateLevel;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;

class AffiliateLevelForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(AffiliateLevel::class)
            ->setValidatorClass(AffiliateLevelRequest::class)
            ->add(
                'name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
                    ->toArray()
            )
            ->add(
                'min_commission',
                NumberField::class,
                [
                    'label' => trans('plugins/affiliate-pro::level.min_commission'),
                    'required' => true,
                    'attr' => [
                        'placeholder' => 0,
                        'step' => 0.01,
                    ],
                ]
            )
            ->add(
                'max_commission',
                NumberField::class,
                [
                    'label' => trans('plugins/affiliate-pro::level.max_commission'),
                    'help_block' => [
                        'text' => trans('plugins/affiliate-pro::level.max_commission_help'),
                    ],
                    'attr' => [
                        'placeholder' => trans('plugins/affiliate-pro::level.unlimited'),
                        'step' => 0.01,
                    ],
                ]
            )
            ->add(
                'commission_rate',
                NumberField::class,
                [
                    'label' => trans('plugins/affiliate-pro::level.commission_rate'),
                    'required' => true,
                    'default_value' => 1,
                    'attr' => [
                        'step' => 0.01,
                        'placeholder' => 1.0,
                    ],
                    'help_block' => [
                        'text' => trans('plugins/affiliate-pro::level.commission_rate_help'),
                    ],
                ]
            )
            ->add(
                'benefits',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/affiliate-pro::level.benefits'))
                    ->helperText(trans('plugins/affiliate-pro::level.benefits_help'))
                    ->placeholder(trans('plugins/affiliate-pro::level.benefits_placeholder'))
                    ->rows(4)
                    ->toArray()
            )
            ->add(
                'order',
                NumberField::class,
                [
                    'label' => trans('core/base::forms.order'),
                    'default_value' => 0,
                ]
            )
            ->add(
                'status',
                SelectField::class,
                StatusFieldOption::make()->toArray()
            )
            ->setBreakFieldPoint('status');
    }
}
