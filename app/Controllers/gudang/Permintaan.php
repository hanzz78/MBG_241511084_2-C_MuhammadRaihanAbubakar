<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\UserModel; // Kita butuh ini untuk mengambil nama pemohon

class Permintaan extends BaseController
{
    /**
     * Menampilkan daftar semua permintaan bahan baku.
     */
    public function index()
    {
        $permintaanModel = new PermintaanModel();

        // Mengambil semua data permintaan dan menggabungkannya dengan data user (pemohon)
        $data['permintaan'] = $permintaanModel
            ->select('permintaan.*, user.name as nama_pemohon') // Ambil semua dari permintaan + username
            ->join('user', 'user.id = permintaan.pemohon_id') // Gabungkan berdasarkan ID pemohon
            ->findAll();

        $data['title'] = 'Daftar Permintaan Bahan Baku';

        return view('gudang/permintaan/index', $data);
    }
}