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
        // PERBAIKAN: Mengganti '\' menjadi '::' untuk memanggil static method session()
        $this->session = \Config\Services::session(); 
        
        // Memuat helper 'status' untuk logika kedaluwarsa
        helper(['url', 'form', 'status']); 

        // Autentikasi dan Role Check (Gudang)
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'gudang') {
            // Jika belum login atau bukan gudang, diarahkan ke login
            return redirect()->to(base_url('login'));
        }
    }
    
    // C.1.b: Menampilkan daftar bahan baku (Fitur Monitoring Stok)
    public function index()
    {
        $dataBahan = $this->bahanBakuModel->orderBy('tanggal_kadaluarsa', 'ASC')->findAll();
        $listBahan = [];

        // Iterasi untuk menghitung status terkini dan memperbarui di DB jika perlu
        foreach ($dataBahan as $bahan) {
            $statusOtomatis = hitung_status_bahan($bahan['tanggal_kadaluarsa'], $bahan['jumlah']);
            
            // Jika status di database berbeda dengan status otomatis, update.
            if ($bahan['status'] !== $statusOtomatis) {
                 $this->bahanBakuModel->update($bahan['id'], ['status' => $statusOtomatis]);
                 $bahan['status'] = $statusOtomatis; // Update array untuk tampilan
            }
            
            $listBahan[] = $bahan;
        }

        $data = [
            'title' => 'Daftar Bahan Baku',
            'listBahan' => $listBahan
        ];

        return view('bahan_baku/list', $data);
    }

    // C.1.a: Menampilkan form Tambah Bahan Baku
    public function tambah()
    {
        $data = ['title' => 'Tambah Bahan Baku Baru'];
        return view('bahan_baku/tambah', $data); 
    }
    
    // C.1.a: Memproses penambahan bahan baku
    public function simpan()
    {
        // Validasi Input CI4 Bawaan (Dihapus, hanya mengandalkan pengecekan manual di bawah)
        
        $tglMasuk = $this->request->getPost('tanggal_masuk');
        $tglKadaluarsa = $this->request->getPost('tanggal_kadaluarsa');
        $today = date('Y-m-d');

        // Pengecekan dasar Required (dilakukan manual karena validasi CI4 dilewati)
        if (empty($tglMasuk) || empty($tglKadaluarsa) || empty($this->request->getPost('nama')) || empty($this->request->getPost('jumlah'))) {
             session()->setFlashdata('error', 'Semua kolom bertanda bintang (*) harus diisi.');
             return redirect()->back()->withInput();
        }
        
        // 1. Validasi Manual Tanggal Masuk: Tidak boleh lebih dari hari ini
        if (strtotime($tglMasuk) > strtotime($today)) {
             session()->setFlashdata('errors', ['tanggal_masuk' => 'Tanggal Masuk tidak boleh melebihi tanggal hari ini.']);
             return redirect()->back()->withInput();
        }

        // 2. Validasi Manual Tanggal Kadaluarsa: Harus setelah Tanggal Masuk
        if (strtotime($tglKadaluarsa) <= strtotime($tglMasuk)) {
            session()->setFlashdata('errors', ['tanggal_kadaluarsa' => 'Tanggal Kadaluarsa harus **setelah** Tanggal Masuk.']);
            return redirect()->back()->withInput();
        }

        // 3. Siapkan data untuk disimpan
        $data = [
            'nama' => $this->request->getPost('nama'),
            'kategori' => $this->request->getPost('kategori'),
            'jumlah' => $this->request->getPost('jumlah'),
            'satuan' => $this->request->getPost('satuan'),
            'tanggal_masuk' => $tglMasuk,
            'tanggal_kadaluarsa' => $tglKadaluarsa,
            'status' => 'tersedia', // Status awal akan dihitung ulang di index
        ];

        // 4. Simpan ke database
        if ($this->bahanBakuModel->insert($data)) {
            session()->setFlashdata('success', 'Bahan baku **' . $data['nama'] . '** berhasil ditambahkan!');
            return redirect()->to(base_url('bahanbaku')); 
        } else {
            session()->setFlashdata('error', 'Gagal menyimpan data.');
            return redirect()->back()->withInput();
        }
    }

    // C.1.c: Menampilkan form edit bahan baku
    public function edit($id = null)
    {
        $bahan = $this->bahanBakuModel->find($id);

        if (!$bahan) {
            return redirect()->to(base_url('bahanbaku'))->with('error', 'Data bahan baku tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Bahan Baku: ' . $bahan['nama'],
            'bahan' => $bahan
        ];

        return view('bahan_baku/edit', $data);
    }

    // C.1.c: Memproses update data bahan baku
    public function update($id)
    {
        // Validasi Input CI4 Bawaan (Dihapus, hanya mengandalkan pengecekan manual di bawah)
        
        $data = $this->request->getPost();
        $today = date('Y-m-d');

        // Pengecekan dasar Required (dilakukan manual karena validasi CI4 dilewati)
        if (empty($data['tanggal_masuk']) || empty($data['tanggal_kadaluarsa']) || empty($data['nama']) || $data['jumlah'] === null) {
             session()->setFlashdata('error', 'Semua kolom bertanda bintang (*) harus diisi.');
             return redirect()->back()->withInput();
        }

        // 1. Validasi Manual Tanggal Masuk: Tidak boleh lebih dari hari ini
        if (strtotime($data['tanggal_masuk']) > strtotime($today)) {
             session()->setFlashdata('errors', ['tanggal_masuk' => 'Tanggal Masuk tidak boleh melebihi tanggal hari ini.']);
             return redirect()->back()->withInput();
        }
        
        // 2. Validasi Manual Tanggal Kadaluarsa: Harus setelah Tanggal Masuk
        if (strtotime($data['tanggal_kadaluarsa']) <= strtotime($data['tanggal_masuk'])) {
            session()->setFlashdata('errors', ['tanggal_kadaluarsa' => 'Tanggal Kadaluarsa harus **setelah** Tanggal Masuk.']);
            return redirect()->back()->withInput();
        }
        
        // Hitung status baru setelah update stok/tgl kadaluarsa
        $newStatus = hitung_status_bahan($data['tanggal_kadaluarsa'], $data['jumlah']);
        $data['status'] = $newStatus;
        
        // Menghapus _method dari post data agar tidak mengganggu update
        unset($data['_method']);

        if ($this->bahanBakuModel->update($id, $data)) {
            session()->setFlashdata('success', 'Bahan baku **' . esc($data['nama']) . '** berhasil diperbarui!');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui data bahan baku.');
        }

        return redirect()->to(base_url('bahanbaku'));
    }

    // C.1.d: Menghapus data bahan baku
    public function delete($id = null)
    {
        if ($this->bahanBakuModel->delete($id)) {
            session()->setFlashdata('success', 'Bahan baku berhasil dihapus dari stok.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus data bahan baku.');
        }

        return redirect()->to(base_url('bahanbaku'));
    }
}
