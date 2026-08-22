<?php
/**
 * Recompile all Smarty templates (fixes empty templates_c after PHP 7+ compile failure).
 * Usage: php scripts/recompile-templates.php
 */
$root = dirname(__DIR__);
require $root . '/classes/smarty/Smarty.class.php';

$smarty = new Smarty();
$smarty->template_dir = $root . '/templates/default/';
$smarty->compile_dir = $root . '/templates_c/';
$smarty->compile_check = true;
$smarty->force_compile = true;

$files = glob($smarty->template_dir . '*.tpl');
$additional = glob($smarty->template_dir . 'additional/*.tpl');
$all = array_merge($files ?: [], $additional ?: []);

foreach ($all as $path) {
    $name = basename($path);
    if ($name === 'additional') {
        continue;
    }
    $rel = str_replace($smarty->template_dir, '', $path);
    try {
        $smarty->fetch($rel);
        echo "OK: $rel\n";
    } catch (Throwable $e) {
        echo "FAIL: $rel — " . $e->getMessage() . "\n";
    }
}
