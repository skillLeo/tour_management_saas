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
use Botble\Tours\Forms\LanguageForm;
use Botble\Tours\Http\Requests\LanguageRequest;
use Botble\Tours\Models\Language;
use Botble\Tours\Tables\LanguageTable;
use Exception;
use Illuminate\Http\Request;

class LanguageController extends BaseController
{
    public function index(LanguageTable $table)
    {
        PageTitle::setTitle(trans('plugins/tours::languages.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('plugins/tours::languages.create'));

        return $formBuilder->create(LanguageForm::class)->renderForm();
    }

    public function store(LanguageRequest $request, BaseHttpResponse $response)
    {
        $language = Language::query()->create($request->input());

        event(new CreatedContentEvent(LANGUAGE_MODULE_SCREEN_NAME, $request, $language));

        return $response
            ->setPreviousUrl(route('languages.index'))
            ->setNextUrl(route('languages.edit', $language->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Language $language, FormBuilder $formBuilder, Request $request)
    {
        event(new BeforeEditContentEvent($request, $language));

        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $language->name]));

        return $formBuilder->create(LanguageForm::class, ['model' => $language])->renderForm();
    }

    public function update(Language $language, LanguageRequest $request, BaseHttpResponse $response)
    {
        $language->fill($request->input());
        $language->save();

        event(new UpdatedContentEvent(LANGUAGE_MODULE_SCREEN_NAME, $request, $language));

        return $response
            ->setPreviousUrl(route('languages.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Language $language, Request $request, BaseHttpResponse $response)
    {
        try {
            $language->delete();

            event(new DeletedContentEvent(LANGUAGE_MODULE_SCREEN_NAME, $request, $language));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
