<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiChatMessage;
use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiChatController extends Controller
{
    public function history(Request $request)
    {
        $user = $request->user();
        $history = AiChatMessage::where('user_id', $user->id ?? 1)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'language' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $userId = $request->user()->id ?? 1;

        // Save user message
        $userMsg = AiChatMessage::create([
            'user_id' => $userId,
            'sender' => 'user',
            'message' => $request->message,
        ]);

        $reply = $this->buildReply((string) $request->input('message'));
        $aiMsg = AiChatMessage::create([
            'user_id' => $userId,
            'sender' => 'ai',
            'message' => $reply,
        ]);

        return response()->json([
            'success' => true,
            'user_message' => $userMsg,
            'ai_response' => $aiMsg
        ]);
    }

    private function buildReply(string $message): string
    {
        $text = strtolower($message);

        if (str_contains($text, 'vaccine') || str_contains($text, 'vaccination')) {
            return "Vaccination guidance: keep a dated vaccination record, follow the schedule recommended by your local veterinarian, and do not vaccinate visibly sick animals without professional advice. I can help you identify the relevant disease if you share the animal type and symptoms.";
        }

        if (str_contains($text, 'feed') || str_contains($text, 'feeding') || str_contains($text, 'nutrition')) {
            return "Feeding guidance: provide clean water at all times, use feed appropriate for the animal's age and production stage, introduce feed changes gradually, and store feed in a dry protected place. Tell me the animal type and age for more specific guidance.";
        }

        if (str_contains($text, 'breed') || str_contains($text, 'breeding')) {
            return "Breeding guidance: select healthy animals, keep breeding records, monitor heat signs, and avoid breeding animals with known hereditary problems. A veterinarian should confirm pregnancy and address complications.";
        }

        if (str_contains($text, 'market') || str_contains($text, 'price') || str_contains($text, 'sell')) {
            return "For market decisions, compare at least three current buyers, record the animal's weight and condition, and include transport and treatment costs before accepting an offer. Prices depend on location and animal type.";
        }

        if (str_contains($text, 'health') || str_contains($text, 'disease') || str_contains($text, 'symptom') || str_contains($text, 'treat')) {
            $disease = Disease::active()->get()->first(function (Disease $disease) use ($text) {
                $diseaseText = strtolower(implode(' ', [
                    $disease->name,
                    $disease->species_affected,
                    $disease->symptoms,
                ]));
                return collect(preg_split('/\s+/', $text))
                    ->filter(fn ($word) => strlen($word) > 3)
                    ->contains(fn ($word) => str_contains($diseaseText, $word));
            });

            if ($disease) {
                return "A possible match from Jaguza's disease catalog is {$disease->name}. Symptoms: {$disease->symptoms}. Treatment guidance: {$disease->treatment}. This is not a confirmed diagnosis; isolate seriously ill animals and contact a veterinarian.";
            }

            return "For a sick animal, isolate it where possible, provide clean water, record its symptoms and temperature, and avoid giving human medicines. Share the animal type, symptoms, and how long they have been present, or use the Diagnosis screen for a catalog-based match.";
        }

        return "I am Jaguza AI. I can help with animal health, disease symptoms, feeding, breeding, vaccination planning, and farm decisions. Please include the animal type and specific symptoms when asking about sickness.";
    }

    public function clearHistory(Request $request)
    {
        $userId = $request->user()->id ?? 1;
        AiChatMessage::where('user_id', $userId)->delete();
        return response()->json(['success' => true, 'message' => 'Chat history cleared successfully']);
    }

    public function feedback(Request $request, $id)
    {
        $message = AiChatMessage::find($id);
        if (!$message) {
            return response()->json(['success' => false, 'message' => 'Message not found'], 404);
        }

        $message->update(['feedback' => $request->feedback ?? 'helpful']);

        return response()->json([
            'success' => true,
            'message' => 'Feedback saved successfully',
            'data' => $message
        ]);
    }
}
