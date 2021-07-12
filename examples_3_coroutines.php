<?php

require __DIR__ . '/Loop.php';
require __DIR__ . '/Promise.php';
require __DIR__ . '/coroutine.php';
require __DIR__ . '/time.php';
require __DIR__ . '/fetch.php';

goco(function () {
    $dir = yield pfetchUrl('www.dir.bg');
    echo $dir;
    yield wait(2);
    echo "Done\n";
});

goco(function () {
    $g = yield pfetchUrl('google.com');
    echo strlen($g) . "\n";
    yield wait(5);
    echo "GDone\n";
});


Loop::run();
