<?php

namespace App\Interfaces;

interface DataProviderInterface
{
    public function readData(string $filePath);
}
