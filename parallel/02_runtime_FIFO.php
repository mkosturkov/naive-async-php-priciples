<?php

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');

$r1->run(function () {
    count_to('R1', 5);
});

$r1->run(function () {
    count_to('R10', 3);
});
