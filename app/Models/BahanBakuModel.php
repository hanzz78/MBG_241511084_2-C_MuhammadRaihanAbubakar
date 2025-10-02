<?php namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table      = 'bahan_baku';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false; 

    protected $allowedFields = [
        'nama', 'kategori', 'jumlah', 'satuan', 'tanggal_masuk', 
        'tanggal_kadaluarsa', 'status'
    ];

    // --- PENTING: PERBAIKAN TIMESTAMPS ---
    protected $useTimestamps = true; // Tetap true karena ada created_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // GANTI: Gunakan string kosong untuk menonaktifkan update timestamp
    protected $deletedField  = ''; // GANTI: Gunakan string kosong untuk menonaktifkan soft delete
    // ------------------------------------

    // Aturan validasi (sama seperti sebelumnya)
    protected $validationRules = [
        'nama' => 'required',
        'kategori' => 'required',
        'jumlah' => 'required|integer|greater_than[0]',
        'satuan' => 'required',
        'tanggal_masuk' => 'required|valid_date',
        'tanggal_kadaluarsa' => 'required|valid_date|after_current_date'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
}