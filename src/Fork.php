<?php
namespace Menrui;

class Fork
{
    public $cmd;
    public $selectTimeout = 0.5;

    public function __construct($cmd = null)
    {
        if ($cmd === null) {
            $cmd = sprintf('php %s/../bin/fork.php', __DIR__);
        }
        $this->cmd = $cmd;
    }

    public function exec($jobs)
    {
        $n = count($jobs);
        if ($n == 1) {
            $jobs[0]->run();
        } elseif ($n > 1) {
            $this->fork($jobs);
        }
    }

    protected function fork($jobs)
    {
        $this->fakeFork($jobs);
    }

    protected function fakeFork($jobs)
    {
        $tvs = (int)$this->selectTimeout;
        $tvu = ($this->selectTimeout * 1000 * 1000) % (1000 * 1000);

        foreach ($jobs as $job) {
            $job->open($this->cmd);
            $job->init();
        }

        do {
            $running = false;
            foreach ($jobs as $job) {
                if (!$job->done) {
                    $running = true;
                    $stat   = proc_get_status($job->proc);
                    $read   = [$job->pipes[1], $job->pipes[2]];
                    $write  = null;
                    $except = null;
                    $n = stream_select($read, $write, $except, $tvs, $tvu);
                    if ($n > 0) {
                        foreach ([
                            1 => 'raw',
                            2 => 'err',
                        ] as $desc => $var) {
                            do {
                                $data = fread($job->pipes[$desc], 8092);
                                $job->$var .= $data;
                            } while (strlen($data) > 0);
                        }
                    }
                    if ($stat === false) {
                        $job->error('failed to get stat info ob proc');
                    } elseif ($stat['running'] === false) {
                        $job->exit($stat['exitcode']);
                    }
                }
            }
        } while ($running);

        foreach ($jobs as $job) {
            $job->close();
        }
    }

    protected function pcntlFork($jobs)
    {
    }
}
