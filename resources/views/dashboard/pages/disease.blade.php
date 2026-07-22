  <!-- ===== DISEASE INFO ===== -->
  <div class="page" id="page-disease">
    <div class="section-heading">
      <h2><i class="fas fa-virus" style="color:#fd7e14;margin-right:8px;"></i>Disease Information</h2>
      <button class="btn btn-primary" onclick="openAddDiseaseModal()">+ Add Disease</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>Disease</th><th>Species Affected</th><th>Symptoms</th><th>Treatment</th><th>Severity</th><th>Outbreak Risk</th></tr></thead>
          <tbody>
            @forelse($diseases as $disease)
            <tr>
              <td><strong style="color:#1a1a2e;">{{ $disease->name }}</strong></td>
              <td>{{ $disease->species_affected ?? 'N/A' }}</td>
              <td>{{ Str::limit($disease->symptoms ?? '', 50) }}</td>
              <td>{{ Str::limit($disease->treatment ?? '', 50) }}</td>
              <td><span class="badge @if($disease->severity == 'high' || $disease->severity == 'critical') badge-red @elseif($disease->severity == 'medium') badge-orange @else badge-green @endif">{{ ucfirst($disease->severity ?? 'Unknown') }}</span></td>
              <td><span class="badge @if($disease->outbreak_risk == 'high') badge-red @elseif($disease->outbreak_risk == 'medium') badge-orange @else badge-green @endif">{{ ucfirst($disease->outbreak_risk ?? 'Unknown') }}</span></td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:40px;color:#6a7a8a;">No diseases found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
