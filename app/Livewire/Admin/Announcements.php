<?php

namespace App\Livewire\Admin;

use App\Models\Announcement;
use Livewire\Component;

class Announcements extends Component
{
    public $message = '';
    public $is_active = true;
    public $order = 0;
    public $editingId = null;

    protected $rules = [
        'message' => 'required|string|max:500',
        'is_active' => 'boolean',
        'order' => 'integer|min:0',
    ];

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $announcement = Announcement::find($this->editingId);
            $announcement->update([
                'message' => $this->message,
                'is_active' => $this->is_active,
                'order' => $this->order,
            ]);
            session()->flash('message', 'Anúncio atualizado com sucesso!');
        } else {
            Announcement::create([
                'message' => $this->message,
                'is_active' => $this->is_active,
                'order' => $this->order,
            ]);
            session()->flash('message', 'Anúncio criado com sucesso!');
        }

        $this->reset(['message', 'is_active', 'order', 'editingId']);
        $this->is_active = true;
    }

    public function edit($id)
    {
        $announcement = Announcement::find($id);
        $this->editingId = $id;
        $this->message = $announcement->message;
        $this->is_active = $announcement->is_active;
        $this->order = $announcement->order;
    }

    public function delete($id)
    {
        Announcement::find($id)->delete();
        session()->flash('message', 'Anúncio eliminado com sucesso!');
    }

    public function cancelEdit()
    {
        $this->reset(['message', 'is_active', 'order', 'editingId']);
        $this->is_active = true;
    }

    public function toggleActive($id)
    {
        $announcement = Announcement::find($id);
        $announcement->update(['is_active' => !$announcement->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.announcements', [
            'announcements' => Announcement::orderBy('order')->get(),
        ])->layout('components.layouts.admin');
    }
}
