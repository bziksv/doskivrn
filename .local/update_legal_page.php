<?php
require __DIR__ . '/mysql.php';

$link = 'doskivrn_soglasie_pd';
$content = file_get_contents(__DIR__ . '/legal-pages/' . $link . '.html');

$mysqli = new mysqli('localhost', 'doskivrn_sajt', '&q%8&b9v', 'doskivrn_sajt');
if ($mysqli->connect_error) {
	die('connect: ' . $mysqli->connect_error);
}

$stmt = $mysqli->prepare('UPDATE Pages SET pageContent = ?, dataUpdate = ? WHERE link = ?');
$time = (string) time();
$stmt->bind_param('sss', $content, $time, $link);

if ($stmt->execute()) {
	echo 'OK updated rows=' . $stmt->affected_rows . PHP_EOL;
} else {
	echo 'ERR: ' . $stmt->error . PHP_EOL;
}
