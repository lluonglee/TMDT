<?php

namespace App\Http\Controllers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Echo_;
use Illuminate\Support\Facades\DB;

session_start();
class HomeController extends Controller
{


    public function index()
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('brand_id', 'desc')
            ->get();

        // $all_product = DB::table('tbl_product')
        //     ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
        //     ->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id')
        //     ->select('tbl_product.*', 'tbl_category_product.category_name', 'tbl_brand.brand_name')
        //     ->get();

        $all_product = DB::table('tbl_product')
            ->where('product_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('product_id', 'desc')
            // ->Limit(5)
            ->get();

        return view('pages.home')->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,
            'all_product' => $all_product
        ]);
    }
    public function search(Request $request)
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('brand_id', 'desc')
            ->get();

        $keywords = $request->input('keywords'); // Lấy từ khóa từ ô tìm kiếm

        $search_results = DB::table('tbl_product')
            ->where('product_name', 'LIKE', "%{$keywords}%")
            ->orWhere('product_desc', 'LIKE', "%{$keywords}%")
            ->get();

        return view('pages.sanpham.search')->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,
            'search_results' => $search_results,
            'keywords' => $keywords
        ]);
    }
}
