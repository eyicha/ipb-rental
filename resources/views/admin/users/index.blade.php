@extends('layouts.admin')
@section('title', 'Kelola Pengguna')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 style="font-weight:800; color:var(--ipb-navy); margin:0;">Kelola Pengguna</h4>
    <p style="color:#7a8fa0; font-size:14px; margin:4px 0 0;">{{ $users->total() }} pengguna terdaftar</p>
  </div>
</div>

<form method="GET" action="{{ route('admin.users.index') }}" class="ipb-card p-3 mb-4">
  <div class="d-flex gap-2 flex-wrap">
    <input type="text" name="q" class="form-control form-control-sm" style="border-radius:10px; max-width:240px; font-size:13px;" placeholder="Cari nama / email..." value="{{ request('q') }}">
    <select name="role" class="form-select form-select-sm" style="border-radius:10px; width:auto; font-size:13px;">
      <option value="">Semua Role</option>
      <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
      <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    <button type="submit" class="btn btn-sm btn-navy px-4" style="border-radius:10px;">Filter</button>
    @if(request()->hasAny(['q','role']))
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-navy px-3" style="border-radius:10px;">Reset</a>
    @endif
  </div>
</form>

<div class="ipb-card">
  <div class="table-responsive">
    <table class="table table-ipb mb-0">
      <thead>
        <tr>
          <th>Pengguna</th>
          <th>Email</th>
          <th>NIM</th>
          <th>Role</th>
          <th>Bergabung</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="{{ $user->avatarUrl }}" style="width:36px; height:36px; border-radius:10px; object-fit:cover;" alt="">
              <div>
                <div style="font-weight:600; color:var(--ipb-navy); font-size:13px;">{{ $user->name }}</div>
                @if($user->whatsapp)<div style="font-size:11px; color:#7a8fa0;">{{ $user->whatsapp }}</div>@endif
              </div>
            </div>
          </td>
          <td style="font-size:13px; color:#7a8fa0;">{{ $user->email }}</td>
          <td style="font-size:13px; color:#7a8fa0;">{{ $user->nim ?: '—' }}</td>
          <td>
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
              @csrf @method('PATCH')
              <select name="role" class="form-select form-select-sm" style="border-radius:8px; font-size:12px; width:auto; border-color:rgba(46,65,86,0.15);" onchange="this.form.submit()">
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
              </select>
            </form>
          </td>
          <td style="font-size:12px; color:#7a8fa0;">{{ $user->created_at->format('d M Y') }}</td>
          <td>
            @if($user->id !== auth()->id())
            <button class="btn btn-sm" style="border-radius:8px; background:rgba(192,118,106,0.1); color:#8a3a30; border:1px solid rgba(192,118,106,0.2);"
              onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
              <i class="mdi mdi-trash-can-outline"></i>
            </button>
            <form id="del-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-none">
              @csrf @method('DELETE')
            </form>
            @else
            <span style="font-size:12px; color:#7a8fa0;">Anda</span>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-4" style="color:#7a8fa0;">Tidak ada pengguna ditemukan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($users->hasPages())
<div class="d-flex justify-content-center mt-4">
  {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endif
@endsection

@push('scripts')
<script>
function deleteUser(id, nama) {
  Swal.fire({
    title: 'Hapus pengguna?',
    text: '"' + nama + '" akan dihapus permanen.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#c0766a',
    cancelButtonColor: '#7a8fa0',
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal'
  }).then(r => { if (r.isConfirmed) document.getElementById('del-user-' + id).submit(); });
}
</script>
@endpush
