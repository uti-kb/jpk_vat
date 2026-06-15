<?php

namespace SJRoyd\JPK\VAT\Helper;

trait BuyRow
{

    /**
     * @return null
     */
    public function getFixedAssets(): null
    {
        return null;
    }

    /**
     * @param float $nettoToDeduction
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setFixedAssets($nettoToDeduction, $taxToDeduction)
    {
        return $this;
    }

    /**
     * @return null
     */
    public function getOtherAssets(): null
    {
        return null;
    }

    /**
     * @param float $nettoToDeduction
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setOtherAssets($nettoToDeduction, $taxToDeduction)
    {
        return $this;
    }

    /**
     * @return null
     */
    public function getFixedAssetsTaxCorrection(): null
    {
        return null;
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setFixedAssetsTaxCorrection($taxToDeduction)
    {
        return $this;
    }

    /**
     * @return null
     */
    public function getOtherAssetsTaxCorrection(): null
    {
        return null;
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setOtherAssetsTaxCorrection($taxToDeduction)
    {
        return $this;
    }

    /**
     * @return null
     */
    public function getTaxCorrectionArt89bu1(): null
    {
        return null;
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setTaxCorrectionArt89bu1($taxToDeduction)
    {
        return $this;
    }

    /**
     * @return null
     */
    public function getTaxCorrectionArt89bu4(): null
    {
        return null;
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setTaxCorrectionArt89bu4($taxToDeduction)
    {
        return $this;
    }

}
