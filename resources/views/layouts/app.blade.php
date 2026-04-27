<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Municipal Building Permit Repository System' }}</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        :root { --card:#fff; --ink:#17302a; --muted:#60756f; --line:#d7e1dd; --brand:#1f6f5f; --brand-soft:#d8ebe4; --warn:#d97706; --danger:#b91c1c; }
        * { box-sizing:border-box; }
        [x-cloak] { display:none !important; }
        body { margin:0; font-family:'Poppins', sans-serif; background:linear-gradient(180deg,#f8fbf9,#eef3f0); color:var(--ink); }
        a { color:inherit; text-decoration:none; }
        .shell { display:grid; grid-template-columns:320px 1fr; min-height:100vh; }
        .sidebar { position:sticky; top:0; height:100vh; overflow-y:auto; background:#163932; color:#eef7f4; padding:28px 22px; display:flex; flex-direction:column; }
        .brand { display:flex; flex-direction:column; align-items:flex-start; gap:14px; font-size:1.55rem; font-weight:700; line-height:1.25; margin-bottom:22px; }
        .brand-title { display:block; }
        .brand-subtitle { display:block; margin-top:10px; font-size:.88rem; font-weight:600; letter-spacing:.08em; text-transform:uppercase; color:rgba(238,247,244,.78); }
        .brand-logo { width:74px; height:74px; object-fit:cover; border-radius:16px; }
        .brand-logo.centered { align-self:center; }
        .sidebar-divider { display:block; width:100%; height:1px; background:rgba(255,255,255,.72); margin:0 0 24px; }
        .nav-link { display:block; padding:12px 14px; border-radius:12px; margin-bottom:8px; color:#d6e6e1; }
        .nav-link:hover,.nav-link.active { background:rgba(255,255,255,.1); color:#fff; }
        .main { padding:28px; }
        .topbar { display:grid; grid-template-columns:minmax(0, 1fr) auto; gap:16px; align-items:center; min-height:58px; margin-bottom:24px; }
        .topbar-heading { min-width:0; display:grid; gap:4px; align-content:center; }
        .topbar-subtitle { min-height:22px; font-size:.98rem; line-height:1.4; }
        .topbar-actions { display:flex; align-items:center; justify-content:flex-end; gap:10px; margin-left:auto; min-width:max-content; }
        .user-dropdown { position:relative; display:flex; justify-content:flex-end; flex:0 0 auto; }
        .user-dropdown-trigger { display:inline-flex; align-items:center; gap:8px; width:auto; min-height:42px; padding:7px 11px 7px 7px; border:1px solid var(--line); border-radius:10px; background:#fff; color:var(--ink); cursor:pointer; box-shadow:0 8px 18px rgba(17,24,39,.04); font-weight:600; white-space:nowrap; }
        .user-avatar { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; overflow:hidden; border-radius:50%; background:#eef3f0; color:var(--muted); font-size:.78rem; font-weight:700; text-transform:uppercase; }
        .user-avatar img { width:100%; height:100%; object-fit:cover; display:block; }
        .user-avatar-fallback { display:inline-flex; align-items:center; justify-content:center; width:100%; height:100%; }
        .user-dropdown-arrow { width:16px; height:16px; transition:transform .18s ease; color:var(--muted); }
        .user-dropdown-arrow.open { transform:rotate(180deg); }
        .user-dropdown-menu { position:absolute; right:0; top:calc(100% + 8px); width:210px; padding:8px; border:1px solid var(--line); border-radius:10px; background:#fff; box-shadow:0 18px 50px rgba(17,24,39,.16); z-index:1000; transform-origin:top right; }
        .user-dropdown-item { display:flex; width:100%; align-items:center; padding:10px 11px; border:0; border-radius:8px; background:transparent; color:var(--ink); text-align:left; cursor:pointer; font:inherit; font-size:.94rem; }
        .user-dropdown-item:hover { background:#eef6f3; }
        .user-dropdown-item.danger { color:var(--danger); }
        .notification-dropdown { position:relative; flex:0 0 auto; }
        .notification-trigger { position:relative; display:inline-flex; align-items:center; justify-content:center; width:42px; height:42px; padding:0; border:1px solid var(--line); border-radius:10px; background:#fff; color:var(--muted); cursor:pointer; box-shadow:0 8px 18px rgba(17,24,39,.04); }
        .notification-trigger:hover { color:var(--brand); background:#f8fbf9; }
        .notification-trigger svg { width:24px; height:24px; }
        .notification-badge { position:absolute; top:5px; right:5px; min-width:16px; height:16px; padding:0 4px; display:inline-flex; align-items:center; justify-content:center; border-radius:999px; background:#dc2626; color:#fff; font-size:.62rem; font-weight:700; line-height:1; }
        .notification-menu { position:absolute; right:0; top:calc(100% + 8px); width:min(340px, calc(100vw - 36px)); max-height:430px; overflow:auto; padding:8px; border:1px solid var(--line); border-radius:12px; background:#fff; box-shadow:0 18px 50px rgba(17,24,39,.16); z-index:1000; transform-origin:top right; }
        .notification-head { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:9px 10px 10px; border-bottom:1px solid var(--line); }
        .notification-head div { display:grid; gap:2px; }
        .notification-head span { color:var(--muted); font-size:.8rem; }
        .notification-clear { width:auto; min-height:30px; padding:6px 10px; border:0; border-radius:8px; background:var(--brand-soft); color:var(--brand); cursor:pointer; font-size:.8rem; font-weight:700; }
        .notification-clear:hover { background:#c7e1d8; }
        .notification-item { display:grid; grid-template-columns:8px 1fr; gap:10px; padding:10px; border-radius:9px; }
        .notification-item:hover { background:#eef6f3; }
        .notification-item strong { display:block; font-size:.88rem; }
        .notification-item small { display:block; color:var(--muted); font-size:.78rem; line-height:1.35; margin-top:2px; }
        .notification-item em { display:block; color:#8a9995; font-size:.72rem; font-style:normal; margin-top:4px; }
        .notification-dot { width:8px; height:8px; margin-top:5px; border-radius:999px; background:var(--brand); }
        .notification-empty { padding:18px 10px; color:var(--muted); text-align:center; font-size:.9rem; }
        .dropdown-enter { transition:opacity .16s ease, transform .16s ease; }
        .dropdown-enter-start { opacity:0; transform:translateY(-4px) scale(.98); }
        .dropdown-enter-end { opacity:1; transform:translateY(0) scale(1); }
        .dropdown-leave { transition:opacity .12s ease, transform .12s ease; }
        .dropdown-leave-start { opacity:1; transform:translateY(0) scale(1); }
        .dropdown-leave-end { opacity:0; transform:translateY(-4px) scale(.98); }
        .page-title { font-size:1.7rem; line-height:1.2; margin:0; min-height:33px; }
        .card .page-actions { display:grid !important; grid-template-columns:minmax(0, 1fr) auto; align-items:start !important; gap:10px 16px; }
        .page-actions > div:first-child { min-width:0; }
        .page-actions > .btn, .page-actions > .stack, .page-actions > .header-actions { justify-self:end; align-self:start; margin-right:0; }
        .page-actions > .card-search { grid-column:1 / -1; }
        .muted { color:var(--muted); }
        .grid { display:grid; gap:20px; }
        .grid.cols-2 { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .grid.cols-3 { grid-template-columns:repeat(3,minmax(0,1fr)); }
        .grid.cols-4 { grid-template-columns:repeat(4,minmax(0,1fr)); }
        .card { background:var(--card); border:1px solid var(--line); border-radius:18px; padding:20px; box-shadow:0 12px 30px rgba(17,24,39,.05); }
        .hero { background:radial-gradient(circle at top right,#eef9f3,#d9e9e0 45%,#fff); }
        .stat { font-size:2rem; font-weight:700; margin-top:10px; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:.96rem; }
        th,td { padding:12px 10px; border-bottom:1px solid var(--line); text-align:left; vertical-align:top; }
        th { font-size:.82rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); }
        .form-grid { display:grid; gap:14px; grid-template-columns:repeat(2,minmax(0,1fr)); }
        .form-grid.full { grid-template-columns:1fr; }
        label { display:block; font-size:.92rem; margin-bottom:6px; color:var(--muted); }
        input,select,textarea,button { width:100%; border-radius:12px; border:1px solid var(--line); padding:11px 12px; font:inherit; }
        textarea { min-height:92px; resize:vertical; }
        .btn { background:var(--brand); color:#fff; border:none; cursor:pointer; }
        .btn.secondary { background:var(--brand-soft); color:var(--ink); }
        .btn.warn { background:#fff7ed; color:var(--warn); border:1px solid #fdba74; }
        .btn.danger { background:#fef2f2; color:var(--danger); border:1px solid #fca5a5; }
        .btn.small { padding:8px 10px; font-size:.88rem; }
        .stack { display:flex; gap:10px; flex-wrap:wrap; }
        .header-actions { display:flex; justify-content:flex-end; gap:10px; align-items:center; }
        .trash-header-btn { display:inline-flex; align-items:center; justify-content:center; width:46px; height:46px; min-width:46px; padding:0; border-radius:10px; }
        .trash-header-btn svg { width:18px; height:18px; }
        .trash-actions { display:flex; gap:6px; align-items:center; flex-wrap:nowrap; }
        .trash-actions .btn { width:auto; }
        .trash-modal-card { width:min(780px, 100%); }
        .trash-modal-card .modal-head { padding:18px 20px; }
        .trash-modal-card .modal-body { padding:16px 20px 20px; }
        .trash-modal-card table { font-size:.9rem; }
        .trash-modal-card th, .trash-modal-card td { padding:9px 10px; vertical-align:middle; }
        .trash-modal-card .badge { padding:5px 9px; font-size:.76rem; }
        .trash-icon-btn { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; min-width:34px; padding:0; border-radius:8px; }
        .trash-icon-btn svg { width:16px; height:16px; }
        .card-search { display:grid; grid-template-columns:minmax(240px, 1fr) auto; align-items:center; gap:8px; width:100%; margin-top:0; }
        .card-search input { min-height:40px; padding:8px 11px; border-radius:10px; font-size:.92rem; }
        .card-search .btn, .card-search a { display:inline-flex; align-items:center; justify-content:center; width:auto; min-height:40px; padding:8px 12px; border-radius:10px; white-space:nowrap; font-size:.9rem; }
        .card-search-actions { display:flex; justify-content:flex-end; gap:8px; justify-self:end; }
        .badge { display:inline-flex; padding:6px 10px; border-radius:999px; font-size:.82rem; font-weight:700; }
        .badge.Pending { background:#fef3c7; color:#92400e; }
        .badge.Approved { background:#dcfce7; color:#166534; }
        .badge.Rejected { background:#fee2e2; color:#991b1b; }
        .badge.Returned { background:#ffedd5; color:#9a3412; }
        .badge.Expired { background:#dbeafe; color:#1d4ed8; }
        nav[role="navigation"][aria-label*="Pagination"] { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-top:16px; color:var(--muted); font-size:.88rem; }
        nav[role="navigation"][aria-label*="Pagination"] > div:first-child { display:none; }
        nav[role="navigation"][aria-label*="Pagination"] > div:last-child { display:flex; align-items:center; justify-content:space-between; gap:12px; width:100%; }
        nav[role="navigation"][aria-label*="Pagination"] p { margin:0; }
        nav[role="navigation"][aria-label*="Pagination"] span[aria-current="page"] span,
        nav[role="navigation"][aria-label*="Pagination"] a,
        nav[role="navigation"][aria-label*="Pagination"] span[aria-disabled="true"] span { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border:1px solid var(--line); background:#fff; color:var(--ink); font-size:.86rem; line-height:1; }
        nav[role="navigation"][aria-label*="Pagination"] a:hover { background:#eef6f3; color:var(--brand); }
        nav[role="navigation"][aria-label*="Pagination"] span[aria-current="page"] span { background:var(--brand); color:#fff; border-color:var(--brand); }
        nav[role="navigation"][aria-label*="Pagination"] span[aria-disabled="true"] span { color:#9aa8a3; background:#f8fbf9; }
        nav[role="navigation"][aria-label*="Pagination"] svg { width:16px !important; height:16px !important; max-width:16px; max-height:16px; display:block; flex:0 0 16px; }
        .alert { padding:14px 16px; border-radius:14px; margin-bottom:18px; }
        .alert.success { background:#ecfdf5; color:#166534; border:1px solid #86efac; }
        .alert.error { background:#fef2f2; color:#991b1b; border:1px solid #fca5a5; }
        .login-shell { min-height:100vh; display:grid; place-items:center; padding:32px; background:linear-gradient(135deg,#0d2b25,#2f6e5f 52%,#f0f5f1 52%); }
        .login-card { width:min(980px,100%); display:grid; grid-template-columns:1.1fr .9fr; background:#fff; border-radius:28px; overflow:hidden; box-shadow:0 30px 80px rgba(0,0,0,.18); }
        .login-art { background:linear-gradient(180deg,#173932,#28584e); color:#e9f7f2; padding:42px; }
        .login-art-divider { width:100%; height:1px; background:rgba(255,255,255,.78); }
        .login-form { padding:42px; }
        .landing-shell { min-height:100vh; background:
            radial-gradient(circle at top left, rgba(255,214,102,.20), transparent 26%),
            radial-gradient(circle at 85% 22%, rgba(87,169,136,.20), transparent 28%),
            linear-gradient(155deg,#0d2b25 0%,#143e35 38%,#f4efe4 38.5%,#f8f5ed 100%);
            color:#17302a;
        }
        .landing-nav { display:flex; align-items:center; justify-content:space-between; gap:20px; padding:24px 32px 0; max-width:1220px; margin:0 auto; }
        .landing-brand { display:flex; align-items:center; gap:14px; color:#f5fbf8; }
        .landing-brand-badge { width:58px; height:58px; border-radius:18px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.22); display:grid; place-items:center; overflow:hidden; backdrop-filter:blur(10px); }
        .landing-brand-badge img { width:100%; height:100%; object-fit:cover; }
        .landing-brand-copy strong { display:block; font-size:1rem; }
        .landing-brand-copy span { display:block; margin-top:4px; font-size:.78rem; letter-spacing:.08em; text-transform:uppercase; color:rgba(245,251,248,.72); }
        .landing-nav-actions { display:flex; gap:12px; align-items:center; }
        .landing-nav-link { width:auto; padding:11px 16px; border-radius:999px; border:1px solid rgba(255,255,255,.22); color:#f5fbf8; background:rgba(255,255,255,.05); backdrop-filter:blur(12px); }
        .landing-nav-link.primary { background:#f6c453; border-color:#f6c453; color:#17302a; font-weight:700; }
        .landing-hero { max-width:1220px; margin:0 auto; padding:36px 32px 48px; display:grid; grid-template-columns:minmax(0,1.2fr) minmax(320px,.8fr); gap:34px; align-items:center; }
        .landing-copy { color:#f5fbf8; }
        .landing-eyebrow { display:inline-flex; padding:8px 14px; border-radius:999px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16); font-size:.8rem; letter-spacing:.08em; text-transform:uppercase; }
        .landing-copy h1 { margin:20px 0 16px; font-size:clamp(2.8rem, 6vw, 5.2rem); line-height:1; letter-spacing:-.04em; max-width:11ch; }
        .landing-copy p { max-width:620px; margin:0; font-size:1.05rem; line-height:1.9; color:rgba(245,251,248,.82); }
        .landing-actions { display:flex; gap:14px; flex-wrap:wrap; margin-top:28px; }
        .landing-actions .btn { width:auto; min-width:180px; border-radius:999px; padding:14px 20px; box-shadow:0 16px 30px rgba(0,0,0,.18); }
        .landing-actions .btn.secondary { background:rgba(255,255,255,.08); color:#f5fbf8; border:1px solid rgba(255,255,255,.16); }
        .landing-panel { position:relative; overflow:hidden; background:rgba(255,255,255,.92); border:1px solid rgba(23,48,42,.08); border-radius:32px; padding:28px; box-shadow:0 24px 60px rgba(11,26,24,.18); }
        .landing-panel::before { content:""; position:absolute; inset:auto -40px -40px auto; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle, rgba(246,196,83,.28), transparent 65%); }
        .landing-panel-grid { display:grid; gap:14px; }
        .landing-panel-kicker { font-size:.76rem; letter-spacing:.12em; text-transform:uppercase; color:#60756f; }
        .landing-panel-title { margin:8px 0 6px; font-size:1.6rem; line-height:1.2; }
        .landing-panel-copy { margin:0; color:#526761; line-height:1.75; }
        .landing-stat-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; margin-top:10px; }
        .landing-stat { padding:16px; border-radius:20px; background:#f6fbf8; border:1px solid #d8e6df; }
        .landing-stat strong { display:block; font-size:1.5rem; color:#17302a; }
        .landing-stat span { display:block; margin-top:4px; color:#60756f; font-size:.88rem; line-height:1.5; }
        .landing-sections { max-width:1220px; margin:0 auto; padding:0 32px 56px; display:grid; gap:24px; }
        .landing-band { background:#fff; border:1px solid #d7e1dd; border-radius:28px; padding:28px; box-shadow:0 16px 34px rgba(17,24,39,.06); }
        .landing-band-header { display:flex; align-items:end; justify-content:space-between; gap:18px; margin-bottom:22px; }
        .landing-band-header h2 { margin:0; font-size:1.7rem; }
        .landing-band-header p { margin:8px 0 0; color:#60756f; max-width:720px; line-height:1.7; }
        .landing-feature-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; }
        .landing-feature { padding:22px; border-radius:22px; background:linear-gradient(180deg,#f8fbf9,#edf5f1); border:1px solid #d7e1dd; }
        .landing-feature-mark { width:44px; height:44px; border-radius:14px; display:grid; place-items:center; margin-bottom:16px; background:#173932; color:#f6c453; font-weight:700; }
        .landing-feature h3 { margin:0 0 10px; font-size:1.08rem; }
        .landing-feature p { margin:0; color:#60756f; line-height:1.7; font-size:.94rem; }
        .landing-flow { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; counter-reset:step; }
        .landing-flow-step { position:relative; padding:22px 22px 22px 70px; border-radius:22px; background:#173932; color:#f5fbf8; overflow:hidden; }
        .landing-flow-step::before { counter-increment:step; content:counter(step); position:absolute; left:20px; top:18px; width:34px; height:34px; border-radius:50%; display:grid; place-items:center; background:#f6c453; color:#17302a; font-weight:700; }
        .landing-flow-step h3 { margin:0 0 10px; font-size:1.04rem; }
        .landing-flow-step p { margin:0; color:rgba(245,251,248,.8); line-height:1.7; font-size:.92rem; }
        .landing-footer { max-width:1220px; margin:0 auto; padding:0 32px 36px; color:#60756f; font-size:.9rem; }
        .global-modal-backdrop { position:fixed; inset:0; background:rgba(8, 20, 18, .55); display:none; align-items:center; justify-content:center; padding:24px; z-index:2000; }
        .global-modal-backdrop.open { display:flex; }
        .global-modal-card { width:min(420px, 100%); background:#fff; border-radius:22px; border:1px solid var(--line); box-shadow:0 24px 80px rgba(0,0,0,.18); padding:24px; }
        .global-modal-title { margin:0 0 8px; font-size:1.2rem; }
        .global-modal-text { margin:0 0 18px; color:var(--muted); line-height:1.5; }
        .delete-modal-actions, .save-modal-actions { justify-content:flex-end; }
        .delete-modal-actions .btn, .save-modal-actions .btn { width:auto; }
        .app-footer { margin-top:auto; border-top:1px solid rgba(255,255,255,.2); color:#d6e6e1; padding:18px 0 0; }
        .app-footer-inner { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px; margin:0 auto; text-align:center; font-size:.78rem; line-height:1.5; }
        .app-footer p { margin:0; font-weight:500; }
        @media (max-width:920px){ .shell,.login-card,.grid.cols-2,.grid.cols-3,.grid.cols-4,.form-grid,.landing-hero,.landing-feature-grid,.landing-flow{ grid-template-columns:1fr; } .sidebar{ position:relative; height:auto; min-height:0; overflow:visible; } .main{ padding:18px; } .topbar{ align-items:start; } .landing-nav,.landing-hero,.landing-sections,.landing-footer{ padding-left:20px; padding-right:20px; } .landing-nav{ align-items:flex-start; flex-direction:column; } .landing-band-header{ align-items:start; flex-direction:column; } }
        @media (max-width:640px){ .card-search { grid-template-columns:1fr; } .card-search-actions { width:100%; } .card-search .btn, .card-search a { width:100%; } nav[role="navigation"][aria-label*="Pagination"] > div:last-child { justify-content:center; } nav[role="navigation"][aria-label*="Pagination"] p { display:none; } }
    </style>
</head>
<body>
@php
    $systemSettings = \App\Models\SystemSetting::current();
    $systemLogoUrl = $systemSettings->system_logo_path
        ? asset('storage/' . $systemSettings->system_logo_path).'?v='.$systemSettings->updated_at?->timestamp
        : null;
@endphp
@if (!empty($loginPage) || !empty($landingPage))
    @yield('content')
@else
    <div class="shell">
        <aside class="sidebar">
            <div class="brand">
                @if($systemLogoUrl)
                    <img class="brand-logo centered" src="{{ $systemLogoUrl }}" alt="">
                @endif
                <span class="brand-title">
                    {{ $systemSettings->system_name }}
                    <span class="brand-subtitle">{{ $systemSettings->system_subheader ?? 'Municipality of Lebak' }}</span>
                </span>
            </div>
            <div class="sidebar-divider" aria-hidden="true"></div>
            @php($links = [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'module' => 'dashboard'],
                ['label' => 'Building Permit', 'route' => 'building-permits.index', 'module' => 'building-permits'],
                ['label' => 'Building Type', 'route' => 'building-types.index', 'module' => 'building-types'],
                ['label' => 'Building Category', 'route' => 'building-categories.index', 'module' => 'building-categories'],
                ['label' => 'Permit Approval', 'route' => 'permit-approvals.index', 'module' => 'permit-approvals'],
                ['label' => 'Reports', 'route' => 'reports.index', 'module' => 'reports'],
                ['label' => 'Audit Log', 'route' => 'audit-logs.index', 'module' => 'audit-logs'],
                ['label' => 'User Management', 'route' => 'users.index', 'module' => 'users'],
            ])
            @foreach ($links as $link)
                @if (auth()->user()->canAccess($link['module']))
                    <a class="nav-link {{ request()->routeIs($link['route']) ? 'active' : '' }}" href="{{ route($link['route']) }}">{{ $link['label'] }}</a>
                @endif
            @endforeach
            <x-app-footer />
        </aside>
        <main class="main">
            <div class="topbar">
                <div class="topbar-heading">
                    <h1 class="page-title">{{ $title ?? 'Module' }}</h1>
                    <div class="muted topbar-subtitle">{{ $subtitle ?? '' }}</div>
                </div>
                <div class="topbar-actions">
                    <x-notification-dropdown />
                    <x-user-dropdown />
                </div>
            </div>
            @if (session('success'))<div class="alert success">{{ session('success') }}</div>@endif
            @if (session('error'))<div class="alert error">{{ session('error') }}</div>@endif
            @if ($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
            @yield('content')
        </main>
    </div>

    <div class="global-modal-backdrop" id="delete-confirmation-modal" aria-hidden="true">
        <div class="global-modal-card" role="dialog" aria-modal="true" aria-labelledby="delete-confirmation-title">
            <h3 class="global-modal-title" id="delete-confirmation-title">Confirm Delete</h3>
            <p class="global-modal-text" id="delete-confirmation-message">Are you sure you want to delete this record?</p>
            <div class="stack delete-modal-actions">
                <button class="btn danger" id="delete-confirmation-submit" type="button">Delete</button>
                <button class="btn secondary" id="delete-confirmation-cancel" type="button">Cancel</button>
            </div>
        </div>
    </div>
    <div class="global-modal-backdrop" id="save-confirmation-modal" aria-hidden="true">
        <div class="global-modal-card" role="dialog" aria-modal="true" aria-labelledby="save-confirmation-title">
            <h3 class="global-modal-title" id="save-confirmation-title">Save Changes</h3>
            <p class="global-modal-text" id="save-confirmation-message">Save these changes?</p>
            <div class="stack save-modal-actions">
                <button class="btn" id="save-confirmation-submit" type="button">Save</button>
                <button class="btn secondary" id="save-confirmation-cancel" type="button">Cancel</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('delete-confirmation-modal');
            const deleteMessage = document.getElementById('delete-confirmation-message');
            const deleteSubmit = document.getElementById('delete-confirmation-submit');
            const deleteCancel = document.getElementById('delete-confirmation-cancel');
            const saveModal = document.getElementById('save-confirmation-modal');
            const saveTitle = document.getElementById('save-confirmation-title');
            const saveMessage = document.getElementById('save-confirmation-message');
            const saveSubmit = document.getElementById('save-confirmation-submit');
            const saveCancel = document.getElementById('save-confirmation-cancel');
            let pendingDeleteForm = null;
            let pendingSaveForm = null;
            const defaultSaveTitle = 'Save Changes';
            const defaultSaveMessage = 'Save these changes?';
            const defaultSaveLabel = 'Save';

            if (!deleteModal || !deleteMessage || !deleteSubmit || !deleteCancel || !saveModal || !saveTitle || !saveMessage || !saveSubmit || !saveCancel) {
                return;
            }

            const closeDeleteModal = () => {
                deleteModal.classList.remove('open');
                deleteModal.setAttribute('aria-hidden', 'true');
                pendingDeleteForm = null;
            };

            const closeSaveModal = () => {
                saveModal.classList.remove('open');
                saveModal.setAttribute('aria-hidden', 'true');
                saveTitle.textContent = defaultSaveTitle;
                saveMessage.textContent = defaultSaveMessage;
                saveSubmit.textContent = defaultSaveLabel;
                pendingSaveForm = null;
            };

            document.addEventListener('submit', function (event) {
                const form = event.target;

                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (form.hasAttribute('data-confirm-delete') && form.dataset.deleteConfirmed !== 'true') {
                    event.preventDefault();
                    pendingDeleteForm = form;
                    deleteMessage.textContent = form.getAttribute('data-confirm-message') || 'Are you sure you want to delete this record?';
                    deleteModal.classList.add('open');
                    deleteModal.setAttribute('aria-hidden', 'false');
                }

                if (form.hasAttribute('data-confirm-save') && form.dataset.saveConfirmed !== 'true') {
                    event.preventDefault();
                    pendingSaveForm = form;
                    saveTitle.textContent = form.getAttribute('data-save-title') || defaultSaveTitle;
                    saveMessage.textContent = form.getAttribute('data-save-message') || defaultSaveMessage;
                    saveSubmit.textContent = form.getAttribute('data-save-confirm-label') || defaultSaveLabel;
                    saveModal.classList.add('open');
                    saveModal.setAttribute('aria-hidden', 'false');
                }
            }, true);

            deleteSubmit.addEventListener('click', function () {
                if (!pendingDeleteForm) {
                    return;
                }

                pendingDeleteForm.dataset.deleteConfirmed = 'true';
                pendingDeleteForm.submit();
            });

            saveSubmit.addEventListener('click', function () {
                if (!pendingSaveForm) {
                    return;
                }

                pendingSaveForm.dataset.saveConfirmed = 'true';
                pendingSaveForm.submit();
            });

            deleteCancel.addEventListener('click', closeDeleteModal);
            saveCancel.addEventListener('click', closeSaveModal);

            deleteModal.addEventListener('click', function (event) {
                if (event.target === deleteModal) {
                    closeDeleteModal();
                }
            });

            saveModal.addEventListener('click', function (event) {
                if (event.target === saveModal) {
                    closeSaveModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && deleteModal.classList.contains('open')) {
                    closeDeleteModal();
                }

                if (event.key === 'Escape' && saveModal.classList.contains('open')) {
                    closeSaveModal();
                }
            });
        });
    </script>
@endif
</body>
</html>

