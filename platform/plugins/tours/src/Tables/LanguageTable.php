<?php

namespace Botble\Tours\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Media\Facades\RvMedia;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Tours\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class LanguageTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Language::class)
            ->addActions([
                EditAction::make()
                    ->route('languages.edit'),
                DeleteAction::make()
                    ->route('languages.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function (Language $item) {
                return Html::link(route('languages.edit', $item->getKey()), BaseHelper::clean($item->name));
            })
            ->editColumn('flag', function (Language $item) {
                if ($item->flag) {
                    return Html::image(
                        RvMedia::getImageUrl($item->flag, 'thumb'),
                        $item->name,
                        ['width' => 50]
                    );
                }
                
                return '—';
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'code',
                'flag',
                'order',
                'created_at',
                'status',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('flag')
                ->title(trans('plugins/tours::languages.form.flag'))
                ->width(70),
            NameColumn::make(),
            Column::make('code')
                ->title(trans('plugins/tours::languages.form.code'))
                ->alignLeft(),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('languages.create'), 'languages.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('languages.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }
}
