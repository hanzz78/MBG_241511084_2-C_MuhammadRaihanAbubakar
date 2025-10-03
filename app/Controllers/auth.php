<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Login Aplikasi MBG',
        ];

        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // **MD5 Verification:** Hash password input sebelum dibandingkan
        $md5Password = md5($password); 

        $user = $this->userModel->where('email', $email)->first();

        // **MD5 Verification:** Bandingkan MD5 password input dengan MD5 password di database
        if (! $user || $md5Password !== $user['password']) { 
            return redirect()->back()->withInput()->with('error', 'Email atau Password salah.');
        }

        // Set Session
        $ses_data = [
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ];
        session()->set($ses_data);

        return redirect()->to(base_url('dashboard'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda berhasil logout.');
    }
}