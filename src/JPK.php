<?php

namespace SJRoyd\JPK\VAT;

use SJRoyd\JPK\VAT\V1;
use SJRoyd\JPK\VAT\V2;
use SJRoyd\JPK\VAT\V3;
use SJRoyd\JPK\VAT\V7M;

class JPK extends V7M\JPK
{
    /**
     * Checks the form variant and parse the XML document
     * @param string $xml
     * @return V1\JPK|V2\JPK|V3\JPK|V7M\JPK
     */
    public static function parse($xml)
    {
        $parser = new Parser();
        $formCode = $parser->getVersion($xml);
        if(!$formCode){
            throw new \InvalidArgumentException('Indicated data is incorrect XML JPK_VAT data');
        }
        $version = __NAMESPACE__."\\V{$formCode}\\JPK";
        return $version::parse($xml);
    }

}
