<?php

namespace App\Controllers\Dapur;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\PermintaanDetailModel; // Tambahkan ini
use App\Models\BahanBakuModel;       // Tambahkan ini

class Permintaan extends BaseController
{
    /**
     * Menampilkan riwayat permintaan dari user dapur yang sedang login.
     */
    public function index()
    {
        $permintaanModel = new PermintaanModel();
        
        $pemohonId = session()->get('user_id');

        $data['permintaan'] = $permintaanModel
            ->where('pemohon_id', $pemohonId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data['title'] = 'Riwayat Permintaan Saya';

        return view('dapur/permintaan/index', $data);
    }

    /**
     * Menampilkan form untuk membuat permintaan baru dengan daftar bahan baku.
     */
    public function new()
    {
        $bahanBakuModel = new BahanBakuModel();

        $data = [
            'title'     => 'Buat Permintaan Bahan Baku Baru',
            'bahan_baku' => $bahanBakuModel->where('status', 'tersedia')->where('jumlah >', 0)->findAll(),
        ];
        return view('dapur/permintaan/create', $data);
    }

    /**
     * Menyimpan data permintaan baru ke tabel 'permintaan' dan 'permintaan_detail'.
     */
    public function create()
    {
        $permintaanModel = new PermintaanModel();
        $permintaanDetailModel = new PermintaanDetailModel();

        // Data untuk tabel 'permintaan' utama
        $permintaanData = [
            'pemohon_id'   => session()->get('user_id'),
            'tgl_masak'    => $this->request->getPost('tgl_masak'),
            'menu_makan'   => $this->request->getPost('menu_makan'),
            'jumlah_porsi' => $this->request->getPost('jumlah_porsi'),
            'status'       => 'menunggu',
            'created_at'   => date('Y-m-d H:i:s'),
        ];
        
        if ($permintaanModel->save($permintaanData)) {
            $permintaanId = $permintaanModel->getInsertID();

            $bahanIds = $this->request->getPost('bahan_id');
            $jumlahDiminta = $this->request->getPost('jumlah_diminta');

            if (!empty($bahanIds)) {
                foreach ($bahanIds as $index => $bahanId) {
                    // Hanya simpan jika ada jumlah yang diminta
                    if (!empty($jumlahDiminta[$index]) && $jumlahDiminta[$index] > 0) {
                        $detailData = [
                            'permintaan_id'  => $permintaanId,
                            'bahan_id'       => $bahanId,
                            'jumlah_diminta' => $jumlahDiminta[$index],
                        ];
                        $permintaanDetailModel->save($detailData);
                    }
                }
            }
            
            return redirect()->to('/dapur/permintaan')->with('message', 'Permintaan berhasil diajukan.');
        } else {
            return redirect()->back()->withInput()->with('errors', $permintaanModel->errors());
        }
    }
}
