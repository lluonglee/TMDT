<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = DB::table('tbl_promotion')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:tbl_promotion',
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        DB::table('tbl_promotion')->insert([
            'code' => strtoupper($request->code),
            'discount_value' => $request->discount_value,
            'discount_type' => $request->discount_type,
            'max_discount' => $request->max_discount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'usage_limit' => $request->usage_limit,
            'is_active' => $request->is_active ?? true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Redirect::route('admin.promotions.index')->with('success', 'Thêm mã khuyến mãi thành công.');
    }

    public function edit($id)
    {
        $promotion = DB::table('tbl_promotion')->where('id', $id)->first();
        if (!$promotion) {
            return Redirect::route('admin.promotions.index')->with('error', 'Mã khuyến mãi không tồn tại.');
        }
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:tbl_promotion,code,' . $id,
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        DB::table('tbl_promotion')->where('id', $id)->update([
            'code' => strtoupper($request->code),
            'discount_value' => $request->discount_value,
            'discount_type' => $request->discount_type,
            'max_discount' => $request->max_discount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'usage_limit' => $request->usage_limit,
            'is_active' => $request->is_active ?? true,
            'updated_at' => now(),
        ]);

        return Redirect::route('admin.promotions.index')->with('success', 'Cập nhật mã khuyến mãi thành công.');
    }

    public function destroy($id)
    {
        $promotion = DB::table('tbl_promotion')->where('id', $id)->first();
        if (!$promotion) {
            return Redirect::back()->with('error', 'Mã khuyến mãi không tồn tại.');
        }
        DB::table('tbl_promotion')->where('id', $id)->delete();
        return Redirect::back()->with('success', 'Xóa mã khuyến mãi thành công.');
    }
    public function applyPromotion(Request $request)
    {
        $request->validate([
            'promotion_code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($request->promotion_code));
        $today = now()->toDateString();

        // Kiểm tra mã khuyến mãi
        $promotion = DB::table('tbl_promotion')
            ->where('code', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where(function ($query) {
                $query->where('usage_limit', 0)
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->first();

        if (!$promotion) {
            return Redirect::back()->with('promotion_error', 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn.');
        }

        // Tính số tiền giảm giá
        $cart = Session::get('cart', []);
        $subtotal = 0;
        $product_discount_total = 0;
        foreach ($cart as $item) {
            $discounted_price = $item['product_price'] * (1 - ($item['product_discount'] ?? 0) / 100);
            $subtotal += $item['product_price'] * $item['quantity'];
            $product_discount_total += ($item['product_price'] - $discounted_price) * $item['quantity'];
        }

        $discount = 0;
        if ($promotion->discount_type == 'fixed') {
            $discount = $promotion->discount_value;
        } elseif ($promotion->discount_type == 'percentage') {
            $discount = ($promotion->discount_value / 100) * ($subtotal - $product_discount_total);
            if ($promotion->max_discount && $discount > $promotion->max_discount) {
                $discount = $promotion->max_discount;
            }
        }

        // Lưu mã và số tiền giảm vào session
        Session::put('promotion_discount', $discount);
        Session::put('promotion_code', $code);

        return Redirect::back()->with('promotion_success', 'Áp dụng mã khuyến mãi thành công!');
    }

    public function clearPromotion()
    {
        Session::forget('promotion_discount');
        Session::forget('promotion_code');
        return Redirect::back()->with('promotion_success', 'Đã hủy mã khuyến mãi.');
    }
}