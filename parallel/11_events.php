<?php

$threads_count = 10;

$send = parallel\Channel::make('send', parallel\Channel::Infinite);
$input = new parallel\Events\Input();
$events = new parallel\Events();
$events->setInput($input);

$rs = [];

for ($i = 1; $i <= $threads_count; $i++) {
    $recv = new parallel\Channel(parallel\Channel::Infinite);
    $events->addChannel($recv);
    $send->send(mt_rand(5, 15));
    $r = new parallel\Runtime(__DIR__ . '/shared.php');
    $r->run(function () use ($send, $recv, $i) {
        count_to_chan($send, $recv, $i);
    });
    $rs[] = $r;
}

/** @var parallel\Events\Event $e */
foreach ($events as $e) {
    [$id, $message] = $e->value;
    echo "Got message from $id: $message\n";
    if ($message !== 'END') {
        $events->addChannel($e->object);
    }
}
