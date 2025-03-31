<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

session_start();
class CheckoutController extends Controller
{
    //
    public function login_checkout()
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('brand_id', 'desc')
            ->get();
        return view('pages.checkout.login_checkout')->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,
        ]);
    }
}