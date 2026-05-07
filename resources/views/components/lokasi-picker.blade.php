@props(['name' => 'lokasi', 'value' => null, 'label' => 'Lokasi'])

@php
  use App\Data\IPBLokasi;
  $lokasiData    = IPBLokasi::all();
  $currentLokasi = $value;
  $currentKampus = $currentLokasi ? IPBLokasi::findKampus($currentLokasi) : null;

  // Kampus yang hanya punya 1 pilihan (tidak perlu dropdown kedua)
  $singleKampus = ['IPB Baranangsiang', 'IPB Taman Kencana', 'IPB Sukabumi'];
@endphp

<div id="{{ $name }}_wrapper">
  <label style="font-family:var(--font-body,'DM Sans',sans-serif); font-weight:500; font-size:11px; letter-spacing:2px; text-transform:uppercase; color:var(--ipb-slate); display:block; margin-bottom:8px;">
    {{ $label }}
  </label>

  {{-- Step 1: Pilih Kampus --}}
  <select id="{{ $name }}_kampus"
    style="width:100%; padding:15px 18px; border-radius:13px; border:1.5px solid transparent; background:#f7f7f5; font-family:var(--font-body,'DM Sans',sans-serif); font-size:15px; color:var(--ipb-navy); margin-bottom:10px; outline:none; cursor:pointer; transition:border-color .2s;"
    onfocus="this.style.borderColor='var(--ipb-slate)'"
    onblur="this.style.borderColor='transparent'"
    onchange="updateLokasi('{{ $name }}')">
    <option value="">-- Pilih Kampus IPB --</option>
    @foreach($lokasiData as $kampus => $lokasis)
    <option value="{{ $kampus }}" {{ $currentKampus === $kampus ? 'selected' : '' }}>
      {{ $kampus }}
    </option>
    @endforeach
  </select>

  {{-- Step 2: Pilih Lokasi Spesifik (hidden by default) --}}
  <select id="{{ $name }}_detail" name="{{ $name }}"
    style="width:100%; padding:15px 18px; border-radius:13px; border:1.5px solid transparent; background:#f7f7f5; font-family:var(--font-body,'DM Sans',sans-serif); font-size:15px; color:var(--ipb-navy); outline:none; cursor:pointer; transition:border-color .2s; {{ !$currentKampus || in_array($currentKampus, $singleKampus) ? 'display:none;' : '' }}"
    onfocus="this.style.borderColor='var(--ipb-slate)'"
    onblur="this.style.borderColor='transparent'">
    <option value="">-- Pilih Lokasi Spesifik --</option>
    @foreach($lokasiData as $kampus => $lokasis)
      @foreach($lokasis as $lokasi)
      <option value="{{ $lokasi }}"
        data-kampus="{{ $kampus }}"
        {{ $currentLokasi === $lokasi ? 'selected' : '' }}>
        {{ $lokasi }}
      </option>
      @endforeach
    @endforeach
  </select>

</div>

<script>
(function() {
  const singleKampus = @json($singleKampus);

  window.updateLokasi = function(fieldName) {
    const kampusSelect = document.getElementById(fieldName + '_kampus');
    const detailSelect = document.getElementById(fieldName + '_detail');
    const selected     = kampusSelect.value;

    detailSelect.value = '';

    if (!selected) {
      detailSelect.style.display = 'none';
      return;
    }

    // Kampus single → set value langsung, sembunyikan detail
    if (singleKampus.includes(selected)) {
      detailSelect.style.display = 'none';
      // Inject hidden input untuk value
      let hidden = document.getElementById(fieldName + '_hidden');
      if (!hidden) {
        hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.id   = fieldName + '_hidden';
        hidden.name = fieldName;
        detailSelect.parentNode.appendChild(hidden);
      }
      // Hapus name dari select agar tidak conflict
      detailSelect.removeAttribute('name');
      hidden.value = selected;
      return;
    }

    // Hapus hidden input jika ada
    const hidden = document.getElementById(fieldName + '_hidden');
    if (hidden) hidden.remove();
    detailSelect.name = fieldName;

    // Filter opsi berdasarkan kampus
    Array.from(detailSelect.options).forEach(opt => {
      if (opt.value === '') {
        opt.style.display = '';
      } else {
        opt.style.display = opt.dataset.kampus === selected ? '' : 'none';
      }
    });

    detailSelect.style.display = '';
    detailSelect.focus();
  };

  // Init saat load
  document.addEventListener('DOMContentLoaded', function() {
    const kampusSelect = document.getElementById('{{ $name }}_kampus');
    const detailSelect = document.getElementById('{{ $name }}_detail');

    if (!kampusSelect) return;

    if (kampusSelect.value) {
      // Filter opsi yang tampil
      Array.from(detailSelect.options).forEach(opt => {
        if (opt.value === '') {
          opt.style.display = '';
        } else {
          opt.style.display = opt.dataset.kampus === kampusSelect.value ? '' : 'none';
        }
      });

      if (singleKampus.includes(kampusSelect.value)) {
        detailSelect.style.display = 'none';
      } else {
        detailSelect.style.display = '';
      }
    }
  });
})();
</script>