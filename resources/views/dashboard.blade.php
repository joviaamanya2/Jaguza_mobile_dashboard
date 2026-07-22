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
    $diseases = $diseases ?? [];
    $farms = $farms ?? [];
    $animals = $animals ?? [];
    $vaccinations = $vaccinations ?? [];
    $notifications = $notifications ?? [];
    $marketplaceListings = $marketplaceListings ?? [];
    $weatherAdvisories = $weatherAdvisories ?? [];
    $settings = $settings ?? [];
    
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
    
    $user = auth()->user();
    
    // ============================================
    // FIXED: Properly defined helper functions
    // ============================================
    
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
    
    function getStatusBadge($status) {
        return match($status) {
            'open', 'critical' => 'badge-red',
            'treating' => 'badge-orange',
            'resolved' => 'badge-green',
            'referred' => 'badge-blue',
            default => 'badge-purple',
        };
    }
    
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
  <style>
@include('dashboard.partials.styles')
  </style>
</head>
<body>

@include('dashboard.partials.sidebar')

@include('dashboard.partials.topbar')

<!-- MAIN CONTENT -->
<div id="main-wrap">

  @include('dashboard.pages.dashboard')
  @include('dashboard.pages.users')
  @include('dashboard.pages.doctors')
  @include('dashboard.pages.sickness')
  @include('dashboard.pages.disease')
  @include('dashboard.pages.farms')
  @include('dashboard.pages.livestock')
  @include('dashboard.pages.videos')
  @include('dashboard.pages.ads')
  @include('dashboard.pages.gestation')
  @include('dashboard.pages.notifications')
  @include('dashboard.pages.analytics')
  @include('dashboard.pages.vaccinations')
  @include('dashboard.pages.marketplace')
  @include('dashboard.pages.weather')
  @include('dashboard.pages.aichat')
  @include('dashboard.pages.decision')
  @include('dashboard.pages.settings')

</div>

<!-- ============================================ -->
<!-- ===== MODALS ===== -->
<!-- ============================================ -->
@include('dashboard.modals.user')
@include('dashboard.modals.doctor')
@include('dashboard.modals.animal')
@include('dashboard.modals.farm')

<script>
@include('dashboard.partials.scripts')
</script>
</body>
</html>
