<?php

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');
$r2 = new parallel\Runtime(__DIR__ . '/shared.php');
$r3 = new parallel\Runtime(__DIR__ . '/shared.php');

$sync = new parallel\Sync(0);

$fs = [];

$fs[] = $r1->run(function () use ($sync) {
    $sync(function () use ($sync) {
        $sync->set($sync->get() + 1);
    });
});

$fs[] = $r2->run(function () use ($sync) {
    $sync(function () use ($sync) {
        $sync->set($sync->get() + 1);
    });
});

$fs[] = $r3->run(function () use ($sync) {
    $sync(function () use ($sync) {
        $sync->set($sync->get() + 1);
    });
});

foreach ($fs as $f) {
    $f->value();
}

var_dump($sync->get());


