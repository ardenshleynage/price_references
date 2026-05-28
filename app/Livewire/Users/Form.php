<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Form extends Component
{
    public string $mode = 'create';

    public ?int $userId = null;

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public string $role = '';

    public bool $superAdminExists = false;

    protected function rules(): array
    {
        if ($this->mode === 'create') {
            return [
                'username' => 'required|string|max:255|min:2|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email|ends_with:gmail.com',
                'password' => 'required|string|max:255|min:4',
                'role' => 'required|integer|in:1,2,3',
            ];
        }

        $rules = [];
        if ($this->username !== '') {
            $rules['username'] = 'string|max:255|min:2|unique:users,username,'.$this->userId;
        }
        if ($this->email !== '') {
            $rules['email'] = 'string|email|max:255|unique:users,email,'.$this->userId.'|ends_with:gmail.com';
        }
        if ($this->role !== '') {
            $rules['role'] = 'integer|in:2,3';
        }

        return $rules;
    }

    protected $messages = [
        'username.required' => "Le nom d'utilisateur est obligatoire.",
        'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
        'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",
        'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
        'username.unique' => "Ce nom d'utilisateur existe déjà.",
        'email.required' => "L'adresse email est obligatoire.",
        'email.string' => "L'adresse email doit être une chaîne de caractères.",
        'email.email' => "L'adresse email doit être valide.",
        'email.max' => "L'adresse email doit contenir au maximum 255 caractères.",
        'email.unique' => 'Cette adresse email existe déjà.',
        'email.ends_with' => "L'adresse email doit être une adresse Gmail (@gmail.com).",
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
        'password.max' => 'Le mot de passe doit contenir au maximum 255 caractères.',
        'password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
        'role.required' => 'Le rôle est obligatoire.',
        'role.integer' => 'Le rôle doit être un nombre entier.',
        'role.in' => 'Le rôle est incorrect.',
    ];

    public function mount(?int $userId = null): void
    {
        $this->superAdminExists = User::where('role', 1)->exists();

        if ($userId) {
            $this->mode = 'edit';
            $this->userId = $userId;
            $user = User::findOrFail($userId);
            $this->username = $user->username;
            $this->email = $user->email;
            $this->role = (string) $user->role;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->mode === 'create') {
            if ((int) $this->role === 1 && User::where('role', 1)->exists()) {
                $this->addError('role', 'Un super admin existe déjà. Vous ne pouvez pas en créer un second.');

                return;
            }

            User::create([
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => (int) $this->role,
                'status' => 1,
            ]);

            $this->dispatch('user-saved', message: 'Utilisateur créé avec succès !');
        } else {
            $updateData = [];
            if ($this->username !== '') {
                $updateData['username'] = $this->username;
            }
            if ($this->email !== '') {
                $updateData['email'] = $this->email;
            }
            if ($this->role !== '') {
                $updateData['role'] = (int) $this->role;
            }

            if (! empty($updateData)) {
                User::findOrFail($this->userId)->update($updateData);
            }

            $this->dispatch('user-saved', message: 'Utilisateur modifié avec succès !');
        }
    }

    public function render()
    {
        return view('livewire.users.form');
    }
}
