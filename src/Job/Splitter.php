<?php

namespace Menrui\Job;

class Splitter extends \Menrui\Job
{
    public function run()
    {
        list($params, $data) = $this->extractParameters();
        if ($params !== null) {
            $job = $params['job'];
            $key = $params['param'];
            foreach ($data as $d) {
                foreach (is_array($d) ? $d : [$d] as $v) {
                    $this->result[] = new $job([new Parameter([$key => $v])]);
                }
            }
        }
        $this->done = true;
    }
}
