@extends('layouts.admin')
@section('title', 'Verifikasi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 style="font-weight:800; color:var(--ipb-navy); margin:0;">Verifikasi Identitas</h4>
    <p style="color:#7a8fa0; font-size:14px; margin:4px 0 0;">{{ $verifications->total() }} pengajuan total</p>
  </div>
</div>

<form method="GET" action="{{ route('admin.verifications.index') }}" class="ipb-card p-3 mb-4">
  <div class="d-flex gap-2 flex-wrap">
    <select name="status" class="form-select form-select-sm" style="border-radius:10px; width:auto; font-size:13px;">
      <option value="">Semua Status</option>
      <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
      <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
      <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
    </select>
    <button type="submit" class="btn btn-sm btn-navy px-4" style="border-radius:10px;">Filter</button>
    @if(request('status'))
    <a href="{{ route('admin.verifications.index') }}" class="btn btn-sm btn-outline-navy px-3" style="border-radius:10px;">Reset</a>
    @endif
  </div>
</form>

<div class="row g-4">
  @forelse($verifications as $verif)
  <div class="col-md-6">
    <div class="ipb-card p-4">
      <div class="d-flex align-items-center gap-3 mb-3">
        <img src="{{ $verif->user->avatarUrl }}" style="width:44px; height:44px; border-radius:12px; object-fit:cover;" alt="">
        <div class="flex-1">
          <div style="font-weight:700; color:var(--ipb-navy); font-size:14px;">{{ $verif->user->name }}</div>
          <div style="font-size:12px; color:#7a8fa0;">{{ $verif->user->email }}</div>
        </div>
        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; padding:4px 10px; border-radius:8px;
          {{ $verif->status === 'verified' ? 'background:rgba(90,154,120,0.12); color:#2d6a4f;' : ($verif->status === 'pending' ? 'background:rgba(212,164,90,0.15); color:#8a6020;' : 'background:rgba(192,118,106,0.12); color:#8a3a30;') }}">
          {{ ucfirst($verif->status) }}
        </span>
      </div>

      <div class="mb-3">
        <div style="font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:8px;">Dokumen ({{ strtoupper($verif->tipe) }})</div>
        <a href="{{ Storage::url($verif->file) }}" target="_blank">
          <img src="{{ Storage::url($verif->file) }}" style="width:100%; height:160px; object-fit:cover; border-radius:12px; border:2px solid rgba(46,65,86,0.1);" alt="Dokumen">
        </a>
      </div>

      <div style="font-size:11px; color:#7a8fa0; margin-bottom:12px;">Dikirim {{ $verif->created_at->diffForHumans() }}</div>

      <form method="POST" action="{{ route('admin.verifications.update', $verif) }}" class="d-flex gap-2">
        @csrf @method('PATCH')
        <select name="status" class="form-select form-select-sm flex-1" style="border-radius:10px; font-size:13px; border-color:rgba(46,65,86,0.15);">
          <option value="pending" {{ $verif->status === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="verified" {{ $verif->status === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
          <option value="rejected" {{ $verif->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" class="btn btn-sm btn-navy px-3" style="border-radius:10px; font-weight:700;">
          Simpan
        </button>
      </form>
    </div>
  </div>
  @empty
  <div class="col-12 text-center py-5" style="color:#7a8fa0;">
    <i class="mdi mdi-shield-check-outline" style="font-size:60px; color:rgba(46,65,86,0.1); display:block; margin-bottom:12px;"></i>
    Tidak ada pengajuan verifikasi
  </div>
  @endforelse
</div>

@if($verifications->hasPages())
<div class="d-flex justify-content-center mt-4">
  {{ $verifications->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endif
@endsection
