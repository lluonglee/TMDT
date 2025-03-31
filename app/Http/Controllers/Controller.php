<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Echo_;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    public function add_Category_Product()
    {
        return view('admin.add_category_product');
    }
    public function edit_Category_Product($id)
    {
        $category = DB::table('tbl_category_product')->where('category_id', $id)->first();
        return view('admin.edit_category_product', compact('category'));
    }
    public function update_Category_Product(Request $request, $id)
    {
        $data = [
            'category_name' => $request->category_product_name,
            'category_desc' => $request->category_product_desc
        ];

        DB::table('tbl_category_product')->where('category_id', $id)->update($data);

        return redirect('/all-Category-Product')->with('message', 'Cập nhật danh mục thành công!');
    }
    public function delete_Category_Product($id)
    {
        DB::table('tbl_category_product')->where('category_id', $id)->delete();
        return redirect('/all-Category-Product')->with('success', 'Xóa danh mục thành công!');
    }



    public function all_Category_Product()
    {
        $all_category_product = DB::table('tbl_category_product')->get();

        return view('admin.all_category_product', compact('all_category_product'));
    }

    public function save_Category_Product(Request $request)
    {
        $data = [];
        $data['category_name'] = $request->input('category-product-name'); // Sửa lỗi dấu -
        $data['category_desc'] = $request->input('category_product_desc');
        $data['category_status'] = $request->input('category-product-status');

        DB::table('tbl_category_product')->insert($data);
        Session::put('message', 'Them danh muc san pham thanh cong');
        return Redirect::to('add-Category-Product');
    }
    public function active_Category_Product($id)
    {
        DB::table('tbl_category_product')->where('category_id', $id)->update(['category_status' => 1]);
        return redirect('/all-Category-Product')->with('success', 'Danh mục đã được hiển thị!');
    }

    public function unActive_Category_Product($id)
    {
        DB::table('tbl_category_product')->where('category_id', $id)->update(['category_status' => 0]);
        return redirect('/all-Category-Product')->with('success', 'Danh mục đã bị ẩn!');
    }
}
