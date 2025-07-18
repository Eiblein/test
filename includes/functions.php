<?php
function t($key) {
    static $lang = null;
    if ($lang === null) {
        $locale = $_SESSION['lang'] ?? null;

        if ($locale === null && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        if (!in_array($locale, ['de', 'en'])) {
            $locale = 'de';
        }

        $file = __DIR__ . '/../lang/' . $locale . '.php';
        $lang = file_exists($file) ? include $file : include __DIR__ . '/../lang/de.php';
    }

    return $lang[$key] ?? $key;
}

