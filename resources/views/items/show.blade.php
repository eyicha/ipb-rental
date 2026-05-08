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
          <button type="button" data-bs-target="#itemCarousel" data-bs-slide-to="{{ $i }}"
            {{ $i === 0 ? 'class=active' : '' }}
            style="background:var(--ipb-navy); width:8px; height:8px; border-radius:50%;"></button>
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
          <span style="font-size:13px; color:#7a8fa0;">
            <i class="mdi mdi-star" style="color:var(--ipb-gold);"></i>
            {{ number_format($item->rating_avg, 1) }} ({{ $item->total_sewa }}x disewa)
          </span>
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
          <div class="col-6">
            <div style="font-size:11px; color:#7a8fa0; margin-bottom:4px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">DP (50% dari sewa)</div>
            <div style="font-size:14px; font-weight:600; color:#c0766a;">Tidak dikembalikan</div>
          </div>
          <div class="col-6">
            <div style="font-size:11px; color:#7a8fa0; margin-bottom:4px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Stok</div>
            <div style="font-size:16px; font-weight:700; color:{{ $item->stok > 0 ? 'var(--ipb-green)' : '#c0766a' }};">
              {{ $item->stok > 0 ? $item->stok.' Unit Tersedia' : 'Habis' }}
            </div>
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
          <img src="{{ $item->owner->avatarUrl }}"
               style="width:48px; height:48px; border-radius:14px; object-fit:cover; cursor:pointer;"
               onclick="document.getElementById('ownerModal').style.display='flex'" alt="">
          <div class="flex-1">
            <div style="font-weight:700; color:var(--ipb-navy); font-size:15px; cursor:pointer;"
                 onclick="document.getElementById('ownerModal').style.display='flex'">
              {{ $item->owner->name }}
            </div>
            @if($item->owner->nim)
            <div style="font-size:12px; color:#7a8fa0;">NIM: {{ $item->owner->nim }}</div>
            @endif
            @php
              $ownerRating    = round($item->owner->rating_avg ?? 0, 1);
              $ownerTotalSewa = \App\Models\Rental::where('pemilik_id', $item->owner->id)->where('status','finished')->count();
            @endphp
            @if($ownerRating > 0)
            <div style="font-size:12px; color:#7a8fa0;">
              <i class="mdi mdi-star" style="color:var(--ipb-gold);"></i> {{ $ownerRating }} · {{ $ownerTotalSewa }}x transaksi
            </div>
            @endif
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

      {{-- Owner Popup Modal --}}
      <div id="ownerModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:9999; align-items:center; justify-content:center;"
           onclick="if(event.target===this)this.style.display='none'">
        <div style="background:#fff; border-radius:20px; padding:32px; width:min(420px,90vw); box-shadow:0 20px 60px rgba(0,0,0,.2);">
          <div class="d-flex align-items-center gap-3 mb-4">
            <img src="{{ $item->owner->avatarUrl }}" style="width:64px; height:64px; border-radius:16px; object-fit:cover;" alt="">
            <div>
              <div style="font-weight:700; color:var(--ipb-navy); font-size:18px;">{{ $item->owner->name }}</div>
              @if($item->owner->nim)<div style="font-size:13px; color:#7a8fa0;">NIM: {{ $item->owner->nim }}</div>@endif
              @if($item->owner->lokasi)<div style="font-size:13px; color:#7a8fa0;"><i class="mdi mdi-map-marker-outline"></i> {{ $item->owner->lokasi }}</div>@endif
            </div>
          </div>
          <div class="d-flex gap-3 mb-4">
            <div style="flex:1; text-align:center; background:#f7f7f5; border-radius:12px; padding:12px;">
              <div style="font-size:22px; font-weight:700; color:var(--ipb-navy);">{{ $ownerRating > 0 ? $ownerRating : '—' }}</div>
              <div style="font-size:11px; color:#7a8fa0; margin-top:2px;">Rating</div>
              @if($ownerRating > 0)
              <div style="margin-top:4px;">
                @for($i=1; $i<=5; $i++)
                <i class="mdi mdi-star{{ $i <= $ownerRating ? '' : ($i - $ownerRating < 1 ? '-half-full' : '-outline') }}"
                   style="color:var(--ipb-gold); font-size:13px;"></i>
                @endfor
              </div>
              @endif
            </div>
            <div style="flex:1; text-align:center; background:#f7f7f5; border-radius:12px; padding:12px;">
              <div style="font-size:22px; font-weight:700; color:var(--ipb-navy);">{{ $ownerTotalSewa }}</div>
              <div style="font-size:11px; color:#7a8fa0; margin-top:2px;">Transaksi Selesai</div>
            </div>
            <div style="flex:1; text-align:center; background:#f7f7f5; border-radius:12px; padding:12px;">
              <div style="font-size:22px; font-weight:700; color:var(--ipb-navy);">{{ $item->owner->items()->where('status','aktif')->count() }}</div>
              <div style="font-size:11px; color:#7a8fa0; margin-top:2px;">Item Aktif</div>
            </div>
          </div>
          @php
            $reviews = \App\Models\Rental::where('pemilik_id', $item->owner->id)
              ->whereNotNull('rating')->whereNotNull('ulasan')->latest()->take(3)->get();
          @endphp
          @if($reviews->count() > 0)
          <div style="margin-bottom:16px;">
            <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:10px;">Ulasan Terbaru</div>
            @foreach($reviews as $review)
            <div style="padding:10px 14px; background:#f7f7f5; border-radius:10px; margin-bottom:8px;">
              <div class="d-flex align-items-center gap-1 mb-1">
                @for($i=1; $i<=5; $i++)
                <i class="mdi mdi-star{{ $i <= $review->rating ? '' : '-outline' }}" style="color:var(--ipb-gold); font-size:13px;"></i>
                @endfor
                <span style="font-size:11px; color:#7a8fa0; margin-left:4px;">{{ $review->penyewa->name }}</span>
              </div>
              <div style="font-size:13px; color:#7a8fa0;">{{ $review->ulasan }}</div>
            </div>
            @endforeach
          </div>
          @endif
          <div style="display:flex; gap:10px; margin-top:8px;">
            @auth
            @if((string)auth()->id() !== (string)$item->owner_id)
            <a href="{{ route('chat.index', ['with' => $item->owner_id]) }}"
               style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:13px; background:var(--ipb-navy); color:#fff; border-radius:12px; font-weight:600; font-size:14px; text-decoration:none;">
              <i class="mdi mdi-chat-outline"></i> Chat
            </a>
            @endif
            @endauth
            <button onclick="document.getElementById('ownerModal').style.display='none'"
              style="flex:1; padding:13px; border-radius:12px; border:1px solid rgba(46,65,86,0.1); background:#fff; font-size:14px; color:#7a8fa0; cursor:pointer;">
              Tutup
            </button>
          </div>
        </div>
      </div>

    </div>{{-- end col-lg-7 --}}

    {{-- ── Right: Rental Form ── --}}
    <div class="col-lg-5">
      <div class="ipb-card p-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:16px;">Ajukan Sewa</div>

        @auth
        @if(auth()->id() === $item->user_id)
        <div class="text-center py-5" style="color:#7a8fa0;">
          <i class="mdi mdi-package-variant" style="font-size:48px; display:block; margin-bottom:12px; color:rgba(46,65,86,0.2);"></i>
          <div style="font-size:14px; font-weight:600; color:var(--ipb-navy); margin-bottom:6px;">Item Milik Anda</div>
          <div style="font-size:13px; color:#7a8fa0;">Kelola item ini di halaman My Items</div>
          <a href="{{ route('my-items.index') }}" class="btn btn-outline-navy btn-sm mt-3" style="border-radius:10px;">
            <i class="mdi mdi-cog-outline me-1"></i> Kelola Item
          </a>
        </div>

        @elseif($item->stok <= 0 || $item->status !== 'aktif')
        <div class="text-center py-4" style="color:#7a8fa0; font-size:14px;">
          <i class="mdi mdi-package-variant-closed-remove" style="font-size:40px; display:block; margin-bottom:10px;"></i>
          Item tidak tersedia saat ini
        </div>

        @else
        @php
          $isBlocked   = auth()->user()->is_blocked ?? false;
          $ktmVerified = auth()->user()
            ->verifications()
            ->where('tipe', 'ktm')
            ->where('status', 'verified')
            ->exists();
        @endphp

        @if($isBlocked)
        <div class="text-center py-4" style="background:rgba(192,118,106,0.06); border:1px solid rgba(192,118,106,0.2); border-radius:14px; padding:20px;">
          <i class="mdi mdi-account-cancel-outline" style="font-size:48px; color:#c0766a; display:block; margin-bottom:10px;"></i>
          <div style="font-size:15px; font-weight:700; color:#8a3a30; margin-bottom:8px;">Akun Anda Diblokir</div>
          <div style="font-size:13px; color:#7a8fa0; line-height:1.7; margin-bottom:16px;">
            Kamu tidak dapat menyewa karena terlambat mengembalikan barang sebelumnya.<br>
            Hubungi admin melalui laman laporan untuk klarifikasi.
          </div>
          <a href="{{ route('report.index') }}" class="btn btn-sm w-100"
             style="border-radius:8px; background:var(--ipb-navy); color:#fff; font-weight:600; padding:10px;">
            <i class="mdi mdi-flag-outline me-1"></i> Buat Laporan Klarifikasi
          </a>
        </div>

        @elseif(!$ktmVerified)
        <div class="text-center py-3 mb-3" style="background:rgba(192,118,106,0.06); border:1px solid rgba(192,118,106,0.2); border-radius:14px; padding:20px;">
          <i class="mdi mdi-shield-alert-outline" style="font-size:40px; color:#c0766a; display:block; margin-bottom:10px;"></i>
          <div style="font-size:14px; font-weight:700; color:#8a3a30; margin-bottom:6px;">Verifikasi KTM Diperlukan</div>
          <div style="font-size:12px; color:#7a8fa0; line-height:1.6; margin-bottom:14px;">
            Kamu harus mengupload dan menunggu verifikasi KTM sebelum bisa menyewa barang.
          </div>
          @php $ktmRecord = auth()->user()->verifications()->where('tipe','ktm')->latest()->first(); @endphp
          @if($ktmRecord && $ktmRecord->status === 'pending')
          <div style="font-size:12px; font-weight:600; color:#b8860b; background:rgba(230,180,50,0.1); padding:8px 14px; border-radius:8px;">
            <i class="mdi mdi-timer-sand me-1"></i> KTM sedang diverifikasi admin
          </div>
          @elseif($ktmRecord && $ktmRecord->status === 'rejected')
          <div style="font-size:12px; font-weight:600; color:#8a3a30; background:rgba(192,118,106,0.1); padding:8px 14px; border-radius:8px; margin-bottom:10px;">
            <i class="mdi mdi-close-circle me-1"></i> KTM ditolak, upload ulang
          </div>
          <a href="{{ route('profile.index') }}" class="btn btn-sm w-100" style="border-radius:8px; background:var(--ipb-navy); color:#fff; font-weight:600;">
            <i class="mdi mdi-upload me-1"></i> Upload KTM Ulang
          </a>
          @else
          <a href="{{ route('profile.index') }}" class="btn btn-sm w-100" style="border-radius:8px; background:var(--ipb-navy); color:#fff; font-weight:600;">
            <i class="mdi mdi-upload me-1"></i> Upload KTM Sekarang
          </a>
          @endif
        </div>

        @else
        <form method="POST" action="{{ route('rentals.store') }}" id="rentalForm">
          @csrf
          <input type="hidden" name="item_id" value="{{ $item->id }}">
          <div class="mb-3">
            <label class="form-label-ipb">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tglMulai"
              class="form-control form-control-ipb @error('tanggal_mulai') is-invalid @enderror"
              value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}" required>
            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label-ipb">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tglSelesai"
              class="form-control form-control-ipb @error('tanggal_selesai') is-invalid @enderror"
              value="{{ old('tanggal_selesai') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label-ipb">Catatan (opsional)</label>
            <textarea name="catatan" class="form-control form-control-ipb" rows="2"
              placeholder="Informasi tambahan...">{{ old('catatan') }}</textarea>
          </div>

          <div style="background:var(--ipb-cream); border-radius:12px; padding:14px 16px; margin-bottom:16px;" id="priceBreakdown" class="d-none">
            <div class="d-flex justify-content-between mb-1">
              <span style="font-size:13px; color:#7a8fa0;">Durasi</span>
              <span style="font-size:13px; font-weight:600; color:var(--ipb-navy);" id="durasiLabel">—</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span style="font-size:13px; color:#7a8fa0;">Harga sewa</span>
              <span style="font-size:13px; font-weight:600; color:var(--ipb-navy);" id="hargaSewaLabel">—</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span style="font-size:13px; color:#7a8fa0;">
                DP (50% dari sewa)
                <span style="font-size:11px; color:#c0766a; font-weight:600;">(tidak dikembalikan)</span>
              </span>
              <span style="font-size:13px; font-weight:600; color:var(--ipb-navy);" id="depositLabel">—</span>
            </div>
            <hr style="border-color:rgba(46,65,86,0.1); margin:8px 0;">
            <div class="d-flex justify-content-between">
              <span style="font-size:14px; font-weight:700; color:var(--ipb-navy);">Total Bayar</span>
              <span style="font-size:16px; font-weight:800; color:var(--ipb-navy);" id="totalLabel">—</span>
            </div>
            <div style="font-size:11px; color:#7a8fa0; margin-top:6px; line-height:1.5;">
              * DP dibayar di muka dan <strong style="color:#c0766a;">tidak dikembalikan</strong>.
              Sisa pelunasan dibayar saat barang diterima.
            </div>
          </div>

          <button type="submit" class="btn btn-navy w-100 py-3" style="border-radius:12px; font-weight:700; font-size:15px;">
            <i class="mdi mdi-send-outline me-1"></i> Ajukan Permintaan Sewa
          </button>
        </form>
        @endif {{-- end isBlocked/ktmVerified --}}
        @endif {{-- end stok/status --}}

        @else
        <div class="text-center py-3">
          <p style="color:#7a8fa0; font-size:14px; margin-bottom:16px;">Login untuk mengajukan sewa</p>
          <a href="{{ route('login') }}" class="btn btn-navy px-5 py-2" style="border-radius:12px; font-weight:700;">Login</a>
        </div>
        @endauth
      </div>
    </div>{{-- end col-lg-5 --}}

  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mulai       = document.getElementById('tglMulai');
  const selesai     = document.getElementById('tglSelesai');
  const hargaPerHari = {{ $item->harga_per_hari }};

  function updatePrice() {
    if (!mulai || !selesai || !mulai.value || !selesai.value) return;

    const d1   = new Date(mulai.value);
    const d2   = new Date(selesai.value);
    const diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
    
    // Validasi: tanggal selesai harus setelah tanggal mulai
    if (diff <= 0) {
        selesai.setCustomValidity('Tanggal selesai harus setelah tanggal mulai');
        document.getElementById('priceBreakdown').classList.add('d-none');
        return;
    }
    selesai.setCustomValidity('');

    const sewa  = diff * hargaPerHari;   // total harga sewa
    const dp    = Math.round(sewa * 0.5); // DP = 50% dari sewa (tidak dikembalikan)
    const total = sewa;                   // ✅ total = harga sewa saja, bukan sewa + dp

    document.getElementById('durasiLabel').textContent    = diff + ' hari';
    document.getElementById('hargaSewaLabel').textContent = 'Rp ' + sewa.toLocaleString('id-ID');
    document.getElementById('depositLabel').textContent   = 'Rp ' + dp.toLocaleString('id-ID');
    document.getElementById('totalLabel').textContent     = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('priceBreakdown').classList.remove('d-none');

    // Update min tanggal selesai setiap kali tanggal mulai berubah
    const nextDay = new Date(d1);
    nextDay.setDate(nextDay.getDate() + 1);
    selesai.min = nextDay.toISOString().split('T')[0];
}

  if (mulai) mulai.addEventListener('change', updatePrice);
  if (selesai) selesai.addEventListener('change', updatePrice);
}); 
</script>
@endpush