<?php

class Company
{
    private string $regcode;
    private string $sepa;
    private string $name;
    private string $registered;
    private string $terminated;
    private string $address;
    private string $type;
    private string $type_text;

    public function __construct(stdClass $data)
    {
        foreach ($data as $propertyName => $entry) {
            if (property_exists(__CLASS__, $propertyName)) {
                $this->$propertyName = $entry;
            }
        }
    }


    public function __toString()
    {
        $returnString = '';
        foreach ($this as $key => $value) {
            $returnString .= "$key: $value\n";
        }
        return $returnString;
    }
}