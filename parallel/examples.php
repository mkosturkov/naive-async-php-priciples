<?php

include __DIR__ . '/shared_functions.php';

$r1 = new parallel\Runtime(__DIR__ . '/shared_functions.php');
$r2 = new parallel\Runtime(__DIR__ . '/shared_functions.php');
$r3 = new parallel\Runtime(__DIR__ . '/shared_functions.php');

$sync = new \parallel\Sync(0);


for ($i = 0; $i < 100; $i++) {
    $f1 = $r1->run(function () use ($sync) {
        $sync(function () use ($sync) {
            $sync->set($sync->get() + 1);
        });
    });

    $f2 = $r2->run(function () use ($sync) {
        $sync(function () use ($sync) {
            $sync->set($sync->get() + 1);
        });;
    });

    $f3 = $r3->run(function () use ($sync) {
        $sync(function () use ($sync) {
            $sync->set($sync->get() + 1);
        });;;
    });

    $f1->value();
    $f2->value();
    $f3->value();

    $v = $sync->get();
    if ($v !== 3) {
        echo "Iteration $i: $v\n";
    }
    $sync->set(0);
}

$r1->close();
$r2->close();
$r3->close();

//var_dump($sync->get());