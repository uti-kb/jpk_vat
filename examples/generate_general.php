<?php

include '../vendor/autoload.php';

use SJRoyd\JPK\VAT\JPK;

$jpk = new JPK();
$jpk->header
        ->setRangeDates(2019, 2)
        ->setSystemName('Mój system informatyczny');

$jpk->company
        ->setName('Moja Firma')
        ->setNip('4165741358')
        ->setEmail('moja@firma.email');

$buy = $jpk->newBuyRow()
        ->setContractorName('Firma A')
        ->setContractorId('468713551')
        ->setContractorAddress('al. Późna 4/2, 45-678 Łękożna')
        ->setId('FV/01/19')
        ->setBuyDate('2019-01-12')
        ->setFixedAssets(150.42, 24.12);
$jpk->addBuyRow($buy);

$buy = $jpk->newBuyRow()
        ->setContractorName('Firma B')
        ->setContractorId('9514786631')
        ->setContractorAddress('ul. Łąka Żubra 5, 12-345 Mściłówek')
        ->setId('001254/19/S/S')
        ->setBuyDate('2019-01-08')
        ->setOtherAssets(315.20, 41.36);
$jpk->addBuyRow($buy);

$sell = $jpk->newSellRow()
        ->setContractorName('DROP TABLE `users`')
        ->setContractorId('6521475511')
        ->setContractorAddress('ul. Mnoga 8, 00-001 Bronki')
        ->setId('FV/SGD/1/2019')
        ->setSellDate('2019-01-09')
        ->setIssueDate('2019-01-10')
        ->setTaxExempt(50.12)
        ->setTaxA(100, 23)
        ->setTaxB(42, 12);
$jpk->addSellRow($sell);

print_r($jpk->generate());