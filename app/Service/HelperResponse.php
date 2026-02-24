<?php

namespace App\Service;

class HelperResponse
{
    public static function success($data, $message = 'Success', $code = 200, $status = true)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data
        ], $code);
    }

    public static function error($data = null, $message = 'Error', $code = 400, $status = false)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data
        ], $code);
    }
}