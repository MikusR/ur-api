<?php

class Application
{
    public function run(): void
    {
        while (true) {
            echo "1 search for company by name or registration number\n";
            echo "any other key: exit\n";
            $choice = (int)readline();
            $choice = 1;
            switch ($choice) {
                case 1:
                    $searchTerm = readline("Enter name or registration code of company ");
                    $result = $this->searchCompany($searchTerm);
                    $this->displayResults($result);
                    break;
                default:
                    exit;
            }
        }
    }

    public function searchCompany(string $searchTerm): array
    {
        $apiUrl = 'https://data.gov.lv/api/3/action/datastore_search';
        $resource_id = '25e80bf3-f107-4ab4-89ef-251b5b9374e9';
        $response = json_decode(file_get_contents("$apiUrl?q={$searchTerm}&resource_id={$resource_id}"));
        if ($response->success === 'false') {
            return [];
        }
        $result = [];
        foreach ($response->result->records as $record) {
            $owners = $this->searchPerson($record->regcode);
            $result[] = new Company($record, $owners);
        }
        return $result;
    }

    public function searchPerson(string $searchTerm): array
    {
        $apiUrl = 'https://data.gov.lv/api/3/action/datastore_search';
        $resource_id = '837b451a-4833-4fd1-bfdd-b45b35a994fd';
        $response = json_decode(file_get_contents("$apiUrl?q={$searchTerm}&resource_id={$resource_id}"));
        if ($response->success === 'false') {
            return [];
        }
        $result = [];
        foreach ($response->result->records as $record) {
            $result[] = new Person($record);
        }
        return $result;
    }

    public function displayResults(array $results): void
    {
        if (count($results) === 0) {
            echo "No results\n";
            return;
        }
        echo count($results) . " results found\n";
        /**
         * @var Company $result
         */
        foreach ($results as $result) {
            echo str_repeat('-', 20) . PHP_EOL;
            echo $result->toString();
        }
    }
}