  <!-- ===== LIVESTOCK ===== -->
  <div class="page" id="page-livestock">
    <div class="section-heading">
      <h2><i class="fas fa-horse" style="color:#2e7d32;margin-right:8px;"></i>Livestock Animals</h2>
      <button class="btn btn-primary" onclick="openAddAnimalModal()">+ Add Animal</button>
    </div>
    <div class="animal-grid" style="margin-bottom:24px;">
      @forelse($livestockByType as $type => $count)
      <div class="animal-card">
        <div class="animal-emoji">
          @switch($type)
            @case('cattle') &#x1F404; @break
            @case('goat') &#x1F410; @break
            @case('sheep') &#x1F411; @break
            @case('pig') &#x1F416; @break
            @case('poultry') &#x1F414; @break
            @case('rabbit') &#x1F407; @break
            @default &#x1F43E;
          @endswitch
        </div>
        <h4>{{ ucfirst($type) }}</h4>
        <p>Livestock</p>
        <div class="animal-stat">{{ number_format($count) }}</div>
      </div>
      @empty
      <div class="animal-card"><div class="animal-emoji">&#x1F43E;</div><h4>No Animals</h4><p>Add your first animal</p><div class="animal-stat">0</div></div>
      @endforelse
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>ID</th><th>Animal</th><th>Breed</th><th>Age</th><th>Farm</th><th>Health</th><th>Last Checkup</th></tr></thead>
          <tbody>
            @forelse($animals as $animal)
            <tr>
              <td>{{ $animal->identification_number ?? 'N/A' }}</td>
              <td>{{ $animal->name ?? 'N/A' }}</td>
              <td>{{ ucfirst($animal->breed ?? 'N/A') }}</td>
              <td>{{ $animal->age ?? '0' }} {{ ($animal->age ?? 0) > 1 ? 'yrs' : 'yr' }}</td>
              <td>{{ $animal->farm->name ?? 'N/A' }}</td>
              <td><span class="badge @if($animal->health_status == 'healthy') badge-green @elseif($animal->health_status == 'sick' || $animal->health_status == 'critical') badge-red @else badge-orange @endif">{{ ucfirst($animal->health_status ?? 'Unknown') }}</span></td>
              <td>{{ $animal->updated_at ? $animal->updated_at->format('M d, Y') : 'N/A' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:#6a7a8a;">No animals found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
