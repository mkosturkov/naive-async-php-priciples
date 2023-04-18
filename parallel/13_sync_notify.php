<?php

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');
$r2 = new parallel\Runtime(__DIR__ . '/shared.php');
$r3 = new parallel\Runtime(__DIR__ . '/shared.php');

$sync = new parallel\Sync();

$r1->run(function () use ($sync) {
    echo "R1 waiting for sync...\n";
    $sync(function () use ($sync) {
        $sync->wait();
    });
    echo "R1 got notified\n";
});
$r2->run(function () use ($sync) {
    echo "R2 waiting for sync...\n";
    $sync(function () use ($sync) {
        $sync->wait();
    });
    echo "R2 got notified\n";
});
$r3->run(function () use ($sync) {
    echo "R3 waiting for sync...\n";
    $sync(function () use ($sync) {
        $sync->wait();
    });
    echo "R3 got notified\n";
});

sleep(2);
echo "Notifying one\n";
$sync(fn () => $sync->notify());

sleep(1);
echo "Notifying all\n";
$sync(fn () => $sync->notify(true));


