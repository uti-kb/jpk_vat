<?php

namespace SJRoyd\JPK\VAT\V2;

use Sabre\Xml\Element\KeyValue;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;

/**
 * @method SellRow setK10(float $value)
 * @method float getK10()
 * @method SellRow setK11(float $value)
 * @method float getK11()
 * @method SellRow setK12(float $value)
 * @method float getK12()
 * @method SellRow setK13(float $value)
 * @method float getK13()
 * @method SellRow setK14(float $value)
 * @method float getK14()
 * @method SellRow setK15(float $value)
 * @method float getK15()
 * @method SellRow setK16(float $value)
 * @method float getK16()
 * @method SellRow setK17(float $value)
 * @method float getK17()
 * @method SellRow setK18(float $value)
 * @method float getK18()
 * @method SellRow setK19(float $value)
 * @method float getK19()
 * @method SellRow setK20(float $value)
 * @method float getK20()
 * @method SellRow setK21(float $value)
 * @method float getK21()
 * @method SellRow setK22(float $value)
 * @method float getK22()
 * @method SellRow setK23(float $value)
 * @method float getK203)
 * @method SellRow setK24(float $value)
 * @method float getK24()
 * @method SellRow setK25(float $value)
 * @method float getK25()
 * @method SellRow setK26(float $value)
 * @method float getK26()
 * @method SellRow setK27(float $value)
 * @method float getK27()
 * @method SellRow setK28(float $value)
 * @method float getK28()
 * @method SellRow setK29(float $value)
 * @method float getK29()
 * @method SellRow setK30(float $value)
 * @method float getK30()
 * @method SellRow setK31(float $value)
 * @method float getK31()
 * @method SellRow setK32(float $value)
 * @method float getK32()
 * @method SellRow setK33(float $value)
 * @method float getK33()
 * @method SellRow setK34(float $value)
 * @method float getK34()
 * @method SellRow setK35(float $value)
 * @method float getK35()
 * @method SellRow setK36(float $value)
 * @method float getK36()
 * @method SellRow setK37(float $value)
 * @method float getK37()
 * @method SellRow setK38(float $value)
 * @method float getK38()
 * @method SellRow setK39(float $value)
 * @method float getK39()
 */
class SellRow implements XmlSerializable, XmlDeserializable
{
    use Helper\BuySellRow;
    use Helper\SellRow;

    /**
     * @var \DateTime
     */
    protected $sellDate;

    /**
     * @var \DateTime
     */
    protected $issueDate;

    /**
     * @var string
     */
    protected $address;

    protected $fields = [
        'K10' => null,
        'K11' => null,
        'K12' => null,
        'K13' => null,
        'K14' => null,
        'K15' => null,
        'K16' => null,
        'K17' => null,
        'K18' => null,
        'K19' => null,
        'K20' => null,
        'K21' => null,
        'K22' => null,
        'K23' => null,
        'K24' => null,
        'K25' => null,
        'K26' => null,
        'K27' => null,
        'K28' => null,
        'K29' => null,
        'K30' => null,
        'K31' => null,
        'K32' => null,
        'K33' => null,
        'K34' => null,
        'K35' => null,
        'K36' => null,
        'K37' => null,
        'K38' => null,
        'K39' => null
    ];

    protected static $fieldsControl = [
        'K10' => null,
        'K11' => null,
        'K12' => null,
        'K13' => null,
        'K14' => null,
        'K15' => null,
        'K16' => 1,
        'K17' => null,
        'K18' => 1,
        'K19' => null,
        'K20' => 1,
        'K21' => null,
        'K22' => null,
        'K23' => null,
        'K24' => 1,
        'K25' => null,
        'K26' => 1,
        'K27' => null,
        'K28' => 1,
        'K29' => null,
        'K30' => 1,
        'K31' => null,
        'K32' => null,
        'K33' => 1,
        'K34' => null,
        'K35' => 1,
        'K36' => null,
        'K37' => null,
        'K38' => -1,
        'K39' => -1
    ];

    /**
     * @return \DateTime|string
     */
    public function getSellDate($format = 'Y-m-d')
    {
        return ($format && $this->sellDate) ? $this->sellDate->format($format) : $this->sellDate;
    }

    /**
     *
     * @param \DateTime|string $sellDate
     * @return SellRow
     */
    public function setSellDate($sellDate)
    {
        $this->sellDate = $sellDate instanceof \DateTime
                ? $sellDate : new \DateTime($sellDate);
        return $this;
    }

    /**
     * @return \DateTime|string
     */
    public function getIssueDate($format = 'Y-m-d')
    {
        return $format ? $this->issueDate->format($format) : $this->issueDate;
    }

    /**
     * @param \DateTime|string $issueDate
     * @return SellRow
     */
    public function setIssueDate($issueDate)
    {
        $this->issueDate = $issueDate instanceof \DateTime
                ? $issueDate : new \DateTime($issueDate);
        return $this;
    }

    /**
     * C1 zw.
     * @return float
     */
    public function getTaxExempt()
    {
        return $this->getK10();
    }

    /**
     * C1 zw.
     * @param float $net
     * @return $this
     */
    public function setTaxExempt($net)
    {
        $this->setK10($net);
        return $this;
    }

    /**
     * C2 and C2a
     * @return array
     */
    public function getAbroadDelivery()
    {
        return
            is_null($this->getK11())
            ? null
            : [
                $this->getK11(),
                $this->getK12()
            ];
    }

    /**
     * C2 and C2a
     * @param float $net C2
     * @param float $netA C2a
     * @return $this
     */
    public function setAbroadDelivery($net, $netA = null)
    {
        $this->setK11($net);
        $this->setK12($netA);
        return $this;
    }

    /**
     * C3 and C3a 0%
     * @return array
     */
    public function getTaxD()
    {
        return
            is_null($this->getK13())
            ? null
            : [
                $this->getK13(),
                $this->getK14()
            ];
    }

    /**
     * C3 and C3a 0%
     * @param float $net C3
     * @param float $netA C3a
     * @return $this
     */
    public function setTaxD($net, $netA = null)
    {
        $this->setK13($net);
        $this->setK14($netA);
        return $this;
    }

    /**
     * C4 5%
     * @return array
     */
    public function getTaxC()
    {
        return
            is_null($this->getK15())
            ? null
            : [
                $this->getK15(),
                $this->getK16()
            ];
    }

    /**
     * C4 5%
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setTaxC($net, $tax)
    {
        $this->setK15($net);
        $this->setK16($tax);
        return $this;
    }

    /**
     * C5 8%
     * @return array
     */
    public function getTaxB()
    {
        return
            is_null($this->getK17())
            ? null
            : [
                $this->getK17(),
                $this->getK18()
            ];
    }

    /**
     * C5 8%
     * @param type $net
     * @param type $tax
     * @return $this
     */
    public function setTaxB($net, $tax)
    {
        $this->setK17($net);
        $this->setK18($tax);
        return $this;
    }

    /**
     * C6 23%
     * @return array
     */
    public function getTaxA()
    {
        return
            is_null($this->getK19())
            ? null
            : [
                $this->getK19(),
                $this->getK20()
            ];
    }

    /**
     * C6 23%
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setTaxA($net, $tax)
    {
        $this->setK19($net);
        $this->setK20($tax);
        return $this;
    }

    /**
     * C7
     * @return float
     */
    public function getExportUE()
    {
        return $this->getK21();
    }

    /**
     * C7
     * @param float $net
     * @return $this
     */
    public function setExportUE($net)
    {
        $this->setK21($net);
        return $this;
    }

    /**
     * C7
     * @return float
     */
    public function getExport()
    {
        return $this->getK22();
    }

    /**
     * C8
     * @param float $net
     * @return $this
     */
    public function setExport($net)
    {
        $this->setK22($net);
        return $this;
    }

    /**
     * C9
     * @return array
     */
    public function getImportUE()
    {
        return
            is_null($this->getK23())
            ? null
            : [
                $this->getK23(),
                $this->getK24()
            ];
    }

    /**
     * C9
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImportUE($net, $tax)
    {
        $this->setK23($net);
        $this->setK24($tax);
        return $this;
    }

    /**
     * C10
     * @return array
     */
    public function getImport_Art33a()
    {
        return
            is_null($this->getK25())
            ? null
            :[
                $this->getK25(),
                $this->getK26()
            ];
    }

    /**
     * C10
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art33a($net, $tax)
    {
        $this->setK25($net);
        $this->setK26($tax);
        return $this;
    }

    /**
     * C11
     * @return array
     */
    public function getImport_Art28bExcept()
    {
        return
            is_null($this->getK27())
            ? null
            :[
                $this->getK27(),
                $this->getK28()
            ];
    }

    /**
     * C11
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art28bExcept($net, $tax)
    {
        $this->setK27($net);
        $this->setK28($tax);
        return $this;
    }

    /**
     * C23
     * @return array
     */
    public function getImport_Art28bOnly()
    {
        return
            is_null($this->getK29())
            ? null
            :[
                $this->getK29(),
                $this->getK30()
            ];
    }

    /**
     * C12
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art28bOnly($net, $tax)
    {
        $this->setK29($net);
        $this->setK30($tax);
        return $this;
    }

    /**
     * C13
     * @return float
     */
    public function getReverseChargeSeller_Art17u1p7_8()
    {
        return $this->getK31();
    }

    /**
     * C13
     * @param float $net
     * @return $this
     */
    public function setReverseChargeSeller_Art17u1p7_8($net)
    {
        $this->setK31($net);
        return $this;
    }

    /**
     * C14
     * @return array
     */
    public function getReverseChargeBuyer_Art17u1p5()
    {
        return
            is_null($this->getK32())
            ? null
            : [
                $this->getK32(),
                $this->getK33()
            ];
    }

    /**
     * C14
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setReverseChargeBuyer_Art17u1p5($net, $tax)
    {
        $this->setK32($net);
        $this->setK33($tax);
        return $this;
    }

    /**
     * C15
     * @return array
     */
    public function getReverseChargeBuyer_Art17u1p7_8()
    {
        return
            is_null($this->getK34())
            ? null
            : [
                $this->getK34(),
                $this->getK35()
            ];
    }

    /**
     * C15
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setReverseChargeBuyer_Art17u1p7_8($net, $tax)
    {
        $this->setK34($net);
        $this->setK35($tax);
        return $this;
    }

    /**
     * C16
     * @return float
     */
    public function getPsychicalInventoryTax()
    {
        return $this->getK36();
    }

    /**
     * C16
     * @param float $tax
     * @return $this
     */
    public function setPsychicalInventoryTax($tax)
    {
        $this->setK36($tax);
        return $this;
    }

    /**
     * C17
     * @return float
     */
    public function getCashRegisterTaxBack()
    {
        return $this->getK37();
    }

    /**
     * C17
     * @param float $tax
     * @return $this
     */
    public function setCashRegisterTaxBack($tax)
    {
        $this->setK37($tax);
        return $this;
    }

    /**
     * C18
     * @return float
     */
    public function getTransportImportUeTaxDue()
    {
        return $this->getK38();
    }

    /**
     * C18
     * @param float $tax
     * @return $this
     */
    public function setTransportImportUeTaxDue($tax)
    {
        $this->setK38($tax);
        return $this;
    }

    /**
     * C19
     * @return float
     */
    public function getFuelImportUeTax()
    {
        return $this->getK39();
    }

    /**
     * C19
     * @param float $tax
     * @return $this
     */
    public function setFuelImportUeTax($tax)
    {
        $this->setK39($tax);
        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validate()
    {
        if (!$this->id) {
            throw new \InvalidArgumentException('Document ID missing');
        }

        if (!$this->issueDate) {
            throw new \InvalidArgumentException('Issue date missing');
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
        self::$index++;

        $this->validate();

        $writer->write([
            'LpSprzedazy'       => self::$index,
            'NrKontrahenta'     => $this->nip ?: 'brak',
            'NazwaKontrahenta'  => $this->name ?: 'brak',
            'AdresKontrahenta'  => $this->address ?: 'brak',
            'DowodSprzedazy'    => $this->id,
            'DataWystawienia'   => $this->issueDate->format('Y-m-d')
        ]);

        if($this->sellDate && $this->sellDate != $this->issueDate){
            $writer->write([
                'DataSprzedazy' => $this->sellDate->format('Y-m-d')
            ]);
        }

        $fields = array_filter($this->fields, function($val){
            return $val !== null;
        });
        $writer->write($fields);
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
        self::$index        = $keyValue[Schema::NS.'LpSprzedazy'];
        $object->nip        = $keyValue[Schema::NS.'NrKontrahenta'];
        $object->name       = $keyValue[Schema::NS.'NazwaKontrahenta'];
        $object->address    = $keyValue[Schema::NS.'AdresKontrahenta'];
        $object->id         = $keyValue[Schema::NS.'DowodSprzedazy'];
        $object->issueDate  = new \DateTime($keyValue[Schema::NS.'DataWystawienia']);
        $object->sellDate   =
                Helper\array_get($keyValue, Schema::NS.'DataSprzedazy', '\DateTime')
                ?
                : $object->sellDate;

        foreach($object->getFields() as $field => $_unused){
            $object->{'set'.$field}(Helper\array_get($keyValue, Schema::NS.$field));
        }

        return $object;
    }

}
