<?php

namespace SJRoyd\JPK\VAT\Helper;

function array_get($array, $key, $cast = null)
{
    if(array_key_exists($key, $array)){
        return $cast ? new $cast($array[$key]) : $array[$key];
    }
    return null;
}

