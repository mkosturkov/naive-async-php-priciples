<?php


$r1 = new parallel\Runtime(__DIR__ . '/shared.php');

$ch = new parallel\Channel();

$r1->run(function () use ($ch) {
    $cb = $ch->recv();
    $cb();
});

$ch->send(function () {
    echo "Executed from another thread!\n";
});