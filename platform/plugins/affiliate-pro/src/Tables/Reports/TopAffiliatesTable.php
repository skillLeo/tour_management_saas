<?php

namespace Botble\AffiliatePro\Tables\Reports;

use Botble\AffiliatePro\Models\Affiliate;
use Botble\Base\Facades\Html;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopAffiliatesTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Affiliate::class)
            ->setType(self::TABLE_TYPE_SIMPLE)
            ->setOption('id', 'table-top-affiliates')
            ->setOption('class', 'table-report-table')
            ->setOption('card_title', trans('plugins/affiliate-pro::reports.top_affiliates'))
            ->setOption('filters', [
                'affiliate_code' => [
                    'title' => trans('plugins/affiliate-pro::affiliate.affiliate_code'),
                    'type' => 'text',
                    'validate' => 'required|max:120',
                ],
            ])
            ->setView($this->simpleTableView())
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('customer_id')
                    ->title(trans('plugins/affiliate-pro::affiliate.customer'))
                    ->searchable(false)
                    ->orderable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();
                        if (! $item->customer) {
                            return '&mdash;';
                        }

                        return Html::link(route('customers.edit', $item->customer->id), $item->customer->name);
                    }),
                Column::make('affiliate_code')
                    ->title(trans('plugins/affiliate-pro::affiliate.affiliate_code'))
                    ->searchable(),
                FormattedColumn::make('period_commission')
                    ->title(trans('plugins/affiliate-pro::reports.period_commission'))
                    ->searchable(false)
                    ->orderable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        return format_price($column->getItem()->period_commission ?? 0);
                    }),
                FormattedColumn::make('total_commission')
                    ->title(trans('plugins/affiliate-pro::affiliate.total_commission'))
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        return format_price($column->getItem()->total_commission);
                    }),
                FormattedColumn::make('balance')
                    ->title(trans('plugins/affiliate-pro::affiliate.balance'))
                    ->searchable(false)
                    ->getValueUsing(function (FormattedColumn $column) {
                        return format_price($column->getItem()->balance);
                    }),
            ])
            ->queryUsing(function (Builder $query) {
                [$startDate, $endDate] = EcommerceHelper::getDateRangeInReport(request());

                return $query
                    ->select([
                        'affiliates.id',
                        'affiliates.customer_id',
                        'affiliates.affiliate_code',
                        'affiliates.balance',
                        'affiliates.total_commission',
                        DB::raw('SUM(affiliate_commissions.amount) as period_commission'),
                    ])
                    ->join('affiliate_commissions', function ($join) use ($startDate, $endDate) {
                        $join->on('affiliates.id', '=', 'affiliate_commissions.affiliate_id')
                            ->whereDate('affiliate_commissions.created_at', '>=', $startDate)
                            ->whereDate('affiliate_commissions.created_at', '<=', $endDate);
                    })
                    ->groupBy([
                        'affiliates.id',
                        'affiliates.customer_id',
                        'affiliates.affiliate_code',
                        'affiliates.balance',
                        'affiliates.total_commission',
                    ])
                    ->orderBy('period_commission', 'desc');
            });
    }
}
