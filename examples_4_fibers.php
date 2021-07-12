<?php

require __DIR__ . '/Loop.php';
require __DIR__ . '/Promise.php';
require __DIR__ . '/coroutine.php';
require __DIR__ . '/time.php';
require __DIR__ . '/fetch.php';

defer(function () {
    echo "Fetching dir.bg\n";
    try {
        $dirbg = ffetchUrl('www.dir.bg');
        echo "Got dir.bg\n";
        echo $dirbg;
    } catch (Exception $e) {
        echo "Got error from dir.bg\n";
        echo $e->getMessage() . "\n";
    }
});

defer(function () {
    echo "Fetching google.com\n";
    try {
        $google = ffetchUrl('www.google.com');
        echo "Got Google\n";
        echo "Size is: " . strlen($google) . "\n";
    } catch (Exception $e) {
        echo "Got error from google\n";
        echo $e->getMessage() . "\n";
    }
});

Loop::run();
