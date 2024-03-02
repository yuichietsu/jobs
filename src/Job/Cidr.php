<?php

namespace Menrui\Job;

class Cidr extends \Menrui\Job
{
    public function run()
    {
        [$params, $results] = $this->collectSubJobsInfo();

        $expand      = $params['expand'] ?? true;
        $resultMerge = $params['resultMerge'] ?? false;
        if ($expand) {
            foreach ($results as $cidr) {
                $r = $this->expandToIpList($cidr);
                if ($resultMerge) {
                    $this->result = array_merge($this->result, $r);
                } else {
                    $this->result[] = $r;
                }
            }
        }
        $this->done = true;
    }

    public function expandToIpList(string $cidr): array
    {
        list($ip, $prefix) = explode('/', $cidr);
        $ipAddress = ip2long($ip);
        $prefixMask = -1 << (32 - $prefix);
        $subnetStart = $ipAddress & $prefixMask;
        $subnetEnd = $subnetStart + pow(2, (32 - $prefix)) - 1;
        $ipList = [];
        for ($i = $subnetStart; $i <= $subnetEnd; $i++) {
            $ipList[] = long2ip($i);
        }
        return $ipList;
    }
}
