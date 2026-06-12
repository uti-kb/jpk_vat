<?php

namespace SJRoyd\JPK\VAT\V7M;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;

/**
 * Deklaracja - VAT-7 (23) declaration part of JPK_V7M
 *
 * Amounts are declared in full zlotys (etd:TKwotaC).
 *
 * @method Declaration setP10(float $value)
 * @method float getP10()
 * @method Declaration setP11(float $value)
 * @method float getP11()
 * @method Declaration setP12(float $value)
 * @method float getP12()
 * @method Declaration setP13(float $value)
 * @method float getP13()
 * @method Declaration setP14(float $value)
 * @method float getP14()
 * @method Declaration setP15(float $value)
 * @method float getP15()
 * @method Declaration setP16(float $value)
 * @method float getP16()
 * @method Declaration setP17(float $value)
 * @method float getP17()
 * @method Declaration setP18(float $value)
 * @method float getP18()
 * @method Declaration setP19(float $value)
 * @method float getP19()
 * @method Declaration setP20(float $value)
 * @method float getP20()
 * @method Declaration setP21(float $value)
 * @method float getP21()
 * @method Declaration setP22(float $value)
 * @method float getP22()
 * @method Declaration setP23(float $value)
 * @method float getP23()
 * @method Declaration setP24(float $value)
 * @method float getP24()
 * @method Declaration setP25(float $value)
 * @method float getP25()
 * @method Declaration setP26(float $value)
 * @method float getP26()
 * @method Declaration setP27(float $value)
 * @method float getP27()
 * @method Declaration setP28(float $value)
 * @method float getP28()
 * @method Declaration setP29(float $value)
 * @method float getP29()
 * @method Declaration setP30(float $value)
 * @method float getP30()
 * @method Declaration setP31(float $value)
 * @method float getP31()
 * @method Declaration setP32(float $value)
 * @method float getP32()
 * @method Declaration setP33(float $value)
 * @method float getP33()
 * @method Declaration setP34(float $value)
 * @method float getP34()
 * @method Declaration setP35(float $value)
 * @method float getP35()
 * @method Declaration setP36(float $value)
 * @method float getP36()
 * @method Declaration setP360(float $value)
 * @method float getP360()
 * @method Declaration setP37(float $value)
 * @method float getP37()
 * @method Declaration setP38(float $value)
 * @method float getP38()
 * @method Declaration setP39(float $value)
 * @method float getP39()
 * @method Declaration setP40(float $value)
 * @method float getP40()
 * @method Declaration setP41(float $value)
 * @method float getP41()
 * @method Declaration setP42(float $value)
 * @method float getP42()
 * @method Declaration setP43(float $value)
 * @method float getP43()
 * @method Declaration setP44(float $value)
 * @method float getP44()
 * @method Declaration setP45(float $value)
 * @method float getP45()
 * @method Declaration setP46(float $value)
 * @method float getP46()
 * @method Declaration setP47(float $value)
 * @method float getP47()
 * @method Declaration setP48(float $value)
 * @method float getP48()
 * @method Declaration setP49(float $value)
 * @method float getP49()
 * @method Declaration setP50(float $value)
 * @method float getP50()
 * @method Declaration setP51(float $value)
 * @method float getP51()
 * @method Declaration setP52(float $value)
 * @method float getP52()
 * @method Declaration setP53(float $value)
 * @method float getP53()
 * @method Declaration setP54(float $value)
 * @method float getP54()
 * @method Declaration setP540(int $value)
 * @method int getP540()
 * @method Declaration setP55(int $value)
 * @method int getP55()
 * @method Declaration setP56(int $value)
 * @method int getP56()
 * @method Declaration setP560(int $value)
 * @method int getP560()
 * @method Declaration setP58(int $value)
 * @method int getP58()
 * @method Declaration setP59(int $value)
 * @method int getP59()
 * @method Declaration setP60(float $value)
 * @method float getP60()
 * @method Declaration setP61(string $value)
 * @method string getP61()
 * @method Declaration setP62(float $value)
 * @method float getP62()
 * @method Declaration setP63(int $value)
 * @method int getP63()
 * @method Declaration setP64(int $value)
 * @method int getP64()
 * @method Declaration setP65(int $value)
 * @method int getP65()
 * @method Declaration setP66(int $value)
 * @method int getP66()
 * @method Declaration setP660(int $value)
 * @method int getP660()
 * @method Declaration setP67(int $value)
 * @method int getP67()
 * @method Declaration setP68(float $value)
 * @method float getP68()
 * @method Declaration setP69(float $value)
 * @method float getP69()
 */
class Declaration implements XmlSerializable, XmlDeserializable
{
    const REFUND_15_DAYS       = 'P_540';
    const REFUND_25_DAYS_VAT   = 'P_55';
    const REFUND_25_DAYS       = 'P_56';
    const REFUND_40_DAYS       = 'P_560';
    const REFUND_180_DAYS      = 'P_58';

    /**
     * Fields of PozycjeSzczegolowe in the schema order
     * @var array
     */
    protected $fields = [
        'P_10' => null, 'P_11' => null, 'P_12' => null, 'P_13' => null,
        'P_14' => null, 'P_15' => null, 'P_16' => null, 'P_17' => null,
        'P_18' => null, 'P_19' => null, 'P_20' => null, 'P_21' => null,
        'P_22' => null, 'P_23' => null, 'P_24' => null, 'P_25' => null,
        'P_26' => null, 'P_27' => null, 'P_28' => null, 'P_29' => null,
        'P_30' => null, 'P_31' => null, 'P_32' => null, 'P_33' => null,
        'P_34' => null, 'P_35' => null, 'P_36' => null, 'P_360' => null,
        'P_37' => null, 'P_38' => null, 'P_39' => null, 'P_40' => null,
        'P_41' => null, 'P_42' => null, 'P_43' => null, 'P_44' => null,
        'P_45' => null, 'P_46' => null, 'P_47' => null, 'P_48' => null,
        'P_49' => null, 'P_50' => null, 'P_51' => null, 'P_52' => null,
        'P_53' => null, 'P_54' => null, 'P_540' => null, 'P_55' => null,
        'P_56' => null, 'P_560' => null, 'P_58' => null, 'P_59' => null,
        'P_60' => null, 'P_61' => null, 'P_62' => null, 'P_63' => null,
        'P_64' => null, 'P_65' => null, 'P_66' => null, 'P_660' => null,
        'P_67' => null, 'P_68' => null, 'P_69' => null, 'P_ORDZU' => null,
    ];

    /**
     * Text fields - written without number formatting
     * @var array
     */
    protected static $textFields = ['P_61', 'P_ORDZU'];

    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @param array $values
     * @return Declaration|float|string|null
     * @throws \InvalidArgumentException
     */
    public function __call($name, $values)
    {
        if (!preg_match('~^(set|get)P(\d+)$~', $name, $m)) {
            throw new \InvalidArgumentException("Method {$name} not exists");
        }
        $field = 'P_'.$m[2];
        if (!array_key_exists($field, $this->fields)) {
            throw new \InvalidArgumentException("Field {$field} not exists");
        }

        if ($m[1] == 'set') {
            $this->fields[$field] = $values[0];
            return $this;
        }

        return $this->fields[$field];
    }

    /**
     * P_38 - total output tax
     * @param float $tax
     * @return Declaration
     */
    public function setOutputTax($tax)
    {
        return $this->setP38($tax);
    }

    /**
     * P_48 - total input tax to deduct
     * @param float $tax
     * @return Declaration
     */
    public function setInputTax($tax)
    {
        return $this->setP48($tax);
    }

    /**
     * P_39 - surplus of input tax from the previous declaration
     * @param float $value
     * @return Declaration
     */
    public function setPreviousSurplus($value)
    {
        return $this->setP39($value);
    }

    /**
     * P_51 - tax amount to be paid to the tax office
     * @param float $tax
     * @return Declaration
     */
    public function setTaxToPay($tax)
    {
        return $this->setP51($tax);
    }

    /**
     * P_53 - surplus of input tax over output tax
     * @param float $value
     * @return Declaration
     */
    public function setSurplus($value)
    {
        return $this->setP53($value);
    }

    /**
     * P_54 + refund method choice (P_540|P_55|P_56|P_560|P_58)
     * @param float $amount
     * @param string $option one of the REFUND_* constants
     * @return Declaration
     * @throws \InvalidArgumentException
     */
    public function setRefund($amount, $option)
    {
        if (!in_array($option, [
            self::REFUND_15_DAYS,
            self::REFUND_25_DAYS_VAT,
            self::REFUND_25_DAYS,
            self::REFUND_40_DAYS,
            self::REFUND_180_DAYS,
        ])) {
            throw new \InvalidArgumentException("Incorrect refund option: {$option}");
        }

        $this->fields['P_54'] = $amount;
        foreach (['P_540', 'P_55', 'P_56', 'P_560', 'P_58'] as $field) {
            $this->fields[$field] = $field == $option ? 1 : null;
        }
        return $this;
    }

    /**
     * P_62 - surplus to carry over to the next settlement period
     * @param float $value
     * @return Declaration
     */
    public function setSurplusToCarryOver($value)
    {
        return $this->setP62($value);
    }

    /**
     * P_ORDZU - justification of the correction
     * @return string
     */
    public function getCorrectionReason()
    {
        return $this->fields['P_ORDZU'];
    }

    /**
     * P_ORDZU - justification of the correction
     * @param string $text
     * @return Declaration
     */
    public function setCorrectionReason($text)
    {
        $this->fields['P_ORDZU'] = $text;
        return $this;
    }

    protected function validate()
    {
        if (is_null($this->fields['P_38'])) {
            $this->fields['P_38'] = 0;
        }

        if (is_null($this->fields['P_51'])) {
            $this->fields['P_51'] = 0;
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $this->validate();

        $writer->write([
            Schema::getFullNS('TNS') . 'Naglowek' => [
                [
                    'name' => Schema::getFullNS('TNS') . 'KodFormularzaDekl',
                    'value' => 'VAT-7',
                    'attributes' => [
                        'kodSystemowy' => 'VAT-7 (23)',
                        'kodPodatku' => 'VAT',
                        'rodzajZobowiazania' => 'Z',
                        'wersjaSchemy' => '1-0E',
                    ]
                ],
                Schema::getFullNS('TNS') . 'WariantFormularzaDekl' => 23,
            ]
        ]);

        $fields = array_filter($this->fields, function($val){
            return $val !== null;
        });

        $items = [];
        foreach ($fields as $field => $value) {
            $items[Schema::getFullNS('TNS') . $field] = in_array($field, self::$textFields)
                ? $value
                : round($value);
        }

        $writer->write([
            Schema::getFullNS('TNS') . 'PozycjeSzczegolowe' => $items,
            Schema::getFullNS('TNS') . 'Pouczenia' => 1,
        ]);
    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * @return mixed
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $children = $reader->parseInnerTree([
            Schema::TNS.'Naglowek'           => 'Sabre\Xml\Element\KeyValue',
            Schema::TNS.'PozycjeSzczegolowe' => 'Sabre\Xml\Element\KeyValue',
        ]);

        $object = new self();
        foreach ((array) $children as $child) {
            if ($child['name'] == Schema::TNS.'PozycjeSzczegolowe') {
                foreach ($object->fields as $field => $_unused) {
                    $object->fields[$field] = Helper\array_get($child['value'], Schema::TNS.$field);
                }
            }
        }
        return $object;
    }

}
