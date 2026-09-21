  <!-- ===== MARKETPLACE ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'marketplace' ? 'active' : '' }}" id="page-marketplace">
    <div class="section-heading">
      <h2><i class="fas fa-store" style="color:#fd7e14;margin-right:8px;"></i>Market Place</h2>
      <button class="btn btn-primary" onclick="openAddListingModal()">+ Add Listing</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Image</th><th>Listing</th><th>Seller</th><th>Category</th><th>Price</th><th>Location</th><th>Status</th></tr></thead>
          <tbody>
            @forelse($marketplaceListings as $listing)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>
                @php($listingImage = is_array($listing->images ?? null) && count($listing->images) ? $listing->images[0] : null)
                @if($listingImage)
                  <img src="{{ filter_var($listingImage, FILTER_VALIDATE_URL) ? $listingImage : asset('storage/' . ltrim($listingImage, '/')) }}" alt="{{ $listing->title ?? 'Listing image' }}" style="width:52px;height:52px;object-fit:cover;border-radius:8px;">
                @else
                  <span style="display:inline-flex;width:52px;height:52px;align-items:center;justify-content:center;background:#f0f2f5;border-radius:8px;color:#8c9aab;"><i class="fas fa-image"></i></span>
                @endif
              </td>
              <td>{{ $listing->title ?? 'N/A' }}</td>
              <td>{{ $listing->seller->name ?? 'N/A' }}</td>
              <td><span class="badge badge-green">{{ ucfirst($listing->category ?? 'General') }}</span></td>
              <td>{{ $listing->currency ?? 'UGX' }} {{ number_format($listing->price ?? 0) }}</td>
              <td>{{ $listing->location ?? 'N/A' }}</td>
              <td><span class="badge @if($listing->status == 'active') badge-green @elseif($listing->status == 'pending') badge-orange @else badge-red @endif">{{ ucfirst($listing->status ?? 'Unknown') }}</span></td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:#6a7a8a;">No marketplace listings found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
