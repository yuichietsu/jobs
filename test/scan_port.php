<?php

namespace Menrui;

require_once __DIR__ . '/../vendor/autoload.php';

$t = microtime(true);
$b = new Bootstrap();
$b->job = new Job\PrintR([
    new Job\Forker([
        new Job\Parameter(['resultMerge' => true]),
        new Job\Splitter([
            new Job\Parameter([
                'job'       => Job\CheckPort::class,
                'parameter' => [
                    'port'    => 5555,
                    'timeout' => 1,
                ],
            ]),
            new Job\Cidr([
                'cidr' => '192.168.11.0/24',
                'expand' => true,
            ]),
        ]),
    ]),
]);
$b->run();
printf("%0.6f\n", microtime(true) - $t);
