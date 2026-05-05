@extends('layouts.app')
@section('title', 'My Items')

@section('content')
@php
  $avgRating = auth()->user()->items()->where('rating_avg', '>', 0)->avg('rating_avg') ?? 0;

  $bgMap = [
    'fotografi'  => 'mi-bg-foto',
    'elektronik' => 'mi-bg-elektronik',
    'audio'      => 'mi-bg-audio',
    'akademik'   => 'mi-bg-akademik',
    'drone'      => 'mi-bg-drone',
    'olahraga'   => 'mi-bg-olahraga',
    'perabot'    => 'mi-bg-peralatan',
    'kendaraan'  => 'mi-bg-kendaraan',
    'lainnya'    => 'mi-bg-lainnya',
  ];

  $emojiMap = [
    'fotografi'  => '📷',
    'elektronik' => '💻',
    'audio'      => '🎧',
    'akademik'   => '📚',
    'drone'      => '🚁',
    'olahraga'   => '⚽',
    'perabot'    => '⛺',
    'kendaraan'  => '🚲',
    'lainnya'    => '📦',
  ];
@endphp

<style>
/* ─── My Items Page Styles ──────────────────────────────────── */
.mi-page { max-width: 1280px; margin-inline: auto; padding: 36px 24px 60px; }

/* Page Header */
.mi-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 12px; }
.mi-header h1 { font-family: var(--font-display); font-weight: 500; font-size: 48px; line-height: 1.05; color: var(--ipb-navy); margin: 0 0 4px; }
.mi-header p  { font-family: var(--font-body); font-size: 13px; color: #7a8fa0; margin: 0; }

/* Stats Row */
.mi-stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
.mi-stat-card { background: #fff; border: 1px solid rgba(46,65,86,.06); border-radius: 12px; padding: 16px 18px; display: flex; align-items: center; gap: 14px; }
.mi-stat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.mi-stat-icon--blue  { background: rgba(200,217,230,.35); }
.mi-stat-icon--green { background: rgba(90,154,120,.1); }
.mi-stat-icon--gold  { background: rgba(212,164,90,.12); }
.mi-stat-icon--red   { background: rgba(192,118,106,.1); }
.mi-stat-value { font-family: var(--font-display); font-weight: 300; font-size: 30px; line-height: 1; color: var(--ipb-navy); }
.mi-stat-label { font-family: var(--font-body); font-size: 11px; color: #7a8fa0; margin-top: 2px; }

/* Toolbar */
.mi-toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.mi-search-box { display: flex; align-items: center; gap: 9px; background: #fff; border: 1px solid rgba(46,65,86,.06); border-radius: 9px; padding: 8px 14px; flex: 1; min-width: 200px; max-width: 320px; transition: border-color .2s; }
.mi-search-box:focus-within { border-color: var(--ipb-sky, #b8d4e0); }
.mi-search-box input { flex: 1; border: none; background: transparent; outline: none; font-family: var(--font-body); font-size: 13px; color: var(--ipb-navy); }
.mi-search-box input::placeholder { color: rgba(86,124,141,.4); }
.mi-toolbar-select { appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%232e4156' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 10px center; border: 1px solid rgba(46,65,86,.06); border-radius: 9px; padding: 8px 30px 8px 13px; font-family: var(--font-body); font-size: 12px; color: var(--ipb-navy); outline: none; height: 36px; }
.mi-item-count { margin-left: auto; font-family: var(--font-body); font-size: 12px; color: #7a8fa0; white-space: nowrap; }
.mi-item-count strong { color: var(--ipb-navy); }

/* Items Grid */
.mi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

/* Card */
.mi-card { background: #fff; border: 1px solid rgba(46,65,86,.07); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; }

/* Card image area */
.mi-card__img { position: relative; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; }
.mi-card__img::before { content: ''; position: absolute; inset: 0; background: radial-gradient(50% 50% at 68% 40%, rgba(255,255,255,.22) 0%, transparent 55%), radial-gradient(50% 50% at 32% 60%, rgba(255,255,255,.12) 0%, transparent 45%); pointer-events: none; }
.mi-icon-wrap { width: 54px; height: 54px; border-radius: 15px; background: rgba(255,255,255,.18); backdrop-filter: blur(2px); box-shadow: 0 4px 16px rgba(0,0,0,.15); display: flex; align-items: center; justify-content: center; position: relative; z-index: 1; font-size: 22px; }
.mi-card-photo { position: absolute; inset: 0; object-fit: cover; width: 100%; height: 100%; z-index: 0; }
.mi-card-photo-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,.4) 100%); z-index: 1; }

/* Status badge (top right) */
.mi-card-status { position: absolute; top: 10px; right: 10px; padding: 4px 10px; border-radius: 20px; font-family: var(--font-body); font-weight: 500; font-size: 10px; letter-spacing: .4px; color: #fff; z-index: 2; }
.mi-card-status--green { background: rgba(90,154,120,.85); box-shadow: 0 2px 8px rgba(90,154,120,.35); }
.mi-card-status--gold  { background: rgba(212,164,90,.9);  box-shadow: 0 2px 8px rgba(212,164,90,.35); }
.mi-card-status--grey  { background: rgba(46,65,86,.6); }

/* Category badge (bottom left) */
.mi-card-cat { position: absolute; bottom: 10px; left: 10px; padding: 3px 9px; border-radius: 20px; background: rgba(255,255,255,.2); backdrop-filter: blur(2.5px); font-family: var(--font-body); font-size: 10px; letter-spacing: .4px; color: rgba(255,255,255,.92); z-index: 2; }

/* Card body */
.mi-card__body { padding: 16px 18px 18px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
.mi-card__name { font-family: var(--font-display); font-weight: 400; font-size: 19px; line-height: 1.2; color: var(--ipb-navy); margin: 0; }

/* Rating row */
.mi-rating-row { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
.mi-star   { color: #d4a45a; font-size: 11px; }
.mi-rscore { font-family: var(--font-body); font-size: 11px; color: #7a8fa0; }
.mi-rcount { font-family: var(--font-body); font-size: 11px; color: rgba(122,143,160,.4); }
.mi-qpill  { padding: 2px 9px; border-radius: 20px; font-family: var(--font-body); font-size: 10px; }
.mi-qpill--best { background: rgba(90,154,120,.1);  color: #3d7a5a; }
.mi-qpill--good { background: rgba(212,164,90,.1);  color: #8a6020; }
.mi-qpill--ok   { background: rgba(192,118,106,.1); color: #8a3a30; }

/* Mini stats */
.mi-mini-stats { display: grid; grid-template-columns: repeat(3,1fr); border: 1px solid rgba(46,65,86,.06); border-radius: 10px; overflow: hidden; }
.mi-mini-stat { display: flex; flex-direction: column; align-items: center; padding: 9px 8px; }
.mi-mini-stat + .mi-mini-stat { border-left: 1px solid rgba(46,65,86,.06); }
.mi-mini-val { font-family: var(--font-body); font-weight: 500; font-size: 13px; color: var(--ipb-navy); }
.mi-mini-lbl { font-family: var(--font-body); font-weight: 300; font-size: 10px; color: #7a8fa0; margin-top: 1px; }

/* Card footer */
.mi-card__footer { display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px solid rgba(46,65,86,.05); }
.mi-price { font-family: var(--font-body); font-weight: 500; font-size: 16px; color: var(--ipb-navy); margin: 0; }
.mi-price span { font-weight: 300; font-size: 11px; color: #7a8fa0; }
.mi-actions { display: flex; align-items: center; gap: 5px; }

/* Action buttons */
.mi-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer; font-family: var(--font-body); font-weight: 500; font-size: 11px; letter-spacing: .33px; transition: opacity .2s; text-decoration: none; white-space: nowrap; }
.mi-btn:hover { opacity: .8; text-decoration: none; }
.mi-btn svg { width: 11px; height: 11px; flex-shrink: 0; }
.mi-btn--detail   { background: rgba(46,65,86,.07);   color: var(--ipb-navy); }
.mi-btn--edit     { background: rgba(86,124,141,.1);  color: var(--ipb-slate, #567c8d); }
.mi-btn--pause    { background: rgba(212,164,90,.1);  color: #8a6020; }
.mi-btn--activate { background: rgba(90,154,120,.1);  color: #3d7a5a; }
.mi-btn--delete   { background: rgba(192,118,106,.1); color: #8a3a30; }
.mi-btn--icon     { padding: 6px; }
.mi-btn--primary  { background: var(--ipb-navy); color: #fff; }
.mi-btn--primary:hover { color: #fff; }

/* Category gradient backgrounds */
.mi-bg-foto       { background: linear-gradient(153deg, #2e4156 0%, #567c8d 100%); }
.mi-bg-elektronik { background: linear-gradient(153deg, #35506b 0%, #567c8d 100%); }
.mi-bg-audio      { background: linear-gradient(153deg, #3a4a38 0%, #5a8a58 100%); }
.mi-bg-akademik   { background: linear-gradient(153deg, #4a3820 0%, #8a6a38 100%); }
.mi-bg-drone      { background: linear-gradient(153deg, #1c2a3a 0%, #3a6880 100%); }
.mi-bg-olahraga   { background: linear-gradient(153deg, #3a2020 0%, #8a4a3a 100%); }
.mi-bg-peralatan  { background: linear-gradient(153deg, #2a2a3e 0%, #5a5a7a 100%); }
.mi-bg-kendaraan  { background: linear-gradient(153deg, #1a3020 0%, #3a6050 100%); }
.mi-bg-lainnya    { background: linear-gradient(153deg, #2a2a2a 0%, #5a5a5a 100%); }

/* Responsive */
@media (max-width: 1024px) {
  .mi-grid { grid-template-columns: repeat(2, 1fr); }
  .mi-stats-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .mi-grid { grid-template-columns: 1fr; }
  .mi-page { padding: 20px 16px 40px; }
  .mi-header h1 { font-size: 32px; }
  .mi-item-count { display: none; }
}
</style>

<div class="mi-page">

  {{-- ── Page Header ── --}}
  <div class="mi-header">
    <div>
      <h1>My Items</h1>
      <p>Kelola item yang kamu daftarkan untuk disewakan</p>
    </div>
    <a href="{{ route('my-items.create') }}" class="mi-btn mi-btn--primary" style="padding: 10px 22px; font-size: 13px; border-radius: 10px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Tambah Item
    </a>
  </div>

  {{-- ── Stats Row ── --}}
  <div class="mi-stats-row">

    <div class="mi-stat-card">
      <div class="mi-stat-icon mi-stat-icon--blue">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#567c8d" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
      </div>
      <div>
        <div class="mi-stat-value">{{ $stats['total'] }}</div>
        <div class="mi-stat-label">Total Item</div>
      </div>
    </div>

    <div class="mi-stat-card">
      <div class="mi-stat-icon mi-stat-icon--green">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#5a9a78" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
      </div>
      <div>
        <div class="mi-stat-value">{{ $stats['aktif'] }}</div>
        <div class="mi-stat-label">Tersedia</div>
      </div>
    </div>

    <div class="mi-stat-card">
      <div class="mi-stat-icon mi-stat-icon--gold">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d4a45a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
      </div>
      <div>
        <div class="mi-stat-value">{{ $stats['disewa'] }}</div>
        <div class="mi-stat-label">Sedang Disewa</div>
      </div>
    </div>

    <div class="mi-stat-card">
      <div class="mi-stat-icon mi-stat-icon--red">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c0766a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
      </div>
      <div>
        <div class="mi-stat-value">{{ $avgRating > 0 ? number_format($avgRating, 1) : '—' }}</div>
        <div class="mi-stat-label">Rating Rata-rata</div>
      </div>
    </div>

  </div>

  {{-- ── Toolbar ── --}}
  <form method="GET" action="{{ route('my-items.index') }}" class="mi-toolbar">
    <div class="mi-search-box">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(86,124,141,0.5)" stroke-width="2" stroke-linecap="round">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari item kamu...">
    </div>

    <select name="kategori" class="mi-toolbar-select" onchange="this.form.submit()">
      <option value="">Semua Kategori</option>
      @foreach($categories as $cat)
        <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
      @endforeach
    </select>

    <select name="status" class="mi-toolbar-select" onchange="this.form.submit()">
      <option value="">Semua Status</option>
      <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Tersedia</option>
      <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <p class="mi-item-count">Menampilkan <strong>{{ $items->total() }}</strong> item</p>
  </form>

  {{-- ── Items Grid ── --}}
  @if($items->count())
  <div class="mi-grid">
    @foreach($items as $item)
    @php
      $bgClass  = $bgMap[$item->kategori] ?? 'mi-bg-lainnya';
      $emoji    = $emojiMap[$item->kategori] ?? '📦';
      $hasPhoto = !empty($item->foto) && is_array($item->foto) && count($item->foto) > 0;

      // Quality pill
      if ($item->rating_avg >= 4.5)     { $qLabel = 'Sangat Baik'; $qClass = 'mi-qpill--best'; }
      elseif ($item->rating_avg >= 3.5) { $qLabel = 'Baik';        $qClass = 'mi-qpill--good'; }
      else                               { $qLabel = 'Cukup';       $qClass = 'mi-qpill--ok'; }

      // Status badge
      if ($item->status === 'aktif')    { $statusLabel = 'Tersedia';  $statusCls = 'mi-card-status--green'; }
      else                               { $statusLabel = 'Nonaktif'; $statusCls = 'mi-card-status--grey'; }
    @endphp

    <article class="mi-card">

      {{-- ── Card Image ── --}}
      <div class="mi-card__img {{ $hasPhoto ? '' : $bgClass }}">
        @if($hasPhoto)
          <img src="{{ $item->first_foto_url }}" class="mi-card-photo" alt="{{ $item->nama }}"
               onerror="this.onerror=null; this.parentElement.classList.add('{{ $bgClass }}')">
          <div class="mi-card-photo-overlay"></div>
        @else
          <div class="mi-icon-wrap">{{ $emoji }}</div>
        @endif
        <span class="mi-card-status {{ $statusCls }}">{{ $statusLabel }}</span>
        <span class="mi-card-cat">{{ ucfirst($item->kategori) }}</span>
      </div>

      {{-- ── Card Body ── --}}
      <div class="mi-card__body">

        <h3 class="mi-card__name">{{ $item->nama }}</h3>

        {{-- Rating + Quality Pill --}}
        <div class="mi-rating-row">
          @if($item->rating_avg > 0)
            <span class="mi-star">★</span>
            <span class="mi-rscore">{{ number_format($item->rating_avg, 1) }}</span>
            <span class="mi-rcount">({{ $item->total_sewa }})</span>
            <span class="mi-qpill {{ $qClass }}">{{ $qLabel }}</span>
          @else
            <span style="font-family: var(--font-body); font-size:11px; color:#7a8fa0;">Belum ada ulasan</span>
          @endif
        </div>

        {{-- Mini Stats --}}
        <div class="mi-mini-stats">
          <div class="mi-mini-stat">
            <span class="mi-mini-val">{{ $item->total_sewa }}×</span>
            <span class="mi-mini-lbl">Disewa</span>
          </div>
          <div class="mi-mini-stat">
            <span class="mi-mini-val" style="font-size:12px;">Rp&nbsp;{{ number_format($item->deposit ?? 0, 0, ',', '.') }}</span>
            <span class="mi-mini-lbl">Deposit</span>
          </div>
          <div class="mi-mini-stat">
            <span class="mi-mini-val">{{ $item->total_sewa }}</span>
            <span class="mi-mini-lbl">Ulasan</span>
          </div>
        </div>

        {{-- Footer: price + actions --}}
        <div class="mi-card__footer">
          <p class="mi-price">Rp {{ number_format($item->harga_per_hari, 0, ',', '.') }}<span>/hari</span></p>
          <div class="mi-actions">

            {{-- Detail --}}
            <a href="{{ route('my-items.edit', $item) }}" class="mi-btn mi-btn--detail">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
              Detail
            </a>

            {{-- Edit --}}
            <a href="{{ route('my-items.edit', $item) }}" class="mi-btn mi-btn--edit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
              Edit
            </a>

            {{-- Toggle Status (icon only) --}}
            <form method="POST" action="{{ route('my-items.toggle-status', $item) }}" style="display:inline">
              @csrf
              @if($item->status === 'aktif')
                <button type="submit" class="mi-btn mi-btn--pause mi-btn--icon" title="Nonaktifkan">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>
                  </svg>
                </button>
              @else
                <button type="submit" class="mi-btn mi-btn--activate mi-btn--icon" title="Aktifkan">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <polygon points="5 3 19 12 5 21 5 3"/>
                  </svg>
                </button>
              @endif
            </form>

            {{-- Delete (icon only) --}}
            <button type="button" class="mi-btn mi-btn--delete mi-btn--icon" title="Hapus"
              onclick="deleteItem({{ $item->id }}, '{{ addslashes($item->nama) }}')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14H6L5 6"/>
                <path d="M10 11v6"/><path d="M14 11v6"/>
                <path d="M9 6V4h6v2"/>
              </svg>
            </button>
            <form id="del-{{ $item->id }}" method="POST" action="{{ route('my-items.destroy', $item) }}" class="d-none">
              @csrf @method('DELETE')
            </form>

          </div>
        </div>

      </div>
    </article>
    @endforeach
  </div>

  {{-- Pagination --}}
  @if($items->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $items->links() }}
    </div>
  @endif

  @else
  {{-- ── Empty State ── --}}
  <div style="text-align:center; padding: 80px 20px;">
    <div style="width:80px; height:80px; border-radius:20px; background:rgba(46,65,86,.05); display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:36px;">📦</div>
    <h5 style="font-family: var(--font-display); font-size:28px; font-weight:400; color: var(--ipb-navy); margin-bottom:8px;">Belum ada item</h5>
    <p style="font-family: var(--font-body); color:#7a8fa0; font-size:14px; margin-bottom:24px;">Mulai daftarkan barang yang ingin kamu sewakan</p>
    <a href="{{ route('my-items.create') }}" class="mi-btn mi-btn--primary" style="padding: 12px 32px; font-size: 14px; border-radius: 12px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Tambah Item Pertama
    </a>
  </div>
  @endif

</div>
@endsection

@push('scripts')
<script>
function deleteItem(id, nama) {
  Swal.fire({
    title: 'Hapus item?',
    text: '"' + nama + '" akan dihapus permanen.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#c0766a',
    cancelButtonColor: '#7a8fa0',
    confirmButtonText: 'Ya, Hapus',
    cancelButtonText: 'Batal'
  }).then(result => {
    if (result.isConfirmed) document.getElementById('del-' + id).submit();
  });
}
</script>
@endpush
