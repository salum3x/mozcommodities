<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public $name = '';
    public $description = '';
    public $is_active = true;
    public $editingId = null;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->reset(['name', 'description', 'is_active', 'editingId']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $category = Category::findOrFail($this->editingId);
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Categoria atualizada com sucesso!');
        } else {
            Category::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Categoria criada com sucesso!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Categoria excluída com sucesso!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'description', 'is_active', 'editingId']);
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.categories', [
            'categories' => $categories,
        ])->layout('components.layouts.admin');
    }
}
