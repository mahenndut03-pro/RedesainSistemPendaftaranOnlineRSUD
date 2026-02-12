<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateBpjsRequest;
use Illuminate\Http\JsonResponse;

class BpjsController extends Controller
{
    /**
     * Store a new BPJS number (example).
     *
     * @param  ValidateBpjsRequest  $request
     * @return JsonResponse
     */
    public function store(ValidateBpjsRequest $request)
    {
        $validated = $request->validated();

        return response()->json([
            'message' => 'No BPJS valid.',
            'data' => $validated,
        ], 200);
    }
}
