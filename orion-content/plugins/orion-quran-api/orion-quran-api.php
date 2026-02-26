<?php
/*
Plugin Name: Orion Quran API
Description: Integrasi Al-Qur'an menggunakan Quran API dari SantriKoding.
Version: 1.0
Author: Orion AI
*/

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('ORION_QURAN_API_BASE')) {
    define('ORION_QURAN_API_BASE', 'https://quran-api.santrikoding.com/api/');
}

function orion_quran_api_request($path)
{
    $url = ORION_QURAN_API_BASE . ltrim($path, '/');
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

function orion_quran_get_surah_list()
{
    $data = orion_quran_api_request('surah');
    if (!is_array($data)) {
        return [];
    }
    $list = [];
    foreach ($data as $item) {
        if (!isset($item['nomor'], $item['nama_latin'])) {
            continue;
        }
        $index = (int)$item['nomor'];
        $label = $index . '. ' . $item['nama_latin'];
        $list[] = [
            'index' => $index,
            'label' => $label,
            'name_latin' => $item['nama_latin'],
            'name_ar' => isset($item['nama']) ? $item['nama'] : '',
            'arti' => isset($item['arti']) ? $item['arti'] : '',
            'audio' => isset($item['audio']) ? $item['audio'] : '',
        ];
    }
    return $list;
}

function orion_quran_get_surah($sura)
{
    $sura = (int)$sura;
    if ($sura < 1) {
        $sura = 1;
    }
    if ($sura > 114) {
        $sura = 114;
    }
    $data = orion_quran_api_request('surah/' . $sura);
    if (!is_array($data) || !isset($data['status']) || !$data['status']) {
        return null;
    }
    if (!isset($data['ayat']) || !is_array($data['ayat'])) {
        return null;
    }
    $arabic = [];
    $translation = [];
    foreach ($data['ayat'] as $ayat) {
        $arabic[] = isset($ayat['ar']) ? $ayat['ar'] : '';
        $translation[] = isset($ayat['idn']) ? $ayat['idn'] : '';
    }
    return [
        'index' => isset($data['nomor']) ? (int)$data['nomor'] : $sura,
        'name' => isset($data['nama_latin']) ? $data['nama_latin'] : '',
        'arabic' => $arabic,
        'translation' => $translation,
        'audio' => isset($data['audio']) ? $data['audio'] : '',
        'arti' => isset($data['arti']) ? $data['arti'] : '',
    ];
}

