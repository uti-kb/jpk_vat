<?php

namespace SJRoyd\JPK\VAT\V7M;

use Sabre\Xml\Reader;
use Sabre\Xml\Writer;
use Sabre\Xml\XmlDeserializable;
use Sabre\Xml\XmlSerializable;
use SJRoyd\JPK\VAT\Helper;

/**
 * Podmiot1 - the taxpayer, either a company (OsobaNiefizyczna)
 * or a natural person (OsobaFizyczna)
 */
class Company implements XmlSerializable, XmlDeserializable
{

    /**
     * @var string
     */
    protected $nip;

    /**
     * PelnaNazwa (OsobaNiefizyczna)
     * @var string
     */
    protected $name;

    /**
     * ImiePierwsze (OsobaFizyczna)
     * @var string
     */
    protected $firstName;

    /**
     * Nazwisko (OsobaFizyczna)
     * @var string
     */
    protected $lastName;

    /**
     * DataUrodzenia (OsobaFizyczna)
     * @var \DateTime
     */
    protected $birthDate;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @return string
     */
    public function getNip()
    {
        return $this->nip;
    }

    /**
     * @param string $nip
     * @return Company
     */
    public function setNip($nip)
    {
        $this->nip = $nip;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the full name - the subject becomes OsobaNiefizyczna
     * @param string $name
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->firstName = $this->lastName = $this->birthDate = null;
        return $this;
    }

    /**
     * @return array|null [firstName, lastName, birthDate]
     */
    public function getPerson()
    {
        return $this->firstName
            ? [$this->firstName, $this->lastName, $this->birthDate]
            : null;
    }

    /**
     * Sets the personal data - the subject becomes OsobaFizyczna
     * @param string $firstName
     * @param string $lastName
     * @param \DateTime|string $birthDate
     * @return Company
     */
    public function setPerson($firstName, $lastName, $birthDate)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate instanceof \DateTime
                ? $birthDate : new \DateTime($birthDate);
        $this->name = null;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Company
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    protected function validate()
    {
        if (!$this->nip) {
            throw new \InvalidArgumentException('Missing NIP');
        }

        if (!$this->name && !$this->firstName) {
            throw new \InvalidArgumentException('Missing subject name - use setName() or setPerson()');
        }

        if (!$this->email) {
            throw new \InvalidArgumentException('Missing email');
        }
    }

    /**
     * The xmlSerialize method is called during xml writing.
     *
     * @param Writer $writer
     * @return void
     */
    public function xmlSerialize(Writer $writer): void
    {
        $this->validate();

        if ($this->firstName) {
            $person = [
                Schema::getFullNS('ETD') . 'NIP'           => $this->nip,
                Schema::getFullNS('ETD') . 'ImiePierwsze'  => $this->firstName,
                Schema::getFullNS('ETD') . 'Nazwisko'      => $this->lastName,
                Schema::getFullNS('ETD') . 'DataUrodzenia' => $this->birthDate->format('Y-m-d'),
                Schema::getFullNS('TNS') . 'Email'         => $this->email,
            ];
            $this->phone && $person[Schema::getFullNS('TNS') . 'Telefon'] = $this->phone;

            $writer->write([
                Schema::getFullNS('TNS') . 'OsobaFizyczna' => $person
            ]);
        } else {
            $company = [
                Schema::getFullNS('TNS') . 'NIP'        => $this->nip,
                Schema::getFullNS('TNS') . 'PelnaNazwa' => $this->name,
                Schema::getFullNS('TNS') . 'Email'      => $this->email,
            ];
            $this->phone && $company[Schema::getFullNS('TNS') . 'Telefon'] = $this->phone;

            $writer->write([
                Schema::getFullNS('TNS') . 'OsobaNiefizyczna' => $company
            ]);
        }
    }

    /**
     * The deserialize method is called during xml parsing.
     *
     * @return mixed
     */
    public static function xmlDeserialize(Reader $reader)
    {
        $children = $reader->parseInnerTree([
            Schema::TNS.'OsobaFizyczna'    => 'Sabre\Xml\Element\KeyValue',
            Schema::TNS.'OsobaNiefizyczna' => 'Sabre\Xml\Element\KeyValue',
        ]);

        $object = new self();
        foreach ((array) $children as $child) {
            $inner = $child['value'];
            if ($child['name'] == Schema::TNS.'OsobaFizyczna') {
                $object->nip       = $inner[Schema::ETD.'NIP'];
                $object->firstName = $inner[Schema::ETD.'ImiePierwsze'];
                $object->lastName  = $inner[Schema::ETD.'Nazwisko'];
                $object->birthDate = Helper\array_get($inner, Schema::ETD.'DataUrodzenia', '\DateTime');
                $object->email     = $inner[Schema::TNS.'Email'];
                $object->phone     = Helper\array_get($inner, Schema::TNS.'Telefon');
            } elseif ($child['name'] == Schema::TNS.'OsobaNiefizyczna') {
                $object->nip   = $inner[Schema::TNS.'NIP'];
                $object->name  = $inner[Schema::TNS.'PelnaNazwa'];
                $object->email = $inner[Schema::TNS.'Email'];
                $object->phone = Helper\array_get($inner, Schema::TNS.'Telefon');
            }
        }
        return $object;
    }

}
