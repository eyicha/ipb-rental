@extends('layouts.app')
@section('title', 'Pembayaran DP Sewa')

@section('content')
<div class="container py-5" style="max-width:600px;">
  <div class="d-flex align-items-center gap-3 mb-5">
    <a href="{{ route('rentals.index') }}" class="btn btn-sm btn-outline-navy" style="border-radius:10px; width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center;">
      <i class="mdi mdi-arrow-left"></i>
    </a>
    <div>
      <h5 style="font-weight:800; color:var(--ipb-navy); margin:0;">Pembayaran DP Sewa</h5>
      <p style="font-size:12px; color:#7a8fa0; margin:0;">Item: {{ $rental->item->nama }}</p>
    </div>
  </div>

  <div style="background:#f7f7f5; border-radius:16px; padding:28px; margin-bottom:24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
      <span style="font-size:14px; color:#7a8fa0;">Jumlah DP</span>
      <span style="font-size:20px; font-weight:800; color:var(--ipb-navy);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
    </div>
    <hr style="border:none; border-top:1px solid rgba(46,65,86,.1); margin:16px 0;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
      <span style="font-size:13px; color:#7a8fa0;">Rental ID</span>
      <span style="font-size:13px; font-weight:600; color:var(--ipb-navy);">#{{ $rental->id }}</span>
    </div>
  </div>

  <div style="background:#fff; border:1px solid rgba(46,65,86,.1); border-radius:14px; padding:24px; text-align:center; margin-bottom:24px;">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#5a9a78" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;">
      <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
    <p style="font-size:13px; color:#7a8fa0; margin:0; line-height:1.6;">
      Tekan tombol di bawah untuk melanjutkan ke pembayaran melalui Midtrans Snap. Pilih metode pembayaran QRIS atau metode lainnya.
    </p>
  </div>

  <button id="pay-button" style="width:100%; padding:16px; background:var(--ipb-navy); color:#fff; border:none; border-radius:12px; font-family:var(--font-body,'DM Sans',sans-serif); font-weight:700; font-size:16px; cursor:pointer;">
    <i class="mdi mdi-credit-card me-2"></i> Bayar Sekarang
  </button>

  <div style="margin-top:24px; padding:16px; background:rgba(86,124,141,.05); border-radius:12px;">
    <div style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--ipb-slate); margin-bottom:8px;">Metode Pembayaran Tersedia</div>
    <ul style="margin:0; padding-left:16px; font-size:13px; color:#7a8fa0; line-height:1.8;">
      <li>QRIS</li>
      <li>Transfer Bank</li>
      <li>E-Wallet (OVO, Dana, LinkAja)</li>
      <li>Cicilan (Akulaku, kredivo, dll)</li>
    </ul>
  </div>
</div>

{{-- Midtrans Snap Script --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
const payButton = document.getElementById('pay-button');
const snapToken = "{{ $snapToken }}";

payButton.addEventListener('click', function() {
  snap.pay(snapToken, {
    onSuccess: function(result) {
      // Payment successful - this will be handled by the webhook/callback
      window.location.href = "{{ route('rentals.index') }}";
    },
    onPending: function(result) {
      // Payment is pending
      alert('Pembayaran sedang diproses. Silakan tunggu konfirmasi dari Midtrans.');
    },
    onError: function(result) {
      // Payment error
      alert('Pembayaran gagal: ' + result.status_message);
    },
    onClose: function() {
      // User closed the payment modal
      alert('Pembayaran dibatalkan.');
    }
  });
});
</script>
@endsection
