<?php

namespace SJRoyd\JPK\VAT\V7M;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;
use function Sabre\Xml\Deserializer\keyValue;

/**
 * SprzedazWiersz - output tax evidence row
 *
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
 * @method float getK23()
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
 * @method SellRow setK360(float $value)
 * @method float getK360()
 */
#[\AllowDynamicProperties]
class SellRow implements XmlSerializable, XmlDeserializable
{
    use KsefDocument;
    use Row;

    const DOC_RO  = 'RO';
    const DOC_WEW = 'WEW';
    const DOC_FP  = 'FP';

    /**
     * Procedure markers in the schema order
     * @var array
     */
    protected static $procedureList = [
        'WSTO_EE', 'IED', 'TP', 'TT_WNT', 'TT_D', 'MR_T', 'MR_UZ',
        'I_42', 'I_63', 'B_SPV', 'B_SPV_DOSTAWA', 'B_MPV_PROWIZJA',
    ];

    /**
     * Fields counted as plus in PodatekNalezny
     * @var array
     */
    protected static $taxPlus = [
        'K_16', 'K_18', 'K_20', 'K_24', 'K_26', 'K_28', 'K_30',
        'K_32', 'K_33', 'K_34',
    ];

    /**
     * Fields counted as minus in PodatekNalezny
     * @var array
     */
    protected static $taxMinus = ['K_35', 'K_36', 'K_360'];

    /**
     * @var \DateTime
     */
    protected $sellDate;

    /**
     * @var \DateTime
     */
    protected $issueDate;

    /**
     * TypDokumentu: RO|WEW|FP
     * @var string
     */
    protected $documentType;

    /**
     * GTU codes (1-13)
     * @var int[]
     */
    protected $gtu = [];

    /**
     * Procedure markers
     * @var string[]
     */
    protected $procedures = [];

    /**
     * KorektaPodstawyOpodt (art. 89a of the act)
     * @var bool
     */
    protected $badDebtCorrection = false;

    /**
     * TerminPlatnosci
     * @var \DateTime
     */
    protected $paymentDeadline;

    /**
     * DataZaplaty
     * @var \DateTime
     */
    protected $paymentDate;

    /**
     * SprzedazVAT_Marza
     * @var float
     */
    protected $margin;

    protected $fields = [
        'K_10' => null, 'K_11' => null, 'K_12' => null, 'K_13' => null,
        'K_14' => null, 'K_15' => null, 'K_16' => null, 'K_17' => null,
        'K_18' => null, 'K_19' => null, 'K_20' => null, 'K_21' => null,
        'K_22' => null, 'K_23' => null, 'K_24' => null, 'K_25' => null,
        'K_26' => null, 'K_27' => null, 'K_28' => null, 'K_29' => null,
        'K_30' => null, 'K_31' => null, 'K_32' => null, 'K_33' => null,
        'K_34' => null, 'K_35' => null, 'K_36' => null, 'K_360' => null,
    ];

    /**
     * @return \DateTime|string
     */
    public function getSellDate($format = 'Y-m-d')
    {
        return ($format && $this->sellDate) ? $this->sellDate->format($format) : $this->sellDate;
    }

    /**
     * @param \DateTime|string $sellDate
     * @return SellRow
     */
    public function setSellDate($sellDate): static
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
    public function setIssueDate($issueDate): static
    {
        $this->issueDate = $issueDate instanceof \DateTime
                ? $issueDate : new \DateTime($issueDate);
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
     * TypDokumentu: RO|WEW|FP
     * @param string $type
     * @return SellRow
     * @throws \InvalidArgumentException
     */
    public function setDocumentType($type): static
    {
        if (!in_array($type, [self::DOC_RO, self::DOC_WEW, self::DOC_FP])) {
            throw new \InvalidArgumentException("Incorrect document type: {$type}");
        }
        $this->documentType = $type;
        return $this;
    }

    /**
     * FP rows are counted in LiczbaWierszySprzedazy
     * but excluded from PodatekNalezny
     * @return bool
     */
    public function isFP(): bool
    {
        return $this->documentType == self::DOC_FP;
    }

    /**
     * @return int[]
     */
    public function getGTU()
    {
        return $this->gtu;
    }

    /**
     * GTU_01..GTU_13 markers, eg. setGTU(1, 12)
     * @param int ...$codes
     * @return SellRow
     * @throws \InvalidArgumentException
     */
    public function setGTU(...$codes): static
    {
        foreach ($codes as $code) {
            if ($code < 1 || $code > 13) {
                throw new \InvalidArgumentException("Incorrect GTU code: {$code}");
            }
        }
        $this->gtu = array_map(intval(...), $codes);
        sort($this->gtu);
        return $this;
    }

    /**
     * @return string[]
     */
    public function getProcedures()
    {
        return $this->procedures;
    }

    /**
     * Procedure markers, eg. setProcedure('TP', 'WSTO_EE')
     * @param string ...$codes
     * @return SellRow
     * @throws \InvalidArgumentException
     */
    public function setProcedure(...$codes): static
    {
        $codes = array_map(strtoupper(...), $codes);
        foreach ($codes as $code) {
            if (!in_array($code, self::$procedureList)) {
                throw new \InvalidArgumentException("Incorrect procedure marker: {$code}");
            }
        }
        $this->procedures = $codes;
        return $this;
    }

    /**
     * KorektaPodstawyOpodt - bad debt relief correction (art. 89a)
     * @param \DateTime|string $date payment deadline (art. 89a par. 1)
     *        or payment date (art. 89a par. 4)
     * @param bool $isPaymentDate true if $date is the payment date (DataZaplaty)
     * @return SellRow
     */
    public function setBadDebtCorrection($date, $isPaymentDate = false): static
    {
        $date = $date instanceof \DateTime ? $date : new \DateTime($date);
        $this->badDebtCorrection = true;
        $this->paymentDeadline = $isPaymentDate ? null : $date;
        $this->paymentDate = $isPaymentDate ? $date : null;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBadDebtCorrection()
    {
        return $this->badDebtCorrection;
    }

    /**
     * SprzedazVAT_Marza
     * @return float
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * SprzedazVAT_Marza - gross value of the margin scheme sale
     * (art. 119 and art. 120 of the act)
     * @param float $gross
     * @return SellRow
     */
    public function setMargin($gross): static
    {
        $this->margin = $gross;
        return $this;
    }

    /**
     * K_10
     * @return float
     */
    public function getTaxExempt()
    {
        return $this->getK10();
    }

    /**
     * K_10 - domestic supply exempt from tax
     * @param float $net
     * @return $this
     */
    public function setTaxExempt($net)
    {
        return $this->setK10($net);
    }

    /**
     * K_11 and K_12
     * @return array|null
     */
    public function getAbroadDelivery(): array
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
     * K_11 and K_12 - supply outside the country
     * @param float $net K_11
     * @param float $netA K_12 (art. 100 par. 1 pt 4)
     * @return $this
     */
    public function setAbroadDelivery($net, $netA = null): static
    {
        $this->setK11($net);
        $this->setK12($netA);
        return $this;
    }

    /**
     * K_13 and K_14
     * @return array|null
     */
    public function getTaxD(): array
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
     * K_13 and K_14 - domestic supply taxed at 0%
     * @param float $net K_13
     * @param float $netA K_14 (art. 129)
     * @return $this
     */
    public function setTaxD($net, $netA = null): static
    {
        $this->setK13($net);
        $this->setK14($netA);
        return $this;
    }

    /**
     * K_15 and K_16
     * @return array|null
     */
    public function getTaxC(): array
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
     * K_15 and K_16 - domestic supply taxed at 5%
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setTaxC($net, $tax): static
    {
        $this->setK15($net);
        $this->setK16($tax);
        return $this;
    }

    /**
     * K_17 and K_18
     * @return array|null
     */
    public function getTaxB(): array
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
     * K_17 and K_18 - domestic supply taxed at 7% or 8%
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setTaxB($net, $tax): static
    {
        $this->setK17($net);
        $this->setK18($tax);
        return $this;
    }

    /**
     * K_19 and K_20
     * @return array|null
     */
    public function getTaxA(): array
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
     * K_19 and K_20 - domestic supply taxed at 22% or 23%
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setTaxA($net, $tax): static
    {
        $this->setK19($net);
        $this->setK20($tax);
        return $this;
    }

    /**
     * K_21
     * @return float
     */
    public function getExportUE()
    {
        return $this->getK21();
    }

    /**
     * K_21 - intra-community supply of goods
     * @param float $net
     * @return $this
     */
    public function setExportUE($net)
    {
        return $this->setK21($net);
    }

    /**
     * K_22
     * @return float
     */
    public function getExport()
    {
        return $this->getK22();
    }

    /**
     * K_22 - export of goods
     * @param float $net
     * @return $this
     */
    public function setExport($net)
    {
        return $this->setK22($net);
    }

    /**
     * K_23 and K_24
     * @return array|null
     */
    public function getImportUE(): array
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
     * K_23 and K_24 - intra-community acquisition of goods
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImportUE($net, $tax): static
    {
        $this->setK23($net);
        $this->setK24($tax);
        return $this;
    }

    /**
     * K_25 and K_26
     * @return array|null
     */
    public function getImport_Art33a(): array
    {
        return
            is_null($this->getK25())
            ? null
            : [
                $this->getK25(),
                $this->getK26()
            ];
    }

    /**
     * K_25 and K_26 - import of goods settled acc. to art. 33a
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art33a($net, $tax): static
    {
        $this->setK25($net);
        $this->setK26($tax);
        return $this;
    }

    /**
     * K_27 and K_28
     * @return array|null
     */
    public function getImport_Art28bExcept(): array
    {
        return
            is_null($this->getK27())
            ? null
            : [
                $this->getK27(),
                $this->getK28()
            ];
    }

    /**
     * K_27 and K_28 - import of services excluding art. 28b services
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art28bExcept($net, $tax): static
    {
        $this->setK27($net);
        $this->setK28($tax);
        return $this;
    }

    /**
     * K_29 and K_30
     * @return array|null
     */
    public function getImport_Art28bOnly(): array
    {
        return
            is_null($this->getK29())
            ? null
            : [
                $this->getK29(),
                $this->getK30()
            ];
    }

    /**
     * K_29 and K_30 - import of art. 28b services
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setImport_Art28bOnly($net, $tax): static
    {
        $this->setK29($net);
        $this->setK30($tax);
        return $this;
    }

    /**
     * K_31 and K_32
     * @return array|null
     */
    public function getReverseChargeBuyer_Art17u1p5(): array
    {
        return
            is_null($this->getK31())
            ? null
            : [
                $this->getK31(),
                $this->getK32()
            ];
    }

    /**
     * K_31 and K_32 - supply for which the buyer is the taxpayer
     * (art. 17 par. 1 pt 5)
     * @param float $net
     * @param float $tax
     * @return $this
     */
    public function setReverseChargeBuyer_Art17u1p5($net, $tax): static
    {
        $this->setK31($net);
        $this->setK32($tax);
        return $this;
    }

    /**
     * K_33
     * @return float
     */
    public function getPsychicalInventoryTax()
    {
        return $this->getK33();
    }

    /**
     * K_33 - tax on goods covered by the physical inventory (art. 14 par. 5)
     * @param float $tax
     * @return $this
     */
    public function setPsychicalInventoryTax($tax)
    {
        return $this->setK33($tax);
    }

    /**
     * K_34
     * @return float
     */
    public function getCashRegisterTaxBack()
    {
        return $this->getK34();
    }

    /**
     * K_34 - return of the cash register purchase relief (art. 111 par. 6)
     * @param float $tax
     * @return $this
     */
    public function setCashRegisterTaxBack($tax)
    {
        return $this->setK34($tax);
    }

    /**
     * K_35
     * @return float
     */
    public function getTransportImportUeTaxDue()
    {
        return $this->getK35();
    }

    /**
     * K_35 - tax on the intra-community acquisition of means of transport
     * (art. 103 par. 3 and 4)
     * @param float $tax
     * @return $this
     */
    public function setTransportImportUeTaxDue($tax)
    {
        return $this->setK35($tax);
    }

    /**
     * K_36
     * @return float
     */
    public function getFuelImportUeTax()
    {
        return $this->getK36();
    }

    /**
     * K_36 - tax on the intra-community acquisition of goods
     * mentioned in art. 103 par. 5aa
     * @param float $tax
     * @return $this
     */
    public function setFuelImportUeTax($tax)
    {
        return $this->setK36($tax);
    }

    /**
     * K_360
     * @return float
     */
    public function getDepositTax()
    {
        return $this->getK360();
    }

    /**
     * K_360 - tax on unreturned deposits for beverage packaging
     * covered by the deposit system (art. 17b)
     * @param float $tax
     * @return $this
     */
    public function setDepositTax($tax)
    {
        return $this->setK360($tax);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validate(): void
    {
        if (!$this->id) {
            throw new \InvalidArgumentException('Document ID missing');
        }

        if (!$this->issueDate) {
            throw new \InvalidArgumentException('Issue date missing');
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
            Schema::getFullNS('TNS') . 'LpSprzedazy' => $this->lp
        ]);

        if ($this->countryCode) {
            $writer->write([
                Schema::getFullNS('TNS') . 'KodKrajuNadaniaTIN' => $this->countryCode
            ]);
        }

        $writer->write([
            Schema::getFullNS('TNS') . 'NrKontrahenta'    => $this->nip ?: 'BRAK',
            Schema::getFullNS('TNS') . 'NazwaKontrahenta' => $this->name ?: 'BRAK',
            Schema::getFullNS('TNS') . 'DowodSprzedazy'   => $this->id,
            Schema::getFullNS('TNS') . 'DataWystawienia'  => $this->issueDate->format('Y-m-d')
        ]);

        if ($this->sellDate && $this->sellDate != $this->issueDate) {
            $writer->write([
                Schema::getFullNS('TNS') . 'DataSprzedazy' => $this->sellDate->format('Y-m-d')
            ]);
        }

        $this->writeKsef($writer);

        if ($this->documentType) {
            $writer->write([
                Schema::getFullNS('TNS') . 'TypDokumentu' => $this->documentType
            ]);
        }

        foreach ($this->gtu as $code) {
            $writer->write([
                Schema::getFullNS('TNS') . sprintf('GTU_%02d', $code) => 1
            ]);
        }

        foreach (self::$procedureList as $code) {
            if (in_array($code, $this->procedures)) {
                $writer->write([
                    Schema::getFullNS('TNS') . $code => 1
                ]);
            }
        }

        if ($this->badDebtCorrection) {
            $writer->write([
                Schema::getFullNS('TNS') . 'KorektaPodstawyOpodt' => 1
            ]);
            $this->paymentDeadline && $writer->write([
                Schema::getFullNS('TNS') . 'TerminPlatnosci' => $this->paymentDeadline->format('Y-m-d')
            ]);
            $this->paymentDate && $writer->write([
                Schema::getFullNS('TNS') . 'DataZaplaty' => $this->paymentDate->format('Y-m-d')
            ]);
        }

        $this->writeFields($writer);

        if (!is_null($this->margin)) {
            $writer->write([
                Schema::getFullNS('TNS') . 'SprzedazVAT_Marza' => sprintf('%.2f', $this->margin)
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
        $object->lp          = (int) $keyValue[Schema::TNS.'LpSprzedazy'];
        $object->countryCode = Helper\array_get($keyValue, Schema::TNS.'KodKrajuNadaniaTIN');
        $object->nip         = $keyValue[Schema::TNS.'NrKontrahenta'];
        $object->name        = $keyValue[Schema::TNS.'NazwaKontrahenta'];
        $object->id          = $keyValue[Schema::TNS.'DowodSprzedazy'];
        $object->issueDate   = new \DateTime($keyValue[Schema::TNS.'DataWystawienia']);
        $object->sellDate    =
                Helper\array_get($keyValue, Schema::TNS.'DataSprzedazy', '\DateTime')
                ?
                : $object->sellDate;

        $object->readKsef($keyValue);

        $object->documentType = Helper\array_get($keyValue, Schema::TNS.'TypDokumentu');

        for ($code = 1; $code <= 13; $code++) {
            if (Helper\array_get($keyValue, Schema::TNS.sprintf('GTU_%02d', $code))) {
                $object->gtu[] = $code;
            }
        }

        foreach (self::$procedureList as $code) {
            if (Helper\array_get($keyValue, Schema::TNS.$code)) {
                $object->procedures[] = $code;
            }
        }

        if (Helper\array_get($keyValue, Schema::TNS.'KorektaPodstawyOpodt')) {
            $object->badDebtCorrection = true;
            $object->paymentDeadline = Helper\array_get($keyValue, Schema::TNS.'TerminPlatnosci', '\DateTime');
            $object->paymentDate = Helper\array_get($keyValue, Schema::TNS.'DataZaplaty', '\DateTime');
        }

        $object->readFields($keyValue);

        $object->margin = Helper\array_get($keyValue, Schema::TNS.'SprzedazVAT_Marza');

        return $object;
    }

}
