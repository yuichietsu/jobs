<?php

namespace Menrui\Job;

class Forker extends \Menrui\Job
{
    protected $concurrency = 3;
    protected $resultMerge = true;

    public function run()
    {
        $this->result = [];
        $jobs = [];
        foreach ($this->jobs as $job) {
            if ($job instanceof Parameter) {
                if ($c = $job->result('concurrency')) {
                    $this->concurrency = $c;
                }
                if ($job->result('resultMerge')) {
                    $this->resultMerge = true;
                }
            } else {
                foreach ($job->result as $job) {
                    $jobs[] = $job;
                }
            }
        }
        $jobSegs = array_chunk($jobs, $this->concurrency);
        $fork = new \Menrui\Fork();
        foreach ($jobSegs as $jobSeg) {
            $fork->exec($jobSeg);
        }
        if ($this->resultMerge) {
            foreach ($jobs as $job) {
                $this->result = array_merge($this->result, $job->result);
            }
        } else {
            foreach ($jobs as $job) {
                $this->result[] = $job->result;
            }
        }
        $this->done = true;
    }
}
