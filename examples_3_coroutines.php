<?php

require __DIR__ . '/Loop.php';
require __DIR__ . '/Promise.php';
require __DIR__ . '/coroutine.php';
require __DIR__ . '/time.php';
require __DIR__ . '/fetch.php';

$dir = function () {
    echo "Getting dir\n";
    $dir = yield pfetchUrl('www.dir.bg');
    echo $dir;
    yield wait(2);
    echo "Got dir\n";
    return strlen($dir);
};


//goco(function () {
//    $dir = yield pfetchUrl('www.dir.bg');
//    echo $dir;
//    yield wait(2);
//    echo "Done\n";
//});

goco(function () use ($dir) {
    echo "Getting google\n";
    $g = yield pfetchUrl('google.com');
    echo "Got google\n";
    $d = yield goco($dir());
    echo strlen($g) . "\n";
    echo strlen($d) . "\n";
    yield wait(5);
    echo "GDone\n";
});


Loop::run();
