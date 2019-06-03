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
    public function newSellRow()
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
    public function setSellRows(array $sellRows)
    {
        $this->sellRows = $sellRows;
        return $this;
    }

    /**
     * @param SellRow $sellRow
     * @return JPK
     */
    public function addSellRow(SellRow $sellRow)
    {
        $this->sellRows[] = $sellRow;
        return $this;
    }

    /**
     * @return BuyRow
     */
    public function newBuyRow()
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
    public function setBuyRows(array $buyRows)
    {
        $this->buyRows = $buyRows;
        return $this;
    }

    /**
     * @param BuyRow $buyRow
     * @return JPK
     */
    public function addBuyRow(BuyRow $buyRow)
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
            'Naglowek' => $this->header,
            'Podmiot1' => $this->company
        ]);

        if($this->buyRows){
            foreach($this->buyRows as $row){
                $writer->write([
                    'ZakupWiersz' => $row
                ]);
                $this->buyControl->addCount();
                $this->buyControl->addTax($row->getTax());
            }
            $writer->write([
                'ZakupCtrl' => $this->buyControl
            ]);
        }

        if($this->sellRows){
            foreach($this->sellRows as $row){
                $writer->write([
                    'SprzedazWiersz' => $row
                ]);
                $this->sellControl->addCount();
                $this->sellControl->addTax($row->getTax());
            }
            $writer->write([
                'SprzedazCtrl' => $this->sellControl
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
            Schema::getNS('NS') => '',
            Schema::getNS('ETD') => 'etd'
        ];
        return $xmlService->write('JPK', [
            $this
        ]);
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
            Schema::NS.'JPK'            => JPK::class,
            Schema::NS.'Naglowek'       => Header::class,
            Schema::NS.'Podmiot1'       => Company::class,
            Schema::NS.'ZakupWiersz'    => BuyRow::class,
            Schema::NS.'ZakupCtrl'      => BuyControl::class,
            Schema::NS.'SprzedazWiersz' => SellRow::class,
            Schema::NS.'SprzedazCtrl'   => SellControl::class,
        ];

        return $service->parse($xml);
    }

}
