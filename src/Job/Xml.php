<?php

namespace Menrui\Job;

class Xml extends \Menrui\Job
{
    public function run()
    {
        list($params, $data) = $this->extractParameters();
        if ($params !== null) {
            $xpath = $params['xpath'];
            foreach ($data as $xml) {
                $sxe = new \SimpleXMLElement($xml);
                $this->result[] = $sxe->xpath($xpath);
            }
        }
        $this->done = true;
    }
}
