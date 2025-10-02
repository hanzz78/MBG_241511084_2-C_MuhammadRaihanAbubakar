<?php
/**
 * Helper untuk menangani logika perhitungan status bahan baku (kedaluwarsa & stok).
 */

if ( ! function_exists('hitung_status_bahan'))
{
    /**
     * Menghitung status bahan baku berdasarkan tanggal dan jumlah stok.
     * @param string $tgl_kadaluarsa Tanggal kedaluwarsa (format Y-m-d).
     * @param int $jumlah Jumlah/stok bahan saat ini.
     * @return string Status bahan baku ('habis', 'kadaluarsa', 'segera_kadaluarsa', 'tersedia').
     */
    function hitung_status_bahan($tgl_kadaluarsa, $jumlah)
    {
        // 1. Cek HABIS (Prioritas tertinggi: stok 0 atau kurang)
        if ($jumlah <= 0) {
            return 'habis'; 
        }

        $hari_ini = date('Y-m-d');
        $tgl_kadaluarsa_ts = strtotime($tgl_kadaluarsa);
        $hari_ini_ts = strtotime($hari_ini);

        // Hitung selisih hari
        $selisih_detik = $tgl_kadaluarsa_ts - $hari_ini_ts;
        $selisih_hari = floor($selisih_detik / (60 * 60 * 24));
        
        // 2. Cek KADALUARSA (Jika tanggal sudah terlewat, stok > 0)
        if ($selisih_hari < 0) {
            return 'kadaluarsa';
        }
        
        // 3. Cek SEGERA KADALUARSA (H-3 atau kurang, stok > 0)
        // Ambang batas 3 hari (sesuai soal)
        if ($selisih_hari >= 0 && $selisih_hari <= 3) {
            return 'segera_kadaluarsa';
        }

        // 4. TERSEDIA (Stok > 0 dan Tanggal Kadaluarsa masih lama)
        return 'tersedia'; 
    }
}
