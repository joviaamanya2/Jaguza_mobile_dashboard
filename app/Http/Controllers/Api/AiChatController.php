<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiChatMessage;
use App\Services\AiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AiChatController extends Controller
{
    public function __construct(private readonly AiChatService $ai)
    {
    }

    public function history(Request $request)
    {
        $userId = $request->user()->id ?? 1;

        $history = AiChatMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(fn (AiChatMessage $m) => [
                'id' => $m->id,
                'sender' => $m->sender,
                'isUser' => $m->sender === 'user',
                'message' => $m->message,
                'language' => $m->language,
                'feedback' => $m->feedback,
                'created_at' => optional($m->created_at)->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'data' => $history,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:4000',
            'language' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $userId = $request->user()->id ?? 1;
        $message = trim((string) $request->input('message'));
        $language = $request->input('language') ?: 'English';

        // Build conversation context before we persist the new turn.
        $context = [];
        try {
            $context = AiChatMessage::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get(['sender', 'message'])
                ->reverse()
                ->map(fn (AiChatMessage $m) => ['sender' => $m->sender, 'message' => (string) $m->message])
                ->values()
                ->all();
        } catch (Throwable $e) {
            Log::warning('Jaguza AI chat: could not load history for context.', ['error' => $e->getMessage()]);
        }

        $result = $this->ai->reply($message, $context, $language);
        $reply = $result['message'];

        $userMsg = null;
        $aiMsg = null;

        try {
            $userMsg = AiChatMessage::create([
                'user_id' => $userId,
                'sender' => 'user',
                'message' => $message,
                'language' => $language,
            ]);

            $aiMsg = AiChatMessage::create([
                'user_id' => $userId,
                'sender' => 'ai',
                'message' => $reply,
                'language' => $language,
                'metadata' => ['source' => $result['source']],
            ]);
        } catch (Throwable $e) {
            // Persistence failed, but the farmer should still get the answer.
            Log::error('Jaguza AI chat: failed to save messages.', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'source' => $result['source'],
            'persisted' => $aiMsg !== null,
            'user_message' => $userMsg ?? ['sender' => 'user', 'message' => $message],
            'ai_response' => $aiMsg ?? ['sender' => 'ai', 'message' => $reply],
            'message' => $reply,
        ]);
    }

    public function clearHistory(Request $request)
    {
        $userId = $request->user()->id ?? 1;

        try {
            AiChatMessage::where('user_id', $userId)->delete();
        } catch (Throwable $e) {
            Log::error('Jaguza AI chat: failed to clear history.', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Could not clear chat history.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Chat history cleared successfully']);
    }

    public function feedback(Request $request, $id)
    {
        $message = AiChatMessage::find($id);

        if (! $message) {
            return response()->json(['success' => false, 'message' => 'Message not found'], 404);
        }

        $feedback = $request->input('feedback', 'helpful');
        $message->update([
            'feedback' => in_array($feedback, ['helpful', 'not_helpful'], true) ? $feedback : 'helpful',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback saved successfully',
            'data' => $message,
        ]);
    }
}
