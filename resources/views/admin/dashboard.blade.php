@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-4">
  <h4 style="font-weight:800; color:var(--ipb-navy); margin:0;">Dashboard</h4>
  <p style="color:#7a8fa0; font-size:14px; margin:4px 0 0;">Selamat datang, {{ auth()->user()->name }}</p>
</div>

{{-- ── Stats Row ── --}}
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card-mini d-flex align-items-center gap-3">
      <div style="width:48px; height:48px; border-radius:14px; background:rgba(46,65,86,0.08); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <i class="mdi mdi-account-group-outline" style="font-size:24px; color:var(--ipb-navy);"></i>
      </div>
      <div>
        <div class="stat-num-big">{{ $stats['total_users'] }}</div>
        <div class="stat-lbl-sm">Total Pengguna</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card-mini d-flex align-items-center gap-3">
      <div style="width:48px; height:48px; border-radius:14px; background:rgba(90,154,120,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <i class="mdi mdi-package-variant-closed" style="font-size:24px; color:var(--ipb-green);"></i>
      </div>
      <div>
        <div class="stat-num-big" style="color:var(--ipb-green);">{{ $stats['total_items'] }}</div>
        <div class="stat-lbl-sm">Total Item</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card-mini d-flex align-items-center gap-3">
      <div style="width:48px; height:48px; border-radius:14px; background:rgba(86,124,141,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <i class="mdi mdi-handshake-outline" style="font-size:24px; color:var(--ipb-slate);"></i>
      </div>
      <div>
        <div class="stat-num-big" style="color:var(--ipb-slate);">{{ $stats['total_rentals'] }}</div>
        <div class="stat-lbl-sm">Total Rental</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card-mini d-flex align-items-center gap-3">
      <div style="width:48px; height:48px; border-radius:14px; background:rgba(212,164,90,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <i class="mdi mdi-alert-circle-outline" style="font-size:24px; color:var(--ipb-gold);"></i>
      </div>
      <div>
        <div class="stat-num-big" style="color:var(--ipb-gold);">{{ $stats['pending_reports'] }}</div>
        <div class="stat-lbl-sm">Laporan Pending</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  {{-- ── Recent Rentals ── --}}
  <div class="col-lg-7">
    <div class="ipb-card">
      <div class="p-3 pb-0 d-flex align-items-center justify-content-between">
        <div style="font-size:13px; font-weight:700; color:var(--ipb-navy);">Rental Terbaru</div>
        <a href="{{ route('admin.rentals.index') }}" style="font-size:12px; color:var(--ipb-slate); text-decoration:none;">Lihat semua</a>
      </div>
      <div class="table-responsive mt-3">
        <table class="table table-ipb mb-0">
          <thead><tr><th>Item</th><th>Penyewa</th><th>Status</th><th>Total</th></tr></thead>
          <tbody>
            @foreach($recentRentals as $rental)
            <tr>
              <td>
                <a href="{{ route('admin.rentals.show', $rental) }}" style="font-weight:600; color:var(--ipb-navy); text-decoration:none; font-size:13px;">
                  {{ $rental->item ? Str::limit($rental->item->nama, 25) : '(Item Deleted)' }}
                </a>
              </td>
              <td style="font-size:13px; color:#7a8fa0;">{{ $rental->penyewa->name ?? '(User Deleted)' }}</td>
              <td><span class="badge {{ $rental->statusBadge }}" style="border-radius:8px; font-size:11px;">{{ $rental->statusLabel }}</span></td>
              <td style="font-size:13px; font-weight:600; color:var(--ipb-navy);">Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- ── Quick Links + Pending ── --}}
  <div class="col-lg-5">
    <div class="ipb-card p-4 mb-4">
      <div style="font-size:13px; font-weight:700; color:var(--ipb-navy); margin-bottom:14px;">Perlu Tindakan</div>
      <div class="d-flex flex-column gap-2">
        <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="background:rgba(212,164,90,0.08); border-radius:12px; border:1px solid rgba(212,164,90,0.2);">
          <i class="mdi mdi-shield-check-outline" style="font-size:22px; color:var(--ipb-gold);"></i>
          <div>
            <div style="font-size:13px; font-weight:700; color:var(--ipb-navy);">{{ $stats['pending_verif'] }} Verifikasi</div>
            <div style="font-size:11px; color:#7a8fa0;">Menunggu review</div>
          </div>
          <i class="mdi mdi-chevron-right ms-auto" style="color:#7a8fa0;"></i>
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none" style="background:rgba(192,118,106,0.08); border-radius:12px; border:1px solid rgba(192,118,106,0.15);">
          <i class="mdi mdi-alert-circle-outline" style="font-size:22px; color:#c0766a;"></i>
          <div>
            <div style="font-size:13px; font-weight:700; color:var(--ipb-navy);">{{ $stats['pending_reports'] }} Laporan</div>
            <div style="font-size:11px; color:#7a8fa0;">Menunggu penanganan</div>
          </div>
          <i class="mdi mdi-chevron-right ms-auto" style="color:#7a8fa0;"></i>
        </a>
      </div>
    </div>

    <div class="ipb-card p-4">
      <div style="font-size:13px; font-weight:700; color:var(--ipb-navy); margin-bottom:14px;">Status Rental</div>
      @foreach(['pending' => ['Menunggu Konfirmasi', 'var(--ipb-gold)'], 'active' => ['Aktif', 'var(--ipb-green)'], 'finished' => ['Selesai', '#7a8fa0']] as $status => [$label, $color])
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span style="font-size:13px; color:#7a8fa0;">{{ $label }}</span>
        <span style="font-size:14px; font-weight:700; color:{{ $color }};">{{ $stats['rentals_by_status'][$status] ?? 0 }}</span>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
