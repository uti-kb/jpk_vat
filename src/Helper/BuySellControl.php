<?php

namespace SJRoyd\JPK\VAT\Helper;

trait BuySellControl
{
    protected $count = 0;

    protected $tax = 0;

    /**
     * @param int $count
     * @return BuyControl
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return BuyControl
     */
    public function addCount()
    {
        $this->count++;
        return $this;
    }

    /**
     * @param float $tax
     * @return BuyControl
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @param float $tax
     * @return BuyControl
     */
    public function addTax($tax)
    {
        $this->tax += $tax;
        return $this;
    }

}
