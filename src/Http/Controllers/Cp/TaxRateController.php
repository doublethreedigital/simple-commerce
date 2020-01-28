<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Controllers\Cp;

use DoubleThreeDigital\SimpleCommerce\Models\TaxRate;
use Illuminate\Http\Request;
use Statamic\Http\Controllers\CP\CpController;

class TaxRateController extends CpController
{
    public function index()
    {
        if (! auth()->user()->hasPermission('edit settings') && auth()->user()->isSuper() != true) {
            abort(401);
        }

        return TaxRate::with('country', 'state')->get();
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermission('edit settings') && auth()->user()->isSuper() != true) {
            abort(401);
        }

        // TODO: setup a validation request

        $rate = new TaxRate();
        $rate->name = $request->name;
        $rate->country_id = $request->country[0];
        $rate->state_id = $request->state[0] ?? 1;
        $rate->start_of_zip_code = $request->start_of_zip_code ?? '?';
        $rate->rate = $request->rate;
        $rate->save();

        return $rate;
    }

    public function update(TaxRate $rate, Request $request)
    {
        if (! auth()->user()->hasPermission('edit settings') && auth()->user()->isSuper() != true) {
            abort(401);
        }

        // TODO: update the tax rate

        // TODO: return back the tax rate
    }

    public function destroy(TaxRate $rate)
    {
        if (! auth()->user()->hasPermission('edit settings') && auth()->user()->isSuper() != true) {
            abort(401);
        }

        $rate->delete();

        return redirect(route('settings.edit'))
            ->with('success', 'Deleted tax rate');
    }
}