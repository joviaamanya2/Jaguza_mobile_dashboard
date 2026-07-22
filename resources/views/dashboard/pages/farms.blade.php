<!-- ===== FARMS ===== -->
<div class="page" id="page-farms">
    <div class="section-heading">
        <h2><i class="fas fa-warehouse" style="color:#fd7e14;margin-right:8px;"></i>Farms</h2>
        <button class="btn btn-primary" onclick="openAddFarmModal()">+ Add Farm</button>
    </div>
    
    <!-- Farm Stats -->
    <div class="stats-grid" style="margin-bottom:20px;">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e3f2fd;color:#0d47a1;"><i class="fas fa-warehouse"></i></div>
            <div class="stat-body">
                <h3 id="farms-total-count">{{ number_format($totalFarms ?? 0) }}</h3>
                <p>Total Farms</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-body">
                <h3 id="farms-active-count">{{ number_format($activeFarms ?? 0) }}</h3>
                <p>Active Farms</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fce4ec;color:#c62828;"><i class="fas fa-times-circle"></i></div>
            <div class="stat-body">
                <h3 id="farms-inactive-count">{{ number_format(($totalFarms ?? 0) - ($activeFarms ?? 0)) }}</h3>
                <p>Inactive Farms</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f3e5f5;color:#4a148c;"><i class="fas fa-pets"></i></div>
            <div class="stat-body">
                <h3>{{ number_format($totalAnimalsOnFarms ?? 0) }}</h3>
                <p>Total Animals</p>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Farm Name</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Size (acres)</th>
                        <th>Animals</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($farms as $farm)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $farm->name ?? 'N/A' }}</strong></td>
                        <td>{{ $farm->owner_name ?? $farm->user->name ?? 'N/A' }}</td>
                        <td>{{ $farm->location ?? 'N/A' }}</td>
                        <td>{{ $farm->size ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-purple">{{ number_format($farm->animals->count() ?? 0) }}</span>
                        </td>
                        <td>{{ $farm->created_at ? \Carbon\Carbon::parse($farm->created_at)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="badge {{ ($farm->is_active ?? true) ? 'badge-green' : 'badge-red' }}">
                                {{ ($farm->is_active ?? true) ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;" 
                                    onclick="editFarm({{ $farm->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;color:var(--red);" 
                                    onclick="deleteFarm({{ $farm->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:40px;color:#6a7a8a;">
                            <i class="fas fa-warehouse" style="font-size:48px;display:block;margin-bottom:16px;color:#e8ecf1;"></i>
                            <h3 style="color:#1a1a2e;margin-bottom:8px;">No Farms Found</h3>
                            <p>Click "Add Farm" to create your first farm.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
