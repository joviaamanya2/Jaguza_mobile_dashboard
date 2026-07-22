  <!-- ===== DASHBOARD ===== -->
  <div class="page active" id="page-dashboard">
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-users"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($stats['total_users'] ?? 0) }}</h3>
          <p>Total Users</p>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> {{ number_format($userGrowthPercent ?? 0, 1) }}% this month</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fde8e8;color:#c62828;"><i class="fas fa-file-medical"></i></div>
        <div class="stat-body">
          <h3>{{ number_format(($stats['open_reports'] ?? 0) + ($stats['under_treatment'] ?? 0)) }}</h3>
          <p>Sickness Reports</p>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> {{ number_format($sicknessGrowthPercent ?? 0, 1) }}% this week</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#e3f2fd;color:#0d47a1;"><i class="fas fa-stethoscope"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($stats['total_doctors'] ?? 0) }}</h3>
          <p>Active Doctors</p>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> {{ number_format($newDoctors ?? 0) }} new this month</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-warehouse"></i></div>
        <div class="stat-body">
          <h3 id="dashboard-farms-count">{{ number_format($stats['total_farms'] ?? 0) }}</h3>
          <p>Registered Farms</p>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> {{ number_format($farmGrowthPercent ?? 0, 1) }}% growth</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#f3e5f5;color:#4a148c;"><i class="fas fa-horse"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($stats['total_animals'] ?? 0) }}</h3>
          <p>Livestock Animals</p>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> {{ number_format($livestockGrowthPercent ?? 0, 1) }}% new</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-play-circle"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($stats['total_videos'] ?? 0) }}</h3>
          <p>Videos Published</p>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> {{ number_format($newVideosThisWeek ?? 0) }} this week</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#f5f5f5;color:#f57c00;"><i class="fas fa-bullhorn"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($stats['active_ads'] ?? 0) }}</h3>
          <p>Active Ads</p>
          <div class="stat-trend trend-down"><i class="fas fa-arrow-down"></i> {{ number_format($expiredAds ?? 0) }} expired</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:#fce4ec;color:#c62828;"><i class="fas fa-baby"></i></div>
        <div class="stat-body">
          <h3>{{ number_format($stats['total_gestations'] ?? 0) }}</h3>
          <p>Gestation Records</p>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> {{ number_format($dueGestationsCount ?? 0) }} due this week</div>
        </div>
      </div>
    </div>
    
    <div class="charts-grid">
      <div class="chart-card">
        <h3><i class="fas fa-chart-bar" style="color:#2e7d32;margin-right:8px;"></i>Sickness Reports (Monthly)</h3>
        <canvas id="sickChart"></canvas>
      </div>
      <div class="chart-card">
        <h3><i class="fas fa-chart-pie" style="color:#0d6efd;margin-right:8px;"></i>Livestock by Type</h3>
        <canvas id="animalChart"></canvas>
      </div>
      <div class="chart-card">
        <h3><i class="fas fa-chart-line" style="color:#2e7d32;margin-right:8px;"></i>User Registrations</h3>
        <canvas id="userChart"></canvas>
      </div>
      <div class="chart-card">
        <h3><i class="fas fa-store" style="color:#fd7e14;margin-right:8px;"></i>Market Activity</h3>
        <canvas id="marketChart"></canvas>
      </div>
    </div>
    
    <div class="section-heading" style="margin-top:28px;"><h2>Quick Actions</h2></div>
    <div class="quick-grid">
      <div class="quick-card" onclick="openAddUserModal()">
        <div class="qi" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-user-plus"></i></div>
        <span>Add User</span>
      </div>
      <div class="quick-card" onclick="openAddReportModal()">
        <div class="qi" style="background:#fde8e8;color:#c62828;"><i class="fas fa-file-medical"></i></div>
        <span>New Report</span>
      </div>
      <div class="quick-card" onclick="openAddDoctorModal()">
        <div class="qi" style="background:#e3f2fd;color:#0d47a1;"><i class="fas fa-stethoscope"></i></div>
        <span>Add Doctor</span>
      </div>
      <div class="quick-card" onclick="openAddAnimalModal()">
        <div class="qi" style="background:#f3e5f5;color:#4a148c;"><i class="fas fa-horse"></i></div>
        <span>Add Animal</span>
      </div>
      <div class="quick-card" onclick="openAddAdModal()">
        <div class="qi" style="background:#f5f5f5;color:#f57c00;"><i class="fas fa-bullhorn"></i></div>
        <span>New Ad</span>
      </div>
      <div class="quick-card" onclick="openAddVideoModal()">
        <div class="qi" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-upload"></i></div>
        <span>Upload Video</span>
      </div>
      <div class="quick-card" onclick="openAddGestationModal()">
        <div class="qi" style="background:#fce4ec;color:#c62828;"><i class="fas fa-baby"></i></div>
        <span>Add Gestation</span>
      </div>
      <div class="quick-card" onclick="openAddNotificationModal()">
        <div class="qi" style="background:#fff3e0;color:#e65100;"><i class="fas fa-paper-plane"></i></div>
        <span>Send Notification</span>
      </div>
    </div>
  </div>
