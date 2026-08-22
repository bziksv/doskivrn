<?php
/**
 * Создаёт/обновляет юридические страницы в таблице Pages (контент не в git как файлы, а в БД).
 * На проде после git pull: php scripts/install-legal-pages.php
 */
$root = dirname(__DIR__);
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = $root;
}

$cfgFile = $root . '/configure.php';
if (!is_readable($cfgFile)) {
    fwrite(STDERR, "Missing configure.php\n");
    exit(1);
}
$cfg = file_get_contents($cfgFile);
if (!preg_match('/\$dbhost\s*=\s*"([^"]*)"/', $cfg, $m)) {
    fwrite(STDERR, "Cannot parse dbhost from configure.php\n");
    exit(1);
}
$dbhost = $m[1];
preg_match('/\$dbuser\s*=\s*"([^"]*)"/', $cfg, $m) && $dbuser = $m[1];
preg_match('/\$dbpass\s*=\s*"([^"]*)"/', $cfg, $m) && $dbpass = $m[1];
preg_match('/\$dbname\s*=\s*"([^"]*)"/', $cfg, $m) && $dbname = $m[1];
if (empty($dbuser) || empty($dbname)) {
    fwrite(STDERR, "Cannot parse DB credentials from configure.php\n");
    exit(1);
}

$legalDir = $root . '/.local/legal-pages/';
if (!is_dir($legalDir)) {
    fwrite(STDERR, "Missing directory: $legalDir\n");
    exit(1);
}

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_error) {
    fwrite(STDERR, 'DB connect: ' . $mysqli->connect_error . "\n");
    exit(1);
}
$mysqli->set_charset('utf8');

$pages = array(
    'doskivrn_politika_cookie' => array(
        'name' => 'Политика использования cookie-файлов',
        'title' => 'Политика использования cookie-файлов — компания Велес',
        'description' => 'Политика использования cookie-файлов на сайте doskivrn.ru',
    ),
    'doskivrn_politika_pd' => array(
        'name' => 'Политика обработки персональных данных',
        'title' => 'Политика обработки персональных данных — компания Велес',
        'description' => 'Политика обработки персональных данных на сайте doskivrn.ru',
    ),
    'doskivrn_soglasie_pd' => array(
        'name' => 'Согласие на обработку персональных данных',
        'title' => 'Согласие на обработку персональных данных — компания Велес',
        'description' => 'Согласие на обработку персональных данных на сайте doskivrn.ru',
    ),
    'doskivrn_rekomendacii' => array(
        'name' => 'Правила применения рекомендательных технологий',
        'title' => 'Правила применения рекомендательных технологий — компания Велес',
        'description' => 'Правила применения рекомендательных технологий на сайте doskivrn.ru',
    ),
);

$sql = 'INSERT INTO Pages (name, link, `system`, adminAdd, adminUpdate, dataAdd, dataUpdate, pageTitle, pageDescription, pageKeywords, pageContent, _string_ico)
VALUES (?, ?, 0, \'1\', \'1\', ?, ?, ?, ?, \'\', ?, \'\')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    adminUpdate = VALUES(adminUpdate),
    dataUpdate = VALUES(dataUpdate),
    pageTitle = VALUES(pageTitle),
    pageDescription = VALUES(pageDescription),
    pageContent = VALUES(pageContent)';

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    fwrite(STDERR, 'prepare: ' . $mysqli->error . "\n");
    exit(1);
}

$time = (string) time();
$ok = 0;

foreach ($pages as $link => $meta) {
    $file = $legalDir . $link . '.html';
    if (!is_readable($file)) {
        fwrite(STDERR, "SKIP missing file: $file\n");
        continue;
    }
    $content = file_get_contents($file);
    $stmt->bind_param(
        'sssssss',
        $meta['name'],
        $link,
        $time,
        $time,
        $meta['title'],
        $meta['description'],
        $content
    );
    if (!$stmt->execute()) {
        fwrite(STDERR, "FAIL $link: " . $stmt->error . "\n");
        continue;
    }
    echo "OK $link len=" . strlen($content) . " rows=" . $stmt->affected_rows . "\n";
    $ok++;
}

echo "done: $ok/" . count($pages) . "\n";
exit($ok === count($pages) ? 0 : 1);
