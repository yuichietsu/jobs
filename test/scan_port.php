<?php

namespace Menrui;

require_once __DIR__ . '/../vendor/autoload.php';

$t = microtime(true);
$b = new Bootstrap();
$b->job = new Job\PrintR([
    new Job\Forker([
        new Job\Parameter(['resultMerge' => true, 'concurrency' => 10]),
        new Job\Splitter([
            new Job\Parameter([
                'job'       => Job\CheckPort::class,
                'parameter' => [
                    'port'    => 5555,
                    'timeout' => 0.5,
                ],
            ]),
            new Job\Cidr([
                new Job\Parameter(['resultMerge' => true]),
                new Job\Data(['192.168.11.0/24']),
            ]),
        ]),
    ]),
]);
$b->run();
printf("%0.6f\n", microtime(true) - $t);
