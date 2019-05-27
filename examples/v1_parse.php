<?php

include '../vendor/autoload.php';

use SJRoyd\JPK\VAT\V1\JPK;

$xml = <<<XML
<?xml version="1.0"?>
<JPK xmlns="http://jpk.mf.gov.pl/wzor/2016/03/09/03094/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:etd="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2016/01/25/eD/DefinicjeTypy/" xmlns:kck="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2013/05/23/eD/KodyCECHKRAJOW/">
 <Naglowek>
  <KodFormularza kodSystemowy="JPK_VAT (1)" wersjaSchemy="1-0">JPK_VAT</KodFormularza>
  <WariantFormularza>1</WariantFormularza>
  <CelZlozenia>2</CelZlozenia>
  <DataWytworzeniaJPK>2019-05-23T11:33:050</DataWytworzeniaJPK>
  <DataOd>2015-02-01</DataOd>
  <DataDo>2015-02-28</DataDo>
  <DomyslnyKodWaluty>PLN</DomyslnyKodWaluty>
  <KodUrzedu>0000</KodUrzedu>
 </Naglowek>
 <Podmiot1>
  <IdentyfikatorPodmiotu>
   <NIP>1111111111</NIP>
   <PelnaNazwa>My Company</PelnaNazwa>
  </IdentyfikatorPodmiotu>
  <AdresPodmiotu>
   <etd:KodKraju>PL</etd:KodKraju>
   <etd:Wojewodztwo>Krakonoskie</etd:Wojewodztwo>
   <etd:Powiat>krakowiecki</etd:Powiat>
   <etd:Gmina>Miasto-coś</etd:Gmina>
   <etd:Ulica>ul. Piękna</etd:Ulica>
   <etd:NrDomu>14/16</etd:NrDomu>
   <etd:Miejscowosc>Jakieś miasto</etd:Miejscowosc>
   <etd:KodPocztowy>12-345</etd:KodPocztowy>
   <etd:Poczta>Miasto-coś</etd:Poczta>
  </AdresPodmiotu>
 </Podmiot1>
 <ZakupWiersz typ="G">
  <LpZakupu>0</LpZakupu>
  <NazwaWystawcy>Firma A</NazwaWystawcy>
  <AdresWystawcy>adres firmy A</AdresWystawcy>
  <NrIdWystawcy>468713551</NrIdWystawcy>
  <NrFaktury>FV/01/19</NrFaktury>
  <DataWplywuFaktury>2019-01-12</DataWplywuFaktury>
  <K42>150.42</K42>
  <K43>24.12</K43>
 </ZakupWiersz>
 <ZakupWiersz typ="G">
  <LpZakupu>1</LpZakupu>
  <NazwaWystawcy>Firma B</NazwaWystawcy>
  <AdresWystawcy>adres firmy B</AdresWystawcy>
  <NrIdWystawcy>9514786631</NrIdWystawcy>
  <NrFaktury>001254/19/S/S</NrFaktury>
  <DataWplywuFaktury>2019-01-08</DataWplywuFaktury>
  <K44>315.20</K44>
  <K45>41.36</K45>
 </ZakupWiersz>
 <ZakupCtrl>
  <LiczbaWierszyZakupow>2</LiczbaWierszyZakupow>
  <PodatekNaliczony>65.48</PodatekNaliczony>
 </ZakupCtrl>
 <SprzedazWiersz typ="G">
  <LpSprzedazy>1</LpSprzedazy>
  <DataWystawienia>2019-01-09</DataWystawienia>
  <NrDokumentu>FV/SGD/1/2019</NrDokumentu>
  <NazwaNabywcy>Firma X</NazwaNabywcy>
  <AdresNabywcy>adres firmy X</AdresNabywcy>
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

