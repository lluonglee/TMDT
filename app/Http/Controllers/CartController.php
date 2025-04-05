<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function save_cart(Request $request)
    {
        $productId = $request->product_id_hidden;
        $quantity = $request->qty;

        // Lấy sản phẩm từ cơ sở dữ liệu
        $product = DB::table('tbl_product')->where('product_id', $productId)->first();

        if ($product) {
            // Lấy giỏ hàng hiện tại từ session
            $cart = Session::get('cart', []);

            // Tính giá sau khi giảm (nếu có)
            $discount_price = $product->product_price * (1 - ($product->discount ?? 0) / 100);

            // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
                $cart[$productId] = [
                    'product_id' => $product->product_id,
                    'product_name' => $product->product_name,
                    'product_price' => $discount_price, // Lưu giá đã giảm
                    'product_image' => $product->product_image,
                    'discount' => $product->discount ?? 0,
                    'quantity' => $quantity,
                ];
            }

            // Cập nhật giỏ hàng trong session
            Session::put('cart', $cart);
        }

        // Chuyển hướng về trang giỏ hàng
        return Redirect::to('/show-cart');
    }

    // public function show_cart()
    // {
    //     $cart = Session::get('cart', []);
    //     $subtotal = 0;
    //     $tax = 0;
    //     $shipping_fee = 0; // Đặt phí vận chuyển cố định hoặc tùy theo điều kiện

    //     foreach ($cart as $item) {
    //         $subtotal += $item['product_price'] * $item['quantity'];
    //     }

    //     $tax = $subtotal * 0.05; // Giả sử thuế là 5%
    //     $total = $subtotal + $tax + $shipping_fee;

    //     $categories = DB::table('tbl_category_product')->where('category_status', '1')->orderBy('category_id', 'desc')->get();
    //     $brands = DB::table('tbl_brand')->where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

    //     return view('pages.cart.show_cart', compact('categories', 'brands', 'subtotal', 'tax', 'shipping_fee', 'total'));
    // }
    public function show_cart()
    {
        $cart = Session::get('cart', []);
        $subtotal = 0;
        $shipping_fee = 0; // Phí vận chuyển có thể thay đổi theo điều kiện

        foreach ($cart as $item) {
            // Tính giá sau khi giảm
            $discount_price = $item['product_price'] * (1 - ($item['discount'] ?? 0) / 100);
            $total_price = $discount_price * $item['quantity'];

            $subtotal += $total_price;
        }

        // Không còn tính thuế
        $total = $subtotal + $shipping_fee;

        $categories = DB::table('tbl_category_product')->where('category_status', '1')->orderBy('category_id', 'desc')->get();
        $brands = DB::table('tbl_brand')->where('brand_status', '1')->orderBy('brand_id', 'desc')->get();

        return view('pages.cart.show_cart', compact('categories', 'brands', 'subtotal', 'shipping_fee', 'total'));
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
    public function delete_cart($productId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]); // Xóa sản phẩm khỏi mảng
        }

        Session::put('cart', $cart); // Cập nhật lại session
        return Redirect::to('/show-cart');
    }

    // Xóa toàn bộ giỏ hàng
    public function clear_cart()
    {
        Session::forget('cart'); // Xóa toàn bộ session giỏ hàng
        return Redirect::to('/show-cart');
    }
}
