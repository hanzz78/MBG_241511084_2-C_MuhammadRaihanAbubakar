<?php

namespace App\Models;

use CodeIgniter\Model;

class PermintaanModel extends Model
{
    protected $table            = 'permintaan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pemohon_id',
        'tgl_masak',
        'menu_makan',
        'jumlah_porsi',
        'status',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false; // Karena Anda sudah punya created_at secara manual
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    // Validation (bisa ditambahkan nanti)
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}