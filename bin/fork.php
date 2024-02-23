<?php

namespace Menrui;

require_once __DIR__ . '/../vendor/autoload.php';

exit(main());

function main()
{
    $in = '';
    while ('' !== ($data = fread(STDIN, 8092))) {
        $in .= $data;
    }
    $job = \Opis\Closure\Unserialize($in);
    $job->run();
    echo serialize($job->result);
    return 0;
}
