<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Echo_;
use Illuminate\Support\Facades\DB;

session_start();
class BrandProduct extends Controller
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
    public function add_Brand_Product()
    {
        $this->AuthLogin();
        return view('admin.add_brand_product');
    }

    public function all_Brand_Product()
    {
        $this->AuthLogin();
        $all_brand_product = DB::table('tbl_brand')->get();
        return view('admin.all_brand_product', compact('all_brand_product'));
    }

    public function delete_Brand_Product($id)
    {
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id', $id)->delete();
        return redirect::to('all-brand-Product')->with('message', 'Xóa thương hiệu thành công!');
    }

    public function save_Brand_Product(Request $request)
    {
        $this->AuthLogin();
        $brand_name = $request->input('brand_product_name');

        // Kiểm tra tên thương hiệu đã tồn tại chưa (không phân biệt hoa thường)
        $exists = DB::table('tbl_brand')
            ->whereRaw('LOWER(brand_name) = ?', [strtolower($brand_name)])
            ->exists();

        if ($exists) {
            return Redirect::to('add-brand-Product')->with('message', 'Tên thương hiệu đã tồn tại, vui lòng chọn tên khác!');
        }
        $data = [
            'brand_name' => $request->brand_product_name,
            'brand_desc' => $request->brand_product_desc,
            'brand_status' => $request->brand_product_status,
        ];

        DB::table('tbl_brand')->insert($data);
        return Redirect::to('all-brand-Product')->with('message', 'Thêm thương hiệu thành công!');
    }


    public function edit_Brand_Product($id)
    {
        $this->AuthLogin();
        $brand = DB::table('tbl_brand')->where('brand_id', $id)->first();
        return view('admin.edit_brand_product', compact('brand'));
    }
    public function update_Brand_Product(Request $request, $id)
    {
        $this->AuthLogin();
        $data = [
            'brand_name' => $request->brand_product_name,
            'brand_desc' => $request->brand_product_desc
        ];

        DB::table('tbl_brand')->where('brand_id', $id)->update($data);

        return redirect('/all-brand-Product')->with('message', 'Cập nhật danh mục thành công!');
    }

    public function active_Brand_Product($id)
    {
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id', $id)->update(['brand_status' => 1]);
        return redirect('/all-brand-Product')->with('success', 'Thương hiệu đã được hiển thị!');
    }

    public function unActive_Brand_Product($id)
    {
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id', $id)->update(['brand_status' => 0]);
        return redirect('/all-brand-Product')->with('success', 'Thương hiệu đã bị ẩn!');
    }
    ///brand
    public function show_brand($brand_id)
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

        // Lấy danh sách sản phẩm theo thương hiệu
        $brand_by_id = DB::table('tbl_product')
            ->join('tbl_brand', 'tbl_product.brand_id', '=', 'tbl_brand.brand_id')
            ->where('tbl_product.brand_id', $brand_id)
            ->where('tbl_product.product_status', '1') // Chỉ lấy sản phẩm đang hiển thị
            ->select('tbl_product.*') // Lấy dữ liệu từ bảng sản phẩm
            ->orderBy('tbl_product.product_id', 'desc')
            ->get();

        $brand_name = DB::table('tbl_brand')
            ->where('brand_id', $brand_id)
            ->value('brand_name');

        return view('pages.brand.show_brand')->with([
            'categories' => $categories,
            'brands' => $brands,
            'brand_by_id' => $brand_by_id,
            'brand_name' => $brand_name
        ]);
    }
}
