<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Echo_;
use Illuminate\Support\Facades\DB;

class CategoryProduct extends Controller
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
    public function add_Category_Product()
    {
        $this->AuthLogin();
        return view('admin.add_category_product');
    }
    public function edit_Category_Product($id)
    {
        $this->AuthLogin();
        $category = DB::table('tbl_category_product')->where('category_id', $id)->first();
        return view('admin.edit_category_product', compact('category'));
    }
    public function update_Category_Product(Request $request, $id)
    {
        $this->AuthLogin();
        $data = [
            'category_name' => $request->category_product_name,
            'category_desc' => $request->category_product_desc
        ];

        DB::table('tbl_category_product')->where('category_id', $id)->update($data);

        return redirect('/all-Category-Product')->with('message', 'Cập nhật danh mục thành công!');
    }
    public function delete_Category_Product($id)
    {
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id', $id)->delete();
        return redirect('/all-Category-Product')->with('success', 'Xóa danh mục thành công!');
    }



    public function all_Category_Product()
    {
        $this->AuthLogin();
        $all_category_product = DB::table('tbl_category_product')->get();

        return view('admin.all_category_product', compact('all_category_product'));
    }

    public function save_Category_Product(Request $request)
    {
        $this->AuthLogin();
        $data = [];
        $data['category_name'] = $request->input('category-product-name'); // Sửa lỗi dấu -
        $data['category_desc'] = $request->input('category_product_desc');
        $data['category_status'] = $request->input('category-product-status');

        DB::table('tbl_category_product')->insert($data);
        Session::put('message', 'Them danh muc san pham thanh cong');
        return Redirect::to('add-brand-Product');
    }
    public function active_Category_Product($id)
    {
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id', $id)->update(['category_status' => 1]);
        return redirect('/all-Category-Product')->with('success', 'Danh mục đã được hiển thị!');
    }

    public function unActive_Category_Product($id)
    {
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id', $id)->update(['category_status' => 0]);
        return redirect('/all-Category-Product')->with('success', 'Danh mục đã bị ẩn!');
    }
    //page admin
    // public function show_category($category_id)
    // {

    //     $categories = DB::table('tbl_category_product')
    //         ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
    //         ->orderBy('category_id', 'desc')
    //         ->get();

    //     $brands = DB::table('tbl_brand')
    //         ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
    //         ->orderBy('brand_id', 'desc')
    //         ->get();

    //     $category_by_id = DB::table('tbl_product')->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')->where('tbl_product.category_id', $category_id);

    //     return view('pages.category.show_category')->with([
    //         'categories' => $categories, // Truyền đúng biến
    //         'brands' => $brands,
    //         'category_by_id'=>$category_by_id;

    //     ]);;
    // }
    public function show_category($category_id)
    {
        // Lấy danh mục sản phẩm
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'desc')
            ->get();

        // Lấy thương hiệu sản phẩm
        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'desc')
            ->get();

        // Lấy sản phẩm thuộc danh mục
        $category_by_id = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
            ->where('tbl_product.category_id', $category_id)
            ->where('tbl_product.product_status', '1') // Chỉ lấy sản phẩm đang hiển thị
            ->select('tbl_product.*') // Chọn tất cả các cột của bảng sản phẩm
            ->orderBy('tbl_product.product_id', 'desc')
            ->get();

        $category_name = DB::table('tbl_category_product')
            ->where('category_id', $category_id)
            ->value('category_name'); // Lấy duy nhất 1 giá trị của category_name

        return view('pages.category.show_category')->with([
            'categories' => $categories,
            'brands' => $brands,
            'category_by_id' => $category_by_id,
            'category_name' => $category_name // Truyền thêm biến này
        ]);
    }
}