<?php

class Application
{

    private CompanyDb $db;
    private bool $dbCreated = false;

    public function run(): void
    {
        while (true) {
            echo "\n";
            echo "1 search for company by name or registration number using api\n";
            echo "2 search for company by registration number\n";
            echo "3 search for company by name\n";
            echo "4 build company database\n";
            echo "any other key: exit\n";
            $choice = (int)readline();


            switch ($choice) {
                case 1:
                    $searchTerm = readline("Enter name or registration code of company ");
                    $result = $this->searchCompanyApi($searchTerm);
                    $this->displayResults($result);
                    break;
                case 2:
                    if (!$this->dbCreated) {
                        echo "First create a DB\n";
                        break;
                    }
                    $searchTerm = readline("Enter registration code of company (\"ex: 42103088167\") ");
                    $result = $this->db->getCompany($searchTerm);
                    if (is_null($result)) {
                        echo "not found\n";
                        break;
                    }
                    echo $result->toString();
                    
                    break;
                case 3:
                    if (!$this->dbCreated) {
                        echo "First create a DB\n";
                        break;
                    }
                    $searchTerm = readline("Enter registration code of company (\"ex: codelex\") ");
                    $result = $this->db->search($searchTerm);
                    $this->displayResults($result);
                    break;
                case 4:
                    if (!$this->dbCreated) {
                        $start = microtime(true);
                        $this->db = new CompanyDb();

                        $end = microtime(true);
                        $elapsed = $end - $start;
                        echo "import took $elapsed seconds\n";

                        $this->dbCreated = true;
                        break;
                    }
                    echo "DB already created.\n";
                    break;
                default:
                    exit;
            }
        }
    }


    public function searchCompanyApi(string $searchTerm): array
    {
        $apiUrl = 'https://data.gov.lv/api/3/action/datastore_search';
        $resource_id = '25e80bf3-f107-4ab4-89ef-251b5b9374e9';
        $response = json_decode(file_get_contents("$apiUrl?q={$searchTerm}&resource_id={$resource_id}"));
        if ($response->success === 'false') {
            return [];
        }
        $result = [];
        foreach ($response->result->records as $record) {
            $owners = $this->searchPersonApi($record->regcode);
            $result[] = new Company($record, $owners);
        }
        return $result;
    }

    public function searchPersonApi(string $searchTerm): array
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