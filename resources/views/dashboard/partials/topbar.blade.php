<!-- TOPBAR -->
<header id="topbar">
  <div class="topbar-left">
    <button id="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <span id="page-title">Dashboard Overview</span>
  </div>
  <div class="topbar-search">
    <i class="fas fa-search"></i>
    <input type="text" placeholder="Search anything..." id="globalSearch" />
  </div>
  <div class="topbar-right">
    <button class="topbar-action-btn" onclick="navigate('notifications','Notifications')">
      <i class="fas fa-bell"></i>
      <span class="topbar-badge">{{ count($notifications ?? []) }}</span>
    </button>
    

  </div>
</header>
