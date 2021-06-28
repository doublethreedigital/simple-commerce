<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Controllers\CP;

use DoubleThreeDigital\SimpleCommerce\Facades\TaxCategory;
use DoubleThreeDigital\SimpleCommerce\Facades\TaxRate;
use DoubleThreeDigital\SimpleCommerce\Facades\TaxZone;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxRate\CreateRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxRate\DeleteRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxRate\EditRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxRate\IndexRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxRate\StoreRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxRate\UpdateRequest;
use Statamic\Facades\Stache;

class TaxRateController
{
    public function index(IndexRequest $request)
    {
        return view('simple-commerce::cp.tax-rates.index', [
            'taxRates' => TaxRate::all(),
            'taxCategories' => TaxCategory::all(),
        ]);
    }

    public function create(CreateRequest $request)
    {
        return view('simple-commerce::cp.tax-rates.create', [
            'taxCategory' => TaxCategory::find($request->taxCategory),
            'taxZones' => TaxZone::all(),
        ]);
    }

    public function store(StoreRequest $request)
    {
        $taxRate = TaxRate::make()
            ->id(Stache::generateId())
            ->name($request->name)
            ->rate($request->rate)
            ->category($request->category)
            ->zone($request->zone);

        $taxRate->save();

        return redirect($taxRate->editUrl());
    }

    public function edit(EditRequest $request, $taxRate)
    {
        $taxRate = TaxRate::find($taxRate);

        return view('simple-commerce::cp.tax-rates.edit', [
            'taxRate' => $taxRate,
            'taxCategories' => TaxCategory::all(),
            'taxZones' => TaxZone::all(),
        ]);
    }

    public function update(UpdateRequest $request, $taxRate)
    {
        $taxRate = TaxRate::find($taxRate)
            ->name($request->name)
            ->rate($request->rate)
            ->category($request->category)
            ->zone($request->zone);

        $taxRate->save();

        return redirect($taxRate->editUrl());
    }

    public function destroy(DeleteRequest $request, $taxRate)
    {
        TaxRate::find($taxRate)->delete();

        return redirect(cp_route('simple-commerce.tax-rates.index'));
    }
}
