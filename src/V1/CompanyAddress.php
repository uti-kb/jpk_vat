<?php

namespace SJRoyd\JPK\VAT\V1;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use function Sabre\Xml\Deserializer\keyValue;

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
        if (!$this->voivodeship) {
            throw new \InvalidArgumentException('Missing voivodeship');
        }

        if (!$this->country) {
            throw new \InvalidArgumentException('Missing country');
        }

        if (!$this->community) {
            throw new \InvalidArgumentException('Missing community');
        }

        if (!$this->city) {
            throw new \InvalidArgumentException('Missing city name');
        }

        if (!$this->postalCode) {
            throw new \InvalidArgumentException('Missing postal code');
        }

        if (!$this->postal) {
            throw new \InvalidArgumentException('Missing postal place');
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
            Schema::ETD.'KodKraju'     => $this->countryCode,
            Schema::ETD.'Wojewodztwo'  => $this->voivodeship,
            Schema::ETD.'Powiat'       => $this->country,
            Schema::ETD.'Gmina'        => $this->community
        ]);
        $this->street && $writer->write([
            Schema::ETD.'Ulica'        => $this->street
        ]);
        $this->number && $writer->write([
            Schema::ETD.'NrDomu'       => $this->number
        ]);
        $writer->write([
            Schema::ETD.'Miejscowosc'  => $this->city,
            Schema::ETD.'KodPocztowy'  => $this->postalCode,
            Schema::ETD.'Poczta'       => $this->postal
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
        $object->voivodeship    = $keyValue[Schema::ETD.'Wojewodztwo'];
        $object->country        = $keyValue[Schema::ETD.'Powiat'];
        $object->community      = $keyValue[Schema::ETD.'Gmina'];
        $object->street         = $keyValue[Schema::ETD.'Ulica'];
        $object->number         = $keyValue[Schema::ETD.'NrDomu'];
        $object->city           = $keyValue[Schema::ETD.'Miejscowosc'];
        $object->postalCode     = $keyValue[Schema::ETD.'KodPocztowy'];
        $object->postal         = $keyValue[Schema::ETD.'Poczta'];
        return $object;
    }

}
