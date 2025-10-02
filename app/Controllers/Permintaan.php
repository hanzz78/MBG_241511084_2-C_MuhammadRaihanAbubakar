<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\PermintaanDetailModel; 
use App\Models\BahanBakuModel;

class Permintaan extends BaseController
{
    protected $permintaanModel;
    protected $detailPermintaanModel;
    protected $bahanBakuModel;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->permintaanModel = new PermintaanModel();
        $this->detailPermintaanModel = new PermintaanDetailModel(); 
        $this->bahanBakuModel = new BahanBakuModel();
        $this->session = \Config\Services::session(); 
        $this->db = \Config\Database::connect(); 
        
        helper(['url', 'form', 'status']); 
        
        if (!$this->session->get('user_id')) {
            return redirect()->to(base_url('login'));
        }
    }

    // A.1.e: Dapur melihat daftar permintaan yang pernah diajukan
    public function list()
    {
        if ($this->session->get('role') !== 'dapur') {
            session()->setFlashdata('error', 'Akses ditolak.');
            return redirect()->to(base_url('dashboard'));
        }
        
        $userId = $this->session->get('user_id');
        $listPermintaan = $this->permintaanModel
                               ->where('pemohon_id', $userId) // DIPERBAIKI: pakai pemohon_id
                               ->orderBy('created_at', 'DESC')
                               ->findAll();

        $data = [
            'title' => 'Riwayat Permintaan Bahan Baku',
            'listPermintaan' => $listPermintaan
        ];

        return view('permintaan/list_dapur', $data);
    }
    
    // C.1.e: Gudang melihat daftar permintaan yang butuh persetujuan
    public function approvalList()
    {
        if ($this->session->get('role') !== 'gudang') {
            session()->setFlashdata('error', 'Akses ditolak.');
            return redirect()->to(base_url('dashboard'));
        }
        
        // DIPERBAIKI: JOIN ke tabel 'user' dan SELECT 'u.name'
        $listPermintaan = $this->db->table('permintaan p')
            ->select('p.*, u.name as user_name') // Mengambil nama dari kolom 'name'
            ->join('user u', 'u.id = p.pemohon_id', 'left') // JOIN ke tabel 'user' menggunakan 'pemohon_id'
            ->orderBy('created_at', 'ASC')
            ->get() 
            ->getResultArray(); 

        $data = [
            'title' => 'Daftar Persetujuan Permintaan',
            'listPermintaan' => $listPermintaan
        ];

        return view('permintaan/list_gudang', $data);
    }

    // C.1.e: Menampilkan detail permintaan (dipakai Gudang dan Dapur)
    public function detail($permintaanId)
    {
        $permintaan = $this->permintaanModel->find($permintaanId);

        if (!$permintaan) {
            session()->setFlashdata('error', 'Permintaan tidak ditemukan.');
            return redirect()->back();
        }
        
        $role = $this->session->get('role');
        
        // Cek akses: hanya pemohon atau Gudang
        if ($role !== 'gudang' && $permintaan['pemohon_id'] !== $this->session->get('user_id')) { // DIPERBAIKI: pakai pemohon_id
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke permintaan ini.');
            return redirect()->to(base_url('dashboard'));
        }

        // Ambil detail permintaan dan gabungkan dengan stok Bahan Baku
        $details = $this->db->table('permintaan_detail dp')
            ->select('dp.*, bb.nama as bahan_nama, bb.satuan, bb.jumlah as stok_saat_ini')
            ->join('bahan_baku bb', 'bb.id = dp.bahan_id', 'left') // DIPERBAIKI: kolom dp.bahan_id
            ->where('dp.permintaan_id', $permintaanId)
            ->get()
            ->getResultArray();
        
        // DIPERBAIKI: SELECT dari tabel 'user' dan kolom 'name'
        $user = $this->db->table('user')->select('name')->where('id', $permintaan['pemohon_id'])->get()->getRow();
        $pemohonName = $user ? $user->name : 'Pengguna Tidak Dikenal'; // Mengambil 'name'

        $data = [
            'title' => 'Detail Permintaan',
            'permintaan' => $permintaan,
            'details' => $details,
            'role' => $role,
            'pemohon_name' => $pemohonName
        ];

        return view('permintaan/detail', $data);
    }
    
    // C.1.f: Memproses persetujuan permintaan dan mengurangi stok
    public function approve($permintaanId)
    {
        if ($this->session->get('role') !== 'gudang') {
            session()->setFlashdata('error', 'Akses ditolak.');
            return redirect()->back();
        }
        
        $permintaan = $this->permintaanModel->find($permintaanId);
        
        if (!$permintaan || $permintaan['status'] !== 'menunggu') {
            session()->setFlashdata('error', 'Permintaan tidak valid atau sudah diproses.');
            return redirect()->back();
        }

        $details = $this->detailPermintaanModel->where('permintaan_id', $permintaanId)->findAll();
        
        $this->db->transBegin(); 

        try {
            // 1. Cek Ketersediaan Stok
            foreach ($details as $detail) {
                $bahanBaku = $this->bahanBakuModel->find($detail['bahan_id']); // DIPERBAIKI: pakai bahan_id
                
                if (!$bahanBaku || $bahanBaku['jumlah'] < $detail['jumlah_diminta']) {
                    $this->db->transRollback();
                    session()->setFlashdata('error', 'Gagal menyetujui: Stok **' . ($bahanBaku['nama'] ?? 'bahan') . '** tidak mencukupi atau tidak ditemukan.');
                    return redirect()->back();
                }
            }
            
            // 2. Kurangi Stok dan Hitung Ulang Status Bahan Baku
            foreach ($details as $detail) {
                $bahanBaku = $this->bahanBakuModel->find($detail['bahan_id']); // DIPERBAIKI: pakai bahan_id
                $newJumlah = $bahanBaku['jumlah'] - $detail['jumlah_diminta'];
                
                $newStatus = hitung_status_bahan($bahanBaku['tanggal_kadaluarsa'], $newJumlah);
                
                $this->bahanBakuModel->update($detail['bahan_id'], [ // DIPERBAIKI: pakai bahan_id
                    'jumlah' => $newJumlah,
                    'status' => $newStatus
                ]);
            }
            
            // 3. Update Status Permintaan
            $this->permintaanModel->update($permintaanId, [
                'status' => 'disetujui',
                'tgl_persetujuan' => date('Y-m-d H:i:s'),
                'user_persetujuan' => $this->session->get('user_id')
            ]);

            $this->db->transCommit(); 
            session()->setFlashdata('success', 'Permintaan **#' . $permintaanId . '** berhasil disetujui. Stok bahan baku telah dikurangi.');
            
        } catch (\Exception $e) {
            $this->db->transRollback(); 
            session()->setFlashdata('error', 'Terjadi kesalahan sistem saat memproses transaksi: ' . $e->getMessage());
        }

        return redirect()->to(base_url('permintaan/gudang'));
    }

    // C.1.f: Memproses penolakan permintaan
    public function reject($permintaanId)
    {
        if ($this->session->get('role') !== 'gudang') {
            session()->setFlashdata('error', 'Akses ditolak.');
            return redirect()->back();
        }
        
        $permintaan = $this->permintaanModel->find($permintaanId);
        
        if (!$permintaan || $permintaan['status'] !== 'menunggu') {
            session()->setFlashdata('error', 'Permintaan tidak valid atau sudah diproses.');
            return redirect()->back();
        }

        // Update Status Permintaan menjadi ditolak
        $this->permintaanModel->update($permintaanId, [
            'status' => 'ditolak',
            'tgl_persetujuan' => date('Y-m-d H:i:s'),
            'user_persetujuan' => $this->session->get('user_id')
        ]);

        session()->setFlashdata('success', 'Permintaan **#' . $permintaanId . '** berhasil ditolak.');
        return redirect()->to(base_url('permintaan/gudang'));
    }
    
}