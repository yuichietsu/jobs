<?php

namespace Menrui\Job;

class Http extends \Menrui\Job
{
    public function run()
    {
        $this->result = [];
        foreach ($this->jobs as $job) {
            foreach ($job->result as $url) {
                $this->result[] = file_get_contents($url);
            }
        }
        $this->done = true;
    }
}
