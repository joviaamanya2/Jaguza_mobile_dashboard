  <!-- ===== GESTATION ===== -->
  <div class="page" id="page-gestation">
    <div class="section-heading">
      <h2><i class="fas fa-baby" style="color:#dc3545;margin-right:8px;"></i>Gestation Information</h2>
      <button class="btn btn-primary" onclick="openAddGestationModal()">+ Add Record</button>
    </div>
    <div class="stats-grid" style="margin-bottom:20px;">
      <div class="stat-card"><div class="stat-icon" style="background:#fde8e8;color:#c62828;"><i class="fas fa-clock"></i></div><div class="stat-body"><h3>{{ number_format($dueGestationsCount ?? 0) }}</h3><p>Due This Week</p></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-calendar-alt"></i></div><div class="stat-body"><h3>{{ number_format($dueGestationsThisMonth ?? 0) }}</h3><p>Due This Month</p></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-check-double"></i></div><div class="stat-body"><h3>{{ number_format($stats['total_gestations'] ?? 0) }}</h3><p>Total Active</p></div></div>
    </div>
    <div class="card">
      @forelse($dueGestations as $gestation)
      <div class="gestation-item">
        <div class="gestation-icon">
          @if($gestation->animal->type == 'cattle') &#x1F404;
          @elseif($gestation->animal->type == 'goat') &#x1F410;
          @elseif($gestation->animal->type == 'sheep') &#x1F411;
          @elseif($gestation->animal->type == 'pig') &#x1F416;
          @else &#x1F43E;
          @endif
        </div>
        <div class="gestation-body">
          <h4>{{ ucfirst($gestation->animal->type ?? 'Animal') }} – {{ $gestation->animal->name ?? $gestation->animal->identification_number ?? 'Unknown' }}
            @php $daysLeft = now()->diffInDays($gestation->expected_delivery_date, false); @endphp
            @if($daysLeft <= 3)<span class="badge badge-red">Due in {{ $daysLeft }} days</span>
            @elseif($daysLeft <= 14)<span class="badge badge-orange">Due in {{ $daysLeft }} days</span>
            @else<span class="badge badge-blue">Due in {{ $daysLeft }} days</span>@endif
          </h4>
          <p>Farm: {{ $gestation->animal->farm->name ?? 'N/A' }} · Mated: {{ $gestation->mating_date ? $gestation->mating_date->format('M d, Y') : 'N/A' }} · Expected: {{ $gestation->expected_delivery_date ? $gestation->expected_delivery_date->format('M d, Y') : 'N/A' }}</p>
          @php
            $totalDays = $gestation->mating_date ? now()->diffInDays($gestation->expected_delivery_date) : 280;
            $daysPassed = $gestation->mating_date ? $gestation->mating_date->diffInDays(now()) : 0;
            $progress = $totalDays > 0 ? min(100, round(($daysPassed / $totalDays) * 100)) : 0;
          @endphp
          <div class="progress-bar"><div class="progress-fill" style="width:{{ $progress }}%;"></div></div>
          <div class="progress-pct">{{ $progress }}% Complete</div>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:40px;color:#6a7a8a;"><p>No gestation records found.</p></div>
      @endforelse
    </div>
  </div>
