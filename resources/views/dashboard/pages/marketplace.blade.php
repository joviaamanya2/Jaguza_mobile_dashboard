  <!-- ===== MARKETPLACE ===== -->
  <div class="page" id="page-marketplace">
    <div class="section-heading">
      <h2><i class="fas fa-store" style="color:#fd7e14;margin-right:8px;"></i>Market Place</h2>
      <button class="btn btn-primary" onclick="openAddListingModal()">+ Add Listing</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Listing</th><th>Seller</th><th>Category</th><th>Price</th><th>Location</th><th>Status</th></tr></thead>
          <tbody>
            @forelse($marketplaceListings as $listing)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $listing->title ?? 'N/A' }}</td>
              <td>{{ $listing->seller->name ?? 'N/A' }}</td>
              <td><span class="badge badge-green">{{ ucfirst($listing->category ?? 'General') }}</span></td>
              <td>{{ $listing->currency ?? 'UGX' }} {{ number_format($listing->price ?? 0) }}</td>
              <td>{{ $listing->location ?? 'N/A' }}</td>
              <td><span class="badge @if($listing->status == 'active') badge-green @elseif($listing->status == 'pending') badge-orange @else badge-red @endif">{{ ucfirst($listing->status ?? 'Unknown') }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:#6a7a8a;">No marketplace listings found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
