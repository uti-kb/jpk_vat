<?php

namespace SJRoyd\JPK\VAT\V7M;

use Sabre\Xml\Writer;
use SJRoyd\JPK\VAT\Helper;

/**
 * KSeF invoice marker - exactly one of NrKSeF / OFF / BFK / DI
 * is required in every evidence row.
 */
trait KsefDocument
{
    /**
     * Pattern of the KSeF invoice number (TNumerKSeF)
     * @var string
     */
    protected static $ksefNumberPattern = '~^([1-9]((\d[1-9])|([1-9]\d))\d{7}|M\d{9}|[A-Z]{3}\d{7})-(20[2-9][0-9]|2[1-9][0-9]{2}|[3-9][0-9]{3})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])-([0-9A-F]{6})-?([0-9A-F]{6})-([0-9A-F]{2})$~';

    /**
     * @var string
     */
    protected $ksefNumber;

    /**
     * @var bool
     */
    protected $ksefOffline = false;

    /**
     * @var bool
     */
    protected $nonKsefInvoice = false;

    /**
     * @var bool
     */
    protected $otherDocument = false;

    /**
     * NrKSeF - number identifying the invoice in KSeF
     * @return string|null
     */
    public function getKsefNumber()
    {
        return $this->ksefNumber;
    }

    /**
     * NrKSeF - number identifying the invoice in KSeF
     * @param string $ksefNumber
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setKsefNumber($ksefNumber)
    {
        if (!preg_match(self::$ksefNumberPattern, $ksefNumber)) {
            throw new \InvalidArgumentException("Invalid KSeF number: {$ksefNumber}");
        }
        $this->ksefNumber = $ksefNumber;
        $this->ksefOffline = $this->nonKsefInvoice = $this->otherDocument = false;
        return $this;
    }

    /**
     * OFF - invoice issued in the offline mode (art. 106nf of the act)
     * without a KSeF number on the day of submission
     * @return bool
     */
    public function isKsefOffline()
    {
        return $this->ksefOffline;
    }

    /**
     * OFF - invoice issued in the offline mode (art. 106nf of the act)
     * without a KSeF number on the day of submission
     * @return $this
     */
    public function setKsefOffline()
    {
        $this->ksefOffline = true;
        $this->ksefNumber = null;
        $this->nonKsefInvoice = $this->otherDocument = false;
        return $this;
    }

    /**
     * BFK - electronic or paper invoice issued outside KSeF
     * @return bool
     */
    public function isNonKsefInvoice()
    {
        return $this->nonKsefInvoice;
    }

    /**
     * BFK - electronic or paper invoice issued outside KSeF
     * @return $this
     */
    public function setNonKsefInvoice()
    {
        $this->nonKsefInvoice = true;
        $this->ksefNumber = null;
        $this->ksefOffline = $this->otherDocument = false;
        return $this;
    }

    /**
     * DI - document other than an invoice
     * @return bool
     */
    public function isOtherDocument()
    {
        return $this->otherDocument;
    }

    /**
     * DI - document other than an invoice
     * @return $this
     */
    public function setOtherDocument()
    {
        $this->otherDocument = true;
        $this->ksefNumber = null;
        $this->ksefOffline = $this->nonKsefInvoice = false;
        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function validateKsef()
    {
        if (!$this->ksefNumber && !$this->ksefOffline && !$this->nonKsefInvoice && !$this->otherDocument) {
            throw new \InvalidArgumentException(
                'Missing KSeF marker - use setKsefNumber(), setKsefOffline(), setNonKsefInvoice() or setOtherDocument()'
            );
        }
    }

    /**
     * Writes the NrKSeF|OFF|BFK|DI choice
     * @param Writer $writer
     */
    protected function writeKsef(Writer $writer)
    {
        if ($this->ksefNumber) {
            $writer->write([Schema::getFullNS('TNS') . 'NrKSeF' => $this->ksefNumber]);
        } elseif ($this->ksefOffline) {
            $writer->write([Schema::getFullNS('TNS') . 'OFF' => 1]);
        } elseif ($this->nonKsefInvoice) {
            $writer->write([Schema::getFullNS('TNS') . 'BFK' => 1]);
        } else {
            $writer->write([Schema::getFullNS('TNS') . 'DI' => 1]);
        }
    }

    /**
     * Reads the NrKSeF|OFF|BFK|DI choice from parsed key-value data
     * @param array $keyValue
     */
    protected function readKsef(array $keyValue)
    {
        $this->ksefNumber     = Helper\array_get($keyValue, Schema::TNS.'NrKSeF');
        $this->ksefOffline    = (bool) Helper\array_get($keyValue, Schema::TNS.'OFF');
        $this->nonKsefInvoice = (bool) Helper\array_get($keyValue, Schema::TNS.'BFK');
        $this->otherDocument  = (bool) Helper\array_get($keyValue, Schema::TNS.'DI');
    }
}
