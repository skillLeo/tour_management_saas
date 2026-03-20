<?php

namespace Botble\Tours\Tables;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Tours\Models\TourEnquiry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;


class TourEnquiryTable extends TableAbstract
{
    public function setup(): void
    {
        $this->model(TourEnquiry::class)
            ->addActions([
                DeleteAction::make()->route('tour-enquiries.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()
            ->query()
            ->select([
                'id',
                'tour_id',
                'customer_name',
                'customer_email',
                'subject',
                'created_at',
            ])
            ->with(['tour:id,name']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('tour.name')
                ->title(trans('plugins/tours::tour-bookings.form.tour'))
                ->alignLeft(),
            Column::make('customer_name')
                ->title('Customer Name')
                ->alignLeft(),
            Column::make('customer_email')
                ->title('Customer Email')
                ->alignLeft(),
            Column::make('subject')
                ->title('Subject')
                ->alignLeft(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return [];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('tour-enquiries.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [];
    }

    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
}