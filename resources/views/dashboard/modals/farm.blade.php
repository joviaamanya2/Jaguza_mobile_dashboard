<!-- ===== FARM MODAL ===== -->
<div class="modal-overlay" id="farmModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="farmModalTitle">Add New Farm</h3>
            <button class="modal-close" onclick="closeModal('farmModal')">&times;</button>
        </div>
        <form id="farmForm" onsubmit="event.preventDefault(); saveFarm();">
            <input type="hidden" id="farmId">
            
            <div class="form-group">
                <label>Farm Name <span class="required">*</span></label>
                <input type="text" id="farm_name" class="form-control" placeholder="Green Valley Farm" required>
            </div>
            
            <div class="form-group">
                <label>Owner Name <span class="required">*</span></label>
                <input type="text" id="farm_owner" class="form-control" placeholder="John Mukasa" required>
            </div>
            
            <div class="form-group">
                <label>Location <span class="required">*</span></label>
                <input type="text" id="farm_location" class="form-control" placeholder="Wakiso, Uganda" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Farm Size</label>
                    <input type="text" id="farm_size" class="form-control" placeholder="50 acres">
                </div>
                <div class="form-group">
                    <label>Established Year</label>
                    <input type="text" id="farm_established" class="form-control" placeholder="2018">
                </div>
            </div>
            
            <div class="form-group">
                <label>GPS Coordinates</label>
                <input type="text" id="farm_coordinates" class="form-control" placeholder="0.3136° N, 32.5811° E">
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea id="farm_description" class="form-control" rows="3" placeholder="Describe your farm..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Facilities</label>
                <div id="facilitiesContainer" style="display:flex;flex-wrap:wrap;gap:8px;padding:8px;border:1px solid #e8ecf1;border-radius:8px;min-height:44px;">
                    <span style="color:#6a7a8a;font-size:12px;padding:4px 0;">Select facilities from dropdown</span>
                </div>
                <select id="facilitySelect" class="form-control" style="margin-top:8px;" onchange="addFacility()">
                    <option value="">Select a facility...</option>
                    <option value="Barn">Barn</option>
                    <option value="Milking Parlor">Milking Parlor</option>
                    <option value="Poultry House">Poultry House</option>
                    <option value="Store">Store</option>
                    <option value="Silo">Silo</option>
                    <option value="Greenhouse">Greenhouse</option>
                    <option value="Irrigation System">Irrigation System</option>
                    <option value="Fencing">Fencing</option>
                    <option value="Water Tanks">Water Tanks</option>
                    <option value="Solar Panels">Solar Panels</option>
                    <option value="Processing Unit">Processing Unit</option>
                    <option value="Office">Office</option>
                </select>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="farmSubmitBtn">Save Farm</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('farmModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
