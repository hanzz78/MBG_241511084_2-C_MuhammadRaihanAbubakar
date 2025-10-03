<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * 
 * 
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */



// ... (existing content)

/**
 * Menghitung status bahan baku berdasarkan tanggal kadaluarsa dan stok.
 * Fungsi ini digunakan untuk tampilan real-time di view.
 *
 * @param string $tanggalKadaluarsa
 * @param int $jumlahStok
 * @return string
 */
function get_bahan_baku_status(string $tanggalKadaluarsa, int $jumlahStok): string
{
    $hariIni = date('Y-m-d');
    $tigaHariLagi = date('Y-m-d', strtotime('+3 days'));

    if ($jumlahStok <= 0) {
        return 'habis';
    }
    
    if ($hariIni > $tanggalKadaluarsa) {
        return 'kadaluarsa';
    }
    
    if ($tanggalKadaluarsa <= $tigaHariLagi) {
        return 'segera_kadaluarsa';
    }
    
    return 'tersedia';
}

