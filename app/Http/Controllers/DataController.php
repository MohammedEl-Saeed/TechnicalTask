<?php

// app/Http/Controllers/DataController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataService;

class DataController extends Controller
{
    private $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['provider', 'statusCode', 'balanceMin', 'balanceMax', 'currency']);

        $data = $this->dataService->getAllData();
        $filteredData = $this->dataService->filterData($data, $filters);

        return response()->json(array_values($filteredData));
    }
}
