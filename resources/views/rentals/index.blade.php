@extends('layouts.app')
@section('title', 'Rentals')

@push('styles')
<style>
/* ── Stats row ── */
.rental-stat-card {
  display: flex; align-items: center; gap: 14px;
  padding: 20px 22px;
  background: #fff; border: 1px solid rgba(46,65,86,0.06);
  border-radius: 14px; position: relative; overflow: hidden;
}
.rental-stat-icon {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.rsi--blue   { background: linear-gradient(145deg,rgba(86,124,141,0.15) 0%,rgba(200,217,230,0.3) 100%); }
.rsi--green  { background: linear-gradient(145deg,rgba(90,154,120,0.12) 0%,rgba(90,154,120,0.22) 100%); }
.rsi--gold   { background: linear-gradient(145deg,rgba(212,164,90,0.12) 0%,rgba(212,164,90,0.22) 100%); }
.rsi--red    { background: linear-gradient(145deg,rgba(192,118,106,0.1) 0%,rgba(192,118,106,0.2) 100%); }
.rstat-num {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 300; font-size: 34px; line-height: 1; color: var(--ipb-navy);
}
.rstat-lbl {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 12px; color: #7a8fa0;
}

/* ── Filter bar ── */
.rentals-filter-bar {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 20px; flex-wrap: wrap;
}
.filter-search-wrap {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px; background: #fff;
  border: 1px solid rgba(46,65,86,0.06); border-radius: 10px; width: 280px;
}
.filter-search-wrap input {
  flex: 1; border: none; outline: none;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 13px; color: var(--ipb-navy); background: transparent;
}
.filter-search-wrap input::placeholder { color: rgba(86,124,141,0.4); }
.status-tabs-wrap {
  display: flex; gap: 4px; padding: 4px;
  background: #fff; border: 1px solid rgba(46,65,86,0.06); border-radius: 11px;
}
.status-tab-btn {
  padding: 6px 16px; border: none; border-radius: 8px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 13px; cursor: pointer;
  transition: all 0.2s; background: transparent; color: #7a8fa0;
  text-decoration: none;
}
.status-tab-btn.active, .status-tab-btn:focus {
  background: var(--ipb-navy); color: #fff;
}
.role-select-wrap select {
  padding: 10px 36px 10px 14px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 13px; color: var(--ipb-navy);
  background: #fff; border: 1px solid rgba(46,65,86,0.06);
  border-radius: 10px; outline: none; cursor: pointer; appearance: none;
}

/* ── Rental card ── */
.rental-card-proto {
  position: relative; background: #fff;
  border: 1px solid rgba(46,65,86,0.07);
  border-radius: 18px; overflow: hidden;
  margin-bottom: 16px;
  transition: box-shadow 0.2s;
}
.rental-card-proto:hover { box-shadow: 0 8px 24px rgba(46,65,86,0.08); }
.rc-strip {
  position: absolute; top: 1px; bottom: 1px; left: 1px;
  width: 5px; border-radius: 4px 0 0 4px;
}
.rc-strip--active    { background: linear-gradient(180deg,#5a9a78 0%,#3d7a5a 100%); }
.rc-strip--dp_paid   { background: linear-gradient(180deg,#567c8d 0%,#2e4156 100%); }
.rc-strip--pending   { background: linear-gradient(180deg,#d4a45a 0%,#a07830 100%); }
.rc-strip--finished  { background: linear-gradient(180deg,#7a8fa0 0%,#567c8d 100%); }
.rc-strip--cancelled { background: linear-gradient(180deg,#c0766a 0%,#8a3a30 100%); }
.rc-tint {
  position: absolute; top: 1px; left: 1px; right: 1px;
  height: 90px; border-radius: 17px 17px 0 0; opacity: 0.5; pointer-events: none;
}
.rc-tint--active    { background: linear-gradient(180deg,rgba(90,154,120,0.08) 0%,rgba(90,154,120,0) 100%); }
.rc-tint--dp_paid   { background: linear-gradient(180deg,rgba(86,124,141,0.08) 0%,rgba(86,124,141,0) 100%); }
.rc-tint--pending   { background: linear-gradient(180deg,rgba(212,164,90,0.08) 0%,rgba(212,164,90,0) 100%); }
.rc-tint--finished  { background: linear-gradient(180deg,rgba(122,143,160,0.06) 0%,rgba(122,143,160,0) 100%); }
.rc-tint--cancelled { background: linear-gradient(180deg,rgba(192,118,106,0.06) 0%,rgba(192,118,106,0) 100%); }
.rc-inner { padding: 22px 26px 22px 30px; position: relative; z-index: 1; }
.rc-item-name {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 400; font-size: 22px; color: var(--ipb-navy); margin-bottom: 6px;
}
.rc-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 11px; border-radius: 20px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 600; font-size: 10.5px; letter-spacing: 0.6px;
}
.rc-dot { width: 5px; height: 5px; border-radius: 50%; }
.rcb--active   { background:rgba(90,154,120,0.12);border:1px solid rgba(90,154,120,0.2);color:#2d6a4f; }
.rcb--active .rc-dot { background:#5a9a78; }
.rcb--dp_paid  { background:rgba(86,124,141,0.1);border:1px solid rgba(86,124,141,0.18);color:#567c8d; }
.rcb--dp_paid .rc-dot { background:#567c8d; }
.rcb--pending  { background:rgba(212,164,90,0.12);border:1px solid rgba(212,164,90,0.2);color:#8a6020; }
.rcb--pending .rc-dot { background:#d4a45a; }
.rcb--finished { background:rgba(122,143,160,0.1);border:1px solid rgba(122,143,160,0.15);color:#7a8fa0; }
.rcb--finished .rc-dot { background:#7a8fa0; }
.rcb--cancelled{ background:rgba(192,118,106,0.1);border:1px solid rgba(192,118,106,0.2);color:#8a3a30; }
.rcb--cancelled .rc-dot { background:#c0766a; }
.rcb--role { background:rgba(46,65,86,0.07);border:none;color:var(--ipb-navy);font-weight:500; }
.rcb--role-penyewa { background:rgba(86,124,141,0.08);border:none;color:var(--ipb-slate); }

/* Stepper */
.stepper-track-proto {
  height: 4px; background: rgba(46,65,86,0.08);
  border-radius: 4px; margin-bottom: 8px; position: relative; overflow: hidden;
}
.stepper-fill-proto {
  position: absolute; left: 0; top: 0; height: 100%; border-radius: 4px; transition: width 0.3s;
}
.sf--active    { background: linear-gradient(90deg,#5a9a78,#3d7a5a); }
.sf--dp_paid   { background: linear-gradient(90deg,#5a9a78,#567c8d); }
.sf--pending   { background: linear-gradient(90deg,#d4a45a,#a07830); }
.sf--finished  { background: linear-gradient(90deg,#5a9a78,#7a8fa0); }
.sf--cancelled { background: rgba(192,118,106,0.5); }
.stepper-steps-proto {
  display: flex; align-items: flex-start; justify-content: space-between;
}
.sp-step { display: flex; align-items: center; gap: 5px; flex: 1; }
.sp-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.sp-dot--done    { background: #5a9a78; }
.sp-dot--current { background: #567c8d; }
.sp-dot--pending { background: rgba(46,65,86,0.15); }
.sp-label { font-family: var(--font-body,'DM Sans',sans-serif); font-size: 11px; color: #7a8fa0; }
.sp-label--done { font-weight: 500; color: var(--ipb-navy); }

/* Info cells */
.info-cells-wrap {
  display: grid; grid-template-columns: repeat(4,1fr); gap: 10px;
}
.info-cell-proto {
  padding: 12px 16px 14px;
  background: var(--ipb-cream);
  border: 1px solid rgba(46,65,86,0.04); border-radius: 11px;
}
.ic-key {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 10px; letter-spacing: 1.1px;
  text-transform: uppercase; color: #7a8fa0; margin-bottom: 6px;
}
.ic-val {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 14px; color: var(--ipb-navy); margin-bottom: 2px;
}
.ic-val--price {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 400; font-size: 18px;
}
.ic-sub {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 10.5px; color: #7a8fa0;
}

@media (max-width: 768px) {
  .info-cells-wrap { grid-template-columns: repeat(2,1fr); }
  .filter-search-wrap { width: 100%; }
  .status-tabs-wrap { flex-wrap: wrap; }
}
</style>
@endpush

@section('content')
@php
  $uid = auth()->id();
  $totalCount = $asPenyewa->total() + $asPemilik->total();
  $activeCount = \App\Models\Rental::where(function($q) use ($uid) {
    $q->where('penyewa_id', $uid)->orWhere('pemilik_id', $uid);
  })->where('status','active')->count();
  $pendingCount = \App\Models\Rental::where(function($q) use ($uid) {
    $q->where('penyewa_id', $uid)->orWhere('pemilik_id', $uid);
  })->whereIn('status',['pending','dp_paid'])->count();
  $finishedCount = \App\Models\Rental::where(function($q) use ($uid) {
    $q->where('penyewa_id', $uid)->orWhere('pemilik_id', $uid);
  })->whereIn('status',['finished','cancelled'])->count();

  $activeTab = request('tab','penyewa');
  $list = $activeTab === 'pemilik' ? $asPemilik : $asPenyewa;
@endphp

<div style="padding:48px 40px 80px; max-width:1200px; margin:0 auto;">

  {{-- Page header --}}
  <div style="margin-bottom:32px;">
    <h1 style="font-family:var(--font-display,'Cormorant Garamond',serif); font-weight:500; font-size:60px; line-height:1; color:var(--ipb-navy); margin-bottom:8px;">My Rentals</h1>
    <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-weight:600; font-size:13px; color:#7a8fa0; margin:0;">Lacak semua transaksi sewa kamu — aktif, selesai, maupun pending</p>
  </div>

  {{-- Stats row --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="rental-stat-card">
        <div class="rental-stat-icon rsi--blue">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#567c8d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
          </svg>
        </div>
        <div><div class="rstat-num">{{ $totalCount }}</div><div class="rstat-lbl">Total Rental</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="rental-stat-card">
        <div class="rental-stat-icon rsi--green">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5a9a78" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <div><div class="rstat-num">{{ $activeCount }}</div><div class="rstat-lbl">Aktif</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="rental-stat-card">
        <div class="rental-stat-icon rsi--gold">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d4a45a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <div><div class="rstat-num">{{ $pendingCount }}</div><div class="rstat-lbl">Pending / DP</div></div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="rental-stat-card">
        <div class="rental-stat-icon rsi--red">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c0766a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
        <div><div class="rstat-num">{{ $finishedCount }}</div><div class="rstat-lbl">Selesai</div></div>
      </div>
    </div>
  </div>

  {{-- Filter bar --}}
  <div class="rentals-filter-bar">
    <div class="filter-search-wrap">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(86,124,141,0.5)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" placeholder="Cari rental..." id="rentalSearch">
    </div>
    <div class="status-tabs-wrap">
      <a href="{{ route('rentals.index', ['tab' => $activeTab]) }}" class="status-tab-btn active">Semua</a>
      <a href="{{ route('rentals.index', ['tab' => $activeTab, 'status' => 'active']) }}" class="status-tab-btn">Aktif</a>
      <a href="{{ route('rentals.index', ['tab' => $activeTab, 'status' => 'pending']) }}" class="status-tab-btn">Pending</a>
      <a href="{{ route('rentals.index', ['tab' => $activeTab, 'status' => 'finished']) }}" class="status-tab-btn">Selesai</a>
    </div>
    <div class="role-select-wrap">
      <select onchange="window.location=this.value">
        <option value="{{ route('rentals.index', ['tab' => 'penyewa']) }}" {{ $activeTab === 'penyewa' ? 'selected' : '' }}>Saya Menyewa</option>
        <option value="{{ route('rentals.index', ['tab' => 'pemilik']) }}" {{ $activeTab === 'pemilik' ? 'selected' : '' }}>Barang Saya</option>
      </select>
    </div>
    <div style="margin-left:auto; font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:#7a8fa0;">
      Menampilkan <strong style="color:var(--ipb-navy);">{{ $list->total() }}</strong> transaksi
    </div>
  </div>

  {{-- Rental cards --}}
  @if($list->count())
  @foreach($list as $rental)
  @php
    $st = $rental->status;
    $isPemilik = ($activeTab === 'pemilik');
    $partner = $isPemilik ? $rental->penyewa : $rental->pemilik;
    $stepMap = ['pending'=>0,'dp_paid'=>33,'active'=>67,'finished'=>100,'cancelled'=>0];
    $fillPct = $stepMap[$st] ?? 0;
  @endphp

  <div class="rental-card-proto">
    <div class="rc-strip rc-strip--{{ $st }}"></div>
    <div class="rc-tint rc-tint--{{ $st }}"></div>
    <div class="rc-inner">

      {{-- Top row: item + actions --}}
      <div class="d-flex align-items-start justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
          {{-- Thumb --}}
          <div style="width:50px; height:50px; border-radius:14px; overflow:hidden; flex-shrink:0; background:linear-gradient(145deg,rgba(46,65,86,0.12),rgba(86,124,141,0.2));">
            @if(true)
              <img src="{{ $rental->item->first_foto_url }}" style="width:100%;height:100%;object-fit:cover;" alt=""
                   onerror="this.onerror=null; this.parentElement.style.background='rgba(46,65,86,0.15)'">
            @else
              <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <i class="mdi mdi-{{ $rental->item->kategoriIcon }}" style="font-size:22px;color:var(--ipb-slate);"></i>
              </div>
            @endif
          </div>
          <div>
            <div class="rc-item-name">{{ $rental->item->nama }}</div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="rc-badge rcb--{{ $st }}">
                <span class="rc-dot"></span>{{ strtoupper($rental->statusLabel) }}
              </span>
              <span class="rc-badge {{ $isPemilik ? 'rcb--role' : 'rcb--role-penyewa' }}">
                {{ $isPemilik ? 'Pemilik' : 'Penyewa' }}
              </span>
              <span style="font-family:monospace; font-size:10px; color:rgba(122,143,160,0.6);">#{{ str_pad($rental->id,3,'0',STR_PAD_LEFT) }}</span>
            </div>
          </div>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
          @if($st === 'active' && $isPemilik)
          <form method="POST" action="{{ route('rentals.action', $rental) }}">
            @csrf
            <input type="hidden" name="action" value="finish">
            <button type="submit" style="padding:8px 18px; border-radius:10px; background:linear-gradient(163deg,#5a9a78 0%,#3d7a5a 100%); color:#fff; border:none; font-family:var(--font-body,'DM Sans',sans-serif); font-weight:500; font-size:13px; cursor:pointer; box-shadow:0 4px 14px rgba(90,154,120,0.25);">
              ✓ Selesaikan
            </button>
          </form>
          @endif
          <a href="{{ route('rentals.show', $rental) }}" style="padding:8px 18px; border-radius:10px; background:rgba(46,65,86,0.05); color:var(--ipb-navy); border:1px solid rgba(46,65,86,0.1); font-family:var(--font-body,'DM Sans',sans-serif); font-weight:500; font-size:13px; text-decoration:none;">Detail</a>
        </div>
      </div>

      {{-- Progress stepper --}}
      <div style="margin-bottom:18px;">
        <div class="stepper-track-proto">
          <div class="stepper-fill-proto sf--{{ $st }}" style="width:{{ $fillPct }}%;"></div>
        </div>
        <div class="stepper-steps-proto">
          @foreach(['pending' => 'Pending', 'dp_paid' => 'Dp Paid', 'active' => 'Active', 'finished' => 'Finished'] as $sKey => $sLabel)
          @php
            $orders = ['pending'=>1,'dp_paid'=>2,'active'=>3,'finished'=>4,'cancelled'=>0];
            $curOrder = $orders[$st] ?? 0;
            $thisOrder = $orders[$sKey] ?? 0;
            $dotClass = ($thisOrder < $curOrder) ? 'sp-dot--done' : (($sKey === $st) ? 'sp-dot--current' : 'sp-dot--pending');
            $lblClass = ($thisOrder <= $curOrder) ? 'sp-label--done' : '';
            $lblColor = ($sKey === $st) ? ($st === 'active' ? '#2d6a4f' : '#567c8d') : '';
          @endphp
          <div class="sp-step">
            <div class="sp-dot {{ $dotClass }}"></div>
            <div class="sp-label {{ $lblClass }}" @if($lblColor) style="color:{{ $lblColor }};" @endif>{{ $sLabel }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Info cells --}}
      <div class="info-cells-wrap">
        <div class="info-cell-proto">
          <div class="ic-key">Penyewa</div>
          <div class="ic-val">{{ $rental->penyewa->name }}</div>
          <div class="ic-sub">{{ $rental->penyewa_id === $uid ? 'Kamu' : 'Pelanggan' }}</div>
        </div>
        <div class="info-cell-proto">
          <div class="ic-key">Pemilik</div>
          <div class="ic-val">{{ $rental->pemilik->name }}</div>
          <div class="ic-sub">{{ $rental->pemilik_id === $uid ? 'Kamu' : 'Pemilik item' }}</div>
        </div>
        <div class="info-cell-proto">
          <div class="ic-key">Durasi</div>
          <div class="ic-val">{{ $rental->tanggal_mulai->format('d M Y') }}</div>
          <div class="ic-sub">→ {{ $rental->tanggal_selesai->format('d M Y') }} · {{ $rental->durasi_hari }} hari</div>
        </div>
        <div class="info-cell-proto">
          <div class="ic-key">Total</div>
          <div class="ic-val ic-val--price">Rp {{ number_format($rental->total_harga,0,',','.') }}</div>
          <div class="ic-sub">Dep. Rp {{ number_format($rental->deposit,0,',','.') }}</div>
        </div>
      </div>

    </div>
  </div>
  @endforeach

  @if($list->hasPages())
  <div class="d-flex justify-content-center mt-4">
    {{ $list->withQueryString()->links('pagination::bootstrap-5') }}
  </div>
  @endif

  @else
  <div class="text-center py-5">
    <i class="mdi mdi-handshake-outline" style="font-size:72px; color:rgba(46,65,86,0.1);"></i>
    <h5 style="color:#7a8fa0; margin-top:16px; font-family:var(--font-display,'Cormorant Garamond',serif); font-size:28px; font-weight:400;">Belum ada rental</h5>
    <p style="color:#7a8fa0; font-size:14px; font-family:var(--font-body,'DM Sans',sans-serif);">
      @if($activeTab === 'pemilik') Belum ada yang menyewa barang kamu.
      @else Kamu belum pernah menyewa barang. @endif
    </p>
    <a href="{{ route('explore') }}" class="btn btn-navy mt-2 px-5" style="border-radius:20px;">Jelajahi Barang</a>
  </div>
  @endif

</div>
@endsection
