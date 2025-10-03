<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'role', 'created_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // Validation (min_length disarankan minimal 5)
    protected $validationRules = [
        'email'    => 'required|valid_email|is_unique[user.email,id,{id}]',
        'password' => 'required|min_length[3]',
        'name'     => 'required',
        'role'     => 'required|in_list[gudang,dapur]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['md5Password']; // Menggunakan MD5
    protected $beforeUpdate   = ['md5Password']; // Menggunakan MD5

    /**
     * Mengubah password input menjadi hash MD5
     */
    protected function md5Password(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = md5($data['data']['password']); 
        }

        return $data;
    }
}