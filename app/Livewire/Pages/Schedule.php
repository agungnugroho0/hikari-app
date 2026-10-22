<?php

namespace App\Livewire\Pages;

use App\Models\Schedule as ScheduleModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Schedule')]
class Schedule extends Component
{
    public bool $showForm = false;

    public bool $showConfirm = false;

    public ?int $scheduleId = null;

    public ?int $deleteId = null;

    public string $title = '';

    public string $date = '';

    public string $method = '';

    public string $note = '';

    protected function authorizeAdmin(): void
    {
        abort_unless(Auth::user()?->akses === 'admin', 403);
    }

    public function mount(): void
    {
        $this->authorizeAdmin();
    }

    public function create(): void
    {
        $this->authorizeAdmin();
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->authorizeAdmin();
        $schedule = ScheduleModel::findOrFail($id);
        $this->scheduleId = $schedule->id_jadwal;
        $this->title = $schedule->title;
        $this->date = $schedule->date->format('Y-m-d\TH:i');
        $this->method = $schedule->method;
        $this->note = $schedule->note ?? '';
        $this->resetValidation();
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->authorizeAdmin();
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'method' => ['required', 'in:online,offline'],
            'note' => ['nullable', 'string'],
        ], [
            'title.required' => 'Judul pertemuan wajib diisi.',
            'date.required' => 'Tanggal dan waktu wajib diisi.',
            'date.date' => 'Tanggal dan waktu tidak valid.',
            'method.required' => 'Metode wajib dipilih.',
            'method.in' => 'Pilih metode Online atau Offline.',
        ]);

        $data['date'] = str_replace('T', ' ', $data['date']);
        $data['note'] = $data['note'] ?: null;
        ScheduleModel::updateOrCreate(['id_jadwal' => $this->scheduleId], $data);

        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('schedule-notice', message: 'Jadwal berhasil disimpan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->authorizeAdmin();
        ScheduleModel::findOrFail($id);
        $this->deleteId = $id;
        $this->showConfirm = true;
    }

    public function delete(): void
    {
        $this->authorizeAdmin();
        ScheduleModel::findOrFail($this->deleteId)->delete();
        $this->showConfirm = false;
        $this->deleteId = null;
        $this->dispatch('schedule-notice', message: 'Jadwal berhasil dihapus.');
    }

    public function render()
    {
        $schedules = ScheduleModel::query()->orderBy('date')->get();

        return view('pages.schedule', compact('schedules'));
    }

    private function resetForm(): void
    {
        $this->reset(['scheduleId', 'title', 'date', 'method', 'note']);
        $this->resetValidation();
    }
}
