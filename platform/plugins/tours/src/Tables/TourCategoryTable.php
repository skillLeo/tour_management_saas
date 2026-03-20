<?php

namespace Botble\Tours\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Tours\Models\TourCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class TourCategoryTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(TourCategory::class)
            ->addActions([
                EditAction::make()->route('tour-categories.edit'),
                DeleteAction::make()->route('tour-categories.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'slug',
                'image',
                'order',
                'status',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            ImageColumn::make('image')
                ->title(trans('plugins/tours::tour-categories.form.image'))
                ->width(70),
            NameColumn::make('name')
                ->route('tour-categories.edit')
                ->title(trans('plugins/tours::tour-categories.form.name')),
            Column::make('slug')
                ->title(trans('plugins/tours::tour-categories.form.slug')),
            Column::make('order')
                ->title(trans('plugins/tours::tour-categories.form.order'))
                ->width(100),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('tour-categories.create'), 'tour-categories.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('tour-categories.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            StatusBulkChange::make(),
            CreatedAtBulkChange::make(),
        ];
    }

    public function getFilters(): array
    {
        return [
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
        ];
    }
} 