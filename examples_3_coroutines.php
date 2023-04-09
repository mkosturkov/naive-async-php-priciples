<?php

require __DIR__ . '/Loop.php';
require __DIR__ . '/Promise.php';
require __DIR__ . '/coroutine.php';
require __DIR__ . '/time.php';
require __DIR__ . '/fetch.php';

function getDirBG (): Generator {
    echo "Getting dir\n";
    $dir = yield pfetchUrl('www.dir.bg');
    echo $dir;
    yield wait(2);
    echo "Got dir\n";
    return $dir;
};

goco(function () {
    echo "Getting google\n";
    $g = yield pfetchUrl('google.com');
    echo "Got google\n";
    $d = yield getDirBG();
    echo strlen($g) . "\n";
    echo strlen($d) . "\n";
    yield wait(5);
    echo yield goco(fn () => "This is from a normal func\n");
    echo yield "This is a static value\n";
    echo "GDone\n";
});


Loop::run();
