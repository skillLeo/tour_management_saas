<?php

namespace Botble\AffiliatePro\Http\Controllers;

use Botble\AffiliatePro\Forms\AffiliateLevelForm;
use Botble\AffiliatePro\Http\Requests\AffiliateLevelRequest;
use Botble\AffiliatePro\Models\AffiliateLevel;
use Botble\AffiliatePro\Tables\AffiliateLevelTable;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;

class AffiliateLevelController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/affiliate-pro::level.menu_name'), route('affiliate-pro.levels.index'));
    }

    public function index(AffiliateLevelTable $table)
    {
        $this->pageTitle(trans('plugins/affiliate-pro::level.menu_name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/affiliate-pro::level.create'));

        return AffiliateLevelForm::create()->renderForm();
    }

    public function store(AffiliateLevelRequest $request, BaseHttpResponse $response)
    {
        AffiliateLevel::query()->create($request->input());

        return $response
            ->setPreviousUrl(route('affiliate-pro.levels.index'))
            ->setNextUrl(route('affiliate-pro.levels.create'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(AffiliateLevel $level)
    {
        $this->pageTitle(trans('plugins/affiliate-pro::level.edit', ['name' => $level->name]));

        return AffiliateLevelForm::createFromModel($level)->renderForm();
    }

    public function update(AffiliateLevel $level, AffiliateLevelRequest $request, BaseHttpResponse $response)
    {
        $level->fill($request->input());
        $level->save();

        return $response
            ->setPreviousUrl(route('affiliate-pro.levels.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(AffiliateLevel $level, DeleteResourceAction $action)
    {
        return $action->handle($level, 'affiliate-pro.levels.index');
    }
}
