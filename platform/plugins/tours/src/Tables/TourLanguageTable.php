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
use Botble\Tours\Models\TourLanguage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class TourLanguageTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(TourLanguage::class)
            ->addActions([
                EditAction::make()
                    ->route('tour-languages.edit'),
                DeleteAction::make()
                    ->route('tour-languages.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('flag', function (TourLanguage $item) {
                if ($item->flag) {
                    return Html::image(
                        RvMedia::getImageUrl($item->flag, 'thumb', false, RvMedia::getDefaultImage()),
                        $item->name,
                        ['width' => 50]
                    );
                }

                return '&mdash;';
            })
            ->editColumn('code', function (TourLanguage $item) {
                return '<span class="badge bg-primary">' . $item->code . '</span>';
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
                ->title(trans('plugins/tours::tour-languages.form.flag'))
                ->width(70),
            NameColumn::make(),
            Column::make('code')
                ->title(trans('plugins/tours::tour-languages.form.code'))
                ->width(100),
            Column::make('order')
                ->title(trans('core/base::tables.order'))
                ->width(100),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('tour-languages.create'), 'tour-languages.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('tour-languages.destroy'),
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
            'code' => [
                'title' => trans('plugins/tours::tour-languages.form.code'),
                'type' => 'text',
                'validate' => 'required|max:10',
            ],
            'order' => [
                'title' => trans('core/base::tables.order'),
                'type' => 'number',
                'validate' => 'required|integer|min:0',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
