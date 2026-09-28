  <!-- ===== MEDIA ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'videos' ? 'active' : '' }}" id="page-videos">
    <div class="section-heading">
      <h2><i class="fas fa-photo-video" style="color:#2e7d32;margin-right:8px;"></i>Media Library</h2>
      <button class="btn btn-primary" onclick="openAddVideoModal()">+ Upload Media</button>
    </div>

    <div class="card" style="margin-bottom:20px;">
      <div class="section-heading" style="margin-bottom:12px;">
        <h3 style="margin:0;font-size:15px;">Media Categories</h3>
      </div>
      <div class="media-category-filters" role="group" aria-label="Filter media by category">
        <button type="button" class="media-category-filter active" data-category-id="all" aria-pressed="true" onclick="filterMediaCategory(this)">All</button>
        @forelse($videoCategories ?? [] as $category)
        <button type="button" class="media-category-filter" data-category-id="{{ $category->id }}" aria-pressed="false" onclick="filterMediaCategory(this)">{{ $category->name }}</button>
        @empty
        <span class="media-category-empty">No categories yet. Add one from the Upload Media form.</span>
        @endforelse
      </div>
    </div>

    <div class="video-grid">
      @forelse($recentVideos as $video)
      @php($isImage = ($video->media_type ?? 'video') === 'image')
      <div class="video-card" data-category-id="{{ $video->category_id }}">
        <div class="video-thumb">
          @if($isImage && $video->image_url)
            <img src="{{ $video->image_url }}" alt="{{ $video->title }}">
          @elseif($video->thumbnail_url)
            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
          @else
            <i class="fas {{ $isImage ? 'fa-image' : 'fa-video' }}"></i>
          @endif
          @unless($isImage)
          <div class="play-btn" onclick="playVideo({{ $video->id }})"><i class="fas fa-play"></i></div>
          @endunless
        </div>
        <div class="video-info">
          <h4>{{ $video->title }}</h4>
          <p>{{ $isImage ? 'Image' : ($video->duration ?? 'N/A') }} &bull; {{ number_format($video->views_count ?? 0) }} views &bull; {{ $video->created_at ? $video->created_at->format('M d') : 'N/A' }}</p>
        </div>
      </div>
      @empty
      <div class="video-card"><div class="video-thumb"><i class="fas fa-photo-video"></i></div><div class="video-info"><h4>No media available</h4><p>Upload an image or video for farmers</p></div></div>
      @endforelse
    </div>
  </div>
