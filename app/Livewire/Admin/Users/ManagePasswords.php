<?php
// app/Livewire/Admin/Users/ManagePasswords.php
namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ManagePasswords extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public string $search = '';
    public int $perPage = 10;

    public ?int $selectedUserId = null;
    public string $new_password = '';
    public string $new_password_confirmation = '';
    public string $successMessage = '';

    public bool $showAddUserModal = false;
    public string $new_user_name = '';
    public string $new_user_email = '';
    public string $new_user_role = 'security';
    public string $new_user_password = '';
    public string $new_user_password_confirmation = '';

    public $showNewPassword = false;
    public $showNewPasswordConfirm = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function openChangePassword(int $userId)
    {
        $this->resetValidation();
        $this->selectedUserId = $userId;
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function openAddUserModal()
    {
        $this->resetValidation();
        $this->showAddUserModal = true;
        $this->new_user_name = '';
        $this->new_user_email = '';
        $this->new_user_role = 'security';
        $this->new_user_password = '';
        $this->new_user_password_confirmation = '';
    }

    public function createUser()
    {
        $this->validate([
            'new_user_name' => ['required', 'string', 'max:255'],
            'new_user_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'new_user_role' => ['required', 'in:admin,security,loading,ttb'],
            'new_user_password' => ['required', 'confirmed', Password::min(4)->numbers()],
        ], [
            'new_user_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $this->new_user_name,
            'email' => $this->new_user_email,
            'role' => $this->new_user_role,
            'password' => Hash::make($this->new_user_password),
        ]);

        $this->successMessage = "Pengguna {$this->new_user_name} berhasil ditambahkan.";
        $this->showAddUserModal = false;
        $this->new_user_name = $this->new_user_email = $this->new_user_password = $this->new_user_password_confirmation = '';
        $this->new_user_role = 'security';

        $this->resetPage();
    }

    public function saveNewPassword()
    {
        $this->validate([
            'selectedUserId'           => ['required', 'exists:users,id'],
            'new_password'             => ['required', 'confirmed', Password::min(4)->numbers()],
            'new_password_confirmation'=> ['required'],
        ], [
            'new_password.confirmed'   => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        $user->update(['password' => Hash::make($this->new_password)]);

        $this->successMessage = "Password untuk {$user->name} berhasil diubah.";
        // kosongkan form/modal
        $this->selectedUserId = null;
        $this->new_password = $this->new_password_confirmation = '';
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('role', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.admin.users.manage-passwords', [
            'users' => $users,
        ])->layout('layouts.app');
    }

    public function mount()
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403);
        }
    }
    
}