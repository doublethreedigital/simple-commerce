@extends('statamic::layout')
@section('title', "Tax Category: {$taxCategory->updateUrl()}")
@section('wrapper_class', 'max-w-xl')

@section('content')
    <form action="{{ $taxCategory->updateUrl() }}" method="POST">
        @csrf

        @include('simple-commerce::cp.partials.breadcrumbs', [
            'title' => __('Tax Categories'),
            'url' => cp_route('simple-commerce.tax-categories.index'),
        ])

        <header class="mb-3">
            <div class="flex items-center justify-between">
                <h1>{{ $taxCategory->name() }}</h1>
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </header>

        <div class="publish-form card p-0 flex flex-wrap">
            <div class="form-group w-full">
                <label class="block mb-1">Name</label>
                <input type="text" name="name" autofocus="autofocus" class="input-text" value="{{ $taxCategory->name() }}">
            </div>

            <div class="form-group w-full">
                <label class="block mb-1">Description</label>
                <textarea name="description" cols="30" rows="5" class="input-text">{{ $taxCategory->description() }}</textarea>
            </div>
        </div>
    </form>
@endsection
