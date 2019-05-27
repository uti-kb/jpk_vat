<?php

namespace SJRoyd\JPK\VAT\Helper;

trait SellRow
{

    /**
     * C1
     * @return null
     */
    public function getTaxExempt()
    {
        return null;
    }

    /**
     * C1
     * @param float $net
     * @return $this
     */
    public function setTaxExempt($net)
    {
        return $this;
    }

    /**
     * C2 and C2a
     * @return null
     */
    public function getAbroadDelivery()
    {
        return null;
    }

    /**
     * C2 and C2a
     * @param float $net C2
     * @param float $netA C2a
     * @return $this
     */
    public function setAbroadDelivery($net, $netA = null)
    {
        return $this;
    }

    /**
     * C3 and C3a
     * @return null
     */
    public function getTaxD()
    {
        return null;
    }

    /**
     * C3 and C3a
     * @param float $net C3
     * @param float $netA C3a
     * @return $this
     */
    public function setTaxD($net, $netA = null)
    {
        return $this;
    }

    /**
     * C4
     * @return null
     */
    public function getTaxC()
    {
        return null;
    }

    /**
     * C4
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setTaxC($net, $tax)
    {
        return $this;
    }

    /**
     * C5
     * @return null
     */
    public function getTaxB()
    {
        return null;
    }

    /**
     * C5
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setTaxB($net, $tax)
    {
        return $this;
    }

    /**
     * C6
     * @return null
     */
    public function getTaxA()
    {
        return null;
    }

    /**
     * C6
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setTaxA($net, $tax)
    {
        return $this;
    }

    /**
     * C7
     * @return null
     */
    public function getExportUE()
    {
        return null;
    }

    /**
     * C7
     * @param float $net
     * @return $this
     */
    public function setExportUE($net)
    {
        return $this;
    }

    /**
     * C8
     * @return null
     */
    public function getExport()
    {
        return null;
    }

    /**
     * C8
     * @param float $net
     * @return $this
     */
    public function setExport($net)
    {
        return $this;
    }

    /**
     * C9
     * @return null
     */
    public function getImportUE()
    {
        return null;
    }

    /**
     * C9
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImportUE($net, $tax)
    {
        return $this;
    }

    /**
     * C10
     * @return null
     */
    public function getImport_Art33a()
    {
        return null;
    }

    /**
     * C10
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art33a($net, $tax)
    {
        return $this;
    }

    /**
     * C11
     * @return null
     */
    public function getImport_Art28bExcept()
    {
        return null;
    }

    /**
     * C11
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art28bExcept($net, $tax)
    {
        return $this;
    }

    /**
     * C12
     * @return null
     */
    public function getImport_Art28bOnly()
    {
        return null;
    }

    /**
     * C12
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art28bOnly($net, $tax)
    {
        return $this;
    }

    /**
     * C13
     * @return null
     */
    public function getReverseChargeSeller_Art17u1p7_8()
    {
        return null;
    }

    /**
     * C13
     * @param float $net
     * @return $this
     */
    public function setReverseChargeSeller_Art17u1p7_8($net)
    {
        return $this;
    }

    /**
     * C14
     * @return null
     */
    public function getReverseChargeBuyer_Art17u1p5()
    {
        return null;
    }

    /**
     * C14
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setReverseChargeBuyer_Art17u1p5($net, $tax)
    {
        return $this;
    }

    /**
     * C15
     * @return null
     */
    public function getReverseChargeBuyer_Art17u1p7_8()
    {
        return null;
    }

    /**
     * C15
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setReverseChargeBuyer_Art17u1p7_8($net, $tax)
    {
        return $this;
    }

    /**
     * C16
     * @return null
     */
    public function getPsychicalInventoryTax()
    {
        return null;
    }

    /**
     * C16
     * @param float $tax
     * @return $this
     */
    public function setPsychicalInventoryTax($tax)
    {
        return $this;
    }

    /**
     * C17
     * @return null
     */
    public function getCashRegisterTaxBack()
    {
        return null;
    }

    /**
     * C17
     * @param float $tax
     * @return $this
     */
    public function setCashRegisterTaxBack($tax)
    {
        return $this;
    }

    /**
     * C18
     * @return null
     */
    public function getTransportImportUeTaxDue()
    {
        return null;
    }

    /**
     * C18
     * @param float $tax
     * @return $this
     */
    public function setTransportImportUeTaxDue($tax)
    {
        return $this;
    }

    /**
     * C19
     * @return null
     */
    public function getFuelImportUeTax()
    {
        return null;
    }

    /**
     * C19
     * @param float $tax
     * @return $this
     */
    public function setFuelImportUeTax($tax)
    {
        return $this;
    }

}
