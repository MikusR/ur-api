<?php

class CompanyDb
{
    /**
     * @var Company[]
     */
    private array $list = [];

    public function __construct()
    {
        if (!file_exists("register.csv")) {
            echo "register.csv missing\n" .
                "get it here: https://data.gov.lv/dati/dataset/4de9697f-850b-45ec-8bba-61fa09ce932f/resource/25e80bf3-f107-4ab4-89ef-251b5b9374e9/download/register.csv\n";
            return;
        };

        $csvFile = fopen("register.csv", "r");
        $header = fgetcsv($csvFile, 1000, ";");
        while (($data = fgetcsv($csvFile, 1000, ";")) !== false) {
            $parameters = new stdClass();
            foreach ($header as $key => $value) {
                $parameters->$value = $data[$key];
            }
            $this->list[$parameters->regcode] = new Company($parameters);
        }
    }


    public function getCompany(string $regcode): ?Company
    {
        if (!array_key_exists($regcode, $this->list)) {
            return null;
        }
        return $this->list[$regcode];
    }

    /**
     * @param string $name
     * @return Company[]
     */
    public function search(string $name): array
    {
        $results = [];
        foreach ($this->list as $company) {
            if (stripos($company->getName(), $name)) {
                $results[] = $company;
            }
        }
        return $results;
    }
}