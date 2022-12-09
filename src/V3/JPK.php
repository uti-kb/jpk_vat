<?php

namespace SJRoyd\JPK\VAT\V3;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\Service;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;

/**
 * @property Header $header
 * @property Company $company
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
            Schema::getFullNS('TNS') . 'Podmiot1' => $this->company
        ]);

        if($this->buyRows){
            foreach($this->buyRows as $row){
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

        if($this->sellRows){
            foreach($this->sellRows as $row){
                $writer->write([
                    Schema::getFullNS('TNS') . 'SprzedazWiersz' => $row
                ]);
                $this->sellControl->addCount();
                $this->sellControl->addTax($row->getTax());
            }
            $writer->write([
                Schema::getFullNS('TNS') . 'SprzedazCtrl' => $this->sellControl
            ]);
        }

    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * @return mixed
     */
    public static function xmlDeserialize(Reader $reader) {
        $jpk = new self();
        $children = $reader->parseInnerTree();

        foreach($children as $child) {
            $child['value'] instanceof Header       && $jpk->header      = $child['value'];
            $child['value'] instanceof Subject      && $jpk->company     = $child['value'];
            $child['value'] instanceof BuyRow       && $jpk->buyRows[]   = $child['value'];
            $child['value'] instanceof BuyControl   && $jpk->buyControl  = $child['value'];
            $child['value'] instanceof SellRow      && $jpk->sellRows[]  = $child['value'];
            $child['value'] instanceof SellControl  && $jpk->sellControl = $child['value'];
        }
        return $jpk;
    }

    public function __get($name)
    {
        return $this->$name;
    }


    /**
     * Generate JPK_VAT object to XML
     * @return string
     */
    public function generate()
    {
        $xmlService = new Service();
        $xmlService->namespaceMap = [
            Schema::getNS('TNS') => 'tns',
            Schema::getNS('XSI') => 'xsi',
            Schema::getNS('ETD') => 'etd',
            Schema::getNS('KCK') => 'kck',
            Schema::getNS('XSD') => 'xsd',
            Schema::getNS('XSL') => 'xsl',
            Schema::getNS('USR') => 'usr',
        ];

        return $xmlService->write(Schema::getFullNS('TNS') . 'JPK', function($xmlWriter) {
            $xmlWriter->writeAttribute('xsi:schemaLocation', 'http://jpk.mf.gov.pl/wzor/2017/11/13/1113/ http://www.mf.gov.pl/documents/764034/6145258/Schemat_JPK_VAT(3)_v1-1.xsd');
            $xmlWriter->write($this);
        });
    }

    /**
     * Parse XML to JPK_VAT object
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
            Schema::TNS.'ZakupWiersz'    => BuyRow::class,
            Schema::TNS.'ZakupCtrl'      => BuyControl::class,
            Schema::TNS.'SprzedazWiersz' => SellRow::class,
            Schema::TNS.'SprzedazCtrl'   => SellControl::class,
        ];

        return $service->parse($xml);
    }

}
