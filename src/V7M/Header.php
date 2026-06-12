<?php

namespace SJRoyd\JPK\VAT\V7M;

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

    protected $schema = '1-0E';

    /**
     * CelZlozenia: 1 - submission, 2 - correction
     * @var int
     */
    protected $reason = 1;

    /**
     * KodUrzedu - tax office code
     * @var string
     */
    protected $officeCode;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var int
     */
    protected $month;

    /**
     * @var string
     */
    protected $systemName;

    /**
     * @return string
     */
    public function getSystemCode()
    {
        return "JPK_V7M ($this->variant)";
    }

    /**
     * @return int
     */
    public function getReasonOfSubmission()
    {
        return $this->reason;
    }

    /**
     * Marks the file as a correction (CelZlozenia = 2)
     * @return Header
     */
    public function setCorrection()
    {
        $this->reason = 2;
        return $this;
    }

    /**
     * @return string
     */
    public function getOfficeCode()
    {
        return $this->officeCode;
    }

    /**
     * KodUrzedu - four digit tax office code
     * @param string $officeCode
     * @return Header
     */
    public function setOfficeCode($officeCode)
    {
        $this->officeCode = $officeCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Rok and Miesiac - settlement period
     * @param int $year
     * @param int $month
     * @return Header
     */
    public function setPeriod($year, $month)
    {
        $this->year = (int) $year;
        $this->month = (int) $month;
        return $this;
    }

    /**
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * @param string $systemName
     * @return Header
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
        return $this;
    }

    protected function validate()
    {
        if (!in_array($this->reason, [1, 2])) {
            throw new \InvalidArgumentException('Incorrect reason of submission');
        }

        if (!preg_match('~^\d{4}$~', (string) $this->officeCode)) {
            throw new \InvalidArgumentException('Missing or incorrect tax office code');
        }

        if (!$this->year || $this->year < 2026) {
            throw new \InvalidArgumentException('Missing or incorrect year (2026 or later required)');
        }

        if (!$this->month || $this->month < 1 || $this->month > 12) {
            throw new \InvalidArgumentException('Missing or incorrect month');
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
            Schema::getFullNS('TNS') . 'DataWytworzeniaJPK' => (new \DateTime('now', new \DateTimeZone('UTC')))
                ->format('Y-m-d\TH:i:s\Z'),
        ]);

        $this->systemName && $writer->write([
            Schema::getFullNS('TNS') . 'NazwaSystemu' => $this->systemName
        ]);

        $writer->write([
            [
                'name' => Schema::getFullNS('TNS') . 'CelZlozenia',
                'value' => $this->reason,
                'attributes' => [
                    'poz' => 'P_7',
                ]
            ],
            Schema::getFullNS('TNS') . 'KodUrzedu' => $this->officeCode,
            Schema::getFullNS('TNS') . 'Rok' => $this->year,
            Schema::getFullNS('TNS') . 'Miesiac' => $this->month,
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
        $object->reason     = (int) $keyValue[Schema::TNS.'CelZlozenia'];
        $object->officeCode = $keyValue[Schema::TNS.'KodUrzedu'];
        $object->year       = (int) $keyValue[Schema::TNS.'Rok'];
        $object->month      = (int) $keyValue[Schema::TNS.'Miesiac'];
        $object->systemName = Helper\array_get($keyValue, Schema::TNS.'NazwaSystemu');
        return $object;
    }

}
