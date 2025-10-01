<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel; // Variabel harus konsisten: $userModel (tanpa 's')
    protected $session;

    public function __construct()
    {
        // Pastikan nama class yang dipanggil adalah UserModel
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
        // 1. Validasi Input
        if (!$this->validate([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[3]' 
        ])) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        // 2. Ambil dan bersihkan input
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        // 3. Cari user berdasarkan email
        $user = $this->userModel->where('email', $email)->first();

        if ($user) {
            // 4. Perbandingan Password menggunakan MD5
            // Input password di-MD5-kan sebelum dibandingkan dengan hash di database
            if (md5($password) === $user['password']) { 
                
                // Login Berhasil
                $sessData = [
                    'user_id'    => $user['id'],
                    'name'       => $user['name'],
                    'role'       => $user['role'],
                    'isLoggedIn' => TRUE
                ];
                $this->session->set($sessData);

                // Arahkan sesuai role
                if ($user['role'] === 'gudang') {
                    session()->setFlashdata('success', 'Selamat datang, Petugas Gudang!');
                    return redirect()->to(base_url('dashboard/gudang'));
                } else {
                    session()->setFlashdata('success', 'Selamat datang, Petugas Dapur!');
                    return redirect()->to(base_url('dashboard/dapur'));
                }
            } else {
                // Password MD5 tidak cocok
                session()->setFlashdata('error', 'Email atau Password Salah.');
                return redirect()->back()->withInput();
            }
        } else {
            // Email tidak ditemukan
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