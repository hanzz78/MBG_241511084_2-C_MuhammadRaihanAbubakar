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
        $semuaBahanBaku = $model->findAll();
        $bahanBakuTerkini = [];

        $today = date('Y-m-d');

        foreach ($semuaBahanBaku as $bahan) {
            $tanggalKadaluarsa = $bahan['tanggal_kadaluarsa'];
            $selisih = strtotime($tanggalKadaluarsa) - strtotime($today);
            $selisihHari = $selisih / (60 * 60 * 24);

            if ($selisihHari < 0) {
                $bahan['status'] = 'kadaluarsa';
            } elseif ($selisihHari <= 3) {
                $bahan['status'] = 'segera kadaluarsa';
            } else {
                $bahan['status'] = 'tersedia';
            }

            if ((int)$bahan['jumlah'] === 0) {
                $bahan['status'] = 'habis';
            }
            $bahanBakuTerkini[] = $bahan;
        }

        $data = [
            'title' => 'Daftar Bahan Baku',
            'bahan_baku' => $bahanBakuTerkini
        ];

        return view('gudang/bahanbaku/index', $data);
    }

    /**
     * Menampilkan form untuk menambah data bahan baku baru.
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
     */
    public function create()
    {
        $model = new BahanBakuModel();

        $tanggalMasuk = $this->request->getPost('tanggal_masuk');
        $tanggalKadaluarsa = $this->request->getPost('tanggal_kadaluarsa');

        if ($tanggalKadaluarsa < $tanggalMasuk) {
            return redirect()->back()->withInput()->with('errors', ['tanggal_kadaluarsa' => 'Tanggal kadaluarsa tidak boleh lebih awal dari tanggal masuk.']);
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'jumlah' => $this->request->getPost('jumlah'),
            'satuan' => $this->request->getPost('satuan'),
            'tanggal_masuk' => $tanggalMasuk,
            'tanggal_kadaluarsa' => $tanggalKadaluarsa,
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
     * Menampilkan form untuk mengedit data bahan baku.
     */
    public function edit($id = null)
    {
        $model = new BahanBakuModel();
        $bahan = $model->find($id);

        if (!$bahan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Bahan baku tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Bahan Baku',
            'bahan' => $bahan
        ];

        return view('gudang/bahanbaku/edit', $data);
    }

    /**
     * Mengupdate data bahan baku di database.
     */
    public function update($id = null)
    {
        $model = new BahanBakuModel();

        $tanggalMasuk = $this->request->getPost('tanggal_masuk');
        $tanggalKadaluarsa = $this->request->getPost('tanggal_kadaluarsa');

        if ($tanggalKadaluarsa < $tanggalMasuk) {
            return redirect()->back()->withInput()->with('errors', ['tanggal_kadaluarsa' => 'Tanggal kadaluarsa tidak boleh lebih awal dari tanggal masuk.']);
        }
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'jumlah' => $this->request->getPost('jumlah'),
            'satuan' => $this->request->getPost('satuan'),
            'tanggal_masuk' => $tanggalMasuk,
            'tanggal_kadaluarsa' => $tanggalKadaluarsa,
        ];
        
        if ((int)$this->request->getPost('jumlah') <= 0) {
            $data['status'] = 'habis';
        } else {
            $data['status'] = 'tersedia';
        }

        if ($model->update($id, $data)) {
            return redirect()->to('/gudang/bahanbaku')->with('message', 'Data bahan berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }
    // app/Controllers/gudang/BahanBaku.php

    // app/Controllers/gudang/BahanBaku.php

    public function delete($id = null)
    {
        $model = new BahanBakuModel();
        $bahan = $model->find($id);

        if (!$bahan) {
            return redirect()->to('/gudang/bahanbaku')->with('error', 'Bahan tidak ditemukan.');
        }

        $isExpired = strtotime($bahan['tanggal_kadaluarsa']) < strtotime(date('Y-m-d'));

        if ($isExpired) {
            // Baris ini sekarang akan berjalan
            $model->delete($id); 
            return redirect()->to('/gudang/bahanbaku')->with('message', 'Bahan kadaluarsa berhasil dihapus.');
        } else {
            return redirect()->to('/gudang/bahanbaku')->with('error', 'Hanya bahan dengan status kadaluarsa yang bisa dihapus.');
        }
    }
}