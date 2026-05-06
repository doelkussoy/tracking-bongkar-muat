<div class="min-h-full">

    {{-- TAMPILAN JIKA SUDAH LOGIN (DASHBOARD) --}}
    @if (Auth::check())
    @php $user = Auth::user(); @endphp

    <div class="flex flex-col min-h-screen" style="background: #f3f4f6;">

        <main class="flex-grow">

            {{-- Header Dashboard --}}
            <div style="background: #2563eb; color: white; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="max-width: 2000px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h1 style="font-size: 24px; font-weight: bold; margin: 0 0 4px 0;">Tracking Bongkar Muat</h1>
                        <p style="font-size: 14px; margin: 0; opacity: 0.9;">PT CBA Chemical Industry</p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        {{-- MULAI TAMBAHAN: Menu Kelola Password (Hanya Admin) --}}
                        @if($user->role === 'admin')
                        {{-- Perhatikan bagian route() di bawah ini sudah disesuaikan --}}
                        <a href="{{ route('admin.users.passwords') }}"
                            style="color: white; text-decoration: none; font-size: 14px; background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 6px; margin-right: 8px;">
                            🔑 Kelola Pengguna
                        </a>
                        @endif
                        {{-- SELESAI TAMBAHAN --}}
                        <span style="font-size: 14px; opacity: 0.9;">👤 {{ $user->name }}</span>
                        <button wire:click="logout" class="btn" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; cursor: pointer;" wire:loading.attr="disabled">
                            Logout
                        </button>
                    </div>
                </div>
            </div>

            <div style="max-width: 2000px; margin: 0 auto; padding: 16px;">

                {{-- Flash Messages --}}
                @if (session()->has('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Sukses!</strong> {{ session('message') }}
                </div>
                @endif
                @if (session()->has('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Error!</strong> {{ session('error') }}
                </div>
                @endif

                {{-- Tombol Tambah (Security Only) --}}
                @if ($user->role === 'security')
                <button wire:click="openNewEntryModal" class="btn" style="width: 100%; background: #2563eb; color: white; padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    + Tambah Kendaraan Baru
                </button>
                @endif




                {{-- TAMPILAN ADMIN (TABEL) --}}
                @if ($user->role === 'admin')

                <div style="display: flex; gap: 8px; margin-bottom: 12px;">
                    <div>
                        <label style="font-size: 12px; color: #6b7280;">Tanggal Mulai</label>
                        <input type="date" wire:model.live="start_date"
                            style="padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                    </div>

                    <div>
                        <label style="font-size: 12px; color: #6b7280;">Tanggal Akhir</label>
                        <input type="date" wire:model.live="end_date"
                            style="padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                    </div>

                    @if ($start_date || $end_date)
                    <div style="display:flex;align-items:flex-end;">
                        <button wire:click="resetDates"
                            style="padding:8px 12px;background:#ef4444;color:white;border-radius:6px;">
                            Reset
                        </button>
                    </div>
                    @endif
                </div>
                <div style="margin-bottom: 16px;">
                    <button wire:click="exportExcel" class="btn"
                        style="width: 100%; background: #10b981; color: white; padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                        wire:loading.attr="disabled" wire:target="exportExcel">
                        <span wire:loading.remove wire:target="exportExcel">📥 Export ke Excel</span>
                        <span wire:loading wire:target="exportExcel">⏳ Memproses...</span>
                    </button>
                </div>

                <div style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
                    {{-- Search & Per Page --}}
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <select wire:model.live="perPage"
                                style="padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="500">500</option>
                                <option value="750">750</option>
                                <option value="1000">1000</option>
                            </select>
                            <span style="font-size: 14px; color: #6b7280;">entries per page</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label for="statusFilter" style="font-size: 14px; color: #6b7280;">Status:</label>
                            <select id="statusFilter" wire:model.live="statusFilter"
                                style="padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                                <option value="">Semua</option>
                                <option value="completed">Selesai</option>
                            </select>
                            <label for="search" style="font-size: 14px; color: #6b7280;">Search:</label>
                            <input wire:model.live.debounce.500ms="search" id="search" type="text"
                                placeholder="Cari kendaraan / plat / supir..."
                                style="padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                        </div>
                    </div>

                    {{-- Bulk Delete Toolbar --}}
                    @if(count($selectedIds) > 0)
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 10px 16px;">
                        <span style="font-size: 14px; color: #b91c1c; font-weight: 600;">
                            🗂️ {{ count($selectedIds) }} data dipilih
                        </span>
                        <button
                            type="button"
                            onclick="if(confirm('Yakin hapus {{ count($selectedIds) }} data yang dipilih? Tindakan ini tidak dapat dibatalkan!')) { @this.call('deleteSelected') }"
                            style="background: #ef4444; color: white; border: none; border-radius: 6px; padding: 6px 14px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                            🗑️ Hapus yang Dipilih
                        </button>
                        <button
                            type="button"
                            wire:click="$set('selectedIds', [])"
                            style="background: #6b7280; color: white; border: none; border-radius: 6px; padding: 6px 14px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='#4b5563'" onmouseout="this.style.background='#6b7280'">
                            ✕ Batal Pilih
                        </button>
                    </div>
                    @endif

                    @php $currentPageIds = $userRecords->pluck('id')->toArray(); @endphp

                    <div style="overflow-x: auto;" wire:loading.style="opacity: 0.5;" wire:target="search, perPage, page, statusFilter">
                        <table style="width: 100%; min-width: 1200px; border-collapse: collapse; white-space: nowrap;">
                            <thead style="background: #f3f4f6;">
                                <tr>
                                    <th style="padding: 12px 16px; text-align: center; width: 44px;">
                                        <input
                                            type="checkbox"
                                            wire:model.live="selectAll"
                                            wire:change="toggleSelectAll({{ json_encode($currentPageIds) }})"
                                            title="Pilih semua di halaman ini"
                                            style="width: 16px; height: 16px; cursor: pointer; accent-color: #ef4444;"
                                        >
                                    </th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Kendaraan</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Supir</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Jenis</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Security</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Bongkar / Muat</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Officer TTB/SJ</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Distribusi</th>
                                    <th style="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Status</th>
                                    <th styl e="padding: 12px 16px; text-align: left; font-size: 14px; font-weight: 600; color: #374151;">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userRecords as $record)
                                @if($record)
                                @php
                                // Label status lebih rinci
                                $statusLabel = 'Proses';
                                $statusClass = 'background: #f59e0b; color: white;';
                                if ($record->current_stage === 'completed') {
                                $statusLabel = '✓ Selesai';
                                $statusClass = 'background: #10b981; color: white;';
                                } elseif ($record->current_stage === 'canceled') {
                                $statusLabel = '✕ Dibatalkan';
                                $statusClass = 'background: #ef4444; color: white;';
                                } elseif ($record->current_stage === 'ttb_distributed') {
                                $statusLabel = 'Menunggu Keluar (Security)';
                                } elseif (in_array($record->current_stage, ['ttb_started', 'ttb_ended'])) {
                                $statusLabel = 'Proses TTB/SJ';
                                } elseif (in_array($record->current_stage, ['loading_started', 'loading_ended'])) {
                                $statusLabel = 'Proses Bongkar/Muat';
                                } elseif ($record->current_stage === 'security_in') {
                                $statusLabel = 'Menunggu Bongkar/Muat';
                                }
                                @endphp
                                <tr style="border-top: 1px solid #e5e7eb; {{ in_array($record->id, $selectedIds) ? 'background: #fff5f5;' : '' }}">
                                    {{-- Checkbox --}}
                                    <td style="padding: 12px 16px; text-align: center; width: 44px;">
                                        <input
                                            type="checkbox"
                                            wire:model.live="selectedIds"
                                            value="{{ $record->id }}"
                                            style="width: 16px; height: 16px; cursor: pointer; accent-color: #ef4444;"
                                        >
                                    </td>
                                    {{-- Kendaraan --}}
                                    <td style="padding: 12px 16px; font-size: 14px; color: #1f2937;">
                                        <div style="font-weight: 600;">{{ $record->vehicle_name}}</div>
                                        @if($record->company_name)
                                        <div style="font-size: 12px; color: #4b5563;">
                                            {{ $record->company_name}}
                                        </div>
                                        @endif
                                        <div style="font-size: 12px; color: #6b7280;">
                                            {{ $record->plate_number     }}
                                            @if($record->destination)
                                            • {{ $record->destination }}
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Supir --}}
                                    <td style="padding: 12px 16px; font-size: 14px; color: #1f2937;">
                                        <div style="font-weight: 600;">{{ $record->driver_name ?? '-' }}</div>
                                        <div style="font-size: 12px; color: #6b7280;">
                                            {{ $record->driver_phone ?? '' }}
                                        </div>
                                        <div style="font-size: 12px; color: #9ca3af;">
                                            {{ $record->driver_identity ?? '' }}
                                        </div>
                                    </td>

                                    {{-- Jenis --}}
                                    <td style="padding: 12px 16px; font-size: 14px; color: #1f2937;">
                                        <span
                                            class="px-2 py-1 text-xs font-bold rounded {{ $record->type == 'bongkar' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ strtoupper($record->type) }}
                                        </span>
                                    </td>

                                    {{-- Security --}}
                                    <td style="padding: 12px 16px; font-size: 12px; color: #1f2937; line-height: 1.5;">
                                        <div>
                                            <span style="font-weight: 600;">IN:</span>
                                            {{ $record->security_start ? $record->security_start->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div style="color:#6b7280;">
                                            👮 {{ $record->security_in_officer ?? '-' }}
                                        </div>
                                        <div style="margin-top:4px;">
                                            <span style="font-weight: 600;">OUT:</span>
                                            {{ $record->security_end ? $record->security_end->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div style="color:#6b7280;">
                                            👮 {{ $record->security_out_officer ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Bongkar / Muat --}}
                                    <td style="padding: 12px 16px; font-size: 12px; color: #1f2937; line-height: 1.5;">
                                        <div>
                                            <span style="font-weight: 600;">Mulai:</span>
                                            {{ $record->loading_start ? $record->loading_start->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div style="color:#6b7280;">
                                            👷 {{ $record->loading_start_officer ?? '-' }}
                                        </div>
                                        <div style="margin-top:4px;">
                                            <span style="font-weight: 600;">Selesai:</span>
                                            {{ $record->loading_end ? $record->loading_end->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div style="color:#6b7280;">
                                            🏁 {{ $record->loading_end_officer ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Officer TTB/SJ --}}
                                    <td style="padding: 12px 16px; font-size: 12px; color: #1f2937; line-height: 1.5;">
                                        <div>
                                            <span style="font-weight: 600;">Mulai TTB/SJ:</span>
                                            {{ $record->ttb_start ? $record->ttb_start->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div style="color:#6b7280;">
                                            📝 {{ $record->ttb_start_officer ?? '-' }}
                                        </div>
                                        <div style="margin-top:4px;">
                                            <span style="font-weight: 600;">Selesai TTB/SJ:</span>
                                            {{ $record->ttb_end ? $record->ttb_end->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div style="color:#6b7280;">
                                            🏁 {{ $record->ttb_end_officer ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Distribusi --}}
                                    <td style="padding: 12px 16px; font-size: 12px; color: #1f2937; line-height: 1.5;">
                                        <div>
                                            <span style="font-weight: 600;">Waktu:</span>
                                            {{ $record->distribution_at ? $record->distribution_at->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div style="color:#6b7280;">
                                            🚚 {{ $record->distribution_officer ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td style="padding: 12px 16px; font-size: 14px;">
                                        <span
                                            style="padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td style="padding: 12px 16px; font-size: 14px;">
                                        <div style="display: flex; gap: 8px;">
                                            <button wire:click="openUpdateModal({{ $record->id }})"
                                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-400">Edit</button>

                                            <button type="button" wire:click="deleteTracking({{ $record->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-400">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="10"
                                        style="padding: 32px; text-align: center; color: #9ca3af;">
                                        Tidak ada data.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                        {{ $userRecords->links() }}
                    </div>
                </div>
                @else
                {{-- (bagian card worker tetap seperti sebelumnya) --}}
                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px;">
                    <div></div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <label for="search_card" style="font-size:14px; color:#6b7280;">Cari:</label>
                        <input id="search_card" wire:model.live.debounce.500ms="search" type="text" placeholder="Cari kendaraan / plat / supir..." style="padding:8px 12px; border:1px solid #e5e7eb; border-radius:6px; font-size:14px;">
                    </div>
                </div>
                <div id="recordsList">
                    @forelse ($userRecords as $record)
                    @if($record)
                    @include('livewire.partials.record-card', [
                    'record' => $record,
                    'currentUserRole' => $user->role,
                    ])
                    @endif
                    @empty
                    <div
                        style="background: white; border-radius: 12px; padding: 32px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <p style="font-size: 16px; color: #9ca3af; margin: 0;">Tidak ada data untuk tahap ini</p>
                    </div>
                    @endforelse
                </div>
                @endif


            </div>
        </main>


        @include('partials.dashboard-footer')

    </div>

    {{-- TAMPILAN BELUM LOGIN (MENU UTAMA SUPIR / PETUGAS) --}}
    @else
    <div class="flex flex-col min-h-screen" style="background: linear-gradient(135deg, #2563eb 0%, #10b981 100%);">
        <main class="flex-grow">
            <div style="max-width: 420px; margin: 0 auto; padding: 32px 16px 40px;">

                <div style="text-align: center; margin-bottom: 32px;">
                    <h1 style="font-size: 32px; font-weight: bold; color: white; margin: 0 0 8px 0;">Tracking Bongkar Muat</h1>
                    <p style="font-size: 18px; color: rgba(255,255,255,0.9); margin: 0;">PT CBA Chemical Industry</p>
                </div>

                {{-- TOMBOL INPUT MANDIRI (SUPIR) --}}
                <div style="margin-bottom: 32px;">
                    <a href="{{ route('public.input') }}"
                        style="width: 100%; background: white; color: #2563eb; font-weight: bold; padding: 20px;
                                    border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                                    display: flex; align-items: center; justify-content: center; gap: 12px;
                                    border: none; cursor: pointer;">
                        <span style="font-size: 28px;">🚚</span>
                        <span style="font-size: 18px;">Input Kendaraan (Supir)</span>
                    </a>

                    <p style="text-align: center; color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 8px;">
                        Untuk Supir/Vendor: Klik tombol di atas untuk lapor masuk.
                    </p>
                </div>

                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                    <div style="height: 1px; background: rgba(255,255,255,0.3); flex: 1;"></div>
                    <span style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: bold;">ATAU LOGIN PETUGAS</span>
                    <div style="height: 1px; background: rgba(255,255,255,0.3); flex: 1;"></div>
                </div>

                {{-- FORM LOGIN --}}
                <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); margin-bottom: 24px;">
                    <h2 style="font-size: 24px; font-weight: bold; margin: 0 0 24px 0; color: #1f2937; text-align: center;">Login Petugas</h2>
                    <form wire:submit.prevent="login">
                        @if ($loginError)
                        <div style="background: #fef2f2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 16px; text-align: center;">{{ $loginError }}</div>
                        @endif
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 8px; color: #1f2937;">Role:</label>
                            <select wire:model="login_role" required style="width: 100%; padding: 14px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px; color: #1f2937; background: white;">
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="security">Security</option>
                                <option value="loading">Bongkar/Muat</option>
                                <option value="ttb">Officer TTB/SJ</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 8px; color: #1f2937;">Password:</label>
                            <input wire:model="login_password" type="password" required placeholder="Masukkan password" style="width: 100%; padding: 14px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px; color: #1f2937; background: white;">
                        </div>
                        <button type="submit" class="btn" style="width: 100%; background: #2563eb; color: white; padding: 14px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" wire:loading.attr="disabled" wire:target="login">
                            <span wire:loading.remove wire:target="login">🔐 Login</span>
                            <span wire:loading wire:target="login">⏳ Memproses...</span>
                        </button>
                    </form>
                </div>

                <div style="margin-bottom: 50px;">
                    <livewire:live-update-widget lazy />
                </div>

            </div>
        </main>
        <div style="max-width: 420px; margin: 0 auto; padding: 0 16px 16px;">
            @include('partials.login-footer')
        </div>
    </div>
    @endif


    {{-- MODAL (Shared for All Forms) --}}
    @if ($showModal)
    <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; padding: 16px; overflow-y: auto;">
        <div style="max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; padding: 24px; box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
            <form wire:submit.prevent="handleSubmit">
                <h2 style="font-size: 20px; font-weight: bold; margin: 0 0 20px 0;">
                    @if ($modalAction === 'public_create')
                    🚚 Input Data (Supir)
                    @elseif ($modalAction === 'create')
                    Input Kendaraan Masuk (Security)
                    @elseif (Auth::check() && Auth::user()->role == 'admin')
                    Edit Data (Admin)
                    @else
                    Update Proses
                    @endif
                </h2>

                <div id="formFields" class="space-y-4">

                    {{-- STEP 0: PILIH JENIS KEGIATAN --}}
                    @if (in_array($modalAction, ['create', 'public_create']) || (Auth::check() && Auth::user()->role === 'admin'))
                    <div style="background: #f9fafb; padding: 12px 16px; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 10px;">
                        <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">
                            Jenis Kegiatan <span style="color: red">*</span>
                        </label>
                        <select wire:model.live="type"
                            style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; background: white;">
                            <option value="">-- Pilih Jenis Kegiatan --</option>
                            <option value="bongkar">BONGKAR</option>
                            <option value="muat">MUAT</option>
                        </select>
                        <p style="font-size: 12px; color: #6b7280; margin-top: 6px;">
                            Pilih dulu jenis kegiatan. Form di bawah akan menyesuaikan (BONGKAR / MUAT).
                        </p>
                        @error('type')
                        <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    {{-- Kalau belum pilih type, jangan tampilkan form detail --}}
                    @if (empty($type))
                    {{-- no warning shown here per request --}}
                    @else
                    {{-- 1. DATA KENDARAAN (Create / Public / Admin) --}}
                    @if (in_array($modalAction, ['create', 'public_create']) || (Auth::check() && Auth::user()->role === 'admin'))
                    <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 16px;">
                        <h3 style="font-weight: bold; color: #374151; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;">
                            Data Kendaraan & Supir
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">

                            {{-- Nama Instansi / Vendor --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Instansi / Vendor <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        list="companyNames"
                                        wire:model="company_name"
                                        class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="Ketik atau pilih instansi / vendor...">
                                    <datalist id="companyNames">
                                        <option value="ABHISHEK IMPEX"></option>
                                        <option value="ACD"></option>
                                        <option value="ADIL MAKMUR FAJAR, PT (AMCO)"></option>
                                        <option value="AGRINDO SURYA ABADI, PT"></option>
                                        <option value="AGRO MULIA, CV"></option>
                                        <option value="AGRODAN CHEMICAL CO., LTD"></option>
                                        <option value="AGROTECH PESTICIDE INDUSTRY"></option>
                                        <option value="AKINO WAHANAMULIA, PT"></option>
                                        <option value="AKR CORPORINDO TBK, PT"></option>
                                        <option value="ALFA KIMIA, TOKO"></option>
                                        <option value="ALKINDO NARATAMA TBK, PT"></option>
                                        <option value="ANEKA KARYA, CV"></option>
                                        <option value="ANHUI HUAXING CHEMICAL INDUSTRY"></option>
                                        <option value="ANHUI HUAXING CHEMICAL INDUSTRY (CNY)"></option>
                                        <option value="ANUGERAH PRIMATAMA, PT"></option>
                                        <option value="ANUGRAH KIMIA ARIWIDYA, PT"></option>
                                        <option value="ARGATA PUTRA"></option>
                                        <option value="ARTA PITAGIRI LESTARI, PT"></option>
                                        <option value="ARTEMIS PRIMAVERA KEMINDO, PT"></option>
                                        <option value="ASIA PRIMA PACKAGING, PT"></option>
                                        <option value="ASTANA WIRA KARYA, PT"></option>
                                        <option value="AZELIS INDONESIA DISTRIBUSI, PT"></option>
                                        <option value="BAHAGIA FA"></option>
                                        <option value="BAHTERA ADI JAYA, PT"></option>
                                        <option value="BAMBANG"></option>
                                        <option value="BANJAR"></option>
                                        <option value="BARIA BULK TERMINAL, PT"></option>
                                        <option value="BASF DISTRIBUTION INDONESIA, PT"></option>
                                        <option value="BERKAH BERSAMA, TOKO"></option>
                                        <option value="BETA CHEMICALS, LTD."></option>
                                        <option value="BETA CHEMICALS, LTD. (CNY)"></option>
                                        <option value="BRENNTAG, PT"></option>
                                        <option value="BUKIT MEGA MAS ABADI, PT"></option>
                                        <option value="BUKIT TINGGI"></option>
                                        <option value="BUKIT WARNA ABADI, PT"></option>
                                        <option value="CAHAYA FAJAR GEMILANG, CV"></option>
                                        <option value="CAKRAWALA MEGA INDAH, PT"></option>
                                        <option value="CANDI GASINDO UTAMA, PT"></option>
                                        <option value="CATALITE INDONESIA, PT (IDR)"></option>
                                        <option value="CATUR AGRODAYA MANDIRI, PT"></option>
                                        <option value="CHANDRA ASRI PACIFIC TBK, PT"></option>
                                        <option value="CHEMPLAST INDONESIA, PT"></option>
                                        <option value="CHINA JIANGSU INTERNATIONAL INDONESIA, PT"></option>
                                        <option value="CIANJUR"></option>
                                        <option value="CIPTA JAYA MANDIRI, CV"></option>
                                        <option value="CIPTA MITRA WARNA SEMPURNA, PT"></option>
                                        <option value="CITRA SPS"></option>
                                        <option value="CJI OVERSEAS IMPORT AND EXPORT LTD"></option>
                                        <option value="CJI OVERSEAS IMPORT AND EXPORT LTD (CNY)"></option>
                                        <option value="DELTACIPTA SARANAPROMOSI, PT"></option>
                                        <option value="DHARMA COLOUR"></option>
                                        <option value="DINAMIKA CIPTA MULIA PRIMATAMA, PT"></option>
                                        <option value="DJULINAR METTA"></option>
                                        <option value="EASTCHEM CO., LTD."></option>
                                        <option value="ENERGI HIJAU SAMOEDERA BERSAUDARA, PT"></option>
                                        <option value="ETONG"></option>
                                        <option value="ETONG CHEMICAL INDONESIA, PT"></option>
                                        <option value="EXCEL"></option>
                                        <option value="EXCEL MEG INDO, PT"></option>
                                        <option value="EXTRANS"></option>
                                        <option value="FADILLAH MANDIRI, CV"></option>
                                        <option value="FARACO GLOBAL TECHNOLOGY, PT"></option>
                                        <option value="FH AGROCHEMICAL INTERNATIONAL TRADE PTE., LTD."></option>
                                        <option value="FH AGROCHEMICAL INTERNATIONAL TRADE PTE.LTD (CNY)"></option>
                                        <option value="GALIC BINA MADA, PT"></option>
                                        <option value="GANA MAS PRIMA, PT"></option>
                                        <option value="GANDIWA SUKSES BERSAMA, PT"></option>
                                        <option value="GARAM, PT"></option>
                                        <option value="GARUDA MAS LESTARI, PT"></option>
                                        <option value="GENUS"></option>
                                        <option value="GLOBALINDO INTI PERSADA, PT"></option>
                                        <option value="GnG ASIA BINTANG JAYA, PT"></option>
                                        <option value="GPI"></option>
                                        <option value="GRAHA BATAVIA MANDIRI, PT"></option>
                                        <option value="GRAHA JAYA PRATAMA KINERJA, PT"></option>
                                        <option value="GREENFIELD CHEMICAL INDUSTRY CO., LIMITED (CNY)"></option>
                                        <option value="GUANGAN LIER CHEMICAL CO., LTD"></option>
                                        <option value="HALIM SAKTI PRATAMA, PT"></option>
                                        <option value="HALIM SAMUDRA INTERUTAMA, PT"></option>
                                        <option value="HEBEI SHANLI CHEMICAL CO., LTD."></option>
                                        <option value="HEBEI SHANLI CHEMICAL CO., LTD. (CNY)"></option>
                                        <option value="HEBEI SHUANGJI CHEMICAL CO., LTD (CNY)"></option>
                                        <option value="HEMANI INDUSTRIES, LTD."></option>
                                        <option value="HERIANTO"></option>
                                        <option value="HERSUM CO., LIMITED."></option>
                                        <option value="HEXA PRIMA PERSADA, PT"></option>
                                        <option value="HITASE INDONESIA, PT"></option>
                                        <option value="HUBEI BENXING ARGOCHEMICAL CO., LTD (CNY)"></option>
                                        <option value="HUBEI BENXING SUPPLY CHAIN MANAGEMENT CO., LTD"></option>
                                        <option value="HYPHEN CHEMICALS LIMITED (CNY)"></option>
                                        <option value="ILUVA GRAVURE INDUSTRY, PT"></option>
                                        <option value="INDOCHEMICAL CITRA KIMIA, PT"></option>
                                        <option value="INDOKEMIKA JAYATAMA, PT"></option>
                                        <option value="INDOKO BUMI PERMATA, PT"></option>
                                        <option value="INDO-SINO AGROCHEMICAL, PT"></option>
                                        <option value="INIKO KARYA PERSADA, PT"></option>
                                        <option value="INNER MONGOLIA MIRACULOUS CROP SCIENCE CO., LTD."></option>
                                        <option value="INNER MONGOLIA MIRACULOUS CROP SCIENCE CO., LTD. (CNY)"></option>
                                        <option value="INTI EVERSPRING INDONESIA, PT"></option>
                                        <option value="IPROCHEM COMPANY LIMITED"></option>
                                        <option value="JAKARTA GLOBALINDO, PT"></option>
                                        <option value="JAMBI"></option>
                                        <option value="JAVA TAIKO DRUM INDUSTRIES, PT"></option>
                                        <option value="JEBSEN & JESSEN CHEMICALS GMBH"></option>
                                        <option value="JEBSEN & JESSEN INGREDIENTS INDONESIA"></option>
                                        <option value="JETSET POLYCHROME, PT"></option>
                                        <option value="JIANGSU INSTITUTE OF ECOMONES CO., LTD"></option>
                                        <option value="JIANGSU INSTITUTE OF ECOMONES CO., LTD (YUAN)"></option>
                                        <option value="JIANGSU NEW ENERGY CROP PROTECTION CO., LTD"></option>
                                        <option value="JIANGSU NEW ENERGY CROP PROTECTION CO.,LTD (CNY)"></option>
                                        <option value="JIVA AGRICULTURE INDONESIA"></option>
                                        <option value="JOKOIS"></option>
                                        <option value="JSK"></option>
                                        <option value="JUSTUS KIMIARAYA, PT"></option>
                                        <option value="KALIMANTAN"></option>
                                        <option value="KARYA INDAH MULTIGUNA, PT"></option>
                                        <option value="LAKIN KEMAS GLOBAL, PT"></option>
                                        <option value="LAMBERTI INDONESIA, PT"></option>
                                        <option value="LIER CHEMICAL CO., LTD."></option>
                                        <option value="LIER CHEMICAL CO.,LTD. (CNY)"></option>
                                        <option value="LOTTE CHEMICAL TITAN NUSANTARA, PT"></option>
                                        <option value="MAHA KIMIA INDONESIA, PT"></option>
                                        <option value="MAKMUR FALLAS, CV"></option>
                                        <option value="MATA PELANGI TRADINDO, PT"></option>
                                        <option value="MCL"></option>
                                        <option value="MEDIA KARYA MAJASARI, PT"></option>
                                        <option value="MEGA CIPTA UTAMA, CV"></option>
                                        <option value="MEGAH CONVERTING, PT"></option>
                                        <option value="MENARA CIPTA PROFILINDO, PT"></option>
                                        <option value="MOBIL PABRIK"></option>
                                        <option value="MITRA ABADI KEMASINDO, PT"></option>
                                        <option value="MITRA MAKMUR DWIJAYA, PT"></option>
                                        <option value="MITRA SANTOSA ABADI, CV"></option>
                                        <option value="MULTI MAKMUR INDAH INDUSTRI, PT"></option>
                                        <option value="MULTI SARANA INDOTANI, PT"></option>
                                        <option value="MULTIBOX INDAH, PT"></option>
                                        <option value="MULYA ADHI PARAMITA, PT"></option>
                                        <option value="NABE CRYSTAL MANDIRI, PT"></option>
                                        <option value="NAGARI AGRIMUKTI, CV"></option>
                                        <option value="NANJING LIMIN CHEMICAL CO., LTD (CNY)"></option>
                                        <option value="NANTONG JIANGSHAN AGROCHEMICAL & CHEMICALS CO., LTD (CNY)"></option>
                                        <option value="NANTONG JIANGSHAN AGROCHEMICAL &CHEMICALS LIMITED LIABILITY Co."></option>
                                        <option value="NAVA HITA KARANA, PT"></option>
                                        <option value="NINGXIA WYNCA TECHNOLOGY CO., LTD"></option>
                                        <option value="NINGXIA WYNCA TECHNOLOGY CO., LTD (CNY)"></option>
                                        <option value="NOPOSION AGROCHEMICALS INDONESIA, PT"></option>
                                        <option value="OMNI KEMAS INDUSTRY, PT"></option>
                                        <option value="OMNI PLUS SYSTEM, PT"></option>
                                        <option value="PALKO SARI EKA, PT"></option>
                                        <option value="PELANGI DWIPUTRA KIMIARAYA, PT"></option>
                                        <option value="PELITA ABADI SENTOSA, PT"></option>
                                        <option value="PLASTRADE WORLD, PT"></option>
                                        <option value="POLYMARK REAINDO PLUS, PT"></option>
                                        <option value="PRIMA JAYA INDAH LESTARI, PT"></option>
                                        <option value="PRIMA KARYA BERJAYA, PT"></option>
                                        <option value="PRIMA KARYA BERJAYA, PT (CNY)"></option>
                                        <option value="QINGDAO HISIGMA CHEMICALS CO., LTD."></option>
                                        <option value="QINGDAO HISIGMA CHEMICALS CO., LTD. (CNY)"></option>
                                        <option value="ROHA LAUTAN PEWARNA, PT"></option>
                                        <option value="SAGARA PRIMA PERKASA, PT"></option>
                                        <option value="SAIKU PUTRA EXPRESS"></option>
                                        <option value="SAMCHEM PRASANDHA, PT"></option>
                                        <option value="SAMUDRA UTAMA NARAPATI, PT (USD)"></option>
                                        <option value="SARI KRESNA KIMIA, PT"></option>
                                        <option value="SARI KRESNA KIMIA, PT (CNY)"></option>
                                        <option value="SARI KRESNA KIMIA, PT (USD)"></option>
                                        <option value="SEJATI TANI, CV"></option>
                                        <option value="SELARAS JAYA ABADI, CV"></option>
                                        <option value="SENTRA KEMIKA PERSADA, PT"></option>
                                        <option value="SENTRAL TANI NUSANTARA, PT"></option>
                                        <option value="SHANDONG BINNONG TECHNOLOGY CO., LTD."></option>
                                        <option value="SHANDONG WEIFANG RAINBOW CHEMICAL CO., LTD."></option>
                                        <option value="SHANDONG WEIFANG RAINBOW CHEMICAL CO., LTD. (YUAN)"></option>
                                        <option value="SHANGHAI E-TONG CHEMICAL CO., LTD."></option>
                                        <option value="SHANGHAI E-TONG CHEMICAL CO.,LTD (YUAN)"></option>
                                        <option value="SHANGHAI HANFU BIOTECHNOLOGY CO., LTD"></option>
                                        <option value="SHANGHAI KJ IMPORT & EXPORT CO., LTD."></option>
                                        <option value="SHANGHAI NEW VALLEY BIOTECHNOLOGY CO., LTD (CNY)"></option>
                                        <option value="SICHUAN LESHAN FUHUA TONGDA AGRO-CHEMICAL TECHNOLOGY CO., LTD (CNY)"></option>
                                        <option value="SINAMYANG"></option>
                                        <option value="SINAMYANG GROUP PTE., LTD."></option>
                                        <option value="SINAMYANG GROUP PTE., LTD. (YUAN)"></option>
                                        <option value="SINAMYANG INDONESIA, PT"></option>
                                        <option value="SINAR KIMIA UTAMA, PT"></option>
                                        <option value="SINAR SILICA MAKMUR, PT"></option>
                                        <option value="SINERGI MITRA SUKSES, PT"></option>
                                        <option value="SINLOG"></option>
                                        <option value="SINO AGRI BIOSCIENCES SINGAPORE PTE., LTD."></option>
                                        <option value="SINO AGRI BIOSCIENCES SINGAPORE PTE., LTD. (CNY)"></option>
                                        <option value="SINO-AGRI LEADING BIOSCIENCES CO., LTD (CNY)"></option>
                                        <option value="SINO-AGRI RED SUN BIO-TECHNOLOGY CO., LTD"></option>
                                        <option value="SINO-AGRI RED SUN BIO-TECHNOLOGY CO., LTD (CNY)"></option>
                                        <option value="SOLO LOGO INDONESIA, PT"></option>
                                        <option value="STS"></option>
                                        <option value="SUENHO PERKASA, PT"></option>
                                        <option value="SUMATERA ABADI JAYA, CV"></option>
                                        <option value="SUMBER ABADI KIMIA"></option>
                                        <option value="SUMBER INDOKEM JAYA"></option>
                                        <option value="SUPER PERDANA"></option>
                                        <option value="SURYA BARU, PT"></option>
                                        <option value="SURYA MANDIRI"></option>
                                        <option value="SURYA RENGO CONTAINERS, PT"></option>
                                        <option value="SUWANDY"></option>
                                        <option value="TAIZHOU CHUNFENG MACHINERY CO., LTD."></option>
                                        <option value="TAIZHOU CHUNFENG MACHINERY CO., LTD. (CNY)"></option>
                                        <option value="TAIZHOU LUQIAO LAMSIN IMPORT & EXPORT CO., LTD."></option>
                                        <option value="TAIZHOU LUQIAO LAMSIN IMPORT & EXPORT CO., LTD. (CNY)"></option>
                                        <option value="TAIZHOU ORDER IMPORT AND EXPORT CO.,LTD"></option>
                                        <option value="TAIZHOU ORDER IMPORT AND EXPORT CO.,LTD. (CNY)"></option>
                                        <option value="TANI MAKMUR, CV"></option>
                                        <option value="THAI HARVEST, LTD."></option>
                                        <option value="THAI HARVEST LTD. (CNY)"></option>
                                        <option value="TIANJIN DINGSHEGXIN CHEMICAL INDUSTRY CO., LTD."></option>
                                        <option value="TIGA KRISMA SEJAHTERA, CV"></option>
                                        <option value="TIRTA SURYA RAYA, PT"></option>
                                        <option value="TITIAN ABADI LESTARI, PT"></option>
                                        <option value="TOKO MANG OGI"></option>
                                        <option value="TOKOPEDIA"></option>
                                        <option value="TRANSPORINDO"></option>
                                        <option value="TRICIPTA AGUNG SEJAHTERA, PT"></option>
                                        <option value="TRITAMA VIRYA POLYMER, PT"></option>
                                        <option value="TRUSTCHEM CO., LTD."></option>
                                        <option value="TRUSTCHEM CO., LTD. (CNY)"></option>
                                        <option value="UDAYA ANUGERAH, PT"></option>
                                        <option value="ULTRA PRIMA CORRUGATOR, PT"></option>
                                        <option value="UNGGUL TEKNO FILTER, PT"></option>
                                        <option value="UNID GLOBAL CORPORATION"></option>
                                        <option value="UNIPACK PLASINDO"></option>
                                        <option value="UNITED CHEMICAL INTER ANEKA"></option>
                                        <option value="UNIVERSAL PACK INDUSTRY, PT"></option>
                                        <option value="UPL INDONESIA, PT"></option>
                                        <option value="WAHANA MAS MULIA, PT"></option>
                                        <option value="WARNAPRIMA KIMIATAMA, PT"></option>
                                        <option value="WEDO INDUSTRY LIMITED"></option>
                                        <option value="WEIFANG LUBA CHEMICAL CO., LTD (CNY)"></option>
                                        <option value="WIKA INTINUSA NIAGATAMA, PT"></option>
                                        <option value="XUZHOU JINHE CHEMICALS CO., LTD"></option>
                                        <option value="YANNO AGRO SCIENCE INDONESIA, PT"></option>
                                        <option value="YUASA INDUSTRIAL BATTERY INDONESIA, PT"></option>
                                        <option value="YUDIAN KAWAN MINERAL, PT"></option>
                                        <option value="ZHEJIANG CHEMICALS IMPORT & EXPORT CORPORATION"></option>
                                        <option value="ZHEJIANG CHEMICALS IMPORT & EXPORT CORPORATION (CNY)"></option>
                                        <option value="ZHEJIANG RAYFULL CHEMICALS CO.,LTD."></option>
                                        <option value="ZHEJIANG XINAN CHEMICAL INDUSTRIAL GROUP CO., LTD (CNY)"></option>
                                        <option value="ZHEJIANG XINNONG CHEMICAL CO., LTD."></option>
                                        <option value="ZHEJIANG XINNONG CHEMICAL CO., LTD. (CNY)"></option>
                                        <option value="ZHONGCHANG INTERNASIONAL (SINGAPORE) TRADING PTE. LTD."></option>
                                        <option value="ZHONGCHANG INTERNASIONAL (SINGAPORE) TRADING PTE. LTD. (CNY)"></option>
                                        <option value="ZHUOCHEN INDUSTRIES (SHANGHAI) CO., LTD"></option>
                                        <option value="ZHUOCHEN INDUSTRIES (SHANGHAI) CO., LTD (YUAN)"></option>

                                    </datalist>
                                </div>
                                @error('company_name')
                                <p class="text-xs text-red-600 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                            {{-- Nomor Polisi --}}
                            <div>
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">
                                    Nomor Polisi <span style="color: red">*</span>
                                </label>
                                <input wire:model="plate_number" type="text" required
                                    placeholder="B 9070 NYK"
                                    style="width: 100%; padding: 10px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px;">
                            </div>

                            {{-- Jenis Kendaraan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jenis Kendaraan
                                </label>
                                <div>
                                    <input
                                        type="text"
                                        list="vehicleKinds"
                                        wire:model="vehicle_name"
                                        class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="Ketik atau pilih jenis kendaraan...">
                                    <datalist id="vehicleKinds">
                                        <option value="COLT DIESEL"></option>
                                        <option value="FUSO BESAR"></option>
                                        <option value="FUSO ISOTANK"></option>
                                        <option value="FUSO TRUK GANDENG"></option>
                                        <option value="FUSO WING BOX"></option>
                                        <option value="KONTAINER 20 FT"></option>
                                        <option value="KONTAINER 40 FT"></option>
                                        <option value="L300"></option>
                                        <option value="MINI BUS"></option>

                                    </datalist>
                                </div>
                            </div>
                            {{-- Tujuan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan</label>
                                <div>
                                    <input
                                        type="text"
                                        list="destinations"
                                        wire:model="destination"
                                        class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="Ketik atau pilih tujuan...">
                                    <datalist id="destinations">
                                        <option value="ACEH"></option>
                                        <option value="BANGKA"></option>
                                        <option value="BANJAR"></option>
                                        <option value="BENGKULU"></option>
                                        <option value="BREBES"></option>
                                        <option value="BUKIT TINGGI TMA"></option>
                                        <option value="BUKIT TINGGI UD. CAM"></option>
                                        <option value="CAACI - PEKANBARU"></option>
                                        <option value="CBA PABRIK"></option>
                                        <option value="CIANJUR"></option>
                                        <option value="DEPO BARABAI"></option>
                                        <option value="GORONTALO"></option>
                                        <option value="JAMBI"></option>
                                        <option value="JEMBER"></option>
                                        <option value="JOJGA"></option>
                                        <option value="KENDARI"></option>
                                        <option value="LAMPUNG"></option>
                                        <option value="MAKASAR"></option>
                                        <option value="MALANG"></option>
                                        <option va lue="MAMUJU"></option>
                                        <option value="MANADO"></option>
                                        <option value="MEDAN"></option>
                                        <option value="PALOPO"></option>
                                        <option value="PALU"></option>
                                        <option value="PALEMBANG"></option>
                                        <option value="PAMANUKAN"></option>
                                        <option value="PATI"></option>
                                        <option value="PEKANBARU"></option>
                                        <option value="PONTIANAK"></option>
                                        <option value="PURWOKERTO"></option>
                                        <option value="SAMARINDA"></option>
                                        <option value="SAMPIT"></option>
                                        <option value="SUB DEPO BATANG"></option>
                                        <option value="SUB DEPO BELITANG"></option>
                                        <option value="SUB DEPO BERAU"></option>
                                        <option value="SUB DEPO BIMA"></option>
                                        <option value="SUB DEPO MERANGEN"></option>
                                    </datalist>
                                </div>
                            </div>

                            {{-- Nama Sopir --}}
                            <div>
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">
                                    Nama Sopir <span style="color: red">*</span>
                                </label>
                                <input wire:model="driver_name" type="text" required
                                    placeholder="Nama lengkap sopir"
                                    style="width: 100%; padding: 10px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px;">
                            </div>

                            {{-- Nomor HP Sopir --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP Sopir</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-xl border-2 border-r-0 border-gray-200 bg-gray-50 text-sm text-gray-700">+62</span>
                                    <input
                                        type="text"
                                        wire:model="driver_phone"
                                        class="w-full border-2 border-gray-200 rounded-r-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="812xxxx">
                                </div>
                            </div>

                            {{-- Identitas Sopir --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Identitas</label>
                                <div>
                                    <input
                                        type="text"
                                        list="identityTypes"
                                        wire:model="driver_identity"
                                        class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="Ketik atau pilih identitas...">
                                    <datalist id="identityTypes">
                                        <option value="KTP"></option>
                                        <option value="SIM"></option>
                                        <option value="Passport"></option>
                                    </datalist>
                                </div>
                            </div>

                            {{-- FIELD TAMBAHAN KHUSUS BONGKAR / MUAT --}}
                            {{-- moved below Input Petugas & Waktu per request --}}

                            {{-- Keterangan --}}
                            <div>
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">
                                    Keterangan
                                </label>
                                <textarea wire:model="description" rows="2"
                                    placeholder="{{ strtoupper($type) }}"
                                    style="width: 100%; padding: 10px; border: 2px solid #f3f4f6; border-radius: 8px; font-size: 16px; resize: vertical;"></textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif {{-- end if type selected --}}

                    @if (Auth::check() && Auth::user()->role === 'admin' && $modalAction === 'update')
                    <!--<div style="background: #f3f4f6; padding: 16px; border-radius: 8px; border: 1px solid #d1d5db; margin-bottom: 16px;">-->
                    <!--    <h3 style="font-weight: bold; color: #374151; margin-bottom: 12px;">Waktu & Tanggal</h3>-->
                    <!--    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px;">-->
                    <!--        <div>-->
                    <!--            <label class="block text-sm font-semibold text-gray-700">Masuk Security</label>-->
                    <!--            <input type="datetime-local" wire:model.defer="security_start" class="w-full border-2 border-gray-200 rounded-xl p-2" />-->
                    <!--            <input type="text" wire:model.defer="security_in_officer" placeholder="Petugas Security In" class="w-full border-2 border-gray-200 rounded-xl p-2 mt-1" />-->
                    <!--        </div>-->
                    <!--        <div>-->
                    <!--            <label class="block text-sm font-semibold text-gray-700">Keluar Security</label>-->
                    <!--            <input type="datetime-local" wire:model.defer="security_end" class="w-full border-2 border-gray-200 rounded-xl p-2" />-->
                    <!--            <input type="text" wire:model.defer="security_out_officer" placeholder="Petugas Security Out" class="w-full border-2 border-gray-200 rounded-xl p-2 mt-1" />-->
                    <!--        </div>-->
                    <!--        <div>-->
                    <!--            <label class="block text-sm font-semibold text-gray-700">Mulai Loading</label>-->
                    <!--            <input type="datetime-local" wire:model.defer="loading_start" class="w-full border-2 border-gray-200 rounded-xl p-2" />-->
                    <!--            <input type="text" wire:model.defer="loading_start_officer" placeholder="Petugas Loading Start" class="w-full border-2 border-gray-200 rounded-xl p-2 mt-1" />-->
                    <!--        </div>-->
                    <!--        <div>-->
                    <!--            <label class="block text-sm font-semibold text-gray-700">Selesai Loading</label>-->
                    <!--            <input type="datetime-local" wire:model.defer="loading_end" class="w-full border-2 border-gray-200 rounded-xl p-2" />-->
                    <!--            <input type="text" wire:model.defer="loading_end_officer" placeholder="Petugas Loading End" class="w-full border-2 border-gray-200 rounded-xl p-2 mt-1" />-->
                    <!--        </div>-->
                    <!--        <div>-->
                    <!--            <label class="block text-sm font-semibold text-gray-700">Mulai TTB</label>-->
                    <!--            <input type="datetime-local" wire:model.defer="ttb_start" class="w-full border-2 border-gray-200 rounded-xl p-2" />-->
                    <!--            <input type="text" wire:model.defer="ttb_start_officer" placeholder="Petugas TTB Start" class="w-full border-2 border-gray-200 rounded-xl p-2 mt-1" />-->
                    <!--        </div>-->
                    <!--        <div>-->
                    <!--            <label class="block text-sm font-semibold text-gray-700">Selesai TTB</label>-->
                    <!--            <input type="datetime-local" wire:model.defer="ttb_end" class="w-full border-2 border-gray-200 rounded-xl p-2" />-->
                    <!--            <input type="text" wire:model.defer="ttb_end_officer" placeholder="Petugas TTB End" class="w-full border-2 border-gray-200 rounded-xl p-2 mt-1" />-->
                    <!--        </div>-->
                    <!--        <div>-->
                    <!--            <label class="block text-sm font-semibold text-gray-700">Distribusi</label>-->
                    <!--            <input type="datetime-local" wire:model.defer="distribution_at" class="w-full border-2 border-gray-200 rounded-xl p-2" />-->
                    <!--            <input type="text" wire:model.defer="distribution_officer" placeholder="Petugas Distribusi" class="w-full border-2 border-gray-200 rounded-xl p-2 mt-1" />-->
                    <!--        </div>-->
                    <!--        <div>-->
                    <!--            <label class="block text-sm font-semibold text-gray-700">Current Stage</label>-->
                    <!--            <select wire:model.defer="current_stage" class="w-full border-2 border-gray-200 rounded-xl p-2">-->
                    <!--                <option value="security_in">security_in</option>-->
                    <!--                <option value="loading_started">loading_started</option>-->
                    <!--                <option value="loading_ended">loading_ended</option>-->
                    <!--                <option value="ttb_started">ttb_started</option>-->
                    <!--                <option value="ttb_ended">ttb_ended</option>-->
                    <!--                <option value="ttb_distributed">ttb_distributed</option>-->
                    <!--                <option value="completed">completed</option>-->
                    <!--                <option value="canceled">canceled</option>-->
                    <!--            </select>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    @endif

                    {{-- 2. INPUT PETUGAS (Hanya jika Login & Bukan Admin Edit) --}}
                    @if (Auth::check() && ($modalAction === 'create' || ($modalAction === 'update' && Auth::user()->role !== 'admin')))

                    @if($modalAction === 'update' && isset($editingRecord))
                    <div style="background: #e0f2fe; padding: 12px; border-radius: 8px; color: #0369a1; font-size: 14px; border-left: 4px solid #3b82f6; margin-bottom: 12px;">
                        <strong>Status Saat Ini:</strong>
                        @if($editingRecord->current_stage == 'security_in') Menunggu Mulai Bongkar/Muat
                        @elseif($editingRecord->current_stage == 'loading_started') Sedang Proses Bongkar/Muat
                        @elseif($editingRecord->current_stage == 'loading_ended') Menunggu Mulai TTB/SJ
                        @elseif($editingRecord->current_stage == 'ttb_started') Sedang Proses TTB/SJ
                        @elseif($editingRecord->current_stage == 'ttb_ended') Menunggu Distribusi / Keluar (Security)
                        @elseif($editingRecord->current_stage == 'ttb_distributed') Menunggu Keluar (Security)
                        @elseif($editingRecord->current_stage == 'completed') Selesai
                        @elseif($editingRecord->current_stage == 'canceled') Dibatalkan
                        @endif
                    </div>
                    @endif

                    {{-- moved: Field Tambahan PROSES MUAT will render below in security exit section when appropriate --}}

                    <div style="background: #eff6ff; padding: 16px; border-radius: 8px; border: 1px solid #dbeafe;">
                        <h3 style="font-weight: bold; color: #374151; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #d1d5db;">Input Petugas & Waktu</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div>
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px; color: #4b5563;">Waktu (Realtime)</label>
                                {{-- Jam Realtime AlpineJS --}}
                                <div x-data="{ now: new Date(), init() { setInterval(() => { this.now = new Date() }, 1000) } }"
                                    style="width: 100%; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; background: #e5e7eb; color: #6b7280; cursor: not-allowed;">
                                    <span x-text="now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
                                </div>
                            </div>
                            <div>
                                <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px; color: #1f2937;">Nama Petugas <span style="color: #ef4444;">*</span></label>
                                <input
                                    type="text"
                                    list="officerNames"
                                    wire:model="officer_name"
                                    placeholder="Ketik nama petugas..."
                                    required
                                    style="width: 100%; padding: 10px; border: 2px solid #dbeafe; border-radius: 8px; font-size: 16px; background: white;">
                                <datalist id="officerNames">
                                    @php
                                    // Do NOT expose security-role users in this list.
                                    $explicitByRole = [
                                    'security' => [
                                    "ALI MURDANI",
                                    "ANDI AMIR MAHMUD",
                                    "DEDE MULYADI",
                                    "MUHAMAD AGUS ARIFIN",
                                    "RIZKI ARROHMAN",
                                    "RUSMAN",
                                    "SARMIN",
                                    "SOLEHUDDIN",
                                    "YADI SUPRIYADI"
                                    ],
                                    'loading' => [
                                    "ADE ILYAS",
                                    "AGUS GUNAWAN",
                                    "ANDI",
                                    "ANDRIK",
                                    "ANTIN SRI PRIHATIN",
                                    "AQMAL SABILI",
                                    "ASEP TSAURI",
                                    "DEWO AGUNG BUDI PRASETYO",
                                    "DENI MULYANTO",
                                    "DENI PAPILAH",
                                    "DESKA",
                                    "DIKA ANDIKA",
                                    "DIMAS",
                                    "DONA",
                                    "FAJRIN",
                                    "GINTA FEBRIANTI",
                                    "HERWIN",
                                    "ILYAS ALFARITSY",
                                    "KAMAL",
                                    "NEDI",
                                    "NOVI TRI LESTARI",
                                    "ROHMADIN EDI",
                                    "SUGIARJO",
                                    "SUSI IRAWAN",
                                    "SOLEH",
                                    "TRIA",
                                    "ULFAH NURJANAH",
                                    "RUBAI",
                                    "MUTINI",
                                    "RASITA",
                                    "AGUNG",
                                    "ERIK",
                                    "RIFAL",
                                    "JUJUN",
                                    "ADANG",
                                    "SURYADI",
                                    "RASNAM",
                                    "ERLAN"
                                    ],
                                    'ttb' => [
                                    "AMAR",
                                    "ASEP",
                                    "CHOLIK",
                                    "DEDY",
                                    "ENDANG",
                                    "FAUZI",
                                    "IRFAN"
                                    ],
                                    'admin' => [],
                                    ];

                                    $currentName = Auth::check() ? Auth::user()->name : null;
                                    $currentRole = Auth::check() ? Auth::user()->role : null;
                                    $explicit = $explicitByRole[$currentRole] ?? [];
                                    @endphp

                                    {{-- Explicit names for current role (kept empty for security) --}}
                                    @foreach($explicit as $ename)
                                    <option value="{{ $ename }}"></option>
                                    @endforeach

                                    {{-- Current user if not duplicated and not excluded roles --}}
                                    @php
                                    $excludedRoles = ['security', 'loading', 'ttb'];
                                    @endphp
                                    @if($currentName && !in_array($currentName, $explicit) && !in_array($currentRole, $excludedRoles))
                                    <option value="{{ $currentName }}"></option>
                                    @endif

                                    @php $usersCollection = ($allUsers ?? collect()); @endphp
                                    @if($currentRole === 'admin' || !$currentRole)
                                    @php $candidates = $usersCollection->whereNotIn('role', $excludedRoles)->sortBy('name'); @endphp
                                    @else
                                    @php $candidates = $usersCollection->where('role', $currentRole)->whereNotIn('role', $excludedRoles)->sortBy('name'); @endphp
                                    @endif

                                    @foreach($candidates as $u)
                                    @if(!in_array($u->name, $explicit) && $u->name !== $currentName)
                                    <option value="{{ $u->name }}"></option>
                                    @endif
                                    @endforeach
                                </datalist>
                                @error('officer_name') <span style="color: #ef4444; font-size: 12px;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @if(isset($editingRecord) && isset($editingRecord->current_stage) && ($editingRecord->current_stage === 'loading_started' || $editingRecord->current_stage === 'ttb_started'))
                        <div style="margin-top: 12px;">
                            <label style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px; color: #4b5563;">Keterangan Tambahan</label>
                            <textarea @if($editingRecord->current_stage === 'loading_started') wire:model="keterangan" @elseif($editingRecord->current_stage === 'ttb_started') wire:model="ttb_notes" @endif rows="2"
                                              placeholder="Tambahkan keterangan tambahan jika diperlukan..."
                                              style="width: 100%; padding: 10px; border: 2px solid #dbeafe; border-radius: 8px; font-size: 16px; resize: vertical;"></textarea>
                        </div>
                        @endif
                        
                        {{-- Field Tambahan PROSES MUAT (dipindahkan ke saat security finalisasi keluar) --}}
                        @if(isset($editingRecord) && isset($editingRecord->current_stage) && $editingRecord->current_stage === 'ttb_distributed' && Auth::user()->role === 'security' && ($editingRecord->type ?? $type) === 'muat')
                        <div style="margin-top: 8px; padding: 10px; border-radius: 8px; background: #eff6ff; border: 1px solid #bfdbfe;">
                            <p style="font-size: 12px; font-weight: 700; color: #1d4ed8; margin: 0 0 8px 0;">Field Tambahan PROSES MUAT</p>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">No. Surat Jalan</label>
                                    <input wire:model="sj_number" type="text" placeholder="97892736-9900" style="width: 100%; padding: 8px 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                                </div>
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Nama Barang</label>
                                    <input wire:model="item_name" type="text" placeholder="KARTON BOX" style="width: 100%; padding: 8px 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                                </div>
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">Jumlah Barang</label>
                                    <input wire:model="item_quantity" type="text" placeholder="10.000 PCS" style="width: 100%; padding: 8px 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                                </div>
                            </div>
                        </div>
                        @endif
                        <p style="font-size: 12px; color: #6b7280; margin-top: 8px; font-style: italic;">* Masukkan nama petugas lapangan dan keterangan tambahan.</p>

                        {{-- FIELD TAMBAHAN KHUSUS BONGKAR / MUAT (ditampilkan setelah Input Petugas & Waktu) --}}
                        @if ($type === 'bongkar' || ($type === 'muat' && ($modalAction ?? '') !== 'create'))
                        <div style="margin-top: 8px; padding: 10px; border-radius: 8px; background: #eff6ff; border: 1px solid #bfdbfe;">
                            <p style="font-size: 12px; font-weight: 700; color: #1d4ed8; margin: 0 0 8px 0;">
                                Field Tambahan PROSES BONGKAR / MUAT
                            </p>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">
                                        No. Surat Jalan
                                    </label>
                                    <input wire:model="sj_number" type="text"
                                        placeholder="97892736-9900"
                                        style="width: 100%; padding: 8px 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                                </div>
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">
                                        Nama Barang
                                    </label>
                                    <input wire:model="item_name" type="text"
                                        placeholder="KARTON BOX"
                                        style="width: 100%; padding: 8px 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                                </div>
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 4px;">
                                        Jumlah Barang
                                    </label>
                                    <input wire:model="item_quantity" type="text"
                                        placeholder="10.000 PCS"
                                        style="width: 100%; padding: 8px 10px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- 3. TOMBOL CANCEL (Admin Only) --}}
                    @if ($modalAction === 'update' && Auth::check() && Auth::user()->role === 'admin' && isset($editingRecord))
                    <div style="padding-top: 8px;">
                        <button type="button" wire:click="cancelTracking({{ $editingRecord->id }})"
                            onclick="confirm('Yakin ingin membatalkan transaksi ini? Status akan menjadi Canceled.') || event.stopImmediatePropagation()"
                            style="width: 100%; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 10px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                            🚫 Batalkan Transaksi (Set to Canceled)
                        </button>
                    </div>
                    @endif

                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <button type="submit" class="btn" style="flex: 1; background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;" wire:loading.attr="disabled" wire:target="handleSubmit">
                        <span wire:loading.remove wire:target="handleSubmit">
                            @if($modalAction === 'public_create') 📤 Kirim Data
                            @elseif($modalAction === 'create') Simpan Masuk
                            @else 💾 Simpan Update
                            @endif
                        </span>
                        <span wire:loading wire:target="handleSubmit">⏳ Menyimpan...</span>
                    </button>
                    <button wire:click="closeModal" type="button" class="btn" style="flex: 1; background: #f3f4f6; color: #1f2937; padding: 12px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>