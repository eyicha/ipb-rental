@extends('layouts.app')
@section('title', 'Detail Rental')
 
@section('content')
<div class="container py-4" style="max-width:800px;">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-outline-navy" style="border-radius:10px; width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center;">
      <i class="mdi mdi-arrow-left"></i>
    </a>
    <div class="flex-1">
      <h5 style="font-weight:800; color:var(--ipb-navy); margin:0;">Detail Rental</h5>
      <p style="font-size:12px; color:#7a8fa0; margin:0;">#{{ $rental->id }}</p>
    </div>
    <span class="badge {{ $rental->statusBadge }}" style="border-radius:10px; font-size:13px; font-weight:600; padding:8px 14px;">{{ $rental->statusLabel }}</span>
  </div>
 
  {{-- ── Stepper ── --}}
  <div class="ipb-card p-4 mb-4">
    <div class="stepper-track mb-3" style="height:6px;">
      <div class="stepper-fill stepper-fill-active" style="width:{{ $rental->stepperProgress }}%;"></div>
    </div>
    <div class="d-flex justify-content-between">
      @foreach(['Permintaan','DP Dibayar','Aktif','Selesai'] as $i => $step)
      @php
        $stepStatus = ['pending','dp_paid','active','finished'];
        $done = array_search($rental->status, $stepStatus) >= $i;
      @endphp
      <div class="text-center" style="font-size:11px; {{ $done ? 'color:var(--ipb-green); font-weight:600;' : 'color:#7a8fa0;' }}">
        <span class="{{ $done ? 'step-dot-done' : 'step-dot-pending' }}" style="display:block; margin:0 auto 4px;"></span>
        {{ $step }}
      </div>
      @endforeach
    </div>
  </div>
 
  <div class="row g-4">
    {{-- ── Item Info ── --}}
    <div class="col-md-7">
      <div class="ipb-card p-4 mb-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Barang</div>
        <div class="d-flex gap-3">
          <div style="width:72px; height:72px; border-radius:14px; overflow:hidden; flex-shrink:0;">
            <img src="{{ $rental->item->first_foto_url }}" style="width:100%;height:100%;object-fit:cover;" alt=""
                 onerror="this.onerror=null; this.parentElement.style.background='var(--ipb-slate)'">
          </div>
          <div>
            <h6 style="font-weight:700; color:var(--ipb-navy); margin-bottom:4px;">{{ $rental->item->nama }}</h6>
            <div style="font-size:13px; color:#7a8fa0;">{{ ucfirst($rental->item->kategori) }}</div>
            <a href="{{ route('items.show', $rental->item) }}" style="font-size:12px; color:var(--ipb-slate);">Lihat Item</a>
          </div>
        </div>
      </div>
 
      <div class="ipb-card p-4 mb-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Rincian</div>
        <div class="d-flex flex-column gap-2">
          <div class="d-flex justify-content-between"><span style="font-size:13px; color:#7a8fa0;">Tanggal Mulai</span><span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">{{ \Carbon\Carbon::parse($rental->tanggal_mulai)->format('d M Y') }}</span></div>
          <div class="d-flex justify-content-between"><span style="font-size:13px; color:#7a8fa0;">Tanggal Selesai</span><span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">{{ \Carbon\Carbon::parse($rental->tanggal_selesai)->format('d M Y') }}</span></div>
          <div class="d-flex justify-content-between"><span style="font-size:13px; color:#7a8fa0;">Durasi</span><span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">{{ $rental->durasi_hari }} hari</span></div>
          <hr style="border-color:rgba(46,65,86,0.08); margin:4px 0;">
          <div class="d-flex justify-content-between"><span style="font-size:13px; color:#7a8fa0;">Harga sewa</span><span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">Rp {{ number_format($rental->item->harga_per_hari * $rental->durasi_hari, 0, ',', '.') }}</span></div>
          @if($rental->item->deposit > 0)
          <div class="d-flex justify-content-between"><span style="font-size:13px; color:#7a8fa0;">Deposit</span><span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">Rp {{ number_format($rental->item->deposit, 0, ',', '.') }}</span></div>
          @endif
<div class="d-flex justify-content-between">
  <span style="font-size:14px; font-weight:700; color:var(--ipb-navy);">Total Bayar</span>
  <span style="font-size:16px; font-weight:800; color:var(--ipb-navy);">
    Rp {{ number_format($rental->total_harga + $rental->deposit, 0, ',', '.') }}
  </span>
</div>
<div style="font-size:11px; color:#7a8fa0; margin-top:6px; line-height:1.5;">
  * Sudah termasuk deposit Rp {{ number_format($rental->deposit, 0, ',', '.') }} yang akan dikembalikan setelah barang kembali.
</div>        </div>
      </div>
 
      @if($rental->catatan)
      <div class="ipb-card p-4 mb-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:10px;">Catatan Penyewa</div>
        <p style="font-size:14px; color:#7a8fa0; margin:0; line-height:1.7;">{{ $rental->catatan }}</p>
      </div>
      @endif
 
      @if($rental->bukti_dp)
      <div class="ipb-card p-4 mb-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:10px;">Bukti DP</div>
        <img src="{{ Storage::url($rental->bukti_dp) }}" style="width:100%; border-radius:12px;" alt="Bukti DP">
      </div>
      @endif
 
      @if($rental->status === 'finished' && $rental->rating)
      <div class="ipb-card p-4 mb-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:10px;">Rating</div>
        <div class="d-flex gap-1 mb-2">
          @for($i=1; $i<=5; $i++)
          <i class="mdi mdi-star{{ $i <= $rental->rating ? '' : '-outline' }}" style="color:var(--ipb-gold); font-size:20px;"></i>
          @endfor
        </div>
        @if($rental->ulasan)
        <p style="font-size:14px; color:#7a8fa0; margin:0; line-height:1.7;">{{ $rental->ulasan }}</p>
        @endif
      </div>
      @endif
    </div>
 
    {{-- ── Right: Parties + Actions ── --}}
    <div class="col-md-5">
      <div class="ipb-card p-4 mb-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Penyewa</div>
        <div class="d-flex align-items-center gap-3">
          <img src="{{ $rental->penyewa->avatarUrl }}" style="width:42px; height:42px; border-radius:12px; object-fit:cover;" alt="">
          <div>
            <div style="font-weight:700; color:var(--ipb-navy); font-size:14px;">{{ $rental->penyewa->name }}</div>
            @if($rental->penyewa->whatsapp)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$rental->penyewa->whatsapp) }}" target="_blank" style="font-size:12px; color:var(--ipb-green); text-decoration:none;"><i class="mdi mdi-whatsapp"></i> {{ $rental->penyewa->whatsapp }}</a>
            @endif
          </div>
        </div>
      </div>
 
      <div class="ipb-card p-4 mb-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Pemilik</div>
        <div class="d-flex align-items-center gap-3">
          <img src="{{ $rental->pemilik->avatarUrl }}" style="width:42px; height:42px; border-radius:12px; object-fit:cover;" alt="">
          <div>
            <div style="font-weight:700; color:var(--ipb-navy); font-size:14px;">{{ $rental->pemilik->name }}</div>
            @if($rental->pemilik->whatsapp)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$rental->pemilik->whatsapp) }}" target="_blank" style="font-size:12px; color:var(--ipb-green); text-decoration:none;"><i class="mdi mdi-whatsapp"></i> {{ $rental->pemilik->whatsapp }}</a>
            @endif
          </div>
        </div>
      </div>
 
      {{-- ── Actions ── --}}
      @php
        $isPemilik = (string)auth()->id() === (string)$rental->pemilik_id;
        $isPenyewa = (string)auth()->id() === (string)$rental->penyewa_id;
      @endphp
      <div class="ipb-card p-4">
        <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:14px;">Aksi</div>
 
        {{-- ═══════════════ PEMILIK ACTIONS ═══════════════ --}}
        @if($isPemilik)
 
          @if($rental->status === 'pending')
          <form method="POST" action="{{ route('rentals.action', $rental) }}" class="mb-2">
            @csrf
            <input type="hidden" name="action" value="accept">
            <button type="submit" class="btn btn-navy w-100 py-2" style="border-radius:10px; font-weight:700;">
              <i class="mdi mdi-check me-1"></i> Terima Permintaan
            </button>
          </form>
          <form method="POST" action="{{ route('rentals.action', $rental) }}">
            @csrf
            <input type="hidden" name="action" value="reject">
            <button type="submit" class="btn w-100 py-2"
              style="border-radius:10px; font-weight:700; background:rgba(192,118,106,0.12); color:#8a3a30; border:1px solid rgba(192,118,106,0.25);"
              onclick="return confirm('Tolak permintaan sewa ini?')">
              <i class="mdi mdi-close me-1"></i> Tolak
            </button>
          </form>
          @endif
 
          @if($rental->status === 'pending_payment')
          <div class="p-3 text-center" style="background:rgba(230,180,50,0.08); border-radius:10px; border:1px solid rgba(230,180,50,0.25);">
            <i class="mdi mdi-timer-sand" style="color:#b8860b; font-size:20px;"></i>
            <div style="font-size:13px; font-weight:600; color:#7a5c00; margin-top:4px;">Menunggu Pembayaran DP</div>
            <div style="font-size:12px; color:#7a8fa0; margin-top:2px;">Penyewa sedang melakukan pembayaran</div>
          </div>
          @endif
 
          @if($rental->status === 'dp_paid')
          <div class="p-3 text-center mb-2" style="background:rgba(90,154,120,0.08); border-radius:10px; border:1px solid rgba(90,154,120,0.2);">
            <i class="mdi mdi-cash-check" style="color:#5a9a78; font-size:20px;"></i>
            <div style="font-size:13px; font-weight:600; color:#2d6a4f; margin-top:4px;">DP Sudah Diterima</div>
            <div style="font-size:12px; color:#7a8fa0; margin-top:2px;">Silakan antarkan barang ke penyewa</div>
          </div>
          <form method="POST" action="{{ route('rentals.action', $rental) }}">
            @csrf
            <input type="hidden" name="action" value="deliver">
            <button type="submit" class="btn btn-navy w-100 py-2" style="border-radius:10px; font-weight:700;"
              onclick="return confirm('Konfirmasi barang sudah diantarkan?')">
              <i class="mdi mdi-truck-delivery me-1"></i> Barang Sudah Diantar
            </button>
          </form>
          @endif
 
          @if($rental->status === 'active')
          <div class="p-3 text-center mb-2" style="background:rgba(86,124,141,0.08); border-radius:10px; border:1px solid rgba(86,124,141,0.2);">
            <i class="mdi mdi-package-variant" style="color:var(--ipb-slate); font-size:20px;"></i>
            <div style="font-size:13px; font-weight:600; color:var(--ipb-slate); margin-top:4px;">Barang Sedang Disewa</div>
            <div style="font-size:12px; color:#7a8fa0; margin-top:2px;">Konfirmasi saat barang sudah kembali</div>
          </div>
          <form method="POST" action="{{ route('rentals.action', $rental) }}">
            @csrf
            <input type="hidden" name="action" value="return_item">
            <button type="submit" class="btn btn-navy w-100 py-2" style="border-radius:10px; font-weight:700;"
              onclick="return confirm('Konfirmasi barang sudah dikembalikan?')">
              <i class="mdi mdi-keyboard-return me-1"></i> Barang Sudah Kembali
            </button>
          </form>
          @endif
 
          @if($rental->status === 'finished')
          <div class="p-3 text-center" style="background:rgba(90,154,120,0.08); border-radius:10px; border:1px solid rgba(90,154,120,0.2);">
            <i class="mdi mdi-check-circle" style="color:#5a9a78; font-size:20px;"></i>
            <div style="font-size:13px; font-weight:600; color:#2d6a4f; margin-top:4px;">Rental Selesai</div>
          </div>
          @endif
 
        @endif {{-- end isPemilik --}}
 
        {{-- ═══════════════ PENYEWA ACTIONS ═══════════════ --}}
        @if($isPenyewa)
 
          @if($rental->status === 'pending')
          <form method="POST" action="{{ route('rentals.action', $rental) }}">
            @csrf
            <input type="hidden" name="action" value="cancel">
            <button type="submit" class="btn w-100 py-2"
              style="border-radius:10px; font-weight:700; background:rgba(192,118,106,0.12); color:#8a3a30; border:1px solid rgba(192,118,106,0.25);"
              onclick="return confirm('Batalkan permintaan ini?')">
              <i class="mdi mdi-cancel me-1"></i> Batalkan
            </button>
          </form>
          @endif
 
          @if($rental->status === 'pending_payment')
@php
  $dpPayment = \App\Models\Transaction::where('rental_id', (string)$rental->_id)
    ->where('user_id', (string)auth()->id())
    ->latest()->first();
@endphp
  @if(!$dpPayment || $dpPayment->status === 'pending')
  <button onclick="bayarDP(this)" class="btn btn-navy w-100 py-2" style="border-radius:10px; font-weight:700;">
    <i class="mdi mdi-credit-card me-1"></i> 
    {{ $dpPayment ? 'Lanjutkan Pembayaran DP' : 'Bayar DP' }}
  </button>
  @elseif($dpPayment->status === 'success')
  <div class="p-3 text-center" style="background:rgba(90,154,120,0.08); border-radius:10px; border:1px solid rgba(90,154,120,0.2);">
    <i class="mdi mdi-check-circle" style="color:#5a9a78; font-size:20px;"></i>
    <div style="font-size:13px; font-weight:600; color:#2d6a4f; margin-top:4px;">Pembayaran DP diterima</div>
  </div>
  @endif
@endif
 
          @if($rental->status === 'dp_paid')
          <div class="p-3 text-center" style="background:rgba(90,154,120,0.08); border-radius:10px; border:1px solid rgba(90,154,120,0.2);">
            <i class="mdi mdi-check-circle" style="color:#5a9a78; font-size:20px;"></i>
            <div style="font-size:13px; font-weight:600; color:#2d6a4f; margin-top:4px;">DP Sudah Dibayar</div>
            <div style="font-size:12px; color:#7a8fa0; margin-top:2px;">Menunggu pemilik mengantarkan barang</div>
          </div>
          @endif
 
          @if($rental->status === 'active')
          <div class="p-3 text-center" style="background:rgba(86,124,141,0.08); border-radius:10px; border:1px solid rgba(86,124,141,0.2);">
            <i class="mdi mdi-package-variant" style="color:var(--ipb-slate); font-size:20px;"></i>
            <div style="font-size:13px; font-weight:600; color:var(--ipb-slate); margin-top:4px;">Barang Sedang Disewa</div>
            <div style="font-size:12px; color:#7a8fa0; margin-top:2px;">Kembalikan barang tepat waktu</div>
          </div>
          @endif
 
          @if($rental->status === 'finished' && !$rental->rating)
          <form method="POST" action="{{ route('rentals.action', $rental) }}">
            @csrf
            <input type="hidden" name="action" value="rate">
            <div class="mb-3">
              <label class="form-label-ipb">Rating</label>
              <div class="d-flex gap-2" id="starRow">
                @for($i=1; $i<=5; $i++)
                <i class="mdi mdi-star-outline" data-val="{{ $i }}"
                  style="font-size:28px; color:rgba(46,65,86,0.2); cursor:pointer;"
                  onclick="setRating({{ $i }})"></i>
                @endfor
              </div>
              <input type="hidden" name="rating" id="ratingInput">
            </div>
            <div class="mb-3">
              <label class="form-label-ipb">Ulasan (opsional)</label>
              <textarea name="ulasan" class="form-control form-control-ipb" rows="2" placeholder="Bagikan pengalamanmu..."></textarea>
            </div>
            <button type="submit" class="btn btn-navy w-100 py-2" style="border-radius:10px; font-weight:700;">
              <i class="mdi mdi-star me-1"></i> Kirim Rating
            </button>
          </form>
          @endif
 
          @if($rental->status === 'finished' && $rental->rating)
          <div class="p-3 text-center" style="background:rgba(90,154,120,0.08); border-radius:10px; border:1px solid rgba(90,154,120,0.2);">
            <i class="mdi mdi-check-circle" style="color:#5a9a78; font-size:20px;"></i>
            <div style="font-size:13px; font-weight:600; color:#2d6a4f; margin-top:4px;">Rental Selesai</div>
            <div style="font-size:12px; color:#7a8fa0; margin-top:2px;">Terima kasih sudah menggunakan IPB Rental!</div>
          </div>
          @endif
 
        @endif {{-- end isPenyewa --}}
 
        {{-- ═══════════════ TOMBOL CHAT ═══════════════ --}}
        @if(in_array($rental->status, ['active','dp_paid','finished']))
        <a href="{{ route('chat.index', ['with' => $isPemilik ? $rental->penyewa_id : $rental->pemilik_id]) }}"
          class="btn btn-outline-navy w-100 py-2 mt-2" style="border-radius:10px; font-weight:600;">
          <i class="mdi mdi-chat-outline me-1"></i> Chat
        </a>
        @endif
 
      </div>
    </div>{{-- end row --}}
</div>{{-- end container --}}
@endsection
 
@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
  data-client-key="{{ config('midtrans.client_key') }}"></script>
 
<script>
function setRating(val) {
  document.getElementById('ratingInput').value = val;
  document.querySelectorAll('#starRow .mdi').forEach((el, i) => {
    el.className = i < val ? 'mdi mdi-star' : 'mdi mdi-star-outline';
    el.style.color = i < val ? 'var(--ipb-gold)' : 'rgba(46,65,86,0.2)';
  });
}
 
async function bayarDP(btn) {
  const originalHTML = btn.innerHTML;
  const urlCheckout  = "{{ route('rentals.pay', $rental) }}";
  const urlSuccess   = "{{ route('rentals.pay.success', $rental) }}";
  const csrfToken    = "{{ csrf_token() }}";
 
  try {
    btn.disabled = true;
    btn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Memproses...';
 
    const res  = await fetch(urlCheckout, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        'Accept':       'application/json',
      },
    });
    const data = await res.json();
 
    if (!res.ok || !data.snap_token) {
      alert(data.error ?? 'Gagal mendapatkan token pembayaran');
      btn.disabled = false;
      btn.innerHTML = originalHTML;
      return;
    }
 
    window.snap.pay(data.snap_token, {
    onSuccess(result) {
        fetch(urlSuccess, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_id:           result.order_id,
                transaction_status: result.transaction_status,
                payment_type:       result.payment_type,
            }),
        }).finally(() => window.location.reload());
    },
    onPending() {
        const pollInterval = setInterval(async () => {
            try {
                const statusRes = await fetch('{{ route('payments.status', $rental) }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const statusData = await statusRes.json();

                if (statusData.status === 'success') {
                    clearInterval(pollInterval);
                    fetch('{{ route('rentals.pay.success', $rental) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: statusData.order_id,
                            transaction_status: 'settlement',
                            payment_type: 'qris',
                        }),
                    }).finally(() => window.location.reload());
                }
            } catch(e) {}
        }, 3000);

        setTimeout(() => clearInterval(pollInterval), 15 * 60 * 1000);
    },
    onError(result) {
        alert('Pembayaran gagal: ' + (result.status_message ?? 'Unknown error'));
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    },
    onClose() {
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    },
});
 
  } catch (e) {
    alert('Terjadi kesalahan: ' + e.message);
    btn.disabled = false;
    btn.innerHTML = originalHTML;
  }
}

// Auto cek status kalau rental masih pending_payment
@if($rental->status === 'pending_payment')
(function autoCheckStatus() {
    const csrfToken = "{{ csrf_token() }}";
    const urlStatus  = "{{ route('payments.status', $rental) }}";
    const urlSuccess = "{{ route('rentals.pay.success', $rental) }}";

    const pollInterval = setInterval(async () => {
        try {
            const res  = await fetch(urlStatus, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (data.status === 'success') {
                clearInterval(pollInterval);
                await fetch(urlSuccess, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_id: data.order_id,
                        transaction_status: 'settlement',
                        payment_type: 'qris',
                    }),
                });
                window.location.reload();
            }
        } catch(e) {}
    }, 3000);

    // Stop setelah 30 menit
    setTimeout(() => clearInterval(pollInterval), 30 * 60 * 1000);
})();
@endif
</script>
@endpush