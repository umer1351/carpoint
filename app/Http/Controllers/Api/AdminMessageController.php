<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index() {
        $messages = Message::orderBy('created_at', 'desc')->get();
        // return response()->json(["success" => true, "message" => "index executed successfully."]);
        if (!$messages) {
            return response()->json(["success" => false, "message" => "Terms not found."], 404);
        }
    
        return response()->json([
            "success" => true,
            "data" => [
                "general_settings" => $g_setting,
                "messages" => $messages
            ]
        ]);
    }

    public function conversation($senderId, $receiverId) {
        $messages = Message::where(function ($query) use ($senderId, $receiverId) {
                                $query->where('sender_id', $senderId)
                                      ->where('receiver_id', $receiverId);
    })
                            ->orWhere(function ($query) use ($senderId, $receiverId) {
                                $query->where('sender_id', $receiverId)
                                      ->where('receiver_id', $senderId);
                            })
                            ->orderBy('created_at', 'asc')
                            ->get();

        return view('admin.messages.conversation', compact('messages'));
    }
}
