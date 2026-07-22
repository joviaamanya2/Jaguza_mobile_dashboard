// Toggle Farm Fields Based on Role
// ============================================

function toggleFarmFields() {
    const role = document.getElementById('user_role').value;
    const farmFields = document.getElementById('farmFields');
    
    if (role === 'farmer') {
        farmFields.style.display = 'block';
    } else {
        farmFields.style.display = 'none';
        // Clear farm fields when not farmer
        document.getElementById('user_farm_name').value = '';
        document.getElementById('user_farm_location').value = '';
    }
}

// ============================================
// Chart Configuration
// ============================================

Chart.defaults.color = '#4a5a6a';
Chart.defaults.borderColor = '#e8ecf1';
Chart.defaults.font.family = 'Inter';

const chartMonths = @json($months);
const sicknessData = @json($sicknessData);
const userData = @json($userData);
const marketData = @json($marketData);
const livestockLabels = @json($livestockLabels);
const livestockValues = @json($livestockValues);

function renderDashboardCharts() {
    new Chart(document.getElementById('sickChart'), {
        type: 'bar',
        data: {
            labels: chartMonths,
            datasets: [{
                label: 'Reports',
                data: sicknessData,
                backgroundColor: 'rgba(220,53,69,.2)',
                borderColor: '#dc3545',
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    
    const colors = ['#43a047','#66bb6a','#ffa726','#ef5350','#42a5f5','#ab47bc','#78909c','#8d6e63'];
    new Chart(document.getElementById('animalChart'), {
        type: 'doughnut',
        data: {
            labels: livestockLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
            datasets: [{
                data: livestockValues,
                backgroundColor: colors.slice(0, livestockLabels.length),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'right' } }
        }
    });
    
    new Chart(document.getElementById('userChart'), {
        type: 'line',
        data: {
            labels: chartMonths,
            datasets: [{
                label: 'New Users',
                data: userData,
                borderColor: '#2e7d32',
                backgroundColor: 'rgba(46,125,50,0.1)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#2e7d32'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    
    new Chart(document.getElementById('marketChart'), {
        type: 'bar',
        data: {
            labels: chartMonths,
            datasets: [{
                label: 'Listings',
                data: marketData,
                backgroundColor: 'rgba(253,126,20,.2)',
                borderColor: '#fd7e14',
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function renderAnalyticsCharts() {
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    new Chart(document.getElementById('userGrowthChart'), { 
        type:'line', 
        data:{ 
            labels:months, 
            datasets:[{ 
                label:'Users', 
                data:[800,920,1040,1200,1380,1520,1700,1840,1980,2150,2300,2481], 
                borderColor:'#2e7d32', 
                backgroundColor:'rgba(46,125,50,0.08)', 
                borderWidth:2.5, 
                tension:0.4, 
                fill:true 
            }] 
        }, 
        options:{ 
            responsive:true, 
            maintainAspectRatio:true,
            plugins:{legend:{display:false}}, 
            scales:{y:{beginAtZero:false}} 
        } 
    });
    
    new Chart(document.getElementById('diseaseChart'), { 
        type:'pie', 
        data:{ 
            labels:['ECF','FMD','Newcastle','Brucellosis','ASF','Other'], 
            datasets:[{ 
                data:[82,64,58,41,28,74], 
                backgroundColor:['#ef5350','#ffa726','#42a5f5','#ab47bc','#66bb6a','#78909c'], 
                borderWidth:0 
            }] 
        }, 
        options:{ 
            responsive:true, 
            maintainAspectRatio:true,
            plugins:{legend:{position:'right'}} 
        } 
    });
}

// ============================================
// DOCTOR CRUD FUNCTIONS
// ============================================

function resetDoctorForm() {
    document.getElementById('doctorId').value = '';
    document.getElementById('doctor_name').value = '';
    document.getElementById('doctor_email').value = '';
    document.getElementById('doctor_specialization').value = '';
    document.getElementById('doctor_license').value = '';
    document.getElementById('doctor_experience').value = '';
    document.getElementById('doctor_fee').value = '';
    document.getElementById('doctor_location').value = '';
    document.getElementById('doctor_phone').value = '';
    document.getElementById('doctor_bio').value = '';
    document.getElementById('doctorModalTitle').textContent = 'Add New Doctor';
    document.getElementById('doctorSubmitBtn').textContent = 'Save Doctor';
}

function openAddDoctorModal() {
    resetDoctorForm();
    openModal('doctorModal');
}

function editDoctor(id) {
    showToast('Loading doctor data...', 'info');
    
    fetch(`${API_URL}/doctors/${id}`, {
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const doctor = data.data;
            const user = doctor.user;
            
            document.getElementById('doctorId').value = doctor.id;
            document.getElementById('doctor_name').value = user?.name || '';
            document.getElementById('doctor_email').value = user?.email || '';
            document.getElementById('doctor_specialization').value = doctor.specialization || '';
            document.getElementById('doctor_license').value = doctor.license_number || '';
            document.getElementById('doctor_experience').value = doctor.years_of_experience || 0;
            document.getElementById('doctor_fee').value = doctor.consultation_fee || 0;
            document.getElementById('doctor_location').value = doctor.location || '';
            document.getElementById('doctor_phone').value = doctor.phone_number || '';
            document.getElementById('doctor_bio').value = doctor.bio || '';
            
            document.getElementById('doctorModalTitle').textContent = 'Edit Doctor';
            document.getElementById('doctorSubmitBtn').textContent = 'Update Doctor';
            
            openModal('doctorModal');
        } else {
            showToast(data.message || 'Error loading doctor', 'error');
        }
    })
    .catch(error => {
        showToast('Error loading doctor: ' + error.message, 'error');
    });
}

function deleteDoctor(id) {
    if (!confirm('⚠️ Are you sure you want to delete this doctor? This will also delete their user account.')) return;
    
    fetch(`${API_URL}/doctors/${id}`, {
        method: 'DELETE',
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Doctor deleted successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error deleting doctor', 'error');
        }
    })
    .catch(error => {
        showToast('Error: ' + error.message, 'error');
    });
}

function saveDoctor() {
    const id = document.getElementById('doctorId').value;
    
    // Build data object with correct field names
    const data = {
        name: document.getElementById('doctor_name').value,
        email: document.getElementById('doctor_email').value,
        specialization: document.getElementById('doctor_specialization').value,
        license_number: document.getElementById('doctor_license').value,
        years_of_experience: parseInt(document.getElementById('doctor_experience').value) || 0,
        consultation_fee: parseFloat(document.getElementById('doctor_fee').value) || 0,
        location: document.getElementById('doctor_location').value,
        phone_number: document.getElementById('doctor_phone').value,
        bio: document.getElementById('doctor_bio').value,
    };
    
    const url = id ? `${API_URL}/doctors/${id}` : `${API_URL}/doctors`;
    const method = id ? 'PUT' : 'POST';
    
    const submitBtn = document.getElementById('doctorSubmitBtn');
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
        submitBtn.textContent = id ? 'Update Doctor' : 'Save Doctor';
        
        if (data.success) {
            showToast(id ? 'Doctor updated successfully!' : 'Doctor added successfully!');
            closeModal('doctorModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            let errors = '';
            if (data.errors) {
                Object.values(data.errors).forEach(error => {
                    errors += error + '\n';
                });
                showToast(errors, 'error');
            } else {
                showToast(data.message || 'Error saving doctor', 'error');
            }
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.textContent = id ? 'Update Doctor' : 'Save Doctor';
        showToast('Network error: ' + error.message, 'error');
    });
}

function toggleDoctorAvailability(id, currentStatus) {
    const action = currentStatus ? 'mark as busy' : 'mark as available';
    if (!confirm(`Are you sure you want to ${action} this doctor?`)) return;
    
    fetch(`${API_URL}/doctors/${id}/availability`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ is_available: !currentStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error updating availability', 'error');
        }
    })
    .catch(error => {
        showToast('Error: ' + error.message, 'error');
    });
}

// ============================================
// Navigation Functions
// ============================================

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('topbar').classList.toggle('shifted');
    document.getElementById('main-wrap').classList.toggle('shifted');
}

function navigate(pageId, title) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    var page = document.getElementById('page-' + pageId);
    if (page) page.classList.add('active');
    var nav = document.getElementById('nav-' + pageId);
    if (nav) nav.classList.add('active');
    document.getElementById('page-title').textContent = title || pageId.charAt(0).toUpperCase() + pageId.slice(1);
    document.getElementById('main-wrap').scrollTop = 0;
    if (pageId === 'analytics' && !window.analyticsRendered) { 
        renderAnalyticsCharts(); 
        window.analyticsRendered = true; 
    }
}

// ============================================
// API Helpers
// ============================================

const API_URL = '{{ url("/api/v1") }}';
const ADMIN_URL = '{{ url("/admin") }}';
const CSRF_TOKEN = '{{ csrf_token() }}';

function getHeaders() {
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN,
    };
}

function showToast(message, type = 'success') {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i> ${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    } else {
        console.error('Modal not found:', modalId);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        const form = document.getElementById(modalId + 'Form');
        if (form) form.reset();
    }
}

// Close modal on overlay click
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
});

// ============================================
// USER CRUD FUNCTIONS
// ============================================

function resetUserForm() {
    document.getElementById('userId').value = '';
    document.getElementById('user_name').value = '';
    document.getElementById('user_email').value = '';
    document.getElementById('user_role').value = 'farmer';
    document.getElementById('user_phone').value = '';
    document.getElementById('user_farm_name').value = '';
    document.getElementById('user_farm_location').value = '';
    document.getElementById('user_password').value = '';
    document.getElementById('user_password_confirm').value = '';
    document.getElementById('user_password').required = true;
    document.getElementById('user_password_confirm').required = true;
    document.getElementById('userModalTitle').textContent = 'Add New User';
    document.getElementById('passwordLabel').textContent = 'Password *';
    document.getElementById('confirmPasswordLabel').textContent = 'Confirm Password *';
    document.getElementById('userSubmitBtn').textContent = 'Save User';
    
    // Show farm fields by default (since farmer is default role)
    document.getElementById('farmFields').style.display = 'block';
}

function openAddUserModal() {
    resetUserForm();
    openModal('userModal');
}

function editUser(id) {
    showToast('Loading user data...', 'info');
    
    fetch(`${API_URL}/users/${id}`, {
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const user = data.data;
            document.getElementById('userId').value = user.id;
            document.getElementById('user_name').value = user.name || '';
            document.getElementById('user_email').value = user.email || '';
            document.getElementById('user_role').value = user.role || 'farmer';
            document.getElementById('user_phone').value = user.phone_number || '';
            
            // Set farm fields
            document.getElementById('user_farm_name').value = user.farm_name || '';
            document.getElementById('user_farm_location').value = user.farm_location || '';
            
            // Show/hide farm fields based on role
            toggleFarmFields();
            
            document.getElementById('userModalTitle').textContent = 'Edit User';
            document.getElementById('passwordLabel').textContent = 'New Password (optional)';
            document.getElementById('confirmPasswordLabel').textContent = 'Confirm New Password';
            document.getElementById('user_password').required = false;
            document.getElementById('user_password_confirm').required = false;
            document.getElementById('userSubmitBtn').textContent = 'Update User';
            openModal('userModal');
        } else {
            showToast(data.message || 'Error loading user', 'error');
        }
    })
    .catch(error => {
        showToast('Error loading user: ' + error.message, 'error');
    });
}

function deleteUser(id) {
    if (!confirm('⚠️ Are you sure you want to delete this user?')) return;
    
    fetch(`${API_URL}/users/${id}`, {
        method: 'DELETE',
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('User deleted successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error deleting user', 'error');
        }
    })
    .catch(error => {
        showToast('Error: ' + error.message, 'error');
    });
}

function saveUser() {
    const id = document.getElementById('userId').value;
    const password = document.getElementById('user_password').value;
    const passwordConfirm = document.getElementById('user_password_confirm').value;
    const role = document.getElementById('user_role').value;
    
    if (password !== passwordConfirm) {
        showToast('Passwords do not match!', 'error');
        return;
    }
    
    const data = {
        name: document.getElementById('user_name').value,
        email: document.getElementById('user_email').value,
        role: role,
        phone_number: document.getElementById('user_phone').value,
    };
    
    // Only include farm fields if role is farmer
    if (role === 'farmer') {
        data.farm_name = document.getElementById('user_farm_name').value || null;
        data.farm_location = document.getElementById('user_farm_location').value || null;
    }
    
    if (password) data.password = password;
    
    const url = id ? `${API_URL}/users/${id}` : `${API_URL}/users`;
    const method = id ? 'PUT' : 'POST';
    
    const submitBtn = document.getElementById('userSubmitBtn');
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
        submitBtn.textContent = id ? 'Update User' : 'Save User';
        
        if (data.success) {
            showToast(id ? 'User updated successfully!' : 'User created successfully!');
            closeModal('userModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            let errors = '';
            if (data.errors) {
                Object.values(data.errors).forEach(error => {
                    errors += error + '\n';
                });
                showToast(errors, 'error');
            } else {
                showToast(data.message || 'Error saving user', 'error');
            }
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.textContent = id ? 'Update User' : 'Save User';
        showToast('Network error: ' + error.message, 'error');
    });
}

// ============================================
// OTHER MODAL FUNCTIONS
// ============================================

function openAddDoctorModal() { openModal('doctorModal'); }
function openAddAnimalModal() { openModal('animalModal'); }

// ============================================
// SICKNESS REPORT CRUD FUNCTIONS
// ============================================

function resetReportForm() {
    document.getElementById('reportId').value = '';
    document.getElementById('report_user').value = '';
    document.getElementById('report_animal_type').value = 'cattle';
    document.getElementById('report_animal_count').value = '1';
    document.getElementById('report_symptom_primary').value = '';
    document.getElementById('report_symptom_other').value = '';
    document.getElementById('report_symptom_duration').value = '';
    document.getElementById('report_severity').value = 'medium';
    document.getElementById('report_status').value = 'open';
    document.getElementById('report_notes').value = '';
    document.getElementById('report_attachments').value = '';
    document.getElementById('reportModalTitle').textContent = 'New Sickness Report';
    document.getElementById('reportSubmitBtn').textContent = 'Save Report';
}

function openAddReportModal() {
    resetReportForm();
    openModal('reportModal');
}

function editReport(id) {
    showToast('Loading report...', 'info');

    fetch(`${API_URL}/reports/${id}`, { headers: getHeaders() })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const r = data.data;
            document.getElementById('reportId').value = r.id;
            document.getElementById('report_user').value = r.user_id || '';
            document.getElementById('report_animal_type').value = r.affected_animal_type || 'cattle';
            document.getElementById('report_animal_count').value = r.affected_animal_count || 1;
            document.getElementById('report_symptom_primary').value = r.symptom_primary || '';
            document.getElementById('report_symptom_other').value = r.symptom_other || '';
            document.getElementById('report_symptom_duration').value = r.symptom_duration || '';
            document.getElementById('report_severity').value = (r.severity_level || 'medium').toLowerCase();
            document.getElementById('report_status').value = r.status || 'open';
            document.getElementById('report_notes').value = r.notes || '';
            document.getElementById('report_attachments').value = Array.isArray(r.attachments) ? r.attachments.join('\n') : '';

            document.getElementById('reportModalTitle').textContent = 'Edit Sickness Report';
            document.getElementById('reportSubmitBtn').textContent = 'Update Report';
            openModal('reportModal');
        } else {
            showToast(data.message || 'Error loading report', 'error');
        }
    })
    .catch(error => showToast('Error loading report: ' + error.message, 'error'));
}

function deleteReport(id) {
    if (!confirm('⚠️ Are you sure you want to delete this sickness report?')) return;

    fetch(`${API_URL}/reports/${id}`, { method: 'DELETE', headers: getHeaders() })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Report deleted successfully!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error deleting report', 'error');
        }
    })
    .catch(error => showToast('Error: ' + error.message, 'error'));
}

function saveReport() {
    const id = document.getElementById('reportId').value;

    const attachments = document.getElementById('report_attachments').value
        .split(/[\r\n,]+/)
        .map(s => s.trim())
        .filter(s => s.length > 0);

    const data = {
        user_id: document.getElementById('report_user').value || null,
        affected_animal_type: document.getElementById('report_animal_type').value,
        affected_animal_count: parseInt(document.getElementById('report_animal_count').value) || 1,
        symptom_primary: document.getElementById('report_symptom_primary').value,
        symptom_other: document.getElementById('report_symptom_other').value || null,
        symptom_duration: document.getElementById('report_symptom_duration').value || null,
        severity_level: document.getElementById('report_severity').value,
        status: document.getElementById('report_status').value,
        notes: document.getElementById('report_notes').value || null,
        attachments: attachments,
    };

    const url = id ? `${API_URL}/reports/${id}` : `${API_URL}/reports`;
    const method = id ? 'PUT' : 'POST';

    const submitBtn = document.getElementById('reportSubmitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';

    fetch(url, { method: method, headers: getHeaders(), body: JSON.stringify(data) })
    .then(response => response.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.textContent = id ? 'Update Report' : 'Save Report';

        if (data.success) {
            showToast(id ? 'Report updated successfully!' : 'Report created successfully!');
            closeModal('reportModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            let errors = '';
            if (data.errors) {
                Object.values(data.errors).forEach(error => { errors += error + '\n'; });
                showToast(errors, 'error');
            } else {
                showToast(data.message || 'Error saving report', 'error');
            }
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.textContent = id ? 'Update Report' : 'Save Report';
        showToast('Network error: ' + error.message, 'error');
    });
}

function openAddDiseaseModal() { alert('Add Disease functionality coming soon!'); }
function openAddFarmModal() { alert('Add Farm functionality coming soon!'); }
function openAddVideoModal() { alert('Upload Video functionality coming soon!'); }
function openAddAdModal() { alert('Create Ad functionality coming soon!'); }
function openAddGestationModal() { alert('Add Gestation functionality coming soon!'); }
function openAddNotificationModal() { alert('Send Notification functionality coming soon!'); }
function openComposeMessageModal() { alert('Compose Message functionality coming soon!'); }
function openAddVaccinationModal() { alert('Add Vaccination functionality coming soon!'); }
function openAddListingModal() { alert('Add Listing functionality coming soon!'); }
function openAddDecisionModal() { alert('Add Decision Article functionality coming soon!'); }

function refreshWeather() { alert('Refreshing weather data...'); }
function clearChatHistory() { if(confirm('Clear chat history?')) { alert('Cleared'); } }
function sendChatMessage() { 
    const input = document.getElementById('chatInput');
    if(input && input.value.trim()) { alert('Sending: ' + input.value); input.value = ''; }
}
function saveSettings() { alert('Settings saved!'); }
function exportReport() { alert('Exporting report...'); }
function playVideo(id) { alert('Play Video: ' + id); }

// ============================================
// FARM CRUD FUNCTIONS
// ============================================

let selectedFacilities = [];

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
    document.getElementById('farmModalTitle').textContent = 'Add New Farm';
    document.getElementById('farmSubmitBtn').textContent = 'Save Farm';
}

function openAddFarmModal() {
    resetFarmForm();
    openModal('farmModal');
}

function editFarm(id) {
    showToast('Loading farm data...', 'info');
    
    fetch(`${ADMIN_URL}/farms/${id}`, {
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const farm = data.data.farm;
            
            document.getElementById('farmId').value = farm.id;
            document.getElementById('farm_name').value = farm.name || '';
            document.getElementById('farm_owner').value = farm.owner_name || '';
            document.getElementById('farm_location').value = farm.location || '';
            document.getElementById('farm_size').value = farm.size || '';
            document.getElementById('farm_established').value = farm.established_year || '';
            document.getElementById('farm_coordinates').value = farm.coordinates || '';
            document.getElementById('farm_description').value = farm.description || '';
            
            selectedFacilities = farm.facilities || [];
            renderFacilities();
            
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
    
    fetch(`${ADMIN_URL}/farms/${id}`, {
        method: 'DELETE',
        headers: getHeaders()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateFarmCountCards(data.stats);
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
    
    const data = {
        name: document.getElementById('farm_name').value,
        owner_name: document.getElementById('farm_owner').value,
        location: document.getElementById('farm_location').value,
        size: document.getElementById('farm_size').value,
        established_year: document.getElementById('farm_established').value,
        coordinates: document.getElementById('farm_coordinates').value,
        description: document.getElementById('farm_description').value,
        facilities: selectedFacilities,
    };
    
    const url = id ? `${ADMIN_URL}/farms/${id}` : `${ADMIN_URL}/farms`;
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
            updateFarmCountCards(data.stats);
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

function updateFarmCountCards(stats) {
    if (!stats) return;

    const total = Number(stats.total || 0);
    const active = Number(stats.active || 0);
    const values = {
        'dashboard-farms-count': total,
        'farms-total-count': total,
        'farms-active-count': active,
        'farms-inactive-count': total - active,
    };

    Object.entries(values).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value.toLocaleString();
    });
}

// ============================================
// Initialization
// ============================================

window.addEventListener('DOMContentLoaded', function() {
    renderDashboardCharts();
    toggleFarmFields(); // Initialize farm fields toggle
    
    // Global search
    document.getElementById('globalSearch').addEventListener('keyup', function(e) {
        if(e.key === 'Enter' && this.value.length > 2) {
            alert('Searching for: ' + this.value);
        }
    });
});
