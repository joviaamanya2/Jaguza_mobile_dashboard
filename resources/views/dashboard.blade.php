@php
    // Get data from controller or use defaults
    $stats = $stats ?? [
        'total_users' => 0,
        'total_animals' => 0,
        'total_farms' => 0,
        'open_reports' => 0,
        'total_doctors' => 0,
        'total_videos' => 0,
        'active_ads' => 0,
        'total_gestations' => 0,
    ];
    
    $recentReports = $recentReports ?? [];
    $livestockByType = $livestockByType ?? [];
    $recentVideos = $recentVideos ?? [];
    $activeAds = $activeAds ?? [];
    $weatherUpdates = $weatherUpdates ?? [];
    $decisionSupport = $decisionSupport ?? [];
    $dueGestations = $dueGestations ?? [];
    $users = $users ?? [];
    $doctors = $doctors ?? [];
    $extensionWorkers = $extensionWorkers ?? [];
    $diseases = $diseases ?? [];
    $farms = $farms ?? [];
    $animals = $animals ?? [];
    $vaccinations = $vaccinations ?? [];
    $notifications = $notifications ?? [];
    $marketplaceListings = $marketplaceListings ?? [];
    $weatherAdvisories = $weatherAdvisories ?? [];
    $settings = $settings ?? [];
    $videoCategories = $videoCategories ?? [];
    $videoStats = $videoStats ?? [];
    $videos = $videos ?? [];
    
    $months = $months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
    $sicknessData = $sicknessData ?? [0,0,0,0,0,0,0];
    $userData = $userData ?? [0,0,0,0,0,0,0];
    $marketData = $marketData ?? [0,0,0,0,0,0,0];
    $livestockLabels = array_keys($livestockByType);
    $livestockValues = array_values($livestockByType);
    
    $userGrowthPercent = $userGrowthPercent ?? 0;
    $sicknessGrowthPercent = $sicknessGrowthPercent ?? 0;
    $farmGrowthPercent = $farmGrowthPercent ?? 0;
    $livestockGrowthPercent = $livestockGrowthPercent ?? 0;
    $newDoctors = $newDoctors ?? 0;
    $newVideosThisWeek = $newVideosThisWeek ?? 0;
    $expiredAds = $expiredAds ?? 0;
    $dueGestationsCount = $dueGestationsCount ?? 0;
    $dueGestationsThisMonth = $dueGestationsThisMonth ?? 0;
    $totalFarms = $totalFarms ?? 0;
    $activeFarms = $activeFarms ?? 0;
    $totalAnimalsOnFarms = $totalAnimalsOnFarms ?? 0;
    
    $user = auth()->user();
    
    // ============================================
    // HELPER FUNCTIONS - Check if they exist first
    // ============================================
    
    if (!function_exists('getInitials')) {
        function getInitials($name) {
            if (empty($name)) {
                return 'NA';
            }
            $words = explode(' ', $name);
            $initials = '';
            foreach ($words as $word) {
                if (!empty($word)) {
                    $initials .= strtoupper(substr($word, 0, 1));
                }
            }
            return substr($initials, 0, 2);
        }
    }
    
    if (!function_exists('getStatusBadge')) {
        function getStatusBadge($status) {
            return match($status) {
                'open', 'critical' => 'badge-red',
                'treating' => 'badge-orange',
                'resolved' => 'badge-green',
                'referred' => 'badge-blue',
                default => 'badge-purple',
            };
        }
    }
    
    if (!function_exists('getStatusDisplay')) {
        function getStatusDisplay($status) {
            return match($status) {
                'open' => 'Open',
                'treating' => 'Treating',
                'resolved' => 'Resolved',
                'critical' => 'Critical',
                'referred' => 'Referred',
                default => ucfirst($status),
            };
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jaguza Admin Dashboard – Livestock Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- ===== INCLUDE ALL STYLES ===== -->
    <style>
        @include('dashboard.partials.styles')
    </style>
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    @include('dashboard.partials.sidebar')

    <!-- ===== TOPBAR ===== -->
    @include('dashboard.partials.topbar')

    <!-- ===== MAIN CONTENT ===== -->
    <div id="main-wrap">

        <!-- ===== CURRENT PAGE ===== -->
        @include('dashboard.pages.' . ($initialPage ?? 'dashboard'))

    </div>

    <!-- ============================================ -->
    <!-- ===== MODALS ===== -->
    <!-- ============================================ -->
    @include('dashboard.modals.user')
    @include('dashboard.modals.doctor')
    @include('dashboard.modals.extension-worker')
    @include('dashboard.modals.animal')
    @include('dashboard.modals.report')
    @include('dashboard.modals.farm')
    @include('dashboard.modals.video')

    <!-- ===== SCRIPTS ===== -->
    <script>
        @include('dashboard.partials.scripts')
    </script>
    
    <!-- ===== FARM SCRIPTS (Animal Categories) ===== -->
    <script>
        @include('dashboard.partials.farm_script')
    </script>

</body>
</html>
