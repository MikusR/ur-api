<?php

namespace App;

use stdClass;

class Person
{
    //private string $entity_type;
    private string $name;

    public function __construct(stdClass $data)
    {
        foreach ($data as $propertyName => $entry) {
            if (property_exists(__CLASS__, $propertyName)) {
                $this->$propertyName = $entry;
            }
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        $returnString = '';
        foreach ($this as $key => $value) {
            $returnString .= "$key: $value\n";
        }
        return $returnString;
    }
}