@extends('layouts.admin')
@section('title', 'Kelola Rental')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 style="font-weight:800; color:var(--ipb-navy); margin:0;">Kelola Rental</h4>
    <p style="color:#7a8fa0; font-size:14px; margin:4px 0 0;">{{ $rentals->total() }} transaksi total</p>
  </div>
</div>

<form method="GET" action="{{ route('admin.rentals.index') }}" class="ipb-card p-3 mb-4">
  <div class="d-flex gap-2 flex-wrap">
    <input type="text" name="q" class="form-control form-control-sm" style="border-radius:10px; max-width:240px; font-size:13px;" placeholder="Cari item / penyewa..." value="{{ request('q') }}">
    <select name="status" class="form-select form-select-sm" style="border-radius:10px; width:auto; font-size:13px;">
      <option value="">Semua Status</option>
      @foreach(['pending','dp_paid','active','finished','cancelled'] as $s)
      <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-sm btn-navy px-4" style="border-radius:10px;">Filter</button>
    @if(request()->hasAny(['q','status']))
    <a href="{{ route('admin.rentals.index') }}" class="btn btn-sm btn-outline-navy px-3" style="border-radius:10px;">Reset</a>
    @endif
  </div>
</form>

<div class="ipb-card">
  <div class="table-responsive">
    <table class="table table-ipb mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Item</th>
          <th>Penyewa</th>
          <th>Pemilik</th>
          <th>Durasi</th>
          <th>Total</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($rentals as $rental)
        <tr>
          <td style="font-size:12px; color:#7a8fa0;">{{ $rental->id }}</td>
          <td>
            <div style="font-weight:600; color:var(--ipb-navy); font-size:13px;">{{ Str::limit($rental->item->nama, 22) }}</div>
            <div style="font-size:11px; color:#7a8fa0;">{{ \Carbon\Carbon::parse($rental->tanggal_mulai)->format('d M') }} – {{ \Carbon\Carbon::parse($rental->tanggal_selesai)->format('d M Y') }}</div>
          </td>
          <td style="font-size:13px; color:#7a8fa0;">{{ $rental->penyewa->name }}</td>
          <td style="font-size:13px; color:#7a8fa0;">{{ $rental->pemilik->name }}</td>
          <td style="font-size:13px; color:var(--ipb-navy);">{{ $rental->durasi_hari }} hari</td>
          <td style="font-size:13px; font-weight:600; color:var(--ipb-navy);">Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</td>
          <td><span class="badge {{ $rental->statusBadge }}" style="border-radius:8px; font-size:11px;">{{ $rental->statusLabel }}</span></td>
          <td>
            <a href="{{ route('admin.rentals.show', $rental) }}" class="btn btn-sm btn-outline-navy" style="border-radius:8px; font-size:12px;">
              <i class="mdi mdi-eye-outline"></i>
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-4" style="color:#7a8fa0;">Tidak ada rental ditemukan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($rentals->hasPages())
<div class="d-flex justify-content-center mt-4">
  {{ $rentals->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endif
@endsection
