<?php

require './Loop.php';
require './Promise.php';
require './time.php';
require './fetch.php';

pfetchUrl('www.google.com')
    ->then(function ($content) {
        echo "Got google with promise\n";
        echo strlen($content) . "\n";
        return strlen($content) % 2 + 1;
    })
    ->then(function (int $waitInterval) {
        echo "Waiting for $waitInterval seconds\n";
        return wait($waitInterval);
    })
    ->then(function () {
        echo "Finished google chain\n";
    });

pfetchUrl('invalid')
    ->catch(function ($err) {
        echo "Caught promised error: $err\n";
    });

(new Promise(fn () => throw new \Exception('Throwing from init')))
    ->catch(function (Exception $e) {
        echo "Caught from init: ";
        echo $e->getMessage() ."\n";
    });

(new Promise(fn ($r) => $r()))
    ->then(fn () => throw new \Exception('Throwing from handler'))
    ->catch(function (\Exception $e) {
        echo 'Caught from handler: ' . $e->getMessage() . "\n";
    });

wait(1)->then(function () {
    echo "Parallel timer is done\n";
});

Loop::run();
