<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    public function save_cart(Request $request)
    {
        $productId = $request->product_id_hidden;
        $quantity = $request->qty;
        $product = DB::table('tbl_product')->where('product_id', $productId)->first();

        if ($product) {
            $cart = Session::get('cart', []);
            $discount_price = $product->product_price * (1 - ($product->discount ?? 0) / 100);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'product_id' => $product->product_id,
                    'product_name' => $product->product_name,
                    'product_price' => $discount_price,
                    'product_image' => $product->product_image,
                    'discount' => $product->discount ?? 0,
                    'quantity' => $quantity,
                ];
            }
            Session::put('cart', $cart);
        }
        return Redirect::to('/show-cart');
    }

    public function show_cart()
    {
        $cart = Session::get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $total_price = $item['product_price'] * $item['quantity'];
            $subtotal += $total_price;
        }

        // Khởi tạo shipping_fee mặc định
        $shipping_fee = 0;
        $shipping_id = Session::get('shipping_id');

        if ($shipping_id && Schema::hasColumn('tbl_shipping', 'shipping_fee')) {
            $shipping = DB::table('tbl_shipping')
                ->where('shipping_id', $shipping_id)
                ->select('shipping_id', 'matp', 'maqh', 'shipping_fee')
                ->first();

            if ($shipping) {
                $shipping_fee = $shipping->shipping_fee ?? 0;
            } else {
            }
        } else {
        }

        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'desc')
            ->get();
        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'desc')
            ->get();

        return view('pages.cart.show_cart', compact('categories', 'brands', 'subtotal', 'shipping_fee'));
    }

    public function remove_cart($product_id)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$product_id])) {
            unset($cart[$product_id]);
            Session::put('cart', $cart);
        }
        return Redirect::to('/show-cart');
    }

    public function update_cart(Request $request)
    {
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $cart = Session::get('cart', []);
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }
        return Redirect::to('/show-cart');
    }

    public function clear_cart()
    {
        Session::forget('cart');
        return Redirect::to('/show-cart');
    }
}
