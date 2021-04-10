<?php

namespace DoubleThreeDigital\SimpleCommerce\Filters;

use Carbon\Carbon;
use Statamic\Query\Scopes\Filter;

class OrderDateFiltering extends Filter
{
    public function fieldItems()
    {
        return [
            'start' => [
                'type' => 'date',
                'display' => 'Start',
            ],
            'end' => [
                'type' => 'date',
                'display' => 'End',
            ],
        ];
    }

    public function autoApply()
    {
        return [];
    }

    public function apply($query, $values)
    {
        $values['start'] = '1st April 2021';
        $values['end'] = '31st April 2021';

        $query
            ->where('is_paid', true)
            ->whereDate('paid_date', '>=', Carbon::parse($values['start']))
            ->whereDate('paid_date', '<=', Carbon::parse($values['end']));
    }

    public function badge($values)
    {
        $values['start'] = '1st April 2021';
        $values['end'] = '31st April 2021';

        $startDate = Carbon::parse($values['start']);
        $endDate = Carbon::parse($values['end']);

        return "Time Period: {$startDate->format('jS F')} - {$endDate->format('jS F')} {$endDate->format('Y')}";
    }

    public function visibleTo($key)
    {
        return $key === 'entries'
            && $this->context['collection'] === config('simple-commerce.collections.orders');
    }
}
