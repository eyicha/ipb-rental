@extends('layouts.guest')
@section('title', 'Register')

@section('content')
<div class="container py-5" style="min-height:calc(100vh - 72px - 300px); display:flex; align-items:center; justify-content:center;">
  <div class="row w-100 justify-content-center">
    <div class="col-md-10 col-lg-8 col-xl-7">
      <div style="background:#fff; border-radius:24px; box-shadow:0 8px 40px rgba(46,65,86,0.10); overflow:hidden;">
        <div class="row g-0">
          <div class="col-md-5 d-none d-md-flex flex-column justify-content-center align-items-center p-5" style="background:linear-gradient(167deg, var(--ipb-navy) 0%, var(--ipb-slate) 100%);">
            <div style="font-size:28px; font-weight:800; color:#fff; margin-bottom:8px;">IPB<span style="color:var(--ipb-sky);">.</span>Rental</div>
            <p style="color:rgba(255,255,255,0.6); font-size:13px; text-align:center; line-height:1.7;">Bergabunglah dengan komunitas sewa-menyewa mahasiswa IPB.</p>
            <div style="margin-top:32px; display:flex; flex-direction:column; gap:10px; width:100%;">
              <div style="background:rgba(255,255,255,0.08); border-radius:10px; padding:10px 14px; display:flex; align-items:center; gap:10px;">
                <i class="mdi mdi-check-circle-outline" style="color:#a0d4b8; font-size:16px;"></i>
                <span style="color:rgba(255,255,255,0.8); font-size:12px;">Gratis mendaftar</span>
              </div>
              <div style="background:rgba(255,255,255,0.08); border-radius:10px; padding:10px 14px; display:flex; align-items:center; gap:10px;">
                <i class="mdi mdi-check-circle-outline" style="color:#a0d4b8; font-size:16px;"></i>
                <span style="color:rgba(255,255,255,0.8); font-size:12px;">Bisa sewa & sewakan</span>
              </div>
              <div style="background:rgba(255,255,255,0.08); border-radius:10px; padding:10px 14px; display:flex; align-items:center; gap:10px;">
                <i class="mdi mdi-check-circle-outline" style="color:#a0d4b8; font-size:16px;"></i>
                <span style="color:rgba(255,255,255,0.8); font-size:12px;">Chat langsung dengan pemilik</span>
              </div>
            </div>
          </div>
          <div class="col-md-7 p-5">
            <h4 style="font-weight:800; color:var(--ipb-navy); margin-bottom:4px;">Buat Akun</h4>
            <p style="color:#7a8fa0; font-size:14px; margin-bottom:24px;">Isi data di bawah untuk mendaftar</p>

            <form method="POST" action="{{ route('register') }}">
              @csrf
              <div class="mb-3">
                <label class="form-label-ipb">Nama Lengkap</label>
                <input type="text" name="name" class="form-control form-control-ipb @error('name') is-invalid @enderror"
                  placeholder="Nama sesuai KTM" value="{{ old('name') }}" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label-ipb">NIM</label>
                <input type="text" name="nim" class="form-control form-control-ipb @error('nim') is-invalid @enderror"
                  placeholder="Contoh: G1401211001" value="{{ old('nim') }}">
                @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label-ipb">Email</label>
                <input type="email" name="email" class="form-control form-control-ipb @error('email') is-invalid @enderror"
                  placeholder="email@students.ipb.ac.id" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label-ipb">WhatsApp</label>
                <input type="text" name="whatsapp" class="form-control form-control-ipb @error('whatsapp') is-invalid @enderror"
                  placeholder="08xxxxxxxxxx" value="{{ old('whatsapp') }}">
                @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="mb-3">
                <label class="form-label-ipb">Password</label>
                <input type="password" name="password" class="form-control form-control-ipb @error('password') is-invalid @enderror"
                  placeholder="Min. 8 karakter" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="mb-4">
                <label class="form-label-ipb">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control form-control-ipb"
                  placeholder="Ulangi password" required>
              </div>
              <button type="submit" class="btn btn-navy w-100 py-3" style="border-radius:12px; font-weight:700; font-size:15px;">
                Daftar Sekarang <i class="mdi mdi-arrow-right ms-1"></i>
              </button>
            </form>

            <p class="text-center mt-4" style="font-size:14px; color:#7a8fa0;">
              Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--ipb-slate); font-weight:600; text-decoration:none;">Masuk</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
