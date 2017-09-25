<?php
// use default composer autoload
if (file_exists($filename = __DIR__ . '/../vendor/autoload.php')) {
    require_once $filename;
} else {
    error_log('Composer deps not installed, run composer install!');
}

echo "It works!";
