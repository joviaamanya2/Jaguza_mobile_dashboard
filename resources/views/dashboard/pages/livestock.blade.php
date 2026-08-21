  <!-- ===== LIVESTOCK ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'livestock' ? 'active' : '' }}" id="page-livestock">
    <div class="section-heading">
      <h2><i class="fas fa-horse" style="color:#2e7d32;margin-right:8px;"></i>Livestock Animals</h2>
      <button class="btn btn-primary" onclick="openAddAnimalModal()">+ Add Animal</button>
    </div>
    <div class="animal-grid" style="margin-bottom:24px;">
      @forelse($livestockByType as $type => $count)
      <div class="animal-card livestock-summary-card">
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
        <h4>{{ ucfirst($type === 'pig' ? 'Pigs' : $type) }}</h4>
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
          <thead><tr><th>ID</th><th>Animal</th><th>Breed</th><th>Age</th><th>Farm</th><th>Health</th><th>Last Checkup</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($animals as $animal)
            <tr>
              <td>{{ $animal->identification_number ?? 'N/A' }}</td>
              <td><span class="livestock-row-emoji">@switch($animal->type) @case('cattle') 🐄 @break @case('goat') 🐐 @break @case('sheep') 🐑 @break @case('pig') 🐖 @break @case('poultry') 🐔 @break @case('rabbit') 🐇 @break @default 🐾 @endswitch</span> {{ $animal->name ?? 'N/A' }}</td>
              <td>{{ ucfirst($animal->breed ?? 'N/A') }}</td>
              <td>{{ $animal->age ?? '0' }} {{ ($animal->age ?? 0) > 1 ? 'yrs' : 'yr' }}</td>
              <td>{{ $animal->farm->name ?? 'N/A' }}</td>
              <td><span class="badge @if($animal->health_status == 'healthy') badge-green @elseif($animal->health_status == 'sick' || $animal->health_status == 'critical') badge-red @else badge-orange @endif">{{ ucfirst($animal->health_status ?? 'Unknown') }}</span></td>
              <td>{{ $animal->updated_at ? $animal->updated_at->format('M d, Y') : 'N/A' }}</td>
              <td class="livestock-actions"><div class="livestock-action-group"><button type="button" class="livestock-action livestock-edit-action" title="Edit animal" aria-label="Edit animal" onclick="editLivestockAnimal({{ $animal->id }})"><i class="fas fa-pen"></i></button><button type="button" class="livestock-action livestock-delete-action" title="Delete animal" aria-label="Delete animal" onclick="deleteLivestockAnimal({{ $animal->id }})"><i class="fas fa-trash"></i></button></div></td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:#6a7a8a;">No animals found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
    function editLivestockAnimal(id) { openAddAnimalModal(); if (typeof showToast === 'function') showToast('Edit animal form opened.'); }
    function deleteLivestockAnimal(id) {
      if (!confirm('Delete this animal? This action cannot be undone.')) return;
      fetch(`/admin/animals/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json'} }).then(() => window.location.reload());
    }
  </script>
