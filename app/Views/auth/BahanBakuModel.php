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
        'nama', 'kategori', 'jumlah', 'satuan', 'tanggal_masuk', 'tanggal_kadaluarsa', 'status', 'created_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation
    protected $validationRules = [
        'nama'               => 'required|max_length[120]',
        'kategori'           => 'required|max_length[60]',
        'jumlah'             => 'required|integer|greater_than_equal_to[0]',
        'satuan'             => 'required|max_length[20]',
        'tanggal_masuk'      => 'required|valid_date',
        'tanggal_kadaluarsa' => 'required|valid_date|after_current_date',
    ];
    protected $validationMessages = [
        'jumlah' => [
            'greater_than_equal_to' => 'Stok tidak boleh kurang dari 0.',
        ],
    ];

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setInitialStatus'];
    protected $afterUpdate    = ['updateCalculatedStatus'];
    protected $beforeDelete   = ['checkDeletionStatus'];

    protected function setInitialStatus(array $data)
    {
        // Status awal harus 'tersedia' jika jumlah > 0, jika jumlah = 0 maka 'habis'
        if (isset($data['data']['jumlah']) && $data['data']['jumlah'] > 0) {
            $data['data']['status'] = 'tersedia';
        } else {
            $data['data']['status'] = 'habis';
        }
        $data['data']['tanggal_masuk'] = date('Y-m-d'); // Set tanggal masuk otomatis
        
        return $data;
    }

    protected function updateCalculatedStatus(array $data)
    {
        if (isset($data['id'])) {
            $id = $data['id'][0];
            $bahan = $this->find($id);
            if ($bahan) {
                $currentStatus = get_bahan_baku_status($bahan['tanggal_kadaluarsa'], $bahan['jumlah']);
                if ($currentStatus !== $bahan['status']) {
                    $this->update($id, ['status' => $currentStatus], false); // update without triggering this callback
                }
            }
        }
        return $data;
    }

    protected function checkDeletionStatus(array $data)
    {
        $id = $data['id'][0] ?? null;
        if ($id) {
            $bahan = $this->find($id);
            // Refresh status just to be sure before checking deletion rule
            $currentStatus = get_bahan_baku_status($bahan['tanggal_kadaluarsa'], $bahan['jumlah']);
            if ($currentStatus !== 'kadaluarsa') {
                session()->setFlashdata('error', 'Penghapusan hanya diizinkan untuk bahan baku yang berstatus **kadaluarsa**.');
                return false;
            }
        }
        return $data;
    }
}