<?php

namespace SJRoyd\JPK\VAT\Helper;

interface HeaderInterface
{
    public function getFormCode();

    public function getSystemCode();

    public function getSchema();

    public function getFormVariant();
}
