@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', $item->nama)

@section('content')
<div class="container py-5">
  <div class="row g-4">

    {{-- ── Left: Photos + Info ── --}}
    <div class="col-lg-7">
      {{-- Photos Carousel --}}
      @if($item->foto && count($item->foto) > 0)
      <div id="itemCarousel" class="carousel slide" style="border-radius:20px; overflow:hidden; margin-bottom:24px;">
        <div class="carousel-inner">
          @foreach($item->fotoUrls() as $i => $fotoUrl)
          <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
            <img src="{{ $fotoUrl }}" class="d-block w-100" style="aspect-ratio:16/9; object-fit:cover;" alt="{{ $item->nama }}">
          </div>
          @endforeach
        </div>
        @if(count($item->foto) > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#itemCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#itemCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
        <div class="carousel-indicators" style="bottom:-30px; position:relative;">
          @foreach($item->foto as $i => $foto)
          <button type="button" data-bs-target="#itemCarousel" data-bs-slide-to="{{ $i }}" {{ $i === 0 ? 'class=active' : '' }} style="background:var(--ipb-navy); width:8px; height:8px; border-radius:50%;"></button>
          @endforeach
        </div>
        @endif
      </div>
      @else
      <div class="item-card-img-ph ph-{{ $item->kategori }}" style="border-radius:20px; margin-bottom:24px; min-height:280px;">
        <i class="mdi mdi-{{ $item->kategoriIcon }}" style="color:rgba(255,255,255,0.4); font-size:80px;"></i>
      </div>
      @endif

      {{-- Item Info --}}
      <div class="ipb-card p-4 mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
          <span style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; background:rgba(86,124,141,0.12); color:var(--ipb-slate); padding:4px 10px; border-radius:8px;">{{ ucfirst($item->kategori) }}</span>
          @if($item->rating_avg > 0)
            <span style="font-size:13px; color:#7a8fa0;"><i class="mdi mdi-star" style="color:var(--ipb-gold);"></i> {{ number_format($item->rating_avg, 1) }} ({{ $item->total_sewa }}x disewa)</span>
          @endif
        </div>
        <h2 style="font-size:26px; font-weight:800; color:var(--ipb-navy); margin-bottom:12px;">{{ $item->nama }}</h2>
        <p style="font-size:14px; color:#7a8fa0; line-height:1.8;">{{ $item->deskripsi }}</p>

        <hr style="border-color:rgba(46,65,86,0.08); margin:20px 0;">

        <div class="row g-3">
          <div class="col-6">
            <div style="font-size:11px; color:#7a8fa0; margin-bottom:4px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Harga/Hari</div>
            <div style="font-size:20px; font-weight:700; color:var(--ipb-navy);">Rp {{ number_format($item->harga_per_hari, 0, ',', '.') }}</div>
          </div>
          @if($item->deposit > 0)
          <div class="col-6">
            <div style="font-size:11px; color:#7a8fa0; margin-bottom:4px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Deposit</div>
            <div style="font-size:20px; font-weight:700; color:var(--ipb-navy);">Rp {{ number_format($item->deposit, 0, ',', '.') }}</div>
          </div>
          @endif
          <div class="col-6">
            <div style="font-size:11px; color:#7a8fa0; margin-bottom:4px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Stok</div>
            <div style="font-size:16px; font-weight:700; color:{{ $item->stok > 0 ? 'var(--ipb-green)' : '#c0766a' }};">{{ $item->stok > 0 ? $item->stok.' Unit Tersedia' : 'Habis' }}</div>
          </div>
          <div class="col-6">
            <div style="font-size:11px; color:#7a8fa0; margin-bottom:4px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Lokasi</div>
            <div style="font-size:14px; font-weight:600; color:var(--ipb-navy);">{{ $item->owner->lokasi ?: 'Kampus IPB' }}</div>
          </div>
        </div>
      </div>

      {{-- Owner Card --}}
      <div class="ipb-card p-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Pemilik</div>
        <div class="d-flex align-items-center gap-3">
          <img src="{{ $item->owner->avatarUrl }}" style="width:48px; height:48px; border-radius:14px; object-fit:cover;" alt="">
          <div class="flex-1">
            <div style="font-weight:700; color:var(--ipb-navy); font-size:15px;">{{ $item->owner->name }}</div>
            @if($item->owner->nim)<div style="font-size:12px; color:#7a8fa0;">NIM: {{ $item->owner->nim }}</div>@endif
          </div>
          @auth
          @if(auth()->id() !== $item->owner_id)
          <a href="{{ route('chat.index', ['with' => $item->owner_id]) }}" class="btn btn-outline-navy btn-sm">
            <i class="mdi mdi-chat-outline"></i> Chat
          </a>
          @endif
          @endauth
        </div>
      </div>
    </div>

    {{-- ── Right: Rental Form ── --}}
    <div class="col-lg-5">
      <div style="position:sticky; top:88px;">
        <div class="ipb-card p-4">
          <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:16px;">Ajukan Sewa</div>

          @auth
          @if(auth()->id() === $item->user_id)
            <div class="text-center py-4" style="color:#7a8fa0; font-size:14px;">
              <i class="mdi mdi-package-variant" style="font-size:40px; display:block; margin-bottom:10px;"></i>
              Ini adalah item milik Anda
            </div>
          @elseif($item->stok <= 0 || $item->status !== 'aktif')
            <div class="text-center py-4" style="color:#7a8fa0; font-size:14px;">
              <i class="mdi mdi-package-variant-closed-remove" style="font-size:40px; display:block; margin-bottom:10px;"></i>
              Item tidak tersedia saat ini
            </div>
          @else
          <form method="POST" action="{{ route('rentals.store') }}" id="rentalForm">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <div class="mb-3">
              <label class="form-label-ipb">Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" class="form-control form-control-ipb @error('tanggal_mulai') is-invalid @enderror"
                value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" required id="tglMulai">
              @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label-ipb">Tanggal Selesai</label>
              <input type="date" name="tanggal_selesai" class="form-control form-control-ipb @error('tanggal_selesai') is-invalid @enderror"
                value="{{ old('tanggal_selesai') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required id="tglSelesai">
              @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label-ipb">Catatan (opsional)</label>
              <textarea name="catatan" class="form-control form-control-ipb" rows="2" placeholder="Informasi tambahan...">{{ old('catatan') }}</textarea>
            </div>

            {{-- Price Breakdown --}}
            <div style="background:var(--ipb-cream); border-radius:12px; padding:14px 16px; margin-bottom:16px;" id="priceBreakdown" class="d-none">
              <div class="d-flex justify-content-between mb-1">
                <span style="font-size:13px; color:#7a8fa0;">Durasi</span>
                <span style="font-size:13px; font-weight:600; color:var(--ipb-navy);" id="durasiLabel">—</span>
              </div>
              <div class="d-flex justify-content-between mb-1">
                <span style="font-size:13px; color:#7a8fa0;">Harga sewa</span>
                <span style="font-size:13px; font-weight:600; color:var(--ipb-navy);" id="hargaSewaLabel">—</span>
              </div>
              @if($item->deposit > 0)
              <div class="d-flex justify-content-between mb-1">
                <span style="font-size:13px; color:#7a8fa0;">Deposit</span>
                <span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">Rp {{ number_format($item->deposit, 0, ',', '.') }}</span>
              </div>
              @endif
              <hr style="border-color:rgba(46,65,86,0.1); margin:8px 0;">
              <div class="d-flex justify-content-between">
                <span style="font-size:14px; font-weight:700; color:var(--ipb-navy);">Total</span>
                <span style="font-size:16px; font-weight:800; color:var(--ipb-navy);" id="totalLabel">—</span>
              </div>
            </div>

            <button type="submit" class="btn btn-navy w-100 py-3" style="border-radius:12px; font-weight:700; font-size:15px;">
              <i class="mdi mdi-send-outline me-1"></i> Ajukan Permintaan Sewa
            </button>
          </form>
          @endif
          @else
          <div class="text-center py-3">
            <p style="color:#7a8fa0; font-size:14px; margin-bottom:16px;">Login untuk mengajukan sewa</p>
            <a href="{{ route('login') }}" class="btn btn-navy px-5 py-2" style="border-radius:12px; font-weight:700;">Login</a>
          </div>
          @endauth
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mulai = document.getElementById('tglMulai');
  const selesai = document.getElementById('tglSelesai');
  const hargaPerHari = {{ $item->harga_per_hari }};
  const deposit = {{ $item->deposit }};

  function updatePrice() {
    if (!mulai || !selesai || !mulai.value || !selesai.value) return;
    const d1 = new Date(mulai.value), d2 = new Date(selesai.value);
    const diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
    if (diff <= 0) return;
    const sewa = diff * hargaPerHari;
    const total = sewa + deposit;
    document.getElementById('durasiLabel').textContent = diff + ' hari';
    document.getElementById('hargaSewaLabel').textContent = 'Rp ' + sewa.toLocaleString('id-ID');
    document.getElementById('totalLabel').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('priceBreakdown').classList.remove('d-none');
    if (selesai) selesai.min = mulai.value;
  }

  if (mulai) mulai.addEventListener('change', updatePrice);
  if (selesai) selesai.addEventListener('change', updatePrice);
});
</script>
@endpush
