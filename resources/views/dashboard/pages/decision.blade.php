{{-- resources/views/admin/decision-support/index.blade.php --}}
<div class="page {{ ($initialPage ?? 'dashboard') === 'decision' ? 'active' : '' }}" id="page-decision">
<div class="container-fluid decision-page-container">
    <div class="decision-page-header">
        <div>
            <h1>Decision Support Resources</h1>
            <p>Manage practical livestock guidance for farmers.</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="openModal('decisionResourceModal')">
            <i class="fas fa-plus"></i> Add New Resource
        </button>
    </div>

    <!-- Filters -->
    <div class="card decision-filter-card">
        <form method="GET" class="decision-filters">
                <div class="decision-filter-field">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="decision-filter-field">
                    <label class="form-label">Topic</label>
                    <select name="topic" class="form-select">
                        <option value="">All Topics</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic }}" {{ request('topic') == $topic ? 'selected' : '' }}>
                                {{ ucfirst($topic) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="decision-filter-field">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="decision-filter-actions">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('dashboard', ['page' => 'decision']) }}" class="btn btn-secondary">Reset</a>
                </div>
        </form>
    </div>

    <!-- Resources Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Topic</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resources as $resource)
                        <tr>
                            <td>
                                @if($resource->image_url)<img src="{{ $resource->image_url }}" alt="{{ $resource->title }}" style="width:42px;height:42px;object-fit:cover;border-radius:8px;margin-right:8px;vertical-align:middle;">@endif
                                <strong>{{ $resource->title }}</strong>
                                @if($resource->is_featured)
                                    <span class="badge bg-warning text-dark ms-2">Featured</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: {{ $resource->animal_info['color'] }}20; color: {{ $resource->animal_info['color'] }};">
                                    <i class="fas {{ $resource->animal_info['icon'] }}"></i>
                                    {{ $resource->animal_info['name'] }}
                                </span>
                            </td>
                            <td>
                                @if($resource->topic)
                                    <span class="badge bg-info">
                                        <i class="fas {{ $resource->topic_info['icon'] }}"></i>
                                        {{ $resource->topic_info['title'] }}
                                    </span>
                                @else
                                    <span class="text-muted">General</span>
                                @endif
                            </td>
                            <td>
                                @if($resource->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-danger">Draft</span>
                                @endif
                            </td>
                            <td>{{ number_format($resource->views_count) }}</td>
                            <td>
                                <div class="decision-actions">
                                    <a href="{{ route('admin.decision-support.edit', $resource) }}" class="decision-action-icon decision-edit-icon" title="Edit resource" aria-label="Edit resource">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.decision-support.destroy', $resource) }}" method="POST" class="decision-delete-form" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="decision-action-icon decision-delete-icon" title="Delete resource" aria-label="Delete resource">
                                        <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted">No resources found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (method_exists($resources, 'links'))
                {{ $resources->links() }}
            @endif
        </div>
    </div>
</div>
</div>

@include('dashboard.modals.decision-support')
