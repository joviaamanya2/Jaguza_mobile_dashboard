<div class="modal-overlay" id="userModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="userModalTitle">Add New User</h3>
            <button class="modal-close" onclick="closeModal('userModal')">&times;</button>
        </div>
        <form id="userForm" onsubmit="event.preventDefault(); saveUser();">
            <input type="hidden" id="userId">
            
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" id="user_name" class="form-control" placeholder="John Doe" required>
            </div>
            
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" id="user_email" class="form-control" placeholder="john@example.com" required>
            </div>
            
            <div class="form-group">
                <label>Role <span class="required">*</span></label>
                <select id="user_role" class="form-control" required onchange="toggleFarmFields()">
                    <option value="admin">Administrator</option>
                    <option value="farmer" selected>Farmer</option>
                    <option value="vet">Veterinary Doctor</option>
                    <option value="manager">Manager</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" id="user_phone" class="form-control" placeholder="+256 700 000 000">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label id="passwordLabel">Password <span class="required">*</span></label>
                    <input type="password" id="user_password" class="form-control" placeholder="Min 8 characters" required>
                </div>
                <div class="form-group">
                    <label id="confirmPasswordLabel">Confirm Password <span class="required">*</span></label>
                    <input type="password" id="user_password_confirm" class="form-control" placeholder="Confirm password" required>
                </div>
            </div>
            
            <!-- Farm fields - only visible when role is farmer -->
            <div id="farmFields" style="display: block;">
                <div class="form-row">
                    <div class="form-group">
                        <label>Farm Name</label>
                        <input type="text" id="user_farm_name" class="form-control" placeholder="Enter farm name">
                    </div>
                    <div class="form-group">
                        <label>Farm Location</label>
                        <input type="text" id="user_farm_location" class="form-control" placeholder="Enter farm location">
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="userSubmitBtn">Save User</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('userModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
