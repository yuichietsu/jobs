<?php

namespace Menrui\Job;

class Data extends \Menrui\Job
{
    public bool $done = true;

    public function __construct($result = [])
    {
        $this->result = $result;
    }
}
