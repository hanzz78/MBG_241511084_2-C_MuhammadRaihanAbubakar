<?php namespace App\Models;

use CodeIgniter\Model;

class PermintaanDetailModel extends Model
{
    protected $table      = 'permintaan_detail';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false; 

    // Disesuaikan: bahan_baku_id diganti bahan_id
    protected $allowedFields = ['permintaan_id', 'bahan_id', 'jumlah_diminta'];

    // Tidak menggunakan timestamps sama sekali
    protected $useTimestamps = false; 
}