<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ChatbotLog;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function __construct(private ChatbotService $chatbot) {}

    public function index()
    {
        $quickReplies = $this->chatbot->quickReplies();
        return view('siswa.chatbot.index', compact('quickReplies'));
    }

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $message = $request->input('message');
        $result  = $this->chatbot->respond($message);

        // Simpan log
        ChatbotLog::create([
            'user_id'  => Auth::id(),
            'message'  => $message,
            'response' => $result['response'],
            'topic'    => $result['topic'],
        ]);

        return response()->json([
            'success'  => true,
            'response' => $result['response'],
            'topic'    => $result['topic'],
        ]);
    }
}
