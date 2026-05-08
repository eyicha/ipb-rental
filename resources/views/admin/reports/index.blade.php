@extends('layouts.admin')
@section('title', 'Kelola Laporan')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 style="font-weight:800; color:var(--ipb-navy); margin:0;">Kelola Laporan</h4>
    <p style="color:#7a8fa0; font-size:14px; margin:4px 0 0;">{{ $reports->total() }} laporan total</p>
  </div>
</div>

<form method="GET" action="{{ route('admin.reports.index') }}" class="ipb-card p-3 mb-4">
  <div class="d-flex gap-2 flex-wrap">
    <select name="status" class="form-select form-select-sm" style="border-radius:10px; width:auto; font-size:13px;">
      <option value="">Semua Status</option>
      <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
      <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
      <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
      <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>
    <button type="submit" class="btn btn-sm btn-navy px-4" style="border-radius:10px;">Filter</button>
    @if(request('status'))
    <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-navy px-3" style="border-radius:10px;">Reset</a>
    @endif
  </div>
</form>

<div class="d-flex flex-column gap-3">
  @forelse($reports as $report)
  <div class="ipb-card p-4" style="display:flex; gap:16px; align-items:flex-start;">
    <div style="width:44px; height:44px; border-radius:12px; background:rgba(192,118,106,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
      <i class="mdi mdi-alert-circle-outline" style="font-size:22px; color:#c0766a;"></i>
    </div>
    <div class="flex-1" style="min-width:0;">
      <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
        <div>
          <span style="font-size:13px; font-weight:700; color:var(--ipb-navy);">{{ $report->kategoriLabel }}</span>
          <span style="font-size:12px; color:#7a8fa0; margin-left:8px;">oleh {{ $report->reporter->name }}</span>
        </div>
        <span class="badge" style="border-radius:8px; font-size:11px; padding:4px 10px; flex-shrink:0;
          {{ $report->status === 'pending' ? 'background:rgba(212,164,90,0.15); color:#8a6020;' : ($report->status === 'diproses' ? 'background:rgba(86,124,141,0.12); color:#2e5566;' : 'background:rgba(90,154,120,0.12); color:#2d6a4f;') }}">
          {{ ucfirst($report->status) }}
        </span>
      </div>
      <p style="font-size:13px; color:#7a8fa0; margin:4px 0; line-height:1.6;">{{ Str::limit($report->deskripsi, 100) }}</p>
      <div style="font-size:11px; color:#7a8fa0;">{{ $report->created_at->diffForHumans() }}</div>
    </div>
    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-outline-navy" style="border-radius:8px; flex-shrink:0;">
      <i class="mdi mdi-eye-outline me-1"></i> Detail
    </a>
  </div>
  @empty
  <div class="text-center py-5" style="color:#7a8fa0;">
    <i class="mdi mdi-clipboard-check-outline" style="font-size:60px; color:rgba(46,65,86,0.1); display:block; margin-bottom:12px;"></i>
    Tidak ada laporan
  </div>
  @endforelse
</div>

@if($reports->hasPages())
<div class="d-flex justify-content-center mt-4">
  {{ $reports->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endif
@endsection
