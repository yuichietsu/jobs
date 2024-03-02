<?php

namespace Menrui\Job;

class RegExp extends \Menrui\Job
{
    public function run()
    {
        list($params, $data) = $this->collectSubJobsInfo();
        if ($params !== null) {
            $pattern = $params['pattern'];
            $target  = $params['target'];
            if ($pattern) {
                foreach ($data as $text) {
                    preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
                    foreach ($matches as $match) {
                        $this->result[] = $this->filter($target, $match);
                    }
                }
            }
        }
        $this->done = true;
    }

    protected function filter($target, $match)
    {
        $ret = null;
        if ($match) {
            if (is_callable($target)) {
                $ret = call_user_func($target, $match);
            } else {
                $ret = $match[(int)$target];
            }
        }
        return $ret;
    }
}
