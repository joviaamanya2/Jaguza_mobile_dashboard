  <!-- ===== AI CHAT ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'aichat' ? 'active' : '' }}" id="page-aichat">
    <div class="section-heading">
      <h2><i class="fas fa-robot" style="color:#0d6efd;margin-right:8px;"></i>AI Conversations</h2>
    </div>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card" style="margin-bottom:16px;">
      <form method="GET" action="{{ route('dashboard', ['page' => 'aichat']) }}" class="form-row" style="align-items:end;">
        <div class="form-group" style="flex:1;">
          <label for="ai_search">Search user content</label>
          <input id="ai_search" name="ai_search" class="form-control" value="{{ request('ai_search') }}" placeholder="Search questions and symptoms">
        </div>
        <div class="form-group" style="min-width:190px;">
          <label for="ai_category">Conversation category</label>
          <select id="ai_category" name="ai_category" class="form-control">
            <option value="">All categories</option>
            @foreach(['Health and Disease', 'Feeding and Nutrition', 'Breeding', 'Vaccination', 'Markets and Prices', 'General Farm Advice'] as $category)
              <option value="{{ $category }}" @selected(request('ai_category') === $category)>{{ $category }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group" style="width:130px;">
          <label for="ai_user_id">User ID</label>
          <input id="ai_user_id" name="ai_user_id" class="form-control" value="{{ request('ai_user_id') }}" inputmode="numeric" placeholder="Any user">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        <a href="{{ route('dashboard', ['page' => 'aichat']) }}" class="btn btn-outline">Reset</a>
      </form>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>User ID</th><th>User</th><th>Category</th><th>Searched content</th><th>Messages</th><th>Duration</th><th>Last activity</th><th>Actions</th></tr>
          </thead>
          <tbody>
            @forelse($aiChatConversations ?? [] as $conversation)
              <tr>
                <td>{{ $conversation['user_id'] }}</td>
                <td>{{ $conversation['user_name'] }}</td>
                <td><span class="badge badge-blue">{{ $conversation['category'] }}</span></td>
                <td style="max-width:360px;white-space:pre-wrap;line-height:1.45;">{{ $conversation['content'] }}</td>
                <td>{{ $conversation['message_count'] }}</td>
                <td>{{ $conversation['duration'] }}</td>
                <td>{{ $conversation['last_activity']->format('M d, Y H:i') }}</td>
                <td>
                  <form method="POST" action="{{ route('admin.ai-chat.destroy', ['key' => $conversation['key']]) }}" onsubmit="return confirm('Delete this AI conversation? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline" style="color:#dc3545;border-color:#dc3545;padding:5px 9px;" title="Delete conversation" aria-label="Delete conversation"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="8" style="text-align:center;padding:40px;color:#6a7a8a;">No AI conversations found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
