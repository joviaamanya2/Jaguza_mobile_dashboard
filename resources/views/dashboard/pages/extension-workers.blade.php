  <!-- ===== EXTENSION WORKERS ===== -->
  <div class="page" id="page-extension-workers">
    <div class="section-heading">
      <h2><i class="fas fa-user-tie" style="color:#e65100;margin-right:8px;"></i>Extension Workers</h2>
      <button class="btn btn-primary" onclick="openAddExtensionWorkerModal()">+ Add Extension Worker</button>
    </div>
    
    <!-- Extension Worker Stats -->
    <div class="stats-grid" style="margin-bottom:20px;">
      <div class="stat-card">
        <div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-user-tie"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($stats['total_extension_workers'] ?? 0) }}</h3>
          <p>Total Extension Workers</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-check-circle"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($available_workers ?? 0) }}</h3>
          <p>Available Now</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fce4ec;color:#c62828;"><i class="fas fa-clock"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($busy_workers ?? 0) }}</h3>
          <p>On Field / Unavailable</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#e3f2fd;color:#0d47a1;"><i class="fas fa-tractor"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($total_farm_visits ?? 0) }}</h3>
          <p>Total Farm Visits</p>
        </div>
      </div>
    </div>
    
    <!-- Extension Workers Table -->
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Worker</th>
              <th>Expertise</th>
              <th>Education</th>
              <th>Assigned Region</th>
              <th>Languages</th>
              <th>Experience</th>
              <th>Farm Visits</th>
              <th>Availability</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($extensionWorkers as $worker)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>
                <span class="avatar-sm">{{ getInitials($worker->user->name ?? '') }}</span> 
                {{ $worker->user->name ?? 'N/A' }}
                <br><small style="color:#6a7a8a;font-size:11px;">{{ $worker->user->email ?? 'N/A' }}</small>
              </td>
              <td><span class="badge badge-orange">{{ $worker->expertise_area ?? 'N/A' }}</span></td>
              <td><span class="badge badge-blue">{{ $worker->education_level ?? 'N/A' }}</span></td>
              <td>{{ $worker->assigned_region ?? 'N/A' }}</td>
              <td>{{ $worker->languages_spoken ?? 'N/A' }}</td>
              <td>{{ $worker->years_of_experience ?? 0 }} yrs</td>
              <td>
                <span class="badge badge-purple">{{ number_format($worker->total_farm_visits ?? 0) }}</span>
              </td>
              <td>
                @php
                  $isAvailable = $worker->is_available ?? true;
                @endphp
                <span class="badge {{ $isAvailable ? 'badge-green' : 'badge-red' }}" onclick="toggleWorkerAvailability({{ $worker->id }}, {{ $isAvailable ? 'true' : 'false' }})" style="cursor:pointer;">
                  <i class="fas {{ $isAvailable ? 'fa-circle' : 'fa-circle' }}"></i> 
                  {{ $isAvailable ? 'Available' : 'On Field' }}
                </span>
                <br><small style="color:#6a7a8a;font-size:10px;">click to toggle</small>
              </td>
              <td>
                <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;" onclick="editExtensionWorker({{ $worker->id }})">
                  <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;color:var(--red);" onclick="deleteExtensionWorker({{ $worker->id }})">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="10" style="text-align:center;padding:40px;color:#6a7a8a;">
                <i class="fas fa-user-tie" style="font-size:40px;display:block;margin-bottom:10px;color:#c8d0d8;"></i>
                No extension workers found. Click the "Add Extension Worker" button to register a new worker.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

