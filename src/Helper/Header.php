<?php

namespace SJRoyd\JPK\VAT\Helper;

trait Header
{
    /**
     * @return string
     */
    public function getFormCode()
    {
        return 'JPK_VAT';
    }

    /**
     * @return string
     */
    public function getSystemCode()
    {
        return "JPK_VAT ($this->variant)";
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @return int
     */
    public function getFormVariant()
    {
        return $this->variant;
    }
}
