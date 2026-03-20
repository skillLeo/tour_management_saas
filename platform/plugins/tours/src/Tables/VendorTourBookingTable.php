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

class VendorTourBookingTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(TourBooking::class)
            ->addActions([
                EditAction::make()->route('marketplace.vendor.tour-bookings.edit'),
                DeleteAction::make()->route('marketplace.vendor.tour-bookings.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $customer = auth('customer')->user();
        $storeId = $customer->store->id;

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
                'currency',
                'payment_status',
                'status',
                'created_at',
            ])
            ->with(['tour'])
            ->where('store_id', $storeId);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('booking_code')
                ->title(__('Booking Code'))
                ->searchable()
                ->orderable(),
            Column::make('tour.name')
                ->title(__('Tour'))
                ->searchable(false)
                ->orderable(false),
            Column::make('customer_name')
                ->title(__('Customer'))
                ->searchable()
                ->orderable(),
            Column::make('customer_email')
                ->title(__('Email'))
                ->searchable()
                ->orderable(),
            Column::make('customer_phone')
                ->title(__('Phone')),
            Column::make('adults')
                ->title(__('People'))
                ->width(100)
                ->renderUsing(function (TourBooking $item) {
                    return $item->adults + $item->children + $item->infants;
                }),
            Column::make('tour_date')
                ->title(__('Tour Date'))
                ->type('date'),
            Column::make('total_amount')
                ->title(__('Amount'))
                ->alignRight()
                ->renderUsing(function (TourBooking $item) {
                    return format_price($item->total_amount);
                }),
            Column::make('payment_status')
                ->title(__('Payment'))
                ->renderUsing(function (TourBooking $item) {
                    $color = match ($item->payment_status) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'secondary',
                    };
                    
                    return Html::tag('span', 
                        ucfirst($item->payment_status),
                        ['class' => 'badge bg-' . $color]
                    );
                }),
            Column::make('status')
                ->title(__('Status'))
                ->renderUsing(function (TourBooking $item) {
                    $color = match ($item->status) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                        default => 'secondary',
                    };
                    
                    return Html::tag('span', 
                        ucfirst($item->status),
                        ['class' => 'badge bg-' . $color]
                    );
                }),
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
                'title' => __('Payment Status'),
                'type' => 'select',
                'choices' => [
                    'pending' => __('Pending'),
                    'paid' => __('Paid'),
                    'failed' => __('Failed'),
                    'refunded' => __('Refunded'),
                ],
            ],
            'status' => [
                'title' => __('Booking Status'),
                'type' => 'select',
                'choices' => [
                    'pending' => __('Pending'),
                    'confirmed' => __('Confirmed'),
                    'cancelled' => __('Cancelled'),
                    'completed' => __('Completed'),
                ],
            ],
        ];
    }
}
