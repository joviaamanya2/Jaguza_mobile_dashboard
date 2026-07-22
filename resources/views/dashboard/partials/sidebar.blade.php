<!-- SIDEBAR -->
<aside id="sidebar">
  <div class="sidebar-logo" style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:16px 12px; text-align:center;">
    <img src="{{ asset('images/logo.png') }}" alt="Jaguza Logo" style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover; margin-bottom: 8px;">
    <div class="logo-text" style="text-align:center;">
        <h1 style="font-size:18px; font-weight:700; color:#fff; letter-spacing:-.5px; margin:0;">Jaguza</h1>
        <span style="font-size:11px; color:rgba(255,255,255,.7); font-weight:400;">Admin Dashboard</span>
    </div>
</div>
  </div>
  <div class="sidebar-scroll">
    <div class="nav-section-label">Overview</div>
    <a class="nav-item active" id="nav-dashboard" onclick="navigate('dashboard','Dashboard Overview')">
      <div class="nav-icon"><i class="fas fa-th-large"></i></div><span class="nav-label">Dashboard</span>
    </a>
    <a class="nav-item" id="nav-analytics" onclick="navigate('analytics','Analytics')">
      <div class="nav-icon"><i class="fas fa-chart-line"></i></div><span class="nav-label">Analytics</span>
    </a>

    <div class="nav-section-label">People</div>
    <a class="nav-item" id="nav-users" onclick="navigate('users','Users')">
      <div class="nav-icon"><i class="fas fa-users"></i></div><span class="nav-label">Users</span>
      <span class="nav-badge">{{ number_format($stats['total_users'] ?? 0) }}</span>
    </a>
    <a class="nav-item" id="nav-doctors" onclick="navigate('doctors','Veterinary Doctors')">
      <div class="nav-icon"><i class="fas fa-stethoscope"></i></div><span class="nav-label">Doctors</span>
    </a>
    
    <a class="nav-item" id="nav-notifications" onclick="navigate('notifications','Notifications')">
      <div class="nav-icon"><i class="fas fa-bell"></i></div><span class="nav-label">Notifications</span>
      <span class="nav-badge">{{ count($notifications ?? []) }}</span>
    </a>

    <div class="nav-section-label">Farm &amp; Animals</div>
    <a class="nav-item" id="nav-farms" onclick="navigate('farms','Farms')">
      <div class="nav-icon"><i class="fas fa-warehouse"></i></div><span class="nav-label">Farms</span>
    </a>
    <a class="nav-item" id="nav-livestock" onclick="navigate('livestock','Livestock Animals')">
      <div class="nav-icon"><i class="fas fa-horse"></i></div><span class="nav-label">Livestock Animals</span>
    </a>
    <a class="nav-item" id="nav-gestation" onclick="navigate('gestation','Gestation Info')">
      <div class="nav-icon"><i class="fas fa-baby"></i></div><span class="nav-label">Gestation Info</span>
    </a>
    <a class="nav-item" id="nav-vaccinations" onclick="navigate('vaccinations','Vaccinations')">
      <div class="nav-icon"><i class="fas fa-syringe"></i></div><span class="nav-label">Vaccinations</span>
    </a>

    <div class="nav-section-label">Health &amp; Disease</div>
    <a class="nav-item" id="nav-sickness" onclick="navigate('sickness','Sickness Reports')">
      <div class="nav-icon"><i class="fas fa-file-medical"></i></div><span class="nav-label">Sickness Reports</span>
      <span class="nav-badge">{{ number_format($stats['open_reports'] ?? 0) }}</span>
    </a>
    <a class="nav-item" id="nav-disease" onclick="navigate('disease','Disease Information')">
      <div class="nav-icon"><i class="fas fa-virus"></i></div><span class="nav-label">Disease Info</span>
    </a>
    <a class="nav-item" id="nav-decision" onclick="navigate('decision','Decision Support')">
      <div class="nav-icon"><i class="fas fa-brain"></i></div><span class="nav-label">Decision Support</span>
    </a>

    <div class="nav-section-label">Market &amp; Media</div>
    <a class="nav-item" id="nav-marketplace" onclick="navigate('marketplace','Market Place')">
      <div class="nav-icon"><i class="fas fa-store"></i></div><span class="nav-label">Market Place</span>
    </a>
    <a class="nav-item" id="nav-videos" onclick="navigate('videos','Videos')">
      <div class="nav-icon"><i class="fas fa-play-circle"></i></div><span class="nav-label">Videos</span>
    </a>
    <a class="nav-item" id="nav-ads" onclick="navigate('ads','Advertisements')">
      <div class="nav-icon"><i class="fas fa-bullhorn"></i></div><span class="nav-label">Advertisements</span>
    </a>

    <div class="nav-section-label">Other</div>
    <a class="nav-item" id="nav-weather" onclick="navigate('weather','Weather Updates')">
      <div class="nav-icon"><i class="fas fa-cloud-sun"></i></div><span class="nav-label">Weather Updates</span>
    </a>
    <a class="nav-item" id="nav-aichat" onclick="navigate('aichat','AI Chat (Vet Bot)')">
      <div class="nav-icon"><i class="fas fa-robot"></i></div><span class="nav-label">AI Chat (Vet Bot)</span>
    </a>
    <a class="nav-item" id="nav-settings" onclick="navigate('settings','Settings')">
      <div class="nav-icon"><i class="fas fa-cog"></i></div><span class="nav-label">Settings</span>
    </a>
  </div>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="user-avatar">{{ getInitials($user->name ?? 'JA') }}</div>
      <div class="user-info">
        <p>{{ $user->name ?? 'Jaguza Admin' }}</p>
        <span>{{ ucfirst($user->role ?? 'Super Admin') }}</span>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
      @csrf
      <button type="submit" style="background:none;border:none;color:#dc3545;cursor:pointer;font-size:13px;width:100%;padding:8px;border-radius:8px;transition:background 0.2s;font-family:'Inter',sans-serif;"
              onmouseover="this.style.background='#fde8e8'" 
              onmouseout="this.style.background='transparent'">
          <i class="fas fa-sign-out-alt"></i> Logout
      </button>
    </form>
  </div>
</aside>
