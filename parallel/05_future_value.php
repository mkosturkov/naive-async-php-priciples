<?php

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');
$r2 = new parallel\Runtime(__DIR__ . '/shared.php');
$r3 = new parallel\Runtime(__DIR__ . '/shared.php');

//$r1->run(fn () => count_to('R1', 5));

$futures = [
    $r1->run(fn () => count_to('R1', 5)),
    $r2->run(fn () => count_to('R2', 6)),
    $r3->run(fn () => count_to('R3', 2))
];


foreach ($futures as $f) {
    var_dump($f->done());
}

foreach ($futures as $idx => $f) {
    $rid = $idx + 1;
    echo "FR$rid: " . $f->value() . "\n";
}

foreach ($futures as $f) {
    var_dump($f->done());
}
