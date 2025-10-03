<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\PermintaanDetailModel; // TAMBAHKAN USE STATEMENT INI
use App\Models\UserModel;

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
            ->select('permintaan.*, user.name as nama_pemohon')
            ->join('user', 'user.id = permintaan.pemohon_id')
            ->findAll();

        $data['title'] = 'Daftar Permintaan Bahan Baku';

        return view('gudang/permintaan/index', $data);
    }

    /**
     * TAMBAHKAN METHOD BARU INI
     * Menampilkan detail satu permintaan beserta bahan-bahan yang diminta.
     */
    public function show($id = null)
    {
        $permintaanModel = new PermintaanModel();
        $permintaanDetailModel = new PermintaanDetailModel();

        // Ambil data permintaan utama, gabungkan dengan nama pemohon
        $permintaan = $permintaanModel
            ->select('permintaan.*, user.name as nama_pemohon')
            ->join('user', 'user.id = permintaan.pemohon_id')
            ->find($id);

        if (!$permintaan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Permintaan tidak ditemukan.');
        }

        // Ambil semua detail bahan yang terkait dengan permintaan ini
        // Gabungkan juga dengan data dari tabel bahan_baku untuk mendapatkan nama bahan & satuan
        $detail_bahan = $permintaanDetailModel
            ->select('permintaan_detail.*, bahan_baku.nama, bahan_baku.satuan')
            ->join('bahan_baku', 'bahan_baku.id = permintaan_detail.bahan_id')
            ->where('permintaan_id', $id)
            ->findAll();

        $data = [
            'title'      => 'Detail Permintaan #' . $permintaan['id'],
            'permintaan' => $permintaan,
            'detail_bahan' => $detail_bahan,
        ];

        return view('gudang/permintaan/show', $data);
    }
}
