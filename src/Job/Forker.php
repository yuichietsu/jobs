<?php

namespace Menrui\Job;

class Forker extends \Menrui\Job
{
    protected $concurrency = 3;

    public function run()
    {
        $this->result = [];
        $jobs = [];
        foreach ($this->jobs as $stream) {
            if ($stream instanceof Parameter) {
                if ($c = $stream->result['concurrency']) {
                    $this->concurrency = $c;
                }
            } else {
                foreach ($stream->result as $job) {
                    $jobs[] = $job;
                }
            }
        }
        $jobSegs = array_chunk($jobs, $this->concurrency);
        $fork = new \Menrui\Fork();
        foreach ($jobSegs as $jobSeg) {
            $fork->exec($jobSeg);
        }
        foreach ($jobs as $job) {
            $this->result[] = $job->result;
        }
        $this->done = true;
    }
}
