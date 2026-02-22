<?php
/*
Plugin Name: Orion Doa Harian
Description: Menampilkan kumpulan doa harian menggunakan API open-api.my.id.
Version: 1.0
Author: Orion AI
*/

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('ORION_DOA_API_BASE')) {
    define('ORION_DOA_API_BASE', 'https://open-api.my.id/api/');
}

function orion_doa_api_request($path)
{
    $url = ORION_DOA_API_BASE . ltrim($path, '/');
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10,
            'ignore_errors' => true,
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        return null;
    }
    $data = json_decode($response, true);
    if (!is_array($data)) {
        return null;
    }
    return $data;
}

function orion_doa_get_all()
{
    $data = orion_doa_api_request('doa');
    if (!is_array($data)) {
        return [];
    }
    $items = [];
    foreach ($data as $row) {
        if (!isset($row['id'], $row['judul'])) {
            continue;
        }
        $items[] = [
            'id' => (int)$row['id'],
            'judul' => $row['judul'],
            'latin' => isset($row['latin']) ? $row['latin'] : '',
            'arab' => isset($row['arab']) ? $row['arab'] : '',
            'terjemah' => isset($row['terjemah']) ? $row['terjemah'] : '',
        ];
    }
    return $items;
}

function orion_doa_get_by_id($id)
{
    $all = orion_doa_get_all();
    foreach ($all as $item) {
        if ($item['id'] === (int)$id) {
            return $item;
        }
    }
    return null;
}

