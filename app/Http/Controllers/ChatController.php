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

        if (str_contains($lowerMessage, 'đơn hàng')) {
            return $this->handleOrderInquiry($session_id);
        }

        if (str_contains($lowerMessage, 'giờ làm việc') || str_contains($lowerMessage, 'khi nào mở cửa')) {
            return "Chúng tôi làm việc từ 8:00 đến 17:00 từ thứ 2 đến thứ 7.";
        }

        if (str_contains($lowerMessage, 'địa chỉ') || str_contains($lowerMessage, 'ở đâu')) {
            return "Địa chỉ cửa hàng: P1, Tp.Vinh Long";
        }
        if (str_contains($lowerMessage, 'áo') || str_contains($lowerMessage, 'quần')) {
            return "Bạn có thể xem các sản phẩm liên quan tại: " . url('/trang-chu');
        }

        if (str_contains($lowerMessage, 'sản phẩm') || str_contains($lowerMessage, 'giảm giá')) {
            return "Bạn có thể xem các sản phẩm đang giảm giá tại trang chủ !!";
        }

        return "Tôi đã nhận được tin nhắn của bạn! Bạn có thể hỏi tôi về đơn hàng, thời gian làm việc, địa chỉ hoặc sản phẩm.";
    }

    private function handleOrderInquiry($session_id)
    {
        $order = DB::table('tbl_order')
            ->where('session_id', $session_id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($order) {
            return "Đơn hàng của bạn: Mã đơn hàng: {$order->order_id}, Trạng thái: {$order->order_status}, Tổng tiền: " . number_format($order->order_total, 0, ',', '.') . " VNĐ";
        }

        return "Bạn chưa có đơn hàng nào!";
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
