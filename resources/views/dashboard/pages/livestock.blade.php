  <!-- ===== LIVESTOCK ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'livestock' ? 'active' : '' }}" id="page-livestock">
    <div class="section-heading">
      <h2><i class="fas fa-horse" style="color:#2e7d32;margin-right:8px;"></i>Livestock Animals</h2>
      <button class="btn btn-primary" onclick="openAddAnimalModal()">+ Add Animal</button>
    </div>
    <div class="animal-grid" style="margin-bottom:24px;">
      @forelse($livestockByType as $type => $count)
      <div class="animal-card livestock-summary-card">
        <div class="animal-icon">
          @switch($type)
            @case('cattle') <i class="fas fa-horse" aria-hidden="true"></i> @break
            @case('goat') <i class="fas fa-paw" aria-hidden="true"></i> @break
            @case('sheep') <i class="fas fa-paw" aria-hidden="true"></i> @break
            @case('pig') <i class="fas fa-paw" aria-hidden="true"></i> @break
            @case('poultry') <i class="fas fa-feather-alt" aria-hidden="true"></i> @break
            @case('rabbit') <i class="fas fa-paw" aria-hidden="true"></i> @break
            @default <i class="fas fa-paw" aria-hidden="true"></i>
          @endswitch
        </div>
        <h4>{{ ucfirst($type === 'pig' ? 'Pigs' : $type) }}</h4>
        <p>Livestock</p>
        <div class="animal-stat">{{ number_format($count) }}</div>
      </div>
      @empty
      <div class="animal-card"><div class="animal-icon"><i class="fas fa-paw" aria-hidden="true"></i></div><h4>No Animals</h4><p>Add your first animal</p><div class="animal-stat">0</div></div>
      @endforelse
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>ID</th><th>Photo</th><th>Animal</th><th>Breed</th><th>Age</th><th>Farm</th><th>Health</th><th>Last Checkup</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($animals as $animal)
            <tr>
              <td>{{ $animal->identification_number ?? 'N/A' }}</td>
              <td>
                @if($animal->photo)
                  <img src="{{ $animal->photo }}" alt="{{ $animal->name ?? 'Animal' }}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;">
                @else
                  <span style="display:inline-flex;width:44px;height:44px;align-items:center;justify-content:center;background:#f0f2f5;border-radius:8px;color:#8c9aab;"><i class="fas fa-paw"></i></span>
                @endif
              </td>
              <td><span class="livestock-row-icon" aria-hidden="true"><i class="fas @switch($animal->type) @case('cattle') fa-horse @break @case('poultry') fa-feather-alt @break @default fa-paw @endswitch"></i></span> {{ $animal->name ?? 'N/A' }}</td>
              <td>{{ ucfirst($animal->breed ?? 'N/A') }}</td>
              <td>{{ $animal->age ?? '0' }} {{ ($animal->age ?? 0) > 1 ? 'yrs' : 'yr' }}</td>
              <td>{{ $animal->farm->name ?? 'N/A' }}</td>
              <td><span class="badge @if($animal->health_status == 'healthy') badge-green @elseif($animal->health_status == 'sick' || $animal->health_status == 'critical') badge-red @else badge-orange @endif">{{ ucfirst($animal->health_status ?? 'Unknown') }}</span></td>
              <td>{{ $animal->updated_at ? $animal->updated_at->format('M d, Y') : 'N/A' }}</td>
              <td class="livestock-actions"><div class="livestock-action-group"><button type="button" class="livestock-action livestock-edit-action" title="Edit animal" aria-label="Edit animal" onclick="editLivestockAnimal({{ $animal->id }})"><i class="fas fa-pen"></i></button><button type="button" class="livestock-action livestock-delete-action" title="Delete animal" aria-label="Delete animal" onclick="deleteLivestockAnimal({{ $animal->id }})"><i class="fas fa-trash"></i></button></div></td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;padding:40px;color:#6a7a8a;">No animals found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
    function editLivestockAnimal(id) { editAnimal(id); }
    function deleteLivestockAnimal(id) {
      if (!confirm('Delete this animal? This action cannot be undone.')) return;
      fetch(`/admin/animals/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json'} }).then(() => window.location.reload());
    }
  </script>
