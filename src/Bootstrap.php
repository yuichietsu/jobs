<?php

namespace Menrui;

class Bootstrap
{
    public $job = null;

    public function run()
    {
        if ($this->job instanceof Job) {
            $fork = new Fork();
            while (!$this->job->done) {
                $fork->exec($this->job->nextJobs());
            }
        }
    }
}
