<?php

namespace Menrui\Job;

class Cidr extends \Menrui\Job
{
    public function run()
    {
        [$params] = $this->extractParameters();
        if ($params !== null) {
            $cidr   = $params['cidr'] ?? '';
            $expand = $params['expand'] ?? false;
            if ($cidr) {
                if ($expand) {
                    $this->result = $this->expandToIpList($cidr);
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
