<?php

class SomeClass {}

const CHANNEL_NAME = 'the-channel';

function count_to(string $id, int $max): float
{
    $total_time = 0;
    for ($i = 1; $i <= $max; $i++) {
        $sleep_time = mt_rand(200_000, 500_000);
        usleep($sleep_time);
        $total_time += $sleep_time;
        echo "$id: $i\n";
    }
    return $total_time / 1_000_000;
}

function count_to_chan(parallel\Channel $in, parallel\Channel $out, string $id) {
    $total_time = 0;
    echo "Waiting to get max...\n";
    $max = $in->recv();
    echo "$id got max: $max\n";
    usleep(200);
    for ($i = 1; $i <= $max; $i++) {
        $sleep_time = mt_rand(200_000, 500_000);
        usleep($sleep_time);
        $total_time += $sleep_time;
        $out->send([$id, $total_time / 1_000_000]);
    }
    $out->send([$id, 'END']);
}

function count_to_chan_named(string $id) {
    $ch = parallel\Channel::open(CHANNEL_NAME);
    count_to_chan($ch, $ch, $id);
}

