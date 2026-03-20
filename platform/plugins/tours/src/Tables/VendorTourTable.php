<?php

namespace Botble\Tours\Tables;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Tours\Models\Tour;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class VendorTourTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tour::class)
            ->addActions([
                EditAction::make()
                    ->route('marketplace.vendor.tours.edit')
                    ->icon('ti ti-edit'),
                DeleteAction::make()
                    ->route('marketplace.vendor.tours.destroy')
                    ->icon('ti ti-trash'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('tours.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                // Check if tour is published and has a slug
                if ($item->status == BaseStatusEnum::PUBLISHED && !empty($item->slug)) {
                    try {
                        $url = route('public.tours.detail', $item->slug);
                        return '<a href="' . esc_url($url) . '" target="_blank" rel="noopener" title="' . __('View tour on website') . '">' . 
                               BaseHelper::clean($item->name) . 
                               ' <i class="ti ti-external-link" style="font-size: 12px; opacity: 0.7;"></i></a>';
                    } catch (\Exception $e) {
                        // If route generation fails, fall back to plain text
                        return BaseHelper::clean($item->name) . 
                               ' <span class="badge bg-warning" style="font-size: 10px; margin-left: 5px;">' . 
                               __('URL Error') . '</span>';
                    }
                }
                
                // For draft/unpublished tours, show name without link
                return BaseHelper::clean($item->name) . 
                       ' <span class="badge bg-secondary" style="font-size: 10px; margin-left: 5px;">' . 
                       __('Not Published') . '</span>';
            })
            ->editColumn('price', function ($item) {
                return format_price($item->price);
            })
            ->editColumn('category', function ($item) {
                return $item->category ? $item->category->name : '&mdash;';
            })
            ->editColumn('duration', function ($item) {
                $duration = [];
                if ($item->duration_days > 0) {
                    $duration[] = $item->duration_days . 'd';
                }
                if ($item->duration_nights > 0) {
                    $duration[] = $item->duration_nights . 'n';
                }
                if ($item->duration_hours > 0) {
                    $duration[] = $item->duration_hours . 'h';
                }
                return implode(' ', $duration) ?: '&mdash;';
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()->query()
            ->select([
                'id',
                'name',
                'slug',
                'image',
                'price',
                'category_id',
                'duration_days',
                'duration_nights', 
                'duration_hours',
                'created_at',
                'status',
                'store_id',
            ])
            ->with(['category']);

        // Filter by vendor store
        if (auth('customer')->check() && auth('customer')->user()->store) {
            $query->where('store_id', auth('customer')->user()->store->id);
        } else {
            // Fallback to author_id if store_id not available
            $query->where('author_id', auth('customer')->id())
                  ->where('author_type', 'Botble\Ecommerce\Models\Customer');
        }

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            ImageColumn::make()
                ->title(trans('core/base::tables.image'))
                ->width(70),
            Column::make('name')
                ->title(trans('plugins/tours::tours.name'))
                ->alignStart(),
            Column::make('category')
                ->title(trans('plugins/tours::tours.category'))
                ->alignStart(),
            Column::make('duration')
                ->title(trans('plugins/tours::tours.duration'))
                ->alignStart()
                ->width(100),
            Column::make('price')
                ->title(trans('plugins/tours::tours.price'))
                ->alignStart()
                ->width(120),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('marketplace.vendor.tours.create'), 'tours.create');
    }

    public function getDefaultButtons(): array
    {
        return array_merge(parent::getDefaultButtons(), [
            'create' => [
                'link' => route('marketplace.vendor.tours.create'),
                'text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> ' . trans('plugins/tours::tours.create'),
                'class' => 'btn-primary',
            ],
        ]);
    }

    public function htmlDrawCallbackFunction(): ?string
    {
        return parent::htmlDrawCallbackFunction();
    }
}
