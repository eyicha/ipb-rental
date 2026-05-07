@extends(auth()->check() ? 'layouts.app' : 'layouts.guest')
@section('title', 'Explore')

@push('styles')
<style>
/* ── Hero Banner ── */
.explore-hero {
  background: linear-gradient(167deg, var(--ipb-navy) 0%, var(--ipb-slate) 100%);
  padding: 60px 80px;
  display: flex; align-items: center; justify-content: space-between;
  gap: 40px; position: relative; overflow: hidden;
}
.explore-hero::before {
  content: ''; position: absolute;
  top: -40px; left: -60px;
  width: 320px; height: 320px; border-radius: 50%;
  border: 1px solid rgba(200,217,230,0.08);
}
.explore-hero::after {
  content: ''; position: absolute;
  right: -80px; bottom: 20px;
  width: 240px; height: 240px; border-radius: 50%;
  border: 1px solid rgba(200,217,230,0.06);
}
.explore-hero__headline {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 600; font-size: clamp(32px,4vw,56px);
  line-height: 1.05; margin-bottom: 16px;
}
.explore-hero__headline .solid { color: #fff; }
.explore-hero__headline .italic { font-weight: 700; font-style: italic; color: var(--ipb-sky); }
.explore-hero__sub {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 600; font-size: 15px; line-height: 1.7;
  color: rgba(234,230,224,0.9);
}
.hero-stat__number {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 300; font-size: 28px; line-height: 1; color: #fff;
}
.hero-stat__label {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 11px;
  color: rgba(200,217,230,0.6);
}
.hero-stat-divider {
  width: 1px; height: 40px;
  background: rgba(200,217,230,0.15);
}

/* ── Filter bar ── */
.explore-filter-bar {
  background: #fff;
  border-bottom: 1px solid rgba(46,65,86,0.07);
  padding: 14px 40px;
  display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
}
.cat-chip {
  padding: 7px 14px; border-radius: 20px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 12px;
  cursor: pointer; border: none; text-decoration: none;
  white-space: nowrap; transition: background 0.2s, color 0.2s;
}
.cat-chip-active { background: var(--ipb-navy); color: #fff; }
.cat-chip-inactive { background: var(--ipb-cream); color: #7a8fa0; }
.cat-chip-inactive:hover { background: var(--ipb-sky); color: #fff; }

/* ── Section header ── */
.section-header-line {
  display: flex; align-items: center; gap: 12px; margin-bottom: 20px;
}
.section-header-line__label {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 700; font-size: 11px; letter-spacing: 1.8px;
  color: var(--ipb-slate); white-space: nowrap;
}
.section-header-line__line {
  flex: 1; height: 1px;
  background: rgba(86,124,141,0.12);
}

/* ── Featured strip ── */
.featured-strip {
  display: grid; grid-template-columns: repeat(3,1fr);
  gap: 16px; margin-bottom: 40px;
}
.featured-card {
  position: relative; border-radius: 14px; overflow: hidden;
  aspect-ratio: 16/9; display: flex; flex-direction: column; justify-content: flex-end;
  cursor: pointer; text-decoration: none;
}
.featured-card__bg {
  position: absolute; inset: 0;
  width: 100%; height: 100%; object-fit: cover;
}
.featured-card__bg-ph {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
}
.featured-card__bg-ph--1 { background: linear-gradient(135deg,#3d6b82 0%,#2e4156 100%); }
.featured-card__bg-ph--2 { background: linear-gradient(135deg,#4a5568 0%,#2d3748 100%); }
.featured-card__bg-ph--3 { background: linear-gradient(135deg,#2c5f7a 0%,#1a3a4e 100%); }
.featured-card__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(0deg,rgba(46,65,86,0.78) 0%,rgba(46,65,86,0.1) 55%,rgba(46,65,86,0) 100%);
}
.featured-card__info {
  position: relative; z-index: 1; padding: 0 20px 16px;
}
.featured-card__category {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 9px; letter-spacing: 1.35px;
  color: rgba(200,217,230,0.7); text-transform: uppercase; margin-bottom: 4px;
}
.featured-card__name {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 400; font-size: 20px; color: #fff; line-height: 1.1; margin-bottom: 6px;
}
.featured-card__price {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 12px; color: rgba(200,217,230,0.8);
}
.featured-card__badge {
  position: absolute; top: 14px; right: 10px;
  padding: 4px 10px; border-radius: 20px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 10px; color: #fff; backdrop-filter: blur(4px);
}
.badge-popular { background: rgba(212,164,90,0.85); }
.badge-new     { background: rgba(86,124,141,0.85); }
.badge-avail   { background: rgba(90,154,120,0.85); }

@media (max-width:768px) {
  .explore-hero { padding: 40px 24px; flex-direction: column; }
  .featured-strip { grid-template-columns: 1fr; }
  .explore-filter-bar { padding: 12px 16px; }
}
</style>
@endpush

@section('content')
@php
  $catImgMap = [
    'fotografi'  => 'gimbal-dji.jpg',
    'elektronik' => 'proyektor.jpg',
    'audio'      => 'speaker-jbl.jpg',
    'drone'      => 'gimbal-dji.jpg',
    'akademik'   => 'kalkulator.jpg',
    'olahraga'   => 'sepeda-mtb.jpg',
    'perabot'    => 'tenda-camping.jpg',
    'kendaraan'  => 'sepeda-mtb.jpg',
    'lainnya'    => 'proyektor.jpg',
  ];
@endphp

{{-- ── Hero Banner ── --}}
<section class="explore-hero">
  <div style="flex:1; max-width:560px; position:relative; z-index:1;">
    <h1 class="explore-hero__headline">
      <span class="solid">Temukan item </span><span class="italic">terbaik</span><br>
      <span class="solid">untuk kebutuhanmu.</span>
    </h1>
    <p class="explore-hero__sub">
      Sewa kamera, laptop, proyektor, dan peralatan lainnya dari sesama<br>
      mahasiswa IPB. Mudah, terpercaya, terjangkau.
    </p>
  </div>
  <div style="display:flex; align-items:center; gap:28px; position:relative; z-index:1;">
    <div>
      <div class="hero-stat__number">{{ $items->total() }}+</div>
      <div class="hero-stat__label">Item Tersedia</div>
    </div>
    <div class="hero-stat-divider"></div>
    <div>
  <div class="hero-stat__number">{{ \App\Models\Rental::where('status','finished')->count() }}+</div>
  <div class="hero-stat__label">Transaksi Sukses</div>
</div>
<div class="hero-stat-divider"></div>
<div>
  @php
  $allRatings = \App\Models\Rental::whereNotNull('rating')->pluck('rating')->map(fn($r) => (int)$r);
  $avgRating = $allRatings->count() > 0 ? number_format($allRatings->avg(), 1) : '—';
@endphp
<div class="hero-stat__number">{{ $avgRating }}★</div>
<div class="hero-stat__label">Rating Platform</div>
</div>
  </div>
</section>

{{-- ── Filter Bar ── --}}
<div class="explore-filter-bar">
  <form method="GET" action="{{ route('explore') }}" class="d-flex align-items-center gap-3 flex-wrap flex-1">
    {{-- Search --}}
    <div style="flex:1; min-width:200px; display:flex; align-items:center; gap:10px; background:var(--ipb-cream); border:1px solid transparent; border-radius:10px; padding:9px 14px; transition:border-color 0.2s;"
         onfocusin="this.style.borderColor='var(--ipb-sky)'" onfocusout="this.style.borderColor='transparent'">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(86,124,141,0.5)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <input type="text" name="q" style="flex:1; border:none; background:transparent; outline:none; font-family:var(--font-body,'DM Sans',sans-serif); font-weight:600; font-size:13px; color:var(--ipb-navy);"
        placeholder="Cari kamera, laptop, proyektor..." value="{{ request('q') }}">
    </div>
    {{-- Category chips --}}
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <a href="{{ route('explore', array_merge(request()->except('kategori'), [])) }}"
        class="cat-chip {{ !request('kategori') ? 'cat-chip-active' : 'cat-chip-inactive' }}">Semua</a>
      @foreach(['elektronik','fotografi','audio','drone','akademik','olahraga','perabot','kendaraan','lainnya'] as $kat)
      <a href="{{ route('explore', array_merge(request()->except('kategori'), ['kategori' => $kat])) }}"
        class="cat-chip {{ request('kategori') === $kat ? 'cat-chip-active' : 'cat-chip-inactive' }}">
        {{ ucfirst($kat) }}
      </a>
      @endforeach
    </div>
    {{-- Sort --}}
    <div style="display:flex; align-items:center; gap:8px; margin-left:auto; flex-shrink:0;">
      <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:12px; color:#7a8fa0; white-space:nowrap;">Urutkan:</span>
      <select name="sort" style="appearance:none; background:var(--ipb-cream); border:1px solid transparent; border-radius:8px; padding:7px 28px 7px 12px; font-family:var(--font-body,'DM Sans',sans-serif); font-size:12px; color:var(--ipb-navy); cursor:pointer; outline:none;" onchange="this.form.submit()">
        <option value="popular" {{ request('sort','popular') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
        <option value="harga_asc" {{ request('sort') === 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
        <option value="harga_desc" {{ request('sort') === 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
        <option value="terbaru" {{ request('sort') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
      </select>
    </div>
  </form>
</div>

{{-- ── Main Content ── --}}
<div style="max-width:1200px; margin:0 auto; padding:40px 40px 60px;">

  {{-- Featured Strip (top 3 by total_sewa) --}}
  @if(!request('q') && !request('kategori'))
  @php $featured3 = \App\Models\Item::where('status','aktif')->with('owner')->orderByDesc('total_sewa')->limit(3)->get(); @endphp
  @if($featured3->count() > 0)
  <div class="section-header-line">
    <span class="section-header-line__label">ITEM UNGGULAN</span>
    <div class="section-header-line__line"></div>
  </div>
  <div class="featured-strip">
    @foreach($featured3 as $fi)
    <a href="{{ route('items.show', $fi) }}" class="featured-card">
      @if($fi->foto && count($fi->foto) > 0)
        <img src="{{ $fi->first_foto_url }}" alt="{{ $fi->nama }}" class="featured-card__bg"
           onerror="this.onerror=null; this.src='{{ asset('images/items/' . ($catImgMap[$fi->kategori] ?? 'proyektor.jpg')) }}'">
      @else
        {{-- Foto statis berdasarkan kategori --}}
        <img src="{{ asset('images/items/' . ($catImgMap[$fi->kategori] ?? 'proyektor.jpg')) }}"
             alt="{{ $fi->nama }}" class="featured-card__bg"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="featured-card__bg-ph featured-card__bg-ph--{{ $loop->index + 1 }}" style="display:none;">
          <i class="mdi mdi-{{ $fi->kategoriIcon }}" style="font-size:48px; color:rgba(255,255,255,0.3);"></i>
        </div>
      @endif
      <div class="featured-card__overlay"></div>
      <span class="featured-card__badge {{ $loop->first ? 'badge-popular' : ($loop->index === 1 ? 'badge-new' : 'badge-avail') }}">
        {{ $loop->first ? '🔥 Terpopuler' : ($loop->index === 1 ? '✨ Baru' : '✅ Tersedia') }}
      </span>
      <div class="featured-card__info">
        <p class="featured-card__category">{{ ucfirst($fi->kategori) }}</p>
        <p class="featured-card__name">{{ $fi->nama }}</p>
        <p class="featured-card__price">Rp {{ number_format($fi->harga_per_hari,0,',','.') }} / hari</p>
      </div>
    </a>
    @endforeach
  </div>
  @endif
  @endif

  {{-- Recommendations Section (for authenticated users) --}}
  @if(auth()->check() && $recommendations->count() > 0 && !request('q') && !request('kategori'))
  <div style="margin-bottom:60px;">
    <div class="section-header-line" style="margin-bottom:24px;">
      <span class="section-header-line__label">REKOMENDASI UNTUK ANDA</span>
      <div class="section-header-line__line"></div>
    </div>
    <div class="row g-4">
      @foreach($recommendations as $rec)
      <div class="col-sm-6 col-lg-4">
        <a href="{{ route('items.show', $rec) }}" class="text-decoration-none">
          <div class="item-card">
            <div class="item-card__img">
              @if($rec->foto && count($rec->foto) > 0)
                <img src="{{ $rec->first_foto_url }}" alt="{{ $rec->nama }}"
                   onerror="this.onerror=null; this.src='{{ asset('images/items/default.jpg') }}'">
              @else
                <div style="width:100%; height:100%; background:var(--ipb-slate); display:flex; align-items:center; justify-content:center;">
                  <i class="mdi mdi-image-off" style="font-size:48px; color:rgba(255,255,255,0.3);"></i>
                </div>
              @endif
            </div>
            <div class="item-card__body">
              <span class="item-card__cat">{{ ucfirst($rec->kategori) }}</span>
              <h3 class="item-card__name">{{ $rec->nama }}</h3>
              <div class="item-card__rating">
                @if($rec->rating_avg > 0)
                  <span style="color:var(--ipb-gold);">★ {{ number_format($rec->rating_avg, 1) }}</span>
                @else
                  <span style="color:#7a8fa0;">Belum ada rating</span>
                @endif
              </div>
              <p class="item-card__price">Rp {{ number_format($rec->harga_per_hari,0,',','.') }} / hari</p>
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Grid header --}}
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
    <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; color:#7a8fa0; margin:0;">
      Menampilkan <strong style="color:var(--ipb-navy);">{{ $items->total() }}</strong> item
      @if(request('q')) untuk "<strong>{{ request('q') }}</strong>"@endif
    </p>
    @if(request()->hasAny(['q','kategori','sort']))
    <a href="{{ route('explore') }}" style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:12px; color:var(--ipb-slate); text-decoration:none;">↺ Reset Filter</a>
    @endif
  </div>

  {{-- Item Grid ── --}}
  <div class="row g-4">
    @forelse($items as $item)
    <div class="col-sm-6 col-lg-4">
      <a href="{{ route('items.show', $item) }}" class="text-decoration-none">
        <div style="background:#fff; border-radius:14px; overflow:hidden; border:1px solid rgba(46,65,86,0.06); transition:box-shadow 0.2s, transform 0.2s; height:100%;" onmouseover="this.style.boxShadow='0 8px 24px rgba(46,65,86,0.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
          {{-- Image wrap --}}
          <div style="position:relative; aspect-ratio:16/9; overflow:hidden;">
            @if($item->foto && count($item->foto) > 0)
              <img src="{{ $item->first_foto_url }}" style="width:100%;height:100%;object-fit:cover;display:block;" alt="{{ $item->nama }}"
                   onerror="this.onerror=null; this.src='{{ asset('images/items/' . ($catImgMap[$item->kategori] ?? 'proyektor.jpg')) }}'">
            @else
              {{-- Foto statis berdasarkan kategori --}}
              <img src="{{ asset('images/items/' . ($catImgMap[$item->kategori] ?? 'proyektor.jpg')) }}"
                   style="width:100%;height:100%;object-fit:cover;display:block;" alt="{{ $item->nama }}"
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
              <div class="item-card-img-ph ph-{{ $item->kategori }}" style="aspect-ratio:16/9; display:none; align-items:center; justify-content:center;">
                <i class="mdi mdi-{{ $item->kategoriIcon }}" style="color:rgba(255,255,255,0.5); font-size:36px;"></i>
              </div>
            @endif
            {{-- Category badge --}}
            <span style="position:absolute; top:12px; left:12px; padding:4px 10px; border-radius:20px; background:rgba(255,255,255,0.8); backdrop-filter:blur(3px); font-family:var(--font-body,'DM Sans',sans-serif); font-weight:500; font-size:10px; letter-spacing:0.6px; color:var(--ipb-navy);">
              {{ ucfirst($item->kategori) }}
            </span>
          </div>
          {{-- Card info --}}
          <div style="padding:14px 18px 16px;">
            <p style="font-family:var(--font-display,'Cormorant Garamond',serif); font-weight:400; font-size:18px; color:var(--ipb-navy); line-height:1.2; margin-bottom:4px;">{{ $item->nama }}</p>
            <div style="display:flex; align-items:center; gap:6px; margin-bottom:10px;">
              <img src="{{ $item->owner->avatarUrl }}" style="width:18px; height:18px; border-radius:5px; object-fit:cover;" alt="">
              <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-weight:300; font-size:11px; color:#7a8fa0;">{{ $item->owner->name }}</span>
            </div>
            <div style="display:flex; align-items:center; justify-content:space-between;">
              <div>
                <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-weight:500; font-size:14px; color:var(--ipb-navy);">Rp {{ number_format($item->harga_per_hari, 0, ',', '.') }}</span>
                <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-weight:300; font-size:11px; color:#7a8fa0;"> / hari</span>
              </div>
              @if($item->rating_avg > 0)
              <div style="display:flex; align-items:center; gap:4px;">
                <i class="mdi mdi-star" style="color:var(--ipb-gold); font-size:13px;"></i>
                <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:11px; color:#7a8fa0;">{{ number_format($item->rating_avg,1) }}</span>
              </div>
              @endif
            </div>
          </div>
        </div>
      </a>
    </div>
    @empty
    <div class="col-12">
      <div class="text-center py-5">
        <i class="mdi mdi-package-variant-closed-remove" style="font-size:60px; color:rgba(46,65,86,0.15);"></i>
        <h5 style="color:#7a8fa0; margin-top:16px; font-weight:600; font-family:var(--font-display,'Cormorant Garamond',serif);">Tidak ada item ditemukan</h5>
        <p style="color:#7a8fa0; font-size:14px; font-family:var(--font-body,'DM Sans',sans-serif);">Coba kata kunci atau filter yang berbeda</p>
        <a href="{{ route('explore') }}" class="btn btn-outline-navy btn-sm mt-2" style="border-radius:20px; font-family:var(--font-body,'DM Sans',sans-serif);">Reset Filter</a>
      </div>
    </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if($items->hasPages())
  <div class="d-flex justify-content-center mt-5">
    {{ $items->withQueryString()->links('pagination::bootstrap-5') }}
  </div>
  @endif

</div>
@endsection
