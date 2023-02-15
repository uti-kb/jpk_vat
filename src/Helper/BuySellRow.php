<?php

namespace SJRoyd\JPK\VAT\Helper;

trait BuySellRow {


    protected static $sum = 0;

    /**
     * @var int
     */
    protected static $index = 0;

    /**
     * @var string
     */
    protected $id;

    protected $tax = 0;

    /**
     * @var string
     */
    protected $nip;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return SellRow
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
     * @param string $nip
     * @return SellRow
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
     * @return SellRow
     */
    public function setContractorName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getContractorAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return SellRow
     */
    public function setContractorAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }


    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @param float $values
     * @return SellRow
     * @throws InvalidArgumentException
     */
    public function __call($name, $values)
    {
        preg_match('~(set|get)(.*)~', $name, $m);
        $set = $m[1] == 'set';
        $get = $m[1] == 'get';
        $field = $m[2];
        if(!array_key_exists($field, $this->fields))
        {
            throw new \InvalidArgumentException("Field {$field} not exists");
        }

        if($set){
            $value = $values[0];
            if(is_null($value)){
                return $this;
            }
            !$this->fields[$field] && $this->fields[$field] = 0;

            if (! is_numeric($value)) {
                $this->fields[$field] = $value;
                $value = 0;
            } else {
                $this->fields[$field] += $value;
            }

            if(self::$fieldsControl[$field] > 0){
                self::$sum += $value;
                $this->tax += $value;
            } else if(self::$fieldsControl[$field] < 0){
                self::$sum -= $value;
                $this->tax -= $value;
            }

            return $this;
        }

        if($get){
            return $this->fields[$field];
        }
    }

}
