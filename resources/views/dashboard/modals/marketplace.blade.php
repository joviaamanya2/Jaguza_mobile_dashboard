<!-- ===== MARKETPLACE LISTING MODAL ===== -->
<div class="modal-overlay" id="marketplaceModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Add Marketplace Listing</h3>
            <button class="modal-close" type="button" onclick="closeModal('marketplaceModal')">&times;</button>
        </div>
        <form id="marketplaceForm" onsubmit="event.preventDefault(); saveMarketplaceListing();">
            <div class="form-group">
                <label for="marketplace_title">Title <span class="required">*</span></label>
                <input type="text" id="marketplace_title" class="form-control" placeholder="e.g. Two-wheel tractor" maxlength="255" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="marketplace_category">Category <span class="required">*</span></label>
                    <select id="marketplace_category" class="form-control" required>
                        <option value="equipment">Equipment</option>
                        <option value="housing">Housing</option>
                        <option value="livestock">Livestock</option>
                        <option value="poultry">Poultry</option>
                        <option value="feed">Feed</option>
                        <option value="medicine">Medicine</option>
                        <option value="services">Services</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="marketplace_price">Price (UGX) <span class="required">*</span></label>
                    <input type="number" id="marketplace_price" class="form-control" min="0" step="1" placeholder="0" required>
                </div>
            </div>
            <div class="form-group">
                <label for="marketplace_location">Location <span class="required">*</span></label>
                <input type="text" id="marketplace_location" class="form-control" placeholder="e.g. Kampala" maxlength="255" required>
            </div>
            <div class="form-group">
                <label for="marketplace_description">Description</label>
                <textarea id="marketplace_description" class="form-control" rows="4" placeholder="Describe the item, condition, and availability"></textarea>
            </div>
            <div class="form-group">
                <label for="marketplace_image">Image</label>
                <input type="file" id="marketplace_image" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="marketplaceSubmitBtn">Add Listing</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('marketplaceModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
