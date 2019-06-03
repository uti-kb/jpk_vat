<?php

namespace SJRoyd\JPK\VAT\V1;

use Sabre\Xml\Element\KeyValue;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;

class BuyControl implements XmlSerializable, XmlDeserializable
{
    use Helper\BuySellControl;
    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $writer->write([
            'LiczbaWierszyZakupow'  => $this->count,
            'PodatekNaliczony'      => sprintf('%.2f', $this->tax)
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
        $object->count  = $keyValue[Schema::NS.'LiczbaWierszyZakupow'];
        $object->buyVat = $keyValue[Schema::NS.'PodatekNaliczony'];
        return $object;
    }

}
