<?php

require './Loop.php';
require './Promise.php';
require './time.php';
require './fetch.php';

setInterval(function () {
    echo "tick\n";
}, 0.5);

setTimeout(function () {
    echo "Hello, world!\n";
}, 1.5);


setTimeout(function () {
    Loop::stop();
}, 5);


fetchUrl(
    'www.google.com',
    function ($content) {
        echo "Got google\n". strlen($content) . "\n";
    },
    function (string $error) {
        echo "Got error from google: $error\n";
    }
);

fetchUrl(
    'www.dir.bg',
    function ($content) {
        echo "Got dir.bg\n";
        echo "$content\n";
    },
    function (string $error) {
        echo "Got error from dir.bg: $error\n";
    }
);

fetchUrl('invalid', function () {}, function ($err) {
    echo "Invalid error: $err\n";
});

Loop::run();
