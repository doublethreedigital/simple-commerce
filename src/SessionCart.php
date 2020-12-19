<?php

namespace DoubleThreeDigital\SimpleCommerce;

use DoubleThreeDigital\SimpleCommerce\Contracts\Order;
use DoubleThreeDigital\SimpleCommerce\Facades\Order as OrderAPI;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Statamic\Facades\Site;
use Statamic\Sites\Site as ASite;

trait SessionCart
{
    protected function getSessionCartKey(): string
    {
        return Session::get(Config::get('simple-commerce.cart_key'));
    }

    protected function getSessionCart(): Order
    {
        return OrderAPI::find($this->getSessionCartKey());
    }

    protected function hasSessionCart(): bool
    {
        return Session::has(Config::get('simple-commerce.cart_key'));
    }

    protected function makeSessionCart(): Order
    {
        $cart = OrderAPI::create()
            ->site($this->guessSiteFromRequest())
            ->save();

        Session::put(config('simple-commerce.cart_key'), $cart->id);

        return $cart;
    }

    protected function getOrMakeSessionCart(): Order
    {
        if ($this->hasSessionCart()) {
            return $this->getSessionCart();
        }

        return $this->makeSessionCart();
    }

    protected function forgetSessionCart()
    {
        Session::forget(config('simple-commerce.cart_key'));
    }

    protected function guessSiteFromRequest(): ASite
    {
        if ($site = request()->get('site')) {
            return Site::get($site);
        }

        foreach (Site::all() as $site) {
            if (Str::contains(request()->url(), $site->url())) {
                return $site;
            }
        }

        if ($referer = request()->header('referer')) {
            foreach (Site::all() as $site) {
                if (Str::contains($referer, $site->url())) {
                    return $site;
                }
            }
        }

        return Site::current();
    }
}
