<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
//Page product, Detail
session_start();
class ProductController extends Controller
{
    //
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return  Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }
    public function add_Product()
    {
        $this->AuthLogin();
        $categories = DB::table('tbl_category_product')->get();
        $brands = DB::table('tbl_brand')->get();
        return view('admin.add_product', compact('categories', 'brands'));
    }
    public function all_Product()
    {
        $this->AuthLogin();
        $all_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
            ->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id')
            ->select('tbl_product.*', 'tbl_category_product.category_name', 'tbl_brand.brand_name')
            ->get();

        return view('admin.all_product', compact('all_product'));
    }

    public function save_Product(Request $request)
    {
        $this->AuthLogin();
        $data = [
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'product_price' => $request->product_price,
            'product_quantity' => $request->product_quantity,
            'product_desc' => $request->product_desc,
            'product_status' => $request->product_status,
            'product_size' => $request->product_size,
            'product_color' => $request->product_color,
            'product_material' => $request->product_material,
            'discount' => $request->discount,
            'product_image' => '',
        ];

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('uploads/product', $image_name);
            $data['product_image'] = 'uploads/product/' . $image_name;
        }

        DB::table('tbl_product')->insert($data);
        return Redirect::to('/all-Product')->with('message', 'Thêm sản phẩm thành công!');
    }
    public function edit_Product($id)
    {
        $this->AuthLogin();
        $product = DB::table('tbl_product')->where('product_id', $id)->first();
        $categories = DB::table('tbl_category_product')->get(); // Lấy danh sách danh mục
        $brands = DB::table('tbl_brand')->get(); // Lấy danh sách thương hiệu

        return view('admin.edit_product', compact('product', 'categories', 'brands'));
    }

    public function update_Product(Request $request, $id)
    {
        $this->AuthLogin();
        $data = [
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'product_price' => $request->product_price,
            'product_quantity' => $request->product_quantity,
            'product_desc' => $request->product_desc,
            'product_status' => $request->product_status,
            'product_size' => $request->product_size,
            'product_color' => $request->product_color,
            'product_material' => $request->product_material,
            'discount' => $request->discount,
        ];

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('uploads/product', $image_name);
            $data['product_image'] = 'uploads/product/' . $image_name;
        }

        DB::table('tbl_product')->where('product_id', $id)->update($data);
        return Redirect::to('/all-Product')->with('message', 'Cập nhật sản phẩm thành công!');
    }
    public function delete_Product($id)
    {
        $this->AuthLogin();
        $product = DB::table('tbl_product')->where('product_id', $id)->first();

        if ($product->product_image) {
            $image_path = public_path($product->product_image);
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Xóa sản phẩm khỏi database
        DB::table('tbl_product')->where('product_id', $id)->delete();

        return Redirect::to('/all-Product')->with('message', 'Xóa sản phẩm thành công!');
    }
    public function active_Product($id)
    {
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id', $id)->update(['product_status' => 1]);
        return redirect('/all-Product')->with('message', 'Sản phẩm đã được hiển thị!');
    }

    public function unActive_Product($id)
    {
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id', $id)->update(['product_status' => 0]);
        return redirect('/all-Product')->with('message', 'Sản phẩm đã bị ẩn!');
    }
    //detail product



    public function details_product($id)
    {
        // Lấy danh mục sản phẩm
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'desc')
            ->get();

        // Lấy danh sách thương hiệu
        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'desc')
            ->get();

        // Lấy chi tiết sản phẩm theo ID
        $detail_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
            ->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id')
            ->where('tbl_product.product_id', $id) // Lọc theo ID sản phẩm
            ->select('tbl_product.*', 'tbl_category_product.category_name', 'tbl_brand.brand_name')
            ->first(); // Lấy duy nhất 1 sản phẩm

        $related_products = DB::table('tbl_product')
            ->where('category_id', $detail_product->category_id) // Lọc theo cùng danh mục
            ->where('product_id', '!=', $id) // Loại trừ sản phẩm đang xem
            ->limit(6) // Giới hạn số lượng sản phẩm hiển thị
            ->get();

        return view('pages.sanpham.show_detail')->with([
            'categories' => $categories,
            'brands' => $brands,
            'detail_product' => $detail_product, // Truyền sản phẩm vào View
            'related_products' => $related_products
        ]);
    }
}
