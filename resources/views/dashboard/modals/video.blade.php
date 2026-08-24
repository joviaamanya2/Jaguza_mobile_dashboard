<!-- ===== VIDEO MODAL ===== -->
<div class="modal-overlay" id="videoModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="videoModalTitle">Upload Video</h3>
            <button class="modal-close" onclick="closeModal('videoModal')">&times;</button>
        </div>
        <form id="videoForm" onsubmit="event.preventDefault(); saveVideo();">
            <input type="hidden" id="videoId">
            
            <div class="form-group">
                <label>Video Title <span class="required">*</span></label>
                <input type="text" id="video_title" class="form-control" placeholder="Enter video title" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea id="video_description" class="form-control" rows="3" placeholder="Describe the video content..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Video File</label>
                <input type="file" id="video_file" class="form-control" accept="video/mp4,video/quicktime,video/webm,video/x-msvideo">
                <small style="color:#6a7a8a;">Upload MP4, MOV, WEBM, or AVI. Maximum 50 MB.</small>
            </div>

            <div class="form-group">
                <label>Video URL <span class="required">*</span> <small>(required only when no file is selected)</small></label>
                <input type="url" id="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                <small style="color:#6a7a8a;">Use a URL or upload a video file.</small>
            </div>
            
            <div class="form-group">
                <label>Thumbnail Image</label>
                <input type="file" id="video_thumbnail_file" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small style="color:#6a7a8a;">JPG, PNG, or WEBP. Maximum 4 MB.</small>
            </div>

            <div class="form-group">
                <label>Thumbnail URL</label>
                <input type="url" id="video_thumbnail" class="form-control" placeholder="https://example.com/thumbnail.jpg">
                <small style="color:#6a7a8a;">Use a URL or upload a thumbnail image.</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select id="video_category" class="form-control" onchange="toggleNewCategoryInput()">
                        <option value="">Select Category</option>
                        @foreach($videoCategories ?? [] as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                        <option value="__new__">+ Add New Category</option>
                    </select>
                    <input type="text" id="video_new_category_name" class="form-control" placeholder="New category name" style="display:none;margin-top:8px;">
                    <small id="video_new_category_hint" style="display:none;color:#6a7a8a;">This category will be created when you upload the video.</small>
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" id="video_duration" class="form-control" placeholder="15:30">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Platform</label>
                    <select id="video_platform" class="form-control">
                        <option value="youtube">YouTube</option>
                        <option value="vimeo">Vimeo</option>
                        <option value="local">Local</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tags (comma separated)</label>
                    <input type="text" id="video_tags" class="form-control" placeholder="farming, cattle, health">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                    <label style="margin:0;display:flex;align-items:center;gap:5px;cursor:pointer;">
                        <input type="checkbox" id="video_featured"> 
                        Feature this video
                    </label>
                    <label style="margin:0;display:flex;align-items:center;gap:5px;cursor:pointer;">
                        <input type="checkbox" id="video_published" checked> 
                        Publish now
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="videoSubmitBtn">Upload Video</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('videoModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
