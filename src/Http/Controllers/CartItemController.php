<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Controllers;

use DoubleThreeDigital\SimpleCommerce\Facades\Product;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CartItem\DestroyRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CartItem\StoreRequest;
use DoubleThreeDigital\SimpleCommerce\Http\Requests\CartItem\UpdateRequest;
use DoubleThreeDigital\SimpleCommerce\Orders\Cart\Drivers\CartDriver;
use Illuminate\Support\Arr;

class CartItemController extends BaseActionController
{
    use CartDriver;

    protected $reservedKeys = [
        'product', 'quantity', 'variant', '_token', '_params', '_redirect',
    ];

    public function store(StoreRequest $request)
    {
        $cart = $this->hasCart() ? $this->getCart() : $this->makeCart();
        $product = Product::find($request->product);

        $items = $cart->has('items') ? $cart->get('items') : [];

        // Ensure there's enough stock to fulfill the customer's quantity
        if ($product->has('stock') && $product->get('stock') !== null && $product->get('stock') < $request->quantity) {
            return $this->withErrors($request, __("There's not enough stock to fulfil the quantity you selected. Please try again later."));
        }

        // Ensure the product doesn't already exist in the cart
        $alreadyExistsQuery = collect($items);

        if ($request->has('variant')) {
            $alreadyExistsQuery = $alreadyExistsQuery->where('variant', [
                'variant' => $request->get('variant'),
                'product' => $request->get('product'),
            ]);
        } else {
            $alreadyExistsQuery = $alreadyExistsQuery->where('product', $request->product);
        }

        if ($alreadyExistsQuery->count() >= 1) {
            $cart->updateLineItem($alreadyExistsQuery->first()['id'], [
                'quantity' => (int) $alreadyExistsQuery->first()['quantity'] + $request->quantity,
            ]);
        } else {
            $item = [
                'product'  => $request->product,
                'quantity' => (int) $request->quantity,
                'total'    => 0000,
            ];

            if ($request->has('variant')) {
                $item['variant'] = [
                    'variant' => $request->variant,
                    'product' => $request->product,
                ];
            }

            $item = array_merge(
                $item,
                [
                    'metadata' => Arr::except($request->all(), $this->reservedKeys),
                ]
            );

            $cart->addLineItem($item);
        }

        return $this->withSuccess($request, [
            'message' => __('simple-commerce.messages.cart_item_added'),
            'cart'    => $cart->toResource(),
        ]);
    }

    public function update(UpdateRequest $request, string $requestItem)
    {
        $cart = $this->getCart();
        $lineItem = $cart->lineItem($requestItem);

        $cart->updateLineItem(
            $requestItem,
            array_merge(
                Arr::only($request->all(), 'quantity', 'variant'),
                [
                    'metadata' => array_merge(
                        isset($lineItem['metadata']) ? $lineItem['metadata'] : [],
                        Arr::except($request->all(), $this->reservedKeys),
                    )
                ]
            ),
        );

        return $this->withSuccess($request, [
            'message' => __('simple-commerce.messages.cart_item_updated'),
            'cart'    => $cart->toResource(),
        ]);
    }

    public function destroy(DestroyRequest $request, string $item)
    {
        $cart = $this->getCart();

        $cart->removeLineItem($item);

        return $this->withSuccess($request, [
            'message' => __('simple-commerce.messages.cart_item_deleted'),
            'cart'    => $cart->toResource(),
        ]);
    }
}
