<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Controllers\CP;

use Carbon\Carbon;
use DoubleThreeDigital\SimpleCommerce\Facades\Order;
use Illuminate\Support\Collection;
use Statamic\Http\Controllers\CP\CpController;

class SalesReportController extends CpController
{
    public function index()
    {
        $orders = collect(Order::all())->filter(function ($order) {
            return $order->get('is_paid') === true;
        })->map(function ($order) {
            return Carbon::parse($order->get('paid_date'))->timestamp;
        })->values();

        return view('simple-commerce::cp.reports.sales.index', [
            'salesPastMonth' => $this->getStatsPastMonth($orders),
            'salesPastWeek'  => $this->getStatsPastWeek($orders),
            'salesPastDay'   => $this->getStatsPastDay($orders),
        ]);
    }

    protected function getStatsPastMonth(Collection $orders)
    {
        $days = [];

        for ($day = now()->subWeeks(4); $day < now(); $day->addWeek()) {
            $days[] = $day->copy();
        }

        $salesPastMonth = [];

        foreach ($days as $day) {
            $count = $orders->filter(function ($timestamp) use ($day) {
                return Carbon::parse($timestamp)->isSameWeek($day);
            })->count();

            $salesPastMonth[] = [$count, "{$day->startOfWeek()->format('d')}-{$day->endOfWeek()->format('d')}"];
        }

        return $salesPastMonth;
    }

    protected function getStatsPastWeek(Collection $orders)
    {
        $days = [];

        for ($day = now()->subWeek(); $day < now(); $day->addWeek()) {
            $days[] = $day->copy();
        }

        $salesPastWeek = [];

        foreach ($days as $day) {
            $count = $orders->filter(function ($timestamp) use ($day) {
                $date = Carbon::parse($timestamp);

                return $date->isSameYear($day) && $date->isSameMonth($day) && $date->isSameDay($day);
            })->count();

            $salesPastWeek[] = [$count, $day->format('d')];
        }

        return $salesPastWeek;
    }

    protected function getStatsPastDay(Collection $orders)
    {
        $hours = [];

        for ($hour = now()->subDay(); $hour < now(); $hour->addHours(4)) {
            $hours[] = $hour->copy();
        }

        $salesPastDay = [];

        foreach ($hours as $hour) {
            $count = $orders->filter(function ($timestamp) use ($hour) {
                return Carbon::parse($timestamp)->isBetween($hour->copy()->subHour(), $hour->copy()->addHours(3));
            })->count();

            $salesPastDay[] = [$count, $hour->format('H:00')];
        }

        return $salesPastDay;
    }
}
