<div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
    
    {{-- HEADER: JUDUL & SEARCH --}}
    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="pulse" style="width: 12px; height: 12px; background: #10b981; border-radius: 50%;"></div>
            <h2 style="font-size: 20px; font-weight: bold; margin: 0; color: #1f2937;">Update Terkini - Live</h2>
        </div>

        {{-- INPUT SEARCH (Disini tempatnya) --}}
        <div style="position: relative;">
            <input type="text" 
                   wire:model.live.debounce.500ms="search" 
                   placeholder="Cari Plat / Supir / PT..." 
                   style="width: 100%; padding: 10px 12px 10px 40px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; outline: none; transition: all 0.2s; background-color: #f9fafb;"
                   onfocus="this.style.borderColor='#2563eb'; this.style.backgroundColor='white';" 
                   onblur="this.style.borderColor='#e5e7eb'; this.style.backgroundColor='#f9fafb'">
            
            {{-- Ikon Search --}}
            <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af;">
                🔍
            </div>
        </div>
    </div>
    
    {{-- LIST DATA --}}
    <div wire:poll.30s id="liveRecordsList" style="max-height: 450px; overflow-y: auto; padding-right: 4px;">
        @forelse ($liveRecords as $record)
            
            {{-- MEMANGGIL CARD (File live-record-card.blade.php yang Mas kirim tadi) --}}
            @include('livewire.partials.live-record-card', ['record' => $record])

        @empty
            <div style="text-align: center; padding: 32px; background: #f9fafb; border-radius: 8px; border: 1px dashed #e5e7eb;">
                @if(!empty($search))
                    <p style="font-size: 14px; color: #6b7280;">Tidak ditemukan data: <br><strong>"{{ $search }}"</strong></p>
                @else
                    <p style="font-size: 14px; color: #9ca3af;">Belum ada data hari ini.</p>
                @endif
            </div>
        @endforelse
    </div>

    {{-- TOMBOL LOAD MORE --}}
    @if ($liveRecords->count() < $liveTotal)
        <div style="text-align: center; margin-top: 16px; border-top: 1px solid #f3f4f6; padding-top: 16px;">
            <button wire:click="loadMoreLive" class="btn" style="background: white; color: #2563eb; border: 1px solid #2563eb; padding: 8px 20px; border-radius: 99px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#2563eb'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#2563eb';">
                ⬇ Muat Lebih Banyak ({{ $liveTotal - $liveRecords->count() }} lagi)
            </button>
        </div>
    @endif
</div>