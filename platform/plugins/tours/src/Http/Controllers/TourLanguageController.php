<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Tours\Forms\TourLanguageForm;
use Botble\Tours\Http\Requests\TourLanguageRequest;
use Botble\Tours\Models\TourLanguage;
use Botble\Tours\Repositories\Interfaces\TourLanguageInterface;
use Botble\Tours\Tables\TourLanguageTable;
use Exception;
use Illuminate\Http\Request;

class TourLanguageController extends BaseController
{
    public function __construct(protected TourLanguageInterface $tourLanguageRepository)
    {
    }

    public function index(TourLanguageTable $table)
    {
        $this->pageTitle(trans('plugins/tours::tour-languages.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        $this->pageTitle(trans('plugins/tours::tour-languages.create'));

        return $formBuilder->create(TourLanguageForm::class)->renderForm();
    }

    public function store(TourLanguageRequest $request, BaseHttpResponse $response)
    {
        $tourLanguage = $this->tourLanguageRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(TOUR_LANGUAGES_MODULE_SCREEN_NAME, $request, $tourLanguage));

        return $response
            ->setPreviousUrl(route('tour-languages.index'))
            ->setNextUrl(route('tour-languages.edit', $tourLanguage->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $tourLanguage = $this->tourLanguageRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $tourLanguage));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $tourLanguage->name]));

        return $formBuilder->create(TourLanguageForm::class, ['model' => $tourLanguage])->renderForm();
    }

    public function update(int|string $id, TourLanguageRequest $request, BaseHttpResponse $response)
    {
        $tourLanguage = $this->tourLanguageRepository->findOrFail($id);

        $tourLanguage->fill($request->input());

        $this->tourLanguageRepository->createOrUpdate($tourLanguage);

        event(new UpdatedContentEvent(TOUR_LANGUAGES_MODULE_SCREEN_NAME, $request, $tourLanguage));

        return $response
            ->setPreviousUrl(route('tour-languages.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $tourLanguage = $this->tourLanguageRepository->findOrFail($id);

            $this->tourLanguageRepository->delete($tourLanguage);

            event(new DeletedContentEvent(TOUR_LANGUAGES_MODULE_SCREEN_NAME, $request, $tourLanguage));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function search(Request $request, BaseHttpResponse $response)
    {
        $query = $request->input('q');

        $result = $this->tourLanguageRepository->getModel()
            ->where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('code', 'LIKE', '%' . $query . '%')
            ->select(['id', 'name'])
            ->take(10)
            ->get();

        return $response->setData($result);
    }
}
