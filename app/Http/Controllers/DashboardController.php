<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Farm;
use App\Models\User;
use App\Models\Doctor;
use App\Models\SicknessReport;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\Advertisement;
use App\Models\WeatherUpdate;
use App\Models\DecisionSupport;
use App\Models\GestationRecord;
use App\Models\VaccinationRecord;
use App\Models\MarketplaceListing;
use App\Models\Notification;
use App\Models\Message;
use App\Models\Setting;
use App\Models\Disease;
use App\Models\ExtensionWorker;
use App\Models\AiChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request, ?string $page = null)
    {
        $initialPage = $request->route('page') ?: ($page ?: 'dashboard');

        // Get all data for the dashboard
        $stats = $this->getStats();
        $recentReports = $this->getRecentReports();
        $livestockByType = $this->getLivestockByType();
        $recentVideos = $this->getRecentVideos();
        $defaultVideoCategories = [
            ['name' => 'Cattle', 'slug' => 'cattle'],
            ['name' => 'Poultry', 'slug' => 'poultry'],
            ['name' => 'Pigs', 'slug' => 'pigs'],
            ['name' => 'Goats', 'slug' => 'goats'],
        ];
        foreach ($defaultVideoCategories as $position => $category) {
            VideoCategory::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name'], 'order' => $position, 'is_active' => true]
            );
        }
        $videoCategories = VideoCategory::active()->orderBy('order')->orderBy('name')->get();
        $activeAds = $this->getActiveAds();
        $weatherUpdates = $this->getWeatherUpdates();
        $decisionSupport = $this->getDecisionSupport();
        $dueGestations = $this->getDueGestations();
        $users = $this->getUsers();
        $doctors = $this->getDoctors();
        $extensionWorkers = $this->getExtensionWorkers();
        $diseases = $this->getDiseases();
        $farms = $this->getFarms();
        $animals = $this->getAnimals();
        $vaccinations = $this->getVaccinations();
        $messages = $this->getMessages();
        $notifications = $this->getNotifications();
        $marketplaceListings = $this->getMarketplaceListings();
        $settings = $this->getSettings();
        $weatherAdvisories = $this->getWeatherAdvisories();
        $aiChatConversations = $this->getAiChatConversations($request);

        // Filters and collection expected by the decision-support dashboard page.
        $resources = $decisionSupport;
        $categories = ['cattle', 'goat', 'sheep', 'poultry', 'pig', 'rabbit'];
        $topics = ['feeding', 'health', 'breeding', 'housing', 'marketing'];
        
        // Chart data
        $chartData = $this->getChartData();
        $months = $chartData['months'];
        $sicknessData = $chartData['sicknessData'];
        $userData = $chartData['userData'];
        $marketData = $chartData['marketData'];
        
        // Calculate growth percentages
        $userGrowthPercent = $this->calculateGrowth(User::class);
        $sicknessGrowthPercent = $this->calculateGrowth(SicknessReport::class);
        $farmGrowthPercent = $this->calculateGrowth(Farm::class);
        $livestockGrowthPercent = $this->calculateGrowth(Animal::class);
        $marketVolumeGrowthPercent = $this->calculateSumGrowth(MarketplaceListing::class, 'price');
        $videoViewsGrowthPercent = $this->calculateSumGrowth(Video::class, 'views_count');

        // Analytics page: 12-month user growth trend and top reported symptoms.
        $analyticsUserGrowth = $this->getAnalyticsUserGrowth();
        $analyticsMonths = $analyticsUserGrowth['months'];
        $analyticsUserData = $analyticsUserGrowth['data'];
        $diseaseCases = $this->getDiseaseCaseData();
        $diseaseLabels = $diseaseCases->pluck('symptom_primary');
        $diseaseCounts = $diseaseCases->pluck('count');

        // These values power the farm-page summary cards. Keep them separate
        // from the limited list of recently created farms shown in the table.
        $totalFarms = $stats['total_farms'];
        $activeFarms = Farm::where('is_active', true)->count();
        $totalAnimalsOnFarms = Animal::whereNotNull('farm_id')->count();
        
        $newDoctors = Doctor::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $newVideosThisWeek = Video::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $expiredAds = Advertisement::where('status', 'expired')->count();
        $dueGestationsCount = GestationRecord::whereBetween('expected_delivery_date', [now(), now()->addDays(7)])
            ->whereNull('actual_delivery_date')
            ->count();
        $dueGestationsThisMonth = GestationRecord::whereBetween('expected_delivery_date', [now(), now()->addMonth()])
            ->whereNull('actual_delivery_date')
            ->count();

        // Return the view with all data
        // Doctor stats for the doctors page
        $available_doctors = Doctor::where('is_available', true)->count();
        $busy_doctors = Doctor::where('is_available', false)->count();
        $total_cases = Doctor::sum('total_cases');

        // Extension worker stats for the extension workers page
        $available_workers = ExtensionWorker::where('is_available', true)->count();
        $busy_workers = ExtensionWorker::where('is_available', false)->count();
        $total_farm_visits = ExtensionWorker::sum('total_farm_visits');

        return view('dashboard', compact(
            'stats',
            'recentReports',
            'livestockByType',
            'recentVideos',
            'videoCategories',
            'activeAds',
            'weatherUpdates',
            'decisionSupport',
            'initialPage',
            'resources',
            'categories',
            'topics',
            'dueGestations',
            'users',
            'doctors',
            'extensionWorkers',
            'diseases',
            'farms',
            'animals',
            'vaccinations',
            'messages',
            'notifications',
            'marketplaceListings',
            'settings',
            'weatherAdvisories',
            'aiChatConversations',
            'months',
            'sicknessData',
            'userData',
            'marketData',
            'userGrowthPercent',
            'sicknessGrowthPercent',
            'farmGrowthPercent',
            'livestockGrowthPercent',
            'marketVolumeGrowthPercent',
            'videoViewsGrowthPercent',
            'analyticsMonths',
            'analyticsUserData',
            'diseaseLabels',
            'diseaseCounts',
            'totalFarms',
            'activeFarms',
            'totalAnimalsOnFarms',
            'newDoctors',
            'newVideosThisWeek',
            'expiredAds',
            'dueGestationsCount',
            'dueGestationsThisMonth',
            'available_doctors',
            'busy_doctors',
            'total_cases',
            'available_workers',
            'busy_workers',
            'total_farm_visits'
        ));
    }

    private function getStats()
    {
        return [
            'total_users' => User::count(),
            'total_farms' => Farm::count(),
            'total_animals' => Animal::count(),
            'total_doctors' => Doctor::count(),
            'total_extension_workers' => ExtensionWorker::count(),
            'open_reports' => SicknessReport::where('status', 'open')->count(),
            'under_treatment' => SicknessReport::where('status', 'treating')->count(),
            'resolved_reports' => SicknessReport::where('status', 'resolved')->count(),
            'total_videos' => Video::where('is_published', true)->count(),
            'active_ads' => Advertisement::where('status', 'active')->count(),
            'total_gestations' => GestationRecord::count(),
            'total_market_volume' => MarketplaceListing::whereIn('status', ['active', 'sold'])->sum('price'),
            'total_video_views' => Video::sum('views_count'),
        ];
    }

    private function getRecentReports()
    {
        return SicknessReport::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    private function getLivestockByType()
    {
        return Animal::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    private function getChartData()
    {
        $months = [];
        $sicknessData = [];
        $userData = [];
        $marketData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M');
            
            $sicknessData[] = SicknessReport::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
                
            $userData[] = User::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
                
            $marketData[] = Advertisement::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        return [
            'months' => $months,
            'sicknessData' => $sicknessData,
            'userData' => $userData,
            'marketData' => $marketData,
        ];
    }

    private function getRecentVideos()
    {
        return Video::with('category')
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
    }

    private function getActiveAds()
    {
        // The ads management page needs to show pending submissions (from the
        // app's "Advertise with Jaguza" form) too, not just already-approved
        // ones, so admins have something to act on.
        return Advertisement::with('creator')
            ->orderByRaw("FIELD(status, 'pending', 'active', 'draft', 'expired')")
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    private function getWeatherUpdates()
    {
        return WeatherUpdate::orderBy('weather_data_time', 'desc')
            ->limit(4)
            ->get();
    }

    private function getDecisionSupport()
    {
        return DecisionSupport::where('is_published', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
    }

    private function getDueGestations()
    {
        return GestationRecord::with('animal')
            ->whereNull('actual_delivery_date')
            ->whereBetween('expected_delivery_date', [now(), now()->addDays(7)])
            ->limit(4)
            ->get();
    }

    private function getUsers()
    {
        return User::orderBy('created_at', 'desc')->limit(10)->get();
    }

    private function getDoctors()
    {
        return Doctor::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
    }

    private function getExtensionWorkers()
    {
        return ExtensionWorker::with('user')->orderBy('created_at', 'desc')->limit(10)->get();
    }

    private function getDiseases()
    {
        return Disease::orderBy('name')->limit(10)->get();
    }

    private function getFarms()
    {
        return Farm::with(['user', 'owner', 'animals'])->orderBy('created_at', 'desc')->limit(10)->get();
    }

    private function getAnimals()
    {
        return Animal::with(['farm', 'owner'])->orderBy('created_at', 'desc')->limit(10)->get();
    }

    private function getVaccinations()
    {
        return VaccinationRecord::with(['animal', 'administeredBy'])->orderBy('created_at', 'desc')->limit(10)->get();
    }

    private function getMessages()
    {
        return Message::with('sender')->orderBy('created_at', 'desc')->limit(10)->get();
    }

    private function getNotifications()
    {
        return Notification::orderBy('created_at', 'desc')->limit(10)->get();
    }

    public function deleteAiConversation(string $key)
    {
        if (str_starts_with($key, 'message-')) {
            AiChatMessage::whereKey((int) substr($key, 8))->delete();
        } else {
            AiChatMessage::where('session_id', $key)->delete();
        }

        return redirect()
            ->route('dashboard', ['page' => 'aichat'])
            ->with('success', 'AI conversation deleted successfully.');
    }

    private function getAiChatConversations(Request $request): array
    {
        $messages = AiChatMessage::with('user')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $conversations = $messages->groupBy(function (AiChatMessage $message) {
            return $message->session_id ?: 'message-'.$message->id;
        })->map(function ($items, $key) {
            $userMessages = $items->where('sender', 'user');
            $content = $userMessages->pluck('message')->filter()->implode("\n");
            $first = $items->first();
            $last = $items->last();
            $seconds = max(0, $first->created_at->diffInSeconds($last->created_at));

            return [
                'key' => $key,
                'user_id' => $first->user_id,
                'user_name' => optional($first->user)->name ?: 'Unknown user',
                'content' => $content ?: $items->pluck('message')->filter()->implode("\n"),
                'category' => $this->aiConversationCategory($content),
                'duration' => $this->formatAiDuration($seconds),
                'message_count' => $items->count(),
                'last_activity' => $last->created_at,
            ];
        })->sortByDesc('last_activity')->values();

        $category = $request->string('ai_category')->toString();
        $userId = $request->string('ai_user_id')->toString();
        $search = strtolower($request->string('ai_search')->toString());

        return $conversations->filter(function (array $conversation) use ($category, $userId, $search) {
            return ($category === '' || $conversation['category'] === $category)
                && ($userId === '' || (string) $conversation['user_id'] === $userId)
                && ($search === '' || str_contains(strtolower($conversation['content']), $search));
        })->values()->all();
    }

    private function aiConversationCategory(string $content): string
    {
        $text = strtolower($content);
        $categories = [
            'Health and Disease' => ['sick', 'disease', 'symptom', 'treat', 'medicine', 'fever', 'cough', 'diarrhea', 'wound'],
            'Feeding and Nutrition' => ['feed', 'feeding', 'nutrition', 'fodder', 'pasture', 'grazing', 'ration'],
            'Breeding' => ['breed', 'breeding', 'heat', 'pregnan', 'mating', 'calving', 'lambing', 'kidding'],
            'Vaccination' => ['vaccin', 'immuni', 'deworm'],
            'Markets and Prices' => ['market', 'price', 'sell', 'buyer', 'cost'],
        ];

        foreach ($categories as $name => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) return $name;
            }
        }

        return 'General Farm Advice';
    }

    private function formatAiDuration(int $seconds): string
    {
        if ($seconds < 60) return $seconds.' sec';
        $minutes = intdiv($seconds, 60);
        if ($minutes < 60) return $minutes.' min';
        return intdiv($minutes, 60).' hr '.($minutes % 60).' min';
    }

    private function getMarketplaceListings()
    {
        return MarketplaceListing::with('seller')->orderBy('created_at', 'desc')->limit(10)->get();
    }

    private function getSettings()
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    private function getWeatherAdvisories()
    {
        return WeatherUpdate::whereNotNull('advisory')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }

    private function calculateGrowth($model)
    {
        $current = $model::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $previous = $model::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        if ($previous == 0) return 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    // Same idea as calculateGrowth() but compares a summed column (e.g. price,
    // views_count) for rows created this month vs last month, instead of a
    // row count. Used for the Analytics page's Market Volume / Video Views
    // growth badges.
    private function calculateSumGrowth($model, string $column)
    {
        $current = $model::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum($column);

        $previous = $model::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum($column);

        if ($previous == 0) return 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    // 12 months of real new-user counts for the Analytics page's user growth
    // line chart (the main dashboard's userChart only covers the last 7).
    private function getAnalyticsUserGrowth()
    {
        $months = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M');
            $data[] = User::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        return ['months' => $months, 'data' => $data];
    }

    // Top reported symptoms across all sickness reports, for the Analytics
    // page's "Disease Cases by Type" chart. There's no disease_id on
    // SicknessReport, so the reported primary symptom is the closest real
    // grouping available.
    private function getDiseaseCaseData()
    {
        return SicknessReport::select('symptom_primary', DB::raw('count(*) as count'))
            ->whereNotNull('symptom_primary')
            ->groupBy('symptom_primary')
            ->orderByDesc('count')
            ->limit(6)
            ->get();
    }
}
