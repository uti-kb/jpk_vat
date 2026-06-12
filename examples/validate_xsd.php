<?php

// Pomocniczy skrypt weryfikacyjny: generuje XML (v7m_generate.php)
// i waliduje go schematem schema.xsd (wymaga dostępu do sieci -
// schemat importuje definicje z crd.gov.pl).

ob_start();
include __DIR__ . '/v7m_generate.php';
$xml = ob_get_clean();

libxml_use_internal_errors(true);

$dom = new DOMDocument();
$dom->loadXML($xml);

$xsd = str_replace('\\', '/', realpath(__DIR__ . '/../schema.xsd'));

if ($dom->schemaValidate($xsd)) {
    echo "XML poprawny względem schema.xsd\n";
    exit(0);
}

echo "Błędy walidacji:\n";
foreach (libxml_get_errors() as $error) {
    printf("  [%s] %s\n", $error->code, trim($error->message));
}
exit(1);
