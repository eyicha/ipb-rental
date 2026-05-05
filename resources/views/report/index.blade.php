@extends('layouts.app')
@section('title', 'Laporan')

@push('styles')
<style>
/* ── Page hero ── */
.report-hero {
  display: flex; align-items: flex-end; justify-content: space-between;
  padding: 32px 80px 32px; min-height: 180px;
  background: linear-gradient(174deg,rgba(46,65,86,1) 0%,rgba(62,92,114,1) 55%,rgba(86,124,141,1) 100%);
  position: relative; overflow: hidden;
}
.report-hero::after {
  content: ''; position: absolute;
  right: 80px; bottom: -80px;
  width: 260px; height: 260px; border-radius: 50%;
  border: 1px solid rgba(200,217,230,0.07);
}
.report-hero__title {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 600; font-size: 52px; line-height: 1.05;
  color: #fff; margin-bottom: 10px;
}
.report-hero__sub {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 600; font-size: 14px; line-height: 1.7;
  color: rgba(234,230,224,0.9); max-width: 520px;
}
/* ── Tab group ── */
.tab-group-proto {
  display: flex; gap: 4px; padding: 4px;
  background: var(--ipb-cream); border-radius: 10px; margin-bottom: 20px;
}
.tab-btn-proto {
  flex: 1; padding: 10px 16px; border: none; border-radius: 8px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 14px; cursor: pointer; transition: all 0.2s;
  background: transparent; color: #7a8fa0;
}
.tab-btn-proto.active {
  background: #fff; color: var(--ipb-navy); font-weight: 500;
  box-shadow: 0 1px 4px rgba(46,65,86,0.1);
}
/* ── Card ── */
.rp-card {
  background: #fff; border-radius: 16px;
  border: 1px solid rgba(46,65,86,0.07);
  box-shadow: 0 4px 16px rgba(46,65,86,0.05);
  overflow: hidden; margin-bottom: 22px;
}
.rp-card-header {
  display: flex; align-items: center;
  padding: 20px 26px 16px;
  border-bottom: 1px solid rgba(46,65,86,0.06);
}
.rp-card-header h2 {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 400; font-size: 24px; color: var(--ipb-navy); margin: 0;
}
.rp-card-body { padding: 22px 26px; }
/* ── Form ── */
.rp-label {
  display: block; font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 11px; letter-spacing: 1.8px;
  text-transform: uppercase; color: var(--ipb-slate); margin-bottom: 6px;
}
.rp-input {
  width: 100%; padding: 14px 16px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 15px; color: var(--ipb-navy);
  background: var(--ipb-cream);
  border: 1.5px solid transparent; border-radius: 11px;
  outline: none; transition: border-color 0.2s, box-shadow 0.2s;
}
.rp-input::placeholder { color: rgba(86,124,141,0.38); }
.rp-input:focus { border-color: var(--ipb-slate); box-shadow: 0 0 0 3px rgba(86,124,141,0.1); }
.rp-input.is-invalid { border-color: #dc3545; }
/* ── Sidebar ── */
.status-row-proto {
  display: flex; align-items: center; justify-content: space-between;
  padding: 13px 0; border-bottom: 1px solid rgba(46,65,86,0.05);
}
.status-row-proto:last-child { border-bottom: none; padding-bottom: 0; }
.status-row-proto:first-child { padding-top: 0; }
.status-dot-proto { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.status-count-proto {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 300; font-size: 24px; color: var(--ipb-navy);
}
.info-box-proto {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 14px 16px; background: rgba(86,124,141,0.07);
  border-radius: 11px; margin-bottom: 10px;
}
.info-box-proto:last-child { margin-bottom: 0; }
.info-box-proto p {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 14px; line-height: 1.65;
  color: var(--ipb-navy); margin: 0;
}
.info-box-proto strong { font-weight: 500; }
.cat-item-proto {
  display: flex; align-items: center; justify-content: space-between;
  padding: 9px 12px; background: var(--ipb-cream);
  border-radius: 9px; margin-bottom: 8px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 14px; color: var(--ipb-navy);
}
.cat-item-proto:last-child { margin-bottom: 0; }
.cat-item-proto span { font-weight: 500; font-size: 14px; color: #7a8fa0; }
</style>
@endpush

@section('content')

{{-- ── Hero Banner ── --}}
<div class="report-hero">
  <div style="position:relative; z-index:1;">
    <h1 class="report-hero__title">Laporan &amp; Pengaduan</h1>
    <p class="report-hero__sub">Ada masalah dengan transaksi? Sampaikan di sini. Admin kami siap membantu dalam 1×24 jam kerja.</p>
  </div>
</div>

{{-- ── Page Body ── --}}
<div style="display:grid; grid-template-columns:1fr 380px; gap:28px; padding:36px 80px 80px; max-width:1400px; margin:0 auto;" class="report-page-body">

  {{-- ── Left: Form column ── --}}
  <div>
    {{-- Tab switcher --}}
    <div class="tab-group-proto">
      <button class="tab-btn-proto active" id="tab-buat" onclick="switchTab('buat')">+ Buat Laporan</button>
      <button class="tab-btn-proto" id="tab-saya" onclick="switchTab('saya')">Laporan Saya</button>
    </div>

    {{-- Buat Laporan panel --}}
    <div class="rp-card" id="panel-buat">
      <div class="rp-card-header"><h2>Buat Laporan</h2></div>
      <div class="rp-card-body">
        <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="rp-label">Kategori <span style="color:#dc3545;">*</span></label>
              <select name="kategori" class="rp-input @error('kategori') is-invalid @enderror" required>
                <option value="">Pilih kategori...</option>
                @foreach(['penipuan' => 'Penipuan', 'barang_rusak' => 'Kerusakan Barang', 'tidak_sesuai' => 'Barang Tidak Sesuai', 'keterlambatan' => 'Pengembalian Terlambat', 'lainnya' => 'Lainnya'] as $val => $label)
                <option value="{{ $val }}" {{ old('kategori') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              @error('kategori')<div style="font-size:12px;color:#dc3545;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="rp-label">Transaksi Terkait</label>
              <select name="rental_id" class="rp-input @error('rental_id') is-invalid @enderror">
                <option value="">Pilih transaksi (opsional)</option>
                @foreach($myRentals as $r)
                <option value="{{ $r->id }}" {{ old('rental_id') == $r->id ? 'selected' : '' }}>
                  #{{ str_pad($r->id,3,'0',STR_PAD_LEFT) }} – {{ $r->item->nama }} ({{ $r->statusLabel }})
                </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mb-4">
            <label class="rp-label">Deskripsi / Kronologi <span style="color:#dc3545;">*</span></label>
            <textarea name="deskripsi" class="rp-input @error('deskripsi') is-invalid @enderror"
              style="resize:vertical; min-height:130px; line-height:1.65;"
              placeholder="Jelaskan apa yang terjadi, kapan, dan kondisi barangnya..." required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')<div style="font-size:12px;color:#dc3545;margin-top:4px;">{{ $message }}</div>@enderror
          </div>

          <div class="mb-4">
            <label class="rp-label">Lampiran Bukti (Opsional)</label>
            <label for="buktiInput" style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px; padding:32px 20px; background:var(--ipb-cream); border:1.5px dashed rgba(86,124,141,0.28); border-radius:12px; cursor:pointer; transition:background 0.2s, border-color 0.2s;"
              onmouseover="this.style.background='rgba(200,217,230,0.15)';this.style.borderColor='rgba(86,124,141,0.5)'"
              onmouseout="this.style.background='var(--ipb-cream)';this.style.borderColor='rgba(86,124,141,0.28)'">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--ipb-slate)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
              </svg>
              <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:15px; color:var(--ipb-navy);">Tap untuk upload foto / PDF</span>
              <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:#7a8fa0;">Maks 10MB</span>
            </label>
            <input type="file" id="buktiInput" name="bukti[]" multiple accept="image/*,.pdf" class="d-none" onchange="previewBukti(this)">
            <div id="buktiPreview" class="d-flex flex-wrap gap-2 mt-3"></div>
          </div>

          <button type="submit" style="display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:16px; background:var(--ipb-navy); color:#fff; border:none; border-radius:12px; font-family:var(--font-body,'DM Sans',sans-serif); font-weight:500; font-size:15px; letter-spacing:0.6px; cursor:pointer; transition:background 0.2s; margin-top:6px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
            Kirim Laporan
          </button>
        </form>
      </div>
    </div>

    {{-- Laporan Saya panel --}}
    <div class="rp-card" id="panel-saya" style="display:none;">
      <div class="rp-card-header"><h2>Laporan Saya</h2></div>
      <div class="rp-card-body">
        @forelse($myReports as $report)
        <div style="padding:14px 0; border-bottom:1px solid rgba(46,65,86,0.06);">
          <div class="d-flex align-items-center justify-content-between mb-1">
            <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; font-weight:700; color:var(--ipb-navy);">{{ $report->kategoriLabel }}</span>
            <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:10px; font-weight:600; padding:4px 10px; border-radius:20px;
              {{ $report->status === 'pending' ? 'background:rgba(212,164,90,0.15);color:#8a6020;' : ($report->status === 'diproses' ? 'background:rgba(86,124,141,0.12);color:#2e5566;' : 'background:rgba(90,154,120,0.12);color:#2d6a4f;') }}">
              {{ ucfirst($report->status) }}
            </span>
          </div>
          <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:12px; color:#7a8fa0; margin:4px 0; line-height:1.5;">{{ Str::limit($report->deskripsi, 90) }}</p>
          @if($report->balasan_admin)
          <div style="background:rgba(200,217,230,0.2); border-left:3px solid var(--ipb-slate); padding:8px 12px; border-radius:0 8px 8px 0; margin-top:8px;">
            <div style="font-size:11px; font-weight:700; color:var(--ipb-slate); margin-bottom:2px; font-family:var(--font-body,'DM Sans',sans-serif);">Balasan Admin</div>
            <p style="font-size:12px; color:#7a8fa0; margin:0; line-height:1.5; font-family:var(--font-body,'DM Sans',sans-serif);">{{ $report->balasan_admin }}</p>
          </div>
          @endif
          <div style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:11px; color:#7a8fa0; margin-top:6px;">{{ $report->created_at->diffForHumans() }}</div>
        </div>
        @empty
        <div style="text-align:center; padding:48px 20px; color:#7a8fa0;">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(122,143,160,0.4)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 16px; display:block;">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
          </svg>
          <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:14px;">Belum ada laporan yang dikirim.</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>

  {{-- ── Right: Sidebar ── --}}
  <div>

    {{-- Status Laporan --}}
    <div class="rp-card">
      <div class="rp-card-header"><h2>Status Laporan</h2></div>
      <div class="rp-card-body" style="padding:16px 26px;">
        @php
          $pendingRep = $myReports->where('status','pending')->count();
          $diprosesRep = $myReports->where('status','diproses')->count();
          $selesaiRep = $myReports->where('status','selesai')->count();
        @endphp
        <div class="status-row-proto">
          <div style="display:flex; align-items:center; gap:10px; font-family:var(--font-body,'DM Sans',sans-serif); font-weight:300; font-size:15px; color:var(--ipb-navy);">
            <span class="status-dot-proto" style="background:#d4a45a;"></span> Menunggu Respons
          </div>
          <span class="status-count-proto">{{ $pendingRep }}</span>
        </div>
        <div class="status-row-proto">
          <div style="display:flex; align-items:center; gap:10px; font-family:var(--font-body,'DM Sans',sans-serif); font-weight:300; font-size:15px; color:var(--ipb-navy);">
            <span class="status-dot-proto" style="background:#567c8d;"></span> Sedang Diproses
          </div>
          <span class="status-count-proto">{{ $diprosesRep }}</span>
        </div>
        <div class="status-row-proto">
          <div style="display:flex; align-items:center; gap:10px; font-family:var(--font-body,'DM Sans',sans-serif); font-weight:300; font-size:15px; color:var(--ipb-navy);">
            <span class="status-dot-proto" style="background:#5a9a78;"></span> Selesai
          </div>
          <span class="status-count-proto">{{ $selesaiRep }}</span>
        </div>
      </div>
    </div>

    {{-- Informasi --}}
    <div class="rp-card">
      <div class="rp-card-header"><h2>Informasi</h2></div>
      <div class="rp-card-body" style="padding:16px 26px;">
        <div class="info-box-proto">
          <div style="width:20px; flex-shrink:0; color:var(--ipb-slate); margin-top:2px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
          </div>
          <p>Laporan akan direspons dalam <strong>1×24 jam</strong> di hari kerja (Senin–Jumat).</p>
        </div>
        <div class="info-box-proto">
          <div style="width:20px; flex-shrink:0; color:var(--ipb-slate); margin-top:2px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
          </div>
          <p>Sertakan <strong>nomor transaksi</strong> dan <strong>foto bukti</strong> agar penanganan lebih cepat.</p>
        </div>
        <div class="info-box-proto">
          <div style="width:20px; flex-shrink:0; color:var(--ipb-slate); margin-top:2px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
          </div>
          <p>Butuh bantuan cepat? Hubungi admin via <strong>Chat</strong> di menu utama.</p>
        </div>
      </div>
    </div>

    {{-- Kategori Umum --}}
    <div class="rp-card">
      <div class="rp-card-header"><h2>Kategori Umum</h2></div>
      <div class="rp-card-body" style="padding:16px 26px;">
        @php
          $katCounts = $myReports->groupBy('kategori')->map->count();
        @endphp
        @foreach(['penipuan' => 'Penipuan', 'barang_rusak' => 'Kerusakan Barang', 'tidak_sesuai' => 'Tidak Sesuai', 'keterlambatan' => 'Pengembalian Terlambat', 'lainnya' => 'Lainnya'] as $k => $lbl)
        <div class="cat-item-proto">{{ $lbl }} <span>{{ $katCounts[$k] ?? 0 }}</span></div>
        @endforeach
      </div>
    </div>

  </div>
</div>

@push('styles')
<style>
@media (max-width: 1024px) {
  .report-page-body { grid-template-columns: 1fr !important; }
  .report-hero { padding: 32px 24px !important; }
}
</style>
@endpush

@endsection

@push('scripts')
<script>
function switchTab(tab) {
  document.getElementById('panel-buat').style.display = tab === 'buat' ? 'block' : 'none';
  document.getElementById('panel-saya').style.display = tab === 'saya' ? 'block' : 'none';
  document.querySelectorAll('.tab-btn-proto').forEach((b,i) => {
    b.classList.toggle('active', (i === 0 && tab === 'buat') || (i === 1 && tab === 'saya'));
  });
}
function previewBukti(input) {
  const preview = document.getElementById('buktiPreview');
  preview.innerHTML = '';
  [...input.files].slice(0,5).forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      const div = document.createElement('div');
      div.style.cssText = 'width:70px;height:70px;border-radius:10px;overflow:hidden;border:2px solid rgba(86,124,141,0.2);';
      div.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
      preview.appendChild(div);
    };
    reader.readAsDataURL(file);
  });
}
</script>
@endpush
