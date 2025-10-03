<?php

namespace App\Models;

use CodeIgniter\Model;

class PermintaanDetailModel extends Model
{
    protected $table            = 'permintaan_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'permintaan_id',
        'bahan_id',
        'jumlah_diminta'
    ];

    // Nonaktifkan timestamps karena tidak ada di tabel Anda
    protected $useTimestamps = false;
}
