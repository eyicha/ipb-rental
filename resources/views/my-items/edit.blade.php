@extends('layouts.app')
@section('title', 'Edit Item')

@section('content')
<div class="container py-4" style="max-width:760px;">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('my-items.index') }}" class="btn btn-sm btn-outline-navy" style="border-radius:10px; width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center;">
      <i class="mdi mdi-arrow-left"></i>
    </a>
    <div>
      <h5 style="font-weight:800; color:var(--ipb-navy); margin:0;">Edit Item</h5>
      <p style="font-size:13px; color:#7a8fa0; margin:0;">{{ $myItem->nama }}</p>
    </div>
  </div>

  <form method="POST" action="{{ route('my-items.update', $myItem) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:16px;">Informasi Dasar</div>

      <div class="mb-3">
        <label class="form-label-ipb">Nama Item <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control form-control-ipb @error('nama') is-invalid @enderror"
          value="{{ old('nama', $myItem->nama) }}" required>
        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label-ipb">Kategori <span class="text-danger">*</span></label>
        <select name="kategori" class="form-select form-control-ipb @error('kategori') is-invalid @enderror" required>
          @foreach(['elektronik','fotografi','audio','drone','akademik','olahraga','perabot','kendaraan','lainnya'] as $kat)
          <option value="{{ $kat }}" {{ old('kategori', $myItem->kategori) === $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>
          @endforeach
        </select>
        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label-ipb">Deskripsi</label>
        <textarea name="deskripsi" class="form-control form-control-ipb @error('deskripsi') is-invalid @enderror"
          rows="4">{{ old('deskripsi', $myItem->deskripsi) }}</textarea>
        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:16px;">Harga & Stok</div>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label-ipb">Harga/Hari (Rp) <span class="text-danger">*</span></label>
          <input type="number" name="harga_per_hari" class="form-control form-control-ipb @error('harga_per_hari') is-invalid @enderror"
            value="{{ old('harga_per_hari', $myItem->harga_per_hari) }}" min="0" required>
          @error('harga_per_hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label-ipb">Deposit (Rp)</label>
          <input type="number" name="deposit" class="form-control form-control-ipb @error('deposit') is-invalid @enderror"
            value="{{ old('deposit', $myItem->deposit) }}" min="0">
          @error('deposit')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
          <label class="form-label-ipb">Stok <span class="text-danger">*</span></label>
          <input type="number" name="stok" class="form-control form-control-ipb @error('stok') is-invalid @enderror"
            value="{{ old('stok', $myItem->stok) }}" min="1" required>
          @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="ipb-card p-4 mb-4">
      <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:16px;">Foto Item</div>

      @if($myItem->foto && count($myItem->foto) > 0)
      <div class="d-flex flex-wrap gap-2 mb-3">
        @foreach($myItem->fotoUrls() as $fotoUrl)
        <div style="width:80px; height:80px; border-radius:10px; overflow:hidden; border:2px solid rgba(86,124,141,0.2);">
          <img src="{{ $fotoUrl }}" style="width:100%; height:100%; object-fit:cover;" alt="">
        </div>
        @endforeach
      </div>
      <p style="font-size:12px; color:#7a8fa0; margin-bottom:12px;">Upload foto baru untuk mengganti foto yang ada</p>
      @endif

      <label class="upload-area d-block" for="fotoInput">
        <i class="mdi mdi-image-plus" style="font-size:36px; color:var(--ipb-slate); margin-bottom:8px; display:block;"></i>
        <div style="font-weight:600; color:var(--ipb-navy); font-size:14px;">Klik untuk upload foto baru</div>
        <div style="font-size:12px; color:#7a8fa0; margin-top:4px;">Maks. 5 foto, format JPG/PNG</div>
      </label>
      <input type="file" id="fotoInput" name="foto[]" multiple accept="image/*" class="d-none" onchange="previewFoto(this)">
      <div id="fotoPreview" class="d-flex flex-wrap gap-2 mt-3"></div>
    </div>

    <div class="d-flex gap-3 justify-content-end">
      <a href="{{ route('my-items.index') }}" class="btn btn-sm px-4 py-2" style="border-radius:10px; border:1px solid rgba(46,65,86,0.15); color:#7a8fa0;">Batal</a>
      <button type="submit" class="btn btn-navy px-5 py-2" style="border-radius:10px; font-weight:700;">
        <i class="mdi mdi-check me-1"></i> Simpan Perubahan
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
function previewFoto(input) {
  const preview = document.getElementById('fotoPreview');
  preview.innerHTML = '';
  [...input.files].slice(0, 5).forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      const div = document.createElement('div');
      div.style.cssText = 'width:80px;height:80px;border-radius:10px;overflow:hidden;border:2px solid rgba(86,124,141,0.2);';
      div.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
      preview.appendChild(div);
    };
    reader.readAsDataURL(file);
  });
}
</script>
@endpush
