<?php

namespace Menrui;

class Bootstrap
{
    public $flow = null;

    public function run()
    {
        if ($this->flow instanceof Job) {
            $fork = new Fork();
            while (!$this->flow->done) {
                $fork->exec($this->flow->nextJobs());
            }
        }
    }
}
