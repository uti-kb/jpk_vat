<?php

namespace SJRoyd\JPK\VAT\V7M;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\Service;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

/**
 * JPK_V7M (3) - evidence and declaration with the KSeF invoice markers,
 * valid for settlement periods from February 2026
 *
 * @property Header $header
 * @property Company $company
 * @property Declaration|null $declaration
 * @property BuyRow[] $buyRows
 * @property SellRow[] $sellRows
 */
class JPK implements XmlSerializable, XmlDeserializable
{
    /**
     * @var Header
     */
    private $header;

    /**
     * @var Company
     */
    private $company;

    /**
     * @var Declaration
     */
    private $declaration;

    /**
     * @var SellRow[]
     */
    private $sellRows;

    /**
     * @var SellControl
     */
    private $sellControl;

    /**
     * @var BuyRow[]
     */
    private $buyRows;

    /**
     * @var BuyControl
     */
    private $buyControl;

    public function __construct()
    {
        $this->header = new Header;
        $this->company = new Company;
        $this->buyControl = new BuyControl;
        $this->sellControl = new SellControl;
    }

    /**
     * @return Declaration
     */
    public function newDeclaration(): Declaration
    {
        return new Declaration;
    }

    /**
     * @return Declaration|null
     */
    public function getDeclaration()
    {
        return $this->declaration;
    }

    /**
     * @param Declaration $declaration
     * @return JPK
     */
    public function setDeclaration(Declaration $declaration): JPK
    {
        $this->declaration = $declaration;
        return $this;
    }

    /**
     * @return SellRow
     */
    public function newSellRow(): SellRow
    {
        return new SellRow;
    }

    /**
     * @return SellRow[] $sellRows
     */
    public function getSellRows()
    {
        return $this->sellRows;
    }

    /**
     * @param SellRow[] $sellRows
     * @return JPK
     */
    public function setSellRows(array $sellRows): JPK
    {
        $this->sellRows = $sellRows;
        return $this;
    }

    /**
     * @param SellRow $sellRow
     * @return JPK
     */
    public function addSellRow(SellRow $sellRow): JPK
    {
        $this->sellRows[] = $sellRow;
        return $this;
    }

    /**
     * @return BuyRow
     */
    public function newBuyRow(): BuyRow
    {
        return new BuyRow;
    }

    /**
     * @return BuyRow[]
     */
    public function getBuyRows()
    {
        return $this->buyRows;
    }

    /**
     * @param BuyRow[] $buyRows
     * @return JPK
     */
    public function setBuyRows(array $buyRows): JPK
    {
        $this->buyRows = $buyRows;
        return $this;
    }

    /**
     * @param BuyRow $buyRow
     * @return JPK
     */
    public function addBuyRow(BuyRow $buyRow): JPK
    {
        $this->buyRows[] = $buyRow;
        return $this;
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
            Schema::getFullNS('TNS') . 'Naglowek' => $this->header,
            [
                'name' => Schema::getFullNS('TNS') . 'Podmiot1',
                'value' => $this->company,
                'attributes' => [
                    'rola' => 'Podatnik',
                ]
            ],
        ]);

        if ($this->declaration) {
            $writer->write([
                Schema::getFullNS('TNS') . 'Deklaracja' => $this->declaration
            ]);
        }

        $this->sellControl->setCount(0)->setTax(0);
        $this->buyControl->setCount(0)->setTax(0);

        $writer->write([
            Schema::getFullNS('TNS') . 'Ewidencja' => function(Writer $writer) {
                $lp = 1;
                foreach ((array) $this->sellRows as $row) {
                    $row->setLp($lp++);
                    $writer->write([
                        Schema::getFullNS('TNS') . 'SprzedazWiersz' => $row
                    ]);
                    $this->sellControl->addCount();
                    $row->isFP() || $this->sellControl->addTax($row->getTax());
                }
                $writer->write([
                    Schema::getFullNS('TNS') . 'SprzedazCtrl' => $this->sellControl
                ]);

                $lp = 1;
                foreach ((array) $this->buyRows as $row) {
                    $row->setLp($lp++);
                    $writer->write([
                        Schema::getFullNS('TNS') . 'ZakupWiersz' => $row
                    ]);
                    $this->buyControl->addCount();
                    $this->buyControl->addTax($row->getTax());
                }
                $writer->write([
                    Schema::getFullNS('TNS') . 'ZakupCtrl' => $this->buyControl
                ]);
            }
        ]);
    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * @return mixed
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $jpk = new self();
        $children = $reader->parseInnerTree();

        foreach ($children as $child) {
            $child['value'] instanceof Header      && $jpk->header      = $child['value'];
            $child['value'] instanceof Company     && $jpk->company     = $child['value'];
            $child['value'] instanceof Declaration && $jpk->declaration = $child['value'];

            if ($child['name'] == Schema::TNS.'Ewidencja' && is_array($child['value'])) {
                foreach ($child['value'] as $element) {
                    $element['value'] instanceof SellRow     && $jpk->sellRows[]  = $element['value'];
                    $element['value'] instanceof SellControl && $jpk->sellControl = $element['value'];
                    $element['value'] instanceof BuyRow      && $jpk->buyRows[]   = $element['value'];
                    $element['value'] instanceof BuyControl  && $jpk->buyControl  = $element['value'];
                }
            }
        }
        return $jpk;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Generate JPK_V7M object to XML
     * @return string
     */
    public function generate()
    {
        $xmlService = new Service();
        $xmlService->namespaceMap = [
            Schema::getNS('TNS') => 'tns',
            Schema::getNS('ETD') => 'etd',
        ];

        return $xmlService->write(Schema::getFullNS('TNS') . 'JPK', function($xmlWriter) {
            $xmlWriter->write($this);
        });
    }

    /**
     * Parse XML to JPK_V7M object
     * @param string $xml
     * @return JPK
     */
    public static function parse($xml)
    {
        $service = new Service();
        $service->elementMap = [
            Schema::TNS.'JPK'            => JPK::class,
            Schema::TNS.'Naglowek'       => Header::class,
            Schema::TNS.'Podmiot1'       => Company::class,
            Schema::TNS.'Deklaracja'     => Declaration::class,
            Schema::TNS.'SprzedazWiersz' => SellRow::class,
            Schema::TNS.'SprzedazCtrl'   => SellControl::class,
            Schema::TNS.'ZakupWiersz'    => BuyRow::class,
            Schema::TNS.'ZakupCtrl'      => BuyControl::class,
        ];

        return $service->parse($xml);
    }

}
