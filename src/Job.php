<?php

namespace Menrui;

class Job
{
    public bool $done = false;
    public $errorMessage = null;
    public $exitCode = 0;
    public array $jobs = [];
    public array $result = [];
    public $proc;
    public $pipes;
    public $raw = '';
    public $err = '';

    public function __construct(array $jobs = [])
    {
        $this->jobs = $jobs;
    }

    public function nextJobs(&$jobs = null)
    {
        $jobs === null && ($jobs = []);
        if (!$this->done) {
            $ready = true;
            foreach ($this->jobs as $job) {
                if (!$job->done) {
                    $ready = false;
                    $job->nextJobs($jobs);
                }
            }
            $ready && ($jobs[] = $this);
        }
        return $jobs;
    }

    public function run()
    {
    }

    protected function collectSubJobsInfo(): array
    {
        $results = [];
        $params  = [];
        foreach ($this->jobs as $job) {
            if ($job instanceof Job\Parameter) {
                $params = array_merge($params, $job->result);
            } else {
                $results = array_merge($results, $job->result);
            }
        }
        return [$params, $results];
    }

    public function error($msg)
    {
        $this->errorMessage = $msg;
        $this->done = true;
    }

    public function exit($code)
    {
        if ($code === 0) {
            $this->result = unserialize($this->raw);
        }
        $this->exitCode = $code;
        $this->done = true;
    }

    public function open($cmd)
    {
        $pipes = [];
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $proc = proc_open($cmd, $descriptors, $pipes);
        stream_set_blocking($pipes[1], 0);
        stream_set_blocking($pipes[2], 0);
        $this->proc = $proc;
        $this->pipes = $pipes;
    }

    public function init()
    {
        fwrite($this->pipes[0], \Opis\Closure\serialize($this));
        fclose($this->pipes[0]);
    }

    public function close()
    {
        fclose($this->pipes[1]);
        fclose($this->pipes[2]);
        proc_close($this->proc);
    }

    public function result(string $name): mixed
    {
        return $this->result[$name] ?? null;
    }
}
