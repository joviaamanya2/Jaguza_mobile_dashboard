  <!-- ===== NOTIFICATIONS ===== -->
  <div class="page" id="page-notifications">
    <div class="section-heading">
      <h2><i class="fas fa-bell" style="color:#fd7e14;margin-right:8px;"></i>Notifications</h2>
      <button class="btn btn-primary" onclick="openAddNotificationModal()">+ Send Notification</button>
    </div>
    <div class="card">
      @forelse($notifications as $notification)
      <div class="notif-item">
        <div class="notif-dot" style="background:{{ $notification->color ?? '#2e7d32' }};"></div>
        <div class="notif-body">
          <p><strong style="color:#1a1a2e;">{{ $notification->title ?? 'Notification' }}:</strong> {{ $notification->message ?? '' }}</p>
          <span>{{ $notification->created_at ? $notification->created_at->diffForHumans() : 'N/A' }}</span>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:40px;color:#6a7a8a;"><p>No notifications found.</p></div>
      @endforelse
    </div>
  </div>
