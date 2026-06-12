<?php

namespace SJRoyd\JPK\VAT\V7M;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;
use function Sabre\Xml\Deserializer\keyValue;

/**
 * ZakupWiersz - input tax evidence row
 *
 * @method BuyRow setK40(float $value)
 * @method float getK40()
 * @method BuyRow setK41(float $value)
 * @method float getK41()
 * @method BuyRow setK42(float $value)
 * @method float getK42()
 * @method BuyRow setK43(float $value)
 * @method float getK43()
 * @method BuyRow setK44(float $value)
 * @method float getK44()
 * @method BuyRow setK45(float $value)
 * @method float getK45()
 * @method BuyRow setK46(float $value)
 * @method float getK46()
 * @method BuyRow setK47(float $value)
 * @method float getK47()
 */
#[\AllowDynamicProperties]
class BuyRow implements XmlSerializable, XmlDeserializable
{
    use KsefDocument;
    use Row;

    const DOC_MK     = 'MK';
    const DOC_VAT_RR = 'VAT_RR';
    const DOC_WEW    = 'WEW';

    /**
     * Fields counted as plus in PodatekNaliczony
     * @var array
     */
    protected static $taxPlus = ['K_41', 'K_43', 'K_44', 'K_45', 'K_46', 'K_47'];

    /**
     * @var array
     */
    protected static $taxMinus = [];

    /**
     * @var \DateTime
     */
    protected $buyDate;

    /**
     * DataWplywu
     * @var \DateTime
     */
    protected $receivedDate;

    /**
     * DokumentZakupu: MK|VAT_RR|WEW
     * @var string
     */
    protected $documentType;

    /**
     * IMP - import of goods (art. 33a)
     * @var bool
     */
    protected $import = false;

    /**
     * ZakupVAT_Marza
     * @var float
     */
    protected $margin;

    protected $fields = [
        'K_40' => null, 'K_41' => null, 'K_42' => null, 'K_43' => null,
        'K_44' => null, 'K_45' => null, 'K_46' => null, 'K_47' => null,
    ];

    /**
     * @return \DateTime|string
     */
    public function getBuyDate($format = 'Y-m-d')
    {
        return $format ? $this->buyDate->format($format) : $this->buyDate;
    }

    /**
     * @param \DateTime|string $buyDate
     * @return BuyRow
     */
    public function setBuyDate($buyDate)
    {
        $this->buyDate = $buyDate instanceof \DateTime
                ? $buyDate : new \DateTime($buyDate);
        return $this;
    }

    /**
     * @return \DateTime|string
     */
    public function getReceiveDate($format = 'Y-m-d')
    {
        return ($format && $this->receivedDate) ? $this->receivedDate->format($format) : $this->receivedDate;
    }

    /**
     * DataWplywu
     * @param \DateTime|string $receiveDate
     * @return BuyRow
     */
    public function setReceiveDate($receiveDate)
    {
        $this->receivedDate = $receiveDate instanceof \DateTime
                ? $receiveDate : new \DateTime($receiveDate);
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * DokumentZakupu: MK|VAT_RR|WEW
     * @param string $type
     * @return BuyRow
     * @throws \InvalidArgumentException
     */
    public function setDocumentType($type)
    {
        if (!in_array($type, [self::DOC_MK, self::DOC_VAT_RR, self::DOC_WEW])) {
            throw new \InvalidArgumentException("Incorrect document type: {$type}");
        }
        $this->documentType = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isImport()
    {
        return $this->import;
    }

    /**
     * IMP - import of goods marker
     * @return BuyRow
     */
    public function setImport()
    {
        $this->import = true;
        return $this;
    }

    /**
     * K_40 and K_41
     * @return array|null
     */
    public function getFixedAssets()
    {
        return
            is_null($this->getK40())
            ? null
            : [
                $this->getK40(),
                $this->getK41()
            ];
    }

    /**
     * K_40 and K_41 - acquisition of fixed assets
     * @param float $net
     * @param float $tax
     * @return BuyRow
     */
    public function setFixedAssets($net, $tax)
    {
        $this->setK40($net);
        $this->setK41($tax);
        return $this;
    }

    /**
     * K_42 and K_43
     * @return array|null
     */
    public function getOtherAssets()
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
     * K_42 and K_43 - acquisition of other goods and services
     * @param float $net
     * @param float $tax
     * @return BuyRow
     */
    public function setOtherAssets($net, $tax)
    {
        $this->setK42($net);
        $this->setK43($tax);
        return $this;
    }

    /**
     * K_44
     * @return float
     */
    public function getFixedAssetsTaxCorrection()
    {
        return $this->getK44();
    }

    /**
     * K_44 - input tax correction on fixed assets (art. 90a-90c, art. 91)
     * @param float $tax
     * @return BuyRow
     */
    public function setFixedAssetsTaxCorrection($tax)
    {
        return $this->setK44($tax);
    }

    /**
     * K_45
     * @return float
     */
    public function getOtherAssetsTaxCorrection()
    {
        return $this->getK45();
    }

    /**
     * K_45 - input tax correction on other acquisitions (art. 90a-90c, art. 91)
     * @param float $tax
     * @return BuyRow
     */
    public function setOtherAssetsTaxCorrection($tax)
    {
        return $this->setK45($tax);
    }

    /**
     * K_46
     * @return float
     */
    public function getTaxCorrectionArt89bu1()
    {
        return $this->getK46();
    }

    /**
     * K_46 - input tax correction mentioned in art. 89b par. 1
     * @param float $tax
     * @return BuyRow
     */
    public function setTaxCorrectionArt89bu1($tax)
    {
        return $this->setK46($tax);
    }

    /**
     * K_47
     * @return float
     */
    public function getTaxCorrectionArt89bu4()
    {
        return $this->getK47();
    }

    /**
     * K_47 - input tax correction mentioned in art. 89b par. 4
     * @param float $tax
     * @return BuyRow
     */
    public function setTaxCorrectionArt89bu4($tax)
    {
        return $this->setK47($tax);
    }

    /**
     * ZakupVAT_Marza
     * @return float
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * ZakupVAT_Marza - value of goods and services acquired
     * for the margin scheme sale (art. 120 of the act)
     * @param float $value
     * @return BuyRow
     */
    public function setMargin($value)
    {
        $this->margin = $value;
        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function validate()
    {
        if (!$this->id) {
            throw new \InvalidArgumentException('Document ID missing');
        }

        if (!$this->buyDate) {
            throw new \InvalidArgumentException('Buy date missing');
        }

        $this->validateKsef();
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        $writer->write([
            Schema::getFullNS('TNS') . 'LpZakupu' => $this->lp
        ]);

        if ($this->countryCode) {
            $writer->write([
                Schema::getFullNS('TNS') . 'KodKrajuNadaniaTIN' => $this->countryCode
            ]);
        }

        $writer->write([
            Schema::getFullNS('TNS') . 'NrDostawcy'    => $this->nip ?: 'BRAK',
            Schema::getFullNS('TNS') . 'NazwaDostawcy' => $this->name ?: 'BRAK',
            Schema::getFullNS('TNS') . 'DowodZakupu'   => $this->id,
            Schema::getFullNS('TNS') . 'DataZakupu'    => $this->buyDate->format('Y-m-d')
        ]);

        if ($this->receivedDate && $this->receivedDate != $this->buyDate) {
            $writer->write([
                Schema::getFullNS('TNS') . 'DataWplywu' => $this->receivedDate->format('Y-m-d')
            ]);
        }

        $this->writeKsef($writer);

        if ($this->documentType) {
            $writer->write([
                Schema::getFullNS('TNS') . 'DokumentZakupu' => $this->documentType
            ]);
        }

        if ($this->import) {
            $writer->write([
                Schema::getFullNS('TNS') . 'IMP' => 1
            ]);
        }

        $this->writeFields($writer);

        if (!is_null($this->margin)) {
            $writer->write([
                Schema::getFullNS('TNS') . 'ZakupVAT_Marza' => sprintf('%.2f', $this->margin)
            ]);
        }
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
        $object->lp          = (int) $keyValue[Schema::TNS.'LpZakupu'];
        $object->countryCode = Helper\array_get($keyValue, Schema::TNS.'KodKrajuNadaniaTIN');
        $object->nip         = $keyValue[Schema::TNS.'NrDostawcy'];
        $object->name        = $keyValue[Schema::TNS.'NazwaDostawcy'];
        $object->id          = $keyValue[Schema::TNS.'DowodZakupu'];
        $object->buyDate     = new \DateTime($keyValue[Schema::TNS.'DataZakupu']);
        $object->receivedDate =
                Helper\array_get($keyValue, Schema::TNS.'DataWplywu', '\DateTime')
                ?
                : $object->receivedDate;

        $object->readKsef($keyValue);

        $object->documentType = Helper\array_get($keyValue, Schema::TNS.'DokumentZakupu');
        $object->import = (bool) Helper\array_get($keyValue, Schema::TNS.'IMP');

        $object->readFields($keyValue);

        $object->margin = Helper\array_get($keyValue, Schema::TNS.'ZakupVAT_Marza');

        return $object;
    }

}
