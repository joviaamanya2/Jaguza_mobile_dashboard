// ============================================
// FARM CRUD FUNCTIONS WITH ANIMAL CATEGORIES
// ============================================

let selectedFacilities = [];
let animalRows = 1;

// ============================================
// ANIMAL CATEGORIES FUNCTIONS
// ============================================

function addAnimalCategoryRow() {
    const container = document.getElementById('animalCategoriesContainer');
    const row = document.createElement('div');
    row.className = 'animal-category-row';
    row.innerHTML = `
        <select class="animal-type-select form-control" style="flex:2;">
            <option value="">Select Animal Type</option>
            <option value="cattle">🐄 Cattle</option>
            <option value="goat">🐐 Goats</option>
            <option value="sheep">🐑 Sheep</option>
            <option value="pig">🐖 Pigs</option>
            <option value="poultry">🐔 Poultry</option>
            <option value="rabbit">🐰 Rabbits</option>
            <option value="horse">🐴 Horses</option>
            <option value="fish">🐟 Fish</option>
            <option value="other">Other</option>
        </select>
        <input type="number" class="animal-count-input form-control" style="flex:1;min-width:70px;" placeholder="Count" min="0" value="0">
        <button type="button" class="btn btn-outline" style="padding:4px 10px;font-size:14px;color:#dc3545;border-color:#dc3545;" onclick="removeAnimalRow(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(row);
    animalRows++;
}

function removeAnimalRow(button) {
    const container = document.getElementById('animalCategoriesContainer');
    if (container.children.length <= 1) {
        showToast('You need at least one animal category row', 'warning');
        return;
    }
    button.parentElement.remove();
    animalRows--;
}

function collectAnimalData() {
    const rows = document.querySelectorAll('.animal-category-row');
    const animals = [];
    let hasError = false;
    
    rows.forEach(row => {
        const typeSelect = row.querySelector('.animal-type-select');
        const countInput = row.querySelector('.animal-count-input');
        
        const type = typeSelect.value;
        const count = parseInt(countInput.value) || 0;
        
        if (type && count > 0) {
            animals.push({ type, count });
        } else if (type && !countInput.value) {
            hasError = true;
            countInput.style.borderColor = '#dc3545';
            countInput.style.backgroundColor = '#fff5f5';
        } else if (countInput.value && !type) {
            hasError = true;
            typeSelect.style.borderColor = '#dc3545';
            typeSelect.style.backgroundColor = '#fff5f5';
        } else {
            typeSelect.style.borderColor = '';
            typeSelect.style.backgroundColor = '';
            countInput.style.borderColor = '';
            countInput.style.backgroundColor = '';
        }
    });
    
    if (hasError) {
        showToast('Please fill in both animal type and count', 'error');
        return null;
    }
    
    return animals;
}

// ============================================
// FACILITIES FUNCTIONS
// ============================================

function addFacility() {
    const select = document.getElementById('facilitySelect');
    const facility = select.value;
    if (facility && !selectedFacilities.includes(facility)) {
        selectedFacilities.push(facility);
        renderFacilities();
    }
    select.value = '';
}

function removeFacility(facility) {
    selectedFacilities = selectedFacilities.filter(f => f !== facility);
    renderFacilities();
}

function renderFacilities() {
    const container = document.getElementById('facilitiesContainer');
    if (selectedFacilities.length === 0) {
        container.innerHTML = '<span style="color:#6a7a8a;font-size:12px;padding:4px 0;">Select facilities from dropdown</span>';
        return;
    }
    container.innerHTML = selectedFacilities.map(f => `
        <span style="background:#e8f5e9;color:#2e7d32;padding:4px 12px;border-radius:16px;font-size:12px;display:inline-flex;align-items:center;gap:6px;">
            ${f}
            <span onclick="removeFacility('${f}')" style="cursor:pointer;color:#dc3545;">&times;</span>
        </span>
    `).join('');
}

// ============================================
// FARM CRUD FUNCTIONS
// ============================================

function resetFarmForm() {
    document.getElementById('farmId').value = '';
    document.getElementById('farm_name').value = '';
    document.getElementById('farm_owner').value = '';
    document.getElementById('farm_location').value = '';
    document.getElementById('farm_size').value = '';
    document.getElementById('farm_established').value = '';
    document.getElementById('farm_coordinates').value = '';
    document.getElementById('farm_description').value = '';
    selectedFacilities = [];
    renderFacilities();
    
    // Reset animal categories to just one row
    const container = document.getElementById('animalCategoriesContainer');
    container.innerHTML = `
        <div class="animal-category-row">
            <select class="animal-type-select form-control" style="flex:2;">
                <option value="">Select Animal Type</option>
                <option value="cattle">🐄 Cattle</option>
                <option value="goat">🐐 Goats</option>
                <option value="sheep">🐑 Sheep</option>
                <option value="pig">🐖 Pigs</option>
                <option value="poultry">🐔 Poultry</option>
                <option value="rabbit">🐰 Rabbits</option>
                <option value="horse">🐴 Horses</option>
                <option value="fish">🐟 Fish</option>
                <option value="other">Other</option>
            </select>
            <input type="number" class="animal-count-input form-control" style="flex:1;min-width:70px;" placeholder="Count" min="0" value="0">
            <button type="button" class="btn btn-outline" style="padding:4px 10px;font-size:14px;color:#dc3545;border-color:#dc3545;" onclick="removeAnimalRow(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    animalRows = 1;
    
    document.getElementById('farmModalTitle').textContent = 'Add New Farm';
    document.getElementById('farmSubmitBtn').textContent = 'Save Farm';
}

function openAddFarmModal() {
    resetFarmForm();
    openModal('farmModal');
}

function editFarm(id) {
    showToast('Loading farm data...', 'info');
    
    fetch(`${API_URL}/farms/${id}`, {
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const farm = data.data.farm;
            
            document.getElementById('farmId').value = farm.id;
            document.getElementById('farm_name').value = farm.farm_name || farm.name || '';
            document.getElementById('farm_owner').value = farm.owner_name || '';
            document.getElementById('farm_location').value = farm.location || '';
            document.getElementById('farm_size').value = farm.size || '';
            document.getElementById('farm_established').value = farm.established_year || '';
            document.getElementById('farm_coordinates').value = farm.coordinates || '';
            document.getElementById('farm_description').value = farm.description || '';
            
            selectedFacilities = farm.facilities || [];
            renderFacilities();
            
            // Populate animal categories
            if (farm.animals && farm.animals.length > 0) {
                const container = document.getElementById('animalCategoriesContainer');
                container.innerHTML = '';
                farm.animals.forEach((animal) => {
                    const row = document.createElement('div');
                    row.className = 'animal-category-row';
                    row.innerHTML = `
                        <select class="animal-type-select form-control" style="flex:2;">
                            <option value="">Select Animal Type</option>
                            <option value="cattle" ${animal.type === 'cattle' ? 'selected' : ''}>🐄 Cattle</option>
                            <option value="goat" ${animal.type === 'goat' ? 'selected' : ''}>🐐 Goats</option>
                            <option value="sheep" ${animal.type === 'sheep' ? 'selected' : ''}>🐑 Sheep</option>
                            <option value="pig" ${animal.type === 'pig' ? 'selected' : ''}>🐖 Pigs</option>
                            <option value="poultry" ${animal.type === 'poultry' ? 'selected' : ''}>🐔 Poultry</option>
                            <option value="rabbit" ${animal.type === 'rabbit' ? 'selected' : ''}>🐰 Rabbits</option>
                            <option value="horse" ${animal.type === 'horse' ? 'selected' : ''}>🐴 Horses</option>
                            <option value="fish" ${animal.type === 'fish' ? 'selected' : ''}>🐟 Fish</option>
                            <option value="other" ${animal.type === 'other' ? 'selected' : ''}>Other</option>
                        </select>
                        <input type="number" class="animal-count-input form-control" style="flex:1;min-width:70px;" placeholder="Count" min="0" value="${animal.count || 0}">
                        <button type="button" class="btn btn-outline" style="padding:4px 10px;font-size:14px;color:#dc3545;border-color:#dc3545;" onclick="removeAnimalRow(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    container.appendChild(row);
                });
                animalRows = farm.animals.length;
            }
            
            document.getElementById('farmModalTitle').textContent = 'Edit Farm';
            document.getElementById('farmSubmitBtn').textContent = 'Update Farm';
            openModal('farmModal');
        } else {
            showToast(data.message || 'Error loading farm', 'error');
        }
    })
    .catch(error => {
        showToast('Error loading farm: ' + error.message, 'error');
    });
}

function deleteFarm(id) {
    if (!confirm('⚠️ Are you sure you want to delete this farm? This will also remove all associated animals.')) return;
    
    fetch(`${API_URL}/farms/${id}`, {
        method: 'DELETE',
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Farm deleted successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error deleting farm', 'error');
        }
    })
    .catch(error => {
        showToast('Error: ' + error.message, 'error');
    });
}

function saveFarm() {
    const id = document.getElementById('farmId').value;
    
    // Collect animal data
    const animals = collectAnimalData();
    if (animals === null) {
        return;
    }
    
    const data = {
        farm_name: document.getElementById('farm_name').value,
        owner_name: document.getElementById('farm_owner').value,
        location: document.getElementById('farm_location').value,
        size: document.getElementById('farm_size').value,
        established_year: document.getElementById('farm_established').value,
        coordinates: document.getElementById('farm_coordinates').value,
        description: document.getElementById('farm_description').value,
        facilities: selectedFacilities,
        animals: animals,
    };
    
    const url = id ? `${API_URL}/farms/${id}` : `${API_URL}/farms`;
    const method = id ? 'PUT' : 'POST';
    
    const submitBtn = document.getElementById('farmSubmitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    
    fetch(url, {
        method: method,
        headers: getHeaders(),
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.textContent = id ? 'Update Farm' : 'Save Farm';
        
        if (data.success) {
            showToast(id ? 'Farm updated successfully!' : 'Farm created successfully!');
            closeModal('farmModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            let errors = '';
            if (data.errors) {
                Object.values(data.errors).forEach(error => {
                    errors += error + '\n';
                });
                showToast(errors, 'error');
            } else {
                showToast(data.message || 'Error saving farm', 'error');
            }
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.textContent = id ? 'Update Farm' : 'Save Farm';
        showToast('Network error: ' + error.message, 'error');
    });
}