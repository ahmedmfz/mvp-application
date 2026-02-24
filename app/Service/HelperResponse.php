<?php

namespace App\Service;

class HelperResponse
{
    public static function success($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error($message, $code = 400)
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }
}