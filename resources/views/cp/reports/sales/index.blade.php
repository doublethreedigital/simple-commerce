@extends('statamic::layout')
@section('title', 'Sales Report')

@section('content')

    <header class="mb-3 flex items-center justify-between">
        <h1>Sales Report</h1>

        <div class="flex items-center">
            <div class="select-input-container w-32">
                <select class="select-input">
                <option value="sevenDays">7 Days</option>
                <option value="fourteenDays">14 Days</option>
                <option value="thirtyDays">30 Days</option>
                </select>
                <div class="select-input-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                    </svg>
                </div>
            </div>

            <button class="btn-primary ml-2">Export</button>
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
                @foreach($salesPastMonth as $key => [$salesCount, $label, $total])
                    <tr>
                        <td><a href="#">{{ $label }}</a></td>
                        <td>{{ $salesCount }} sales</td>
                        <td>{{ \DoubleThreeDigital\SimpleCommerce\Facades\Currency::parse($total, \Statamic\Facades\Site::current()) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
