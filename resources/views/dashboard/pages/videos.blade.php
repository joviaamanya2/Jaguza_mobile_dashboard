  <!-- ===== VIDEOS ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'videos' ? 'active' : '' }}" id="page-videos">
    <div class="section-heading">
      <h2><i class="fas fa-play-circle" style="color:#2e7d32;margin-right:8px;"></i>Educational Videos</h2>
      <button class="btn btn-primary" onclick="openAddVideoModal()">+ Upload Video</button>
    </div>

    <div class="card" style="margin-bottom:20px;">
      <div class="section-heading" style="margin-bottom:12px;">
        <h3 style="margin:0;font-size:15px;"><i class="fas fa-tags" style="color:#6a4caf;margin-right:6px;"></i>Video Categories</h3>
      </div>
      <div style="display:flex;flex-wrap:wrap;gap:10px;">
        @forelse($videoCategories ?? [] as $category)
        <div style="display:flex;align-items:center;gap:8px;background:#f4f2fa;border-radius:20px;padding:6px 8px 6px 14px;">
          <span style="font-size:13px;font-weight:600;color:#2b2540;">{{ $category->name }}</span>
          <button title="Edit category" style="border:none;background:none;cursor:pointer;color:#6a7a8a;padding:4px;" onclick='editCategory({{ $category->id }}, {{ \Illuminate\Support\Js::from($category->name) }}, {{ \Illuminate\Support\Js::from($category->description) }})'>
            <i class="fas fa-edit" style="font-size:12px;"></i>
          </button>
          <button title="Delete category" style="border:none;background:none;cursor:pointer;color:var(--red);padding:4px;" onclick="deleteCategory({{ $category->id }})">
            <i class="fas fa-trash" style="font-size:12px;"></i>
          </button>
        </div>
        @empty
        <p style="color:#6a7a8a;font-size:13px;margin:0;">No categories yet. New categories can be added from the "Upload Video" form.</p>
        @endforelse
      </div>
    </div>

    <div class="video-grid">
      @forelse($recentVideos as $video)
      <div class="video-card">
        <div class="video-thumb">
          @if($video->thumbnail_url)<img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">@else<i class="fas fa-video"></i>@endif
          <div class="play-btn" onclick="playVideo({{ $video->id }})"><i class="fas fa-play"></i></div>
        </div>
        <div class="video-info">
          <h4>{{ $video->title }}</h4>
          <p>{{ $video->duration ?? 'N/A' }} &bull; {{ number_format($video->views_count ?? 0) }} views &bull; {{ $video->created_at ? $video->created_at->format('M d') : 'N/A' }}</p>
        </div>
      </div>
      @empty
      <div class="video-card"><div class="video-thumb"><i class="fas fa-video"></i></div><div class="video-info"><h4>No videos available</h4><p>Upload educational videos for farmers</p></div></div>
      @endforelse
    </div>
  </div>
