<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModels;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        helper('url'); 
    }

    // Menampilkan halaman login
    public function login()
    {
        if ($this->session->get('user_id')) {
            if ($this->session->get('role') === 'gudang') {
                return redirect()->to(base_url('dashboard/gudang'));
            } else {
                return redirect()->to(base_url('dashboard/dapur'));
            }
        }
        
        $data = ['title' => 'Login Sistem MBG'];
        return view('auth/login', $data);
    }

    // Memproses permintaan login
    public function loginProcess()
    {
        if (!$this->validate([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[3]' 
        ])) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if ($user) {
            

            if ($password === $user['password']) { 
                
                $sessData = [
                    'user_id'    => $user['id'],
                    'name'       => $user['name'],
                    'role'       => $user['role'],
                    'isLoggedIn' => TRUE
                ];
                $this->session->set($sessData);

                if ($user['role'] === 'gudang') {
                    session()->setFlashdata('success', 'Selamat datang, Petugas Gudang!');
                    return redirect()->to(base_url('dashboard/gudang'));
                } else {
                    session()->setFlashdata('success', 'Selamat datang, Petugas Dapur!');
                    return redirect()->to(base_url('dashboard/dapur'));
                }
            } else {
                session()->setFlashdata('error', 'Email atau Password Salah.');
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('error', 'Email atau Password Salah.');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        $this->session->destroy();
        session()->setFlashdata('info', 'Anda telah berhasil logout.');
        return redirect()->to(base_url('login'));
    }
}