<?php

include '../vendor/autoload.php';

use SJRoyd\JPK\VAT\V3\JPK;

$xml = <<<XML
<?xml version="1.0"?>
<JPK xmlns="http://jpk.mf.gov.pl/wzor/2017/11/13/1113/" xmlns:etd="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2016/01/25/eD/DefinicjeTypy/">
 <Naglowek>
  <KodFormularza kodSystemowy="JPK_VAT (3)" wersjaSchemy="1-1">JPK_VAT</KodFormularza>
  <WariantFormularza>3</WariantFormularza>
  <CelZlozenia>0</CelZlozenia>
  <DataWytworzeniaJPK>2019-05-21T09:36:46Z</DataWytworzeniaJPK>
  <DataOd>2019-02-01</DataOd>
  <DataDo>2019-02-28</DataDo>
  <NazwaSystemu>Mój system informatyczny</NazwaSystemu>
 </Naglowek>
 <Podmiot1>
  <NIP>4165741358</NIP>
  <PelnaNazwa>Moja Firma</PelnaNazwa>
  <Email>moja@firma.email</Email>
 </Podmiot1>
 <ZakupWiersz>
  <LpZakupu>0</LpZakupu>
  <NrDostawcy>468713551</NrDostawcy>
  <NazwaDostawcy>Firma A</NazwaDostawcy>
  <AdresDostawcy>al. Późna 4/2, 45-678 Łękożna</AdresDostawcy>
  <DowodZakupu>FV/01/19</DowodZakupu>
  <DataZakupu>2019-01-12</DataZakupu>
  <K43>150.42</K43>
  <K44>24.12</K44>
 </ZakupWiersz>
 <ZakupWiersz>
  <LpZakupu>1</LpZakupu>
  <NrDostawcy>9514786631</NrDostawcy>
  <NazwaDostawcy>Firma B</NazwaDostawcy>
  <AdresDostawcy>ul. Łąka Żubra 5, 12-345 Mściłówek</AdresDostawcy>
  <DowodZakupu>001254/19/S/S</DowodZakupu>
  <DataZakupu>2019-01-08</DataZakupu>
  <K45>315.20</K45>
  <K46>41.36</K46>
 </ZakupWiersz>
 <ZakupCtrl>
  <LiczbaWierszyZakupow>2</LiczbaWierszyZakupow>
  <PodatekNaliczony>65.48</PodatekNaliczony>
 </ZakupCtrl>
 <SprzedazWiersz>
  <LpSprzedazy>1</LpSprzedazy>
  <NrKontrahenta>6521475511</NrKontrahenta>
  <NazwaKontrahenta>DROP TABLE `users`</NazwaKontrahenta>
  <AdresKontrahenta>ul. Mnoga 8, 00-001 Bronki</AdresKontrahenta>
  <DowodSprzedazy>FV/SGD/1/2019</DowodSprzedazy>
  <DataWystawienia>2019-01-10</DataWystawienia>
  <DataSprzedazy>2019-01-09</DataSprzedazy>
  <K10>50.12</K10>
  <K17>42</K17>
  <K18>12</K18>
  <K19>100</K19>
  <K20>23</K20>
 </SprzedazWiersz>
 <SprzedazCtrl>
  <LiczbaWierszySprzedazy>1</LiczbaWierszySprzedazy>
  <PodatekNalezny>35.00</PodatekNalezny>
 </SprzedazCtrl>
</JPK>
XML;

$jpk = JPK::parse($xml);
print_r($jpk->buyRows[0]->getFixedAssets());
print_r($jpk->sellRows[0]->getContractorAddress());

