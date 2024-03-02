<?php

namespace Menrui;

require_once __DIR__ . '/../vendor/autoload.php';

$t = microtime(true);
$b = new Bootstrap();
$b->job = new Job\PrintR([
    new Job\UserFunction([
        new Job\Parameter(['function' => function ($data) {
            $ret = [];
            foreach ($data as $nodes) {
                for ($i = 0, $n = count($nodes); $i < $n && $i < 3; $i++) {
                    $ret[] = (string)$nodes[$i];
                }
            }
            return $ret;
        }]),
        new Job\Xml([
            new Job\Parameter(['xpath' => '/rss/channel/item/title']),
            new Job\Forker([
                new Job\Parameter(['resultMerge' => true]),
                new Job\Splitter([
                    new Job\Parameter(['job' => Job\Http::class, 'param' => 'url']),
                    new Job\Data([
                        'https://news.yahoo.co.jp/rss/categories/domestic.xml',
                        'https://news.yahoo.co.jp/rss/categories/world.xml',
                        'https://news.yahoo.co.jp/rss/categories/business.xml',
                    ]),
                ]),
            ]),
        ]),
    ]),
]);
$b->run();
printf("%0.6f\n", microtime(true) - $t);
