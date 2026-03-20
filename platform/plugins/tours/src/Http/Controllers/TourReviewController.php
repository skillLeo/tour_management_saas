<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Tours\Forms\TourReviewForm;
use Botble\Tours\Http\Requests\TourReviewRequest;
use Botble\Tours\Models\TourReview;
use Botble\Tours\Tables\TourReviewTable;
use Exception;
use Illuminate\Http\Request;

class TourReviewController extends BaseController
{
    public function index(TourReviewTable $table)
    {
        page_title()->setTitle(trans('plugins/tours::tour-reviews.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/tours::tour-reviews.create'));

        return $formBuilder->create(TourReviewForm::class)->renderForm();
    }

    public function store(TourReviewRequest $request, BaseHttpResponse $response)
    {
        $tourReview = TourReview::query()->create($request->input());

        event(new CreatedContentEvent('tour-review', $request, $tourReview));

        return $response
            ->setPreviousUrl(route('tour-reviews.index'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function show(TourReview $tourReview)
    {
        page_title()->setTitle(trans('plugins/tours::tour-reviews.edit', ['name' => $tourReview->customer_name]));

        return view('plugins/tours::tour-reviews.show', compact('tourReview'));
    }

    public function edit(TourReview $tourReview, FormBuilder $formBuilder, Request $request)
    {
        event(new BeforeEditContentEvent($request, $tourReview));

        page_title()->setTitle(trans('plugins/tours::tour-reviews.edit', ['name' => $tourReview->customer_name]));

        return $formBuilder->create(TourReviewForm::class, ['model' => $tourReview])->renderForm();
    }

    public function update(TourReview $tourReview, TourReviewRequest $request, BaseHttpResponse $response)
    {
        $tourReview->fill($request->input());

        $tourReview->save();

        event(new UpdatedContentEvent('tour-review', $request, $tourReview));

        return $response
            ->setPreviousUrl(route('tour-reviews.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(TourReview $tourReview, Request $request, BaseHttpResponse $response)
    {
        try {
            $tourReview->delete();

            event(new DeletedContentEvent('tour-review', $request, $tourReview));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
} 