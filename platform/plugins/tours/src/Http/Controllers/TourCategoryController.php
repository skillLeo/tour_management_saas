<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Tours\Forms\TourCategoryForm;
use Botble\Tours\Http\Requests\TourCategoryRequest;
use Botble\Tours\Models\TourCategory;
use Botble\Tours\Repositories\Interfaces\TourCategoryInterface;
use Botble\Tours\Tables\TourCategoryTable;
use Botble\SeoHelper\Facades\SeoHelper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TourCategoryController extends BaseController
{
    public function __construct(protected TourCategoryInterface $tourCategoryRepository)
    {
    }

    public function index(TourCategoryTable $table)
    {
        $this->pageTitle(trans('plugins/tours::tour-categories.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        $this->pageTitle(trans('plugins/tours::tour-categories.create'));

        return $formBuilder->create(TourCategoryForm::class)->renderForm();
    }

    public function store(TourCategoryRequest $request, BaseHttpResponse $response)
    {
        $tourCategory = $this->tourCategoryRepository->createOrUpdate($request->input());
        
        // Save SEO metadata
        SeoHelper::saveMetaData(TOUR_CATEGORY_MODULE_SCREEN_NAME, $request, $tourCategory);

        event(new CreatedContentEvent(TOUR_CATEGORY_MODULE_SCREEN_NAME, $request, $tourCategory));

        return $response
            ->setPreviousUrl(route('tour-categories.index'))
            ->setNextUrl(route('tour-categories.edit', $tourCategory->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function show(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $tourCategory = $this->tourCategoryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $tourCategory));

        return view('plugins/tours::tour-categories.show', compact('tourCategory'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $tourCategory = $this->tourCategoryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $tourCategory));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $tourCategory->name]));

        return $formBuilder->create(TourCategoryForm::class, ['model' => $tourCategory])->renderForm();
    }

    public function update(int|string $id, TourCategoryRequest $request, BaseHttpResponse $response)
    {
        $tourCategory = $this->tourCategoryRepository->findOrFail($id);

        $tourCategory->fill($request->input());

        $this->tourCategoryRepository->createOrUpdate($tourCategory);
        
        // Save SEO metadata
        SeoHelper::saveMetaData(TOUR_CATEGORY_MODULE_SCREEN_NAME, $request, $tourCategory);

        event(new UpdatedContentEvent(TOUR_CATEGORY_MODULE_SCREEN_NAME, $request, $tourCategory));

        return $response
            ->setPreviousUrl(route('tour-categories.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $tourCategory = $this->tourCategoryRepository->findOrFail($id);
            
            // Delete SEO metadata
            SeoHelper::deleteMetaData(TOUR_CATEGORY_MODULE_SCREEN_NAME, $tourCategory);

            $this->tourCategoryRepository->delete($tourCategory);

            event(new DeletedContentEvent(TOUR_CATEGORY_MODULE_SCREEN_NAME, $request, $tourCategory));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        
        $categories = $this->tourCategoryRepository
            ->getModel()
            ->where('name', 'LIKE', '%' . $query . '%')
            ->where('status', 'published')
            ->limit(10)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                ];
            });

        return response()->json([
            'results' => $categories,
        ]);
    }
}