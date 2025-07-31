<?php
//add_action('init', function () {
//    if (is_admin()) return;
//
//    $request_uri = $_SERVER['REQUEST_URI'];
//    $path = parse_url($request_uri, PHP_URL_PATH);
//    $segments = explode('/', trim($path, '/'));
//
//    $available = acfai_get_available_lang_codes();
//    $default = acfai_get_default_lang_code();
//
//    $lang = $segments[0] ?? null;
//
//    if (in_array($lang, $available)) {
//        $GLOBALS['acfai_current_lang'] = $lang;
//    } else {
//        $GLOBALS['acfai_current_lang'] = $default;
//    }
//});
//
//add_filter('request', function ($query_vars) {
//    if (is_admin()) return $query_vars;
//
//    $request_uri = $_SERVER['REQUEST_URI'];
//    $path = parse_url($request_uri, PHP_URL_PATH);
//    $segments = explode('/', trim($path, '/'));
//
//    $available = acfai_get_available_lang_codes();
//    $lang = $segments[0] ?? null;
//    if (in_array($lang, $available)) {
//        if (count($segments) === 1 || $segments[1] === '') {
//            unset($query_vars['pagename']);
//            unset($query_vars['name']);
//
//        } else {
//            $query_vars['pagename'] = implode('/', array_slice($segments, 1));
//        }
//    }
//    return $query_vars;
//});
//
//
//add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
//    $path = parse_url($requested_url, PHP_URL_PATH);
//    $segments = explode('/', trim($path, '/'));
//
//    $available = acfai_get_available_lang_codes();
//    $lang = $segments[0] ?? null;
//
//    if (in_array($lang, $available) && count($segments) === 1) {
//        return false;
//    }
//
//    return $redirect_url;
//}, 10, 2);