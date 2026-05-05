<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'IPB Rental') — IPB Rental</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=DM+Sans:wght@300;400;500;700&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>:root{--font-display:'Cormorant Garamond','Times New Roman',serif;--font-body:'DM Sans','Manrope',sans-serif;}</style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>
<body style="background:#fff;">

  <!-- ── Navbar ── -->
  <nav class="navbar navbar-expand-lg sticky-top app-navbar px-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('welcome') }}">IPB<span>.</span>Rental</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="guestNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ route('welcome') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('explore') ? 'active' : '' }}" href="{{ route('explore') }}">Explore</a></li>
        </ul>
        <div class="d-flex gap-2">
          <a href="{{ route('login') }}" class="btn btn-sm btn-outline-navy px-4">Login</a>
          <a href="{{ route('register') }}" class="btn btn-sm btn-navy px-4">Register</a>
        </div>
      </div>
    </div>
  </nav>

  @yield('content')

  <!-- ── Footer ── -->
  <footer style="background:var(--ipb-navy); border-top:1px solid rgba(255,255,255,0.06); padding:64px 40px 40px;">
    <div style="max-width:1200px; margin:0 auto;">
      <div class="row g-5 mb-5">
        <div class="col-lg-5">
          <div style="font-family:var(--font-display,'Cormorant Garamond',serif); font-size:38px; font-weight:700; color:#fff; margin-bottom:16px;">IPB<span style="color:var(--ipb-slate);">.</span>Rental</div>
          <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; font-weight:700; line-height:1.8; color:#fff; max-width:300px;">
            Platform verified rental terpercaya untuk komunitas kampus.<br>
            Setiap transaksi terjamin. Setiap pengalaman terelevasi.
          </p>
        </div>
        <div class="col-6 col-lg-2">
          <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:10px; font-weight:500; letter-spacing:1.6px; text-transform:uppercase; color:#fff; margin-bottom:20px;">Platform</p>
          <ul class="list-unstyled" style="display:flex; flex-direction:column; gap:12px;">
            <li><a href="{{ route('explore') }}" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Explore Items</a></li>
            <li><a href="#" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Cara Kerja</a></li>
            <li><a href="{{ route('register') }}" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Daftarkan Item</a></li>
          </ul>
        </div>
        <div class="col-6 col-lg-2">
          <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:10px; font-weight:500; letter-spacing:1.6px; text-transform:uppercase; color:#fff; margin-bottom:20px;">Perusahaan</p>
          <ul class="list-unstyled" style="display:flex; flex-direction:column; gap:12px;">
            <li><a href="#" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Tentang Kami</a></li>
            <li><a href="#" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Blog</a></li>
            <li><a href="#" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Kontak</a></li>
          </ul>
        </div>
        <div class="col-6 col-lg-2">
          <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:10px; font-weight:500; letter-spacing:1.6px; text-transform:uppercase; color:#fff; margin-bottom:20px;">Legal</p>
          <ul class="list-unstyled" style="display:flex; flex-direction:column; gap:12px;">
            <li><a href="#" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Kebijakan Privasi</a></li>
            <li><a href="#" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Syarat Penggunaan</a></li>
            <li><a href="#" class="text-decoration-none" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:rgba(255,255,255,0.9);">Perjanjian Sewa</a></li>
          </ul>
        </div>
      </div>
      <hr style="border-color:rgba(255,255,255,0.06); margin:0 0 28px;">
      <div class="d-flex justify-content-between align-items-center" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:11px; letter-spacing:0.5px; color:rgba(255,255,255,0.2);">
        <span>© {{ date('Y') }} IPB Rental. All rights reserved.</span>
        <span>Designed with intention.</span>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

  @if(session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: @json(session('success')), showConfirmButton: false, timer: 3000, timerProgressBar: true });
    });
  </script>
  @endif
  @if(session('error'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: @json(session('error')), showConfirmButton: false, timer: 4000 });
    });
  </script>
  @endif

  @stack('scripts')
</body>
</html>
