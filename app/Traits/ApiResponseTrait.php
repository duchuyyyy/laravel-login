<?php

namespace App\Traits;

use App\Http\Common\CommonConstant;

trait ApiResponseTrait
{
    public function successWithData($data)
    {
        return response()->json([
            'status' => CommonConstant::SUCCESS,
            'data' => $data
        ], 200);
    }

    public function failedWithData($data)
    {

        return response()->json([
            'status' => CommonConstant::FAILURE,
            'data' => $data
        ], 500);
    }
}
