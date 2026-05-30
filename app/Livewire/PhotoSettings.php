<?php

namespace App\Livewire;

use App\Models\PhotoSetting;
use Livewire\Component;

class PhotoSettings extends Component
{
    public array $editingId = [];
    public array $editName = [];
    public array $editCommand = [];
    public bool $showForm = false;
    public string $newName = '';
    public string $newCommand = '';

    public function toggleActive(int $id): void
    {
        $setting = PhotoSetting::findOrFail($id);
        $setting->update(['is_active' => !$setting->is_active]);
    }

    public function startEdit(int $id, string $name, string $command): void
    {
        $this->editingId = [$id => true];
        $this->editName[$id] = $name;
        $this->editCommand[$id] = $command;
    }

    public function saveEdit(int $id): void
    {
        PhotoSetting::findOrFail($id)->update([
            'name'         => trim($this->editName[$id] ?? ''),
            'gmic_command' => trim($this->editCommand[$id] ?? ''),
        ]);
        unset($this->editingId[$id]);
    }

    public function cancelEdit(int $id): void
    {
        unset($this->editingId[$id]);
    }

    public function addSetting(): void
    {
        $name    = trim($this->newName);
        $command = trim($this->newCommand);

        if (!$name || !$command) return;

        $max = PhotoSetting::max('sort_order') ?? 0;
        PhotoSetting::create([
            'name'         => $name,
            'gmic_command' => $command,
            'sort_order'   => $max + 1,
            'is_active'    => true,
        ]);

        $this->newName    = '';
        $this->newCommand = '';
        $this->showForm   = false;
    }

    public function delete(int $id): void
    {
        PhotoSetting::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.photo-settings', [
            'settings' => PhotoSetting::orderBy('sort_order')->get(),
        ]);
    }
}
