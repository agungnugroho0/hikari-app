<?php

namespace App\Livewire\Auth;

use App\Models\Staff;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $username;
    public $password;

    protected $rules = [
        'username'  => 'required',
        'password'=> 'required',
    ];

    protected $messages = [
    'username.required' => 'Username wajib diisi',
    'password.required' => 'Password wajib diisi',
];


    public function login(){
        $this->validate();

        if (Auth::attempt([
            'username'=> $this->username,
            'password'=> $this->password,
        ]))
        {
            session()->regenerate();

            // ini hanya digunakan jika ada 1 user akses (tidak multiuser)
            // return redirect()->intended('/dashboard');

            // ini digunakan untuk multiuser
            $user = Auth::user();

            if ($user instanceof Staff && Hash::check('123456', $user->password)) {
                return match ($user->akses) {
                    'admin' => redirect()->route('setelan'),
                    'guru' => redirect()->route('sensei.profil'),
                    default => redirect()->to('/dev/dashboard'),
                };
            }

            return match ($user->akses) {
                'admin' => redirect()->to('/dashboard'),
                'guru'  => redirect()->to('/sensei/dashboard'),
                'dev'   => redirect()->to('/dev/dashboard'),
                default => abort(403),
            };
        }
        $this->addError('login', 'username atau password salah');
    }
    
    public function render()
    {
        return view('livewire.auth.login');
    }
}
