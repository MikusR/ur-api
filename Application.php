<?php

class Application
{
    public function run(): void
    {
        $result = $this->search('codelex');
        if (!$result) {
            echo 'nothing';
            die;
        }
        if (count($result->result->records) === 0) {
            echo 'nothing';
            die;
        }

        $test = new Company($result->result->records[0]);
        echo $test;
    }

    public function search(string $searchTerm, string $type = 'organization'): ?object
    {
        $apiUrl = 'https://data.gov.lv/api/3/action/datastore_search';
        $resource_id = '25e80bf3-f107-4ab4-89ef-251b5b9374e9';
        if ($type === 'person') {
            $resource_id = '837b451a-4833-4fd1-bfdd-b45b35a994fd';
        }
        $response = json_decode(file_get_contents("$apiUrl?q={$searchTerm}&resource_id={$resource_id}"));
        if ($response->success === 'false') {
            return null;
        }
        return $response;
    }
}