@extends('layouts.admin')
@section('title', 'Detail Laporan')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-navy" style="border-radius:10px; width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center;">
    <i class="mdi mdi-arrow-left"></i>
  </a>
  <div class="flex-1">
    <h4 style="font-weight:800; color:var(--ipb-navy); margin:0;">Detail Laporan #{{ $report->id }}</h4>
  </div>
  <span class="badge" style="border-radius:10px; font-size:13px; padding:8px 14px;
    {{ $report->status === 'pending' ? 'background:rgba(212,164,90,0.15); color:#8a6020;' : ($report->status === 'diproses' ? 'background:rgba(86,124,141,0.12); color:#2e5566;' : 'background:rgba(90,154,120,0.12); color:#2d6a4f;') }}">
    {{ ucfirst($report->status) }}
  </span>
</div>

<div class="row g-4">
  <div class="col-md-8">
    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:6px;">Kategori</div>
      <h5 style="font-weight:700; color:var(--ipb-navy); margin-bottom:16px;">{{ $report->kategoriLabel }}</h5>
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:8px;">Deskripsi</div>
      <p style="font-size:14px; color:#7a8fa0; line-height:1.8; margin:0;">{{ $report->deskripsi }}</p>
    </div>

    @if($report->bukti && count($report->bukti) > 0)
    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Bukti</div>
      <div class="d-flex flex-wrap gap-3">
        @foreach($report->bukti as $file)
        <a href="{{ Storage::url($file) }}" target="_blank">
          <img src="{{ Storage::url($file) }}" style="width:120px; height:90px; object-fit:cover; border-radius:10px; border:2px solid rgba(46,65,86,0.1);" alt="">
        </a>
        @endforeach
      </div>
    </div>
    @endif

    {{-- ── Update Form ── --}}
    <div class="ipb-card p-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:16px;">Tindakan Admin</div>
      <form id="form-update-report" method="POST" action="{{ route('admin.reports.update', $report) }}">
        @csrf @method('PATCH')
        <div class="mb-3">
          <label class="form-label-ipb">Status</label>
          <select name="status" class="form-select form-control-ipb @error('status') is-invalid @enderror" required>
            <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="diproses" {{ $report->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="ditolak" {{ $report->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
          </select>
        </div>
        <div class="mb-4">
          <label class="form-label-ipb">Balasan / Catatan Admin</label>
          <textarea name="balasan_admin" class="form-control form-control-ipb" rows="4"
            placeholder="Tulis balasan untuk pelapor...">{{ old('balasan_admin', $report->balasan_admin) }}</textarea>
        </div>
        <button type="button" id="btn-simpan" class="btn btn-navy px-5 py-2" style="border-radius:12px; font-weight:700;">
    <i class="mdi mdi-check me-1"></i> Simpan
</button>
      </form>
    </div>
  </div>

  <div class="col-md-4">
    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Pelapor</div>
      <div class="d-flex align-items-center gap-3">
        <img src="{{ $report->reporter->avatarUrl }}" style="width:44px; height:44px; border-radius:12px; object-fit:cover;" alt="">
        <div>
          <div style="font-weight:700; color:var(--ipb-navy);">{{ $report->reporter->name }}</div>
          <div style="font-size:12px; color:#7a8fa0;">{{ $report->reporter->email }}</div>
        </div>
      </div>
    </div>

    @if($report->terlapor)
    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Terlapor</div>
      <div class="d-flex align-items-center gap-3">
        <img src="{{ $report->terlapor->avatarUrl }}" style="width:44px; height:44px; border-radius:12px; object-fit:cover;" alt="">
        <div>
          <div style="font-weight:700; color:var(--ipb-navy);">{{ $report->terlapor->name }}</div>
          <div style="font-size:12px; color:#7a8fa0;">{{ $report->terlapor->email }}</div>
        </div>
      </div>
    </div>
    @endif

    @if($report->rental)
    <div class="ipb-card p-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Rental Terkait</div>
      <a href="{{ route('admin.rentals.show', $report->rental) }}" style="font-size:13px; color:var(--ipb-slate); text-decoration:none; font-weight:600;">
        #{{ $report->rental_id }} — {{ $report->rental->item->nama }}
        <i class="mdi mdi-open-in-new ms-1"></i>
      </a>
    </div>
    @endif
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('btn-simpan').addEventListener('click', function () {
    const status        = document.querySelector('select[name="status"]').value;
    const isKlarifikasi = {{ $report->kategori === 'klarifikasi' ? 'true' : 'false' }};
    const terlapor      = "{{ $report->terlapor->name ?? 'User' }}";

    let title, text, icon, confirmColor;

    if (status === 'ditolak') {
        title        = 'Tolak Laporan?';
        text         = isKlarifikasi
                        ? `Klarifikasi ${terlapor} akan ditolak. User tidak akan bisa melakukan rental lagi!`
                        : 'Laporan ini akan ditolak.';
        icon         = 'warning';
        confirmColor = '#d33';
    } else if (status === 'selesai') {
        title        = 'Selesaikan Laporan?';
        text         = isKlarifikasi
                        ? `Klarifikasi ${terlapor} disetujui. User akan bisa melakukan rental kembali!`
                        : 'Laporan ini akan ditandai selesai.';
        icon         = 'question';
        confirmColor = '#2e7d32';
    } else {
        // status pending/diproses — langsung submit tanpa konfirmasi
        document.getElementById('form-update-report').submit();
        return;
    }

    Swal.fire({
        title,
        text,
        icon,
        showCancelButton  : true,
        confirmButtonColor: confirmColor,
        cancelButtonText  : 'Batal',
        confirmButtonText : 'Ya, Lanjutkan',
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById('form-update-report').submit();
        }
    });
});
</script>
@endsection
