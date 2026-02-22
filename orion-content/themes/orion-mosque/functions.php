<?php

function orion_mosque_get_prayer_times($city = 'Jakarta', $country = 'Indonesia', $method = 20)
{
    $url = 'https://api.aladhan.com/v1/timingsByCity?city=' . urlencode($city) .
        '&country=' . urlencode($country) .
        '&method=' . intval($method);

    $response = @file_get_contents($url);
    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);
    if (!is_array($data) || !isset($data['code']) || $data['code'] !== 200 || !isset($data['data']['timings'])) {
        return null;
    }

    $t = $data['data']['timings'];

    return [
        'Subuh' => isset($t['Fajr']) ? substr($t['Fajr'], 0, 5) : null,
        'Dzuhur' => isset($t['Dhuhr']) ? substr($t['Dhuhr'], 0, 5) : null,
        'Ashar' => isset($t['Asr']) ? substr($t['Asr'], 0, 5) : null,
        'Maghrib' => isset($t['Maghrib']) ? substr($t['Maghrib'], 0, 5) : null,
        'Isya' => isset($t['Isha']) ? substr($t['Isha'], 0, 5) : null,
    ];
}

function orion_mosque_quran_get_metadata()
{
    static $meta = null;
    if ($meta !== null) {
        return $meta;
    }
    $file = ABSPATH . 'orion-content/plugins/quran-text-multilanguage/inc/quran/data.xml';
    if (!file_exists($file)) {
        $meta = [];
        return $meta;
    }
    $xml = @simplexml_load_file($file);
    if ($xml === false || !isset($xml->suras->sura)) {
        $meta = [];
        return $meta;
    }
    $meta = [];
    foreach ($xml->suras->sura as $sura) {
        $index = isset($sura['index']) ? (int)$sura['index'] : 0;
        if ($index < 1 || $index > 114) {
            continue;
        }
        $meta[$index] = [
            'start' => isset($sura['start']) ? (int)$sura['start'] : 0,
            'ayas' => isset($sura['ayas']) ? (int)$sura['ayas'] : 0,
            'tname' => isset($sura['tname']) ? (string)$sura['tname'] : '',
        ];
    }
    return $meta;
}

function orion_mosque_quran_get_surah_list()
{
    $meta = orion_mosque_quran_get_metadata();
    $list = [];
    foreach ($meta as $index => $info) {
        $label = $index . '. ' . $info['tname'];
        $list[] = [
            'index' => $index,
            'label' => $label,
        ];
    }
    return $list;
}

function orion_mosque_quran_get_surah($sura, $lang = 'indonesian')
{
    $meta = orion_mosque_quran_get_metadata();
    $sura = (int)$sura;
    if ($sura < 1) {
        $sura = 1;
    }
    if ($sura > 114) {
        $sura = 114;
    }
    if (!isset($meta[$sura])) {
        return null;
    }
    $arabic_file = ABSPATH . 'orion-content/plugins/quran-text-multilanguage/inc/quran/arabe.txt';
    $trans_file = ABSPATH . 'orion-content/plugins/quran-text-multilanguage/inc/quran/' . $lang . '.txt';
    if (!file_exists($arabic_file) || !file_exists($trans_file)) {
        return null;
    }
    $arabic_lines = @file($arabic_file, FILE_IGNORE_NEW_LINES);
    $trans_lines = @file($trans_file, FILE_IGNORE_NEW_LINES);
    if ($arabic_lines === false || $trans_lines === false) {
        return null;
    }
    $start = $meta[$sura]['start'];
    $count = $meta[$sura]['ayas'];
    if ($count <= 0) {
        return null;
    }
    $arabic_slice = array_slice($arabic_lines, $start, $count);
    $trans_slice = array_slice($trans_lines, $start, $count);
    return [
        'index' => $sura,
        'name' => $meta[$sura]['tname'],
        'arabic' => $arabic_slice,
        'translation' => $trans_slice,
    ];
}
