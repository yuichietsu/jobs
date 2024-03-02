<?php

namespace Menrui\Job;

class Data extends \Menrui\Job
{
    public function __construct(array $result = [])
    {
        $this->done   = true;
        $this->result = $result;
    }
}
