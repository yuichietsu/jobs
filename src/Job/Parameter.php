<?php

namespace Menrui\Job;

class Parameter extends \Menrui\Job
{
    public bool $done = true;

    public function __construct(array $result = [])
    {
        $this->result = $result;
    }
}
