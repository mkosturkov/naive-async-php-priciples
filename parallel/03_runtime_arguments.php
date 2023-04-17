<?php

include __DIR__ . '/shared.php';

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');
$r2 = new parallel\Runtime();

$r1->run(function ($p) {
    echo "The parameter is: $p\n";
}, ['NICE']);

$p = 'COOL';
$r1->run(function () use ($p) {
    echo "The used value is: $p\n";
});

$r1->run(function ($object) {
    echo "The class is: " . get_class($object) . "\n";
}, [new SomeClass()]);

$r2->run(function ($object) {
    echo "Without bootstrap file the class is: " . get_class($object) . "\n";
}, [new SomeClass()]);

//$r2->run(function () use (&$p) {
//    echo "This will never show\n";
//});

