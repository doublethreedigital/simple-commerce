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
        });

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
            $sales = $orders->filter(function ($order) use ($day) {
                return Carbon::parse($order->get('paid_date'))->isSameWeek($day);
            });

            $totals = $sales->map(function ($order) {
                return ['grand_total' => $order->data()->get('grand_total')];
            })->values();

            $salesPastMonth[] = [
                $sales->count(),
                "{$day->startOfWeek()->format('d')}-{$day->endOfWeek()->format('d')}",
                $totals->sum('grand_total'),
            ];
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
            $sales = $orders->filter(function ($order) use ($day) {
                $date = Carbon::parse($order->get('paid_date'));

                return $date->isSameYear($day) && $date->isSameMonth($day) && $date->isSameDay($day);
            });

            $totals = $sales->map(function ($order) {
                return ['grand_total' => $order->data()->get('grand_total')];
            })->values();

            $salesPastWeek[] = [
                $sales->count(),
                $day->format('d'),
                $totals->sum('grand_total'),
            ];
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
            $sales = $orders->filter(function ($order) use ($hour) {
                return Carbon::parse($order->get('paid_date'))->isBetween($hour->copy()->subHour(), $hour->copy()->addHours(3));
            });

            $totals = $sales->map(function ($order) {
                return ['grand_total' => $order->data()->get('grand_total')];
            })->values();

            $salesPastDay[] = [
                $sales->count(),
                $hour->format('H:00'),
                $totals->sum('grand_total'),
            ];
        }

        return $salesPastDay;
    }
}
