<?php

namespace App\Services;

use App\Interfaces\DataProviderInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DataProviderX implements DataProviderInterface
{

//    public $statusMapping = [
//        1 => 'authorised',
//        2 => 'decline',
//        3 => 'refunded'
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
                        'amount' => $item['parentAmount'] ?? 0,
                        'currency' => $item['Currency'] ?? 'Unknown',
                        'email' => $item['parentEmail'] ?? 'Unknown',
                        'statusCode' => $this->mapStatus($item['statusCode'] ?? 0),
                        'date' => $item['registerationDate'] ?? 'Unknown',
                        'id' => $item['parentIdentification'] ?? 'Unknown',
                        'provider' => 'DataProviderX',
                    ];
                }
            }
        }

//        return $result;
    }

    private function mapStatus(int $statusCode): string
    {
        return match ($statusCode) {
            1 => 'authorised',
            2 => 'decline',
            3 => 'refunded'
        };
    }
}
