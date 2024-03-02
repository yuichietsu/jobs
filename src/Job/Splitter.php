<?php

namespace Menrui\Job;

class Splitter extends \Menrui\Job
{
    public function run()
    {
        [$params, $data] = $this->collectSubJobsInfo();

        $job = $params['job'] ?? null;
        if ($job) {
            $parameter = null;
            if (array_key_exists('parameter', $params)) {
                $parameter = new Parameter($params['parameter']);
            }
            foreach ($data as $value) {
                $data = new Data([$value]);
                $this->result[] = new $job($parameter ? [$parameter, $data] : [$data]);
            }
        }
        $this->done = true;
    }
}
