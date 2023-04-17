<?php

register_shutdown_function(function () {
    echo "Shutting down main\n";
});

$r1 = new parallel\Runtime(__DIR__ . '/shared.php');

$r1->run(function () {
    register_shutdown_function(function () {
        echo "Shutting down R1\n";
    });
});

$r1->run(function () {
    count_to('R1', 7);
});

//echo "Going to sleep\n";
//sleep(1);
//echo "Woke up\n";
//$r1->close();
////$r1->kill();