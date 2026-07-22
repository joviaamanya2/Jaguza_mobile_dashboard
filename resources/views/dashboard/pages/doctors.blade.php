  <!-- ===== DOCTORS ===== -->
  <div class="page" id="page-doctors">
    <div class="section-heading">
      <h2><i class="fas fa-stethoscope" style="color:#0d6efd;margin-right:8px;"></i>Veterinary Doctors</h2>
      <button class="btn btn-primary" onclick="openAddDoctorModal()">+ Add Doctor</button>
    </div>
    
    <!-- Doctor Stats -->
    <div class="stats-grid" style="margin-bottom:20px;">
      <div class="stat-card">
        <div class="stat-icon" style="background:#e3f2fd;color:#0d47a1;"><i class="fas fa-user-md"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($stats['total_doctors'] ?? 0) }}</h3>
          <p>Total Doctors</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-check-circle"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($available_doctors ?? 0) }}</h3>
          <p>Available Now</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-clock"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($busy_doctors ?? 0) }}</h3>
          <p>Busy/Unavailable</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fce4ec;color:#c62828;"><i class="fas fa-file-medical"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($total_cases ?? 0) }}</h3>
          <p>Total Cases Handled</p>
        </div>
      </div>
    </div>
    
    <!-- Doctors Table -->
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Doctor</th>
              <th>Specialization</th>
              <th>License</th>
              <th>Location</th>
              <th>Experience</th>
              <th>Cases</th>
              <th>Availability</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($doctors as $doctor)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>
                <span class="avatar-sm">{{ getInitials($doctor->user->name ?? '') }}</span> 
                {{ $doctor->user->name ?? 'N/A' }}
                <br><small style="color:#6a7a8a;font-size:11px;">{{ $doctor->user->email ?? 'N/A' }}</small>
              </td>
              <td><span class="badge badge-blue">{{ $doctor->specialization ?? 'N/A' }}</span></td>
              <td>{{ $doctor->license_number ?? 'N/A' }}</td>
              <td>{{ $doctor->clinic_location ?? $doctor->location ?? 'N/A' }}</td>
              <td>{{ $doctor->years_of_experience ?? 0 }} yrs</td>
              <td>
                <span class="badge badge-purple">{{ number_format($doctor->cases_count ?? 0) }}</span>
              </td>
              <td>
                @php
                  $isAvailable = $doctor->is_available ?? true;
                @endphp
                <span class="badge {{ $isAvailable ? 'badge-green' : 'badge-red' }}" onclick="toggleDoctorAvailability({{ $doctor->id }}, {{ $isAvailable ? 'true' : 'false' }})" style="cursor:pointer;">
                  <i class="fas {{ $isAvailable ? 'fa-circle' : 'fa-circle' }}"></i> 
                  {{ $isAvailable ? 'Available' : 'Busy' }}
                </span>
                <br><small style="color:#6a7a8a;font-size:10px;">click to toggle</small>
              </td>
              <td>
                <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;" onclick="editDoctor({{ $doctor->id }})">
                  <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;color:var(--red);" onclick="deleteDoctor({{ $doctor->id }})">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" style="text-align:center;padding:40px;color:#6a7a8a;">
                <i class="fas fa-stethoscope" style="font-size:40px;display:block;margin-bottom:10px;color:#c8d0d8;"></i>
                No doctors found. Click the "Add Doctor" button to register a new veterinary doctor.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
