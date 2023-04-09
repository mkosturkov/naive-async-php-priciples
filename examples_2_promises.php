<?php

require './Loop.php';
require './Promise.php';
require './time.php';
require './fetch.php';

pfetchUrl('www.google.com')
    ->then(function ($content) {
        echo "Got google with promise\n";
        echo strlen($content) . "\n";
    })
    ->then(fn () => wait(1))
    ->then(function () {
        echo "And got this chained\n";
    });

pfetchUrl('invalid')
    ->error(function ($err) {
        echo "Got promised error: $err\n";
    });

wait(1)->then(function () {
    echo "Timer is done\n";
});

Loop::run();
