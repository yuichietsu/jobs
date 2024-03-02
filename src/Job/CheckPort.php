<?php

namespace Menrui\Job;

class CheckPort extends \Menrui\Job
{
    public function run()
    {
        [$params, $results] = $this->collectSubJobsInfo();
        if ($params !== null) {
            $timeout = $params['timeout'] ?? 5;
            $port    = $params['port'] ?? 80;
            foreach ($results as $host) {
                $this->result[] = ['host' => $host, 'active' => $this->checkResponse($host, $port, $timeout)];
            }
        }
        $this->done = true;
    }

    public function checkResponse(string $host, int $port, float $timeout = 5): bool
    {
        $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if ($socket) {
            fclose($socket);
            return true;
        } else {
            return false;
        }
    }
}
