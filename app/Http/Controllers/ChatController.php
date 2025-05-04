<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        if (!$message) {
            return redirect()->back()->with('error', 'Tin nhắn không được để trống!');
        }

        $session_id = session()->getId();

        DB::table('tbl_chat_messages')->insert([
            'session_id' => $session_id,
            'message' => $message,
            'is_bot' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $botMessage = str_contains(strtolower($message), 'mã đơn hàng')
            ? (session('latest_order_id') ? "Mã đơn hàng của bạn là: " . session('latest_order_id') : "Bạn chưa có đơn hàng!")
            : "Tôi đã nhận được tin nhắn của bạn!";

        DB::table('tbl_chat_messages')->insert([
            'session_id' => $session_id,
            'message' => $botMessage,
            'is_bot' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Tin nhắn đã gửi!')->with('chat_open', true);
    }

    public function getHistory()
    {
        $session_id = session()->getId();
        $messages = DB::table('tbl_chat_messages')
            ->where('session_id', $session_id)
            ->get();
        return view('partials.chat_messages', compact('messages'));
    }
}
