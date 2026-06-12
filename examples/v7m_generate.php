<?php

include '../vendor/autoload.php';

use SJRoyd\JPK\VAT\V7M\JPK;
use SJRoyd\JPK\VAT\V7M\SellRow;

$jpk = new JPK();
$jpk->header
        ->setPeriod(2026, 2)
        ->setOfficeCode('0202')
        ->setSystemName('Mój system informatyczny');

$jpk->company
        ->setName('Moja Firma')
        ->setNip('4165741358')
        ->setEmail('moja@firma.email')
        ->setPhone('+48600700800');

// faktura zakupu z KSeF
$buy = $jpk->newBuyRow()
        ->setContractorName('Firma A')
        ->setContractorId('4687135510')
        ->setId('FV/01/26')
        ->setBuyDate('2026-02-12')
        ->setKsefNumber('4687135510-20260212-010203-ABCDEF-04')
        ->setFixedAssets(150.42, 24.12);
$jpk->addBuyRow($buy);

// faktura zakupu spoza KSeF (papierowa/elektroniczna)
$buy = $jpk->newBuyRow()
        ->setContractorName('Firma B')
        ->setContractorId('9514786631')
        ->setId('001254/26/S/S')
        ->setBuyDate('2026-02-08')
        ->setReceiveDate('2026-02-10')
        ->setNonKsefInvoice()
        ->setOtherAssets(315.20, 41.36);
$jpk->addBuyRow($buy);

// faktura sprzedaży z KSeF, z kodem GTU i oznaczeniem procedury
$sell = $jpk->newSellRow()
        ->setContractorName('Kontrahent X')
        ->setContractorId('6521475511')
        ->setContractorCountryCode('PL')
        ->setId('FV/SGD/1/2026')
        ->setSellDate('2026-02-09')
        ->setIssueDate('2026-02-10')
        ->setKsefNumber('4165741358-20260210-0A0B0C-D0E0F0-01')
        ->setGTU(6, 12)
        ->setProcedure('TP')
        ->setTaxExempt(50.12)
        ->setTaxA(100, 23)
        ->setTaxB(42, 12);
$jpk->addSellRow($sell);

// raport okresowy z kasy fiskalnej (dowód inny niż faktura)
$sell = $jpk->newSellRow()
        ->setContractorName('BRAK')
        ->setContractorId('BRAK')
        ->setId('RO/02/2026')
        ->setIssueDate('2026-02-28')
        ->setOtherDocument()
        ->setDocumentType(SellRow::DOC_RO)
        ->setTaxA(813.01, 186.99);
$jpk->addSellRow($sell);

// część deklaracyjna VAT-7 (23)
$declaration = $jpk->newDeclaration()
        ->setP10(50)
        ->setP19(913)
        ->setP20(222)
        ->setP17(42)
        ->setP18(12)
        ->setOutputTax(234)          // P_38
        ->setP40(150)
        ->setP41(24)
        ->setP42(315)
        ->setP43(41)
        ->setInputTax(65)            // P_48
        ->setTaxToPay(169);          // P_51
$jpk->setDeclaration($declaration);

print_r($jpk->generate());
