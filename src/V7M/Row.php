<?php

namespace SJRoyd\JPK\VAT\V7M;

use Sabre\Xml\Writer;
use SJRoyd\JPK\VAT\Helper;

/**
 * Common parts of the evidence rows (SprzedazWiersz/ZakupWiersz)
 */
trait Row
{
    /**
     * Row number, assigned during generation (starts at 1)
     * @var int
     */
    protected $lp;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $nip;

    /**
     * @var string
     */
    protected $name;

    /**
     * KodKrajuNadaniaTIN
     * @var string
     */
    protected $countryCode;

    /**
     * @return int
     */
    public function getLp()
    {
        return $this->lp;
    }

    /**
     * @param int $lp
     * @return $this
     */
    public function setLp($lp)
    {
        $this->lp = $lp;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Document number
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getContractorId()
    {
        return $this->nip;
    }

    /**
     * Contractor tax identification number
     * @param string $nip
     * @return $this
     */
    public function setContractorId($nip)
    {
        $this->nip = $nip;
        return $this;
    }

    /**
     * @return string
     */
    public function getContractorName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setContractorName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getContractorCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * KodKrajuNadaniaTIN - country code of the contractor TIN
     * @param string $countryCode
     * @return $this
     */
    public function setContractorCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Tax amount of the row counted in the control sums
     * @return float
     */
    public function getTax()
    {
        $tax = 0;
        foreach (static::$taxPlus as $field) {
            $tax += $this->fields[$field];
        }
        foreach (static::$taxMinus as $field) {
            $tax -= $this->fields[$field];
        }
        return $tax;
    }

    /**
     * Maps setK10()/getK10() calls to the K_10 field etc.
     *
     * @param string $name
     * @param array $values
     * @return $this|float|null
     * @throws \InvalidArgumentException
     */
    public function __call($name, $values)
    {
        if (!preg_match('~^(set|get)K(\d+)$~', $name, $m)) {
            throw new \InvalidArgumentException("Method {$name} not exists");
        }
        $field = 'K_'.$m[2];
        if (!array_key_exists($field, $this->fields)) {
            throw new \InvalidArgumentException("Field {$field} not exists");
        }

        if ($m[1] == 'set') {
            $this->fields[$field] = $values[0];
            return $this;
        }

        return $this->fields[$field];
    }

    /**
     * Writes the K_xx fields in the schema order
     * @param Writer $writer
     */
    protected function writeFields(Writer $writer)
    {
        $fields = array_filter($this->fields, function($val){
            return $val !== null;
        });

        foreach ($fields as $field => $value) {
            $writer->write([
                Schema::getFullNS('TNS') . $field => sprintf('%.2f', $value)
            ]);
        }
    }

    /**
     * Reads the K_xx fields from parsed key-value data
     * @param array $keyValue
     */
    protected function readFields(array $keyValue)
    {
        foreach ($this->fields as $field => $_unused) {
            $this->fields[$field] = Helper\array_get($keyValue, Schema::TNS.$field);
        }
    }
}
