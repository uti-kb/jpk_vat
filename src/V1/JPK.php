<?php

namespace SJRoyd\JPK\VAT\V1;

use Sabre\Xml\Reader;
use Sabre\Xml\Service;
use Sabre\Xml\Writer;
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
    private \SJRoyd\JPK\VAT\V1\Header $header;

    private \SJRoyd\JPK\VAT\V1\Company $company;

    /**
     * @var SellRow[]
     */
    private ?array $sellRows = null;

    private \SJRoyd\JPK\VAT\V1\SellControl $sellControl;

    /**
     * @var BuyRow[]
     */
    private ?array $buyRows = null;

    private \SJRoyd\JPK\VAT\V1\BuyControl $buyControl;

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
     * @param SellRow[] $sellRows
     * @return JPK
     */
    public function setSellRows(array $sellRows): static
    {
        $this->sellRows = $sellRows;
        return $this;
    }

    /**
     * @param SellRow $sellRow
     * @return JPK
     */
    public function addSellRow(SellRow $sellRow): static
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
     * @param BuyRow[] $buyRows
     * @return JPK
     */
    public function setBuyRows(array $buyRows): static
    {
        $this->buyRows = $buyRows;
        return $this;
    }
    /**
     * @param BuyRow $buyRow
     * @return JPK
     */
    public function addBuyRow(BuyRow $buyRow): static
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
    public function xmlSerialize(Writer $writer): void
    {
        $writer->write([
            'Naglowek' => $this->header,
            'Podmiot1' => $this->company
        ]);

        if($this->buyRows){
            foreach($this->buyRows as $row){
                $writer->write([
                    [
                        'name' => 'ZakupWiersz',
                        'value' => $row,
                        'attributes' => [
                            'typ' => 'G'
                        ]
                    ]
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
                    [
                        'name' => 'SprzedazWiersz',
                        'value' => $row,
                        'attributes' => [
                            'typ' => 'G'
                        ]
                    ]
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
    public static function xmlDeserialize(Reader $reader)
    {
        $children = $reader->parseInnerTree();

        $object = new self();
        foreach($children as $child) {
            $child['value'] instanceof Header       && $object->header      = $child['value'];
            $child['value'] instanceof Company      && $object->company     = $child['value'];
            $child['value'] instanceof BuyRow       && $object->buyRows[]   = $child['value'];
            $child['value'] instanceof BuyControl   && $object->buyControl  = $child['value'];
            $child['value'] instanceof SellRow      && $object->sellRows[]  = $child['value'];
            $child['value'] instanceof SellControl  && $object->sellControl = $child['value'];
        }
        return $object;
    }


    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    /**
     * Generate JPK_VAT object to XML
     * @return string
     */
    public function generate(): string
    {
        $xmlService = new Service();
        $xmlService->namespaceMap = [
            Schema::getNS('NS') => '',
            Schema::getNS('XSD') => 'xsd',
            Schema::getNS('ETD') => 'etd',
            Schema::getNS('KCK') => 'kck',
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
            Schema::NS.'JPK'                    => JPK::class,
            Schema::NS.'Naglowek'               => Header::class,
            Schema::NS.'Podmiot1'               => Company::class,
            Schema::NS.'IdentyfikatorPodmiotu'  => CompanyIdentity::class,
            Schema::NS.'AdresPodmiotu'          => CompanyAddress::class,
            Schema::NS.'ZakupWiersz'            => BuyRow::class,
            Schema::NS.'ZakupCtrl'              => BuyControl::class,
            Schema::NS.'SprzedazWiersz'         => SellRow::class,
            Schema::NS.'SprzedazCtrl'           => SellControl::class,
        ];
        return $service->parse($xml);
    }
}
