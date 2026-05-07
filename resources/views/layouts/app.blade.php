<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard') — IPB Rental</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=DM+Sans:wght@300;400;500;700&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>:root{--font-display:'Cormorant Garamond','Times New Roman',serif;--font-body:'DM Sans','Manrope',sans-serif;}</style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>
<body style="background:#f7f7f5;">

  <!-- ── App Navbar ── -->
  <nav class="navbar navbar-expand-lg sticky-top app-navbar px-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('welcome') }}">IPB<span>.</span>Rental</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="appNav">
        <ul class="navbar-nav mx-auto gap-1">
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('explore') ? 'active' : '' }}" href="{{ route('explore') }}">Explore</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('my-items.*') ? 'active' : '' }}" href="{{ route('my-items.index') }}">My Items</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('rentals.*') ? 'active' : '' }}" href="{{ route('rentals.index') }}">Rentals</a></li>
          <li class="nav-item">
            @php
              $unreadCount = auth()->check()
                ? \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count()
                : 0;
            @endphp
            <a class="nav-link position-relative {{ request()->routeIs('chat.*') ? 'active' : '' }}" href="{{ route('chat.index') }}">
              Chat
              @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;">{{ $unreadCount }}</span>
              @endif
            </a>
          </li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('report.*') ? 'active' : '' }}" href="{{ route('report.index') }}">Report</a></li>
        </ul>

        <div class="d-flex align-items-center gap-3">
  <img src="{{ auth()->user()->avatarUrl }}" 
       style="width:36px; height:36px; border-radius:10px; object-fit:cover; border:2px solid rgba(46,65,86,0.1);" 
       alt="{{ auth()->user()->initials }}">
  <span style="font-size:14px; color:#7a8fa0;">{{ auth()->user()->name }}</span>
          <div class="dropdown">
            <button class="btn btn-sm p-0 border-0 bg-transparent" data-bs-toggle="dropdown" style="color:#7a8fa0;">
              <i class="mdi mdi-chevron-down" style="font-size:18px;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px; min-width:180px;">
              <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.index') }}"><i class="mdi mdi-account-outline"></i> Profil</a></li>
              @if(auth()->user()->isAdmin())
              <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}"><i class="mdi mdi-shield-crown-outline"></i> Admin Panel</a></li>
              @endif
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
    </div>
  </nav>

  @yield('content')

  <!-- Tambahkan Snap.js di head -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
async function bayar() {
    const res = await fetch('/api/checkout', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' },
        body: JSON.stringify({ item_id: '...', gross_amount: 50000, item_name: 'Sewa Kamera' })
    });

    const data = await res.json();

    // Buka popup Snap Midtrans
    window.snap.pay(data.snap_token, {
        onSuccess: (result) => { console.log('Berhasil', result); },
        onPending: (result) => { console.log('Pending', result); },
        onError:   (result) => { console.log('Error', result); },
        onClose:   ()       => { console.log('Popup ditutup'); }
    });
}
</script>
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
