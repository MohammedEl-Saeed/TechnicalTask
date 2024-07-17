<?php

// app/Services/DataProviderY.php
namespace App\Services;

use App\Interfaces\DataProviderInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DataProviderY implements DataProviderInterface
{

//    private $statusMapping = [
//        100 => 'authorised',
//        200 => 'decline',
//        300 => 'refunded',
//    ];

    public function readData(string $filePath): iterable
    {
        if (!File::exists($filePath)) {
            return [];
        }

        $result = [];

        $lines = File::lines($filePath)->chunk(100)->toArray();

        foreach ($lines as $chunk) {
            foreach ($chunk as $line) {
                $line = str_replace('[{', '{', $line);
                $line = str_replace('},', '}', $line);
                $line = str_replace('}]', '}', $line);
                $item = json_decode($line, true);
                if (is_array($item)) {
                    yield [
                        'amount' => $item['balance'] ?? 0,
                        'currency' => $item['currency'] ?? 'Unknown',
                        'email' => $item['email'] ?? 'Unknown',
                        'statusCode' => $this->mapStatus($item['status'] ?? 0),
                        'date' => $item['created_at'] ?? 'Unknown',
                        'id' => $item['id'] ?? 'Unknown',
                        'provider' => 'DataProviderY',
                    ];
                }
            }
        }
    }

    private function mapStatus(int $status): string
    {
        return match ($status) {
            100 => 'authorised',
            200 => 'decline',
            300 => 'refunded',
            default => 'unknown',
        };
    }
}
