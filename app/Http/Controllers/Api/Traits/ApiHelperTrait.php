<?php

namespace App\Http\Controllers\Api\Traits;


trait ApiHelperTrait
{
    // Start Our Methods
    public function apiResponse($success, $message, $data = null, $status = 200)
    {
        return response()->json([
            'success'   => $success,
            'message'   => $message,
            'data'      => $data, 
        ], $status);
    }
}