<?php

namespace SJRoyd\JPK\VAT\V3;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;
use function Sabre\Xml\Deserializer\keyValue;

/**
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
 * @method SellRow setK50(float $value)
 * @method float getK50()
 */
class BuyRow implements XmlSerializable, XmlDeserializable
{
    use Helper\BuySellRow;
    use Helper\BuyRow;

    /**
     * @var \DateTime
     */
    protected $receivedDate;

    /**
     * @var \DateTime
     */
    protected $buyDate;

    protected $fields = [
        'K43' => null,
        'K44' => null,
        'K45' => null,
        'K46' => null,
        'K47' => null,
        'K48' => null,
        'K49' => null,
        'K50' => null
    ];

    protected static $fieldsControl = [
        'K43' => null,
        'K44' => 1,
        'K45' => null,
        'K46' => 1,
        'K47' => 1,
        'K48' => 1,
        'K49' => 1,
        'K50' => 1
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
     * @return string
     */
    public function getBuyDate($format = 'Y-m-d')
    {
        return $format ? $this->buyDate->format($format) : $this->buyDate;
    }

    /**
     * @param \DateTime|string $issueDate
     * @return SellRow
     */
    public function setBuyDate($issueDate)
    {
        $this->buyDate = $issueDate instanceof \DateTime
                ? $issueDate : new \DateTime($issueDate);
        return $this;
    }

    /**
     * @return array
     */
    public function getFixedAssets()
    {
        return
            is_null($this->getK43())
            ? null
            : [
                $this->getK43(),
                $this->getK44()
            ];
    }

    /**
     * @param float $nettoToDeduction
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setFixedAssets($nettoToDeduction, $taxToDeduction)
    {
        $this->setK43($nettoToDeduction);
        $this->setK44($taxToDeduction);
        return $this;
    }

    /**
     * @return array
     */
    public function getOtherAssets()
    {
        return
            is_null($this->getK45())
            ? null
            : [
                $this->getK45(),
                $this->getK46()
            ];
    }

    /**
     * @param float $nettoToDeduction
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setOtherAssets($nettoToDeduction, $taxToDeduction)
    {
        $this->setK45($nettoToDeduction);
        $this->setK46($taxToDeduction);
        return $this;
    }

    /**
     * @return float
     */
    public function getFixedAssetsTaxCorrection()
    {
        return $this->getK47();
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setFixedAssetsTaxCorrection($taxToDeduction)
    {
        $this->setK47($taxToDeduction);
        return $this;
    }

    /**
     * @return float
     */
    public function getOtherAssetsTaxCorrection()
    {
        return $this->getK48();
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setOtherAssetsTaxCorrection($taxToDeduction)
    {
        $this->setK48($taxToDeduction);
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxCorrectionArt89bu1()
    {
        return $this->setK49();
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setTaxCorrectionArt89bu1($taxToDeduction)
    {
        $this->setK49($taxToDeduction);
        return $this;
    }

    /**
     * @return float
     */
    public function getTaxCorrectionArt89bu4()
    {
        return $this->setK50();
    }

    /**
     * @param float $taxToDeduction
     * @return BuyRow
     */
    public function setTaxCorrectionArt89bu4($taxToDeduction)
    {
        $this->setK50($taxToDeduction);
        return $this;
    }

    protected function validate()
    {
        if ($this->id === null) {
            throw new \InvalidArgumentException('Document ID missing');
        }

        if ($this->buyDate === null) {
            throw new \InvalidArgumentException('Buy date missing');
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
            Schema::getFullNS('TNS') . 'LpZakupu'      => self::$index,
            Schema::getFullNS('TNS') . 'NrDostawcy'    => $this->nip ?: 'brak',
            Schema::getFullNS('TNS') . 'NazwaDostawcy' => $this->name ?: 'brak',
            Schema::getFullNS('TNS') . 'AdresDostawcy' => $this->address ?: 'brak',
            Schema::getFullNS('TNS') . 'DowodZakupu'   => $this->id,
            Schema::getFullNS('TNS') . 'DataZakupu'    => $this->buyDate->format('Y-m-d')
        ]);

        if($this->receivedDate && $this->receivedDate != $this->buyDate){
            $writer->write([
                Schema::getFullNS('TNS') . 'DataWplywu' => $this->receivedDate
            ]);
        }

        $fields = array_filter($this->fields, function($val){
            return $val !== null;
        });
        foreach($fields as $field => $value){
            $writer->write([
                Schema::getFullNS('TNS') . $field => sprintf('%.2f', $value)
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
        $keyValue = keyValue($reader);

        $object = new self();
        self::$index            = $keyValue[Schema::NS.'LpZakupu'];
        $object->nip            = $keyValue[Schema::NS.'NrDostawcy'];
        $object->name           = $keyValue[Schema::NS.'NazwaDostawcy'];
        $object->address        = $keyValue[Schema::NS.'AdresDostawcy'];
        $object->id             = $keyValue[Schema::NS.'DowodZakupu'];
        $object->buyDate        = new \DateTime($keyValue[Schema::NS.'DataZakupu']);
        $object->receivedDate   =
                Helper\array_get($keyValue, Schema::NS.'DataWplywu', '\DateTime')
                ?
                : $object->buyDate;

        foreach($object->getFields() as $field => $_unused){
            $object->{'set'.$field}(Helper\array_get($keyValue, Schema::NS.$field));
        }

        return $object;
    }

}
