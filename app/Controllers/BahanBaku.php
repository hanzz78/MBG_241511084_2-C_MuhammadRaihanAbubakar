<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BahanBakuModel;

class BahanBaku extends BaseController
{
    protected $bahanBakuModel;
    protected $session;

    public function __construct()
    {
        $this->bahanBakuModel = new BahanBakuModel();
        $this->session = \Config\Services::session();
        // Memuat helper 'url', 'form' (untuk validasi), dan 'status' (untuk hitung_status_bahan)
        helper(['url', 'form', 'status']); 

        // Autentikasi dan Role Check (Hanya Petugas Gudang yang boleh akses)
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'gudang') {
            return redirect()->to(base_url('login'));
        }
    }
    
    // [C.1.b] Menampilkan daftar bahan baku dan mengupdate status otomatis
    public function index()
    {
        // Ambil semua data bahan baku, diurutkan berdasarkan tanggal kedaluwarsa terdekat
        $dataBahan = $this->bahanBakuModel->orderBy('tanggal_kadaluarsa', 'ASC')->findAll();
        $listBahan = [];

        // Iterasi untuk menghitung status terkini dan memperbarui di database
        foreach ($dataBahan as $bahan) {
            // Hitung status menggunakan Helper
            $statusOtomatis = hitung_status_bahan($bahan['tanggal_kadaluarsa'], $bahan['jumlah']);
            
            // Logika Aturan Bisnis: Jika status di DB berbeda dengan status otomatis, lakukan update
            if ($bahan['status'] !== $statusOtomatis) {
                 // Update status di database
                 $this->bahanBakuModel->update($bahan['id'], ['status' => $statusOtomatis]);
                 $bahan['status'] = $statusOtomatis; // Update array untuk tampilan yang akan di-loop
            }
            
            $listBahan[] = $bahan;
        }

        $data = [
            'title' => 'Daftar Bahan Baku',
            'listBahan' => $listBahan
        ];

        return view('bahan_baku/list', $data);
    }
    
    // [C.1.a] Menampilkan form Tambah Bahan Baku
    public function tambah()
    {
        $data = ['title' => 'Tambah Bahan Baku Baru'];
        return view('bahan_baku/tambah', $data); 
    }
    
    // [C.1.a] Memproses penambahan bahan baku
    public function simpan()
    {
        // 1. Validasi Input CI4
        if (!$this->validate([
            'nama' => 'required|min_length[3]',
            'kategori' => 'required',
            'jumlah' => 'required|integer|greater_than[0]',
            'satuan' => 'required',
            'tanggal_masuk' => 'required|valid_date|less_than_equal_to[today]', 
            // Tanggal kadaluarsa harus sama dengan atau setelah tanggal masuk
            'tanggal_kadaluarsa' => 'required|valid_date|after_equal[tanggal_masuk]',
        ])) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        // 2. Siapkan data untuk disimpan (Status awal 'tersedia')
        $data = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'jumlah' => $this->request->getPost('jumlah'),
            'satuan' => $this->request->getPost('satuan'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'tanggal_kadaluarsa' => $this->request->getPost('tanggal_kadaluarsa'),
            'status' => 'tersedia', // Status awal
        ];

        // 3. Simpan ke database
        if ($this->bahanBakuModel->insert($data)) {
            session()->setFlashdata('success', 'Bahan baku **' . $data['nama'] . '** berhasil ditambahkan!');
            return redirect()->to(base_url('bahanbaku')); // Redirect ke list bahan baku
        } else {
            session()->setFlashdata('error', 'Gagal menyimpan data.');
            return redirect()->back()->withInput();
        }
    }
}
