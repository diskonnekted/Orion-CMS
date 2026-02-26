<?php
/*
Plugin Name: Donorbox Donation Form (Orion)
Description: Integrasi Donorbox untuk Orion CMS dengan shortcode [donate] dan [donate-with-info].
Version: 1.0
Author: Orion Dev
*/

if (!defined('ABSPATH')) {
    $bootstrap_path = dirname(dirname(dirname(__DIR__))) . '/orion-load.php';
    if (file_exists($bootstrap_path)) {
        require_once $bootstrap_path;
    } else {
        die('Orion CMS Core not found.');
    }
}

function orion_donorbox_get_options() {
    $options = get_option('donorbox_embed_campaign_options');
    if (!is_array($options)) {
        $options = array();
    }
    if (!isset($options['donorbox_embed_campaign_id'])) {
        $options['donorbox_embed_campaign_id'] = '';
    }
    return $options;
}

function orion_donorbox_set_campaign_url($url) {
    $options = orion_donorbox_get_options();
    $options['donorbox_embed_campaign_id'] = trim($url);
    update_option('donorbox_embed_campaign_options', $options);
}

function orion_donorbox_generate_iframe($with_info = false, $override_url = '') {
    $donorbox_domain = 'https://donorbox.org';
    $options = orion_donorbox_get_options();
    $input = isset($options['donorbox_embed_campaign_id']) ? $options['donorbox_embed_campaign_id'] : '';

    if ($override_url !== '') {
        $input = $override_url;
    }

    $input = trim($input);
    if ($input === '') {
        return '<div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-4">Donorbox campaign URL belum dikonfigurasi.</div>';
    }

    $campaign_keys = parse_url($input);
    if (!is_array($campaign_keys) || !isset($campaign_keys['path'])) {
        return '<div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-4">Donorbox campaign URL tidak valid.</div>';
    }

    $path = explode('/', $campaign_keys['path']);
    $campaign_id = end($path);
    $style = 'style="max-width:500px; min-width:310px;"';
    if (empty($campaign_id)) {
        $campaign_id = prev($path);
    }

    $pars = array();
    if ($with_info) {
        $pars[] = 'show_content=true';
        $style = 'style="max-width:100%; min-width:100%;"';
    }
    if (isset($campaign_keys['query']) && $campaign_keys['query']) {
        $pars[] = $campaign_keys['query'];
    }
    if (!empty($pars)) {
        $campaign_id .= '?' . implode('&', $pars);
    }

    $campaign_id = htmlspecialchars($campaign_id, ENT_QUOTES, 'UTF-8');

    $iframe = '<script src="https://donorbox.org/widget.js" type="text/javascript"></script>';
    $iframe .= '<iframe src="' . $donorbox_domain . '/embed/' . $campaign_id . '" width="100%" ' . $style . ' seamless="seamless" id="dbox-form-embed" name="donorbox" frameborder="0" scrolling="no" allowpaymentrequest></iframe>';

    return $iframe;
}

function orion_donorbox_parse_atts($text) {
    $atts = array();
    $text = trim($text);
    if ($text === '') {
        return $atts;
    }
    if (preg_match_all('/(\w+)\s*=\s*"([^"]*)"/', $text, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $atts[strtolower($m[1])] = $m[2];
        }
    }
    return $atts;
}

function orion_donorbox_filter_content($content) {
    if (strpos($content, '[donate') === false && strpos($content, '[donate-with-info') === false) {
        return $content;
    }

    $callback = function ($matches) {
        $tag = strtolower($matches[1]);
        $attr_text = isset($matches[2]) ? $matches[2] : '';
        $atts = orion_donorbox_parse_atts($attr_text);
        $override_url = isset($atts['url']) ? $atts['url'] : '';
        $with_info = ($tag === 'donate-with-info');
        return orion_donorbox_generate_iframe($with_info, $override_url);
    };

    $pattern = '/\[(donate|donate-with-info)([^\]]*)\]/i';

    return preg_replace_callback($pattern, $callback, $content);
}

if (function_exists('add_filter')) {
    add_filter('the_content', 'orion_donorbox_filter_content', 10, 1);
}

