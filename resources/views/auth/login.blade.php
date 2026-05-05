<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login — IPB Rental</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    html, body { height: 100%; margin: 0; padding: 0; }
    body { display: flex; min-height: 100vh; background: var(--ipb-navy); }

    /* ── Left visual panel ── */
    .login-visual {
      flex: 1;
      position: relative;
      overflow: hidden;
      background: linear-gradient(160deg, #3a5a72 0%, #2e4156 100%);
    }
    .login-visual__img {
      position: absolute; inset: 0;
      width: 100%; height: 100%; object-fit: cover; opacity: 0.85;
    }
    .login-visual__overlay {
      position: absolute; inset: 0;
      background: linear-gradient(180deg, rgba(46,65,86,0.2) 0%, rgba(46,65,86,0.5) 100%);
    }
    .login-visual__brand {
      position: absolute; top: 40px; left: 44px;
      font-family: 'Cormorant Garamond', serif; font-weight: 600;
      font-size: 22px; letter-spacing: 0.5px; color: #fff;
      text-shadow: 0 1px 4px rgba(0,0,0,0.3); text-decoration: none;
    }
    .login-visual__brand span { color: #a8c8d8; }
    .login-visual__tagline {
      position: absolute; bottom: 48px; left: 44px; right: 44px;
    }
    .login-visual__tagline h2 {
      font-family: 'Cormorant Garamond', serif; font-weight: 600;
      font-size: 44px; line-height: 1.1; color: #fff; margin-bottom: 12px;
    }
    .login-visual__tagline p {
      font-family: 'DM Sans', sans-serif; font-size: 14px;
      color: rgba(255,255,255,0.65); line-height: 1.7;
    }

    /* ── Right form panel ── */
    .login-form-panel {
      width: 540px; flex-shrink: 0;
      background: var(--ipb-cream, #f5efeb);
      display: flex; align-items: center; justify-content: center;
      padding: 64px 52px; min-height: 100vh;
    }
    .login-form-inner { width: 100%; max-width: 440px; }

    .login-heading {
      font-family: 'Cormorant Garamond', serif; font-weight: 600;
      font-size: 56px; line-height: 1.05; letter-spacing: -0.5px;
      color: var(--ipb-navy, #2e4156); margin-bottom: 8px;
    }
    .login-subheading {
      font-family: 'DM Sans', sans-serif; font-size: 14px;
      color: #7a8fa0; margin-bottom: 36px;
    }

    .form-label-split {
      display: block;
      font-family: 'DM Sans', sans-serif; font-weight: 500;
      font-size: 11px; letter-spacing: 1.4px; text-transform: uppercase;
      color: var(--ipb-slate, #567c8d); margin-bottom: 6px;
    }
    .input-split-wrap {
      display: flex; align-items: center;
      background: #fff; border: 1px solid transparent;
      border-radius: 10px; overflow: hidden;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-split-wrap:focus-within {
      border-color: var(--ipb-sky, #c8d9e6);
      box-shadow: 0 0 0 3px rgba(86,124,141,0.12);
    }
    .input-split-wrap.is-invalid { border-color: #dc3545; }
    .form-input-split {
      flex: 1; padding: 15px 20px;
      font-family: 'DM Sans', sans-serif; font-size: 14px;
      color: var(--ipb-navy, #2e4156);
      background: transparent; border: none; outline: none;
    }
    .form-input-split::placeholder { color: rgba(86,124,141,0.4); }
    .input-icon-split {
      width: 44px; display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(86,124,141,0.5);
      background: none; border: none;
    }

    .btn-login-split {
      width: 100%; padding: 16px;
      background: var(--ipb-navy, #2e4156); color: #fff;
      border: none; border-radius: 10px;
      font-family: 'DM Sans', sans-serif; font-weight: 500;
      font-size: 14px; letter-spacing: 1.2px; cursor: pointer;
      transition: background 0.2s;
    }
    .btn-login-split:hover { background: #3a526a; }
    .btn-login-split:active { transform: scale(0.99); }

    .register-row {
      display: flex; align-items: center; justify-content: center;
      gap: 4px; padding-top: 28px;
    }
    .register-row__text {
      font-family: 'DM Sans', sans-serif; font-size: 13px; color: #7a8fa0;
    }
    .register-row__link {
      font-family: 'DM Sans', sans-serif; font-weight: 500; font-size: 13px;
      color: var(--ipb-navy, #2e4156);
      border-bottom: 1px solid var(--ipb-sky, #c8d9e6);
      padding-bottom: 1px; text-decoration: none;
    }
    .register-row__link:hover { color: var(--ipb-slate, #567c8d); }

    @media (max-width: 768px) {
      .login-visual { display: none; }
      .login-form-panel { width: 100%; }
    }
  </style>
</head>
<body>

  <!-- ── Left visual panel ── -->
  <div class="login-visual d-none d-md-block">
    <img src="{{ asset('img/login-bg.jpg') }}" alt="" class="login-visual__img" onerror="this.style.display='none'">
    <div class="login-visual__overlay"></div>
    <a href="{{ route('welcome') }}" class="login-visual__brand">IPB<span>.</span>Rental</a>
    <div class="login-visual__tagline">
      <h2>Rent With<br><em>Intention.</em></h2>
      <p>Platform sewa-menyewa terpercaya untuk<br>komunitas kampus IPB.</p>
    </div>
  </div>

  <!-- ── Right form panel ── -->
  <aside class="login-form-panel">
    <div class="login-form-inner">

      <h1 class="login-heading">Welcome<br>Back.</h1>
      <p class="login-subheading">Masuk ke akun IPB Rental kamu</p>

      @if(session('error'))
      <div style="background:rgba(192,118,106,0.1); border:1px solid rgba(192,118,106,0.25); color:#8a3a30; border-radius:10px; padding:12px 16px; font-family:'DM Sans',sans-serif; font-size:13px; margin-bottom:20px;">
        {{ session('error') }}
      </div>
      @endif
      @if($errors->any())
      <div style="background:rgba(192,118,106,0.1); border:1px solid rgba(192,118,106,0.25); color:#8a3a30; border-radius:10px; padding:12px 16px; font-family:'DM Sans',sans-serif; font-size:13px; margin-bottom:20px;">
        {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div style="margin-bottom:16px;">
          <label for="email" class="form-label-split">Email</label>
          <div class="input-split-wrap {{ $errors->has('email') ? 'is-invalid' : '' }}">
            <input id="email" name="email" type="email" class="form-input-split"
              placeholder="nama@apps.ipb.ac.id" value="{{ old('email') }}"
              autocomplete="email" required autofocus>
          </div>
          @error('email')
          <div style="font-family:'DM Sans',sans-serif; font-size:12px; color:#dc3545; margin-top:4px;">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div style="margin-bottom:0;">
          <label for="password" class="form-label-split">Password</label>
          <div class="input-split-wrap {{ $errors->has('password') ? 'is-invalid' : '' }}">
            <input id="password" name="password" type="password" class="form-input-split"
              placeholder="••••••••" autocomplete="current-password" required>
            <button type="button" class="input-icon-split" onclick="togglePwd()" aria-label="Toggle password">
              <i class="mdi mdi-eye-outline" id="eyeIcon" style="font-size:18px;"></i>
            </button>
          </div>
          @error('password')
          <div style="font-family:'DM Sans',sans-serif; font-size:12px; color:#dc3545; margin-top:4px;">{{ $message }}</div>
          @enderror
        </div>

        <!-- Forgot link -->
        <div style="display:flex; justify-content:flex-end; padding:8px 0 22px;">
          <a href="#" style="font-family:'DM Sans',sans-serif; font-size:12px; color:var(--ipb-slate); text-decoration:none; transition:opacity 0.2s;">
            Lupa password?
          </a>
        </div>

        <button type="submit" class="btn-login-split">Masuk</button>
      </form>

      <div class="register-row">
        <span class="register-row__text">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="register-row__link">Daftar di sini</a>
      </div>

    </div>
  </aside>

  <script>
  function togglePwd() {
    const inp = document.getElementById('password');
    const ico = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
      inp.type = 'text';
      ico.className = 'mdi mdi-eye-off-outline';
      ico.style.fontSize = '18px';
    } else {
      inp.type = 'password';
      ico.className = 'mdi mdi-eye-outline';
      ico.style.fontSize = '18px';
    }
  }
  </script>

</body>
</html>
