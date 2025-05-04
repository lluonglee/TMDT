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
        $message = trim($request->input('message'));
        if (!$message) {
            return redirect()->back()->with('error', 'Tin nhắn không được để trống!')->with('chat_open', true);
        }

        $session_id = session()->getId();

        // Lưu tin nhắn người dùng
        DB::table('tbl_chat_messages')->insert([
            'session_id' => $session_id,
            'message' => $message,
            'is_bot' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Kiểm tra yêu cầu về đơn hàng
        $botMessage = $this->generateBotResponse($message, $session_id);

        // Lưu phản hồi bot
        DB::table('tbl_chat_messages')->insert([
            'session_id' => $session_id,
            'message' => $botMessage,
            'is_bot' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Tin nhắn đã gửi!')->with('chat_open', true);
    }

    private function generateBotResponse($message, $session_id)
    {
        $lowerMessage = strtolower($message);

        // Kiểm tra các từ khóa liên quan đến đơn hàng
        if (str_contains($lowerMessage, 'đơn hàng của tôi') || str_contains($lowerMessage, 'đơn hàng') || str_contains($lowerMessage, 'đặt hàng')) {
            $order = DB::table('tbl_order')
                ->where('session_id', $session_id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($order) {
                return "Đơn hàng của bạn: Mã đơn hàng: {$order->order_id}, Trạng thái: {$order->order_status}, Tổng tiền: " . number_format($order->order_total, 2) . " VNĐ";
            }
            return "Bạn chưa có đơn hàng nào!";
        }

        // Phản hồi mặc định
        return "Tôi đã nhận được tin nhắn của bạn!";
    }

    public function getHistory()
    {
        $session_id = session()->getId();
        $messages = DB::table('tbl_chat_messages')
            ->where('session_id', $session_id)
            ->get();
        return view('partials.chat_messages', compact('messages'));
    }

    public function getSessionId()
    {
        return session()->getId();
    }
}
