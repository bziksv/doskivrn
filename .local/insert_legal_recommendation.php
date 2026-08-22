<?php
require '/Users/stanislav/Documents/projects/doskivrn/.local/mysql.php';
$mysqli = new mysqli('localhost', 'doskivrn_sajt', '&q%8&b9v', 'doskivrn_sajt');
if ($mysqli->connect_error) {
    die('connect: ' . $mysqli->connect_error);
}
$content = file_get_contents(__DIR__ . '/legal-pages/doskivrn_rekomendacii.html');
$stmt = $mysqli->prepare('INSERT INTO Pages (name, link, `system`, adminAdd, adminUpdate, dataAdd, dataUpdate, pageTitle, pageDescription, pageKeywords, pageContent, _string_ico) VALUES (?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$name = 'Правила применения рекомендательных технологий';
$link = 'doskivrn_rekomendacii';
$admin = '1';
$time = (string) time();
$title = 'Правила применения рекомендательных технологий — компания Велес';
$desc = 'Правила применения рекомендательных технологий на сайте doskivrn.ru';
$keywords = '';
$ico = '';
$stmt->bind_param('sssssssssss', $name, $link, $admin, $admin, $time, $time, $title, $desc, $keywords, $content, $ico);
if ($stmt->execute()) {
    echo 'OK id=' . $mysqli->insert_id . PHP_EOL;
} else {
    echo 'ERR: ' . $stmt->error . PHP_EOL;
}
