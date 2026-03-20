<?php

namespace Botble\Tours\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Tours\Forms\TourCityForm;
use Botble\Tours\Http\Requests\TourCityRequest;
use Botble\Tours\Repositories\Interfaces\TourCityInterface;
use Botble\Tours\Tables\TourCityTable;
use Exception;
use Illuminate\Http\Request;

class TourCityController extends BaseController
{
    // Define the constant in the controller
    const TOUR_CITIES_MODULE_SCREEN_NAME = 'tour-cities';
    public function __construct(protected TourCityInterface $tourCityRepository)
    {
    }

    public function index(TourCityTable $table)
    {
        PageTitle::setTitle(trans('plugins/tours::tour-cities.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('plugins/tours::tour-cities.create'));

        return $formBuilder->create(TourCityForm::class)->renderForm();
    }

    public function store(TourCityRequest $request, BaseHttpResponse $response)
    {
        $tourCity = $this->tourCityRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(self::TOUR_CITIES_MODULE_SCREEN_NAME, $request, $tourCity));

        return $response
            ->setPreviousUrl(route('tour-cities.index'))
            ->setNextUrl(route('tour-cities.edit', $tourCity->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $tourCity = $this->tourCityRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $tourCity));

        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $tourCity->name]));

        return $formBuilder->create(TourCityForm::class, ['model' => $tourCity])->renderForm();
    }

    public function update(int|string $id, TourCityRequest $request, BaseHttpResponse $response)
    {
        $tourCity = $this->tourCityRepository->findOrFail($id);

        $tourCity->fill($request->input());

        $this->tourCityRepository->createOrUpdate($tourCity);

        event(new UpdatedContentEvent(self::TOUR_CITIES_MODULE_SCREEN_NAME, $request, $tourCity));

        return $response
            ->setPreviousUrl(route('tour-cities.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $tourCity = $this->tourCityRepository->findOrFail($id);

            $this->tourCityRepository->delete($tourCity);

            event(new DeletedContentEvent(self::TOUR_CITIES_MODULE_SCREEN_NAME, $request, $tourCity));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $tourCity = $this->tourCityRepository->findOrFail($id);
            $this->tourCityRepository->delete($tourCity);
            event(new DeletedContentEvent(self::TOUR_CITIES_MODULE_SCREEN_NAME, $request, $tourCity));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
