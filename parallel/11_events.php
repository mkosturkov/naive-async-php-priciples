<?php

$threads_count = 3;

$chan = [];

for ($i = 1; $i <= $threads_count; $i++) {
    echo "$i\n";
    echo "Creating CH\n";
    $ch = new parallel\Channel(parallel\Channel::Infinite);
    echo "Creating RT\n";
    $r = new parallel\Runtime(__DIR__ . '/shared.php');
    echo "Adding Task\n";
    $r->run(function () use ($ch, $i) {
        count_to_chan($ch, $i);
    });
    echo "Sending\n";
    $ch->send(mt_rand(5, 15));
    echo "Adding CH\n";
    $chan[] = $ch;
}

$events = new parallel\Events();

echo "Looping\n";

/** @var parallel\Events\Event $e */
foreach ($events as $e) {
    [$id, $message] = $e->value;
    if ($message === 'END') {
        echo "$id finished\n";
    } else {
        echo "Message from $id: $message\n";
        $events->addChannel($e->object);
    }
}