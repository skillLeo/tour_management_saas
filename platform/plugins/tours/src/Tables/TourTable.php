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
use Botble\Tours\Models\Tour;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class TourTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tour::class)
            ->addActions([
                EditAction::make()->route('tours.edit'),
                DeleteAction::make()->route('tours.destroy'),
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
                'price',
                'duration_days',
                'duration_nights',
                'max_people',
                'location',
                'is_featured',
                'category_id',
                'status',
                'created_at',
            ])
            ->with(['category']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            ImageColumn::make('image')
                ->title(trans('plugins/tours::tours.form.image'))
                ->width(70),
            NameColumn::make('name')
                ->route('tours.edit')
                ->title(trans('plugins/tours::tours.form.name')),
            Column::make('category.name')
                ->title(trans('plugins/tours::tours.form.category'))
                ->searchable(false)
                ->orderable(false),
            Column::make('price')
                ->title(trans('plugins/tours::tours.form.price'))
                ->alignRight()
                ->renderUsing(function (Tour $item) {
                    return format_price($item->price);
                }),
            Column::make('duration_days')
                ->title(trans('plugins/tours::tours.form.duration'))
                ->renderUsing(function (Tour $item) {
                    if (!empty($item->duration_hours) && $item->duration_hours > 0) {
                        return $item->duration_hours . ' ' . trans('plugins/tours::tours.hours');
                    }
                    $duration = $item->duration_days . ' ' . trans('plugins/tours::tours.days');
                    if ($item->duration_nights > 0) {
                        $duration .= ', ' . $item->duration_nights . ' ' . trans('plugins/tours::tours.nights');
                    }
                    return $duration;
                }),
            Column::make('max_people')
                ->title(trans('plugins/tours::tours.form.max_people'))
                ->width(100),
            Column::make('location')
                ->title(trans('plugins/tours::tours.form.location')),
            Column::make('is_featured')
                ->title(trans('plugins/tours::tours.form.is_featured'))
                ->type('boolean')
                ->width(100),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('tours.create'), 'tours.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('tours.destroy'),
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
            'category_id' => [
                'title' => trans('plugins/tours::tours.form.category'),
                'type' => 'select-ajax',
                'validate' => 'required',
                'callback' => function (int|string|null $value = null): array {
                    $categorySelected = [];
                    if ($value && $category = \Botble\Tours\Models\TourCategory::query()->find($value)) {
                        $categorySelected = [$category->getKey() => $category->name];
                    }

                    return [
                        'url' => route('tour-categories.search'),
                        'selected' => $categorySelected,
                        'minimum-input' => 1,
                    ];
                },
            ],
            'is_featured' => [
                'title' => trans('plugins/tours::tours.form.is_featured'),
                'type' => 'select',
                'choices' => [
                    0 => trans('core/base::base.no'),
                    1 => trans('core/base::base.yes'),
                ],
                'validate' => 'required|in:0,1',
            ],
        ];
    }
}