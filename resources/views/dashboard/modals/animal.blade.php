<!-- ===== ANIMAL MODAL ===== -->
<div class="modal-overlay" id="animalModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="animalModalTitle">Add New Animal</h3>
            <button class="modal-close" onclick="closeModal('animalModal')">&times;</button>
        </div>
        <form id="animalForm" onsubmit="event.preventDefault(); saveAnimal();">
            <input type="hidden" id="animalId">
            
            <div class="form-group">
                <label>Identification Number <span class="required">*</span></label>
                <input type="text" id="animal_identification" class="form-control" placeholder="AN-0001" required>
            </div>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" id="animal_name" class="form-control" placeholder="Animal name">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Type <span class="required">*</span></label>
                    <select id="animal_type" class="form-control" required>
                        <option value="cattle">Cattle</option>
                        <option value="goat">Goat</option>
                        <option value="sheep">Sheep</option>
                        <option value="pig">Pig</option>
                        <option value="poultry">Poultry</option>
                        <option value="rabbit">Rabbit</option>
                        <option value="horse">Horse</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Breed <span class="required">*</span></label>
                    <input type="text" id="animal_breed" class="form-control" placeholder="Friesian" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Gender <span class="required">*</span></label>
                    <select id="animal_gender" class="form-control" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Age (months) <span class="required">*</span></label>
                    <input type="number" id="animal_age" class="form-control" placeholder="24" min="0" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Weight (kg)</label>
                    <input type="number" id="animal_weight" class="form-control" placeholder="250" min="0" step="0.1">
                </div>
                <div class="form-group">
                    <label>Health Status <span class="required">*</span></label>
                    <select id="animal_health" class="form-control" required>
                        <option value="healthy">Healthy</option>
                        <option value="sick">Sick</option>
                        <option value="treatment">Under Treatment</option>
                        <option value="quarantine">Quarantine</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Farm <span class="required">*</span></label>
                <select id="animal_farm" class="form-control" required>
                    <option value="">Select Farm</option>
                    @foreach($farms ?? [] as $farm)
                    <option value="{{ $farm->id }}">{{ $farm->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Animal</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('animalModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
