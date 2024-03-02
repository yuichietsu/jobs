<?php

namespace Menrui\Job;

class Http extends \Menrui\Job
{
    protected bool $flatten = false;

    public function run()
    {
        $this->result = [];
        foreach ($this->jobs as $job) {
            if ($url = $job->result('url')) {
                $this->result[] = file_get_contents($url);
            }
            if ($job->result('flatten')) {
                $this->flatten = true;
            }
        }
        if ($this->flatten && count($this->result) == 1) {
            $this->result = $this->result[0];
        }
        $this->done = true;
    }
}
