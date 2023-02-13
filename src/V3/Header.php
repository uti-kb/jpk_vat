<?php

namespace SJRoyd\JPK\VAT\V3;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;
use function Sabre\Xml\Deserializer\keyValue;

#[\AllowDynamicProperties]
class Header implements Helper\HeaderInterface, XmlSerializable, XmlDeserializable
{
    use Helper\Header;

    protected $variant = 3;

    protected $schema = '1-1';

    /**
     * @var int
     */
    protected $reason = 0;

    /**
     * @var \DateTime
     */
    protected $dateFrom;

    /**
     * @var \DateTime
     */
    protected $dateTo;

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
    public function setCorrection($i)
    {
        $this->reason = $i;
        return $this;
    }

    /**
     *
     * @return \DateTime|string
     */
    public function getRangeDateFrom($format = 'Y-m-d')
    {
        return $format ? $this->dateFrom->format($format) : $this->dateFrom;
    }

    /**
     *
     * @return \DateTime|string
     */
    public function getRangeDateTo($format = 'Y-m-d')
    {
        return $format ? $this->dateTo->format($format) : $this->dateTo;
    }

    /**
     *
     * @param \DateTime|string $fromDate
     * @param \DateTime|string $toDate
     * @return Header
     */
    public function setRangeDates($fromDate, $toDate)
    {
        $this->dateFrom = $fromDate instanceof \DateTime ? $fromDate : new \DateTime($fromDate);
        $this->dateTo = $toDate instanceof \DateTime ? $toDate : new \DateTime($toDate);

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     *
     * @param string $systemName
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
    }

    protected function validate()
    {
        if ($this->reason === null) {
            throw new \InvalidArgumentException('Missing reason of submission');
        }

        if (! $this->dateFrom || ! $this->dateTo) {
            throw new \InvalidArgumentException('Missing date range');
        }

        if (!$this->systemName) {
            throw new \InvalidArgumentException('Missing system name');
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
                'name' => Schema::getFullNS('TNS') . 'KodFormularza',
                'value' => $this->getFormCode(),
                'attributes' => [
                    'kodSystemowy' => $this->getSystemCode(),
                    'wersjaSchemy' => $this->getSchema(),
                ]
            ],
            Schema::getFullNS('TNS') . 'WariantFormularza' => $this->getFormVariant(),
            Schema::getFullNS('TNS') . 'CelZlozenia' => $this->reason,
            Schema::getFullNS('TNS') . 'DataWytworzeniaJPK' => (new \DateTime('now', new \DateTimeZone('UTC')))
                ->format('Y-m-d\TH:i:s\Z'),
            Schema::getFullNS('TNS') . 'DataOd' => $this->dateFrom->format('Y-m-d'),
            Schema::getFullNS('TNS') . 'DataDo' => $this->dateTo->format('Y-m-d'),
            Schema::getFullNS('TNS') . 'NazwaSystemu' => $this->systemName
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
        $object->reason     = $keyValue[Schema::TNS.'CelZlozenia'];
        $object->dateFrom   = new \DateTime($keyValue[Schema::TNS.'DataOd']);
        $object->dateTo     = new \DateTime($keyValue[Schema::TNS.'DataDo']);
        $object->systemName = $keyValue[Schema::TNS.'NazwaSystemu'];
        return $object;
    }

}
