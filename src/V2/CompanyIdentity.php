<?php

namespace SJRoyd\JPK\VAT\V2;

use Sabre\Xml\Element\KeyValue;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

class CompanyIdentity implements XmlSerializable, XmlDeserializable
{

    /**
     * @var string
     */
    protected $nip;

    /**
     * @var string
     */
    protected $name;

    /**
     *
     * @return string
     */
    public function getNip()
    {
        return $this->nip;
    }

    /**
     *
     * @param string $nip
     * @return CompanyIdentity
     */
    public function setNip($nip)
    {
        $this->nip = $nip;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return CompanyIdentity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    protected function validate()
    {
        if (!$this->nip) {
            throw new \InvalidArgumentException('Missing NIP');
        }

        if (!$this->name) {
            throw new \InvalidArgumentException('Missing subject name');
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
            'NIP'           => $this->nip,
            'PelnaNazwa'    => $this->name,
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
        $object->nip    = $keyValue[Schema::NS.'NIP'];
        $object->name   = $keyValue[Schema::NS.'PelnaNazwa'];
        return $object;
    }

}
