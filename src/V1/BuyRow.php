<?php

namespace SJRoyd\JPK\VAT\V1;

use Sabre\Xml\Element\KeyValue;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;

/**
 * @method SellRow setK42(float $value)
 * @method float getK42()
 * @method SellRow setK43(float $value)
 * @method float getK43()
 * @method SellRow setK44(float $value)
 * @method float getK44()
 * @method SellRow setK45(float $value)
 * @method float getK45()
 * @method SellRow setK46(float $value)
 * @method float getK46()
 * @method SellRow setK47(float $value)
 * @method float getK47()
 * @method SellRow setK48(float $value)
 * @method float getK48()
 * @method SellRow setK49(float $value)
 * @method float getK49()
 */
class BuyRow implements XmlSerializable, XmlDeserializable
{
    use Helper\BuySellRow;
    use Helper\BuyRow;

    /**
     * @var \DateTime
     */
    protected $receivedDate;

    protected $fields = [
        'K42' => null,
        'K43' => null,
        'K44' => null,
        'K45' => null,
        'K46' => null,
        'K47' => null,
        'K48' => null,
        'K49' => null
    ];

    protected static $fieldsControl = [
        'K42' => null,
        'K43' => 1,
        'K44' => null,
        'K45' => 1,
        'K46' => 1,
        'K47' => 1,
        'K48' => 1,
        'K49' => 1
    ];


    /**
     * @return \DateTime|string
     */
    public function getReceiveDate($format = 'Y-m-d')
    {
        return ($format && $this->receivedDate) ? $this->receivedDate->format($format) : $this->receivedDate;
    }

    /**
     *
     * @param \DateTime|string $sellDate
     * @return SellRow
     */
    public function setReceiveDate($sellDate)
    {
        $this->receivedDate = $sellDate instanceof \DateTime
                ? $sellDate : new \DateTime($sellDate);
        return $this;
    }

    /**
     * @return array
     */
    public function getFixedAssets()
    {
        return
            is_null($this->getK42())
            ? null
            : [
                $this->getK42(),
                $this->getK43()
            ];
    }

    /**
     * @param float $nettoToDeduction
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setFixedAssets($nettoToDeduction, $taxToDeduction)
    {
        $this->setK42($nettoToDeduction);
        $this->setK43($taxToDeduction);
        return $this;
    }

    /**
     * @return array
     */
    public function getOtherAssets()
    {
        return
            is_null($this->getK44())
            ? null
            : [
                $this->getK44(),
                $this->getK45()
            ];
    }

    /**
     * @param float $nettoToDeduction
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setOtherAssets($nettoToDeduction, $taxToDeduction)
    {
        $this->setK44($nettoToDeduction);
        $this->setK45($taxToDeduction);
        return $this;
    }

    /**
     * @return float
     */
    public function getFixedAssetsTaxCorrection()
    {
        return $this->getK46();
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setFixedAssetsTaxCorrection($taxToDeduction)
    {
        $this->setK46($taxToDeduction);
        return $this;
    }

    /**
     * @return float
     */
    public function getOtherAssetsTaxCorrection()
    {
        return $this->getK47();
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setOtherAssetsTaxCorrection($taxToDeduction)
    {
        $this->setK47($taxToDeduction);
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxCorrectionArt89bu1()
    {
        return $this->setK48();
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setTaxCorrectionArt89bu1($taxToDeduction)
    {
        $this->setK48($taxToDeduction);
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxCorrectionArt89bu4()
    {
        return $this->setK49();
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setTaxCorrectionArt89bu4($taxToDeduction)
    {
        $this->setK49($taxToDeduction);
        return $this;
    }

    protected function validate()
    {
        if ($this->id === null) {
            throw new \InvalidArgumentException('Document ID missing');
        }

        if ($this->nip === null) {
            throw new \InvalidArgumentException('Missing NIP');
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
            'LpZakupu'      => self::$index,
            'NazwaWystawcy' => $this->name ?: 'brak',
            'AdresWystawcy' => $this->address ?: 'brak',
            'NrIdWystawcy'  => $this->nip,
            'NrFaktury'     => $this->id
        ]);

        if($this->receivedDate !== null){
            $writer->write([
                'DataWplywuFaktury' => $this->receivedDate->format('Y-m-d')
            ]);
        }

        $fields = array_filter($this->fields, function($val){
            return $val !== null;
        });
        foreach($fields as $field => $value){
            $writer->write([
                $field => sprintf('%.2f', $value)
            ]);
        }

        self::$index++;
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
        self::$index            = $keyValue[Schema::NS.'LpZakupu'];
        $object->nip            = $keyValue[Schema::NS.'NrIdWystawcy'];
        $object->name           = $keyValue[Schema::NS.'NazwaWystawcy'];
        $object->address        = $keyValue[Schema::NS.'AdresWystawcy'];
        $object->id             = $keyValue[Schema::NS.'NrFaktury'];
        $object->receivedDate   =
                Helper\array_get($keyValue, Schema::NS.'DataWplywuFaktury', '\DateTime');

        foreach($object->getFields() as $field => $_unused){
            $object->{'set'.$field}(Helper\array_get($keyValue, Schema::NS.$field));
        }

        return $object;
    }

}
