<div class="modal-overlay {{ $errors->any() ? 'active' : '' }}" id="decisionResourceModal">
    <div class="modal-box decision-resource-modal-box">
        <div class="modal-header">
            <div><h3>Add decision-support resource</h3><p class="modal-subtitle">Create practical guidance for farmers.</p></div>
            <button type="button" class="modal-close" onclick="closeModal('decisionResourceModal')" aria-label="Close">&times;</button>
        </div>
        <form id="decisionResourceModalForm" action="{{ route('admin.decision-support.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group"><label for="decision_title">Title <span class="required">*</span></label><input id="decision_title" name="title" class="form-control" type="text" value="{{ old('title') }}" placeholder="e.g. Preventing mastitis in dairy cattle" required>@error('title')<small class="form-error">{{ $message }}</small>@enderror</div>
            <div class="form-row">
                <div class="form-group"><label for="decision_category">Category <span class="required">*</span></label><select id="decision_category" name="category" class="form-control" required><option value="">Select category</option>@foreach($categories as $category)<option value="{{ $category }}" @selected(old('category') === $category)>{{ ucfirst($category) }}</option>@endforeach</select>@error('category')<small class="form-error">{{ $message }}</small>@enderror</div>
                <div class="form-group"><label for="decision_topic">Topic</label><select id="decision_topic" name="topic" class="form-control"><option value="">Select topic</option>@foreach($topics as $topic)<option value="{{ $topic }}" @selected(old('topic') === $topic)>{{ ucfirst($topic) }}</option>@endforeach</select>@error('topic')<small class="form-error">{{ $message }}</small>@enderror</div>
            </div>
            <div class="form-group"><label for="decision_summary">Summary</label><textarea id="decision_summary" name="summary" class="form-control" rows="2" placeholder="Short description shown in resource previews">{{ old('summary') }}</textarea></div>
            <div class="form-group"><label for="decision_image">Resource image</label><input id="decision_image" name="image" class="form-control" type="file" accept="image/jpeg,image/png,image/webp"><small class="form-help">JPG, PNG or WEBP, maximum 4 MB.</small>@error('image')<small class="form-error">{{ $message }}</small>@enderror</div>
            <div class="form-group"><label for="decision_content">Resource content <span class="required">*</span></label><textarea id="decision_content" name="content" class="form-control" rows="6" placeholder="Write the guidance here..." required>{{ old('content') }}</textarea>@error('content')<small class="form-error">{{ $message }}</small>@enderror</div>
            <div class="form-group decision-checks"><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', true))> Publish immediately</label><label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured'))> Feature resource</label></div>
            <div class="modal-footer"><button type="button" class="btn btn-outline" onclick="closeModal('decisionResourceModal')">Cancel</button><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save resource</button></div>
        </form>
    </div>
</div>
