@extends('statamic::layout')
@section('title', 'Sales Report')

@section('content')

    <sales-report
        :sales-past-month='@json($salesPastMonth)'
        :sales-past-week='@json($salesPastWeek)'
        :sales-past-day='@json($salesPastDay)'
    >
        <template #day>
            @include('simple-commerce::cp.reports.live-chart', ['data' => $salesPastDay])
        </template>

        <template #week>
            @include('simple-commerce::cp.reports.live-chart', ['data' => $salesPastWeek])
        </template>

        <template #month>
            @include('simple-commerce::cp.reports.live-chart', ['data' => $salesPastMonth])
        </template>
    </sales-report>

@endsection
