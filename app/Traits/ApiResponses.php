<?php

namespace App\Traits;

trait ApiResponses
{
    protected function success ($message, $data, $statusCode = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function error ($message, $statusCode)
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    protected function loggedin ($message, $user, $token, $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'authorisation' => [
                'access_token' => $token,
                'token_type' => 'bearer'
            ],
            'user' => $user,
            'status' => $statusCode
        ]);
    }
}
