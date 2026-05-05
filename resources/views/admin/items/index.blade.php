@extends('layouts.admin')
@section('title', 'Kelola Item')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 style="font-weight:800; color:var(--ipb-navy); margin:0;">Kelola Item</h4>
    <p style="color:#7a8fa0; font-size:14px; margin:4px 0 0;">{{ $items->total() }} item terdaftar</p>
  </div>
</div>

{{-- ── Filter ── --}}
<form method="GET" action="{{ route('admin.items.index') }}" class="ipb-card p-3 mb-4">
  <div class="d-flex gap-2 flex-wrap">
    <input type="text" name="q" class="form-control form-control-sm" style="border-radius:10px; max-width:240px; font-size:13px;" placeholder="Cari nama item..." value="{{ request('q') }}">
    <select name="status" class="form-select form-select-sm" style="border-radius:10px; width:auto; font-size:13px;">
      <option value="">Semua Status</option>
      <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
      <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
      <option value="habis" {{ request('status') === 'habis' ? 'selected' : '' }}>Habis</option>
    </select>
    <button type="submit" class="btn btn-sm btn-navy px-4" style="border-radius:10px;">Filter</button>
    @if(request()->hasAny(['q','status']))
    <a href="{{ route('admin.items.index') }}" class="btn btn-sm btn-outline-navy px-3" style="border-radius:10px;">Reset</a>
    @endif
  </div>
</form>

<div class="ipb-card" style="overflow:visible;">
  <div class="table-responsive">
    <table class="table table-ipb mb-0">
      <thead>
        <tr>
          <th>Item</th>
          <th>Pemilik</th>
          <th>Kategori</th>
          <th>Harga/hari</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <div style="width:44px; height:44px; border-radius:10px; overflow:hidden; flex-shrink:0;">
                @if(true)
                  <img src="{{ $item->first_foto_url }}" style="width:100%;height:100%;object-fit:cover;" alt=""
                       onerror="this.onerror=null; this.parentElement.style.background='var(--ipb-slate)'">
                @else
                  <div class="ph-{{ $item->kategori }}" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                    <i class="mdi mdi-{{ $item->kategoriIcon }}" style="color:rgba(255,255,255,0.6);font-size:18px;"></i>
                  </div>
                @endif
              </div>
              <div>
                <div style="font-weight:600; color:var(--ipb-navy); font-size:13px;">{{ $item->nama }}</div>
                <div style="font-size:11px; color:#7a8fa0;">Stok: {{ $item->stok }} | {{ $item->total_sewa }}x disewa</div>
              </div>
            </div>
          </td>
          <td style="font-size:13px; color:#7a8fa0;">{{ $item->owner->name }}</td>
          <td><span style="font-size:11px; font-weight:600; color:var(--ipb-slate);">{{ ucfirst($item->kategori) }}</span></td>
          <td style="font-size:13px; font-weight:600; color:var(--ipb-navy);">Rp {{ number_format($item->harga_per_hari, 0, ',', '.') }}</td>
          <td>
            <form method="POST" action="{{ route('admin.items.update', $item) }}">
              @csrf @method('PATCH')
              <select name="status" class="form-select form-select-sm" style="border-radius:8px; font-size:12px; width:auto; border-color:rgba(46,65,86,0.15);" onchange="this.form.submit()">
                <option value="aktif" {{ $item->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $item->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                <option value="habis" {{ $item->status === 'habis' ? 'selected' : '' }}>Habis</option>
              </select>
            </form>
          </td>
          <td>
            <button class="btn btn-sm" style="border-radius:8px; background:rgba(192,118,106,0.1); color:#8a3a30; border:1px solid rgba(192,118,106,0.2);"
              onclick="deleteItem({{ $item->id }}, '{{ addslashes($item->nama) }}')">
              <i class="mdi mdi-trash-can-outline"></i>
            </button>
            <form id="del-item-{{ $item->id }}" method="POST" action="{{ route('admin.items.destroy', $item) }}" class="d-none">
              @csrf @method('DELETE')
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4" style="color:#7a8fa0;">Tidak ada item ditemukan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($items->hasPages())
<div class="d-flex justify-content-center mt-4">
  {{ $items->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endif
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
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal'
  }).then(r => { if (r.isConfirmed) document.getElementById('del-item-' + id).submit(); });
}
</script>
@endpush
