<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        $role = session()->get('role');
        $data = [
            'title' => 'Dashboard',
            'role'  => $role,
        ];

        if ($role === 'gudang') {
            return view('dashboard/gudang', $data);
        }

        if ($role === 'dapur') {
            return view('dashboard/dapur', $data);
        }

        return redirect()->to(base_url('login'));
    }
}