<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class ShippingFeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = DB::table('shipping_fees')
            ->leftJoin('tbl_tinhthanhpho', 'shipping_fees.matp', '=', 'tbl_tinhthanhpho.matp')
            ->leftJoin('tbl_quanhuyen', 'shipping_fees.maqh', '=', 'tbl_quanhuyen.maqh')
            ->select(
                'shipping_fees.*',
                'tbl_tinhthanhpho.name as province_name',
                'tbl_quanhuyen.name as district_name'
            );

        if ($search) {
            $query->where('tbl_tinhthanhpho.name', 'like', "%$search%")
                ->orWhere('tbl_quanhuyen.name', 'like', "%$search%");
        }

        $shippingFees = $query->paginate(10);
        return view('admin.shipping_fees.index', compact('shippingFees', 'search'));
    }

    public function create()
    {
        $provinces = DB::table('tbl_tinhthanhpho')->orderBy('name')->get();
        return view('admin.shipping_fees.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matp' => 'required|exists:tbl_tinhthanhpho,matp',
            'maqh' => 'nullable|exists:tbl_quanhuyen,maqh',
            'fee' => 'required|integer|min:0',
        ]);

        $exists = DB::table('shipping_fees')
            ->where('matp', $data['matp'])
            ->where('maqh', $data['maqh'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['maqh' => 'Phí ship cho địa phương này đã tồn tại!']);
        }

        DB::table('shipping_fees')->insert([
            'matp' => $data['matp'],
            'maqh' => $data['maqh'],
            'fee' => $data['fee'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('shipping_fees.index')->with('success', 'Thêm phí ship thành công!');
    }

    public function edit($id)
    {
        $shippingFee = DB::table('shipping_fees')->where('id', $id)->first();
        if (!$shippingFee) {
            return redirect()->route('shipping_fees.index')->with('error', 'Phí ship không tồn tại!');
        }

        $provinces = DB::table('tbl_tinhthanhpho')->orderBy('name')->get();
        $districts = DB::table('tbl_quanhuyen')->where('matp', $shippingFee->matp)->orderBy('name')->get();

        return view('admin.shipping_fees.edit', compact('shippingFee', 'provinces', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'matp' => 'required|exists:tbl_tinhthanhpho,matp',
            'maqh' => 'nullable|exists:tbl_quanhuyen,maqh',
            'fee' => 'required|integer|min:0',
        ]);

        $exists = DB::table('shipping_fees')
            ->where('matp', $data['matp'])
            ->where('maqh', $data['maqh'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['maqh' => 'Phí ship cho địa phương này đã tồn tại!']);
        }

        DB::table('shipping_fees')
            ->where('id', $id)
            ->update([
                'matp' => $data['matp'],
                'maqh' => $data['maqh'],
                'fee' => $data['fee'],
                'updated_at' => now(),
            ]);

        return redirect()->route('shipping_fees.index')->with('success', 'Cập nhật phí ship thành công!');
    }

    public function destroy($id)
    {
        DB::table('shipping_fees')->where('id', $id)->delete();
        return redirect()->route('shipping_fees.index')->with('success', 'Xóa phí ship thành công!');
    }

    public function getDistricts($matp)
    {
        $districts = DB::table('tbl_quanhuyen')
            ->where('matp', $matp)
            ->orderBy('name')
            ->get();
        return response()->json($districts);
    }

    public function getShippingFee($matp, $maqh = null)
    {
        $query = DB::table('shipping_fees')
            ->where('matp', $matp);

        if ($maqh) {
            $query->where('maqh', $maqh);
        } else {
            $query->whereNull('maqh');
        }

        $fee = $query->select('fee')->first();


        return response()->json([
            'fee' => $fee ? $fee->fee : 10000,
            'message' => $fee ? 'Fee found' : 'No fee defined for this location'
        ]);
    }

    public function editShipping()
    {
        if (!Session::has('shipping_id')) {
            return Redirect::to('/checkout')->with('error', 'Chưa có thông tin giao hàng để chỉnh sửa');
        }

        $shipping_id = Session::get('shipping_id');
        $shipping = DB::table('tbl_shipping')->where('shipping_id', $shipping_id)->first();
        if (!$shipping) {

            return Redirect::to('/checkout')->with('error', 'Không tìm thấy thông tin giao hàng');
        }

        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'desc')
            ->get();

        $provinces = DB::table('tbl_tinhthanhpho')
            ->orderBy('name')
            ->get();

        $shipping_fee = $shipping->shipping_fee ?? 0;
        $session_id = session()->getId();
        $messages = DB::table('tbl_chat_messages')
            ->where('session_id', $session_id)
            ->get();

        return view('pages.checkout.edit_checkout')->with([
            'categories' => $categories,
            'brands' => $brands,
            'provinces' => $provinces,
            'shipping' => $shipping,
            'shipping_fee' => $shipping_fee,
            'messages' => $messages
        ]);
    }

    public function updateShipping(Request $request)
    {
        $shipping_id = $request->shipping_id;
        $matp = $request->matp;
        $maqh = $request->maqh ?: null;
        $shipping_fee = 0;

        if ($matp) {
            $query = DB::table('shipping_fees')->where('matp', $matp);
            if ($maqh) {
                $query->where('maqh', $maqh);
            } else {
                $query->whereNull('maqh');
            }
            $fee_record = $query->select('fee')->first();
            $shipping_fee = $fee_record ? $fee_record->fee : 0;
        } else {
        }

        $data = [
            'shipping_email' => $request->shipping_email,
            'shipping_name' => $request->shipping_name,
            'shipping_address' => $request->shipping_address,
            'shipping_phone' => $request->shipping_phone,
            'matp' => $matp,
            'maqh' => $maqh,
            'shipping_fee' => $shipping_fee,
            'updated_at' => now(),
        ];

        $updated = DB::table('tbl_shipping')
            ->where('shipping_id', $shipping_id)
            ->update($data);

        if ($updated) {

            Session::put('shipping_fee', $shipping_fee);
            return Redirect::to('/show-cart')->with('success', 'Cập nhật thông tin giao hàng thành công');
        } else {

            return Redirect::to('/edit-shipping')->with('error', 'Cập nhật thông tin thất bại');
        }
    }
}
