<?php

include __DIR__ . '/vendor/autoload.php';
$now = microtime(true);
Core\App::run();
error_log('exec ' . (microtime(true) - $now)*1000 . "ms");