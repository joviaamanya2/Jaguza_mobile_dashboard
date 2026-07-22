  <!-- ===== DECISION SUPPORT ===== -->
  <div class="page" id="page-decision">
    <div class="section-heading">
      <h2><i class="fas fa-brain" style="color:#0d6efd;margin-right:8px;"></i>Decision Support</h2>
      <button class="btn btn-primary" onclick="openAddDecisionModal()">+ Add Article</button>
    </div>
    <div class="stats-grid">
      <div class="stat-card"><div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-lightbulb"></i></div><div class="stat-body"><h3>{{ number_format(count($decisionSupport)) }}</h3><p>AI Recommendations</p></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#e3f2fd;color:#0d47a1;"><i class="fas fa-check-circle"></i></div><div class="stat-body"><h3>94%</h3><p>Accuracy Rate</p></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-clock"></i></div><div class="stat-body"><h3>1.2s</h3><p>Avg Response Time</p></div></div>
    </div>
    <div class="card">
      @forelse($decisionSupport as $article)
      <div class="notif-item">
        <div class="notif-dot" style="background:{{ $article->is_featured ? '#2e7d32' : '#0d6efd' }};"></div>
        <div class="notif-body">
          <p><strong style="color:#1a1a2e;">{{ $article->title }}</strong></p>
          <p style="font-size:13px;color:#4a5a6a;margin-top:2px;">{{ Str::limit($article->summary ?? $article->content ?? '', 150) }}</p>
          <span style="display:flex;gap:12px;margin-top:6px;">
            <span><i class="fas fa-tag"></i> {{ ucfirst($article->category ?? 'General') }}</span>
            <span><i class="fas fa-star"></i> {{ $article->difficulty_level ?? 'Beginner' }}</span>
            <span><i class="fas fa-eye"></i> {{ number_format($article->views_count ?? 0) }} views</span>
          </span>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:40px;color:#6a7a8a;"><p>No decision support articles available.</p></div>
      @endforelse
    </div>
  </div>
