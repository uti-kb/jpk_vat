
# Generator/parser JPK_VAT / JPK_V7M

Biblioteka umożliwia generowanie plików XML JPK_V7M (z obsługą KSeF) oraz historycznych JPK_VAT dla dalszych procesów związanych z wysyłką przez bramkę eDokumenty oraz parser pliku XML do obiektów celem ewentualnych importów do systemów finansowych.

Obsługiwane warianty:

- **JPK_V7M (3)**, wersja schemy 1-0E — ewidencja + deklaracja VAT-7(23), z oznaczeniami faktur w Krajowym Systemie e-Faktur (KSeF); obowiązuje dla rozliczeń od lutego 2026
- JPK_VAT (1), (2), (3) — warianty historyczne (2016–2017)

## Instalacja

    $ composer require sj_royd/xml-jpk-vat

## Tworzenie pliku XML

Generator XML domyślnie tworzy plik JPK_V7M (3).

    <?php

    use SJRoyd\JPK\VAT\JPK;
    use SJRoyd\JPK\VAT\V7M\SellRow;

    $jpk = new JPK();
    $jpk->header
            ->setPeriod(2026, 2)
            ->setOfficeCode('0202')
            ->setSystemName('Mój system informatyczny');

    $jpk->company
            ->setName('Moja Firma')
            ->setNip('4165741358')
            ->setEmail('moja@firma.email');

    // faktura zakupu z KSeF
    $buy = $jpk->newBuyRow()
            ->setContractorName('Firma A')
            ->setContractorId('4687135510')
            ->setId('FV/01/26')
            ->setBuyDate('2026-02-12')
            ->setKsefNumber('4687135510-20260212-010203-ABCDEF-04')
            ->setFixedAssets(150.42, 24.12);
    $jpk->addBuyRow($buy);

    // faktura zakupu spoza KSeF (papierowa lub elektroniczna)
    $buy = $jpk->newBuyRow()
            ->setContractorName('Firma B')
            ->setContractorId('9514786631')
            ->setId('001254/26/S/S')
            ->setBuyDate('2026-02-08')
            ->setNonKsefInvoice()
            ->setOtherAssets(315.20, 41.36);
    $jpk->addBuyRow($buy);

    // faktura sprzedaży z KSeF, z kodem GTU i oznaczeniem procedury
    $sell = $jpk->newSellRow()
            ->setContractorName('Kontrahent X')
            ->setContractorId('6521475511')
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

    // część deklaracyjna VAT-7 (23)
    $declaration = $jpk->newDeclaration()
            ->setP10(50)
            ->setP19(913)
            ->setP20(222)
            ->setOutputTax(234)     // P_38
            ->setInputTax(65)       // P_48
            ->setTaxToPay(169);     // P_51
    $jpk->setDeclaration($declaration);

    print_r($jpk->generate());

## Parsowanie pliku XML do obiektów

Parser XML potrafi automatycznie sprawdzić wariant (w tym JPK_V7M po atrybucie `kodSystemowy`) i skonwertować dane do obiektów w wyznaczonym wariancie.

    <?php

    use SJRoyd\JPK\VAT\JPK;

    $jpk = JPK::parse($xml);
    print_r($jpk->sellRows[0]->getKsefNumber());
    print_r($jpk->buyRows[0]->getFixedAssets());

## Generowanie/parsowanie w konkretnym wariancie

Istnieje możliwość generowania i parsowania konkretnych wariantów. W tym celu należy użyć jednego z poniższych namespace:

    SJRoyd\JPK\VAT\V7M\JPK;    // JPK_V7M (3) z KSeF - bieżący
    SJRoyd\JPK\VAT\V1\JPK;     // JPK_VAT (1) - historyczny
    SJRoyd\JPK\VAT\V2\JPK;     // JPK_VAT (2) - historyczny
    SJRoyd\JPK\VAT\V3\JPK;     // JPK_VAT (3) - historyczny

# JPK_V7M (3)

## Nagłówek

Obiekt Header `($jpk->header)`:

- `setPeriod($year, $month)` okres rozliczeniowy (Rok, Miesiac); rok od 2026
- `setOfficeCode($code)` czterocyfrowy kod urzędu skarbowego (KodUrzedu)
- `setSystemName($name)` nazwa systemu (NazwaSystemu, opcjonalna)
- `setCorrection()` oznacza plik jako korektę (CelZlozenia = 2)
- gettery: `getYear()`, `getMonth()`, `getOfficeCode()`, `getSystemName()`, `getReasonOfSubmission()`

## Podmiot

Obiekt Company `($jpk->company)` reprezentuje Podmiot1. Podmiotem może być firma (OsobaNiefizyczna) albo osoba fizyczna (OsobaFizyczna):

- `setNip($nip)`
- `setName($fullName)` pełna nazwa — podmiot staje się osobą niefizyczną
- `setPerson($firstName, $lastName, $birthDate)` — podmiot staje się osobą fizyczną
- `setEmail($email)` wymagany
- `setPhone($phone)` opcjonalny

## Oznaczenia KSeF

W każdym wierszu ewidencji (sprzedaży i zakupu) **wymagane jest dokładnie jedno** z oznaczeń dotyczących występowania faktury w Krajowym Systemie e-Faktur. Ustawienie jednego oznaczenia usuwa pozostałe.

| Element XML | Metoda | Znaczenie |
|---|---|---|
| `NrKSeF` | `setKsefNumber($nr)` | numer identyfikujący fakturę w KSeF (walidowany wzorcem) |
| `OFF` | `setKsefOffline()` | faktura wystawiona w trybie offline (art. 106nf ustawy), bez numeru KSeF na dzień złożenia |
| `BFK` | `setNonKsefInvoice()` | faktura elektroniczna lub papierowa wystawiona poza KSeF |
| `DI` | `setOtherDocument()` | dowód inny niż faktura |

Odczyt: `getKsefNumber()`, `isKsefOffline()`, `isNonKsefInvoice()`, `isOtherDocument()`.

## Wiersz sprzedaży

Obiekt SellRow `($jpk->newSellRow())` — pola `K_xx` odpowiadają polom ewidencji sprzedaży JPK_V7M:

- Nr dokumentu: `getId()` / `setId($id)`
- NIP kontrahenta: `getContractorId()` / `setContractorId($nip)` (`BRAK` gdy nie podano)
- Nazwa kontrahenta: `getContractorName()` / `setContractorName($name)`
- Kod kraju nadania TIN: `getContractorCountryCode()` / `setContractorCountryCode($code)`
- Data wystawienia: `getIssueDate($format = 'Y-m-d')` / `setIssueDate($issueDate)`
- Data sprzedaży *(wymagana gdy inna niż data wystawienia)*: `getSellDate($format = 'Y-m-d')` / `setSellDate($sellDate)`
- Oznaczenie KSeF *(wymagane)* — patrz wyżej
- Typ dokumentu (TypDokumentu): `setDocumentType($type)` — `RO` (raport okresowy z kasy), `WEW` (dokument wewnętrzny), `FP` (faktura do paragonu); stałe `SellRow::DOC_RO`, `DOC_WEW`, `DOC_FP`
- Kody grup towarowych GTU_01–GTU_13: `setGTU(6, 12)` / `getGTU()`
- Oznaczenia procedur: `setProcedure('TP', 'WSTO_EE')` / `getProcedures()` — dozwolone: `WSTO_EE`, `IED`, `TP`, `TT_WNT`, `TT_D`, `MR_T`, `MR_UZ`, `I_42`, `I_63`, `B_SPV`, `B_SPV_DOSTAWA`, `B_MPV_PROWIZJA`
- Korekta podstawy opodatkowania (ulga na złe długi, art. 89a): `setBadDebtCorrection($date, $isPaymentDate = false)` — `$date` to termin płatności (art. 89a ust. 1) lub data zapłaty (art. 89a ust. 4 przy `$isPaymentDate = true`)
- Sprzedaż VAT marża: `setMargin($gross)` / `getMargin()` (SprzedazVAT_Marza)

Pozycje kwotowe można ustawiać ręcznie metodami `setK10($val)`…`setK36($val)`, `setK360($val)` (mapowane na pola `K_10`–`K_36`, `K_360`) lub metodami intuicyjnymi:

1. Dostawa zwolniona od podatku (K_10): `setTaxExempt($net)` / `getTaxExempt()`
2. Dostawa poza terytorium kraju (K_11, K_12): `setAbroadDelivery($net, $netA = null)` / `getAbroadDelivery()`
3. Dostawa opodatkowana stawką 0% (K_13, K_14): `setTaxD($net, $netA = null)` / `getTaxD()`
4. Dostawa opodatkowana stawką 5% (K_15, K_16): `setTaxC($net, $tax)` / `getTaxC()`
5. Dostawa opodatkowana stawką 7% albo 8% (K_17, K_18): `setTaxB($net, $tax)` / `getTaxB()`
6. Dostawa opodatkowana stawką 22% albo 23% (K_19, K_20): `setTaxA($net, $tax)` / `getTaxA()`
7. Wewnątrzwspólnotowa dostawa towarów (K_21): `setExportUE($net)` / `getExportUE()`
8. Eksport towarów (K_22): `setExport($net)` / `getExport()`
9. Wewnątrzwspólnotowe nabycie towarów (K_23, K_24): `setImportUE($net, $tax)` / `getImportUE()`
10. Import towarów zgodnie z art. 33a (K_25, K_26): `setImport_Art33a($net, $tax)` / `getImport_Art33a()`
11. Import usług z wyłączeniem art. 28b (K_27, K_28): `setImport_Art28bExcept($net, $tax)` / `getImport_Art28bExcept()`
12. Import usług z art. 28b (K_29, K_30): `setImport_Art28bOnly($net, $tax)` / `getImport_Art28bOnly()`
13. Dostawa, dla której podatnikiem jest nabywca zgodnie z art. 17 ust. 1 pkt 5 (K_31, K_32): `setReverseChargeBuyer_Art17u1p5($net, $tax)` / `getReverseChargeBuyer_Art17u1p5()`
14. Podatek od spisu z natury, art. 14 ust. 5 (K_33): `setPsychicalInventoryTax($tax)` / `getPsychicalInventoryTax()`
15. Zwrot ulgi na kasy rejestrujące, art. 111 ust. 6 (K_34): `setCashRegisterTaxBack($tax)` / `getCashRegisterTaxBack()`
16. Podatek od WNT środków transportu, art. 103 ust. 3 i 4 (K_35): `setTransportImportUeTaxDue($tax)` / `getTransportImportUeTaxDue()`
17. Podatek od WNT towarów z art. 103 ust. 5aa (K_36): `setFuelImportUeTax($tax)` / `getFuelImportUeTax()`
18. Podatek od niezwróconej kaucji w systemie kaucyjnym, art. 17b (K_360): `setDepositTax($tax)` / `getDepositTax()`

Sumy kontrolne (SprzedazCtrl) liczone są automatycznie podczas generowania; wiersze z typem dokumentu `FP` są wliczane do liczby wierszy, ale wyłączone z sumy podatku należnego.

## Wiersz zakupu

Obiekt BuyRow `($jpk->newBuyRow())`:

- Nr dokumentu: `getId()` / `setId($id)`
- NIP dostawcy: `getContractorId()` / `setContractorId($nip)`
- Nazwa dostawcy: `getContractorName()` / `setContractorName($name)`
- Kod kraju nadania TIN: `getContractorCountryCode()` / `setContractorCountryCode($code)`
- Data zakupu: `getBuyDate($format = 'Y-m-d')` / `setBuyDate($buyDate)`
- Data wpływu *(wymagana gdy inna niż data zakupu)*: `getReceiveDate($format = 'Y-m-d')` / `setReceiveDate($receiveDate)`
- Oznaczenie KSeF *(wymagane)* — patrz wyżej
- Oznaczenie dowodu zakupu (DokumentZakupu): `setDocumentType($type)` — `MK` (metoda kasowa), `VAT_RR`, `WEW`; stałe `BuyRow::DOC_MK`, `DOC_VAT_RR`, `DOC_WEW`
- Import towarów, art. 33a (IMP): `setImport()` / `isImport()`
- Zakup VAT marża: `setMargin($value)` / `getMargin()` (ZakupVAT_Marza)

Pozycje kwotowe (`setK40($val)`…`setK47($val)`, mapowane na `K_40`–`K_47`) lub metody intuicyjne:

- Nabycie środków trwałych (K_40, K_41): `setFixedAssets($net, $tax)` / `getFixedAssets()`
- Nabycie pozostałych towarów i usług (K_42, K_43): `setOtherAssets($net, $tax)` / `getOtherAssets()`
- Korekta podatku naliczonego od środków trwałych (K_44): `setFixedAssetsTaxCorrection($tax)` / `getFixedAssetsTaxCorrection()`
- Korekta podatku naliczonego od pozostałych nabyć (K_45): `setOtherAssetsTaxCorrection($tax)` / `getOtherAssetsTaxCorrection()`
- Korekta z art. 89b ust. 1 (K_46): `setTaxCorrectionArt89bu1($tax)` / `getTaxCorrectionArt89bu1()`
- Korekta z art. 89b ust. 4 (K_47): `setTaxCorrectionArt89bu4($tax)` / `getTaxCorrectionArt89bu4()`

## Deklaracja

Obiekt Declaration `($jpk->newDeclaration()` + `$jpk->setDeclaration($declaration))` reprezentuje opcjonalną część deklaracyjną VAT-7 (23). Kwoty deklaracji podaje się w pełnych złotych (są zaokrąglane przy zapisie).

Wszystkie pozycje można ustawiać i odczytywać metodami `setP10($val)`/`getP10()` … `setP69($val)`/`getP69()` (w tym `setP360()`, `setP540()`, `setP560()`, `setP660()`), mapowanymi na pola `P_10`–`P_69`. Dodatkowo:

- `setOutputTax($tax)` łączna wysokość podatku należnego (P_38, wymagane — domyślnie 0)
- `setInputTax($tax)` łączna wysokość podatku naliczonego do odliczenia (P_48)
- `setPreviousSurplus($value)` nadwyżka z poprzedniej deklaracji (P_39)
- `setTaxToPay($tax)` podatek podlegający wpłacie (P_51, wymagane — domyślnie 0)
- `setSurplus($value)` nadwyżka podatku naliczonego nad należnym (P_53)
- `setRefund($amount, $option)` zwrot nadwyżki (P_54 + wybór sposobu zwrotu); `$option` to jedna ze stałych:
    - `Declaration::REFUND_15_DAYS` (P_540)
    - `Declaration::REFUND_25_DAYS_VAT` (P_55, na rachunek VAT)
    - `Declaration::REFUND_25_DAYS` (P_56)
    - `Declaration::REFUND_40_DAYS` (P_560)
    - `Declaration::REFUND_180_DAYS` (P_58)
- `setSurplusToCarryOver($value)` nadwyżka do przeniesienia na następny okres (P_62)
- `setCorrectionReason($text)` uzasadnienie przyczyn złożenia korekty (P_ORDZU)

Element `Pouczenia` jest zawsze zapisywany z wartością `1` (akceptacja pouczeń).

# Warianty historyczne JPK_VAT (1–3)

Poniższa dokumentacja dotyczy wyłącznie wariantów `V1`/`V2`/`V3` (pliki za okresy do września 2020).

## Wiersz sprzedaży

Obiekt SellRow `($jpk->newSellRow())` zawiera metody pozwalające na zapis i pobranie danych *(pola Kxx odpowiadają numerom pól na deklaracji VAT-7(19))*:

- Nr faktury

    - `getId()`
    - `setId($id)`

- NIP kontrahenta

    - `getContractorId()`
    - `setContractorId($nip)` niewymagane w przypadku dokumentów wewnętrznych

- Nazwa kontrahenta

    - `getContractorName()`
    - `setContractorName($name)` niewymagane w przypadku dokumentów wewnętrznych

- Adres kontrahenta

    - `getContractorAddress()`
    - `setContractorAddress($address)` niewymagane w przypadku dokumentów wewnętrznych

- Data sprzedaży *(wymagana gdy jest inna niż data wystawienia)*

    - `getSellDate($format = 'Y-m-d')` `format = null` wyda obiekt `DateTime` lub `null`
    - `setSellDate($sellDate)` przyjmuje zapis daty dozwolony przez `DateTime` lub gotową instancję `DateTime`

- Data wystawienia dokumentu

    - `getIssueDate($format = 'Y-m-d')` `format = null` wyda obiekt `DateTime`
    - `setIssueDate($issueDate)` przyjmuje zapis daty dozwolony przez `DateTime` lub gotową instancję `DateTime`

VAT-7(19) C. ROZLICZENIE PODATKU NALEŻNEGO

Istnieje możliwość ręcznego podania i pobrania pozycji przez metody `getK10()` i `setK10($val)`, gdzie K10 może przyjmować wartości K10-K39. Dla ułatwienia istnieją metody pozwalające bardziej intuicyjnie zapisywać wartości dla tych pozycji:

1. Dostawa towarów oraz świadczenie usług na terytorium kraju, zwolnione od podatku (K10)

    - `getTaxExempt()`
    - `getTaxExempt($net)`

2. Dostawa towarów oraz świadczenie usług poza terytorium kraju (K11)
2a. w tym świadczenie usług, o których mowa w art. 100 ust. 1 pkt 4 ustawy (K12)

    - `getAbroadDelivery`
    - `setAbroadDelivery($net, $netA = null)` `$netA` odpowiada za 2a.

3. Dostawa towarów oraz świadczenie usług na terytorium kraju, opodatkowane stawką 0% (K13)
3a. w tym dostawa towarów, o której mowa w art. 129 ustawy (K14)

    - `getTaxD()`
    - `setTaxD($net, $netA = null)` `$netA` odpowiada za 3a.

4. Dostawa towarów oraz świadczenie usług na terytorium kraju, opodatkowane stawką 5% (K15, K16)

    - `getTaxC()`
    - `setTaxC($net, $tax)`

5. Dostawa towarów oraz świadczenie usług na terytorium kraju, opodatkowane stawką 7% albo 8% (K17, K18)

    - `getTaxB()`
    - `setTaxB($net, $tax)`

6. Dostawa towarów oraz świadczenie usług na terytorium kraju, opodatkowane stawką 22% albo 23% (K19, K20)

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

10. Import towarów podlegający rozliczeniu zgodnie z art. 33a ustawy (K25, K26)

    - `getImport_Art33a()`
    - `setImport_Art33a($net, $tax)`

11. Import usług z wyłączeniem usług nabywanych od podatników podatku od wartości dodanej, do których stosuje się art. 28b ustawy (K27, K28)

    - `getImport_Art28bExcept()`
    - `setImport_Art28bExcept($net, $tax)`

12. Import usług nabywanych od podatników podatku od wartości dodanej, do których stosuje się art. 28b ustawy (K29, K30)

    - `getImport_Art28bOnly()`
    - `setImport_Art28bOnly($net, $tax)`

13. Dostawa towarów oraz świadczenie usług, dla których podatnikiem jest nabywca zgodnie z art. 17 ust. 1 pkt 7 lub 8 ustawy (wypełnia dostawca) (K31)

    - `getReverseChargeSeller_Art17u1p7_8()`
    - `setReverseChargeSeller_Art17u1p7_8($net)`

14. Dostawa towarów, dla których podatnikiem jest nabywca zgodnie z art. 17 ust. 1 pkt 5 ustawy (wypełnia nabywca) (K32, K33)

    - `getReverseChargeBuyer_Art17u1p5()`
    - `setReverseChargeBuyer_Art17u1p5($net, $tax)`

15. Dostawa towarów oraz świadczenie usług, dla których podatnikiem jest nabywca zgodnie z art. 17 ust. 1 pkt 7 lub 8 ustawy (wypełnia nabywca) (K34, K35)

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

    - `getId()`
    - `setId($id)`

- NIP kontrahenta

    - `getContractorId()`
    - `setContractorId($nip)` niewymagane w przypadku dokumentów wewnętrznych

- Nazwa kontrahenta

    - `getContractorName()`
    - `setContractorName($name)` niewymagane w przypadku dokumentów wewnętrznych

- Adres kontrahenta

    - `getContractorAddress()`
    - `setContractorAddress($address)` niewymagane w przypadku dokumentów wewnętrznych

- Data zakupu

    - `getBuyDate($format = 'Y-m-d')` `format = null` wyda obiekt `DateTime`
    - `setBuyDate($issueDate)` przyjmuje zapis daty dozwolony przez `DateTime` lub gotową instancję `DateTime`

- Data otrzymania dokumentu *(wymagana gdy jest inna niż data zakupu)*

    - `getReceiveDate($format = 'Y-m-d')` `format = null` wyda instancję `DateTime` lub `null`
    - `setReceiveDate($sellDate)` przyjmuje zapis daty dozwolony przez `DateTime` lub gotową instancję `DateTime`

VAT-7(19) D.2 NABYCIE TOWARÓW I USŁUG ORAZ PODATEK NALICZONY Z UWZGLĘDNIENIEM KOREKT
Nabycie towarów i usług zaliczanych u podatnika do środków trwałych (K43, K44)

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
