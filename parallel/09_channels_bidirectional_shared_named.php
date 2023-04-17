<?php

include __DIR__ . '/shared.php';

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');
$r2 = new parallel\Runtime(__DIR__ . '/shared.php');

$ch = parallel\Channel::make(CHANNEL_NAME);

$r1->run(function () {
    count_to_chan_named('R1');
});
$r2->run(function () {
    count_to_chan_named('R2');
});

$ch->send(5);
$ch->send(7);

$ends_received = 0;
do {
    [$id, $message] = $ch->recv();
    echo "Received from $id: $message\n";
    if ($message === 'END') {
        $ends_received++;
    }
} while ($ends_received < 2);

