<?php

include '../vendor/autoload.php';

use SJRoyd\JPK\VAT\V2\JPK;

$jpk = new JPK();
$jpk->header
        ->setRangeDates(2015, 2);
$jpk->header->setCorrection(); // if correction only

$jpk->company->identity
        ->setName('My Company')
        ->setNip('1111111111');

$jpk->company->address
        ->setCity('Wieśławice Małe') // miejscowość
        ->setCommunity('Miastko') // gmina
        ->setCountry('krakowiecki') // powiat
        ->setNumber('14/16') // numer na ulicy
        ->setPostal('Miastko') // miejsowość poczty
        ->setPostalCode('12-345') // kod pocztowy
        ->setStreet('ul. Piękna') // ulica
        ->setVoivodeship('Krakonoskie'); // województwo

$buy = $jpk->newBuyRow()
        ->setContractorName('Firma A')
        ->setContractorId('468713551')
        ->setContractorAddress('adres firmy A')
        ->setId('FV/01/19')
        ->setBuyDate('2019-01-12')
        ->setFixedAssets(150.42, 24.12);
$jpk->addBuyRow($buy);

$buy = $jpk->newBuyRow()
        ->setContractorName('Firma B')
        ->setContractorId('9514786631')
        ->setContractorAddress('adres firmy B')
        ->setId('001254/19/S/S')
        ->setBuyDate('2019-01-08')
        ->setOtherAssets(315.20, 41.36);
$jpk->addBuyRow($buy);

$sell = $jpk->newSellRow()
        ->setContractorName('Firma X')
        ->setContractorId('6521475511')
        ->setContractorAddress('adres firmy X')
        ->setId('FV/SGD/1/2019')
        ->setSellDate('2019-01-09')
        ->setIssueDate('2019-01-10')
        ->setTaxExempt(50.12)
        ->setTaxA(100, 23)
        ->setTaxB(42, 12);
$jpk->addSellRow($sell);

print_r($jpk->generate());