<?php

namespace SJRoyd\JPK\VAT\V1;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;
use function Sabre\Xml\Deserializer\keyValue;

class Header implements Helper\HeaderInterface, XmlSerializable, XmlDeserializable
{
    use Helper\Header;

    protected $variant = 1;

    protected $schema = '1-0';

    /**
     * @var int
     */
    protected $reason = 1;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var int
     */
    protected $taxOfficeCode;

    /**
     *
     * @return int
     */
    public function getReasonOfSubmission()
    {
        return $this->reason;
    }

    /**
     *
     * @return Header
     */
    public function setCorrection()
    {
        $this->reason = 2;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getRangeDate($format = 'Y-m-01')
    {
        return $format ? $this->date->format($format) : $this->date;
    }

    /**
     *
     * @param int $year
     * @param int $month
     * @return Header
     */
    public function setRangeDates($year, $month)
    {
        $this->date = new \DateTime("{$year}-{$month}");
        return $this;
    }

    public function getTaxOficeCode()
    {
        return $this->taxOfficeCode;
    }

    public function setTaxOficeCode($taxOficeCode)
    {
        $this->taxOfficeCode = $taxOficeCode;
        return $this;
    }

    protected function validate()
    {
        if (!$this->reason) {
            throw new \InvalidArgumentException('Missing reason of submission');
        }

        if (!$this->date) {
            throw new \InvalidArgumentException('Missing date range');
        }

        if (!$this->taxOfficeCode) {
            throw new \InvalidArgumentException('Missing date range');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $this->validate();

        $writer->write([
            [
                'name' => 'KodFormularza',
                'value' => $this->getFormCode(),
                'attributes' => [
                    'kodSystemowy' => $this->getSystemCode(),
                    'wersjaSchemy' => $this->getSchema(),
                ]
            ],
            'WariantFormularza' => $this->getFormVariant(),
            'CelZlozenia' => $this->reason,
            'DataWytworzeniaJPK' => (new \DateTime('now', new \DateTimeZone('UTC')))
                ->format('Y-m-d\TH:i:sZ'),
            'DataOd' => $this->date->format('Y-m-01'),
            'DataDo' => $this->date->format('Y-m-t'),
            'DomyslnyKodWaluty' => 'PLN',
            'KodUrzedu' => $this->taxOfficeCode,
        ]);
    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * @return mixed
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValue = keyValue($reader);

        $object = new self();
        $object->reason = $keyValue[Schema::NS.'CelZlozenia'];
        $object->date   = new \DateTime($keyValue[Schema::NS.'DataOd']);
        return $object;
    }

}
