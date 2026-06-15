<?php

namespace SJRoyd\JPK\VAT\Helper;

trait Schema {

    public static function getNS(string $name): string|array|null
    {
        $ns = constant('self::'.$name);
        return preg_replace('~\{(.*)\}~', '$1', (string) $ns);
    }

    public static function getFullNS(string $name): mixed
    {
        return constant('self::'.$name);
    }
}
