<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\UserModel;
use App\Models\PermintaanDetailModel;
use App\Models\BahanBakuModel;

class Permintaan extends BaseController
{
    protected $db; // Tambahkan properti untuk database

    public function __construct()
    {
        // Inisialisasi koneksi database di constructor
        $this->db = \Config\Database::connect();
    }

    /**
     * Menampilkan daftar semua permintaan bahan baku.
     */
    public function index()
    {
        $permintaanModel = new PermintaanModel();
        $data['permintaan'] = $permintaanModel
            ->select('permintaan.*, user.name as nama_pemohon')
            ->join('user', 'user.id = permintaan.pemohon_id')
            ->orderBy('permintaan.created_at', 'ASC')
            ->findAll();
        $data['title'] = 'Daftar Permintaan Bahan Baku';
        return view('gudang/permintaan/index', $data);
    }

    /**
     * Menampilkan detail satu permintaan beserta bahan-bahan yang diminta.
     */
    public function show($id = null)
    {
        $permintaanModel = new PermintaanModel();
        $permintaanDetailModel = new PermintaanDetailModel();

        $permintaan = $permintaanModel
            ->select('permintaan.*, user.name as nama_pemohon')
            ->join('user', 'user.id = permintaan.pemohon_id')
            ->find($id);

        if (!$permintaan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Permintaan tidak ditemukan.');
        }

        $detail_bahan = $permintaanDetailModel
            ->select('permintaan_detail.*, bahan_baku.nama, bahan_baku.satuan, bahan_baku.jumlah as stok_saat_ini')
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

    /**
     * Memproses persetujuan atau penolakan permintaan.
     * Termasuk logika pengurangan stok dan respons AJAX.
     */
    public function updateStatus($id = null)
    {
        $permintaanModel = new PermintaanModel();
        $bahanBakuModel = new BahanBakuModel();
        $permintaanDetailModel = new PermintaanDetailModel();

        $newStatus = $this->request->getPost('status');

        if (!in_array($newStatus, ['disetujui', 'ditolak'])) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Aksi tidak valid.']);
        }
        
        if ($newStatus === 'disetujui') {
            $detail_bahan = $permintaanDetailModel->where('permintaan_id', $id)->findAll();

            $this->db->transStart();

            foreach ($detail_bahan as $item) {
                $bahan = $bahanBakuModel->find($item['bahan_id']);
                
                if (!$bahan || $bahan['jumlah'] < $item['jumlah_diminta']) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Stok untuk ' . ($bahan['nama'] ?? 'item') . ' tidak mencukupi.']);
                }

                $newStock = $bahan['jumlah'] - $item['jumlah_diminta'];
                $bahanBakuModel->update($item['bahan_id'], ['jumlah' => $newStock]);
            }

            $permintaanModel->update($id, ['status' => 'disetujui']);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                 return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Terjadi kesalahan pada database.']);
            }

        } else { 
            $permintaanModel->update($id, ['status' => 'ditolak']);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Status permintaan berhasil diubah.', 'new_status' => $newStatus, 'csrf_hash' => csrf_hash()]);
    }
}

