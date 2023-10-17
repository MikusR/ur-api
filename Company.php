<?php

class Company
{
    private string $regcode;
    private string $sepa;
    private string $name;
    private string $registered;
    private ?string $terminated;
    private string $address;
    private string $type;
    private string $type_text;
    /**
     * @var Person[]
     */
    private ?array $owner;

    public function __construct(stdClass $data, ?array $owner = null)
    {
        foreach ($data as $propertyName => $entry) {
            if (property_exists(__CLASS__, $propertyName)) {
                $this->$propertyName = $entry;
            }
            $this->owner = $owner;
        }
    }

    /**
     * @return string
     */
    public function getRegcode(): string
    {
        return $this->regcode;
    }

    /**
     * @return Person[]
     */
    public function getOwner(): array
    {
        return $this->owner;
    }

    public function __toString()
    {
        $returnString = '';
        foreach ($this as $key => $value) {
            $returnString .= "$key: $value\n";
        }
        return $returnString;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type_text;
    }

    public function toString(): string
    {
        $returnString = "regcode: {$this->getRegcode()}\n"
            . "name: {$this->getName()}\n"
            . "address: {$this->getAddress()}\n"
            . "type: {$this->getType()}\n";
        if (is_null($this->owner)) {
            return $returnString;
        }
        if (count($this->owner) === 1) {
            $returnString .= "Owner: {$this->owner[0]->getName()}\n";
        }
        if (count($this->owner) > 1) {
            $returnString .= "Owners:\n";
            foreach ($this->owner as $owner) {
                $returnString .= $owner->getName() . PHP_EOL;
            }
        }
        return $returnString;
    }
}