<?php

namespace App\Livewire\Pages;

use App\Livewire\Forms\StaffForm;
use App\Models\Staff as StaffModel;
use App\Services\StaffServices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

#[Layout('layouts.developer')]
#[Title('Pengelolaan Staf')]
class DeveloperStaff extends Component
{
    public StaffForm $staff;

    public string $mode = 'list';

    public ?string $editingId = null;

    public ?string $deleteId = null;

    public string $message = '';

    public function create(): void
    {
        $this->mode = 'create';
        $this->editingId = null;
        $this->staff->reset();
        $this->staff->akses = 'guru';
    }

    public function edit(string $id): void
    {
        $this->ensureManageableStaff($id);
        $this->staff->setModels($id);
        $this->editingId = $id;
        $this->mode = 'edit';
    }

    public function save(): void
    {
        $this->validateStaff();

        if ($this->mode === 'edit') {
            $this->ensureManageableStaff((string) $this->editingId);
            $this->staff->update();
            $this->message = 'Data staf berhasil diperbarui.';
        } else {
            $this->staff->store();
            $this->message = 'Staf berhasil ditambahkan.';
        }

        $this->cancelForm();
    }

    public function cancelForm(): void
    {
        $this->staff->reset();
        $this->mode = 'list';
        $this->editingId = null;
    }

    public function confirmDelete(string $id): void
    {
        $this->ensureManageableStaff($id);
        $this->deleteId = $id;
    }

    public function delete(StaffServices $service): void
    {
        $this->ensureManageableStaff((string) $this->deleteId);
        $service->delete($this->deleteId);
        $this->deleteId = null;
        $this->message = 'Staf berhasil dihapus.';
    }

    public function render()
    {
        return view('pages.developer-staff', [
            'staffList' => StaffModel::query()
                ->whereIn('akses', ['admin', 'guru', 'dev'])
                ->orderBy('nama_s')
                ->get(),
        ]);
    }

    private function validateStaff(): void
    {
        $rules = [
            'staff.nama_s' => ['required', 'string', 'max:255'],
            'staff.no' => ['required', 'string', 'max:255'],
            'staff.username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('staff', 'username')->ignore($this->editingId, 'id_staff'),
            ],
            'staff.akses' => ['required', Rule::in(['admin', 'guru', 'dev'])],
            'staff.foto_s' => ['nullable'],
        ];

        if ($this->staff->foto_s instanceof TemporaryUploadedFile) {
            $rules['staff.foto_s'] = ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'];
        }

        $this->validate($rules);
    }

    private function ensureManageableStaff(string $id): StaffModel
    {
        return StaffModel::query()
            ->where('id_staff', $id)
            ->whereIn('akses', ['admin', 'guru', 'dev'])
            ->firstOrFail();
    }
}