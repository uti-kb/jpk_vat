<?php

// Pomocniczy test: generuje XML JPK_V7M, parsuje go z powrotem
// i porównuje kluczowe wartości.

ob_start();
include __DIR__ . '/v7m_generate.php';
$xml = ob_get_clean();

use SJRoyd\JPK\VAT\JPK;

$jpk = JPK::parse($xml);

$check = function($label, $expected, $actual) {
    $ok = $expected == $actual;
    printf("[%s] %s: %s\n", $ok ? 'OK' : 'FAIL', $label, var_export($actual, true));
    return $ok;
};

$pass = true;
$pass &= $check('klasa', 'SJRoyd\JPK\VAT\V7M\JPK', get_class($jpk));
$pass &= $check('rok', 2026, $jpk->header->getYear());
$pass &= $check('miesiac', 2, $jpk->header->getMonth());
$pass &= $check('urzad', '0202', $jpk->header->getOfficeCode());
$pass &= $check('firma NIP', '4165741358', $jpk->company->getNip());
$pass &= $check('firma nazwa', 'Moja Firma', $jpk->company->getName());
$pass &= $check('firma telefon', '+48600700800', $jpk->company->getPhone());
$pass &= $check('deklaracja P_38', 234, $jpk->declaration->getP38());
$pass &= $check('deklaracja P_51', 169, $jpk->declaration->getP51());
$pass &= $check('liczba sprzedazy', 2, count($jpk->sellRows));
$pass &= $check('liczba zakupow', 2, count($jpk->buyRows));
$pass &= $check('sprzedaz 1 NrKSeF', '4165741358-20260210-0A0B0C-D0E0F0-01', $jpk->sellRows[0]->getKsefNumber());
$pass &= $check('sprzedaz 1 GTU', [6, 12], $jpk->sellRows[0]->getGTU());
$pass &= $check('sprzedaz 1 procedury', ['TP'], $jpk->sellRows[0]->getProcedures());
$pass &= $check('sprzedaz 1 K_19/K_20', [100.00, 23.00], $jpk->sellRows[0]->getTaxA());
$pass &= $check('sprzedaz 2 DI', true, $jpk->sellRows[0]->isOtherDocument() === false && $jpk->sellRows[1]->isOtherDocument());
$pass &= $check('sprzedaz 2 TypDokumentu', 'RO', $jpk->sellRows[1]->getDocumentType());
$pass &= $check('zakup 1 NrKSeF', '4687135510-20260212-010203-ABCDEF-04', $jpk->buyRows[0]->getKsefNumber());
$pass &= $check('zakup 1 K_40/K_41', [150.42, 24.12], $jpk->buyRows[0]->getFixedAssets());
$pass &= $check('zakup 2 BFK', true, $jpk->buyRows[1]->isNonKsefInvoice());
$pass &= $check('zakup 2 DataWplywu', '2026-02-10', $jpk->buyRows[1]->getReceiveDate());

echo $pass ? "ROUND-TRIP OK\n" : "ROUND-TRIP FAILED\n";
exit($pass ? 0 : 1);
