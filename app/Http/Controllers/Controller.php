<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function sendErrors($message, $errors)
    {
        $data = [
            'message' => $message,
            'errors' => $errors,
            'status' => 400
        ];
        return response()->json($data, 400);
    }
}
