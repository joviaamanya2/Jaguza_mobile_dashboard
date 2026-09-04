<?php

namespace App\Services;

use App\Models\Disease;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Produces the assistant reply for the Jaguza AI chat.
 *
 * When an LLM provider is configured (AI_API_KEY) it talks to an
 * OpenAI-compatible chat-completions endpoint, giving the conversation real
 * back-and-forth. When it is not configured, or the call fails, it falls back
 * to a local farming knowledge base so the chat always answers.
 */
class AiChatService
{
    /**
     * @param  array<int, array{sender:string, message:string}>  $history  Prior turns, oldest first.
     * @return array{message:string, source:string}
     */
    public function reply(string $message, array $history = [], ?string $language = null): array
    {
        $message = trim($message);
        $language = $language ?: 'English';

        if ($this->configured()) {
            try {
                $answer = $this->askLlm($message, $history, $language);
                if ($answer !== null && $answer !== '') {
                    return ['message' => $answer, 'source' => 'ai'];
                }
            } catch (Throwable $e) {
                Log::warning('Jaguza AI chat: LLM call failed, using knowledge base.', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return ['message' => $this->knowledgeBaseReply($message, $language), 'source' => 'knowledge-base'];
    }

    private function configured(): bool
    {
        return filled(config('services.ai.key'));
    }

    /**
     * @param  array<int, array{sender:string, message:string}>  $history
     */
    private function askLlm(string $message, array $history, string $language): ?string
    {
        $messages = [[
            'role' => 'system',
            'content' => $this->systemPrompt($language, $message),
        ]];

        foreach (array_slice($history, -10) as $turn) {
            $role = ($turn['sender'] ?? 'user') === 'ai' ? 'assistant' : 'user';
            $content = trim((string) ($turn['message'] ?? ''));
            if ($content !== '') {
                $messages[] = ['role' => $role, 'content' => $content];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $baseUrl = rtrim((string) config('services.ai.base_url'), '/');

        $response = Http::withToken((string) config('services.ai.key'))
            ->timeout((int) config('services.ai.timeout', 30))
            ->acceptJson()
            ->post($baseUrl.'/chat/completions', [
                'model' => config('services.ai.model'),
                'messages' => $messages,
                'temperature' => 0.4,
                'max_tokens' => 600,
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('AI provider responded with HTTP '.$response->status());
        }

        $content = $response->json('choices.0.message.content');

        return is_string($content) ? trim($content) : null;
    }

    private function systemPrompt(string $language, string $message): string
    {
        $prompt = "You are Jaguza AI, a practical assistant for smallholder farmers in East Africa. "
            ."You help with livestock health, disease symptoms, feeding, breeding, vaccination planning, "
            ."aquaculture, crops and farm business decisions. "
            ."Keep answers concise, structured and actionable. Use simple language and short steps. "
            ."Always remind the farmer to isolate seriously ill animals and contact a qualified "
            ."veterinarian for diagnosis, prescriptions or emergencies. Never recommend human medicines "
            ."for animals. If a question is outside farming, answer briefly and steer back to farming. "
            ."Reply in {$language}.";

        $context = $this->diseaseContext($message);
        if ($context !== '') {
            $prompt .= "\n\nRelevant entries from the Jaguza disease catalog (use only if they match "
                ."the question, and present them as possibilities, not a diagnosis):\n".$context;
        }

        return $prompt;
    }

    private function knowledgeBaseReply(string $message, string $language): string
    {
        $text = strtolower($message);
        $note = '';

        if (str_contains($text, 'vaccin') || str_contains($text, 'immuni')) {
            return "Vaccination guidance: keep a dated vaccination record, follow the schedule recommended "
                ."by your local veterinarian, and do not vaccinate visibly sick animals without professional "
                ."advice. Tell me the animal type and its age and I can help you plan the next dose.".$note;
        }

        if (str_contains($text, 'feed') || str_contains($text, 'nutrition') || str_contains($text, 'graz')
            || str_contains($text, 'pasture') || str_contains($text, 'ration') || str_contains($text, 'fodder')) {
            return "Feeding guidance: provide clean water at all times, use feed suited to the animal's age "
                ."and production stage, introduce any feed change gradually over 7-10 days, and store feed "
                ."dry and off the ground. Share the animal type and age for a more specific ration.".$note;
        }

        if (str_contains($text, 'breed') || str_contains($text, 'breeding') || str_contains($text, 'heat')) {
            return "Breeding guidance: select healthy animals with good records, watch for heat signs, keep "
                ."mating and expected-birth dates, and avoid breeding animals with known hereditary problems. "
                ."A veterinarian should confirm pregnancy and manage any complications.".$note;
        }

        if (str_contains($text, 'market') || str_contains($text, 'price') || str_contains($text, 'sell')) {
            return "For market decisions, compare at least three current buyers, record the animal's weight "
                ."and body condition, and subtract transport and treatment costs before you accept an offer. "
                ."Prices vary by location, season and animal type.".$note;
        }

        $healthCues = [
            'health', 'disease', 'sick', 'ill', 'symptom', 'treat', 'medicine', 'infection', 'fever',
            'diarrhea', 'diarrhoea', 'cough', 'wound', 'injur', 'bloat', 'lame', 'limp', 'swelling',
            'not eating', 'wont eat', 'won\'t eat', 'weak', 'weight loss', 'blood', 'worms', 'parasite',
            'mastitis', 'abortion', 'discharge', 'dying', 'died', 'vomit',
        ];

        foreach ($healthCues as $cue) {
            if (str_contains($text, $cue)) {
                $isHealth = true;
                break;
            }
        }

        if (! empty($isHealth)) {
            $disease = $this->matchDisease($text);
            if ($disease) {
                return "A possible match from Jaguza's disease catalog is {$disease->name}. "
                    ."Symptoms: {$disease->symptoms}. Treatment guidance: {$disease->treatment}. "
                    ."This is not a confirmed diagnosis - isolate seriously ill animals and contact a "
                    ."veterinarian.".$note;
            }

            return "For a sick animal: isolate it if you can, give clean water, record the symptoms, how "
                ."long they have lasted and the temperature, and avoid human medicines. Share the animal "
                ."type and symptoms, or use the Diagnosis screen for a catalog-based match.".$note;
        }

        return "I am Jaguza AI. I can help with animal health, disease symptoms, feeding, breeding, "
            ."vaccination planning, aquaculture, crops and farm decisions. Ask me a question and include "
            ."the animal type and any symptoms you have noticed.".$note;
    }

    private function diseaseContext(string $message): string
    {
        try {
            $disease = $this->matchDisease(strtolower($message));
        } catch (Throwable) {
            return '';
        }

        if (! $disease) {
            return '';
        }

        return "- {$disease->name} (affects {$disease->species_affected}). Symptoms: {$disease->symptoms}. "
            ."Treatment: {$disease->treatment}. Prevention: {$disease->prevention}.";
    }

    private function matchDisease(string $text): ?Disease
    {
        $words = collect(preg_split('/\s+/', $text))
            ->filter(fn ($word) => strlen($word) > 3)
            ->values();

        if ($words->isEmpty()) {
            return null;
        }

        return Disease::active()->get()->first(function (Disease $disease) use ($words) {
            $haystack = strtolower(implode(' ', [
                $disease->name,
                $disease->species_affected,
                $disease->symptoms,
            ]));

            return $words->contains(fn ($word) => str_contains($haystack, $word));
        });
    }
}
