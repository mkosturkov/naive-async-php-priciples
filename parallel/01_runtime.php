<?php

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');
$r2 = new parallel\Runtime(__DIR__ . '/shared.php');
$r3 = new parallel\Runtime(__DIR__ . '/shared.php');

$r1->run(function () {
    count_to('R1', 5);
});

$r2->run(function () {
    count_to('R2', 7);
});

$r3->run(function () {
    count_to('R3', 4);
});



