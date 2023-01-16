<?php

$path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'];

if (is_file($path) || is_dir($path)) {
    return false;
} else {
    return require __DIR__ . '/index.php';
}
