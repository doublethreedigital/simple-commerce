@extends('statamic::layout')
@section('title', 'Sales Report')

@section('content')

    <header class="mb-3 flex items-center justify-between">
        <h1>Sales Report</h1>

        <div>
            <select class="text-xs bg-white px-2 py-1 rounded-sm">
                <option value="sevenDays">7 Days</option>
                <option value="fourteenDays">14 Days</option>
                <option value="thirtyDays">30 Days</option>
            </select>
        </div>
    </header>

    <div class="card p-2 content mb-4">
        <div class="flex flex-wrap -mx-2 mb-4">
            <div class="px-4 py-2 w-full">
                @include('simple-commerce::cp.reports.live-chart', ['data' => $salesPastMonth])
            </div>


            <!-- <div class="w-full px-2">
                <p class="mb-4">sales in the last month</p>
                <div class="px-1">
                    @include('simple-commerce::cp.reports.live-chart', ['data' => $salesPastMonth])
                </div>
            </div> -->

            <!-- <div class="w-1/3 px-2">
                <p class="mb-4">sales in the last week</p>
                <div class="px-1">
                    @include('simple-commerce::cp.reports.live-chart', ['data' => $salesPastWeek])
                </div>
            </div>

            <div class="w-1/3 px-2">
                <p class="mb-4">sales in the last day</p>
                <div class="px-1">
                    @include('simple-commerce::cp.reports.live-chart', ['data' => $salesPastDay])
                </div>
            </div> -->
        </div>
    </div>

    <div class="card p-0 mb-4">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Summary</th>
                    <th>Sales</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesPastMonth as $key => [$value, $label])
                    <tr>
                        <td><a href="#">{{ $label }} - {{ $value }}</a></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
