<?php

namespace Menrui\Job;

class UserFunction extends \Menrui\Job
{
    public function run()
    {
        list($params, $data) = $this->collectSubJobsInfo();
        if ($params !== null) {
            $func = $params['function'];
            $this->result = call_user_func($func, $data);
        }
        $this->done = true;
    }
}
