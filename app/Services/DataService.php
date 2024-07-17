<?php

// app/Services/DataService.php
namespace App\Services;

use App\Interfaces\DataProviderInterface;

class DataService
{
    private $dataProviders;

    public function __construct(DataProviderX $dataProviderX, DataProviderY $dataProviderY)
    {
        $this->dataProviders = [
            'DataProviderX' => $dataProviderX,
            'DataProviderY' => $dataProviderY,
        ];
    }

    public function getAllData(): iterable
    {
        $data = [];
        foreach ($this->dataProviders as $provider => $dataProvider) {
            $filePath = storage_path("{$provider}.json");
            $data = array_merge($data, iterator_to_array($dataProvider->readData($filePath)));
        }
        return $data;
    }

    public function filterData(array $data, array $filters): array
    {
        return array_filter($data, function ($item) use ($filters) {
            foreach ($filters as $key => $value) {
              if ($key == 'balanceMin'){
                    if($item['amount'] < $value){
                        return false;
                    }
                } elseif ($key == 'balanceMax'){
                    if($item['amount'] > $value){
                        return false;
                    }
            } elseif ($item[$key] != $value) {
                    return false;
                }
            }
            return true;
        });
    }
}

