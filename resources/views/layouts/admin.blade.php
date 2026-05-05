<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin') — IPB Rental</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>
<body style="background:#f7f7f5;">

  <!-- ── Admin Navbar ── -->
  <nav class="navbar sticky-top app-navbar px-4" style="z-index:1030;">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('admin.dashboard') }}">IPB<span>.</span>Rental <span style="font-size:11px; font-weight:600; background:rgba(86,124,141,0.15); color:var(--ipb-slate); padding:3px 8px; border-radius:6px; margin-left:6px; letter-spacing:0.5px;">ADMIN</span></a>
      <div class="d-flex align-items-center gap-3 ms-auto">
        <div class="nav-avatar-chip" style="background:linear-gradient(145deg,#8a3a30,#c0766a);">{{ auth()->user()->initials }}</div>
        <span style="font-size:14px; color:#7a8fa0;">{{ auth()->user()->name }}</span>
        <div class="dropdown">
          <button class="btn btn-sm p-0 border-0 bg-transparent" data-bs-toggle="dropdown" style="color:#7a8fa0;">
            <i class="mdi mdi-chevron-down" style="font-size:18px;"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px; min-width:180px;">
            <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('my-items.index') }}"><i class="mdi mdi-view-dashboard-outline"></i> User Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item d-flex align-items-center gap-2 text-danger" type="submit"><i class="mdi mdi-logout"></i> Keluar</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <div class="d-flex">
    <!-- ── Sidebar ── -->
    <aside class="admin-sidebar">
      <div class="p-3 pb-0">
        <p class="sidebar-section" style="padding-left:0;">Navigasi</p>
      </div>
      <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="mdi mdi-view-dashboard-outline"></i> Dashboard
      </a>

      <div class="sidebar-section mt-2">Konten</div>
      <a href="{{ route('admin.items.index') }}" class="sidebar-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
        <i class="mdi mdi-package-variant-closed"></i> Kelola Item
      </a>
      <a href="{{ route('admin.rentals.index') }}" class="sidebar-link {{ request()->routeIs('admin.rentals.*') ? 'active' : '' }}">
        <i class="mdi mdi-handshake-outline"></i> Kelola Rental
      </a>

      <div class="sidebar-section mt-2">Pengguna</div>
      <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="mdi mdi-account-group-outline"></i> Kelola Pengguna
      </a>
      <a href="{{ route('admin.verifications.index') }}" class="sidebar-link {{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">
        <i class="mdi mdi-shield-check-outline"></i> Verifikasi
        @php $pendingVerif = \App\Models\Verification::where('status','pending')->count(); @endphp
        @if($pendingVerif > 0)
          <span class="badge bg-danger ms-auto" style="font-size:10px;">{{ $pendingVerif }}</span>
        @endif
      </a>

      <div class="sidebar-section mt-2">Lainnya</div>
      <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="mdi mdi-alert-circle-outline"></i> Laporan
        @php $pendingReports = \App\Models\Report::where('status','pending')->count(); @endphp
        @if($pendingReports > 0)
          <span class="badge bg-warning text-dark ms-auto" style="font-size:10px;">{{ $pendingReports }}</span>
        @endif
      </a>
    </aside>

    <!-- ── Main Content ── -->
    <main class="flex-1 p-4" style="min-width:0;">
      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

  @if(session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({ toast:true, position:'top-end', icon:'success', title: @json(session('success')), showConfirmButton:false, timer:3000, timerProgressBar:true });
    });
  </script>
  @endif
  @if(session('error'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({ toast:true, position:'top-end', icon:'error', title: @json(session('error')), showConfirmButton:false, timer:4000 });
    });
  </script>
  @endif

  @stack('scripts')
</body>
</html>
