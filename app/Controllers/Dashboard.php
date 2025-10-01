<?php namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        helper('url');
        
        // Cek apakah user sudah login
        if (!$this->session->get('user_id')) {
            return redirect()->to(base_url('login'));
        }
    }

    // Dashboard untuk Petugas Gudang (Admin)
    public function gudang()
    {
        // Pastikan role-nya adalah gudang
        if ($this->session->get('role') !== 'gudang') {
            return redirect()->to(base_url('login'));
        }
        
        $data = ['title' => 'Dashboard Gudang', 'name' => $this->session->get('name')];
        // Panggil view yang sesuai
        return view('dashboard/gudang', $data); 
    }

    // Dashboard untuk Petugas Dapur (Client)
    public function dapur()
    {
        // Pastikan role-nya adalah dapur
        if ($this->session->get('role') !== 'dapur') {
            return redirect()->to(base_url('login'));
        }

        $data = ['title' => 'Dashboard Dapur', 'name' => $this->session->get('name')];
        // Panggil view yang sesuai
        return view('dashboard/dapur', $data); 
    }
}