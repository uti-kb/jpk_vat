
# Generator/parser JPK_VAT

Biblioteka umożliwia generowanie plików XML JPK_VAT dla dalszych procesów związanych z wysyłką przez bramkę eDokumenty oraz parser pliku XML do obiektów celem ewentualnych importów do systemów finansowych.

## Instalacja

    $ composer require sj_royd/xml-jpk-vat

## Tworzenie pliku XML

Generator XML domyślnie używa wariantu 3 JPK_VAT.

    <?php

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

## Parsowanie pliku XML do obiektów

Parser XML JPK_VAT potrafi automatycznie sprawdzić wariant i skonwertować dane do obiektów w wyznaczonym wariancie.

    <?php

    include '../vendor/autoload.php';

    use SJRoyd\JPK\VAT\JPK;

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

## Generowanie/parsowanie w konkretnym wariancie

Istnieje możliwość generowania i parsowania konkretnych wariantów JPK_VAT. W tym celu należy użyć jednego z poniższych namespace:

    SJRoyd\JPK\VAT\V1\JPK;
    SJRoyd\JPK\VAT\V2\JPK;
    SJRoyd\JPK\VAT\V3\JPK;

## Wiersz sprzedaży

Obiekt BuyRow `($jpk->newSellRow())` zawiera metody pozwalające na zapis i pobranie danych *(pola Kxx odpowiadają numerom pól na deklaracji VAT-7(19))*:

- Nr faktury
-- `getId()`
-- `setId($id)`
- NIP kontrahenta
-- `getContractorId()`
-- `setContractorId($nip)` niewymagane w przypadku dokumentów wewnętrznych
- Nazwa kontrahenta
-- `getContractorName()`
-- `setContractorName($name)` niewymagane w przypadku dokumentów wewnętrznych
- Adres kontrahenta
-- `getContractorAddress()`
-- `setContractorAddress($address)` niewymagane w przypadku dokumentów wewnętrznych
- Data sprzedaży *(wymagana gdy jest inna niż data wystawienia)*
-- `getSellDate($format = 'Y-m-d')` `format = null` wyda obiekt `DateTime` lub `null`
-- `setSellDate($sellDate)` przyjmuje zapis daty dozwolony przez `DateTime` lub gotową instancję `DateTime`
- Data wystawienia dokumentu
-- `getIssueDate($format = 'Y-m-d')` `format = null` wyda obiekt `DateTime`
-- `setIssueDate($issueDate)` przyjmuje zapis daty dozwolony przez `DateTime` lub gotową instancję `DateTime`

VAT-7(19) C. ROZLICZENIE PODATKU NALEŻNEGO

Istnieje możliwość ręcznego podania i pobrania pozycji przez metody `getK10()` i `setK10($val)`, gdzie K10 może przyjmować wartości K10-K39. Dla ułatwienia istnieją metody pozwalające bardziej intuicyjnie zapisywać wartości dla tych pozycji:

1. Dostawa towarów oraz świadczenie usług na terytorium kraju,
zwolnione od podatku (K10)
- `getTaxExempt()`
- `getTaxExempt($net)`
2. Dostawa towarów oraz świadczenie usług poza terytorium
kraju (K11)
2a. w tym świadczenie usług, o których mowa w art. 100
ust. 1 pkt 4 ustawy (K12)
- `getAbroadDelivery`
- `setAbroadDelivery($net, $netA = null)` `$netA` odpowiada za 2a.
3. Dostawa towarów oraz świadczenie usług na terytorium kraju,
opodatkowane stawką 0% (K13)
3a. w tym dostawa towarów, o której mowa w art. 129
ustawy (K14)
- `getTaxD()`
- `setTaxD($net, $netA = null)` `$netA` odpowiada za 3a.
4. Dostawa towarów oraz świadczenie usług na terytorium kraju,
opodatkowane stawką 5% (K15, K16)
- `getTaxC()`
- `setTaxC($net, $tax)`
5. Dostawa towarów oraz świadczenie usług na terytorium kraju,
opodatkowane stawką 7% albo 8% (K17, K18)
- `getTaxB()`
- `setTaxB($net, $tax)`
6. Dostawa towarów oraz świadczenie usług na terytorium kraju,
opodatkowane stawką 22% albo 23% (K19, K20)
- `getTaxA()`
- `setTaxA($net, $tax)`
7. Wewnątrzwspólnotowa dostawa towarów (K21)
- `getExportUE()`
- `setExportUE($net)`
8. Eksport towarów (K22)
- `getExport()`
- `setExport($net)`
9. Wewnątrzwspólnotowe nabycie towarów (K23, K24)
- `getImportUE()`
- `setImportUE($net, $tax)`
10. Import towarów podlegający rozliczeniu zgodnie z art. 33a
ustawy (K25, K26)
- `getImport_Art33a()`
- `setImport_Art33a($net, $tax)`
11. Import usług z wyłączeniem usług nabywanych od
podatników podatku od wartości dodanej, do których stosuje
się art. 28b ustawy (K27, K28)
- `getImport_Art28bExcept()`
- `setImport_Art28bExcept($net, $tax)`
12. Import usług nabywanych od podatników podatku od
wartości dodanej, do których stosuje się art. 28b ustawy (K29, K30)
- `getImport_Art28bOnly()`
- `setImport_Art28bOnly($net, $tax)`
13. Dostawa towarów oraz świadczenie usług, dla których
podatnikiem jest nabywca zgodnie z art. 17 ust. 1 pkt 7 lub 8
ustawy (wypełnia dostawca) (K31)
- `getReverseChargeSeller_Art17u1p7_8()`
- `setReverseChargeSeller_Art17u1p7_8($net)`
14. Dostawa towarów, dla których podatnikiem jest nabywca
zgodnie z art. 17 ust. 1 pkt 5 ustawy (wypełnia nabywca) (K32, K33)
- `getReverseChargeBuyer_Art17u1p5()`
- `setReverseChargeBuyer_Art17u1p5($net, $tax)`
15. Dostawa towarów oraz świadczenie usług, dla których
podatnikiem jest nabywca zgodnie z art. 17 ust. 1 pkt 7 lub 8
ustawy (wypełnia nabywca) (K34, K35)
- `getReverseChargeBuyer_Art17u1p7_8()`
- `setReverseChargeBuyer_Art17u1p7_8($net, $tax)`
16. Kwota podatku należnego od towarów i usług objętych spisem z natury, o którym mowa w art. 14 ust. 5 ustawy (K36)
- `getPsychicalInventoryTax()`
- `setPsychicalInventoryTax($tax)`
17. Zwrot odliczonej lub zwróconej kwoty wydatkowanej na zakup kas rejestrujących, o którym mowa w art. 111 ust. 6 ustawy (K37)
- `getCashRegisterTaxBack()`
- `setCashRegisterTaxBack($tax)`
18. Kwota podatku należnego od wewnątrzwspólnotowego nabycia środków transportu, wykazanego w poz. 24, podlegająca wpłacie w terminie, o którym mowa w art. 103 ust. 3, w związku z ust. 4 ustawy (K38)
- `getTransportImportUeTaxDue()`
- `setTransportImportUeTaxDue($tax)`
19. Kwota podatku od wewnątrzwspólnotowego nabycia paliw silnikowych, podlegająca wpłacie w terminach, o których mowa w art. 103 ust. 5a i 5b ustawy (K39)
- `getFuelImportUeTax()`
- `setFuelImportUeTax($tax)`

## Wiersz zakupu

Obiekt BuyRow `($jpk->newBuyRow())` zawiera metody pozwalające na zapis i pobranie danych *(pola Kxx odpowiadają numerom pól na deklaracji VAT-7(19))*:

- Nr faktury
-- `getId()`
-- `setId($id)`
- NIP kontrahenta
-- `getContractorId()`
-- `setContractorId($nip)` niewymagane w przypadku dokumentów wewnętrznych
- Nazwa kontrahenta
-- `getContractorName()`
-- `setContractorName($name)` niewymagane w przypadku dokumentów wewnętrznych
- Adres kontrahenta
-- `getContractorAddress()`
-- `setContractorAddress($address)` niewymagane w przypadku dokumentów wewnętrznych
- Data zakupu
-- `getBuyDate($format = 'Y-m-d')` `format = null` wyda obiekt `DateTime`
-- `setBuyDate($issueDate)` przyjmuje zapis daty dozwolony przez `DateTime` lub gotową instancję `DateTime`
- Data otrzymania dokumentu *(wymagana gdy jest inna niż data zakupu)*
-- `getReceiveDate($format = 'Y-m-d')` `format = null` wyda instancję `DateTime` lub `null`
-- `setReceiveDate($sellDate)` przyjmuje zapis daty dozwolony przez `DateTime` lub gotową instancję `DateTime`

VAT-7(19) D.2 NABYCIE TOWARÓW I USŁUG ORAZ PODATEK NALICZONY Z UWZGLĘDNIENIEM KOREKT
Nabycie towarów i usług zaliczanych u podatnika do środków
trwałych (K43, K44)
- `getFixedAssets()` zwróci `null` lub tablicę wartości pól K43 i K44
- `setFixedAssets($net, $tax)`

Nabycie towarów i usług pozostałych (K45, K46)
- `getOtherAssets()` zwróci `null` lub tablicę wartości pól K45 i K46
- `setOtherAssets($net, $tax)`

VAT-7(19) D.3. PODATEK NALICZONY – DO ODLICZENIA
Korekta podatku naliczonego od nabycia środków trwałych (K47)
- `getFixedAssetsTaxCorrection()` zwróci `null` lub wartość
- `setFixedAssetsTaxCorrection($tax)`

Korekta podatku naliczonego od pozostałych nabyć (K48)
- `getOtherAssetsTaxCorrection()` zwróci `null` lub wartość
- `setOtherAssetsTaxCorrection($tax)`

Korekta podatku naliczonego, o której mowa w art. 89b ust. 1 ustawy (K49)
- `getTaxCorrectionArt89bu1()` zwróci `null` lub wartość
- `setTaxCorrectionArt89bu1($tax)`

Korekta podatku naliczonego, o której mowa w art. 89b ust. 4 ustawy (K50)
- `getTaxCorrectionArt89bu4()` zwróci `null` lub wartość
- `setTaxCorrectionArt89bu4($tax)`
