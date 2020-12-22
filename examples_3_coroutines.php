<?php

require './Loop.php';
require './Promise.php';
require './coroutine.php';
require './time.php';
require './fetch.php';

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
