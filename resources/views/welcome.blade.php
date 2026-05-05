@extends('layouts.guest')
@section('title', 'Beranda')

@push('styles')
<style>
/* ── Hero ── */
.hero-home {
  background: var(--ipb-cream, #f5efeb);
  padding: 80px 40px 60px;
}
.hero-home__eyebrow {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 13px;
  letter-spacing: 2px; color: var(--ipb-slate);
  margin-bottom: 20px;
}
.hero-home__headline {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-size: clamp(60px, 7vw, 96px);
  line-height: 1.05; margin-bottom: 28px;
}
.hero-home__headline .solid { font-weight: 600; color: var(--ipb-navy); display: block; }
.hero-home__headline .italic { font-weight: 700; font-style: italic; color: var(--ipb-slate); display: block; }
.hero-home__sub {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 18px; line-height: 1.75;
  color: var(--ipb-slate); margin-bottom: 48px; max-width: 480px;
}
.btn-hero-explore {
  display: inline-flex; align-items: center; gap: 12px;
  padding: 16px 44px;
  border: 1.5px solid var(--ipb-navy); border-radius: 100px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 15px; letter-spacing: 1px;
  color: var(--ipb-navy); background: none; text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.btn-hero-explore:hover { background: var(--ipb-navy); color: #fff; }
.hero-badge {
  background: rgba(255,255,255,0.9); backdrop-filter: blur(8px);
  border-radius: 12px; padding: 14px 20px;
}
.hero-badge-num {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 600; font-size: 28px; line-height: 1; color: var(--ipb-navy);
}
.hero-badge-lbl {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 11px; letter-spacing: 0.7px; color: var(--ipb-slate); margin-top: 4px;
}
/* Scroll indicator */
.scroll-indicator {
  display: flex; flex-direction: column; align-items: center; gap: 14px;
  padding-top: 48px;
}
.scroll-indicator__lbl {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 10px; letter-spacing: 2.4px; color: var(--ipb-slate);
}
.scroll-indicator__line {
  width: 2px; height: 60px;
  background: linear-gradient(180deg, var(--ipb-slate) 0%, transparent 100%);
}

/* ── Philosophy ── */
.philosophy {
  background: #fff; padding: 120px 40px;
}
.section-badge {
  display: inline-flex; align-items: center; justify-content: center;
  padding-bottom: 14px; border-bottom: 1px solid var(--ipb-sky);
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 12px;
  letter-spacing: 2.4px; color: var(--ipb-slate); text-transform: uppercase;
}
.philosophy__quote {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 700; font-size: clamp(36px, 5vw, 64px);
  line-height: 1.15; text-align: center;
}
.philosophy__quote .navy { color: var(--ipb-navy); }
.philosophy__quote .slate { color: var(--ipb-slate); }
.philosophy__body {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 17px; line-height: 1.9;
  color: var(--ipb-slate); text-align: center; max-width: 660px;
}
.divider-slim { width: 72px; height: 1px; background: var(--ipb-sky); }

/* ── Featured ── */
.featured-title {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 600; font-size: 44px; line-height: 1.15; color: var(--ipb-navy);
}

/* ── How It Works 5 steps ── */
.steps-5 {
  position: relative;
  display: flex; align-items: flex-start; justify-content: center;
}
.steps-5::before {
  content: '';
  position: absolute;
  top: 29px;
  left: calc(10% + 30px); right: calc(10% + 30px);
  height: 1px;
  background: var(--ipb-sky);
}
.step-item {
  display: flex; flex-direction: column; align-items: center;
  text-align: center; flex: 1; padding: 0 8px; gap: 14px;
}
.step-circle {
  width: 58px; height: 58px;
  background: #fff; border: 1px solid var(--ipb-sky);
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  position: relative; flex-shrink: 0;
}
.step-circle i { font-size: 22px; color: var(--ipb-slate); }
.step-num {
  position: absolute; top: -4px; right: -4px;
  width: 20px; height: 20px; background: var(--ipb-slate);
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 9px; color: #fff;
}
.step-name {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 500; font-size: 16px; color: var(--ipb-navy);
}
.step-desc {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 12px; line-height: 1.7; color: var(--ipb-slate);
}

/* ── Stats section ── */
.stats-section { 
  background: var(--ipb-sky); 
  padding: 80px 40px; 
  display: flex;
  align-items: center;
  justify-content: center;
}
.stats-section > div {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.stats-title {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 700; font-size: 28px; letter-spacing: 2px;
  color: var(--ipb-slate); text-align: center; text-transform: uppercase;
}
.stat-block { display: flex; flex-direction: column; align-items: center; gap: 12px; padding: 40px 20px; }
.stat-num-lg {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 500; font-size: 72px; line-height: 1; color: var(--ipb-navy); text-align: center;
}
.stat-lbl-lg {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 11px; letter-spacing: 1.2px; color: var(--ipb-slate);
  text-transform: uppercase; text-align: center;
}
.stat-divider { width: 1px; background: rgba(86,124,141,0.2); align-self: stretch; }

/* ── CTA ── */
.cta-headline {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-size: 44px; line-height: 1.25; color: #fff; margin-bottom: 12px;
}
.cta-headline em { font-style: italic; font-weight: 600; color: var(--ipb-cream); }
.btn-cta {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 13px 36px;
  border: 1px solid var(--ipb-slate); border-radius: 100px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 13px; letter-spacing: 0.7px;
  color: #fff; background: none; text-decoration: none;
  transition: background 0.2s;
}
.btn-cta:hover { background: var(--ipb-slate); color: #fff; }
</style>
@endpush

@section('content')
@php
  /* Mapping kategori → foto statis di public/images/items/ */
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

{{-- ── Hero ── --}}
<section class="hero-home">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <p class="hero-home__eyebrow">IPB RENTAL — EST. 2026</p>
        <h1 class="hero-home__headline">
          <span class="solid">Rent With</span>
          <span class="italic">Intention.</span>
        </h1>
        <p class="hero-home__sub">Pengalaman sewa kampus yang terpercaya — dirancang untuk kepercayaan, kemudahan, dan kejelasan.</p>
        <a href="{{ route('explore') }}" class="btn-hero-explore">
          Jelajahi Barang
          <svg width="18" height="14" viewBox="0 0 18 14" fill="none" aria-hidden="true">
            <path d="M1 7h16M10 1l7 6-7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      </div>
      <div class="col-lg-6 d-none d-lg-block">
        @php
          /* Ambil item dari DB, fallback ke foto statis jika tidak ada */
          $heroItems = \App\Models\Item::where('status','aktif')->latest()->limit(4)->get();
          $staticFallbacks = array_values($catImgMap); /* dari @php di atas */
          $heroPhotos = [];
          $staticIdx  = 0;
          foreach ($heroItems as $hi) {
              if (!empty($hi->foto) && is_array($hi->foto) && count($hi->foto) > 0) {
                  $heroPhotos[] = ['src' => $hi->first_foto_url, 'alt' => $hi->nama];
              } else {
                  $heroPhotos[] = ['src' => asset('images/items/' . ($catImgMap[$hi->kategori] ?? 'proyektor.jpg')), 'alt' => $hi->nama];
              }
          }
          /* Selalu tampilkan 4 kotak — isi yang kosong dari foto statis */
          $allStatics = ['gimbal-dji.jpg','speaker-jbl.jpg','proyektor.jpg','sepeda-mtb.jpg','kalkulator.jpg','tenda-camping.jpg'];
          while (count($heroPhotos) < 4) {
              $heroPhotos[] = ['src' => asset('images/items/' . $allStatics[$staticIdx % count($allStatics)]), 'alt' => ''];
              $staticIdx++;
          }
        @endphp
        <div style="position:relative; border-radius:20px; overflow:hidden; box-shadow:0 24px 60px rgba(46,65,86,0.15); aspect-ratio:4/5;">
          {{-- 2×2 photo collage --}}
          <div style="display:grid; grid-template-columns:1fr 1fr; grid-template-rows:1fr 1fr; height:100%; gap:4px; background:var(--ipb-navy);">
            @foreach($heroPhotos as $hp)
            <div style="overflow:hidden; position:relative;">
              <img src="{{ $hp['src'] }}" alt="{{ $hp['alt'] }}"
                   style="width:100%; height:100%; object-fit:cover; display:block; transition:transform .4s;"
                   onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'"
                   onerror="this.src='{{ asset('images/items/proyektor.jpg') }}'">
            </div>
            @endforeach
          </div>
          {{-- Overlay gradient supaya badge terbaca --}}
          <div style="position:absolute; inset:0; background:linear-gradient(180deg,rgba(46,65,86,0) 50%,rgba(46,65,86,0.65) 100%); pointer-events:none;"></div>
          {{-- Badge --}}
          <div class="hero-badge" style="position:absolute; left:24px; bottom:24px;">
            <div class="hero-badge-num">{{ $stats['total_items'] }}+</div>
            <div class="hero-badge-lbl">Verified Items</div>
          </div>
        </div>
      </div>
    </div>
    <div class="scroll-indicator d-none d-lg-flex">
      <span class="scroll-indicator__lbl">SCROLL</span>
      <div class="scroll-indicator__line"></div>
    </div>
  </div>
</section>

{{-- ── Philosophy ── --}}
<section class="philosophy">
  <div style="max-width:900px; margin:0 auto; display:flex; flex-direction:column; align-items:center; gap:36px;">
    <p class="section-badge">OUR PHILOSOPHY</p>
    <blockquote class="philosophy__quote" style="margin:0;">
      <span class="navy">"Kepemilikan itu Sementara.<br></span>
      <span class="slate">Pengalaman adalah Segalanya.</span>
      <span class="navy">"</span>
    </blockquote>
    <p class="philosophy__body">
      IPB Rental adalah sistem sewa kampus terverifikasi yang dibangun atas prinsip bahwa akses
      lebih penting dari kepemilikan. Kami menghubungkan mahasiswa dan komunitas kampus melalui
      platform terkurasi dan terpercaya — setiap item terverifikasi, setiap transaksi terlindungi.
    </p>
    <div class="divider-slim"></div>
  </div>
</section>

{{-- ── Featured Items ── --}}
<section style="background:#fff; padding:80px 40px 120px;">
  <div style="max-width:1200px; margin:0 auto;">
    <div class="d-flex align-items-end justify-content-between mb-5">
      <h2 class="featured-title">Item Pilihan<br>Minggu Ini</h2>
      <a href="{{ route('explore') }}" style="display:flex; align-items:center; gap:8px; font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; letter-spacing:0.8px; color:var(--ipb-slate); text-decoration:none;">
        Lihat Semua Item
        <svg width="14" height="10" viewBox="0 0 14 10" fill="none" aria-hidden="true">
          <path d="M1 5h12M8 1l5 4-5 4" stroke="#567C8D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
    </div>
    <div class="row g-4">
      @forelse($featuredItems as $item)
      <div class="col-sm-6 col-lg-4">
        <a href="{{ route('items.show', $item) }}" class="text-decoration-none">
          <div class="ipb-card h-100" style="cursor:pointer; transition:box-shadow 0.2s, transform 0.2s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(46,65,86,0.12)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
            <img src="{{ $item->first_foto_url }}" class="item-card-img" alt="{{ $item->nama }}"
                 onerror="this.onerror=null; this.src='{{ asset('images/items/' . ($catImgMap[$item->kategori] ?? 'proyektor.jpg')) }}'">
            <div class="p-3">
              <div class="d-flex align-items-center gap-2 mb-2">
                <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--ipb-slate);">{{ ucfirst($item->kategori) }}</span>
                @if($item->rating_avg > 0)
                  <span style="font-size:11px; color:#7a8fa0; margin-left:auto;"><i class="mdi mdi-star" style="color:var(--ipb-gold);"></i> {{ number_format($item->rating_avg, 1) }}</span>
                @endif
              </div>
              <h6 style="font-family:var(--font-display,'Cormorant Garamond',serif); font-size:20px; font-weight:400; color:var(--ipb-navy); margin-bottom:4px; line-height:1.2;">{{ $item->nama }}</h6>
              <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:12px; color:#7a8fa0; margin-bottom:12px; line-height:1.5;">{{ Str::limit($item->deskripsi, 55) }}</p>
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <span style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:16px; font-weight:500; color:var(--ipb-slate);">Rp {{ number_format($item->harga_per_hari, 0, ',', '.') }}</span>
                  <span style="font-size:11px; color:#7a8fa0;">/hari</span>
                </div>
                <span style="font-size:11px; padding:3px 10px; border-radius:20px; background:rgba(90,154,120,0.12); color:#2d6a4f; font-family:var(--font-body,'DM Sans',sans-serif); font-weight:500;">Tersedia</span>
              </div>
            </div>
          </div>
        </a>
      </div>
      @empty
      <div class="col-12 text-center py-5" style="color:#7a8fa0;">Belum ada item tersedia.</div>
      @endforelse
    </div>
  </div>
</section>

{{-- ── How It Works (5 steps) ── --}}
<section style="background:var(--ipb-cream); padding:90px 40px;">
  <div style="max-width:960px; margin:0 auto;">
    <div class="text-center mb-5">
      <p class="section-badge" style="margin:0 auto 24px;">THE PROCESS</p>
      <h2 style="font-family:var(--font-display,'Cormorant Garamond',serif); font-weight:500; font-size:48px; color:var(--ipb-navy); margin:0;">Simple from Start to Finish</h2>
    </div>
    <div class="steps-5">
      <div class="step-item">
        <div class="step-circle"><i class="mdi mdi-shield-check-outline"></i><span class="step-num">01</span></div>
        <p class="step-name">Verifikasi Akun</p>
        <p class="step-desc">Submit KTM dan selesaikan verifikasi identitas singkat</p>
      </div>
      <div class="step-item">
        <div class="step-circle"><i class="mdi mdi-magnify"></i><span class="step-num">02</span></div>
        <p class="step-name">Pilih Barang</p>
        <p class="step-desc">Telusuri katalog terkurasi dan pilih yang kamu butuhkan</p>
      </div>
      <div class="step-item">
        <div class="step-circle"><i class="mdi mdi-credit-card-outline"></i><span class="step-num">03</span></div>
        <p class="step-name">Bayar 50% DP</p>
        <p class="step-desc">Amankan rental dengan DP 50% via pembayaran terintegrasi</p>
      </div>
      <div class="step-item">
        <div class="step-circle"><i class="mdi mdi-handshake-outline"></i><span class="step-num">04</span></div>
        <p class="step-name">Ambil &amp; Pakai</p>
        <p class="step-desc">Ambil barang dari pemilik terverifikasi di kampus</p>
      </div>
      <div class="step-item">
        <div class="step-circle"><i class="mdi mdi-check-circle-outline"></i><span class="step-num">05</span></div>
        <p class="step-name">Kembalikan &amp; Selesai</p>
        <p class="step-desc">Kembalikan barang, konfirmasi selesai, dan dapatkan deposit kamu kembali</p>
      </div>
    </div>
  </div>
</section>

{{-- ── Stats ── --}}
<section class="stats-section">
  <div style="max-width:1000px; margin:0 auto; width:100%;">
    <h2 class="stats-title mb-5">TRUSTED BY THE CAMPUS COMMUNITY</h2>
    <div class="d-flex" style="justify-content: center; flex-wrap: wrap;">
      <div class="stat-block flex-1">
        <div class="stat-num-lg">{{ $stats['total_users'] }}+</div>
        <div class="stat-lbl-lg">Verified Users</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-block flex-1">
        <div class="stat-num-lg">{{ $stats['total_rentals'] }}</div>
        <div class="stat-lbl-lg">Active Rentals</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-block flex-1">
        <div class="stat-num-lg">98%</div>
        <div class="stat-lbl-lg">Successful Returns</div>
      </div>
    </div>
  </div>
</section>

{{-- ── CTA ── --}}
<section style="background:var(--ipb-navy); padding:96px 40px; text-align:center;">
  <h2 class="cta-headline">Mulai<br><em>Perjalanan Sewa</em> Kamu.</h2>
  <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:15px; color:rgba(255,255,255,0.7); margin-bottom:36px;">Bergabung dengan komunitas sewa kampus terverifikasi</p>
  <a href="{{ route('explore') }}" class="btn-cta">
    Telusuri Katalog
    <svg width="10" height="7" viewBox="0 0 10 7" fill="none" aria-hidden="true">
      <path d="M1 3.5h8M5.5 1l3 2.5-3 2.5" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </a>
</section>

@endsection
