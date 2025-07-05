<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeminiController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function showChat()
    {
        $conversations = $this->geminiService->getChatHistory();
        return view('chat', compact('conversations'));
    }

    public function ask(Request $request)
    {
        $request->validate(['prompt' => 'required|string|max:1000']);
        
        $response = $this->geminiService->generateContent($request->prompt);
        
        if ($response['success']) {
            return back()->with([
                'answer' => $response['data']->answer,
                'prompt' => $request->prompt
            ]);
        }

        return back()->with('error', $response['error']);
    }

    public function archive()
    {
        $archives = $this->geminiService->getChatHistory();
        return response()->json($archives);
    }
}