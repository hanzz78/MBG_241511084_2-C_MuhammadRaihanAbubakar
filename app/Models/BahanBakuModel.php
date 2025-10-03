<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table            = 'bahan_baku';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama',
        'kategori',
        'jumlah',
        'satuan',
        'tanggal_masuk',
        'tanggal_kadaluarsa',
        'status',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation

    protected $validationRules      = [
        'nama'               => 'required|min_length[3]|max_length[120]',
        'jumlah'             => 'required|integer|greater_than_equal_to[0]',
        'satuan'             => 'required|max_length[20]',
        'tanggal_masuk'      => 'required|valid_date',
        'tanggal_kadaluarsa' => 'required|valid_date',
    ];
    protected $validationMessages   = [
        // Ini adalah pesan error kustom yang akan muncul jika aturan dilanggar
        'tanggal_kadaluarsa' => [
            'greater_than_equal_to' => 'Tanggal kadaluarsa tidak boleh lebih awal dari tanggal masuk.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}