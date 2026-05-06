<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-4 transition hover:shadow-md">
    
    {{-- HEADER: Judul & Status --}}
    <div class="flex justify-between items-start mb-6 pb-4 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-bold text-gray-800 leading-tight">{{ $record->company_name ?? $record->vehicle_name }}</h3>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="bg-gray-100 text-gray-600 text-xs font-mono px-2 py-1 rounded border border-gray-200">
                    {{ $record->plate_number }}
                </span>
                <span class="text-xs font-bold px-2 py-1 rounded border {{ $record->type == 'bongkar' ? 'bg-orange-50 text-orange-700 border-orange-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                    {{ strtoupper($record->type) }}
                </span>
            </div>
        </div>
        
        {{-- Badge Status --}}
        <div>
            @if($record->current_stage == 'completed')
                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full border border-emerald-200">
                    ✓ Selesai
                </span>
            @elseif($record->current_stage == 'canceled')
                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200">
                    ✕ Batal
                </span>
            @else
                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1.5 rounded-full border border-amber-200">
                    ⟳ Proses
                </span>
            @endif
        </div>
    </div>

    {{-- BODY: Timeline Vertical --}}
    <div class="relative pl-4 space-y-0">
        
        {{-- 1. SECURITY MASUK --}}
        @php 
            $hasSecurityIn = !is_null($record->security_start) || $record->security_in_officer !== null;
            $isSecurityCurrent = $record->current_stage == 'security_in';
        @endphp
        <div class="relative pb-6 border-l-2 {{ $hasSecurityIn ? 'border-emerald-400' : 'border-gray-200' }} pl-6 last:border-0">
            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 
                {{ $hasSecurityIn ? 'bg-emerald-500 border-emerald-100' : ($isSecurityCurrent ? 'bg-blue-500 border-blue-100' : 'bg-gray-200 border-white') }}"></div>
            
            <div class="flex justify-between items-start -mt-1">
                <div>
                    <h4 class="text-sm font-bold {{ $hasSecurityIn ? 'text-gray-800' : 'text-gray-400' }}">Mobil Masuk</h4>
                    <p class="text-xs text-gray-500 mt-0.5">
                        @if($record->security_in_officer) 👮 {{ $record->security_in_officer }} @else - @endif
                    </p>
                </div>
                <div class="text-right text-xs font-mono">
                    <div class="{{ $record->security_start ? 'text-gray-700' : 'text-gray-300' }}">
                        {{ $record->security_start ? $record->security_start->format('d/m H:i') : '--:--' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. BONGKAR / MUAT --}}
        @php 
            $hasLoadingStart = !is_null($record->loading_start);
            $hasLoadingEnd   = !is_null($record->loading_end);
            $isLoadingCurrent = in_array($record->current_stage, ['loading_started', 'loading_ended']);
        @endphp
        <div class="relative pb-6 border-l-2 {{ $hasLoadingEnd ? 'border-emerald-400' : ($hasLoadingStart ? 'border-emerald-400' : 'border-gray-200') }} pl-6">
            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 
                {{ $hasLoadingEnd ? 'bg-emerald-500 border-emerald-100' : ($isLoadingCurrent ? 'bg-blue-500 border-blue-100' : ($hasLoadingStart ? 'bg-emerald-500 border-emerald-100' : 'bg-gray-200 border-white')) }}"></div>
            
            <div class="flex justify-between items-start -mt-1">
                <div>
                    <h4 class="text-sm font-bold {{ $hasLoadingStart ? 'text-gray-800' : 'text-gray-400' }}">Bongkar / Muat</h4>
                    <div class="text-xs text-gray-500 mt-1 space-y-1">
                        <div>Mulai: @if($record->loading_start_officer) 👷 {{ $record->loading_start_officer }} @else - @endif</div>
                        <div>Selesai: @if($record->loading_end_officer) 🏁 {{ $record->loading_end_officer }} @else - @endif</div>
                    </div>
                </div>
                <div class="text-right space-y-1 text-xs font-mono">
                    <div class="{{ $hasLoadingStart ? 'text-gray-700' : 'text-gray-300' }}">
                        M: {{ $record->loading_start ? $record->loading_start->format('d/m H:i') : '--:--' }}
                    </div>
                    <div class="{{ $hasLoadingEnd ? 'text-gray-700' : 'text-gray-300' }}">
                        S: {{ $record->loading_end ? $record->loading_end->format('d/m H:i') : '--:--' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. OFFICER TTB/SJ --}}
        @php 
            $hasTtbStart  = !is_null($record->ttb_start);
            $hasTtbEnd    = !is_null($record->ttb_end);
            $isTtbCurrent = in_array($record->current_stage, ['ttb_started', 'ttb_ended']);
        @endphp
        <div class="relative pb-6 border-l-2 {{ $hasTtbEnd ? 'border-emerald-400' : ($hasTtbStart ? 'border-emerald-400' : 'border-gray-200') }} pl-6">
            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 
                {{ $hasTtbEnd ? 'bg-emerald-500 border-emerald-100' : ($isTtbCurrent ? 'bg-yellow-400 border-yellow-100' : 'bg-gray-200 border-white') }}"></div>
            
            <div class="flex justify-between items-start -mt-1">
                <div>
                    <h4 class="text-sm font-bold {{ $hasTtbStart ? 'text-gray-800' : 'text-gray-400' }}">Officer TTB/SJ</h4>
                    <div class="text-xs text-gray-500 mt-1 space-y-1">
                        <div>Mulai: @if($record->ttb_start_officer) 🧾 {{ $record->ttb_start_officer }} @else - @endif</div>
                        <div>Selesai: @if($record->ttb_end_officer) 🏁 {{ $record->ttb_end_officer }} @else - @endif</div>
                    </div>
                </div>
                <div class="text-right space-y-1 text-xs font-mono">
                    <div class="{{ $hasTtbStart ? 'text-gray-700' : 'text-gray-300' }}">
                        M: {{ $record->ttb_start ? $record->ttb_start->format('d/m H:i') : '--:--' }}
                    </div>
                    <div class="{{ $hasTtbEnd ? 'text-gray-700' : 'text-gray-300' }}">
                        S: {{ $record->ttb_end ? $record->ttb_end->format('d/m H:i') : '--:--' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. DISTRIBUSI --}}
        @php 
            $hasDistrib   = !is_null($record->distribution_at);
            $isDistribCurrent = $record->current_stage == 'ttb_distributed';
        @endphp
        <div class="relative pb-6 border-l-2 {{ $hasDistrib ? 'border-emerald-400' : ($isDistribCurrent ? 'border-yellow-400' : 'border-gray-200') }} pl-6">
            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 
                {{ $hasDistrib ? 'bg-emerald-500 border-emerald-100' : ($isDistribCurrent ? 'bg-yellow-400 border-yellow-100' : 'bg-gray-200 border-white') }}"></div>
            
            <div class="flex justify-between items-start -mt-1">
                <div>
                    <h4 class="text-sm font-bold {{ $hasDistrib ? 'text-gray-800' : 'text-gray-400' }}">Distribusi ke Supir</h4>
                    <div class="text-xs text-gray-500 mt-1">
                        @if($record->distribution_officer) 🚚 {{ $record->distribution_officer }} @else - @endif
                    </div>
                </div>
                <div class="text-right text-xs font-mono">
                    <span class="{{ $hasDistrib ? 'text-gray-700' : 'text-gray-300' }}">
                        {{ $record->distribution_at ? $record->distribution_at->format('d/m H:i') : '--:--' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- 5. SECURITY KELUAR --}}
        @php 
            $hasSecurityOut = !is_null($record->security_end);
        @endphp
        <div class="relative pl-6">
            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 {{ $hasSecurityOut ? 'bg-emerald-500 border-emerald-100' : 'bg-gray-200 border-white' }}"></div>
            
            <div class="flex justify-between items-start -mt-1">
                <div>
                    <h4 class="text-sm font-bold {{ $hasSecurityOut ? 'text-gray-800' : 'text-gray-400' }}">Mobil Keluar</h4>
                    <p class="text-xs text-gray-500 mt-0.5">
                        @if($record->security_out_officer) 👮 {{ $record->security_out_officer }} @else - @endif
                    </p>
                </div>
                <div class="text-right text-xs font-mono">
                    <span class="{{ $hasSecurityOut ? 'text-gray-700' : 'text-gray-300' }}">
                        {{ $record->security_end ? $record->security_end->format('d/m H:i') : '--:--' }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- FOOTER: Tombol Aksi --}}
    @if($record->current_stage != 'completed' && $record->current_stage != 'canceled')
        
        @php
            $showButton = false;
            $btnText    = 'Update Status';
            $btnColor   = 'bg-blue-600 hover:bg-blue-700';

            // ADMIN: selalu boleh edit
            if ($currentUserRole == 'admin') {
                $showButton = true;
                $btnColor   = 'bg-gray-800 hover:bg-gray-900';
            } 
            // SECURITY
            elseif ($currentUserRole == 'security') {

                // 1) Verifikasi MASUK untuk data input mandiri supir
                if ($record->current_stage == 'security_in' && $record->security_in_officer === 'Input Mandiri (Supir)') {
                    $showButton = true;
                    $btnText    = 'Verifikasi Masuk';
                    $btnColor   = 'bg-emerald-600 hover:bg-emerald-700';
                }
                // 2) Proses KELUAR setelah distribusi selesai
                elseif ($record->current_stage == 'ttb_distributed') {
                    $showButton = true;
                    $btnText    = 'Proses Keluar';
                    $btnColor   = 'bg-emerald-600 hover:bg-emerald-700';
                }

            }
            // LOADING (Bongkar/Muat)
            elseif ($currentUserRole == 'loading') {

                // Mulai Bongkar/Muat (hanya jika security_start sudah ada)
                if ($record->current_stage == 'security_in' && !is_null($record->security_start)) {
                    $showButton = true;
                    $btnText    = 'Mulai Bongkar/Muat';
                    $btnColor   = 'bg-blue-600 hover:bg-blue-700';
                }
                // Selesai Bongkar/Muat
                elseif ($record->current_stage == 'loading_started') {
                    $showButton = true;
                    $btnText    = 'Selesai Bongkar/Muat';
                    $btnColor   = 'bg-orange-600 hover:bg-orange-700';
                }
                // Distribusi ke Supir
                elseif ($record->current_stage == 'ttb_ended' && $record->type === 'bongkar') {
                    $showButton = true;
                    $btnText    = 'Distribusi ke Supir';
                    $btnColor   = 'bg-emerald-600 hover:bg-emerald-700';
                }

            }
            // OFFICER TTB
            elseif ($currentUserRole == 'ttb') {

                // 1) Mulai TTB
                if ($record->current_stage == 'loading_ended') {
                    $showButton = true;
                    $btnText    = 'Mulai TTB/SJ';
                    $btnColor   = 'bg-blue-600 hover:bg-blue-700';
                }
                // 2) Selesai TTB
                elseif ($record->current_stage == 'ttb_started') {
                    $showButton = true;
                    $btnText    = 'Selesai TTB/SJ';
                    $btnColor   = 'bg-orange-600 hover:bg-orange-700';
                }
                 // Distribusi ke Supir
                elseif ($record->current_stage == 'ttb_ended' && $record->type === 'muat') {
                    $showButton = true;
                    $btnText    = 'Distribusi ke Supir';
                    $btnColor   = 'bg-emerald-600 hover:bg-emerald-700';
                }
            }
        @endphp

        @if($showButton)
            <div class="mt-6 pt-4 border-t border-gray-100">
                <button wire:click="openUpdateModal({{ $record->id }})" 
                    class="w-full {{ $btnColor }} text-white py-3 rounded-lg font-bold transition shadow-sm flex justify-center items-center gap-2 active:scale-95">
                    <span>{{ $btnText }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        @else
            <div class="mt-4 pt-3 border-t border-gray-50 text-center">
                <span class="inline-block px-3 py-1 bg-gray-50 text-gray-400 text-xs italic rounded-full border border-gray-100">
                    ⏳ Menunggu giliran proses sebelumnya...
                </span>
            </div>
        @endif

    @endif
</div>
