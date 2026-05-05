@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
@php
  $userRating = auth()->user()->items()->where('rating_avg', '>', 0)->avg('rating_avg') ?? 0;
  $fotoVerif  = !is_null(auth()->user()->avatar);
  $ktmRecord  = auth()->user()->verifications()->where('tipe', 'ktm')->latest()->first();
  $ktmStatus  = optional($ktmRecord)->status; // null | 'pending' | 'verified' | 'rejected'
@endphp

<style>
/* ─── Profile Page ──────────────────────────────────────────── */

/* Hero */
.prof-hero {
  padding: 36px clamp(24px, 5vw, 80px) 40px;
  background: linear-gradient(174deg, #2e4156 0%, #3a5568 60%, #567c8d 100%);
  position: relative; overflow: hidden;
}
.prof-hero::before {
  content: '';
  position: absolute; top: -60px; right: -40px;
  width: 300px; height: 300px; border-radius: 50%;
  border: 1px solid rgba(200,217,230,.07); pointer-events: none;
}
.prof-hero__inner {
  max-width: 1280px; margin-inline: auto;
  display: flex; align-items: flex-start; gap: 28px;
  position: relative; z-index: 1;
}

/* Avatar */
.prof-avatar-wrap { position: relative; flex-shrink: 0; }
.prof-avatar {
  width: 86px; height: 86px; border-radius: 20px;
  background: rgba(200,217,230,.15);
  border: 2px solid rgba(255,255,255,.18);
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-display); font-weight: 300; font-size: 34px; color: #fff;
  overflow: hidden;
}
.prof-avatar img { width: 100%; height: 100%; object-fit: cover; }
.prof-avatar-edit {
  position: absolute; bottom: -4px; right: -4px;
  width: 24px; height: 24px; border-radius: 7px;
  background: var(--ipb-slate, #567c8d);
  border: 2px solid #1e3045;
  display: flex; align-items: center; justify-content: center;
  color: #fff; cursor: pointer; padding: 0;
}

/* Profile info */
.prof-info { flex: 1; }
.prof-name { font-family: var(--font-display); font-weight: 600; font-size: 38px; color: #fff; line-height: 1; margin-bottom: 6px; }
.prof-email { font-family: var(--font-body); font-size: 13px; color: rgba(255,255,255,.8); margin-bottom: 20px; }
.prof-stats { display: flex; gap: 44px; flex-wrap: wrap; }
.prof-stat { display: flex; flex-direction: column; gap: 2px; }
.prof-stat__val { font-family: var(--font-display); font-weight: 600; font-size: 34px; color: #fff; line-height: 1; }
.prof-stat__lbl { font-family: var(--font-body); font-size: 14px; letter-spacing: .5px; color: rgba(255,255,255,.8); }

/* Page body */
.prof-body {
  display: grid; grid-template-columns: 1fr 400px; gap: 24px;
  padding: 36px clamp(16px, 5vw, 80px) 80px;
  max-width: 1440px; margin: 0 auto;
}

/* Card */
.prof-card {
  background: #fff; border-radius: 18px;
  border: 1px solid rgba(46,65,86,.07);
  box-shadow: 0 5px 18px rgba(46,65,86,.04), 0 1px 4px rgba(46,65,86,.04);
  overflow: hidden; margin-bottom: 22px;
}
.prof-card:last-child { margin-bottom: 0; }
.prof-card-header { display: flex; align-items: center; padding: 20px 28px 17px; border-bottom: 1px solid rgba(46,65,86,.06); }
.prof-card-header h2 { font-family: var(--font-display); font-weight: 400; font-size: 26px; color: var(--ipb-navy); margin: 0; }
.prof-card-body { padding: 24px 28px; }

/* Form */
.prof-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 20px; }
.prof-form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
.prof-form-group:last-child { margin-bottom: 0; }
.prof-label { font-family: var(--font-body); font-weight: 500; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: var(--ipb-slate, #567c8d); }
.prof-input {
  padding: 15px 18px; font-family: var(--font-body); font-size: 15px;
  color: var(--ipb-navy); background: #f7f7f5;
  border: 1.5px solid transparent; border-radius: 13px;
  outline: none; width: 100%; transition: border-color .2s, box-shadow .2s;
  box-sizing: border-box;
}
.prof-input:focus { border-color: var(--ipb-slate, #567c8d); box-shadow: 0 0 0 3px rgba(86,124,141,.1); }
.prof-input[readonly] { opacity: .6; cursor: not-allowed; }
.prof-hint { font-family: var(--font-body); font-weight: 300; font-size: 13px; color: #7a8fa0; }
.prof-hint--error { color: #c0766a; }

/* Save button */
.prof-btn-save {
  display: inline-flex; align-items: center; justify-content: center;
  padding: 16px 32px; background: var(--ipb-navy); color: #fff;
  border: none; border-radius: 13px; font-family: var(--font-body);
  font-weight: 500; font-size: 16px; letter-spacing: .5px;
  cursor: pointer; transition: background .2s; margin-top: 20px;
}
.prof-btn-save:hover { background: #3a526a; }
.prof-btn-save--sm { padding: 12px 28px; font-size: 14px; border-radius: 12px; margin-top: 16px; }

/* Sidebar */
.prof-col-side { display: flex; flex-direction: column; }

/* Verif rows */
.prof-verif-row {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 14px; background: #f7f7f5;
  border-radius: 11px; margin-bottom: 8px;
}
.prof-verif-row:last-child { margin-bottom: 0; }
.prof-verif-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.prof-verif-lbl { flex: 1; font-family: var(--font-body); font-weight: 300; font-size: 16px; color: var(--ipb-navy); }
.prof-verif-ok   { font-family: var(--font-body); font-weight: 500; font-size: 13px; color: #5a9a78; }
.prof-verif-warn { font-family: var(--font-body); font-weight: 500; font-size: 13px; color: #d4a45a; }
.prof-verif-pend { font-family: var(--font-body); font-weight: 500; font-size: 13px; color: #d4a45a; }

/* Info Akun rows */
.prof-info-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 13px 0; border-bottom: 1px solid rgba(46,65,86,.05);
}
.prof-info-row:last-child { border-bottom: none; padding-bottom: 0; }
.prof-info-row:first-child { padding-top: 0; }
.prof-info-key { font-family: var(--font-body); font-weight: 300; font-size: 16px; color: var(--ipb-navy); }
.prof-info-val { font-family: var(--font-body); font-weight: 500; font-size: 15px; color: #7a8fa0; text-align: right; max-width: 200px; overflow: hidden; text-overflow: ellipsis; }
.prof-info-val--active { color: #5a9a78; }
.prof-info-val--navy   { color: var(--ipb-navy); }

/* Danger buttons */
.prof-btn-danger {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 15px;
  border: 1.5px solid rgba(192,118,106,.3); border-radius: 13px;
  background: transparent; font-family: var(--font-body);
  font-weight: 500; font-size: 16px; color: #c0766a;
  cursor: pointer; transition: background .15s; text-decoration: none;
}
.prof-btn-danger:hover { background: rgba(192,118,106,.05); color: #c0766a; }
.prof-btn-danger--sm { opacity: .65; font-size: 15px; padding: 12px; }

/* Responsive */
@media (max-width: 1100px) {
  .prof-body { grid-template-columns: 1fr; }
  .prof-col-side { flex-direction: row; flex-wrap: wrap; gap: 22px; }
  .prof-col-side .prof-card { flex: 1 1 280px; margin-bottom: 0; }
}
@media (max-width: 640px) {
  .prof-hero__inner { flex-direction: column; align-items: center; text-align: center; }
  .prof-stats { justify-content: center; gap: 24px; }
  .prof-stat__val { font-size: 28px; }
  .prof-name { font-size: 28px; }
  .prof-form-row { grid-template-columns: 1fr; }
  .prof-col-side { flex-direction: column; }
  .prof-col-side .prof-card { flex: unset; }
}
</style>

{{-- ── Flash Messages ── --}}
@if(session('success'))
<div style="background:rgba(90,154,120,.1); border-left:4px solid #5a9a78; padding:14px 20px; font-family:var(--font-body); font-size:14px; color:#2d6a4f; margin:0;">
  {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:rgba(192,118,106,.1); border-left:4px solid #c0766a; padding:14px 20px; font-family:var(--font-body); font-size:14px; color:#8a3a30; margin:0;">
  {{ session('error') }}
</div>
@endif

{{-- ── Profile Hero ── --}}
<div class="prof-hero">
  <div class="prof-hero__inner">

    {{-- Avatar --}}
    <div class="prof-avatar-wrap">
      <div class="prof-avatar">
        @if($user->avatar)
          <img src="{{ $user->avatarUrl }}" alt="{{ $user->name }}">
        @else
          {{ $user->initials }}
        @endif
      </div>
      <label for="avatar-quick" class="prof-avatar-edit" title="Ganti foto profil">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
          <circle cx="12" cy="13" r="4"/>
        </svg>
      </label>
      <form id="avatar-quick-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="d-none">
        @csrf @method('PUT')
        <input type="hidden" name="name"  value="{{ $user->name }}">
        <input type="hidden" name="email" value="{{ $user->email }}">
        <input type="file" id="avatar-quick" name="avatar" accept="image/*" onchange="this.form.submit()">
      </form>
    </div>

    {{-- Name, email, stats --}}
    <div class="prof-info">
      <div class="prof-name">{{ $user->name }}</div>
      <div class="prof-email">{{ $user->email }}</div>
      <div class="prof-stats">
        <div class="prof-stat">
          <span class="prof-stat__val">{{ $stats['total_items'] }}</span>
          <span class="prof-stat__lbl">Item Saya</span>
        </div>
        <div class="prof-stat">
          <span class="prof-stat__val">{{ $stats['total_rentals'] }}</span>
          <span class="prof-stat__lbl">Total Sewa</span>
        </div>
        <div class="prof-stat">
          <span class="prof-stat__val">{{ $userRating > 0 ? number_format($userRating, 1) . '★' : '—' }}</span>
          <span class="prof-stat__lbl">Rating</span>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- ── Page Body ── --}}
<div class="prof-body">

  {{-- ── Left: Forms ── --}}
  <div>

    {{-- Data Diri --}}
    <div class="prof-card">
      <div class="prof-card-header"><h2>Data Diri</h2></div>
      <div class="prof-card-body">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
          @csrf @method('PUT')

          {{-- Foto profil input (hidden — triggered by hero button) --}}
          <input type="file" id="avatar-input" name="avatar" accept="image/*" class="d-none">

          {{-- Nama + NIM --}}
          <div class="prof-form-row">
            <div class="prof-form-group" style="margin-bottom:0;">
              <label class="prof-label" for="p-name">Nama Lengkap</label>
              <input type="text" id="p-name" name="name" class="prof-input"
                value="{{ old('name', $user->name) }}" required>
              @error('name')<span class="prof-hint prof-hint--error">{{ $message }}</span>@enderror
            </div>
            <div class="prof-form-group" style="margin-bottom:0;">
              <label class="prof-label" for="p-nim">NIM</label>
              <input type="text" id="p-nim" name="nim" class="prof-input"
                value="{{ old('nim', $user->nim) }}"
                placeholder="Opsional"
                @if($user->nim) readonly @endif>
              @if($user->nim)
                <span class="prof-hint">NIM tidak dapat diubah</span>
              @else
                <span class="prof-hint">Masukkan NIM mahasiswa IPB</span>
              @endif
              @error('nim')<span class="prof-hint prof-hint--error">{{ $message }}</span>@enderror
            </div>
          </div>

          {{-- Email --}}
          <div class="prof-form-group">
            <label class="prof-label" for="p-email">Email</label>
            <input type="email" id="p-email" name="email" class="prof-input"
              value="{{ old('email', $user->email) }}" required>
            @error('email')<span class="prof-hint prof-hint--error">{{ $message }}</span>@enderror
          </div>

          {{-- WhatsApp --}}
          <div class="prof-form-group">
            <label class="prof-label" for="p-wa">No. WhatsApp</label>
            <input type="tel" id="p-wa" name="whatsapp" class="prof-input"
              value="{{ old('whatsapp', $user->whatsapp) }}" placeholder="+62 8xx-xxxx-xxxx">
            @error('whatsapp')<span class="prof-hint prof-hint--error">{{ $message }}</span>@enderror
          </div>

          {{-- Lokasi --}}
          <div class="prof-form-group">
            <label class="prof-label" for="p-lokasi">Lokasi / Alamat Kos</label>
            <input type="text" id="p-lokasi" name="lokasi" class="prof-input"
              value="{{ old('lokasi', $user->lokasi) }}" placeholder="Contoh: Asrama TPB Blok C, Dramaga">
            <span class="prof-hint">Digunakan penyewa untuk menjemput / mengembalikan barang</span>
            @error('lokasi')<span class="prof-hint prof-hint--error">{{ $message }}</span>@enderror
          </div>

          <div>
            <button type="submit" class="prof-btn-save">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Ubah Password --}}
    <div class="prof-card">
      <div class="prof-card-header"><h2>Ubah Password</h2></div>
      <div class="prof-card-body">
        <form method="POST" action="{{ route('profile.update') }}">
          @csrf @method('PUT')
          <input type="hidden" name="name"  value="{{ $user->name }}">
          <input type="hidden" name="email" value="{{ $user->email }}">

          <div class="prof-form-group">
            <label class="prof-label" for="p-pw">Password Baru</label>
            <input type="password" id="p-pw" name="password" class="prof-input" placeholder="Minimal 8 karakter">
            @error('password')<span class="prof-hint prof-hint--error">{{ $message }}</span>@enderror
          </div>
          <div class="prof-form-group">
            <label class="prof-label" for="p-pwc">Konfirmasi Password</label>
            <input type="password" id="p-pwc" name="password_confirmation" class="prof-input" placeholder="Ulangi password baru">
          </div>
          <div>
            <button type="submit" class="prof-btn-save prof-btn-save--sm">Ganti Password</button>
          </div>
        </form>
      </div>
    </div>

    {{-- KTM / Dokumen Upload --}}
    <div class="prof-card">
      <div class="prof-card-header"><h2>Upload Dokumen</h2></div>
      <div class="prof-card-body">
        <form method="POST" action="{{ route('profile.verification') }}" enctype="multipart/form-data">
          @csrf
          <div class="prof-form-group">
            <label class="prof-label">Jenis Dokumen</label>
            <select name="tipe" class="prof-input" style="cursor:pointer;" required>
              <option value="ktm" {{ old('tipe') === 'ktm' ? 'selected' : '' }}>KTM (Kartu Tanda Mahasiswa)</option>
              <option value="ktp" {{ old('tipe') === 'ktp' ? 'selected' : '' }}>KTP</option>
            </select>
            @error('tipe')<span class="prof-hint prof-hint--error">{{ $message }}</span>@enderror
          </div>
          <div class="prof-form-group">
            <label class="prof-label">File Dokumen</label>
            <label for="verif-file" style="display:block; padding:24px; border:1.5px dashed rgba(86,124,141,.3); border-radius:13px; background:#f7f7f5; text-align:center; cursor:pointer;">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(86,124,141,.5)" stroke-width="1.5" stroke-linecap="round" style="margin-bottom:8px; display:block; margin-inline:auto;">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
              </svg>
              <span style="font-family:var(--font-body); font-size:13px; font-weight:500; color:var(--ipb-navy);">Pilih file dokumen</span>
              <span id="verif-file-name" style="display:block; font-family:var(--font-body); font-size:11px; color:#7a8fa0; margin-top:4px;">JPG/PNG, maks. 5MB</span>
            </label>
            <input type="file" id="verif-file" name="file" accept=".jpg,.jpeg,.png" class="d-none" required
              onchange="document.getElementById('verif-file-name').textContent = this.files[0]?.name ?? 'JPG/PNG, maks. 5MB'">
            @error('file')<span class="prof-hint prof-hint--error">{{ $message }}</span>@enderror
          </div>
          <div>
            <button type="submit" class="prof-btn-save prof-btn-save--sm">Upload Verifikasi</button>
          </div>
        </form>
      </div>
    </div>

  </div>

  {{-- ── Right: Sidebar ── --}}
  <div class="prof-col-side">

    {{-- Verifikasi --}}
    <div class="prof-card">
      <div class="prof-card-header"><h2>Verifikasi</h2></div>
      <div class="prof-card-body">

        {{-- KTM --}}
        <div class="prof-verif-row">
          <span class="prof-verif-dot" style="background: {{ $ktmStatus === 'verified' ? '#5a9a78' : ($ktmStatus === 'pending' ? '#d4a45a' : '#c0766a') }};"></span>
          <span class="prof-verif-lbl">KTM Mahasiswa</span>
          @if($ktmStatus === 'verified')
            <span class="prof-verif-ok">✓ Terverifikasi</span>
          @elseif($ktmStatus === 'pending')
            <span class="prof-verif-pend">Menunggu review</span>
          @else
            <span class="prof-verif-warn">Belum upload</span>
          @endif
        </div>

        {{-- Foto Profil --}}
        <div class="prof-verif-row">
          <span class="prof-verif-dot" style="background: {{ $fotoVerif ? '#5a9a78' : '#d4a45a' }};"></span>
          <span class="prof-verif-lbl">Foto Profil</span>
          @if($fotoVerif)
            <span class="prof-verif-ok">✓ Ada</span>
          @else
            <span class="prof-verif-warn">Belum ada</span>
          @endif
        </div>

      </div>
    </div>

    {{-- Info Akun --}}
    <div class="prof-card">
      <div class="prof-card-header"><h2>Info Akun</h2></div>
      <div class="prof-card-body">
        <div class="prof-info-row">
          <span class="prof-info-key">Email</span>
          <span class="prof-info-val" style="font-size:13px;">{{ $user->email }}</span>
        </div>
        <div class="prof-info-row">
          <span class="prof-info-key">Bergabung</span>
          <span class="prof-info-val prof-info-val--navy">{{ $user->created_at->format('M Y') }}</span>
        </div>
        <div class="prof-info-row">
          <span class="prof-info-key">Status</span>
          <span class="prof-info-val prof-info-val--active">Aktif</span>
        </div>
        @if($user->nim)
        <div class="prof-info-row">
          <span class="prof-info-key">NIM</span>
          <span class="prof-info-val prof-info-val--navy">{{ $user->nim }}</span>
        </div>
        @endif
        @if($user->lokasi)
        <div class="prof-info-row">
          <span class="prof-info-key">Lokasi</span>
          <span class="prof-info-val" style="font-size:12px;">{{ $user->lokasi }}</span>
        </div>
        @endif
      </div>
    </div>

    {{-- Aksi: Logout + Delete --}}
    <div class="prof-card">
      <div class="prof-card-body" style="display:flex; flex-direction:column; gap:10px;">

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="prof-btn-danger">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Keluar dari Akun
          </button>
        </form>

        <button type="button" class="prof-btn-danger prof-btn-danger--sm" onclick="confirmDelete()">
          Hapus Akun
        </button>

      </div>
    </div>

  </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:20px; padding:32px; width:min(420px,90vw); box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <h3 style="font-family:var(--font-display); font-size:26px; color:var(--ipb-navy); margin:0 0 8px;">Hapus Akun?</h3>
    <p style="font-family:var(--font-body); font-size:14px; color:#7a8fa0; margin-bottom:20px; line-height:1.6;">
      Tindakan ini tidak dapat dibatalkan. Semua data, item, dan riwayat sewa kamu akan dihapus permanen.
    </p>
    <form method="POST" action="{{ route('profile.destroy') }}">
      @csrf @method('DELETE')
      <div class="prof-form-group">
        <label class="prof-label">Konfirmasi Password</label>
        <input type="password" name="password" class="prof-input" placeholder="Masukkan password kamu" required>
      </div>
      <div style="display:flex; gap:10px; margin-top:16px;">
        <button type="button" onclick="document.getElementById('deleteModal').style.display='none'"
          style="flex:1; padding:14px; border-radius:12px; border:1px solid rgba(46,65,86,.1); background:#fff; font-family:var(--font-body); font-size:14px; color:#7a8fa0; cursor:pointer;">
          Batal
        </button>
        <button type="submit"
          style="flex:1; padding:14px; border-radius:12px; border:none; background:#c0766a; color:#fff; font-family:var(--font-body); font-weight:500; font-size:14px; cursor:pointer;">
          Hapus Permanen
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
  document.getElementById('deleteModal').style.display = 'flex';
}
// Close modal on backdrop click
document.getElementById('deleteModal').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
</script>
@endpush
