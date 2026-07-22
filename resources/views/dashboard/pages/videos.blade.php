  <!-- ===== VIDEOS ===== -->
  <div class="page" id="page-videos">
    <div class="section-heading">
      <h2><i class="fas fa-play-circle" style="color:#2e7d32;margin-right:8px;"></i>Educational Videos</h2>
      <button class="btn btn-primary" onclick="openAddVideoModal()">+ Upload Video</button>
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
