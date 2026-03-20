<?php

namespace Botble\Tours\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CheckboxColumn;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Tours\Models\TourReview;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TourReviewTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(TourReview::class)
            ->addActions([
                EditAction::make()->route('tour-reviews.edit'),
                DeleteAction::make()->route('tour-reviews.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'tour_id',
                'rating',
                'review',
                'customer_name',
                'customer_email',
                'is_approved',
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
                ->title(trans('plugins/tours::tour-reviews.form.tour'))
                ->alignLeft(),
            Column::make('customer_name')
                ->title(trans('plugins/tours::tour-reviews.form.customer_name'))
                ->alignLeft(),
            Column::make('customer_email')
                ->title(trans('plugins/tours::tour-reviews.form.customer_email'))
                ->alignLeft(),
            Column::make('rating')
                ->title(trans('plugins/tours::tour-reviews.form.rating'))
                ->alignCenter()
                ->width(100)
                ->renderUsing(function (TourReview $item) {
                    $stars = '';
                    $rating = $item->rating;
                    $fullStars = floor($rating);
                    $halfStar = ($rating - $fullStars) >= 0.5;
                    
                    for ($i = 0; $i < $fullStars; $i++) {
                        $stars .= '<i class="fas fa-star text-warning"></i>';
                    }
                    
                    if ($halfStar) {
                        $stars .= '<i class="fas fa-star-half-alt text-warning"></i>';
                    }
                    
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    for ($i = 0; $i < $emptyStars; $i++) {
                        $stars .= '<i class="far fa-star text-muted"></i>';
                    }
                    
                    return $stars . ' <span class="ms-1">(' . $rating . ')</span>';
                }),
            Column::make('review')
                ->title(trans('plugins/tours::tour-reviews.form.review'))
                ->alignLeft()
                ->width(300)
                ->renderUsing(function (TourReview $item) {
                    return $item->review ? Html::entities(Str::limit($item->review, 100)) : '—';
                }),
            Column::make('is_approved')
                ->title(trans('plugins/tours::tour-reviews.form.is_approved'))
                ->alignCenter()
                ->width(100)
                ->renderUsing(function (TourReview $item) {
                    return $item->is_approved 
                        ? '<span class="badge badge-success">' . trans('core/base::base.yes') . '</span>'
                        : '<span class="badge badge-warning">' . trans('core/base::base.no') . '</span>';
                }),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('tour-reviews.create'), 'tour-reviews.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('tour-reviews.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            StatusBulkChange::make(),
        ];
    }

    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
} 