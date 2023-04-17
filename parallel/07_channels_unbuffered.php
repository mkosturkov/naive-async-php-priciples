<?php

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');
$r2 = new parallel\Runtime(__DIR__ . '/shared.php');

$ch = new parallel\Channel();

foreach ([$r1, $r2] as $idx => $r) {
    $r->run(function () use ($idx, $ch) {
        $id = $idx + 1;
        usleep(mt_rand(500_000, 1_000_000));
        $message = $ch->recv();
        echo "In R$id got message: $message\n";
    });
}

echo "Sending message 1\n";
$ch->send('m1');
echo "Sending message 2\n";
$ch->send('m2');

echo "Sending message 3\n";
$ch->send('m3');
echo "We will never get here\n";