@extends('layouts.admin')
@section('title', 'Detail Rental')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="{{ route('admin.rentals.index') }}" class="btn btn-sm btn-outline-navy" style="border-radius:10px; width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center;">
    <i class="mdi mdi-arrow-left"></i>
  </a>
  <div class="flex-1">
    <h4 style="font-weight:800; color:var(--ipb-navy); margin:0;">Detail Rental #{{ $rental->id }}</h4>
  </div>
  <span class="badge {{ $rental->statusBadge }}" style="border-radius:10px; font-size:13px; padding:8px 14px;">{{ $rental->statusLabel }}</span>
</div>

<div class="row g-4">
  <div class="col-md-7">
    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Item</div>
      <div class="d-flex gap-3">
        <div style="width:72px; height:72px; border-radius:14px; overflow:hidden; flex-shrink:0;">
          <img src="{{ $rental->item->first_foto_url }}" style="width:100%;height:100%;object-fit:cover;" alt=""
               onerror="this.onerror=null; this.parentElement.style.background='var(--ipb-slate)'">
        </div>
        <div>
          <h6 style="font-weight:700; color:var(--ipb-navy); margin-bottom:4px;">{{ $rental->item->nama }}</h6>
          <div style="font-size:13px; color:#7a8fa0;">{{ ucfirst($rental->item->kategori) }}</div>
          <div style="font-size:13px; font-weight:600; color:var(--ipb-navy);">Rp {{ number_format($rental->item->harga_per_hari, 0, ',', '.') }}/hari</div>
        </div>
      </div>
    </div>

    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Rincian Transaksi</div>
      <div class="d-flex flex-column gap-2">
        <div class="d-flex justify-content-between"><span style="font-size:13px; color:#7a8fa0;">Tanggal Mulai</span><span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">{{ \Carbon\Carbon::parse($rental->tanggal_mulai)->format('d M Y') }}</span></div>
        <div class="d-flex justify-content-between"><span style="font-size:13px; color:#7a8fa0;">Tanggal Selesai</span><span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">{{ \Carbon\Carbon::parse($rental->tanggal_selesai)->format('d M Y') }}</span></div>
        <div class="d-flex justify-content-between"><span style="font-size:13px; color:#7a8fa0;">Durasi</span><span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">{{ $rental->durasi_hari }} hari</span></div>
        <hr style="border-color:rgba(46,65,86,0.08); margin:4px 0;">
        <div class="d-flex justify-content-between"><span style="font-size:14px; font-weight:700; color:var(--ipb-navy);">Total</span><span style="font-size:16px; font-weight:800; color:var(--ipb-navy);">Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</span></div>
      </div>
    </div>

    @if($rental->bukti_dp)
    <div class="ipb-card p-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:10px;">Bukti DP</div>
      <img src="{{ Storage::url($rental->bukti_dp) }}" style="width:100%; border-radius:12px;" alt="Bukti DP">
    </div>
    @endif
  </div>

  <div class="col-md-5">
    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Penyewa</div>
      <div class="d-flex align-items-center gap-3">
        <img src="{{ $rental->penyewa->avatarUrl }}" style="width:44px; height:44px; border-radius:12px; object-fit:cover;" alt="">
        <div>
          <div style="font-weight:700; color:var(--ipb-navy);">{{ $rental->penyewa->name }}</div>
          <div style="font-size:12px; color:#7a8fa0;">{{ $rental->penyewa->email }}</div>
          @if($rental->penyewa->nim)<div style="font-size:12px; color:#7a8fa0;">{{ $rental->penyewa->nim }}</div>@endif
        </div>
      </div>
    </div>

    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Pemilik</div>
      <div class="d-flex align-items-center gap-3">
        <img src="{{ $rental->pemilik->avatarUrl }}" style="width:44px; height:44px; border-radius:12px; object-fit:cover;" alt="">
        <div>
          <div style="font-weight:700; color:var(--ipb-navy);">{{ $rental->pemilik->name }}</div>
          <div style="font-size:12px; color:#7a8fa0;">{{ $rental->pemilik->email }}</div>
        </div>
      </div>
    </div>

    @if($rental->catatan)
    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:10px;">Catatan</div>
      <p style="font-size:14px; color:#7a8fa0; margin:0; line-height:1.7;">{{ $rental->catatan }}</p>
    </div>
    @endif

    @if($rental->status === 'finished' && $rental->rating)
    <div class="ipb-card p-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:10px;">Rating</div>
      <div class="d-flex gap-1 mb-2">
        @for($i=1;$i<=5;$i++)
        <i class="mdi mdi-star{{ $i <= $rental->rating ? '' : '-outline' }}" style="color:var(--ipb-gold); font-size:18px;"></i>
        @endfor
      </div>
      @if($rental->ulasan)<p style="font-size:13px; color:#7a8fa0; margin:0; line-height:1.6;">{{ $rental->ulasan }}</p>@endif
    </div>
    @endif
  </div>
</div>
@endsection
