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
    public $showForm = false;
    public $search = '';

    protected $queryString = ['search'];

    public function mount()
    {
        if (request()->has('action') && request('action') === 'create') {
            $this->create();
        }
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
        $this->showForm = true;
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

        $this->closeForm();
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Categoria excluida com sucesso!');
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'is_active', 'editingId']);
        $this->is_active = true;
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
