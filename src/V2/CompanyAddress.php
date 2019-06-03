<?php

namespace SJRoyd\JPK\VAT\V2;

use Sabre\Xml\Element\KeyValue;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class CompanyAddress implements XmlSerializable, XmlDeserializable
{

    /**
     * @var string
     */
    protected $countryCode = 'PL';

    /**
     * @var string
     */
    protected $voivodeship;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $community;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $postalCode;

    /**
     * @var string
     */
    protected $postal;

    public function getVoivodeship()
    {
        return $this->voivodeship;
    }

    public function setVoivodeship($voivodeship)
    {
        $this->voivodeship = $voivodeship;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCommunity()
    {
        return $this->community;
    }

    public function setCommunity($community)
    {
        $this->community = $community;
        return $this;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getPostal()
    {
        return $this->postal;
    }

    public function setPostal($postal)
    {
        $this->postal = $postal;
        return $this;
    }

    protected function validate()
    {
        if ($this->country === null) {
            throw new \InvalidArgumentException('Missing country code');
        }

        if ($this->city === null) {
            throw new \InvalidArgumentException('Missing city name');
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
            'KodKraju'      => $this->countryCode
        ]);
        $this->voivodeship && $writer->write([
            'Wojewodztwo'   => $this->voivodeship
        ]);
        $this->country && $writer->write([
            'Powiat'        => $this->country
        ]);
        $this->community && $writer->write([
            'Gmina'         => $this->community
        ]);
        $this->street && $writer->write([
            'Ulica'         => $this->street
        ]);
        $this->number && $writer->write([
            'NrDomu'        => $this->number
        ]);
        $writer->write([
            'Miejscowosc'   => $this->city
        ]);
        $this->postalCode && $writer->write([
            'KodPocztowy'   => $this->postalCode
        ]);
        $this->postal && $writer->write([
            'Poczta'        => $this->postal
        ]);
    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * @return mixed
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $keyValue = KeyValue::xmlDeserialize($reader);

        $object = new self();
        $object->voivodeship    = $keyValue[Schema::NS.'Wojewodztwo'];
        $object->country        = $keyValue[Schema::NS.'Powiat'];
        $object->community      = $keyValue[Schema::NS.'Gmina'];
        $object->street         = $keyValue[Schema::NS.'Ulica'];
        $object->number         = $keyValue[Schema::NS.'NrDomu'];
        $object->city           = $keyValue[Schema::NS.'Miejscowosc'];
        $object->postalCode     = $keyValue[Schema::NS.'KodPocztowy'];
        $object->postal         = $keyValue[Schema::NS.'Poczta'];
        return $object;
    }

}
