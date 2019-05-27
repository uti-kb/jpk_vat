<?php

include '../vendor/autoload.php';

use SJRoyd\JPK\VAT\V2\JPK;

$xml = <<<XML
<?xml version="1.0"?>
<JPK xmlns="http://jpk.mf.gov.pl/wzor/2016/10/26/10261/" xmlns:etd="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2016/01/25/eD/DefinicjeTypy/" xmlns:kck="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2013/05/23/eD/KodyCECHKRAJOW/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
 <Naglowek>
  <KodFormularza kodSystemowy="JPK_VAT (2)" wersjaSchemy="1-0">JPK_VAT</KodFormularza>
  <WariantFormularza>2</WariantFormularza>
  <CelZlozenia>2</CelZlozenia>
  <DataWytworzeniaJPK>2019-05-23T10:37:260</DataWytworzeniaJPK>
  <DataOd>2015-02-01</DataOd>
  <DataDo>2015-02-28</DataDo>
 </Naglowek>
 <Podmiot1>
  <IdentyfikatorPodmiotu>
   <NIP>1111111111</NIP>
   <PelnaNazwa>My Company</PelnaNazwa>
  </IdentyfikatorPodmiotu>
  <AdresPodmiotu>
   <KodKraju>PL</KodKraju>
   <Wojewodztwo>Krakonoskie</Wojewodztwo>
   <Powiat>krakowiecki</Powiat>
   <Gmina>Miastko</Gmina>
   <Ulica>ul. Piękna</Ulica>
   <NrDomu>14/16</NrDomu>
   <Miejscowosc>Wieśławice Małe</Miejscowosc>
   <KodPocztowy>12-345</KodPocztowy>
   <Poczta>Miastko</Poczta>
  </AdresPodmiotu>
 </Podmiot1>
 <ZakupWiersz typ="G">
  <LpZakupu>0</LpZakupu>
  <NrDostawcy>468713551</NrDostawcy>
  <NazwaDostawcy>Firma A</NazwaDostawcy>
  <AdresDostawcy>adres firmy A</AdresDostawcy>
  <DowodZakupu>FV/01/19</DowodZakupu>
  <DataZakupu>2019-01-12</DataZakupu>
  <K43>150.42</K43>
  <K44>24.12</K44>
 </ZakupWiersz>
 <ZakupWiersz typ="G">
  <LpZakupu>1</LpZakupu>
  <NrDostawcy>9514786631</NrDostawcy>
  <NazwaDostawcy>Firma B</NazwaDostawcy>
  <AdresDostawcy>adres firmy B</AdresDostawcy>
  <DowodZakupu>001254/19/S/S</DowodZakupu>
  <DataZakupu>2019-01-08</DataZakupu>
  <K45>315.20</K45>
  <K46>41.36</K46>
 </ZakupWiersz>
 <ZakupCtrl>
  <LiczbaWierszyZakupow>2</LiczbaWierszyZakupow>
  <PodatekNaliczony>65.48</PodatekNaliczony>
 </ZakupCtrl>
 <SprzedazWiersz typ="G">
  <LpSprzedazy>1</LpSprzedazy>
  <NrKontrahenta>6521475511</NrKontrahenta>
  <NazwaKontrahenta>Firma X</NazwaKontrahenta>
  <AdresKontrahenta>adres firmy X</AdresKontrahenta>
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
//print_r($jpk);
print_r($jpk->buyRows[0]->getFixedAssets());
print_r($jpk->sellRows[0]->getContractorAddress());

