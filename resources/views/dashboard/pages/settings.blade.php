  <!-- ===== SETTINGS ===== -->
  <div class="page" id="page-settings">
    <div class="section-heading">
      <h2><i class="fas fa-cog" style="color:#2e7d32;margin-right:8px;"></i>Settings</h2>
      <button class="btn btn-primary" onclick="saveSettings()">Save Changes</button>
    </div>
    <div class="card">
      <h3 style="font-size:14px;font-weight:600;margin-bottom:20px;color:#1a1a2e;">General Settings</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
        <div class="form-group"><label>App Name</label><input class="form-control" id="setting_app_name" value="{{ $settings['app_name'] ?? 'Jaguza Livestock Management' }}" /></div>
        <div class="form-group"><label>Admin Email</label><input class="form-control" id="setting_admin_email" value="{{ $settings['admin_email'] ?? 'admin@jaguzalivestock.com' }}" /></div>
        <div class="form-group"><label>Country</label><input class="form-control" id="setting_country" value="{{ $settings['country'] ?? 'Uganda' }}" /></div>
        <div class="form-group"><label>Currency</label><input class="form-control" id="setting_currency" value="{{ $settings['currency'] ?? 'UGX' }}" /></div>
        <div class="form-group"><label>App Version</label><input class="form-control" id="setting_version" value="{{ $settings['app_version'] ?? '2.0.0' }}" readonly /></div>
        <div class="form-group"><label>Backend URL</label><input class="form-control" id="setting_backend_url" value="{{ $settings['backend_url'] ?? 'https://api.jaguzalivestock.com' }}" /></div>
      </div>
    </div>
  </div>
