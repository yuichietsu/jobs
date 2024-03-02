<?php

namespace Menrui\Job;

class Forker extends \Menrui\Job
{
    public function run()
    {
        $concurrency = 3;
        $resultMerge = true;

        $this->result = [];
        $jobs = [];
        foreach ($this->jobs as $job) {
            if ($job instanceof Parameter) {
                if ($c = $job->result('concurrency')) {
                    $concurrency = $c;
                }
                if ($job->result('resultMerge')) {
                    $resultMerge = true;
                }
            } else {
                foreach ($job->result as $job) {
                    $jobs[] = $job;
                }
            }
        }
        $jobSegs = array_chunk($jobs, $concurrency);
        $fork = new \Menrui\Fork();
        foreach ($jobSegs as $jobSeg) {
            $fork->exec($jobSeg);
        }
        if ($resultMerge) {
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
