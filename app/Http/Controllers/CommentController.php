<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index()
    {
        $reviews = DB::table('tbl_product_reviews')
            ->join('tbl_product', 'tbl_product_reviews.product_id', '=', 'tbl_product.product_id')
            ->join('tbl_customer', 'tbl_product_reviews.customer_id', '=', 'tbl_customer.customer_id')
            ->select(
                'tbl_product_reviews.*',
                'tbl_product.product_name',
                'tbl_customer.customer_email'
            )
            ->orderBy('tbl_product_reviews.created_at', 'desc')
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        DB::table('tbl_product_reviews')
            ->where('id', $id)
            ->update(['status' => $request->status, 'updated_at' => now()]);

        return Redirect::back()->with('success', 'Cập nhật trạng thái đánh giá thành công.');
    }

    public function destroy($id)
    {
        DB::table('tbl_product_reviews')->where('id', $id)->delete();
        return Redirect::back()->with('success', 'Xóa đánh giá thành công.');
    }
    //
    public function store(Request $request, $product_id)
    {
        // Kiểm tra khách hàng đã đăng nhập chưa
        $customer_id = Session::get('customer_id');
        if (!$customer_id) {
            return Redirect::to('/customer/login')->with('error', 'Bạn cần đăng nhập để đánh giá sản phẩm.');
        }

        // Validate dữ liệu
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao.',
            'rating.min' => 'Đánh giá phải từ 1 đến 5 sao.',
            'rating.max' => 'Đánh giá phải từ 1 đến 5 sao.',
        ]);

        // Kiểm tra xem khách hàng đã mua sản phẩm chưa
        $hasPurchased = DB::table('tbl_order')
            ->join('tbl_order_detail', 'tbl_order.order_id', '=', 'tbl_order_detail.order_id')
            ->where('tbl_order.customer_id', $customer_id)
            ->where('tbl_order_detail.product_id', $product_id)
            ->where('tbl_order.order_status', 'Hoàn thành') // Chỉ cho phép đánh giá khi đơn hàng đã giao
            ->exists();

        if (!$hasPurchased) {
            return Redirect::back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi mua và nhận hàng.');
        }

        // Kiểm tra xem khách hàng đã đánh giá sản phẩm này chưa
        $existingReview = DB::table('tbl_product_reviews')
            ->where('customer_id', $customer_id)
            ->where('product_id', $product_id)
            ->exists();

        if ($existingReview) {
            return Redirect::back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }
        // Lấy tên khách hàng
        $customer_name = DB::table('tbl_customer')->where('customer_id', $customer_id)->value('customer_name');

        // Lưu đánh giá với trạng thái pending
        DB::table('tbl_product_reviews')->insert([
            'customer_id' => $customer_id,
            'product_id' => $product_id,
            'customer_name' => $customer_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Redirect::back()->with('success', 'Đánh giá của bạn đã được gửi và đang chờ duyệt.');
    }
}