{{-- resources/views/livewire/admin/users/manage-passwords.blade.php --}}
<div class="max-w-6xl mx-auto mt-6 sm:mt-8 px-4">
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-white px-6 py-5 border-b border-gray-100">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-3">
          <button type="button" onclick="window.history.back()" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 px-3 py-2 rounded-lg border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
          </button>
          <div>
            <h1 class="text-xl font-bold text-gray-800">Kelola Password Pengguna</h1>
            <p class="text-sm text-gray-500 mt-1">Reset atau perbarui password akun pengguna sistem</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <!--<button type="button" wire:click="openAddUserModal" class="inline-flex items-center gap-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-lg shadow-sm">-->
          <!--  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
          <!--    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />-->
          <!--  </svg>-->
          <!--  Tambah Pengguna-->
          <!--</button>-->
          <a href="{{ route('account.password') }}" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Ganti Password Saya
          </a>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    @if ($successMessage)
      <div class="mx-6 mt-5 mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-3">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-emerald-700 font-medium">{{ $successMessage }}</span>
      </div>
    @endif

    <!-- Controls -->
    <div class="px-6 pt-2 pb-5">
      <div class="flex flex-col sm:flex-row sm:items-end gap-4">
        <div class="flex-1">
          <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pengguna</label>
          <input
            id="search"
            type="text"
            placeholder="Nama, email, atau role…"
            wire:model.live="search"
            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          >
        </div>
        <div class="w-full sm:w-auto">
          <label for="perPage" class="block text-sm font-medium text-gray-700 mb-1">Tampilan</label>
          <select
            id="perPage"
            wire:model.live="perPage"
            class="w-full sm:w-24 px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
            <option value="10">10/baris</option>
            <option value="25">25/baris</option>
            <option value="50">50/baris</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="px-6 pb-6">
      <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
              <th scope="col" class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
              <th scope="col" class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
              <th scope="col" class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            @forelse ($users as $u)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4 whitespace-nowrap">
                  <div class="font-medium text-gray-900">{{ $u->name }}</div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600">{{ $u->email }}</td>
                <td class="px-5 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($u->role === 'admin') bg-purple-100 text-purple-800
                    @elseif($u->role === 'security') bg-blue-100 text-blue-800
                    @elseif($u->role === 'loading') bg-amber-100 text-amber-800
                    @elseif($u->role === 'ttb') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($u->role) }}
                  </span>
                </td>
                <td class="px-5 py-4 text-right whitespace-nowrap">
                  <button
                    wire:click="openChangePassword({{ $u->id }})"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition shadow-sm"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Ganti Password
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-5 py-8 text-center">
                  <div class="flex flex-col items-center justify-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <p class="text-sm font-medium">Tidak ada pengguna ditemukan</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-5">
        {{ $users->links() }}
      </div>
    </div>
  </div>

  {{-- Modal tambah pengguna baru --}}
  @if ($showAddUserModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="bg-blue-600 px-6 py-4">
          <h2 class="text-lg font-bold text-white">Tambah Pengguna Baru</h2>
        </div>

        <form wire:submit.prevent="createUser" class="p-6 space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
            <input type="text" wire:model.defer="new_user_name" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Nama pengguna" required>
            @error('new_user_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" wire:model.defer="new_user_email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="email@contoh.com" required>
            @error('new_user_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select wire:model.defer="new_user_role" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="admin">Admin</option>
              <option value="security">Security</option>
              <option value="loading">Bongkar/Muat</option>
              <option value="ttb">Officer TTB/SJ</option>
            </select>
            @error('new_user_role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password (4 digit)</label>
            <input type="password" wire:model.defer="new_user_password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan password" maxlength="4" required inputmode="numeric">
            @error('new_user_password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
            <input type="password" wire:model.defer="new_user_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ulangi password" maxlength="4" required inputmode="numeric">
          </div>

          <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-sm hover:shadow-md transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Simpan</button>
            <button type="button" wire:click="$set('showAddUserModal', false)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl transition">Batal</button>
          </div>
        </form>
      </div>
    </div>
  @endif

 {{-- Modal ganti password user lain --}}
@if ($selectedUserId)
  <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
        <h2 class="text-lg font-bold text-white">Ganti Password Pengguna</h2>
      </div>

      <form wire:submit.prevent="saveNewPassword" class="p-6 space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru (4 digit)</label>
          <div class="relative">
            <input
              type="{{ $showNewPassword ? 'text' : 'password' }}"
              wire:model.defer="new_password"
              class="w-full px-4 py-3 pl-4 pr-12 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="4 digit angka"
              maxlength="4"
              inputmode="numeric"
            >
            <button
              type="button"
              wire:click="$toggle('showNewPassword')"
              class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-700"
            >
              @if($showNewPassword)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                </svg>
              @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              @endif
            </button>
          </div>
          @error('new_password')
            <p class="mt-2 text-sm text-red-600 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ $message }}
            </p>
          @enderror
          <p class="text-xs text-gray-500 mt-2">Masukkan **4 digit angka**</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
          <div class="relative">
            <input
              type="{{ $showNewPasswordConfirm ? 'text' : 'password' }}"
              wire:model.defer="new_password_confirmation"
              class="w-full px-4 py-3 pl-4 pr-12 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="Ulangi 4 digit"
              maxlength="4"
              inputmode="numeric"
            >
            <button
              type="button"
              wire:click="$toggle('showNewPasswordConfirm')"
              class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-700"
            >
              @if($showNewPasswordConfirm)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                </svg>
              @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              @endif
            </button>
          </div>
          @error('new_password_confirmation')
            <p class="mt-2 text-sm text-red-600 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ $message }}
            </p>
          @enderror
        </div>

        <div class="flex gap-3 pt-2">
          <button
            type="submit"
            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-sm hover:shadow-md transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            Simpan Password
          </button>
          <button
            type="button"
            wire:click="$set('selectedUserId', null)"
            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl transition"
          >
            Batal
          </button>
        </div>
      </form>
    </div>
  </div>
@endif
</div>