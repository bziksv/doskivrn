<?php
require __DIR__ . '/mysql.php';

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$path = __DIR__ . '/..' . $uri;

if ($uri !== '/' && is_file($path)) {
    return false;
}

if ($uri === '/' || $uri === '') {
    $_GET['module'] = 'pages';
    $_GET['page'] = 'index';
    require __DIR__ . '/../index.php';
    return true;
}

if (preg_match('#^/search/?$#', $uri)) {
    chdir(__DIR__ . '/../search');
    require __DIR__ . '/../search/index.php';
    return true;
}

if (preg_match('#^/admin/?$#', $uri)) {
    require __DIR__ . '/../admin/index.php';
    return true;
}

if (preg_match('#^/([a-zA-Z0-9_\s\-]{1,100})/?$#', $uri, $m)) {
    $_GET['module'] = $m[1];
    require __DIR__ . '/../index.php';
    return true;
}

if (preg_match('#^/([a-zA-Z0-9_\s\-]{1,100})/([a-zA-Z0-9_\s\-]{1,100})/?$#', $uri, $m)) {
    $_GET['module'] = $m[1];
    $_GET['page'] = $m[2];
    require __DIR__ . '/../index.php';
    return true;
}

if (preg_match('#^/([a-zA-Z0-9_\s\-]{1,100})/([a-zA-Z0-9_\s\-]{1,100})/([a-zA-Z0-9_\s\-]{1,100})/?$#', $uri, $m)) {
    $_GET['module'] = $m[1];
    $_GET['page'] = $m[2];
    $_GET['action'] = $m[3];
    require __DIR__ . '/../index.php';
    return true;
}

if (preg_match('#^/([a-zA-Z0-9_\s\-]{1,100})/([a-zA-Z0-9_\s\-]{1,100})/([a-zA-Z0-9_\s\-]{1,100})/([a-zA-Z0-9_\s\-]{1,100})/?$#', $uri, $m)) {
    $_GET['module'] = $m[1];
    $_GET['page'] = $m[2];
    $_GET['action'] = $m[3];
    $_GET['subaction'] = $m[4];
    require __DIR__ . '/../index.php';
    return true;
}

http_response_code(404);
require __DIR__ . '/../index.php';
return true;
