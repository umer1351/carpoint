<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request) {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'listing_id' => 'required|exists:listings,id',
            'message' => 'required'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'listing_id' => $request->listing_id,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function inbox() {
        $messages = Message::where('receiver_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();

        return response()->json(["success" => true, "message" => "inbox executed successfully."]);
    }

    public function replyMessage(Request $request, $messageId) {
        $originalMessage = Message::findOrFail($messageId);

        $request->validate(['message' => 'required']);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $originalMessage->sender_id, // Reply to sender
            'listing_id' => $originalMessage->listing_id,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Reply sent successfully!');
    }
}
