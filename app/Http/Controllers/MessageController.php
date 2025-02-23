<?php
namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{

    
    public function index($receiverId)
    {

        // echo "Message Controller -  index";
        // echo "<pre>";

        $userId = Auth::id(); // Get the authenticated user ID

        
        // print_r($userId);
        // die;

        // Fetch messages where the user is either sender or receiver
        $messages = Message::where(function ($query) use ($userId, $receiverId) {
                $query->where('sender_id', $userId)->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($userId, $receiverId) {
                $query->where('sender_id', $receiverId)->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc') // Order messages by oldest first
            ->get();

        //     echo "<pre>";
            
        // print_r($messages);
        // die;
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        // echo "Message -> store";
        // echo "<pre>";
        // print_r($request->all());
        // die;


        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message);
    }
}

