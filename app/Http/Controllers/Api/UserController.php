<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function read(): array
    {
        return [
            'success' => true,
            'data' => [
                'user' => [
                    'isAdmin' => Auth::user()->isAdmin()
                ]
            ]
        ];
    }
}
