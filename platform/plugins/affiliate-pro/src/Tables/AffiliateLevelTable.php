<?php

namespace Botble\AffiliatePro\Tables;

use Botble\AffiliatePro\Models\AffiliateLevel;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;

class AffiliateLevelTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(AffiliateLevel::class)
            ->addActions([
                EditAction::make()->route('affiliate-pro.levels.edit'),
                DeleteAction::make()->route('affiliate-pro.levels.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('affiliate-pro.levels.destroy'),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'min_commission',
                        'max_commission',
                        'commission_rate',
                        'created_at',
                        'status',
                    ]);
            });
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('affiliate-pro.levels.edit'),
            Column::make('min_commission')->title(trans('plugins/affiliate-pro::level.min_commission')),
            FormattedColumn::make('max_commission')
                ->title(trans('plugins/affiliate-pro::level.max_commission'))
                ->getValueUsing(function (FormattedColumn $column) {
                    $value = $column->getItem()->max_commission;

                    return $value ?? '∞';
                }),
            Column::make('commission_rate')->title(trans('plugins/affiliate-pro::level.commission_rate')),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('affiliate-pro.levels.create'), 'affiliate-pro.levels.create');
    }
}
