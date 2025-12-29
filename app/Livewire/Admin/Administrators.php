<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Administrators extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';
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

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($this->editingId ? ',' . $this->editingId : ''),
            'phone' => 'nullable|string|max:20',
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'O nome e obrigatorio.',
        'email.required' => 'O email e obrigatorio.',
        'email.email' => 'Insira um email valido.',
        'email.unique' => 'Este email ja esta em uso.',
        'password.required' => 'A palavra-passe e obrigatoria.',
        'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
        'password.confirmed' => 'As palavras-passe nao coincidem.',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        $this->editingId = $id;
        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->phone = $admin->phone;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $admin = User::where('role', 'admin')->findOrFail($this->editingId);

            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ];

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            $admin->update($data);
            session()->flash('message', 'Administrador atualizado com sucesso!');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'role' => 'admin',
            ]);
            session()->flash('message', 'Administrador criado com sucesso!');
        }

        $this->closeForm();
    }

    public function delete($id)
    {
        // Prevent deleting yourself
        if ($id == auth()->id()) {
            session()->flash('error', 'Voce nao pode excluir a sua propria conta!');
            return;
        }

        // Check if this is the last admin
        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            session()->flash('error', 'Nao e possivel excluir o ultimo administrador!');
            return;
        }

        User::where('role', 'admin')->findOrFail($id)->delete();
        session()->flash('message', 'Administrador excluido com sucesso!');
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'phone', 'password', 'password_confirmation', 'editingId']);
    }

    public function render()
    {
        $administrators = User::query()
            ->where('role', 'admin')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.administrators', [
            'administrators' => $administrators,
        ])->layout('components.layouts.admin');
    }
}
