@extends('statamic::layout')
@section('title', 'Customers')

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>

    <div class="flex mb-3">
        <h1 class="flex-1">Customers</h1>

        <a href="{{ cp_route('customers.create') }}" class="btn btn-primary">Create Customer</a>
    </div>

    <commerce-listing
            model="customers"
            cols='{{ json_encode([
            [
                'label' => 'Name',
                'field' => 'title',
            ],
            [
                'label' => 'Email',
                'field' => 'email'
            ]
        ]) }}'
            items='@json($customers)'
            primary='title'
    />
@endsection
