<?php

namespace Botble\Tours\Tables;

use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Tours\Models\TourBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class TourBookingTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(TourBooking::class)
            ->addActions([
                EditAction::make()->route('tour-bookings.edit'),
                DeleteAction::make()->route('tour-bookings.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'booking_code',
                'tour_id',
                'customer_name',
                'customer_email',
                'customer_phone',
                'adults',
                'children',
                'infants',
                'tour_date',
                'total_amount',
                'payment_status',
                'status',
                'created_at',
            ])
            ->with(['tour']);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('booking_code')
                ->title(trans('plugins/tours::tour-bookings.form.booking_code'))
                ->searchable()
                ->orderable(),
            Column::make('tour.name')
                ->title(trans('plugins/tours::tour-bookings.form.tour'))
                ->searchable(false)
                ->orderable(false),
            Column::make('customer_name')
                ->title(trans('plugins/tours::tour-bookings.form.customer_name'))
                ->searchable()
                ->orderable(),
            Column::make('customer_email')
                ->title(trans('plugins/tours::tour-bookings.form.customer_email'))
                ->searchable()
                ->orderable(),
            Column::make('customer_phone')
                ->title(trans('plugins/tours::tour-bookings.form.customer_phone')),
            Column::make('adults')
                ->title(trans('plugins/tours::tour-bookings.form.number_of_people'))
                ->width(100)
                ->renderUsing(function (TourBooking $item) {
                    return $item->adults + $item->children + $item->infants;
                }),
            Column::make('tour_date')
                ->title(trans('plugins/tours::tour-bookings.form.booking_date'))
                ->type('date'),
            Column::make('total_amount')
                ->title(trans('plugins/tours::tour-bookings.form.total_amount'))
                ->alignRight()
                ->renderUsing(function (TourBooking $item) {
                    return format_price($item->total_amount);
                }),
            Column::make('payment_status')
                ->title(trans('plugins/tours::tour-bookings.form.payment_status'))
                ->renderUsing(function (TourBooking $item) {
                    $color = match ($item->payment_status) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'secondary',
                    };
                    
                    return Html::tag('span', 
                        trans('plugins/tours::tour-bookings.payment_status.' . $item->payment_status),
                        ['class' => 'badge bg-' . $color]
                    );
                }),
            Column::make('status')
                ->title(trans('plugins/tours::tour-bookings.form.booking_status'))
                ->renderUsing(function (TourBooking $item) {
                    $color = match ($item->status) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                        default => 'secondary',
                    };
                    
                    return Html::tag('span', 
                        trans('plugins/tours::tour-bookings.booking_status.' . $item->status),
                        ['class' => 'badge bg-' . $color]
                    );
                }),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('tour-bookings.create'), 'tour-bookings.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('tour-bookings.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            CreatedAtBulkChange::make(),
        ];
    }

    public function getFilters(): array
    {
        return [
            'payment_status' => [
                'title' => trans('plugins/tours::tour-bookings.form.payment_status'),
                'type' => 'select',
                'choices' => [
                    'pending' => trans('plugins/tours::tour-bookings.payment_status.pending'),
                    'paid' => trans('plugins/tours::tour-bookings.payment_status.paid'),
                    'failed' => trans('plugins/tours::tour-bookings.payment_status.failed'),
                    'refunded' => trans('plugins/tours::tour-bookings.payment_status.refunded'),
                ],
            ],
            'status' => [
                'title' => trans('plugins/tours::tour-bookings.form.booking_status'),
                'type' => 'select',
                'choices' => [
                    'pending' => trans('plugins/tours::tour-bookings.booking_status.pending'),
                    'confirmed' => trans('plugins/tours::tour-bookings.booking_status.confirmed'),
                    'cancelled' => trans('plugins/tours::tour-bookings.booking_status.cancelled'),
                    'completed' => trans('plugins/tours::tour-bookings.booking_status.completed'),
                ],
            ],
        ];
    }
}