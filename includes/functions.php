<?php
<?php
function t($key) {
    static $lang = null;
    if ($lang === null) {
        $locale = $_SESSION['lang'] ?? 'de';
        $file = __DIR__ . '/../lang/' . $locale . '.php';
        $lang = file_exists($file) ? include $file : include __DIR__ . '/../lang/de.php';
    }
    return $lang[$key] ?? $key;
}