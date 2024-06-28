<?php

namespace App\Helpers;


use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    //add item to cart
    static public function addItemToCart($product_id)
    {
        $cart_items = self::getCartItemsFriomCookie();
        $existing_item = null;
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item = $key;
                break;
            }
        }
        if ($existing_item !== null) {
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] * $cart_items[$existing_item]['unit_amount'];
        } else {
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if ($product) {
                $cart_items[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->images,
                    'quantity' => 1,
                    'total_amount' => $product->price,
                    'unit_amount' => $product->price,
                ];
            }
        }
        self::addCartItemToCookie($cart_items);
        return count($cart_items);
    }

    //remove item to cart
    static public function removeCartItem($product_id)
    {
        $cart_items = self::getCartItemsFriomCookie();
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
                break;
            }
        }
        self::addCartItemToCookie($cart_items);
        return count($cart_items);
    }

    //add cart item to cookie
    static public function addCartItemToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    //clear cart items from cookie
    static public function clearCartItemsFromCookie()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    //get all cart items from cookie
    static public function getCartItemsFriomCookie()
    {
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if (!$cart_items) {
            $cart_items = [];
        }
        return $cart_items;
    }

    //increment item quantity
    static public function incremenQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFriomCookie();
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                break;
            }
        }
        self::addCartItemToCookie($cart_items);
        return count($cart_items);
    }

    //decrement item quantity
    static public function decrementQuantityToCartItem()
    {
        $cart_items = self::getCartItemsFriomCookie();
        foreach ($cart_items as $key => $item) {
            if ($item['quantity'] > 1) {
                $cart_items[$key]['quantity']--;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                break;
            }
        }
        self::addCartItemToCookie($cart_items);
        return count($cart_items);
    }

    //calculate grand total
    static public function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }

}
