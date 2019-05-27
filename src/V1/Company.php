<?php

namespace SJRoyd\JPK\VAT\V1;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

/**
 * @property CompanyIdentity $identity
 * @property CompanyAddress $address
 */
class Company implements XmlSerializable, XmlDeserializable
{

    /**
     * @var CompanyIdentity
     */
    protected $identity;

    /**
     * @var CompanyAddress
     */
    protected $address;

    public function __construct()
    {
        $this->identity = new CompanyIdentity;
        $this->address  = new CompanyAddress;
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $writer->write([
            'IdentyfikatorPodmiotu' => $this->identity,
            'AdresPodmiotu'         => $this->address,
        ]);
    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * @return mixed
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $object = new self();
        $children = $reader->parseInnerTree();

        foreach($children as $child) {
            $child['value'] instanceof CompanyIdentity && $object->identity = $child['value'];
            $child['value'] instanceof CompanyAddress  && $object->address  = $child['value'];
        }
        return $object;
    }

    public function __get($name)
    {
        return $this->$name;
    }

}
