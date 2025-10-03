<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\BahanBakuModel;

class BahanBaku extends BaseController
{
    /**
     * Menampilkan daftar semua bahan baku.
     */
    public function index()
    {
        $model = new BahanBakuModel();
        $data = [
            'title' => 'Daftar Bahan Baku',
            'bahan_baku' => $model->findAll()
        ];
        return view('gudang/bahanbaku/index', $data);
    }

    /**
     * =================================================================
     * METHOD YANG MENYEBABKAN ERROR ADA DI SINI
     * Pastikan method ini ada dan namanya "new" (huruf kecil semua).
     * Method ini untuk MENAMPILKAN FORM tambah data.
     * =================================================================
     */
    public function new()
    {
        $data = [
            'title' => 'Tambah Bahan Baku',
        ];
        return view('gudang/bahanbaku/create', $data);
    }

    /**
     * Menyimpan data bahan baku baru ke database.
     * Method ini untuk MEMPROSES DATA dari form.
     */
    public function create()
    {
        $model = new BahanBakuModel();

        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'jumlah' => $this->request->getPost('jumlah'),
            'satuan' => $this->request->getPost('satuan'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'tanggal_kadaluarsa' => $this->request->getPost('tanggal_kadaluarsa'),
            'status' => 'tersedia',
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($model->save($data)) {
            return redirect()->to('/gudang/bahanbaku')->with('message', 'Bahan baku berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }


    /**
     * Mengupdate data bahan baku di database.
     * Terhubung ke route: PUT/PATCH /gudang/bahanbaku/(:num)
     */
    public function update($id = null)
    {
        $model = new BahanBakuModel();
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'jumlah' => $this->request->getPost('jumlah'),
            'satuan' => $this->request->getPost('satuan'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'tanggal_kadaluarsa' => $this->request->getPost('tanggal_kadaluarsa'),
        ];
        
        if ((int)$this->request->getPost('jumlah') > 0) {
            $data['status'] = 'tersedia';
        } else {
            $data['status'] = 'habis';
        }

        if ($model->update($id, $data)) {
            return redirect()->to('/gudang/bahanbaku')->with('message', 'Data bahan berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }

    /**
     * Menghapus data bahan baku.
     * Terhubung ke route: DELETE /gudang/bahanbaku/(:num)
     */
    public function delete($id = null)
    {
        $model = new BahanBakuModel();
        $bahan = $model->find($id);

        if ($bahan) {
            if ($bahan['status'] == 'kadaluarsa') {
                $model->delete($id);
                return redirect()->to('/gudang/bahanbaku')->with('message', 'Bahan kadaluarsa berhasil dihapus.');
            } else {
                return redirect()->to('/gudang/bahanbaku')->with('message', 'Hanya bahan dengan status kadaluarsa yang bisa dihapus.');
            }
        }
        
        return redirect()->to('/gudang/bahanbaku')->with('message', 'Bahan tidak ditemukan.');
    }
}