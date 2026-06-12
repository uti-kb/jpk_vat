<?php

include '../vendor/autoload.php';

use SJRoyd\JPK\VAT\JPK;

$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<tns:JPK xmlns:tns="http://crd.gov.pl/wzor/2025/12/19/14090/" xmlns:etd="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2022/09/13/eD/DefinicjeTypy/">
 <tns:Naglowek>
  <tns:KodFormularza kodSystemowy="JPK_V7M (3)" wersjaSchemy="1-0E">JPK_VAT</tns:KodFormularza>
  <tns:WariantFormularza>3</tns:WariantFormularza>
  <tns:DataWytworzeniaJPK>2026-03-10T09:36:46Z</tns:DataWytworzeniaJPK>
  <tns:NazwaSystemu>Mój system informatyczny</tns:NazwaSystemu>
  <tns:CelZlozenia poz="P_7">1</tns:CelZlozenia>
  <tns:KodUrzedu>0202</tns:KodUrzedu>
  <tns:Rok>2026</tns:Rok>
  <tns:Miesiac>2</tns:Miesiac>
 </tns:Naglowek>
 <tns:Podmiot1 rola="Podatnik">
  <tns:OsobaNiefizyczna>
   <tns:NIP>4165741358</tns:NIP>
   <tns:PelnaNazwa>Moja Firma</tns:PelnaNazwa>
   <tns:Email>moja@firma.email</tns:Email>
  </tns:OsobaNiefizyczna>
 </tns:Podmiot1>
 <tns:Ewidencja>
  <tns:SprzedazWiersz>
   <tns:LpSprzedazy>1</tns:LpSprzedazy>
   <tns:NrKontrahenta>6521475511</tns:NrKontrahenta>
   <tns:NazwaKontrahenta>Kontrahent X</tns:NazwaKontrahenta>
   <tns:DowodSprzedazy>FV/SGD/1/2026</tns:DowodSprzedazy>
   <tns:DataWystawienia>2026-02-10</tns:DataWystawienia>
   <tns:DataSprzedazy>2026-02-09</tns:DataSprzedazy>
   <tns:NrKSeF>4165741358-20260210-0A0B0C-D0E0F0-01</tns:NrKSeF>
   <tns:GTU_06>1</tns:GTU_06>
   <tns:TP>1</tns:TP>
   <tns:K_10>50.12</tns:K_10>
   <tns:K_17>42.00</tns:K_17>
   <tns:K_18>12.00</tns:K_18>
   <tns:K_19>100.00</tns:K_19>
   <tns:K_20>23.00</tns:K_20>
  </tns:SprzedazWiersz>
  <tns:SprzedazCtrl>
   <tns:LiczbaWierszySprzedazy>1</tns:LiczbaWierszySprzedazy>
   <tns:PodatekNalezny>35.00</tns:PodatekNalezny>
  </tns:SprzedazCtrl>
  <tns:ZakupWiersz>
   <tns:LpZakupu>1</tns:LpZakupu>
   <tns:NrDostawcy>9514786631</tns:NrDostawcy>
   <tns:NazwaDostawcy>Firma B</tns:NazwaDostawcy>
   <tns:DowodZakupu>001254/26/S/S</tns:DowodZakupu>
   <tns:DataZakupu>2026-02-08</tns:DataZakupu>
   <tns:BFK>1</tns:BFK>
   <tns:K_42>315.20</tns:K_42>
   <tns:K_43>41.36</tns:K_43>
  </tns:ZakupWiersz>
  <tns:ZakupCtrl>
   <tns:LiczbaWierszyZakupow>1</tns:LiczbaWierszyZakupow>
   <tns:PodatekNaliczony>41.36</tns:PodatekNaliczony>
  </tns:ZakupCtrl>
 </tns:Ewidencja>
</tns:JPK>
XML;

$jpk = JPK::parse($xml);

print_r($jpk->sellRows[0]->getKsefNumber());
echo PHP_EOL;
print_r($jpk->sellRows[0]->getGTU());
print_r($jpk->sellRows[0]->getTaxA());
var_dump($jpk->buyRows[0]->isNonKsefInvoice());
print_r($jpk->buyRows[0]->getOtherAssets());
