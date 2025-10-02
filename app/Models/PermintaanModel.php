<?php namespace App\Models;

use CodeIgniter\Model;

class PermintaanModel extends Model
{
    protected $table      = 'permintaan';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false; 

    // Disesuaikan dengan kolom baru
    protected $allowedFields = ['pemohon_id', 'tgl_masak', 'menu_makan', 'jumlah_porsi', 'status'];

    // Timestamps hanya untuk created_at
    protected $useTimestamps = true; 
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; 
    protected $deletedField  = '';
}