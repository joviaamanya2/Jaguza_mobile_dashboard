  <!-- ===== VACCINATIONS ===== -->
  <div class="page" id="page-vaccinations">
    <div class="section-heading">
      <h2><i class="fas fa-syringe" style="color:#0d6efd;margin-right:8px;"></i>Vaccinations</h2>
      <button class="btn btn-primary" onclick="openAddVaccinationModal()">+ Add Record</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>ID</th><th>Animal</th><th>Vaccine</th><th>Farm</th><th>Administered By</th><th>Date</th><th>Next Due</th><th>Status</th></tr></thead>
          <tbody>
            @forelse($vaccinations as $vaccination)
            <tr>
              <td>{{ $vaccination->id ?? 'N/A' }}</td>
              <td>{{ $vaccination->animal->name ?? $vaccination->animal->identification_number ?? 'N/A' }}</td>
              <td>{{ $vaccination->vaccine_name ?? 'N/A' }}</td>
              <td>{{ $vaccination->animal->farm->name ?? 'N/A' }}</td>
              <td>{{ $vaccination->administeredBy->user->name ?? 'N/A' }}</td>
              <td>{{ $vaccination->administered_date ? $vaccination->administered_date->format('M d, Y') : 'N/A' }}</td>
              <td>{{ $vaccination->next_due_date ? $vaccination->next_due_date->format('M d, Y') : 'N/A' }}</td>
              <td><span class="badge {{ $vaccination->next_due_date && $vaccination->next_due_date->isPast() ? 'badge-red' : ($vaccination->is_completed ? 'badge-green' : 'badge-orange') }}">{{ $vaccination->next_due_date && $vaccination->next_due_date->isPast() ? 'Overdue' : ($vaccination->is_completed ? 'Done' : 'Due Soon') }}</span></td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:#6a7a8a;">No vaccination records found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
