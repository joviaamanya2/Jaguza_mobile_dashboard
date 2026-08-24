<!-- ===== ADVERTISEMENT MODAL ===== -->
<div class="modal-overlay" id="adModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="adModalTitle">Create Ad</h3>
            <button class="modal-close" onclick="closeModal('adModal')">&times;</button>
        </div>
        <form id="adForm" onsubmit="event.preventDefault(); saveAd();">
            <div class="form-group">
                <label>Ad Title <span class="required">*</span></label>
                <input type="text" id="ad_title" class="form-control" placeholder="Enter ad title" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea id="ad_description" class="form-control" rows="3" placeholder="Describe the ad..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Type <span class="required">*</span></label>
                    <select id="ad_type" class="form-control" required>
                        <option value="banner">Banner</option>
                        <option value="sponsored">Sponsored</option>
                        <option value="video">Video</option>
                        <option value="popup">Popup</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Budget</label>
                    <input type="number" id="ad_budget" class="form-control" placeholder="0" min="0">
                </div>
            </div>

            <div class="form-group">
                <label>Link URL</label>
                <input type="url" id="ad_link_url" class="form-control" placeholder="https://example.com">
            </div>

            <div class="form-group">
                <label>Image</label>
                <input type="file" id="ad_image_file" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Start Date <span class="required">*</span></label>
                    <input type="date" id="ad_start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" id="ad_end_date" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="adSubmitBtn">Create Ad</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('adModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
