@php
    $isCompleted = $record->current_stage === 'completed';
    $isCanceled  = $record->current_stage === 'canceled';

    // === 1. Status utama & warna ===
    if ($isCompleted) {
        // FINISH: Security keluar
        $statusText  = '✓ Selesai';
        $borderColor = '#10b981'; // Hijau
        $bgColor     = '#ecfdf5';
        $activeStage = 'Mobil Keluar (Selesai)';
        $officerName = $record->security_out_officer ?: '-';
        $startTime   = $record->security_end;

    } elseif ($isCanceled) {
        $statusText  = '✕ Dibatalkan';
        $borderColor = '#ef4444';
        $bgColor     = '#fef2f2';
        $activeStage = 'Dibatalkan';
        $officerName = '-';
        $startTime   = $record->updated_at;

    } else {
        // Sedang berjalan (kuning)
        $borderColor = '#f59e0b';
        $bgColor     = '#fffbeb';

        switch ($record->current_stage) {
            // 1) Security masuk
            case 'security_in':
                $statusText  = '⟳ Menunggu Bongkar/Muat';
                $activeStage = 'Security Masuk';
                $officerName = $record->security_in_officer ?: 'Input Mandiri (Supir)';
                $startTime   = $record->security_start;
                break;

            // 2) Bongkar / muat dimulai
            case 'loading_started':
                $statusText  = '⟳ Sedang Bongkar/Muat';
                $activeStage = 'Proses Bongkar/Muat';
                $officerName = $record->loading_start_officer ?: '-';
                $startTime   = $record->loading_start;
                break;

            // 3) Bongkar / muat selesai
            case 'loading_ended':
                $statusText  = '⟳ Menunggu Proses TTB';
                $activeStage = 'Selesai Bongkar/Muat';
                $officerName = $record->loading_end_officer ?: '-';
                $startTime   = $record->loading_end;
                break;

            // 4) TTB dimulai
            case 'ttb_started':
                $statusText  = '⟳ Sedang Proses TTB';
                $activeStage = 'Proses TTB';
                $officerName = $record->ttb_start_officer ?: '-';
                $startTime   = $record->ttb_start;
                break;

            // 5) TTB selesai, belum distribusi ke supir
            case 'ttb_ended':
                $statusText  = '⟳ Menunggu Distribusi ke Supir';
                $activeStage = 'Selesai TTB';
                $officerName = $record->ttb_end_officer ?: '-';
                $startTime   = $record->ttb_end;
                break;

            // 6) Distribusi ke supir sudah dilakukan, nunggu security keluar
            case 'ttb_distributed':
                $statusText  = '⟳ Menunggu Mobil Keluar';
                $activeStage = 'Distribusi ke Supir Selesai';
                $officerName = $record->distribution_officer ?: '-';
                $startTime   = $record->distribution_at;
                break;

            // Fallback
            default:
                $statusText  = '⟳ Proses';
                $activeStage = 'Proses';
                $officerName = '-';
                $startTime   = null;
                break;
        }
    }

    // === 2. Label jenis bongkar / muat ===
    $isBongkar = $record->type === 'bongkar';
    $typeLabel = ucfirst($record->type);
    $typeStyle = $isBongkar
        ? 'background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa;'
        : 'background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe;';
@endphp


<div style="background: {{ $bgColor }}; border-radius: 10px; padding: 16px; margin-bottom: 12px; border-left: 5px solid {{ $borderColor }}; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    
    {{-- HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        <div>
            <h3 style="font-size: 15px; font-weight: bold; margin: 0 0 6px 0; color: #1f2937;">{{ $record->vehicle_name }}</h3>
            
            <div style="display: flex; gap: 8px; align-items: center;">
                <span style="font-family: monospace; background: white; border: 1px solid #e5e7eb; color: #374151; padding: 2px 6px; border-radius: 4px; font-size: 13px; font-weight: bold;">
                    {{ $record->plate_number }}
                </span>
                <span style="font-size: 11px; font-weight: bold; padding: 2px 6px; border-radius: 4px; {{ $typeStyle }}">
                    {{ $typeLabel }}
                </span>
            </div>
        </div>

        {{-- Badge Status --}}
        <div style="text-align: right;">
            <div style="background: {{ $borderColor }}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap; box-shadow: 0 1px 2px rgba(0,0,0,0.1); display: inline-block;">
                {{ $statusText }}
            </div>
        </div>
    </div>

    {{-- BODY: Info Proses Terkini --}}
    <div style="background: rgba(255,255,255,0.6); border-radius: 8px; padding: 12px; border: 1px solid rgba(0,0,0,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="font-size: 11px; color: #6b7280; margin: 0 0 2px 0; font-weight: 600; text-transform: uppercase;">Tahap Saat Ini</p>
                <p style="font-size: 14px; font-weight: bold; color: #374151; margin: 0;">{{ $activeStage }}</p>
            </div>
            <div style="text-align: right;">
                 @if($startTime)
                    <p style="font-size: 11px; color: #6b7280; margin: 0 0 2px 0;">Waktu & Petugas</p>
                    <div style="font-size: 13px; font-weight: 600; color: #111827;">
                        {{ $startTime->format('H:i') }} <span style="color: #9ca3af; font-weight: normal;">|</span> 👮 {{ $officerName ?? '-' }}
                    </div>
                    <div style="font-size: 11px; color: #6b7280;">
                        {{ $startTime->format('d M Y') }}
                    </div>
                @else
                     <p style="font-size: 12px; color: #9ca3af; margin: 0;">-</p>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Deskripsi --}}
    @if($record->description)
        <div style="margin-top: 12px; padding-top: 8px; border-top: 1px dashed {{ $borderColor }}40;">
             <p style="font-size: 13px; color: #4b5563; margin: 0; line-height: 1.4;">
                📝 {{ $record->description }}
            </p>
        </div>
    @endif

    {{-- Keterangan Tambahan --}}
    @if($record->keterangan)
        <div style="margin-top: 8px;">
             <p style="font-size: 13px; color: #6b7280; margin: 0; line-height: 1.4;">
                📌 {{ $record->keterangan }}
            </p>
        </div>
    @endif
</div>