<?php

namespace Menrui\Job;

class Parameter extends \Menrui\Job
{
    public $done = true;

    public function __construct($result = [])
    {
        $this->result = $result;
    }
}
