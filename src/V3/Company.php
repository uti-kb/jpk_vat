<?php

namespace SJRoyd\JPK\VAT\V3;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;
use function Sabre\Xml\Deserializer\keyValue;

class Company implements XmlSerializable, XmlDeserializable
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
     * @var string
     */
    protected $email;

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
     * @return Company
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
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Company
     */
    public function setEmail($email) {
        $this->email = $email;
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
        $this->email && $writer->write([
            'Email'         => $this->email
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
        $object->nip    = $keyValue[Schema::NS.'NIP'];
        $object->name   = $keyValue[Schema::NS.'PelnaNazwa'];
        $object->email  = Helper\array_get($keyValue, Schema::NS.'Email');
        return $object;
    }

}
