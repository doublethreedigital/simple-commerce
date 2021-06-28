<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Controllers\CP;

use DoubleThreeDigital\SimpleCommerce\Facades\TaxCategory;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxCategory\CreateRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxCategory\EditRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxCategory\IndexRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxCategory\StoreRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxCategory\UpdateRequest;
use Illuminate\Http\Request;
use Statamic\Facades\Stache;

class TaxCategoryController
{
    public function index(IndexRequest $request)
    {
        return view('simple-commerce::cp.tax-categories.index', [
            'taxCategories' => TaxCategory::all(),
        ]);
    }

    public function create(CreateRequest $request)
    {
        return view('simple-commerce::cp.tax-categories.create');
    }

    public function store(StoreRequest $request)
    {
        $taxCategory = TaxCategory::make()
            ->id(Stache::generateId())
            ->name($request->name)
            ->description($request->description);

        $taxCategory->save();

        return redirect($taxCategory->editUrl());
    }

    public function edit(EditRequest $request, $taxCategory)
    {
        $taxCategory = TaxCategory::find($taxCategory);

        return view('simple-commerce::cp.tax-categories.edit', [
            'taxCategory' => $taxCategory,
        ]);
    }

    public function update(UpdateRequest $request, $taxCategory)
    {
        $taxCategory = TaxCategory::find($taxCategory)
            ->name($request->name)
            ->description($request->description);

        $taxCategory->save();

        return redirect($taxCategory->editUrl());
    }

    public function destroy(Request $request, $taxCategory)
    {
        TaxCategory::find($taxCategory)->delete();

        return redirect(cp_route('simple-commerce.tax-categories.index'));
    }
}
