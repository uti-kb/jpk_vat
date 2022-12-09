<?php

namespace SJRoyd\JPK\VAT\Helper;

trait Schema {

    public static function getNS($name)
    {
        $ns = constant('self::'.$name);
        return preg_replace('~\{(.*)\}~', '$1', $ns);
    }

    public static function getFullNS($name)
    {
        return constant('self::'.$name);
    }
}
