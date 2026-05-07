@extends('layouts.app')
@section('title', 'Chat')

@push('styles')
<style>
.chat-layout {
  height: calc(100vh - 72px);
  display: flex; margin: 14px 20px 14px;
  border: 1px solid rgba(46,65,86,0.07);
  border-radius: 20px; box-shadow: 0 24px 64px rgba(46,65,86,0.1);
  background: #fff; overflow: hidden;
}

/* ── Sidebar ── */
.chat-sidebar-proto {
  width: 340px; flex-shrink: 0;
  display: flex; flex-direction: column;
  background: rgba(240,244,247,0.5);
  border-right: 1px solid rgba(46,65,86,0.06);
  overflow: hidden;
}
.chat-sidebar-header {
  padding: 22px 22px 14px;
  border-bottom: 1px solid rgba(46,65,86,0.06); flex-shrink: 0;
}
.chat-sidebar-title {
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 400; font-size: 26px; color: var(--ipb-navy); margin-bottom: 12px;
}
.chat-search-box {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px; background: #fff;
  border: 1px solid rgba(46,65,86,0.07); border-radius: 12px;
}
.chat-search-box input {
  flex: 1; border: none; outline: none;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 14px; color: var(--ipb-navy); background: transparent;
}
.chat-search-box input::placeholder { color: rgba(86,124,141,0.4); }
.conv-list-proto { flex: 1; overflow-y: auto; padding: 6px 0; }
.conv-section-label-proto {
  padding: 8px 20px 4px;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 10px; letter-spacing: 1.6px;
  text-transform: uppercase; color: rgba(122,143,160,0.55);
}
.conv-item-proto {
  position: relative; display: flex; align-items: center;
  gap: 13px; padding: 13px 20px; cursor: pointer;
  transition: background 0.15s; text-decoration: none;
}
.conv-item-proto:hover { background: rgba(86,124,141,0.06); }
.conv-item-proto.active { background: rgba(86,124,141,0.1); }
.conv-item-proto.active::before {
  content: ''; position: absolute; left: 0; top: 50%;
  transform: translateY(-50%);
  width: 4px; height: calc(100% - 18px);
  background: var(--ipb-slate); border-radius: 0 3px 3px 0;
}
.conv-avatar-proto {
  width: 50px; height: 50px; border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 600; font-size: 17px; color: #fff;
  flex-shrink: 0; background: linear-gradient(145deg,var(--ipb-navy),var(--ipb-slate));
}
.conv-info-proto { flex: 1; min-width: 0; }
.conv-name-proto {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 15px; color: var(--ipb-navy);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.conv-preview-proto {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 13px; color: #7a8fa0;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.conv-meta-proto {
  display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0;
}
.conv-time-proto {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 11px; color: #7a8fa0;
}
.conv-unread-badge {
  width: 20px; height: 20px; border-radius: 50%;
  background: var(--ipb-slate); display: flex; align-items: center;
  justify-content: center; font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 600; font-size: 10px; color: #fff;
}

/* ── Chat panel ── */
.chat-panel-proto {
  flex: 1; display: flex; flex-direction: column; overflow: hidden;
}
.chat-header-proto {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 28px; background: rgba(255,255,255,0.9);
  border-bottom: 1px solid rgba(46,65,86,0.06);
  backdrop-filter: blur(6px); flex-shrink: 0;
}
.chat-peer-avatar-proto {
  width: 48px; height: 48px; border-radius: 14px;
  background: var(--ipb-slate); display: flex; align-items: center;
  justify-content: center; font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 600; font-size: 17px; color: #fff; flex-shrink: 0;
}
.chat-peer-name-proto {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 17px; color: var(--ipb-navy);
}
.chat-peer-status-proto {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 13px; color: #7a8fa0; margin-top: 2px;
}
/* Transaction banner */
.txn-bar-proto {
  display: flex; align-items: center; gap: 14px; padding: 12px 28px;
  background: linear-gradient(90deg,rgba(86,124,141,0.07) 0%,rgba(200,217,230,0.07) 100%);
  border-bottom: 1px solid rgba(86,124,141,0.08);
  flex-shrink: 0; cursor: pointer;
}
.txn-icon-proto {
  width: 36px; height: 36px; border-radius: 10px;
  background: linear-gradient(145deg,var(--ipb-navy),var(--ipb-slate));
  display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0;
}
.txn-name-proto {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 14px; color: var(--ipb-navy);
}
.txn-hint-proto {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 12px; color: #7a8fa0;
}
.txn-status-proto {
  padding: 3px 10px; background: rgba(90,154,120,0.12);
  border-radius: 20px; font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 500; font-size: 12px; color: #2d6a4f;
}
/* Messages */
.messages-area-proto {
  flex: 1; overflow-y: auto;
  padding: 0 28px 12px; display: flex; flex-direction: column;
}
.msg-row-proto { display: flex; align-items: flex-end; gap: 10px; margin-bottom: 4px; }
.msg-row-proto--sent { flex-direction: row-reverse; }
.msg-avatar-proto {
  width: 32px; height: 32px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-display,'Cormorant Garamond',serif);
  font-weight: 600; font-size: 12px; color: #fff; flex-shrink: 0;
}
.msg-body-proto { max-width: 65%; }
.msg-row-proto--sent .msg-body-proto { display: flex; flex-direction: column; align-items: flex-end; }
.msg-bubble-proto {
  display: inline-block; padding: 11px 17px 12px;
  font-family: var(--font-body,'DM Sans',sans-serif); font-size: 15px;
  line-height: 1.55; color: var(--ipb-navy);
  background: #f3f3f3; border-radius: 5px 20px 20px 20px;
  box-shadow: 0 2px 8px rgba(46,65,86,0.06);
}
.msg-bubble-proto--sent {
  border-radius: 20px 5px 20px 20px;
  background: var(--ipb-navy); color: #fff;
  box-shadow: 0 4px 14px rgba(46,65,86,0.2);
}
.msg-time-proto {
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-size: 11px; color: rgba(122,143,160,0.7); padding: 4px 6px 0;
}
.date-divider-proto {
  display: flex; align-items: center; gap: 12px; padding: 14px 0 10px;
}
.date-divider-proto__line { flex: 1; height: 1px; background: rgba(46,65,86,0.07); }
.date-divider-proto__label {
  padding: 3px 12px; background: var(--ipb-cream); border-radius: 20px;
  font-family: var(--font-body,'DM Sans',sans-serif); font-size: 12px;
  color: #7a8fa0; letter-spacing: 0.5px;
}
/* Quick replies */
.quick-replies-proto {
  display: flex; flex-wrap: wrap; gap: 6px;
  padding: 0 28px 12px; flex-shrink: 0;
}
.quick-reply-btn {
  padding: 7px 16px; background: #fff;
  border: 1px solid rgba(86,124,141,0.2); border-radius: 20px;
  font-family: var(--font-body,'DM Sans',sans-serif); font-size: 13px;
  color: var(--ipb-slate); cursor: pointer; transition: background 0.15s, border-color 0.15s;
}
.quick-reply-btn:hover { background: rgba(86,124,141,0.07); border-color: rgba(86,124,141,0.4); }
/* Input area */
.msg-input-area-proto {
  padding: 14px 22px 16px; background: rgba(255,255,255,0.95);
  border-top: 1px solid rgba(46,65,86,0.06); flex-shrink: 0;
}
.msg-input-wrap-proto {
  display: flex; align-items: flex-end; gap: 8px;
  padding: 10px 10px 10px 18px; background: #fff;
  border: 1.5px solid var(--ipb-slate); border-radius: 16px;
  box-shadow: 0 0 0 3.5px rgba(86,124,141,0.1);
}
.msg-input-wrap-proto textarea {
  flex: 1; border: none; outline: none; resize: none;
  font-family: var(--font-body,'DM Sans',sans-serif); font-size: 15px;
  color: var(--ipb-navy); background: transparent; max-height: 120px;
  line-height: 1.5; padding: 2px 0;
}
.msg-input-wrap-proto textarea::placeholder { color: rgba(86,124,141,0.4); }
.msg-send-btn-proto {
  width: 44px; height: 44px; border-radius: 12px;
  border: none; background: var(--ipb-navy);
  display: flex; align-items: center; justify-content: center;
  color: #fff; cursor: pointer; transition: background 0.2s; flex-shrink: 0;
}
.msg-send-btn-proto:hover { background: #3a526a; }
.msg-tool-btn-proto {
  width: 38px; height: 38px; border-radius: 10px; border: none;
  background: transparent; display: flex; align-items: center; justify-content: center;
  color: var(--ipb-slate); cursor: pointer; transition: background 0.15s;
}
.msg-tool-btn-proto:hover { background: rgba(46,65,86,0.06); }
.msg-hint-proto {
  display: flex; align-items: center; gap: 5px; padding: 5px 6px 0;
  font-family: var(--font-body,'DM Sans',sans-serif);
  font-weight: 300; font-size: 11px; color: rgba(122,143,160,0.5);
}
</style>
@endpush

@section('content')
<div class="chat-layout">

  {{-- ── Left Sidebar ── --}}
  <aside class="chat-sidebar-proto">
    <div class="chat-sidebar-header">
      <h1 class="chat-sidebar-title">Messages</h1>
      <div class="chat-search-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(86,124,141,0.5)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Cari percakapan..." id="convSearch">
      </div>
    </div>

    <div class="conv-list-proto">
      @forelse($conversations as $conv)
      @if($loop->first)
      <p class="conv-section-label-proto">Terbaru</p>
      @endif
      @php $partner = $conv['partner']; @endphp
      <a href="{{ route('chat.index', ['with' => $partner->id]) }}"
         class="conv-item-proto {{ request('with') == $partner->id ? 'active' : '' }}">
<img src="{{ $partner->avatarUrl }}" 
     style="width:50px; height:50px; border-radius:14px; object-fit:cover; flex-shrink:0;" 
     alt="{{ $partner->initials }}">        <div class="conv-info-proto">
          <div class="conv-name-proto">{{ $partner->name }}</div>
          <div class="conv-preview-proto {{ ($conv['unreadCount'] ?? 0) > 0 ? 'fw-500' : '' }}">
            {{ $conv['lastMessage'] ? Str::limit($conv['lastMessage']->pesan, 38) : 'Mulai percakapan' }}
          </div>
        </div>
        <div class="conv-meta-proto">
          <span class="conv-time-proto">{{ $conv['lastMessage'] ? $conv['lastMessage']->created_at->diffForHumans(null,true) : '' }}</span>
          @if(($conv['unreadCount'] ?? 0) > 0)
          <div class="conv-unread-badge">{{ $conv['unreadCount'] }}</div>
          @endif
        </div>
      </a>
      @empty
      <div style="text-align:center; padding:40px 20px; color:#7a8fa0;">
        <i class="mdi mdi-chat-outline" style="font-size:48px; display:block; margin-bottom:12px; color:rgba(46,65,86,0.15);"></i>
        <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:13px; margin:0;">Belum ada percakapan</p>
      </div>
      @endforelse
    </div>
  </aside>

  {{-- ── Right Chat Panel ── --}}
  <main class="chat-panel-proto">

    @if($activePartner)

    {{-- Chat header --}}
    <div class="chat-header-proto">
      <div style="display:flex; align-items:center; gap:14px;">
<img src="{{ $activePartner->avatarUrl }}" 
     style="width:48px; height:48px; border-radius:14px; object-fit:cover; flex-shrink:0;" 
     alt="{{ $activePartner->initials }}">        <div>
          <div class="chat-peer-name-proto">{{ $activePartner->name }}</div>
          <div class="chat-peer-status-proto">
            @if($activePartner->nim)
            NIM: {{ $activePartner->nim }}
            @else
            Pengguna IPB Rental
            @endif
          </div>
        </div>
      </div>
      <div style="display:flex; gap:8px;">
        <a href="{{ route('chat.index', ['with' => $activePartner->id]) }}" style="width:40px;height:40px;border-radius:10px;background:rgba(46,65,86,0.06);border:none;display:flex;align-items:center;justify-content:center;color:var(--ipb-slate);" title="Refresh">
          <i class="mdi mdi-refresh" style="font-size:17px;"></i>
        </a>
      </div>
    </div>

    {{-- Transaction banner (if there's an active rental) --}}
    @php
      $activeRental = \App\Models\Rental::where(function($q) use ($activePartner) {
        $q->where(function($q2) use ($activePartner) {
          $q2->where('penyewa_id', auth()->id())->where('pemilik_id', $activePartner->id);
        })->orWhere(function($q2) use ($activePartner) {
          $q2->where('pemilik_id', auth()->id())->where('penyewa_id', $activePartner->id);
        });
      })->whereIn('status',['pending','dp_paid','active'])->with('item')->latest()->first();
    @endphp
    @if($activeRental)
    <a href="{{ route('rentals.show', $activeRental) }}" class="txn-bar-proto" style="text-decoration:none;">
      <div class="txn-icon-proto">
        <i class="mdi mdi-package-variant" style="font-size:17px;"></i>
      </div>
      <div class="txn-info" style="flex:1;">
        <div class="txn-name-proto">{{ $activeRental->item->nama }}</div>
        <div class="txn-hint-proto">Klik untuk lihat detail transaksi</div>
      </div>
      <span class="txn-status-proto">{{ $activeRental->statusLabel }}</span>
    </a>
    @endif

    {{-- Messages --}}
    <div class="messages-area-proto" id="messagesArea">
      @forelse($messages as $msg)
      @php $isMine = $msg->sender_id === auth()->id(); @endphp
      <div class="msg-row-proto {{ $isMine ? 'msg-row-proto--sent' : '' }}">
      <img src="{{ $isMine ? auth()->user()->avatarUrl : $activePartner->avatarUrl }}"
     style="width:32px; height:32px; border-radius:9px; object-fit:cover; flex-shrink:0;"
     alt="{{ $isMine ? auth()->user()->initials : $activePartner->initials }}">
        <div class="msg-body-proto">
          <div class="msg-bubble-proto {{ $isMine ? 'msg-bubble-proto--sent' : '' }}">{{ $msg->pesan }}</div>
          <div class="msg-time-proto">{{ $msg->created_at->format('H:i') }}</div>
        </div>
      </div>
      @empty
      <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; color:#7a8fa0; text-align:center; padding:40px;">
        <i class="mdi mdi-chat-outline" style="font-size:56px; color:rgba(46,65,86,0.1); margin-bottom:12px;"></i>
        <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:14px; margin:0;">Mulai percakapan dengan {{ $activePartner->name }}</p>
      </div>
      @endforelse
    </div>

    {{-- Quick replies --}}
    <div class="quick-replies-proto">
      <button class="quick-reply-btn" onclick="setMsg(this)">Masih tersedia ✓</button>
      <button class="quick-reply-btn" onclick="setMsg(this)">Kapan mau ambil?</button>
      <button class="quick-reply-btn" onclick="setMsg(this)">Bayar pas ketemu ya</button>
      <button class="quick-reply-btn" onclick="setMsg(this)">Terima kasih sudah sewa!</button>
      <button class="quick-reply-btn" onclick="setMsg(this)">Kondisi barang bagaimana?</button>
    </div>

    {{-- Message input --}}
    <div class="msg-input-area-proto">
      <form method="POST" action="{{ route('chat.store') }}" id="chatForm">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $activePartner->id }}">
        <div class="msg-input-wrap-proto">
          <textarea name="pesan" id="msgInput" rows="1" placeholder="Ketik pesan..." required
            onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();document.getElementById('chatForm').submit();}"></textarea>
          <div style="display:flex; align-items:center; gap:2px; flex-shrink:0;">
            <button type="button" class="msg-tool-btn-proto" title="Emoji">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                <line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>
              </svg>
            </button>
            <button type="submit" class="msg-send-btn-proto" title="Kirim (Enter)">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="msg-hint-proto">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 10 4 15 9 20"/><path d="M20 4v7a4 4 0 0 1-4 4H4"/>
          </svg>
          <span>Tekan Enter untuk kirim · Shift+Enter baris baru</span>
        </div>
      </form>
    </div>

    @else

    {{-- No conversation selected --}}
    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; color:#7a8fa0; text-align:center; padding:40px;">
      <i class="mdi mdi-chat-outline" style="font-size:72px; color:rgba(46,65,86,0.1); margin-bottom:16px;"></i>
      <h5 style="font-family:var(--font-display,'Cormorant Garamond',serif); font-size:28px; font-weight:400; color:#7a8fa0; margin-bottom:8px;">Pilih percakapan</h5>
      <p style="font-family:var(--font-body,'DM Sans',sans-serif); font-size:14px; margin:0;">Pilih kontak di sebelah kiri untuk memulai chat</p>
    </div>

    @endif
  </main>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const area = document.getElementById('messagesArea');
  if (area) area.scrollTop = area.scrollHeight;

  // Search filter
  const search = document.getElementById('convSearch');
  if (search) {
    search.addEventListener('input', function() {
      const q = this.value.toLowerCase();
      document.querySelectorAll('.conv-item-proto').forEach(item => {
        const name = item.querySelector('.conv-name-proto')?.textContent.toLowerCase() || '';
        item.style.display = name.includes(q) ? '' : 'none';
      });
    });
  }
});
function setMsg(btn) {
  const input = document.getElementById('msgInput');
  if (input) { input.value = btn.textContent.trim(); input.focus(); }
}
</script>
@endpush
