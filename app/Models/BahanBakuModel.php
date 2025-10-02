<?php namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table      = 'bahan_baku'; // Pastikan nama tabel benar
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false; // Atau true, sesuaikan kebutuhan

    // Kolom yang diizinkan untuk diisi (field database)
    protected $allowedFields = [
        'nama', 
        'kategori', 
        'jumlah', 
        'satuan', 
        'tanggal_masuk', 
        'tanggal_kadaluarsa', 
        'status'
    ];

    // Dates
    protected $useTimestamps = false; // PERBAIKAN: Diubah menjadi FALSE
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at'; // Hanya jika useSoftDeletes = true

    // Validation
    // PENTING: Validation Rules di sini KOSONGKAN atau hanya pakai rules dasar
    // untuk menghindari konflik dengan rules kustom 'after_current_date' 
    // atau 'after' bawaan CI4 yang bisa memicu error.
    protected $validationRules    = [
        'nama'               => 'required|min_length[3]',
        'kategori'           => 'required',
        'jumlah'             => 'required|integer',
        'satuan'             => 'required',
        'tanggal_masuk'      => 'required', // Hanya required
        'tanggal_kadaluarsa' => 'required', // Hanya required
    ];
    
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;
}
