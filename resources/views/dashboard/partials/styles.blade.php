    /* ... (your existing styles remain the same) ... */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --green-900: #0a2e14; 
      --green-800: #133b24; 
      --green-700: #1b5e20;
      --green-600: #2e7d32; 
      --green-500: #388e3c; 
      --green-400: #43a047;
      --green-300: #66bb6a; 
      --green-200: #a5d6a7; 
      --green-100: #e8f5e9;
      --accent: #2e7d32; 
      --gold: #f5c518; 
      --red: #dc3545;
      --blue: #0d6efd; 
      --orange: #fd7e14;
      --sidebar-w: 260px; 
      --sidebar-collapsed: 68px;
      --topbar-h: 64px; 
      --radius: 12px;
      --shadow: 0 2px 12px rgba(0,0,0,.08);
      --transition: .25s cubic-bezier(.4,0,.2,1);
    }
    
    html, body { height: 100%; margin: 0; padding: 0; }
    body { 
      font-family:'Inter',sans-serif; 
      background:#f0f2f5; 
      color:#1a1a2e; 
      min-height:100vh; 
      display:flex; 
      overflow:hidden;
    }

    #sidebar { 
      width:var(--sidebar-w); 
      background:#ffffff; 
      border-right:1px solid #e8ecf1; 
      display:flex; 
      flex-direction:column; 
      transition:width var(--transition); 
      overflow:hidden; 
      position:fixed; 
      top:0; 
      left:0; 
      bottom:0; 
      z-index:100; 
      box-shadow:2px 0 12px rgba(0,0,0,.06); 
    }
    #sidebar.collapsed { width:var(--sidebar-collapsed); }
    
    .sidebar-logo { 
      display:flex; 
      align-items:center; 
      gap:12px; 
      padding:16px 20px; 
      background:linear-gradient(135deg, var(--green-700), var(--green-600));
      border-bottom:1px solid rgba(255,255,255,.1); 
      min-height:var(--topbar-h); 
      flex-shrink:0; 
    }
    .logo-icon { 
      width:40px; 
      height:40px; 
      background:rgba(255,255,255,.2); 
      border-radius:10px; 
      display:flex; 
      align-items:center; 
      justify-content:center; 
      flex-shrink:0; 
    }
    .logo-icon i { color:#fff; font-size:20px; }
    .logo-text { overflow:hidden; white-space:nowrap; }
    .logo-text h1 { font-size:18px; font-weight:700; color:#fff; letter-spacing:-.5px; }
    .logo-text span { font-size:11px; color:rgba(255,255,255,.7); font-weight:400; }
    
    .sidebar-scroll { 
      flex:1; 
      overflow-y:auto; 
      overflow-x:hidden; 
      padding:8px 0 20px; 
      min-height:0;
      background:#ffffff;
    }
    .sidebar-scroll::-webkit-scrollbar { width:4px; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background:#d0d7de; border-radius:2px; }
    
    .nav-section-label { 
      font-size:10px; 
      font-weight:600; 
      text-transform:uppercase; 
      letter-spacing:0.8px; 
      color:#8c9aab; 
      padding:16px 20px 6px; 
      white-space:nowrap; 
      overflow:hidden; 
    }
    #sidebar.collapsed .nav-section-label { opacity:0; height:0; padding:0; }
    
    .nav-item { 
      display:flex; 
      align-items:center; 
      gap:12px; 
      padding:10px 16px; 
      margin:2px 10px; 
      border-radius:8px; 
      cursor:pointer; 
      transition:all var(--transition); 
      text-decoration:none; 
      color:#5a6a7a; 
      white-space:nowrap; 
      overflow:hidden; 
      position:relative; 
    }
    .nav-item:hover { background:#e8f5e9; color:#1b5e20; transform:translateX(2px); }
    .nav-item.active { background:#e8f5e9; color:#1b5e20; font-weight:500; }
    .nav-item .nav-icon { 
      width:34px; height:34px; display:flex; align-items:center; justify-content:center; 
      border-radius:8px; background:#f0f2f5; flex-shrink:0; font-size:14px; 
      transition:all var(--transition); color:#5a6a7a;
    }
    .nav-item:hover .nav-icon, .nav-item.active .nav-icon { background:#c8e6c9; color:#1b5e20; }
    .nav-item .nav-label { font-size:13px; font-weight:500; flex:1; }
    .nav-item .nav-badge { 
      background:#dc3545; color:#fff; font-size:10px; font-weight:600; 
      border-radius:10px; padding:1px 8px; flex-shrink:0; 
    }
    #sidebar.collapsed .nav-label, #sidebar.collapsed .nav-badge { display:none; }
    
    .sidebar-footer { padding:12px 16px; border-top:1px solid #e8ecf1; flex-shrink:0; background:#ffffff; }
    .sidebar-user { 
      display:flex; align-items:center; gap:10px; padding:8px 12px; 
      border-radius:8px; cursor:pointer; transition:background var(--transition); 
    }
    .sidebar-user:hover { background:#f0f2f5; }
    .user-avatar { 
      width:36px; height:36px; border-radius:50%; 
      background:linear-gradient(135deg, var(--green-500), var(--green-700)); 
      display:flex; align-items:center; justify-content:center; 
      color:#fff; font-size:14px; font-weight:600; flex-shrink:0; 
    }
    .user-info { overflow:hidden; }
    .user-info p { font-size:13px; font-weight:600; color:#1a1a2e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .user-info span { font-size:11px; color:#8c9aab; }
    #sidebar.collapsed .user-info { display:none; }

    #topbar { 
      position:fixed; top:0; left:var(--sidebar-w); right:0; height:var(--topbar-h); 
      background:#ffffff; border-bottom:1px solid #e8ecf1; 
      display:flex; align-items:center; justify-content:space-between; 
      padding:0 24px; z-index:90; transition:left var(--transition); 
    }
    #topbar.shifted { left:var(--sidebar-collapsed); }
    .topbar-left { display:flex; align-items:center; gap:16px; }
    #toggle-btn { 
      width:36px; height:36px; background:#f0f2f5; border:1px solid #e8ecf1; 
      border-radius:8px; color:#1a1a2e; cursor:pointer; display:flex; 
      align-items:center; justify-content:center; font-size:16px; 
      transition:all var(--transition); 
    }
    #toggle-btn:hover { background:#e8f5e9; border-color:#a5d6a7; color:#1b5e20; }
    #page-title { font-size:18px; font-weight:600; color:#1a1a2e; }
    
    .topbar-search { 
      display:flex; align-items:center; background:#f0f2f5; border:1px solid #e8ecf1; 
      border-radius:8px; padding:0 14px; gap:8px; height:38px; min-width:220px; 
      transition:all var(--transition);
    }
    .topbar-search:focus-within { border-color:#66bb6a; box-shadow:0 0 0 3px rgba(46,125,50,.1); }
    .topbar-search i { color:#8c9aab; font-size:14px; }
    .topbar-search input { background:transparent; border:none; outline:none; color:#1a1a2e; font-size:13px; width:100%; }
    .topbar-search input::placeholder { color:#b0bec5; }
    
    .topbar-right { display:flex; align-items:center; gap:8px; }
    .topbar-action-btn { 
      width:36px; height:36px; border-radius:8px; background:transparent; 
      border:1px solid #e8ecf1; color:#5a6a7a; cursor:pointer; display:flex; 
      align-items:center; justify-content:center; font-size:15px; position:relative; 
      transition:all var(--transition); 
    }
    .topbar-action-btn:hover { background:#e8f5e9; border-color:#a5d6a7; color:#1b5e20; }
    .topbar-badge { 
      position:absolute; top:-4px; right:-4px; width:18px; height:18px; 
      background:#dc3545; border-radius:50%; font-size:9px; color:#fff; 
      font-weight:600; display:flex; align-items:center; justify-content:center; 
    }

    #main-wrap { 
      margin-left:var(--sidebar-w); margin-top:var(--topbar-h); flex:1; 
      height:calc(100vh - var(--topbar-h)); overflow-y:auto; overflow-x:hidden; 
      transition:margin-left var(--transition); position:relative; background:#f0f2f5;
    }
    #main-wrap.shifted { margin-left:var(--sidebar-collapsed); }
    .page { display:none; padding:28px; animation:fadeIn .3s ease; min-height:100%; }
    .page.active { display:block; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

    #main-wrap::-webkit-scrollbar { width:8px; }
    #main-wrap::-webkit-scrollbar-track { background:#f0f2f5; }
    #main-wrap::-webkit-scrollbar-thumb { background:#c8d0d8; border-radius:4px; }
    #main-wrap::-webkit-scrollbar-thumb:hover { background:#a8b8c8; }

    .stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:18px; margin-bottom:28px; }
    .stat-card { 
      background:#ffffff; border:1px solid #e8ecf1; border-radius:var(--radius); 
      padding:20px 22px; display:flex; align-items:center; gap:16px; 
      box-shadow:var(--shadow); transition:all .2s; 
    }
    .stat-card:hover { transform:translateY(-2px); box-shadow:0 4px 20px rgba(0,0,0,.08); border-color:#a5d6a7; }
    .stat-icon { width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
    .stat-body h3 { font-size:24px; font-weight:700; color:#1a1a2e; }
    .stat-body p { font-size:12.5px; color:#6a7a8a; margin-top:2px; }
    .stat-trend { font-size:11px; font-weight:600; margin-top:4px; }
    .trend-up { color:#2e7d32; } .trend-down { color:#dc3545; }

    .section-heading { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .section-heading h2 { font-size:16px; font-weight:600; color:#1a1a2e; }
    .btn { padding:8px 20px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; border:none; transition:all .2s; }
    .btn:hover { opacity:.9; transform:translateY(-1px); box-shadow:0 4px 12px rgba(46,125,50,.2); }
    .btn-primary { background:linear-gradient(135deg, var(--green-600), var(--green-700)); color:#fff; }
    .btn-outline { background:transparent; border:1px solid #c8d0d8; color:#1a1a2e; }
    .btn-outline:hover { border-color:#66bb6a; color:#1b5e20; background:#e8f5e9; }

    .card { background:#ffffff; border:1px solid #e8ecf1; border-radius:var(--radius); padding:20px; box-shadow:var(--shadow); }
    .table-wrap { overflow-x:auto; border-radius:8px; }
    table { width:100%; border-collapse:collapse; font-size:13px; }
    thead th { 
      background:#f8f9fa; color:#4a5a6a; padding:12px 14px; text-align:left; 
      font-weight:600; font-size:11px; text-transform:uppercase; letter-spacing:.5px; 
      white-space:nowrap; border-bottom:2px solid #e8ecf1;
    }
    tbody tr { border-bottom:1px solid #f0f2f5; transition:background .15s; }
    tbody tr:hover { background:#f8f9fa; }
    tbody td { padding:12px 14px; color:#1a1a2e; vertical-align:middle; }
    
    .avatar-sm { 
      width:32px; height:32px; border-radius:50%; 
      background:linear-gradient(135deg, var(--green-500), var(--green-700)); 
      display:inline-flex; align-items:center; justify-content:center; 
      color:#fff; font-size:12px; font-weight:600; margin-right:8px; 
    }
    .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:500; }
    .badge-green  { background:#e8f5e9; color:#2e7d32; }
    .badge-red    { background:#fde8e8; color:#c62828; }
    .badge-orange { background:#fff3e0; color:#e65100; }
    .badge-blue   { background:#e3f2fd; color:#0d47a1; }
    .badge-purple { background:#f3e5f5; color:#4a148c; }
    .badge-gray   { background:#f5f5f5; color:#616161; }

    .charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-top:18px; }
    @media(max-width:900px) { .charts-grid { grid-template-columns:1fr; } }
    .chart-card { background:#ffffff; border:1px solid #e8ecf1; border-radius:var(--radius); padding:20px; box-shadow:var(--shadow); }
    .chart-card h3 { font-size:14px; font-weight:600; margin-bottom:16px; color:#1a1a2e; }
    canvas { max-height:240px; }

    .quick-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:14px; margin-top:8px; }
    .quick-card { 
      background:#ffffff; border:1px solid #e8ecf1; border-radius:10px; padding:18px 14px; 
      display:flex; flex-direction:column; align-items:center; gap:10px; cursor:pointer; 
      transition:all .2s; text-align:center; box-shadow:var(--shadow);
    }
    .quick-card:hover { transform:translateY(-3px); border-color:#66bb6a; box-shadow:0 4px 20px rgba(46,125,50,.12); }
    .quick-card .qi { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; }
    .quick-card span { font-size:12px; font-weight:500; color:#1a1a2e; }

    .form-group { margin-bottom:16px; }
    .form-group label { display:block; font-size:12px; font-weight:500; color:#4a5a6a; margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px; }
    .form-control { 
      width:100%; background:#ffffff; border:1px solid #d0d7de; border-radius:8px; 
      padding:10px 14px; color:#1a1a2e; font-size:13px; font-family:'Inter',sans-serif; 
      outline:none; transition:border-color .2s; 
    }
    .form-control:focus { border-color:#66bb6a; box-shadow:0 0 0 3px rgba(46,125,50,.1); }

    .notif-item { display:flex; gap:14px; padding:14px 0; border-bottom:1px solid #f0f2f5; }
    .notif-item:last-child { border-bottom:none; }
    .notif-dot { width:10px; height:10px; border-radius:50%; margin-top:5px; flex-shrink:0; }
    .notif-body p { font-size:13.5px; color:#1a1a2e; }
    .notif-body span { font-size:11px; color:#8c9aab; margin-top:3px; display:block; }

    .msg-item { 
      display:flex; align-items:center; gap:12px; padding:12px 0; 
      border-bottom:1px solid #f0f2f5; cursor:pointer; transition:background .15s; border-radius:8px; 
    }
    .msg-item:hover { background:#f8f9fa; padding-left:8px; }
    .msg-body { flex:1; min-width:0; }
    .msg-body h4 { font-size:13.5px; font-weight:600; color:#1a1a2e; }
    .msg-body p { font-size:12px; color:#6a7a8a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .msg-meta { text-align:right; flex-shrink:0; }
    .msg-meta span { font-size:11px; color:#8c9aab; }
    .msg-unread { width:8px; height:8px; border-radius:50%; background:#2e7d32; margin-top:4px; float:right; }

    .video-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:18px; }
    .video-card { 
      background:#ffffff; border:1px solid #e8ecf1; border-radius:var(--radius); 
      overflow:hidden; transition:all .2s; box-shadow:var(--shadow);
    }
    .video-card:hover { transform:translateY(-3px); box-shadow:0 4px 20px rgba(0,0,0,.08); }
    .video-thumb { 
      height:150px; background:linear-gradient(135deg, #e8f5e9, #c8e6c9); 
      display:flex; align-items:center; justify-content:center; font-size:48px; 
      color:rgba(46,125,50,.3); position:relative; overflow:hidden;
    }
    .video-thumb img { width:100%; height:100%; object-fit:cover; }
    .play-btn { 
      position:absolute; width:48px; height:48px; background:rgba(46,125,50,.15); 
      border-radius:50%; display:flex; align-items:center; justify-content:center; 
      color:#2e7d32; font-size:20px; cursor:pointer; transition:all .2s; backdrop-filter:blur(4px); 
    }
    .play-btn:hover { background:rgba(46,125,50,.3); transform:scale(1.1); }
    .video-info { padding:14px; }
    .video-info h4 { font-size:13.5px; font-weight:600; color:#1a1a2e; margin-bottom:4px; }
    .video-info p { font-size:12px; color:#6a7a8a; }

    .animal-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:18px; }
    .animal-card { 
      background:#ffffff; border:1px solid #e8ecf1; border-radius:var(--radius); 
      padding:20px; text-align:center; transition:all .2s; box-shadow:var(--shadow);
    }
    .animal-card:hover { transform:translateY(-3px); border-color:#66bb6a; box-shadow:0 4px 20px rgba(46,125,50,.1); }
    .animal-emoji { font-size:40px; margin-bottom:8px; }
    .animal-card h4 { font-size:15px; font-weight:600; color:#1a1a2e; }
    .animal-card p { font-size:12px; color:#6a7a8a; margin-top:4px; }
    .animal-stat { font-size:20px; font-weight:700; color:#2e7d32; margin-top:8px; }

    .ad-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:18px; }
    .ad-card { 
      background:#ffffff; border:1px solid #e8ecf1; border-radius:var(--radius); 
      overflow:hidden; box-shadow:var(--shadow); transition:all .2s;
    }
    .ad-card:hover { transform:translateY(-2px); box-shadow:0 4px 20px rgba(0,0,0,.08); }
    .ad-banner { height:120px; display:flex; align-items:center; justify-content:center; font-size:36px; background:linear-gradient(135deg, #e8f5e9, #c8e6c9); }
    .ad-info { padding:14px; }
    .ad-info h4 { font-size:14px; font-weight:600; color:#1a1a2e; margin-bottom:4px; }
    .ad-info p { font-size:12px; color:#6a7a8a; margin-bottom:10px; }
    .ad-stats { display:flex; gap:16px; flex-wrap:wrap; }
    .ad-stats span { font-size:12px; color:#4a5a6a; }
    .ad-stats span i { margin-right:4px; }

    .gestation-item { 
      display:flex; align-items:center; gap:14px; padding:14px; 
      background:#f8f9fa; border-radius:10px; margin-bottom:12px; border:1px solid #e8ecf1; 
    }
    .gestation-icon { font-size:32px; flex-shrink:0; }
    .gestation-body { flex:1; }
    .gestation-body h4 { font-size:14px; font-weight:600; color:#1a1a2e; }
    .gestation-body p { font-size:12px; color:#6a7a8a; margin-top:2px; }
    .progress-bar { background:#e8ecf1; border-radius:20px; height:6px; margin-top:8px; }
    .progress-fill { height:100%; border-radius:20px; background:linear-gradient(90deg, var(--green-400), var(--green-600)); }
    .progress-pct { font-size:11px; color:#2e7d32; font-weight:600; margin-top:3px; }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.active { display: flex !important; }
    
    .modal-box {
        background: #ffffff;
        border-radius: 16px;
        width: 95%;
        max-width: 550px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 30px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.4);
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from { transform: translateY(-40px) scale(0.95); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e8ecf1;
    }
    .modal-header h3 { font-size: 20px; font-weight: 700; color: #1a1a2e; margin: 0; }
    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #6a7a8a;
        transition: all 0.2s;
        padding: 0 8px;
        line-height: 1;
    }
    .modal-close:hover { color: #dc3545; transform: rotate(90deg); }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }
    
    .form-group { margin-bottom: 16px; }
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 5px;
    }
    .form-group label .required { color: #dc3545; margin-left: 2px; }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8ecf1;
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
        background: #f8f9fa;
        color: #1a1a2e;
    }
    .form-control:focus {
        outline: none;
        border-color: #2e7d32;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(46,125,50,0.1);
    }
    
    .modal-footer {
        display: flex;
        gap: 10px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid #e8ecf1;
    }
    .modal-footer .btn { flex: 1; padding: 12px 20px; font-size: 14px; }
    .modal-footer .btn-primary {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-footer .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46,125,50,0.3);
    }
    .modal-footer .btn-primary:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
    .modal-footer .btn-outline {
        background: transparent;
        border: 2px solid #e8ecf1;
        color: #1a1a2e;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-footer .btn-outline:hover {
        border-color: #dc3545;
        color: #dc3545;
        background: #fff5f5;
    }

    /* Toast Styles */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 8px;
        color: #fff;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        z-index: 999999;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 400px;
    }
    .toast-success { background: #2e7d32; }
    .toast-error { background: #dc3545; }
    .toast-warning { background: #fd7e14; }
    .toast-info { background: #0d6efd; }
    @keyframes slideIn {
        from { transform: translateX(100px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @media(max-width:768px) {
      #sidebar { width:var(--sidebar-collapsed); }
      #topbar { left:var(--sidebar-collapsed); }
      #main-wrap { margin-left:var(--sidebar-collapsed); }
      .topbar-search { min-width:120px; }
      .stats-grid { grid-template-columns:1fr 1fr; }
      .charts-grid { grid-template-columns:1fr; }
    }
