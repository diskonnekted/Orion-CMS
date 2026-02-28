<?php
/**
 * Plugin Name: Orion Desa Digital
 * Description: Sistem informasi manajemen desa terpadu. Meliputi dasbor kependudukan, perangkat desa, anggaran, peta, dan portal layanan warga online.
 * Version: 1.0.0
 * Author: Orion CMS
 */

// Initialize Database Tables (Simplified for MVP)
function orion_desa_install() {
    global $orion_db, $table_prefix;
    
    // Table: Penduduk
    $table_penduduk = $table_prefix . 'desa_penduduk';
    $orion_db->query("CREATE TABLE IF NOT EXISTS $table_penduduk (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nik VARCHAR(20) NOT NULL UNIQUE,
        nama VARCHAR(100) NOT NULL,
        jk ENUM('L', 'P'),
        alamat TEXT,
        rt VARCHAR(5),
        rw VARCHAR(5),
        status_warga VARCHAR(50) DEFAULT 'Aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Table: Layanan
    $table_layanan = $table_prefix . 'desa_layanan';
    $orion_db->query("CREATE TABLE IF NOT EXISTS $table_layanan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nik VARCHAR(20) NOT NULL,
        nama VARCHAR(100) NOT NULL,
        jenis_surat VARCHAR(100),
        keperluan TEXT,
        status VARCHAR(20) DEFAULT 'Pending',
        tanggal_pengajuan TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}
add_action('init', 'orion_desa_install'); // Run on init to ensure tables exist

// Public Routing for "Layanan Warga"
function orion_desa_public_route() {
    if (isset($_GET['desa_layanan'])) {
        require_once plugin_dir_path(__FILE__) . 'public-layanan.php';
        exit;
    }
}
add_action('init', 'orion_desa_public_route');
