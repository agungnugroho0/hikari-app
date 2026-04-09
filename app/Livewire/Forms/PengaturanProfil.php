<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PengaturanProfil extends Component
{
    use WithFileUploads;

    public bool $mustChangeDefaultPassword = false;

    public string $nama_s = '';

    public $foto_s;

    public $current_password = '';

    public $new_password = '';

    public $new_password_confirmation = '';

    public string $currentPhoto = '';

    public function mount(): void
    {
        $this->nama_s = (string) Auth::user()?->nama_s;
        $this->currentPhoto = (string) Auth::user()?->foto_s;
        $this->mustChangeDefaultPassword = Auth::check() && Hash::check('123456', (string) Auth::user()?->password);
    }

    protected function rules(): array
    {
        return [
            'foto_s' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|same:new_password_confirmation',
            'new_password_confirmation' => 'required|string|min:8',
        ];
    }

    protected function messages(): array
    {
        return [
            'foto_s.image' => 'File harus berupa gambar.',
            'foto_s.mimes' => 'Foto harus berformat jpg, jpeg, atau png.',
            'foto_s.max' => 'Ukuran foto maksimal 2MB.',
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.same' => 'Konfirmasi password baru harus sama.',
            'new_password.not_in' => 'Password baru tidak boleh menggunakan password default.',
            'new_password_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
            'new_password_confirmation.min' => 'Konfirmasi password baru minimal 8 karakter.',
        ];
    }

    public function updateName(): void
    {
        $validated = $this->validate([
            'nama_s' => 'required|string|min:3|max:255',
        ], [
            'nama_s.required' => 'Nama wajib diisi.',
            'nama_s.min' => 'Nama minimal 3 karakter.',
            'nama_s.max' => 'Nama maksimal 255 karakter.',
        ]);

        $staff = Auth::user();

        if (! $staff) {
            return;
        }

        $staff->update([
            'nama_s' => $validated['nama_s'],
        ]);

        Auth::setUser($staff->fresh());
        $this->nama_s = (string) Auth::user()?->nama_s;

        $this->dispatch('kirim', message: 'Nama profil berhasil diperbarui.');
    }

    public function updatePhoto(): void
    {
        $this->validate([
            'foto_s' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $staff = Auth::user();

        if (! $staff || ! $this->foto_s) {
            return;
        }

        if ($staff->foto_s && Storage::disk('public')->exists($staff->foto_s)) {
            Storage::disk('public')->delete($staff->foto_s);
        }

        $path = $this->foto_s->store('staff', 'public');

        $staff->update([
            'foto_s' => $path,
        ]);

        $this->currentPhoto = $path;
        $this->foto_s = null;

        Auth::setUser($staff->fresh());

        $this->dispatch('kirim', message: 'Foto profil berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|same:new_password_confirmation|not_in:123456',
            'new_password_confirmation' => 'required|string|min:8',
        ]);

        $staff = Auth::user();

        if (! $staff) {
            return;
        }

        $staff->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        Auth::setUser($staff->fresh());
        $this->mustChangeDefaultPassword = false;

        $this->reset([
            'current_password',
            'new_password',
            'new_password_confirmation',
        ]);

        $this->dispatch('kirim', message: 'Password berhasil diperbarui.');
    }

    public function render()
    {
        return view('components.form.pengaturan-profil');
    }
}
