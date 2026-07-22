  <div class="page" id="page-users">
    <div class="section-heading">
      <h2><i class="fas fa-users" style="color:#2e7d32;margin-right:8px;"></i>Users Management</h2>
      <button class="btn btn-primary" onclick="openAddUserModal()">+ Add User</button>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>#</th><th>User</th><th>Email</th><th>Farm</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
          </thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td><span class="avatar-sm">{{ getInitials($user->name) }}</span> {{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->farm_name ?? '-' }}</td>
              <td><span class="badge badge-purple">{{ ucfirst($user->role ?? 'Farmer') }}</span></td>
              <td><span class="badge {{ ($user->is_active ?? true) ? 'badge-green' : 'badge-red' }}">{{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}</span></td>
              <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
              <td>
                <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;" onclick="editUser({{ $user->id }})">Edit</button>
                <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;color:var(--red);" onclick="deleteUser({{ $user->id }})">Delete</button>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:#6a7a8a;">No users found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
