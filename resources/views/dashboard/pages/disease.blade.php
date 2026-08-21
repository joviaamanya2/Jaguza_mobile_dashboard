  <!-- ===== DISEASE INFO ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'disease' ? 'active' : '' }}" id="page-disease">
    <div class="section-heading disease-page-heading">
      <div>
        <h2><i class="fas fa-virus" style="color:#fd7e14;margin-right:8px;"></i>Disease Information</h2>
        <p>Reference information for livestock diseases and their management.</p>
      </div>
      <button class="btn btn-primary" onclick="openAddDiseaseModal()"><i class="fas fa-plus"></i> Add Disease</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table class="disease-table">
          <thead><tr><th>Disease</th><th>Species Affected</th><th>Causes</th><th>Prevention</th><th>Treatment</th><th>Severity</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($diseases as $disease)
            <tr>
              <td class="disease-name-cell">
                @if($disease->thumbnail)
                  <img src="{{ asset('storage/' . $disease->thumbnail) }}" alt="{{ $disease->name }} thumbnail" class="disease-thumbnail">
                @else
                  <span class="disease-thumbnail disease-thumbnail-placeholder"><i class="fas fa-virus"></i></span>
                @endif
                <strong style="color:#1a1a2e;">{{ $disease->name }}</strong>
              </td>
              <td>{{ $disease->species_affected ?? 'N/A' }}</td>
              <td>{{ Str::limit($disease->transmission ?? 'Not specified', 55) }}</td>
              <td>{{ Str::limit($disease->prevention ?? 'Not specified', 55) }}</td>
              <td>{{ Str::limit($disease->treatment ?? '', 50) }}</td>
              <td><span class="badge @if($disease->severity == 'high' || $disease->severity == 'critical') badge-red @elseif($disease->severity == 'medium') badge-orange @else badge-green @endif">{{ ucfirst($disease->severity ?? 'Unknown') }}</span></td>
              <td class="disease-actions">
                <button type="button" class="disease-action-btn disease-edit-btn" title="Edit disease" aria-label="Edit disease" onclick="editDisease({{ $disease->id }}, @js($disease->toArray()))"><i class="fas fa-pen"></i></button>
                <button type="button" class="disease-action-btn disease-delete-btn" title="Delete disease" aria-label="Delete disease" onclick="deleteDisease({{ $disease->id }})"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:#6a7a8a;">No diseases found.</td></tr>
            @endforelse
          </tbody>
        </table>
  </div>
  <div class="modal-overlay" id="diseaseModal">
    <div class="modal-box disease-modal-box">
      <div class="modal-header">
        <div><h3>Add Disease Information</h3><p class="modal-subtitle">Add the disease details and a thumbnail image.</p></div>
        <button type="button" class="modal-close" onclick="closeModal('diseaseModal')" aria-label="Close">&times;</button>
      </div>
      <form id="diseaseModalForm" action="{{ url('/admin/diseases') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group"><label for="disease_name">Disease name <span class="required">*</span></label><input id="disease_name" name="name" class="form-control" required value="{{ old('name') }}">@error('name')<small class="form-error">{{ $message }}</small>@enderror</div>
        <div class="form-group"><label for="disease_thumbnail">Disease thumbnail</label><input id="disease_thumbnail" name="thumbnail" class="form-control" type="file" accept="image/jpeg,image/png,image/webp"><small class="form-help">JPG, PNG or WEBP, maximum 2 MB.</small>@error('thumbnail')<small class="form-error">{{ $message }}</small>@enderror</div>
        <div class="form-row"><div class="form-group"><label for="disease_species">Species affected <span class="required">*</span></label><input id="disease_species" name="species_affected" class="form-control" required value="{{ old('species_affected') }}"></div><div class="form-group"><label for="disease_severity">Severity <span class="required">*</span></label><select id="disease_severity" name="severity" class="form-control" required>@foreach(['low','medium','high','critical'] as $severity)<option value="{{ $severity }}" @selected(old('severity','medium') === $severity)>{{ ucfirst($severity) }}</option>@endforeach</select></div></div>
        <div class="form-group"><label for="disease_causes">Causes</label><textarea id="disease_causes" name="transmission" class="form-control" rows="2">{{ old('transmission') }}</textarea></div>
        <div class="form-group"><label for="disease_prevention">Prevention</label><textarea id="disease_prevention" name="prevention" class="form-control" rows="2">{{ old('prevention') }}</textarea></div>
        <div class="form-group"><label for="disease_treatment">Treatment <span class="required">*</span></label><textarea id="disease_treatment" name="treatment" class="form-control" rows="2" required>{{ old('treatment') }}</textarea></div>
        <input type="hidden" name="symptoms" value="{{ old('symptoms', 'See disease information') }}"><input type="hidden" name="outbreak_risk" value="{{ old('outbreak_risk', 'medium') }}">
        <div class="modal-footer"><button type="button" class="btn btn-outline" onclick="closeModal('diseaseModal')">Cancel</button><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save disease</button></div>
      </form>
    </div>
  </div>
  <script>
    async function deleteDisease(id) {
      if (!confirm('Delete this disease? This action cannot be undone.')) return;
      const response = await fetch(`/admin/diseases/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
      });
      if (response.ok) window.location.reload();
      else if (typeof showToast === 'function') showToast('Unable to delete disease.', 'error');
    }

    function openAddDiseaseModal() { openModal('diseaseModal'); }
    async function editDisease(id, disease) {
      const updated = {
        name: prompt('Disease name:', disease.name || ''),
        species_affected: prompt('Species affected:', disease.species_affected || ''),
        symptoms: disease.symptoms || 'Not specified',
        transmission: prompt('Causes:', disease.transmission || ''),
        prevention: prompt('Prevention:', disease.prevention || ''),
        treatment: prompt('Treatment:', disease.treatment || ''),
        severity: prompt('Severity (low, medium, high, critical):', disease.severity || 'medium'),
        outbreak_risk: disease.outbreak_risk || 'medium'
      };
      if (updated.name === null || updated.species_affected === null || updated.treatment === null) return;
      const response = await fetch(`/admin/diseases/${id}`, {
        method: 'PUT',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(updated)
      });
      if (response.ok) window.location.reload();
      else if (typeof showToast === 'function') showToast('Unable to update disease. Check the values and try again.', 'error');
    }
  </script>
</div>
  </div>
