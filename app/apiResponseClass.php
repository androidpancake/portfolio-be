<?php

namespace App;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class apiResponseClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function sendResponse($result, $message, $code)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    public static function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public static function throw($e, $message = 'Something went wrong')
    {
        Log::info($e);
        throw new HttpResponseException(response()->json(['message' => $message], 500));
    }

    public static function rollback($e, $message = 'Something wrong, process not completed')
    {
        DB::rollBack();
        self::throw($e, $message);
    }
}
