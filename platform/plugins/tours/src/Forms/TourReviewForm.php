<?php

namespace Botble\Tours\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Tours\Http\Requests\TourReviewRequest;
use Botble\Tours\Models\TourReview;
use Botble\Tours\Models\Tour;

class TourReviewForm extends FormAbstract
{
    public function buildForm(): void
    {
        $tours = Tour::query()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->pluck('name', 'id')
            ->toArray();

        $this
            ->setupModel(new TourReview())
            ->setValidatorClass(TourReviewRequest::class)
            ->withCustomFields()
            ->add('tour_id', 'customSelect', [
                'label' => trans('plugins/tours::tour-reviews.form.tour'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-search-full',
                    'data-placeholder' => trans('plugins/tours::tour-reviews.form.select_tour'),
                ],
                'choices' => ['' => trans('plugins/tours::tour-reviews.form.select_tour')] + $tours,
                'help_block' => [
                    'text' => trans('plugins/tours::tour-reviews.form.tour_help'),
                ],
            ])
            ->add('customer_name', 'text', [
                'label' => trans('plugins/tours::tour-reviews.form.customer_name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('plugins/tours::tour-reviews.form.customer_name_placeholder'),
                    'data-counter' => 255,
                ],
            ])
            ->add('customer_email', 'email', [
                'label' => trans('plugins/tours::tour-reviews.form.customer_email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('plugins/tours::tour-reviews.form.customer_email_placeholder'),
                    'data-counter' => 255,
                ],
            ])
            ->add('rating', 'number', [
                'label' => trans('plugins/tours::tour-reviews.form.rating'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('plugins/tours::tour-reviews.form.rating_placeholder'),
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
                'help_block' => [
                    'text' => trans('plugins/tours::tour-reviews.form.rating_help'),
                ],
            ])
            ->add('review', 'textarea', [
                'label' => trans('plugins/tours::tour-reviews.form.review'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 5,
                    'placeholder' => trans('plugins/tours::tour-reviews.form.review_placeholder'),
                    'data-counter' => 2000,
                ],
                'help_block' => [
                    'text' => trans('plugins/tours::tour-reviews.form.review_help'),
                ],
            ])
            ->add('is_approved', 'onOff', [
                'label' => trans('plugins/tours::tour-reviews.form.is_approved'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => true,
                'help_block' => [
                    'text' => trans('plugins/tours::tour-reviews.form.is_approved_help'),
                ],
            ])
            ->setBreakFieldPoint('is_approved');
    }
} 