<?php

namespace Menrui\Job;

class RandomGenerator extends \Menrui\Job
{
    public function run()
    {
        $this->result = [mt_rand()];
        $this->done = true;
    }
}
