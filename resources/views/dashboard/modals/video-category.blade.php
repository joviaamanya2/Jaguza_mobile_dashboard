<!-- ===== VIDEO CATEGORY MODAL ===== -->
<div class="modal-overlay" id="videoCategoryModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="videoCategoryModalTitle">Add Category</h3>
            <button class="modal-close" onclick="closeModal('videoCategoryModal')">&times;</button>
        </div>
        <form id="videoCategoryForm" onsubmit="event.preventDefault(); saveVideoCategory();">
            <input type="hidden" id="video_category_id">

            <div class="form-group">
                <label>Category Name <span class="required">*</span></label>
                <input type="text" id="video_category_name" class="form-control" placeholder="e.g. Dairy Farming" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea id="video_category_description" class="form-control" rows="2" placeholder="Short description of this category..."></textarea>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="videoCategorySubmitBtn">Add Category</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('videoCategoryModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
