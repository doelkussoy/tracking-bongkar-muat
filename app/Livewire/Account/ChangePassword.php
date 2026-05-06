<?php
// app/Livewire/Account/ChangePassword.php
namespace App\Livewire\Account;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $showCurrent = false;
    public $showNew = false;
    public $showConfirm = false;
    public $showNewPassword = false;
    public $showNewPasswordConfirm = false;

    public function save()
    {
        $this->validate([
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', 'confirmed', Password::min(4)->numbers()],
            'password_confirmation' => ['required'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
        ]);

        Auth::user()->update(['password' => Hash::make($this->password)]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('message', 'Password berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.account.change-password')->layout('layouts.app');
    }
}